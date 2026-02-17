<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendInvitation extends Model
{
    protected $fillable = ['user_id', 'token', 'used_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isUsed()
    {
        return $this->used_at !== null;
    }

    public function isExpired()
    {
        return $this->created_at->addHour()->isPast();
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
