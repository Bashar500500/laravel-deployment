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
        Schema::create('question_bank_true_or_false_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->constrained('question_banks')->cascadeOnDelete();
            $table->text('text');
            $table->integer('points');
            $table->string('difficulty');
            $table->string('category');
            $table->boolean('required');
            $table->boolean('correct_answer');
            $table->json('labels')->nullable();
            $table->json('settings');
            $table->json('feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_bank_true_or_false_questions');
    }
};
