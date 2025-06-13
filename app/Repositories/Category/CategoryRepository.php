<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepository;
use App\Models\Category\Category;
use App\DataTransferObjects\Category\CategoryDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $category) {
        parent::__construct($category);
    }

    public function all(CategoryDto $dto): object
    {
        return (object) $this->model->with('courses', 'subCategories', 'attachment')
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
            ->load('courses', 'subCategories', 'attachment');
    }

    public function create(CategoryDto $dto): object
    {
        $category = DB::transaction(function () use ($dto) {
            $category = $this->model->create([
                'name' => $dto->name,
                'status' => $dto->status,
                'description' => $dto->description,
            ]);

            if ($dto->categoryImage)
            {
                $storedFile = Storage::disk('local')->putFileAs('Category/' . $category->id . '/Images',
                    $dto->categoryImage,
                    str()->uuid() . '.' . $dto->categoryImage->extension());

                $category->attachment()->create([
                    'reference_field' => AttachmentReferenceField::CategoryImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $category;
        });

        return (object) $category->load('courses', 'subCategories', 'attachment');
    }

    public function update(CategoryDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $category = DB::transaction(function () use ($dto, $model) {
            $category = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'status' => $dto->status ? $dto->status : $model->status,
                'description' => $dto->description ? $dto->description : $model->description,
            ]);

            if ($dto->categoryImage)
            {
                $category->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Category/' . $category->id);

                $storedFile = Storage::disk('local')->putFileAs('Category/' . $category->id . '/Images',
                    $dto->categoryImage,
                    str()->uuid() . '.' . $dto->categoryImage->extension());

                $category->attachment()->create([
                    'reference_field' => AttachmentReferenceField::CategoryImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $category;
        });

        return (object) $category->load('courses', 'subCategories', 'attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $category = DB::transaction(function () use ($id, $model) {
            $subCategories = $model->subCategories;
            $courses = $model->courses;

            foreach ($subCategories as $subCategory)
            {
                $subCategory->attachments()->delete();
                Storage::disk('local')->deleteDirectory('SubCategory/' . $subCategory->id);
            }
            foreach ($courses as $course)
            {
                $sections = $course->sections;
                $groups = $course->groups;
                $learningActivities = $course->learningActivities;
                $events = $course->events;
                $questions = $course->questions;
                $projects = $course->projects;

                foreach ($learningActivities as $learningActivity)
                {
                    $learningActivity->attachments()->delete();
                    Storage::disk('local')->deleteDirectory('LearningActivity/' . $learningActivity->id);
                }
                foreach ($sections as $section)
                {
                    $section->attachments()->delete();
                    Storage::disk('local')->deleteDirectory('Section/' . $section->id);
                }
                foreach ($groups as $group)
                {
                    $group->attachments()->delete();
                    Storage::disk('local')->deleteDirectory('Group/' . $group->id);
                }
                foreach ($events as $event)
                {
                    $event->attachments()->delete();
                    Storage::disk('local')->deleteDirectory('Event/' . $event->id);
                }
                foreach ($questions as $question)
                {
                    $question->attachments()->delete();
                    Storage::disk('local')->deleteDirectory('Question/' . $question->id);
                }
                foreach ($projects as $project)
                {
                    $project->attachments()->delete();
                    Storage::disk('local')->deleteDirectory('Project/' . $project->id);
                }

                $course->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Course/' . $course->id);
            }

            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Category/' . $model->id);
            return parent::delete($id);
        });

        return (object) $category;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Category/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Category/' . $id . '/Images/' . $model->attachment->url);

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
            $exists = Storage::disk('local')->exists('Category/' . $model->id);

            if ($exists)
            {
                $model->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Category/' . $model->id);
            }

            $storedFile = Storage::disk('local')->putFileAs('Category/' . $model->id . '/Images',
                $data['image'],
                basename($data['image']));

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::CategoryImage,
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
        Storage::disk('local')->deleteDirectory('Category/' . $model->id);
    }
}
