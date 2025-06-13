<?php

namespace App\Models\CommunityAccess;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CommunityAccess\CommunityAccessAccessCommunity;
use App\Enums\CommunityAccess\CommunityAccessAccessCourseDiscussions;
use App\Enums\CommunityAccess\CommunityAccessCourseDiscussionsLevel;
use App\Enums\CommunityAccess\CommunityAccessInboxCommunication;

class CommunityAccess extends Model
{
    protected $fillable = [
        'community_enabled',
        'access_community',
        'course_discussions_enabled',
        'permissions_post_enabled',
        'permissions_poll_enabled',
        'permissions_comment_enabled',
        'reactions_upvote_enabled',
        'reactions_like_enabled',
        'reactions_share_enabled',
        'attachments_images_enabled',
        'attachments_videos_enabled',
        'attachments_files_enabled',
        'access_course_discussions',
        'course_discussions_level',
        'inbox_communication',
    ];

    protected $casts = [
        'access_community' => CommunityAccessAccessCommunity::class,
        'access_course_discussions' => CommunityAccessAccessCourseDiscussions::class,
        'course_discussions_level' => CommunityAccessCourseDiscussionsLevel::class,
        'inbox_communication' => CommunityAccessInboxCommunication::class,
    ];
}
