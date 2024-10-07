<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Deal extends Model
{
    use HasFactory, hasSlug;

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
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['title', 'id'])
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    /**
     * Get the images for the deal.
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImageDeal::class);
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
