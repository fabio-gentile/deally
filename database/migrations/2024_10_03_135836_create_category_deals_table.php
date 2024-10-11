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
        Schema::create('category_deals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\CategoryDeal::class)->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_deals');
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\CategoryDeal::class);
        });
    }
};
