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
        Schema::create('community_accesses', function (Blueprint $table) {
            $table->id();
            $table->boolean('community_enabled');
            $table->string('access_community')->nullable();
            $table->boolean('course_discussions_enabled');
            $table->boolean('permissions_post_enabled')->nullable();
            $table->boolean('permissions_poll_enabled')->nullable();
            $table->boolean('permissions_comment_enabled')->nullable();
            $table->boolean('reactions_upvote_enabled')->nullable();
            $table->boolean('reactions_like_enabled')->nullable();
            $table->boolean('reactions_share_enabled')->nullable();
            $table->boolean('attachments_images_enabled')->nullable();
            $table->boolean('attachments_videos_enabled')->nullable();
            $table->boolean('attachments_files_enabled')->nullable();
            $table->string('access_course_discussions')->nullable();
            $table->string('course_discussions_level')->nullable();
            $table->string('inbox_communication')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_accesses');
    }
};
