<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comment_blogs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(\App\Models\Blog::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\CommentBlog::class, 'parent_id')->nullable();
            $table->text('content');
            $table->unsignedBigInteger('answer_to')->nullable();
            $table->foreign('answer_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_blogs');
    }
};
