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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('reason');
            $table->text('description')->nullable();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->morphs('reportable');
            $table->unique(['user_id', 'reportable_id', 'reportable_type']); // Prevent duplicate favorites
            $table->index(['reportable_id', 'reportable_type']); // Faster lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
