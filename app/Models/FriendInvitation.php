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

    public function markAsUsed()
    {
        $this->update(['used_at' => now()]);
    }
}
