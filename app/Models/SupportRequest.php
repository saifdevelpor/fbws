<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'support_type',
        'priority',
        'amount_needed',
        'contact_number',
        'details',
        'status',
        'admin_note',
        'is_seen_admin',
        'reviewed_at',
        'resolved_at',
    ];

    protected $casts = [
        'amount_needed' => 'decimal:2',
        'is_seen_admin' => 'boolean',
        'reviewed_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
