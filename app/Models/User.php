<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'social_id',
        'social_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
    /**
     * Get the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function friendshipsSent()
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    public function friendshipsReceived()
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    public function getPendingRequestsAttribute()
    {
        return $this->friendshipsReceived()->where('status', 'pending')->with('requester')->get();
    }

    public function getFriendsAttribute()
    {
        // Get IDs of friends where I am the requester
        $sent = $this->friendshipsSent()->where('status', 'accepted')->pluck('addressee_id');
        // Get IDs of friends where I am the addressee
        $received = $this->friendshipsReceived()->where('status', 'accepted')->pluck('requester_id');
        
        $friendIds = $sent->merge($received);
        
        return User::whereIn('id', $friendIds)->get();
    }

    public function isFriendWith(User $user)
    {
        return $this->friendshipsSent()->where('addressee_id', $user->id)->where('status', 'accepted')->exists() ||
               $this->friendshipsReceived()->where('requester_id', $user->id)->where('status', 'accepted')->exists();
    }
    
    public function getPendingFriendRequestTo(User $user)
    {
        return $this->friendshipsSent()->where('addressee_id', $user->id)->where('status', 'pending')->first();
    }
    
    public function getPendingFriendRequestFrom(User $user)
    {
        return $this->friendshipsReceived()->where('requester_id', $user->id)->where('status', 'pending')->first();
    }

    public function hasSentFriendRequestTo(User $user)
    {
        return $this->getPendingFriendRequestTo($user) !== null;
    }
}
