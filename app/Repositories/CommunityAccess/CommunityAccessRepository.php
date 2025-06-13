<?php

namespace App\Repositories\CommunityAccess;

use App\Repositories\BaseRepository;
use App\Models\CommunityAccess\CommunityAccess;
use App\DataTransferObjects\CommunityAccess\CommunityAccessDto;
use Illuminate\Support\Facades\DB;

class CommunityAccessRepository extends BaseRepository implements CommunityAccessRepositoryInterface
{
    public function __construct(CommunityAccess $communityAccess) {
        parent::__construct($communityAccess);
    }

    public function all(CommunityAccessDto $dto): object
    {
        return (object) $this->model->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id);
    }

    public function create(CommunityAccessDto $dto): object
    {
        $communityAccess = DB::transaction(function () use ($dto) {
            $communityAccess = (object) $this->model->create([
                'community_enabled' => $dto->communityAccessCommunityDto->communityEnabled,
                'access_community' => $dto->communityAccessCommunityDto->accessCommunity,
                'course_discussions_enabled' => $dto->communityAccessCourseDto->courseDiscussionsEnabled,
                'permissions_post_enabled' => $dto->communityAccessCourseDto->communityAccessPermissionsDto->postEnabled,
                'permissions_poll_enabled' => $dto->communityAccessCourseDto->communityAccessPermissionsDto->pollEnabled,
                'permissions_comment_enabled' => $dto->communityAccessCourseDto->communityAccessPermissionsDto->commentEnabled,
                'reactions_upvote_enabled' => $dto->communityAccessCourseDto->communityAccessReactionsDto->upvoteEnabled,
                'reactions_like_enabled' => $dto->communityAccessCourseDto->communityAccessReactionsDto->likeEnabled,
                'reactions_share_enabled' => $dto->communityAccessCourseDto->communityAccessReactionsDto->shareEnabled,
                'attachments_images_enabled' => $dto->communityAccessCourseDto->communityAccessAttachmentsDto->imagesEnabled,
                'attachments_videos_enabled' => $dto->communityAccessCourseDto->communityAccessAttachmentsDto->videosEnabled,
                'attachments_files_enabled' => $dto->communityAccessCourseDto->communityAccessAttachmentsDto->filesEnabled,
                'access_course_discussions' => $dto->communityAccessCourseDto->accessCourseDiscussions,
                'course_discussions_level' => $dto->communityAccessCourseDto->courseDiscussionsLevel,
                'inbox_communication' => $dto->communityAccessCourseDto->inboxCommunication,
            ]);

            return $communityAccess;
        });

        return (object) $communityAccess;
    }

    public function update(CommunityAccessDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $communityAccess = DB::transaction(function () use ($dto, $model) {
            $communityAccess = tap($model)->update([
                'community_enabled' => $dto->communityAccessCommunityDto->communityEnabled ? $dto->communityAccessCommunityDto->communityEnabled : $model->community_enabled,
                'access_community' => $dto->communityAccessCommunityDto->accessCommunity ? $dto->communityAccessCommunityDto->accessCommunity : $model->access_community,
                'course_discussions_enabled' => $dto->communityAccessCourseDto->courseDiscussionsEnabled ? $dto->communityAccessCourseDto->courseDiscussionsEnabled : $model->course_discussions_enabled,
                'permissions_post_enabled' => $dto->communityAccessCourseDto->communityAccessPermissionsDto->postEnabled ? $dto->communityAccessCourseDto->communityAccessPermissionsDto->postEnabled : $model->permissions_post_enabled,
                'permissions_poll_enabled' => $dto->communityAccessCourseDto->communityAccessPermissionsDto->pollEnabled ? $dto->communityAccessCourseDto->communityAccessPermissionsDto->pollEnabled : $model->permissions_poll_enabled,
                'permissions_comment_enabled' => $dto->communityAccessCourseDto->communityAccessPermissionsDto->commentEnabled ? $dto->communityAccessCourseDto->communityAccessPermissionsDto->commentEnabled : $model->permissions_comment_enabled,
                'reactions_upvote_enabled' => $dto->communityAccessCourseDto->communityAccessReactionsDto->upvoteEnabled ? $dto->communityAccessCourseDto->communityAccessReactionsDto->upvoteEnabled : $model->reactions_upvote_enabled,
                'reactions_like_enabled' => $dto->communityAccessCourseDto->communityAccessReactionsDto->likeEnabled ? $dto->communityAccessCourseDto->communityAccessReactionsDto->likeEnabled : $model->reactions_like_enabled,
                'reactions_share_enabled' => $dto->communityAccessCourseDto->communityAccessReactionsDto->shareEnabled ? $dto->communityAccessCourseDto->communityAccessReactionsDto->shareEnabled : $model->reactions_share_enabled,
                'attachments_images_enabled' => $dto->communityAccessCourseDto->communityAccessAttachmentsDto->imagesEnabled ? $dto->communityAccessCourseDto->communityAccessAttachmentsDto->imagesEnabled : $model->attachments_images_enabled,
                'attachments_videos_enabled' => $dto->communityAccessCourseDto->communityAccessAttachmentsDto->videosEnabled ? $dto->communityAccessCourseDto->communityAccessAttachmentsDto->videosEnabled : $model->attachments_videos_enabled,
                'attachments_files_enabled' => $dto->communityAccessCourseDto->communityAccessAttachmentsDto->filesEnabled ? $dto->communityAccessCourseDto->communityAccessAttachmentsDto->filesEnabled : $model->attachments_files_enabled,
                'access_course_discussions' => $dto->communityAccessCourseDto->accessCourseDiscussions ? $dto->communityAccessCourseDto->accessCourseDiscussions : $model->access_course_discussions,
                'course_discussions_level' => $dto->communityAccessCourseDto->courseDiscussionsLevel ? $dto->communityAccessCourseDto->courseDiscussionsLevel : $model->course_discussions_level,
                'inbox_communication' => $dto->communityAccessCourseDto->inboxCommunication ? $dto->communityAccessCourseDto->inboxCommunication : $model->inbox_communication,
            ]);

            return $communityAccess;
        });

        return (object) $communityAccess;
    }

    public function delete(int $id): object
    {
        $communityAccess = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $communityAccess;
    }
}
