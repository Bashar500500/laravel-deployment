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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            // $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('category_id');
            $table->string('language');
            $table->string('level');
            $table->string('timezone');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->integer('duration');
            $table->decimal('price');
            $table->string('access_settings_access_type');
            $table->boolean('access_settings_price_hidden');
            $table->boolean('access_settings_is_secret');
            $table->boolean('access_settings_enrollment_limit_enabled');
            $table->integer('access_settings_enrollment_limit_limit')->nullable();
            $table->boolean('features_personalized_learning_paths');
            $table->boolean('features_certificate_requires_submission');
            $table->boolean('features_discussion_features_attach_files');
            $table->boolean('features_discussion_features_create_topics');
            $table->boolean('features_discussion_features_edit_replies');
            $table->boolean('features_student_groups');
            $table->boolean('features_is_featured');
            $table->boolean('features_show_progress_screen');
            $table->boolean('features_hide_grade_totals');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
