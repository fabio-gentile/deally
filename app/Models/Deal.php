<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    use HasFactory;

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
        ];
    }
}
