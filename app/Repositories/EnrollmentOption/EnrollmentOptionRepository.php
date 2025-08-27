<?php

namespace App\Repositories\EnrollmentOption;

use App\Repositories\BaseRepository;
use App\Models\EnrollmentOption\EnrollmentOption;
use App\DataTransferObjects\EnrollmentOption\EnrollmentOptionDto;
use Illuminate\Support\Facades\DB;

class EnrollmentOptionRepository extends BaseRepository implements EnrollmentOptionRepositoryInterface
{
    public function __construct(EnrollmentOption $enrollmentOption) {
        parent::__construct($enrollmentOption);
    }

    public function all(EnrollmentOptionDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course')
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
        return (object) parent::find($id)->load('course');
    }

    public function create(EnrollmentOptionDto $dto): object
    {
        $enrollmentOption = DB::transaction(function () use ($dto) {
            $enrollmentOption = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'type' => $dto->type,
                'period' => $dto->period,
                'allow_self_enrollment' => $dto->allowSelfEnrollment,
                'enable_waiting_list' => $dto->enableWaitingList,
                'require_instructor_approval' => $dto->requireInstructorApproval,
                'require_prerequisites' => $dto->requirePrerequisites,
                'enable_notifications' => $dto->enableNotifications,
                'enable_emails' => $dto->enableEmails,
            ]);

            return $enrollmentOption;
        });

        return (object) $enrollmentOption->load('course');
    }

    public function update(EnrollmentOptionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $enrollmentOption = DB::transaction(function () use ($dto, $model) {
            $enrollmentOption = tap($model)->update([
                'type' => $dto->type ? $dto->type : $model->type,
                'period' => $dto->period ? $dto->period : $model->period,
                'allow_self_enrollment' => $dto->allowSelfEnrollment ? $dto->allowSelfEnrollment : $model->allow_self_enrollment,
                'enable_waiting_list' => $dto->enableWaitingList ? $dto->enableWaitingList : $model->enable_waiting_list,
                'require_instructor_approval' => $dto->requireInstructorApproval ? $dto->requireInstructorApproval : $model->require_instructor_approval,
                'require_prerequisites' => $dto->requirePrerequisites ? $dto->requirePrerequisites : $model->require_prerequisites,
                'enable_notifications' => $dto->enableNotifications ? $dto->enableNotifications : $model->enable_notifications,
                'enable_emails' => $dto->enableEmails ? $dto->enableEmails : $model->enable_emails,
            ]);

            return $enrollmentOption;
        });

        return (object) $enrollmentOption->load('course');
    }

    public function delete(int $id): object
    {
        $enrollmentOption = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $enrollmentOption;
    }
}
