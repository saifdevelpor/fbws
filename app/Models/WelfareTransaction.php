<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelfareTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function month()
    {
        return $this->belongsTo(WelfareMonth::class, 'welfare_month_id'); // ✅ FIX
    }
}
