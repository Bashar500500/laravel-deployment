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
        Schema::create('time_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->string('status');
            $table->integer('duration_minutes');
            $table->string('type');
            $table->integer('grace_time_minutes');
            $table->integer('extension_time_minutes');
            $table->json('settings');
            $table->json('warnings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_limits');
    }
};
