<?php

namespace App\Repositories\Assignment;

use App\Repositories\BaseRepository;
use App\Models\Assignment\Assignment;
use App\DataTransferObjects\Assignment\AssignmentDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\Assignment\AssignmentSubmitDto;
use App\Enums\Assessment\AssessmentType;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Model\ModelTypePath;
use App\Enums\Challenge\ChallengeStatus;
use App\Enums\Challenge\ChallengeType;
use Illuminate\Support\Carbon;
use App\Models\Rule\Rule;
use App\Models\Badge\Badge;
use App\Enums\UserAward\UserAwardType;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Grade\GradeCategory;
use App\Enums\Grade\GradeResubmission;
use App\Enums\Grade\GradeStatus;
use App\Enums\Grade\GradeTrend;
use App\Exceptions\CustomException;

class AssignmentRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    public function __construct(Assignment $assignment) {
        parent::__construct($assignment);
    }

    public function all(AssignmentDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'assignmentSubmits', 'grades')
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
            ->load('course', 'assignmentSubmits', 'grades');
    }

    public function create(AssignmentDto $dto): object
    {
        $assignment = DB::transaction(function () use ($dto) {
            $assignment = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'title' => $dto->title,
                'status' => $dto->status,
                'description' => $dto->description,
                'instructions' => $dto->instructions,
                'due_date' => $dto->dueDate,
                'points' => $dto->points,
                'submission_settings' => $dto->submissionSettings,
                'policies' => $dto->policies,
            ]);

            $students = $assignment->course->students;
            foreach ($students as $student)
            {
                $assignment->grade()->create([
                    'student_id' => $student->student_id,
                    'due_date' => $assignment->due_date,
                    'status' => GradeStatus::Missing,
                    'points_earned' => 0,
                    'max_points' => $assignment->points,
                    'percentage' => 0,
                    'category' => $assignment->type == AssessmentType::Quiz ? GradeCategory::Quiz : GradeCategory::Exam,
                    'class_average' => 0,
                    'trend' => GradeTrend::Neutral,
                    'trend_data' => [],
                    'resubmission' => GradeResubmission::Available,
                    'resubmission_due' => $assignment->policies['late_submission']['cutoff_date'],
                ]);
            }

            return $assignment;
        });

        return (object) $assignment->load('course', 'assignmentSubmits', 'grades');
    }

    public function update(AssignmentDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $assignment = DB::transaction(function () use ($dto, $model) {
            $assignment = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'status' => $dto->status ? $dto->status : $model->status,
                'description' => $dto->description ? $dto->description : $model->description,
                'instructions' => $dto->instructions ? $dto->instructions : $model->instructions,
                'due_date' => $dto->dueDate ? $dto->dueDate : $model->due_date,
                'points' => $dto->points ? $dto->points : $model->points,
                'submission_settings' => $dto->submissionSettings ? $dto->submissionSettings : $model->submission_settings,
                'policies' => $dto->policies ? $dto->policies : $model->policies,
            ]);

            return $assignment;
        });

        return (object) $assignment->load('course', 'assignmentSubmits', 'grades');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assignment = DB::transaction(function () use ($id, $model) {
            $assignmentSubmits = $model->assignmentSubmits;

            foreach ($assignmentSubmits as $assignmentSubmit)
            {
                $attachments = $assignmentSubmit->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('AssignmentSubmit/' . $assignmentSubmit->id . '/Files/' . $assignmentSubmit->student_id . '/' . $attachment->url);
                }
                $attachments->delete();
            }

            return parent::delete($id);
        });

        return (object) $assignment;
    }

    public function submit(AssignmentSubmitDto $dto, array $data): object
    {
        $model = (object) parent::find($dto->assignmentId);
        $grade = $model->grades->where('gradeable_type', ModelTypePath::Assignment->getTypePath())->where('gradeable_id', $model->id)->first();

        switch ($model->submission_settings['type'])
        {
            case 'No Submission':
                throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentNoSubmission);
            default:
                if ($dto->type->value != $model->submission_settings['type'])
                {
                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionTypeConflict);
                }

                if ($model->submission_settings['type'] == 'File Upload')
                {
                    $fileSize = $dto->file->getSize();

                    if ($fileSize > $model->submission_settings['max_file_size_mb'])
                    {
                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileSize);
                    }

                    foreach ($model->submission_settings['allowed_file_types'] as $item)
                    {
                        switch ($item)
                        {
                            case 'PDF':
                                $mime = $dto->file->getMimeType();
                                if ($mime != 'application/pdf')
                                {
                                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                }
                                break;
                            case 'Word Document':
                                $mime = $dto->file->getMimeType();
                                if ($mime != 'application/msword' || $mime != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                {
                                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                }
                                break;
                            case 'Text File':
                                $mime = $dto->file->getMimeType();
                                if ($mime != 'text/plain')
                                {
                                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                }
                                break;
                            case 'JPEG Image':
                                $mime = $dto->file->getMimeType();
                                if ($mime != 'image/jpeg')
                                {
                                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                }
                                break;
                            case 'PNG Image':
                                $mime = $dto->file->getMimeType();
                                if ($mime != 'image/png')
                                {
                                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                }
                                break;
                            case 'ZIP Archive':
                                $mime = $dto->file->getMimeType();
                                if ($mime != 'application/zip')
                                {
                                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                }
                                break;
                            default:
                                $mime = $dto->file->getMimeType();
                                if ($mime != 'application/msword' || $mime != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                {
                                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                }
                                break;
                        }
                    }
                }
        }

        $dueDate = Carbon::parse($model->due_date);
        if(! $dueDate->isSameDay(Carbon::today()))
        {
            switch ($model->policies['late_submission']['policy'])
            {
                case 'No Late Submissions':
                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentNoLateSubmission);
                case 'Accept Until Date':
                    $cutoffDate = Carbon::parse($model->policies['late_submission']['cutoff_date']);
                    if($cutoffDate->isBefore(Carbon::today()))
                    {
                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionCutoffDate);
                    }

                    $grade->update([
                        'due_date' => $model->due_date,
                        'status' => GradeStatus::Late,
                        'resubmission' => GradeResubmission::NotAvailable,
                        'resubmission_due' => $model->policies['late_submission']['cutoff_date'],
                    ]);
            }
        }
        else
        {
            $grade->update([
                'due_date' => $model->due_date,
                'status' => GradeStatus::Submitted,
                'resubmission' => GradeResubmission::Available,
                'resubmission_due' => $model->policies['late_submission']['cutoff_date'],
            ]);
        }

        $assignmentSubmit = DB::transaction(function () use ($dto, $model, $data) {
            $assignmentSubmit = $model->assignmentSubmits()->create([
                'student_id' => $data['studentId'],
                'text' => $dto->text ? $dto->text : null,
            ]);

            if ($dto->file)
            {
                $storedFile = Storage::disk('supabase')->putFile('AssignmentSubmit/' . $assignmentSubmit->id . '/Files/' . $data['studentId'],
                    $dto->file);

                $assignmentSubmit->attachment()->create([
                    'reference_field' => AttachmentReferenceField::AssignmentSubmitFile,
                    'type' => AttachmentType::File,
                    'url' => basename($storedFile),
                ]);
            }

            $this->checkChallengeSubmitAssignmentOnTimeRule($assignmentSubmit);

            return $assignmentSubmit;
        });

        return (object) $assignmentSubmit;
    }

    private function checkChallengeSubmitAssignmentOnTimeRule(object $assignmentSubmit): void
    {
        $course = $assignmentSubmit->assignment->course;
        $challenges = $course->instructor->challenges;

        foreach ($challenges as $challenge)
        {
            $challengeRuleBadge = $challenge->challengeRuleBadges
                ->where('contentable_type', ModelTypePath::Rule->getTypePath())
                ->where('contentable_id', 5)->first();

            if ($challengeRuleBadge)
            {
                $challengeCourse = $challenge->challengeCourses->where('course_id', $course->id)->first();

                if ($challengeCourse)
                {
                    $challengeUser = $challenge->challengeUsers->where('student_id', $assignmentSubmit->student_id)->first();

                    if ($challengeUser)
                    {
                        switch ($challenge->status)
                        {
                            case ChallengeStatus::Active:
                                switch ($challenge->type)
                                {
                                    case ChallengeType::Daily:
                                        if ($challenge->created_at->gt(now()->subDays(7)))
                                        {
                                            $this->checkChallengeSubmitAssignmentOnTimeRuleDependingOnChallengeType($assignmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Weekly:
                                        if ($challenge->created_at->gt(now()->subWeek()))
                                        {
                                            $this->checkChallengeSubmitAssignmentOnTimeRuleDependingOnChallengeType($assignmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Monthly:
                                        if ($challenge->created_at->gt(now()->subMonth()))
                                        {
                                            $this->checkChallengeSubmitAssignmentOnTimeRuleDependingOnChallengeType($assignmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    default:
                                        $this->checkChallengeSubmitAssignmentOnTimeRuleDependingOnChallengeType($assignmentSubmit, $challengeRuleBadge);
                                        break;
                                }
                        }
                    }
                }
            }
        }
    }

    private function checkChallengeSubmitAssignmentOnTimeRuleDependingOnChallengeType(object $assignmentSubmit, object $challengeRuleBadge): void
    {
        $student = $assignmentSubmit->student;
        $assignment = $assignmentSubmit->assignment;

        if (Carbon::parse($assignment->due_date)->isToday())
        {
            $this->awardToStudent($student, $challengeRuleBadge, 5);
        }
    }

    private function awardToStudent(object $student, object $challengeRuleBadge, int $ruleId): void
    {
        $student->userRules()->create([
            'challenge_rule_badge_id' => $challengeRuleBadge->id,
        ]);

        $challenge = $challengeRuleBadge->challenge;
        $rule = Rule::find($ruleId);
        $rules = $challenge->challengeRuleBadges
            ->where('contentable_type', ModelTypePath::Rule->getTypePath())->all();
        $studentRules = $student->userRules;

        if (count($rules) == count($studentRules))
        {
            $rule->userAward()->create([
                'challenge_id' => $challenge->id,
                'student_id' => $student->id,
                'type' => UserAwardType::Point,
                'number' => $rule->points,
            ]);

            $badges = $challenge->challengeRuleBadges
                ->where('contentable_type', ModelTypePath::Badge->getTypePath())->all();
            foreach ($badges as $item)
            {
                $badge = Badge::find($item->contentable_id);
                $badgeReward = $badge->reward;
                $badge->userAward()->create([
                    'challenge_id' => $challenge->id,
                    'student_id' => $student->id,
                    'type' => UserAwardType::Point,
                    'number' => $badgeReward['points'],
                ]);
                $badge->userAward()->create([
                    'challenge_id' => $challenge->id,
                    'student_id' => $student->id,
                    'type' => UserAwardType::Xp,
                    'number' => $badgeReward['xp'],
                ]);
            }

            $challengeRewards = $challenge->rewards;
            $challenge->userAward()->create([
                'challenge_id' => $challenge->id,
                'student_id' => $student->id,
                'type' => UserAwardType::Point,
                'number' => $challengeRewards['points'] * $challengeRewards['bonus_multiplier'],
            ]);
        }
        else
        {
            $rule->userAward()->create([
                'challenge_id' => $challenge->id,
                'student_id' => $student->id,
                'type' => UserAwardType::Point,
                'number' => $rule->points,
            ]);
        }
    }
}
