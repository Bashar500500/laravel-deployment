<?php

namespace App\Repositories\Course;

use App\Repositories\BaseRepository;
use App\Models\Course\Course;
use App\DataTransferObjects\Course\CourseDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $course) {
        parent::__construct($course);
    }

    public function all(CourseDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $dto->instructorId)
            ->with('attachment', 'students')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function allWithFilter(CourseDto $dto, array $data): object
    {
        return (object) $this->model->where('access_settings_access_type', $dto->accessType)
            ->with('attachment', 'students')
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
            ->load('attachment', 'students');
    }

    public function create(CourseDto $dto, array $data): object
    {
        $course = DB::transaction(function () use ($dto, $data) {
            $course = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'name' => $dto->name,
                'description' => $dto->description,
                'category_id' => $dto->categoryId,
                'language' => $dto->language,
                'level' => $dto->level,
                'timezone' => $dto->timezone,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'status' => $dto->status,
                'duration' => $dto->duration,
                'price' => $dto->price,
                'access_settings_access_type' => $dto->accessSettingsDto->accessType,
                'access_settings_price_hidden' => $dto->accessSettingsDto->priceHidden,
                'access_settings_is_secret' => $dto->accessSettingsDto->isSecret,
                'access_settings_enrollment_limit_enabled' => $dto->accessSettingsDto->enrollmentLimit->enabled,
                'access_settings_enrollment_limit_limit' => $dto->accessSettingsDto->enrollmentLimit->limit,
                'features_personalized_learning_paths' => $dto->featuresDto->personalizedLearningPaths,
                'features_certificate_requires_submission' => $dto->featuresDto->certificateRequiresSubmission,
                'features_discussion_features_attach_files' => $dto->featuresDto->discussionFeaturesDto->attachFiles,
                'features_discussion_features_create_topics' => $dto->featuresDto->discussionFeaturesDto->createTopics,
                'features_discussion_features_edit_replies' => $dto->featuresDto->discussionFeaturesDto->editReplies,
                'features_student_groups' => $dto->featuresDto->studentGroups,
                'features_is_featured' => $dto->featuresDto->isFeatured,
                'features_show_progress_screen' => $dto->featuresDto->showProgressScreen,
                'features_hide_grade_totals' => $dto->featuresDto->hideGradeTotals,
            ]);

            if ($dto->coverImage)
            {
                $storedFile = Storage::disk('local')->putFileAs('Course/' . $course->id . '/Images',
                    $dto->coverImage,
                    str()->uuid() . '.' . $dto->coverImage->extension());

                $course->attachment()->create([
                    'reference_field' => AttachmentReferenceField::CourseCoverImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $course;
        });

        return (object) $course->load('attachment', 'students');
    }

    public function update(CourseDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $course = DB::transaction(function () use ($dto, $model) {
            $course = tap($model)->update([
                'name' => $dto->name,
                'description' => $dto->description,
                'category_id' => $dto->categoryId,
                'language' => $dto->language,
                'level' => $dto->level,
                'timezone' => $dto->timezone,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'status' => $dto->status,
                'duration' => $dto->duration,
                'price' => $dto->price,
                'access_settings_access_type' => $dto->accessSettingsDto->accessType,
                'access_settings_price_hidden' => $dto->accessSettingsDto->priceHidden,
                'access_settings_is_secret' => $dto->accessSettingsDto->isSecret,
                'access_settings_enrollment_limit_enabled' => $dto->accessSettingsDto->enrollmentLimit->enabled,
                'access_settings_enrollment_limit_limit' => $dto->accessSettingsDto->enrollmentLimit->limit,
                'features_personalized_learning_paths' => $dto->featuresDto->personalizedLearningPaths,
                'features_certificate_requires_submission' => $dto->featuresDto->certificateRequiresSubmission,
                'features_discussion_features_attach_files' => $dto->featuresDto->discussionFeaturesDto->attachFiles,
                'features_discussion_features_create_topics' => $dto->featuresDto->discussionFeaturesDto->createTopics,
                'features_discussion_features_edit_replies' => $dto->featuresDto->discussionFeaturesDto->editReplies,
                'features_student_groups' => $dto->featuresDto->studentGroups,
                'features_is_featured' => $dto->featuresDto->isFeatured,
                'features_show_progress_screen' => $dto->featuresDto->showProgressScreen,
                'features_hide_grade_totals' => $dto->featuresDto->hideGradeTotals,
            ]);

            if ($dto->coverImage)
            {
                $course->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Course/' . $course->id);

                $storedFile = Storage::disk('local')->putFileAs('Course/' . $course->id . '/Images',
                    $dto->coverImage,
                    str()->uuid() . '.' . $dto->coverImage->extension());

                $course->attachment()->create([
                    'reference_field' => AttachmentReferenceField::CourseCoverImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $course;
        });

        return (object) $course->load('attachment', 'students');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $course = DB::transaction(function () use ($id, $model) {
            $sections = $model->sections;
            $groups = $model->groups;
            $learningActivities = $model->learningActivities;
            $events = $model->events;

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

            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Course/' . $model->id);
            return parent::delete($id);
        });

        return (object) $course;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Course/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Course/' . $id . '/Images/' . $model->attachment->url);

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
            $exists = Storage::disk('local')->exists('Course/' . $model->id);

            if ($exists)
            {
                $model->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Course/' . $model->id);
            }

            $storedFile = Storage::disk('local')->putFileAs('Course/' . $model->id . '/Images',
                $data['image'],
                basename($data['image']));

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::CourseCoverImage,
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
        Storage::disk('local')->deleteDirectory('Course/' . $model->id);
    }
}
