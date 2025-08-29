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
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('language')->nullable();
            $table->string('level')->nullable();
            $table->string('timezone')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->integer('duration');
            $table->integer('estimated_duration_hours');
            $table->decimal('price')->nullable();
            $table->string('code')->nullable();
            $table->string('access_settings_access_type')->nullable();
            $table->boolean('access_settings_price_hidden')->nullable();
            $table->boolean('access_settings_is_secret')->nullable();
            $table->boolean('access_settings_enrollment_limit_enabled')->nullable();
            $table->integer('access_settings_enrollment_limit_limit')->nullable();
            $table->boolean('features_personalized_learning_paths')->nullable();
            $table->boolean('features_certificate_requires_submission')->nullable();
            $table->boolean('features_discussion_features_attach_files')->nullable();
            $table->boolean('features_discussion_features_create_topics')->nullable();
            $table->boolean('features_discussion_features_edit_replies')->nullable();
            $table->boolean('features_student_groups')->nullable();
            $table->boolean('features_is_featured')->nullable();
            $table->boolean('features_show_progress_screen')->nullable();
            $table->boolean('features_hide_grade_totals')->nullable();
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
