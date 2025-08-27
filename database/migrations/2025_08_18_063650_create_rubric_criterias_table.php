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
        Schema::create('rubric_criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubric_id')->constrained('rubrics')->cascadeOnDelete();
            $table->string('name');
            $table->integer('weight');
            $table->text('description');
            $table->json('levels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubric_criterias');
    }
};
