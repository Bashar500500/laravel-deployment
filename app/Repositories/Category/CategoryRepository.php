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
                $storedFile = Storage::disk('supabase')->putFile('Category/' . $category->id . '/Images',
                    $dto->categoryImage);

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
                Storage::disk('supabase')->delete('Category/' . $category->id . '/Images/' . $category->attachment?->url);
                $category->attachments()->delete();

                $storedFile = Storage::disk('supabase')->putFile('Category/' . $category->id . '/Images',
                    $dto->categoryImage);

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
                $attachment = $subCategory->attachment;
                Storage::disk('supabase')->delete('SubCategory/' . $subCategory->id . '/Images/' . $attachment?->url);
                $attachment->delete();
            }
            foreach ($courses as $course)
            {
                $sections = $course->sections;
                $groups = $course->groups;
                $learningActivities = $course->learningActivities;
                $events = $course->events;
                $projects = $course->projects;
                $assessments = $course->assessments;
                $assignments = $course->assignments;
                $questionBank = $course->questionBank;
                $questionBankMultipleTypeQuestions = $questionBank->questionBankMultipleTypeQuestions;
                $questionBankTrueOrFalseQuestions = $questionBank->questionBankTrueOrFalseQuestions;
                $questionBankShortAnswerQuestions = $questionBank->questionBankShortAnswerQuestions;
                $questionBankFillInBlankQuestions = $questionBank->questionBankFillInBlankQuestions;

                foreach ($learningActivities as $learningActivity)
                {
                    $attachment = $learningActivity->attachment;
                    switch ($attachment->type)
                    {
                        case AttachmentType::Pdf:
                            Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Pdfs/' . $attachment?->url);
                            break;
                        default:
                            Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Videos/' . $attachment?->url);
                            break;
                    }
                    $attachment->delete();
                }
                foreach ($sections as $section)
                {
                    $attachments = $section->attachments;
                    foreach ($attachments as $attachment)
                    {
                        switch ($attachment->reference_field)
                        {
                            case AttachmentReferenceField::SectionResourcesFile:
                                Storage::disk('supabase')->delete('Section/' . $section->id . '/Files/' . $attachment?->url);
                                break;
                        }
                    }
                    $attachments->delete();
                }
                foreach ($groups as $group)
                {
                    $attachment = $group->attachment;
                    Storage::disk('supabase')->delete('Group/' . $group->id . '/Images/' . $attachment?->url);
                    $attachment->delete();
                }
                foreach ($events as $event)
                {
                    $attachments = $event->attachments;
                    foreach ($attachments as $attachment)
                    {
                        switch ($attachment->reference_field)
                        {
                            case AttachmentReferenceField::EventAttachmentsFile:
                                Storage::disk('supabase')->delete('Event/' . $event->id . '/Files/' . $attachment?->url);
                                break;
                        }
                    }
                    $attachments->delete();
                }
                foreach ($projects as $project)
                {
                    $attachments = $project->attachments;
                    foreach ($attachments as $attachment)
                    {
                        Storage::disk('supabase')->delete('Project/' . $project->id . '/Files/' . $attachment?->url);
                    }
                    $attachments->delete();
                }
                foreach ($assessments as $assessment)
                {
                    $assessmentMultipleTypeQuestions = $assessment->assessmentMultipleTypeQuestions;
                    $assessmentTrueOrFalseQuestions = $assessment->assessmentTrueOrFalseQuestions;
                    $assessmentFillInBlankQuestions = $assessment->assessmentFillInBlankQuestions;

                    foreach ($assessmentMultipleTypeQuestions as $assessmentMultipleTypeQuestion)
                    {
                        $assessmentMultipleTypeQuestion->options()->delete();
                    }
                    foreach ($assessmentTrueOrFalseQuestions as $assessmentTrueOrFalseQuestion)
                    {
                        $assessmentTrueOrFalseQuestion->options()->delete();
                    }
                    foreach ($assessmentFillInBlankQuestions as $assessmentFillInBlankQuestion)
                    {
                        $assessmentFillInBlankQuestion->blanks()->delete();
                    }
                }
                foreach ($assignments as $assignment)
                {
                    $assignmentSubmits = $assignment->assignmentSubmits;

                    foreach ($assignmentSubmits as $assignmentSubmit)
                    {
                        $attachments = $assignmentSubmit->attachments;
                        foreach ($attachments as $attachment)
                        {
                            Storage::disk('supabase')->delete('AssignmentSubmit/' . $assignmentSubmit->id . '/Files/' . $assignmentSubmit->student_id . '/' . $attachment?->url);
                        }
                        $attachments->delete();
                    }
                }
                foreach ($questionBankMultipleTypeQuestions as $questionBankMultipleTypeQuestion)
                {
                    $questionBankMultipleTypeQuestion->options()->delete();
                    $questionBankMultipleTypeQuestion->assessmentQuestionBankQuestions()->delete();
                }
                foreach ($questionBankTrueOrFalseQuestions as $questionBankTrueOrFalseQuestion)
                {
                    $questionBankTrueOrFalseQuestion->options()->delete();
                    $questionBankTrueOrFalseQuestion->assessmentQuestionBankQuestions()->delete();
                }
                foreach ($questionBankShortAnswerQuestions as $questionBankShortAnswerQuestion)
                {
                    $questionBankShortAnswerQuestion->blanks()->delete();
                    $questionBankShortAnswerQuestion->assessmentQuestionBankQuestions()->delete();
                }
                foreach ($questionBankFillInBlankQuestions as $questionBankFillInBlankQuestion)
                {
                    $questionBankFillInBlankQuestion->assessmentQuestionBankQuestions()->delete();
                }

                $attachment = $course->attachment;
                Storage::disk('supabase')->delete('Course/' . $course->id . '/Images/' . $attachment?->url);
                $attachment->delete();
            }

            $attachment = $model->attachment;
            Storage::disk('supabase')->delete('Category/' . $model->id . '/Images/' . $attachment?->url);
            $attachment->delete();
            return parent::delete($id);
        });

        return (object) $category;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Category/' . $model->id . '/Images/' . $model->attachment->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Category/' . $model->id . '/Images/' . $model->attachment->url);
        $encoded = base64_encode($file);
        $decoded = base64_decode($encoded);
        $tempPath = storage_path('app/private/' . $model->attachment->url);
        file_put_contents($tempPath, $decoded);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Category/' . $model->id . '/Images/' . $model->attachment->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Category/' . $model->id . '/Images/' . $model->attachment->url);
        $encoded = base64_encode($file);
        $decoded = base64_decode($encoded);
        $tempPath = storage_path('app/private/' . $model->attachment->url);
        file_put_contents($tempPath, $decoded);

        return $tempPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            Storage::disk('supabase')->delete('Category/' . $model->id . '/Images/' . $model->attachment?->url);
            $model->attachments()->delete();

            $storedFile = Storage::disk('supabase')->putFile('Category/' . $model->id . '/Images',
                $data['image']);

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
        Storage::disk('supabase')->delete('Category/' . $model->id . '/Images/' . $model->attachment?->url);
        $model->attachments()->delete();
    }
}
