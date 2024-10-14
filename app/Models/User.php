<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'preferences',
        'name_updated_at',
        'biography',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'email',
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
            'name_updated_at' => 'datetime',
            'preferences' => 'array',
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'name';
    }

    /**
     * Get the deals for the user.
     *
     * @return HasMany
     */
    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get all the socials for the User
     *
     * @return HasMany
     */
    public function socials(): HasMany {
        return $this->hasMany(Social::class);
    }

    /**
     * Get all the favorites for the User
     *
     * @return HasMany
     */
    public function favorites(): HasMany {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get all the deal comments for the User
     *
     * @return HasMany
     */
    public function dealComments(): HasMany
    {
        return $this->hasMany(CommentDeal::class);
    }

    /**
     * Get all the discussion comments for the User
     *
     * @return HasMany
     */
    public function discussionComments(): HasMany
    {
        return $this->hasMany(CommentDiscussion::class);
    }
}
