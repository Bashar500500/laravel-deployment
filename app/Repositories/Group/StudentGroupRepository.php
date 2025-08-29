<?php

namespace App\Repositories\Group;

use App\Repositories\BaseRepository;
use App\Models\Group\Group;
use App\DataTransferObjects\Group\GroupDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Upload\UploadMessage;
use App\Models\User\User;

class StudentGroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function __construct(Group $group) {
        parent::__construct($group);
    }

    public function all(GroupDto $dto, array $data): object
    {
        return (object) [];
    }

    public function allWithFilter(GroupDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'sectionEventGroups', 'students', 'attachment')
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
        return (object) parent::find($id)
            ->load('course', 'sectionEventGroups', 'students', 'attachment');
    }

    public function create(GroupDto $dto): object
    {
        return (object) [];
    }

    public function update(GroupDto $dto, int $id): object
    {
        return (object) [];
    }

    public function delete(int $id): object
    {
        return (object) [];
    }

    public function join(int $id, array $data): void
    {
        $student = $data['student'];
        $model = (object) parent::find($id);

        DB::transaction(function () use ($student, $model) {
            $student->userCourseGroups()->where('student_id', $student->id)
                ->where('course_id', $model->course_id)
                ->update([
                'group_id' => $model->id,
            ]);
        });
    }

    public function leave(int $id, array $data): void
    {
        $student = $data['student'];
        $model = (object) parent::find($id);

        DB::transaction(function () use ($student, $model) {
            $student->userCourseGroups()->where('student_id', $student->id)
                ->where('course_id', $model->course_id)
                ->update([
                'group_id' => null,
            ]);
        });
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Group/' . $model->id . '/Images/' . $model->attachment?->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Group/' . $model->id . '/Images/' . $model->attachment?->url);
        $tempPath = storage_path('app/private/' . $model->attachment?->url);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Group/' . $model->id . '/Images/' . $model->attachment?->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Group/' . $model->id . '/Images/' . $model->attachment?->url);
        $tempPath = storage_path('app/private/' . $model->attachment?->url);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        return UploadMessage::Image;
    }

    public function deleteAttachment(int $id): void {}
}
