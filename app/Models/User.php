<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

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

    protected $attributes = [
        'preferences' => '{"newsletter": false, "blog_notification": false}',
    ];


//    /**
//     * The attributes that should be hidden for serialization.
//     *
//     * @var array<int, string>
//     */
//    protected $hidden = [
//        'password',
//        'remember_token',
//        'email_verified_at',
//        'email',
//    ];

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
     * The default attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected array $defaultHidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'email',
    ];

    /**
     * Get the hidden attributes based on user role.
     *
     * @return array<int, string>
     */
    public function getHidden(): array
    {
        // Check if the current user is an admin
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            // If the user is an admin, only hide sensitive data like password and remember token
            return ['password', 'remember_token'];
        }

        // Default hidden attributes for non-admin users
        return $this->defaultHidden;
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
