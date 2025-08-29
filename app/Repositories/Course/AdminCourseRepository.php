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

class AdminCourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $course) {
        parent::__construct($course);
    }

    public function all(CourseDto $dto, array $data): object
    {
        return (object) $this->model->with('attachment', 'students', 'requireds')
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
            ->with('attachment', 'students', 'requireds')
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
            ->load('attachment', 'students', 'requireds');
    }

    public function create(CourseDto $dto, array $data): object
    {
        $course = DB::transaction(function () use ($dto, $data) {
            $course = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'category_id' => $dto->categoryId,
                'name' => $dto->name,
                'description' => $dto->description,
                'language' => $dto->language,
                'level' => $dto->level,
                'timezone' => $dto->timezone,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'status' => $dto->status,
                'duration' => $dto->duration,
                'estimated_duration_hours' => 1,
                'price' => $dto->price,
                'code' => $dto->code,
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
                $storedFile = Storage::disk('supabase')->putFile('Course/' . $course->id . '/Images',
                    $dto->coverImage);

                $size = $dto->coverImage->getSize();
                $sizeKb = round($size / 1024, 2);

                $course->attachment()->create([
                    'reference_field' => AttachmentReferenceField::CourseCoverImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                    'size_kb' => $sizeKb,
                ]);
            }

            return $course;
        });

        return (object) $course->load('attachment', 'students', 'requireds');
    }

    public function update(CourseDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $course = DB::transaction(function () use ($dto, $model) {
            $course = tap($model)->update([
                'category_id' => $dto->categoryId ? $dto->categoryId : $model->category_id,
                'name' => $dto->name ? $dto->name : $model->name,
                'description' => $dto->description ? $dto->description : $model->description,
                'language' => $dto->language ? $dto->language : $model->language,
                'level' => $dto->level ? $dto->level : $model->level,
                'timezone' => $dto->timezone ? $dto->timezone : $model->timezone,
                'start_date' => $dto->startDate ? $dto->startDate : $model->start_date,
                'end_date' => $dto->endDate ? $dto->endDate : $model->end_date,
                'status' => $dto->status ? $dto->status : $model->status,
                'duration' => $dto->duration ? $dto->duration : $model->duration,
                'price' => $dto->price ? $dto->price : $model->price,
                'access_settings_access_type' => $dto->accessSettingsDto->accessType ? $dto->accessSettingsDto->accessType : $model->access_settings_access_type,
                'access_settings_price_hidden' => $dto->accessSettingsDto->priceHidden ? $dto->accessSettingsDto->priceHidden : $model->access_settings_price_hidden,
                'access_settings_is_secret' => $dto->accessSettingsDto->isSecret ? $dto->accessSettingsDto->isSecret : $model->access_settings_is_secret,
                'access_settings_enrollment_limit_enabled' => $dto->accessSettingsDto->enrollmentLimit->enabled ? $dto->accessSettingsDto->enrollmentLimit->enabled : $model->access_settings_enrollment_limit_enabled,
                'access_settings_enrollment_limit_limit' => $dto->accessSettingsDto->enrollmentLimit->limit ? $dto->accessSettingsDto->enrollmentLimit->limit : $model->access_settings_enrollment_limit_limit,
                'features_personalized_learning_paths' => $dto->featuresDto->personalizedLearningPaths ? $dto->featuresDto->personalizedLearningPaths : $model->features_personalized_learning_paths,
                'features_certificate_requires_submission' => $dto->featuresDto->certificateRequiresSubmission ? $dto->featuresDto->certificateRequiresSubmission : $model->features_certificate_requires_submission,
                'features_discussion_features_attach_files' => $dto->featuresDto->discussionFeaturesDto->attachFiles ? $dto->featuresDto->discussionFeaturesDto->attachFiles : $model->features_discussion_features_attach_files,
                'features_discussion_features_create_topics' => $dto->featuresDto->discussionFeaturesDto->createTopics ? $dto->featuresDto->discussionFeaturesDto->createTopics : $model->features_discussion_features_create_topics,
                'features_discussion_features_edit_replies' => $dto->featuresDto->discussionFeaturesDto->editReplies ? $dto->featuresDto->discussionFeaturesDto->editReplies : $model->features_discussion_features_edit_replies,
                'features_student_groups' => $dto->featuresDto->studentGroups ? $dto->featuresDto->studentGroups : $model->features_student_groups,
                'features_is_featured' => $dto->featuresDto->isFeatured ? $dto->featuresDto->isFeatured : $model->features_is_featured,
                'features_show_progress_screen' => $dto->featuresDto->showProgressScreen ? $dto->featuresDto->showProgressScreen : $model->features_show_progress_screen,
                'features_hide_grade_totals' => $dto->featuresDto->hideGradeTotals ? $dto->featuresDto->hideGradeTotals : $model->features_hide_grade_totals,
            ]);

            if ($dto->coverImage)
            {
                Storage::disk('supabase')->delete('Course/' . $course->id . '/Images/' . $course->attachment?->url);
                $course->attachments()->delete();

                $storedFile = Storage::disk('supabase')->putFile('Course/' . $course->id . '/Images',
                    $dto->coverImage);

                $size = $dto->coverImage->getSize();
                $sizeKb = round($size / 1024, 2);

                $course->attachment()->create([
                    'reference_field' => AttachmentReferenceField::CourseCoverImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                    'size_kb' => $sizeKb,
                ]);
            }

            return $course;
        });

        return (object) $course->load('attachment', 'students', 'requireds');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $course = DB::transaction(function () use ($id, $model) {
            $sections = $model->sections;
            $groups = $model->groups;
            $learningActivities = $model->learningActivities;
            $events = $model->events;
            $projects = $model->projects;
            $assessments = $model->assessments;
            $assignments = $model->assignments;
            $questionBank = $model->questionBank;
            $questionBankMultipleTypeQuestions = $questionBank->questionBankMultipleTypeQuestions ?? [];
            $questionBankTrueOrFalseQuestions = $questionBank->questionBankTrueOrFalseQuestions ?? [];
            $questionBankShortAnswerQuestions = $questionBank->questionBankShortAnswerQuestions ?? [];
            $questionBankFillInBlankQuestions = $questionBank->questionBankFillInBlankQuestions ?? [];

            foreach ($learningActivities as $learningActivity)
            {
                $attachment = $learningActivity->attachment;
                switch ($attachment?->type)
                {
                    case AttachmentType::Pdf:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Pdfs/' . $attachment?->url);
                        break;
                    default:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Videos/' . $attachment?->url);
                        break;
                }
                $learningActivity->attachment()->delete();
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
                $section->attachments()->delete();
            }
            foreach ($groups as $group)
            {
                $attachment = $group->attachment;
                Storage::disk('supabase')->delete('Group/' . $group->id . '/Images/' . $attachment?->url);
                $group->attachment()->delete();
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
                $event->attachments()->delete();
            }
            foreach ($projects as $project)
            {
                $projectSubmits = $project->projectSubmits;

                foreach ($projectSubmits as $projectSubmit)
                {
                    $attachments = $projectSubmit->attachments;
                    foreach ($attachments as $attachment)
                    {
                        $reference_field = $attachment->reference_field;
                        switch ($reference_field)
                        {
                            case AttachmentReferenceField::ProjectSubmitInstructorFiles:
                                Storage::disk('supabase')->delete('ProjectSubmit/' . $project->id . '/Files/Instructor/' . $attachment?->url);
                                break;
                            default:
                                Storage::disk('supabase')->delete('ProjectSubmit/' . $project->id . '/Files/Student/' . $attachment?->url);
                                break;
                        }
                    }
                    $projectSubmit->attachments()->delete();
                }

                $attachments = $project->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Project/' . $project->id . '/Files/' . $attachment?->url);
                }
                $project->attachments()->delete();
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
                        $reference_field = $attachment->reference_field;
                        switch ($reference_field)
                        {
                            case AttachmentReferenceField::AssignmentSubmitInstructorFiles:
                                Storage::disk('supabase')->delete('AssignmentSubmit/' . $assignment->id . '/Files/' . $assignment->student_id . '/Instructor/' . $attachment?->url);
                                break;
                            default:
                                Storage::disk('supabase')->delete('AssignmentSubmit/' . $assignment->id . '/Files/' . $assignment->student_id . '/Student/' . $attachment?->url);
                                break;
                        }
                    }
                    $assignmentSubmit->attachments()->delete();
                }

                $attachments = $assignment->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Assignment/' . $assignment->id . '/Files/' . $attachment?->url);
                }
                $assignment->attachments()->delete();
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

            $attachment = $model->attachment;
            Storage::disk('supabase')->delete('Course/' . $model->id . '/Images/' . $attachment?->url);
            $model->attachment()->delete();
            $model->prerequisites()->delete();
            $model->requireds()->delete();
            return parent::delete($id);
        });

        return (object) $course;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Course/' . $model->id . '/Images/' . $model->attachment?->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Course/' . $model->id . '/Images/' . $model->attachment?->url);
        $tempPath = storage_path('app/private/' . $model->attachment?->url);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Course/' . $model->id . '/Images/' . $model->attachment?->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Course/' . $model->id . '/Images/' . $model->attachment?->url);
        $tempPath = storage_path('app/private/' . $model->attachment?->url);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            Storage::disk('supabase')->delete('Course/' . $model->id . '/Images/' . $model->attachment?->url);
            $model->attachments()->delete();

            $storedFile = Storage::disk('supabase')->putFile('Course/' . $model->id . '/Images',
                $data['image']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::CourseCoverImage,
                'type' => AttachmentType::Image,
                'url' => basename($storedFile),
                'size_kb' => $data['sizeKb'],
            ]);
        });

        return UploadMessage::Image;
    }

    public function deleteAttachment(int $id): void
    {
        $model = (object) parent::find($id);
        Storage::disk('supabase')->delete('Course/' . $model->id . '/Images/' . $model->attachment?->url);
        $model->attachments()->delete();
    }
}
