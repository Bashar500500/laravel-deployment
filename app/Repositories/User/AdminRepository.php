<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Models\User\User;
use App\DataTransferObjects\User\AdminDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function all(AdminDto $dto): object
    {
        return (object) $this->model->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id);
    }

    public function create(AdminDto $dto): object
    {
        $user = DB::transaction(function () use ($dto) {
            $user = $this->model->create([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'fcm_token' => $dto->fcmToken,
            ]);

            $user['role'] = $user->assignRole($dto->role);
            return $user;
        });

        return (object) $user;
    }

    public function update(AdminDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $user = DB::transaction(function () use ($dto, $model) {
            $user = tap($model)->update([
                'first_name' => $dto->firstName ? $dto->firstName : $model->first_name,
                'last_name' => $dto->lastName ? $dto->lastName : $model->last_name,
                'email' => $dto->email ? $dto->email : $model->email,
                'password' => $dto->password ? Hash::make($dto->password) : $model->password,
                'fcm_token' => $dto->fcmToken ? $dto->fcmToken : $model->fcm_token,
            ]);

            return $user;
        });

        return (object) $user;
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $user = DB::transaction(function () use ($id, $model) {
            $profile = $model->profile;
            $projects = $model->projects;
            $ownedCourses = $model->ownedCourses;
            $badges = $model->badges;

            $attachment = $profile->attachment;
            Storage::disk('supabase')->delete('Profile/' . $profile->id . '/Images/' . $attachment?->url);
            $attachment->delete();

            foreach ($projects as $project)
            {
                $attachments = $project->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Project/' . $project->id . '/Files/' . $attachment?->url);
                }
                $attachments->delete();
            }
            foreach ($ownedCourses as $ownedCourse)
            {
                $sections = $ownedCourse->sections;
                $groups = $ownedCourse->groups;
                $learningActivities = $ownedCourse->learningActivities;
                $events = $ownedCourse->events;
                $projects = $ownedCourse->projects;
                $assessments = $ownedCourse->assessments;
                $assignments = $ownedCourse->assignments;
                $questionBank = $ownedCourse->questionBank;
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

                $attachment = $ownedCourse->attachment;
                Storage::disk('supabase')->delete('Course/' . $ownedCourse->id . '/Images/' . $attachment?->url);
                $attachment->delete();
            }
            foreach ($badges as $badge)
            {
                $badge->challengeRuleBadges()->delete();
            }

            return parent::delete($id);
        });

        return (object) $user;
    }
}
