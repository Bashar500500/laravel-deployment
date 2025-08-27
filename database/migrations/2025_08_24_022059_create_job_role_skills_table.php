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
        Schema::create('job_role_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->string('job_role');
            $table->string('required_level');
            $table->string('importance');
            $table->decimal('weight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_role_skills');
    }
};
