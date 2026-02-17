<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    protected $fillable = [
        'content',
        'sender_id',
        'receiver_id',
        'conversation_id',
        'is_edited',
        'expires_at',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Alias 'user' to 'sender' for compatibility with existing code
    public function user()
    {
        return $this->sender();
    }
}
