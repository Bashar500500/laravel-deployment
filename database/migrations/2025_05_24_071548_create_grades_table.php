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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->date('due_date');
            $table->date('extended_due_date')->nullable();
            $table->string('status');
            $table->integer('points_earned');
            $table->integer('max_points');
            $table->integer('percentage');
            $table->string('category');
            $table->decimal('class_average');
            $table->string('trend');
            $table->json('trend_data');
            $table->string('feedback')->nullable();
            $table->string('resubmission');
            $table->date('resubmission_due')->nullable();
            $table->morphs('gradeable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
