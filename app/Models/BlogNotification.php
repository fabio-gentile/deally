<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogNotification extends Model
{
    use HasFactory;

    protected $fillable = ['last_notified_at', 'user_id', 'blog_id'];
}