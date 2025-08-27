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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('time_limit_id')->constrained('time_limits')->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('description');
            $table->string('status');
            $table->integer('weight');
            $table->date('available_from');
            $table->date('available_to');
            $table->integer('attempts_allowed');
            $table->boolean('shuffle_questions');
            $table->json('feedback_options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
