<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function media(): HasMany
    {
        return $this->hasMany(EventMedia::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Ordered items for public site (DB media rows, or legacy single image). */
    public function displayMediaItems()
    {
        $this->loadMissing('media');
        if ($this->media->isNotEmpty()) {
            return $this->media;
        }
        if ($this->image) {
            return collect([
                (object) ['type' => 'image', 'path' => $this->image],
            ]);
        }

        return collect();
    }

    public function listThumbnailUrl(): string
    {
        $this->loadMissing('media');
        $firstImage = $this->media->firstWhere('type', 'image');
        if ($firstImage) {
            return asset($firstImage->path);
        }
        if ($this->image) {
            return asset($this->image);
        }

        return asset('website/assets/img/default-item.png');
    }
}
