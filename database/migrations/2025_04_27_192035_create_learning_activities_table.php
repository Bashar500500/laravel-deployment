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
            $table->string('status');
            $table->boolean('flags_is_free_preview');
            $table->boolean('flags_is_compulsory');
            $table->boolean('flags_requires_enrollment');
            $table->string('content_type');
            $table->json('content_data');
            $table->string('thumbnail_url')->nullable();
            $table->string('completion_type');
            $table->json('completion_data');
            $table->date('availability_start')->nullable();
            $table->date('availability_end')->nullable();
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
