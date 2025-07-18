<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Models\User\User;
use App\DataTransferObjects\User\AdminDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

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

            $profile->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Profile/' . $profile->id);
            foreach ($projects as $project)
            {
                $project->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Project/' . $project->id);
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
                foreach ($projects as $project)
                {
                    $project->attachments()->delete();
                    Storage::disk('local')->deleteDirectory('Project/' . $project->id);
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
                        $assignmentSubmit->attachments()->delete();
                        Storage::disk('local')->deleteDirectory('AssignmentSubmit/' . $assignmentSubmit->id);
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

                $ownedCourse->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Course/' . $ownedCourse->id);
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
