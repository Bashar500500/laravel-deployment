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

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function __construct(Group $group) {
        parent::__construct($group);
    }

    public function all(GroupDto $dto, array $data): object
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

    public function allWithFilter(GroupDto $dto): object
    {
        return (object) [];
    }

    public function find(int $id): object
    {
        return (object) parent::find($id)
            ->load('course', 'sectionEventGroups', 'students', 'attachment');
    }

    public function create(GroupDto $dto): object
    {
        $group = DB::transaction(function () use ($dto) {
            $group = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'name' => $dto->name,
                'description' => $dto->description,
                'status' => $dto->status,
                'capacity_min' => $dto->groupCapacityDto->min,
                'capacity_max' => $dto->groupCapacityDto->max,
            ]);

            if ($dto->students)
            {
                if(count($dto->students) > $group->capacity_max)
                {
                    throw CustomException::forbidden(ModelName::Group, ForbiddenExceptionMessage::GroupCapacityMax);
                }

                foreach ($dto->students as $student)
                {
                    $student = User::find($student);
                    $exists = $student->userCourseGroups()->where('student_id', $student->id)
                        ->where('course_id', $group->course_id)
                        ->first();

                    if (! $exists)
                    {
                        throw CustomException::notFound('Student');
                    }

                    $student->userCourseGroups()->where('student_id', $student->id)
                        ->where('course_id', $group->course_id)
                        ->update([
                        'group_id' => $group->id,
                    ]);
                }
            }

            if ($dto->image)
            {
                $storedFile = Storage::disk('supabase')->putFile('Group/' . $group->id . '/Images',
                    $dto->image);

                $group->attachment()->create([
                    'reference_field' => AttachmentReferenceField::GroupImageUrl,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $group;
        });

        return (object) $group->load('course', 'sectionEventGroups', 'students', 'attachment');
    }

    public function update(GroupDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $group = DB::transaction(function () use ($dto, $model) {
            $group = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'description' => $dto->description ? $dto->description : $model->description,
                'status' => $dto->status ? $dto->status : $model->status,
                'capacity_min' => $dto->groupCapacityDto->min ? $dto->groupCapacityDto->min : $model->capacity_min,
                'capacity_max' => $dto->groupCapacityDto->max ? $dto->groupCapacityDto->max : $model->capacity_max,
            ]);

            if ($dto->image)
            {
                Storage::disk('supabase')->delete('Group/' . $group->id . '/Images/' . $group->attachment?->url);
                $group->attachments()->delete();

                $storedFile = Storage::disk('supabase')->putFile('Group/' . $group->id . '/Images',
                    $dto->image);

                $group->attachment()->create([
                    'reference_field' => AttachmentReferenceField::GroupImageUrl,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $group;
        });

        return (object) $group->load('course', 'sectionEventGroups', 'students', 'attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $group = DB::transaction(function () use ($id, $model) {
            $projects = $model->projects;

            foreach ($projects as $project)
            {
                $attachments = $project->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Project/' . $project->id . '/Files/' . $attachment?->url);
                }
                $project->attachments()->delete();
            }

            $attachment = $model->attachment;
            Storage::disk('supabase')->delete('Group/' . $model->id . '/Images/' . $attachment?->url);
            $model->attachment()->delete();
            return parent::delete($id);
        });

        return (object) $group;
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
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            Storage::disk('supabase')->delete('Group/' . $model->id . '/Images/' . $model->attachment?->url);
            $model->attachments()->delete();

            $storedFile = Storage::disk('supabase')->putFile('Group/' . $model->id . '/Images',
                $data['image']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::GroupImageUrl,
                'type' => AttachmentType::Image,
                'url' => basename($storedFile),
            ]);
        });

        return UploadMessage::Image;
    }

    public function deleteAttachment(int $id): void
    {
        $model = (object) parent::find($id);
        Storage::disk('supabase')->delete('Group/' . $model->id . '/Images/' . $model->attachment?->url);
        $model->attachments()->delete();
    }
}
