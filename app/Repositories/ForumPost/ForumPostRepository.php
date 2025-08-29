<?php

namespace App\Repositories\ForumPost;

use App\Repositories\BaseRepository;
use App\Models\ForumPost\ForumPost;
use App\DataTransferObjects\ForumPost\ForumPostDto;
use Illuminate\Support\Facades\DB;

class ForumPostRepository extends BaseRepository implements ForumPostRepositoryInterface
{
    public function __construct(ForumPost $forumPost) {
        parent::__construct($forumPost);
    }

    public function all(ForumPostDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            // ->with('')
            ->latest('created_at')
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
            // ->load('');
    }

    public function create(ForumPostDto $dto, array $data): object
    {
        $forumPost = DB::transaction(function () use ($dto, $data) {
            $forumPost = (object) $this->model->create([
                'student_id' => $data['studentId'],
                'course_id' => $dto->courseId,
                'parent_post_id' => $dto->parentPostId,
                'post_type' => $dto->postType,
                'content' => $dto->content,
                'likes_count' => $dto->likesCount,
                'replies_count' => $dto->repliesCount,
                'is_helpful' => $dto->isHelpful,
            ]);

            return $forumPost;
        });

        return (object) $forumPost;
            // ->load('instructor');
    }

    public function update(ForumPostDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $forumPost = DB::transaction(function () use ($dto, $model) {
            $forumPost = tap($model)->update([
                'parent_post_id' => $dto->parentPostId ? $dto->parentPostId : $model->parent_post_id,
                'post_type' => $dto->postType ? $dto->postType : $model->post_type,
                'content' => $dto->content ? $dto->content : $model->content,
                'likes_count' => $dto->likesCount ? $dto->likesCount : $model->likes_count,
                'replies_count' => $dto->repliesCount ? $dto->repliesCount : $model->replies_count,
                'is_helpful' => $dto->isHelpful ? $dto->isHelpful : $model->is_helpful,
            ]);

            return $forumPost;
        });

        return (object) $forumPost;
            // ->load('');
    }

    public function delete(int $id): object
    {
        $forumPost = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $forumPost;
    }
}
