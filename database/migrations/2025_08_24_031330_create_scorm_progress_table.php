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
        Schema::create('scorm_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('scorm_module_id')->constrained('scorm_modules')->cascadeOnDelete();
            $table->string('completion_status');
            $table->string('success_status');
            $table->integer('score_raw')->nullable();
            $table->integer('score_min')->nullable();
            $table->integer('score_max')->nullable();
            $table->string('session_time')->nullable();
            $table->string('total_time')->nullable();
            $table->string('location')->nullable();
            $table->text('suspend_data')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamp('last_accessed')->nullable();
            $table->unique(['student_id', 'scorm_module_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scorm_progress');
    }
};
