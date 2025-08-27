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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('sub_category')->nullable();
            $table->string('difficulty');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('shape')->nullable();
            $table->string('image_url')->nullable();
            $table->string('status')->nullable();
            $table->json('reward');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
