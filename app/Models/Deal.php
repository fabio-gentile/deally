<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Deal extends Model
{
    use HasFactory, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'slug',
        'original_price',
        'price',
        'created_at',
        'expiration_date',
        'start_date',
        'promo_code',
        'image_url',
        'deal_url',
        'category_deal_id',
        'user_id',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title', 'id'],
            ],
        ];
    }

    /**
     * Slug generation event.
     *
     * @return string
     */
    public function sluggableEvent(): string
    {
        /**
         * Optional behaviour -- generate slug after model is saved.
         * This will likely become the new default in the next major release.
         */
        return SluggableObserver::SAVED;
    }

    /**
     * Get the images for the deal.
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImageDeal::class);
    }

    // Delete the associated images when the deal is deleted
    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($deal) {
            foreach ($deal->images as $image) {
                $filePath = $image->path . $image->filename;

                // Delete the image file from the storage
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }
        });
    }

    /**
     * Get the username that owns the discussion.
     *
     * @return BelongsTo
     */
    public function userName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name');
    }

    /**
     * Get the category that owns the deal.
     *
     * @return BelongsTo
     */
    public function categoryDeal(): BelongsTo
    {
        return $this->belongsTo(CategoryDeal::class);
    }

    /**
     * Get the user that owns the deal.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the votes for the deal.
     */
    public function voteDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VoteDeals::class, 'deal_id');
    }

    /**
     * Get the comments for the deal.
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommentDeal::class);
    }

    /**
     * Check if the deal is expired.
     */
    public function isExpired()
    {
        return $this->expiration_date->isPast();
    }

    /**
     * Get the favorites for the deal.
     */
    public function favorites(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Favorite::class,'favoritable');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'expiration_date' => 'datetime',
            'start_date' => 'datetime',
            'description' => PurifyHtmlOnGet::class,
        ];
    }
}
