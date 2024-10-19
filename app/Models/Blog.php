<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Blog extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'published_at',
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
                'source' => ['title'],
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

    // Delete the associated image when the blog is deleted
    public static function boot(): void
    {
        parent::boot();
        static::deleting(function ($blog) {
            $filePath = 'uploads/blog/' . $blog->image;
            // Delete the image file from the storage
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        });
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
            'published_at' => 'datetime',
            'is_published' => 'boolean',
            'content' => PurifyHtmlOnGet::class,
        ];
    }
}
