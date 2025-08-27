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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('rubric_id')->constrained('rubrics')->cascadeOnDelete();
            $table->string('title');
            $table->string('status');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->date('due_date');
            $table->integer('points');
            $table->json('peer_review_settings');
            $table->json('submission_settings')->nullable();
            $table->json('policies')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
