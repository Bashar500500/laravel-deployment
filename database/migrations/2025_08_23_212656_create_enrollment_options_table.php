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
        Schema::create('enrollment_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('type');
            $table->string('period');
            $table->boolean('allow_self_enrollment');
            $table->boolean('enable_waiting_list');
            $table->boolean('require_instructor_approval');
            $table->boolean('require_prerequisites');
            $table->boolean('enable_notifications');
            $table->boolean('enable_emails');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_options');
    }
};
