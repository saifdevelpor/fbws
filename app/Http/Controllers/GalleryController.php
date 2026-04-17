<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\AuditLog; // ✅ ADD
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    private function isAdmin($user): bool
    {
        return strtolower($user->role ?? '') === 'admin';
    }

    private function canManage(GalleryImage $image): bool
    {
        $user = auth()->user();
        return $this->isAdmin($user) || (int)$image->uploaded_by === (int)$user->id;
    }

    private function audit(string $event, string $action, ?int $auditableId, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'Gallery',
            'action' => $action,
            'auditable_type' => GalleryImage::class,
            'auditable_id' => $auditableId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    private static function mediaMimeRule(): array
    {
        return [
            'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime',
            'max:51200',
        ];
    }

    private static function mediaTypeFromMime(?string $mime): string
    {
        if ($mime && str_starts_with($mime, 'video/')) {
            return 'video';
        }

        return 'image';
    }

    public function index()
    {
        $user = auth()->user();

        if ($this->isAdmin($user)) {
            $images = GalleryImage::with(['user:id,name,profile_photo'])->latest()->get(); // Admin → all images
        } else {
            $images = GalleryImage::with(['user:id,name,profile_photo'])->where('uploaded_by', $user->id)->latest()->get(); // User → only own
        }

        return view('gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'media' => ['required', 'array', 'min:1'],
            'media.*' => array_merge(['file'], self::mediaMimeRule()),
        ]);

        foreach ($request->file('media') as $file) {
            $path = $file->store('gallery', 'public');
            $mediaType = self::mediaTypeFromMime($file->getMimeType());

            $img = GalleryImage::create([
                'title' => $request->title,
                'path' => $path,
                'media_type' => $mediaType,
                'uploaded_by' => auth()->id(),
            ]);

            $this->audit('created', 'Gallery Media Uploaded', $img->id, null, $img->toArray());
        }

        return redirect()->route('gallery.index')->with('success', 'Media uploaded!');
    }

    public function update(Request $request, GalleryImage $image)
    {
        abort_unless($this->canManage($image), 403);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => array_merge(['nullable', 'file'], self::mediaMimeRule()),
        ]);

        $old = $image->toArray();

        $data = ['title' => $request->title];

        if ($request->hasFile('image')) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }

            $file = $request->file('image');
            $data['path'] = $file->store('gallery', 'public');
            $data['media_type'] = self::mediaTypeFromMime($file->getMimeType());
        }

        $image->update($data);

        $this->audit('updated', 'Gallery Media Updated', $image->id, $old, $image->fresh()->toArray());

        return back()->with('success', 'Media updated!');
    }

    public function destroy(GalleryImage $image)
    {
        abort_unless($this->canManage($image), 403);

        $old = $image->toArray();
        $imageId = $image->id;

        if ($image->path) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        $this->audit('deleted', 'Gallery Media Deleted', $imageId, $old, null);

        return back()->with('success', 'Media deleted!');
    }
}
