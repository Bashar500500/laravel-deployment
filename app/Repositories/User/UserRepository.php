<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Models\User\User;
use App\DataTransferObjects\User\UserDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\DataTransferObjects\User\UserCourseDto;
use App\Enums\User\UserRole;
use App\Models\UserCourseGroup\UserCourseGroup;
use Carbon\Carbon;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\User\UserMessage;
use App\DataTransferObjects\Auth\PasswordResetCodeDto;
use App\Jobs\GlobalServiceHandlerJob;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Models\InstructorStudent\InstructorStudent;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function all(UserDto $dto, array $data): object
    {
        return (object) $this->model->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function allWithFilter(UserDto $dto, array $data): object
    {
        return (object) [];
    }

    public function find(int $id): object
    {
        return (object) parent::find($id);
    }

    public function create(UserDto $dto, array $data): object
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

        $user['role'] = $user->getRoleNames();
        return (object) $user;
    }

    public function update(UserDto $dto, int $id): object
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
            $profile->attachment()->delete();

            foreach ($projects as $project)
            {
                $attachments = $project->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Project/' . $project->id . '/Files/' . $attachment?->url);
                }
                $project->attachments()->delete();
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
                $questionBankMultipleTypeQuestions = $questionBank->questionBankMultipleTypeQuestions ?? [];
                $questionBankTrueOrFalseQuestions = $questionBank->questionBankTrueOrFalseQuestions ?? [];
                $questionBankShortAnswerQuestions = $questionBank->questionBankShortAnswerQuestions ?? [];
                $questionBankFillInBlankQuestions = $questionBank->questionBankFillInBlankQuestions ?? [];

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
                            Storage::disk('supabase')->delete('AssignmentSubmit/' . $assignmentSubmit->id . '/Files/' . $assignmentSubmit->student_id . '/' . $attachment?->url);
                        }
                        $assignmentSubmit->attachments()->delete();
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
                $ownedCourse->attachment()->delete();
            }
            foreach ($badges as $badge)
            {
                $badge->challengeRuleBadges()->delete();
            }

            return parent::delete($id);
        });

        return (object) $user;
    }

    public function resetPassword(PasswordResetCodeDto $dto): void
    {
        $user = User::where('email', $dto->email)->first();
        $user->update(['password' => Hash::make($dto->password)]);
    }

    public function addStudentToCourse(UserCourseDto $dto): UserMessage
    {
        $student = User::where('email', $dto->email)->first();

        if (! $student)
        {
            $email = DB::transaction(function () use ($dto) {
                $student = $this->model->create([
                    'first_name' => 'New student',
                    'last_name' => 'NST',
                    'email' => $dto->email,
                    'password' => Hash::make('12345'),
                ]);
                $student['role'] = $student->assignRole(UserRole::from('student'));

                $orderNumber = UserCourseGroup::getOrder($dto->courseId);
                $order = str_pad($orderNumber, 3, "0", STR_PAD_LEFT);
                $year = Carbon::now()->format('Y');
                $studentCode = $dto->studentCode . $year . $order;

                $student->userCourseGroups()->create([
                    'course_id' => $dto->courseId,
                    'student_code' => $studentCode,
                ]);

                $email = $student->emails()->create([
                    'subject' => 'New Student Account Created',
                    'body' => "Your email is : $dto->email and Your password is: 12345",
                ]);

                return $email;
            });

            // GlobalServiceHandlerJob::dispatch($email);

            return UserMessage::StudentCreatedAccountAndAddedToCourse;
        }

        $exists = $student->userCourseGroups->where('student_id', $student->id)
            ->where('course_id', $dto->courseId)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::User, ForbiddenExceptionMessage::User);
        }

        DB::transaction(function () use ($dto, $student) {
            $orderNumber = UserCourseGroup::getOrder($dto->courseId);
            $order = str_pad($orderNumber, 3, "0", STR_PAD_LEFT);
            $year = Carbon::now()->format('Y');
            $studentCode = $dto->studentCode . $year . $order;

            $student->userCourseGroups()->create([
                'course_id' => $dto->courseId,
                'student_code' => $studentCode,
            ]);
        });

        return UserMessage::StudentAddedToCourse;
    }

    public function removeStudentFromCourse(UserCourseDto $dto): void
    {
        $exists = UserCourseGroup::where('student_code', $dto->studentCode)->first();

        if (! $exists)
        {
            throw CustomException::notFound('Student');
        }

        DB::transaction(function () use ($exists) {
            $exists->delete();
        });
    }

    public function removeStudentFromInstructorList(UserCourseDto $dto, array $data): void
    {
        $exists = InstructorStudent::where('instructor_id', $data['user']->id)->where('student_id', $dto->studentId)->first();

        if (! $exists)
        {
            throw CustomException::notFound('Student');
        }

        DB::transaction(function () use ($exists) {
            $exists->delete();
        });
    }
}
