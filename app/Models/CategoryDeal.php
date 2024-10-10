<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CategoryDeal extends Model
{
    use HasFactory, hasSlug;

    protected $fillable = ['name', 'slug'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['created_at', 'updated_at'];

    public function deals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Deal::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100);
    }
}
