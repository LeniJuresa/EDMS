<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'session_id',
        'id_number',       // dispatcher id_number
        'messages',
        'location',
        'description',
        'file_location',
        'status',
        'claimed_at',
        'closed_at',
    ];

    protected $casts = [
        'messages' => 'array',
        'claimed_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Map report->dispatcher via id_number
    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
}