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
        Schema::create('learning_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->nullable();
            $table->boolean('flags_is_free_preview')->nullable();
            $table->boolean('flags_is_compulsory')->nullable();
            $table->boolean('flags_requires_enrollment')->nullable();
            $table->json('content_data')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('completion_type')->nullable();
            $table->json('completion_data')->nullable();
            $table->date('availability_start');
            $table->date('availability_end');
            $table->string('availability_timezone')->nullable();
            $table->boolean('discussion_enabled')->nullable();
            $table->boolean('discussion_moderated')->nullable();
            $table->string('metadata_difficulty')->nullable();
            $table->json('metadata_keywords')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_activities');
    }
};
