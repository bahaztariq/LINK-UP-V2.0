<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['user_id', 'token', 'expires_at', 'used_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isUsed()
    {
        return $this->used_at !== null;
    }

    public function isValid()
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    public function markAsUsed()
    {
        $this->update(['used_at' => now()]);
    }
}
