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
        Schema::create('learning_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('gap_id')->constrained('learning_gaps')->cascadeOnDelete();
            $table->string('recommendation_type');
            $table->integer('resource_id');
            $table->string('resource_title');
            $table->string('resource_provider');
            $table->text('resource_url');
            $table->integer('estimated_duration_hours');
            $table->integer('priority_order');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_recommendations');
    }
};
