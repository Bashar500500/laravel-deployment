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
use App\Enums\Upload\UploadMessage;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function __construct(Group $group) {
        parent::__construct($group);
    }

    public function all(GroupDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'sectionGroups', 'students', 'attachment')
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
            ->load('course', 'sectionGroups', 'students', 'attachment');
    }

    public function create(GroupDto $dto): object
    {
        $group = DB::transaction(function () use ($dto) {
            $group = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'name' => $dto->name,
                'description' => $dto->description,
                'capacity_min' => $dto->groupCapacityDto->min,
                'capacity_max' => $dto->groupCapacityDto->max,
            ]);

            if ($dto->image)
            {
                $storedFile = Storage::disk('local')->putFileAs('Group/' . $group->id . '/Images',
                    $dto->image,
                    str()->uuid() . '.' . $dto->image->extension());

                $group->attachment()->create([
                    'reference_field' => AttachmentReferenceField::GroupImageUrl,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $group;
        });

        return (object) $group->load('course', 'sectionGroups', 'students', 'attachment');
    }

    public function update(GroupDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $group = DB::transaction(function () use ($dto, $model) {
            $group = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'description' => $dto->description ? $dto->description : $model->description,
                'capacity_min' => $dto->groupCapacityDto->min ? $dto->groupCapacityDto->min : $model->capacity_min,
                'capacity_max' => $dto->groupCapacityDto->max ? $dto->groupCapacityDto->max : $model->capacity_max,
            ]);

            if ($dto->image)
            {
                $group->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Group/' . $group->id);

                $storedFile = Storage::disk('local')->putFileAs('Group/' . $group->id . '/Images',
                    $dto->image,
                    str()->uuid() . '.' . $dto->image->extension());

                $group->attachment()->create([
                    'reference_field' => AttachmentReferenceField::GroupImageUrl,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $group;
        });

        return (object) $group->load('course', 'sectionGroups', 'students', 'attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $group = DB::transaction(function () use ($id, $model) {
            $projects = $model->projects;

            foreach ($projects as $project)
            {
                $project->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Project/' . $project->id);
            }

            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Group/' . $model->id);
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

        $file = Storage::disk('local')->path('Group/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Group/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            $exists = Storage::disk('local')->exists('Group/' . $model->id);

            if ($exists)
            {
                $model->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Group/' . $model->id);
            }

            $storedFile = Storage::disk('local')->putFileAs('Group/' . $model->id . '/Images',
                $data['image'],
                basename($data['image']));

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
        $model->attachments()->delete();
        Storage::disk('local')->deleteDirectory('Group/' . $model->id);
    }
}
