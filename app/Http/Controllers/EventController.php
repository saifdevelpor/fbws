<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMedia;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    private function audit(string $event, string $action, ?int $auditableId, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'Events',
            'action' => $action,
            'auditable_type' => Event::class,
            'auditable_id' => $auditableId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    private function deletePublicFile(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }
        $full = public_path($relativePath);
        if (is_file($full)) {
            unlink($full);
        }
    }

    private function mediaFileRules(): array
    {
        return [
            'nullable',
            'array',
        ];
    }

    private function mediaItemRules(): array
    {
        return [
            'file',
            'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime',
            'max:51200',
        ];
    }

    private function storeEventMediaFiles(Event $event, ?array $files): void
    {
        if (!$files) {
            return;
        }

        $maxSort = (int) $event->media()->max('sort_order');
        $sort = $maxSort;
        $dest = public_path('uploads/events/media');
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        foreach ($files as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }
            $sort++;
            $mime = $file->getMimeType();
            $type = $mime && str_starts_with($mime, 'video/') ? 'video' : 'image';
            $name = time() . '_' . uniqid('', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($dest, $name);
            EventMedia::create([
                'event_id' => $event->id,
                'type' => $type,
                'path' => 'uploads/events/media/' . $name,
                'sort_order' => $sort,
            ]);
        }
    }

    private function syncEventLegacyImage(Event $event): void
    {
        $event->load('media');
        $firstImg = $event->media->sortBy(fn ($m) => [$m->sort_order, $m->id])->firstWhere('type', 'image');
        $event->image = $firstImg?->path;
        $event->saveQuietly();
    }

    public function index()
    {
         if (Auth::user()->role !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized: Only Admin can See Event Page.');
        }

        $events = Event::with('media')->latest()->get();

        return view('event.list', compact('events'));
    }

    public function save(Request $request)
    {
         if (Auth::user()->role !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized: Only Admin can See Event Page.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media' => $this->mediaFileRules(),
            'media.*' => $this->mediaItemRules(),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $event = new Event();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->save();

        $this->storeEventMediaFiles($event, $request->file('media'));
        $event->refresh();
        $this->syncEventLegacyImage($event);

        $this->audit('created', 'Event Created', $event->id, null, $event->fresh()->toArray());

        return redirect()->back()->with('success', 'Event saved successfully!');
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $event = Event::with('media')->findOrFail($id);
        $old = $event->toArray();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media' => $this->mediaFileRules(),
            'media.*' => $this->mediaItemRules(),
            'remove_media_ids' => 'nullable|array',
            'remove_media_ids.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $event->name = $request->name;
        $event->description = $request->description;

        $removeIds = array_filter(array_map('intval', (array) $request->input('remove_media_ids', [])));
        if ($removeIds !== []) {
            $toRemove = $event->media()->whereIn('id', $removeIds)->get();
            foreach ($toRemove as $m) {
                $this->deletePublicFile($m->path);
                $m->delete();
            }
        }

        $this->storeEventMediaFiles($event, $request->file('media'));
        $event->refresh();
        $this->syncEventLegacyImage($event);

        $this->audit('updated', 'Event Updated', $event->id, $old, $event->fresh()->toArray());

        return redirect()->back()->with('success', 'Event updated successfully!');
    }

    public function delete($id)
    {
        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $event = Event::with('media')->findOrFail($id);
        $old = $event->toArray();
        $eventId = $event->id;

        $paths = $event->media->pluck('path')->all();
        foreach ($paths as $p) {
            $this->deletePublicFile($p);
        }
        if ($event->image && !in_array($event->image, $paths, true)) {
            $this->deletePublicFile($event->image);
        }

        $event->delete();

        $this->audit('deleted', 'Event Deleted', $eventId, $old, null);

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}
