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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique()->nullable();
            $table->decimal('original_price', 8, 2)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->dateTime('expiration_date');
            $table->dateTime('start_date');
            $table->string('promo_code')->nullable();
            $table->text('deal_url');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
