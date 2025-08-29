<?php

namespace App\Repositories\UserActivity;

use App\Repositories\BaseRepository;
use App\Models\UserActivity\UserActivity;
use App\DataTransferObjects\UserActivity\UserActivityDto;
use Illuminate\Support\Facades\DB;

class UserActivityRepository extends BaseRepository implements UserActivityRepositoryInterface
{
    public function __construct(UserActivity $userActivity) {
        parent::__construct($userActivity);
    }

    public function create(UserActivityDto $dto, array $data): void
    {
        $userActivity = DB::transaction(function () use ($dto, $data) {
            $userActivity = (object) $this->model->create([
                'student_id' => $data['studentId'],
                'course_id' => $dto->courseId,
                'activity_type' => $dto->activityType,
                'activity_data' => $dto->activityData,
            ]);

            return $userActivity;
        });
    }
}
