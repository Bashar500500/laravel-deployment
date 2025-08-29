<?php

namespace App\Repositories\UserInteraction;

use App\Repositories\BaseRepository;
use App\Models\UserInteraction\UserInteraction;
use App\DataTransferObjects\UserInteraction\UserInteractionDto;
use Illuminate\Support\Facades\DB;

class UserInteractionRepository extends BaseRepository implements UserInteractionRepositoryInterface
{
    public function __construct(UserInteraction $userInteraction) {
        parent::__construct($userInteraction);
    }

    public function create(UserInteractionDto $dto, array $data): void
    {
        $userInteraction = DB::transaction(function () use ($dto, $data) {
            $userInteraction = (object) $this->model->create([
                'student_id' => $data['studentId'],
                'course_id' => $dto->courseId,
                'page_view_id' => $dto->pageViewId,
                'interaction_type' => $dto->interactionType,
            ]);

            return $userInteraction;
        });
    }
}
