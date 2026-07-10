<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'path', 'media_type', 'uploaded_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function publicUrl(): string
    {
        return asset('https://fbws.exlontech.com/storage/app/public/' . ltrim((string) $this->path, '/'));
    }

    public function isVideo(): bool
    {
        return ($this->media_type ?? 'image') === 'video';
    }
}
