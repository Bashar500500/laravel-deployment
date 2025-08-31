<?php

namespace App\Repositories\Assignment;

use App\Repositories\BaseRepository;
use App\Models\Assignment\Assignment;
use App\DataTransferObjects\Assignment\AssignmentDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\Assignment\AssignmentSubmitDto;
use App\Enums\Assessment\AssessmentType;
use App\Enums\Assignment\AssignmentStatus;
use App\Enums\AssignmentSubmit\AssignmentSubmitStatus;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Model\ModelTypePath;
use App\Enums\Challenge\ChallengeStatus;
use App\Enums\Challenge\ChallengeType;
use App\Enums\EnrollmentOption\EnrollmentOptionPeriod;
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
use App\Enums\Plagiarism\PlagiarismStatus;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Upload\UploadMessage;
use App\Models\Course\Course;

class StudentAssignmentRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    public function __construct(Assignment $assignment) {
        parent::__construct($assignment);
    }

    public function all(AssignmentDto $dto): object
    {
        $course = Course::find($dto->courseId);
        $period = $course->course->enrollmentOption?->period;

        if ($period && $period == EnrollmentOptionPeriod::AlwaysAvailable)
        {
            return (object) $this->model->where('course_id', $dto->courseId)
                ->where('status', AssignmentStatus::Published)
                ->with('course', 'assignmentSubmits', 'grades', 'attachments')
                ->latest('created_at')
                ->simplePaginate(
                    $dto->pageSize,
                    ['*'],
                    'page',
                    $dto->currentPage,
                );
        }

        return (object) $this->model->where('course_id', $dto->courseId)
            ->where('status', AssignmentStatus::Published)
            ->whereDate('due_date', '>=', Carbon::today())
            ->with('course', 'assignmentSubmits', 'grades', 'attachments')
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
            ->load('course', 'assignmentSubmits', 'grades', 'attachments');
    }

    public function create(AssignmentDto $dto): object
    {
        return (object) [];
    }

    public function update(AssignmentDto $dto, int $id): object
    {
        return (object) [];
    }

    public function delete(int $id): object
    {
        return (object) [];
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Assignment/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $file = Storage::disk('supabase')->get('Assignment/' . $model->id . '/Files/' . $fileName);
        $tempPath = storage_path('app/private/' . $fileName);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);
        $attachments = $model->attachments;

        if (count($attachments) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Assignment-Files.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $file = Storage::disk('supabase')->get('Assignment/' . $model->id . '/Files/' . $attachment?->url);
                $tempPath = storage_path('app/private/' . $attachment?->url);
                file_put_contents($tempPath, $file);
                $zip->addFromString(basename($tempPath), file_get_contents($tempPath));
                $tempFiles[] = $tempPath;
            }
            $zip->close();
            File::delete($tempFiles);
        }

        return $zipPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        return UploadMessage::File;
    }

    public function deleteAttachment(int $id, string $fileName): void {}

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
                    foreach ($dto->files as $file)
                    {
                        $fileSize = $file->getSize();
                        $sizeKb = round($fileSize / 1024, 2);

                        if ($sizeKb > $model->submission_settings['max_file_size_mb'])
                        {
                            throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileSize);
                        }

                        foreach ($model->submission_settings['allowed_file_types'] as $item)
                        {
                            switch ($item)
                            {
                                case 'PDF':
                                    $mime = $file->getMimeType();
                                    if ($mime != 'application/pdf')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                                case 'Word Document':
                                    $mime = $file->getMimeType();
                                    if ($mime != 'application/msword' || $mime != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                                case 'Text File':
                                    $mime = $file->getMimeType();
                                    if ($mime != 'text/plain')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                                case 'JPEG Image':
                                    $mime = $file->getMimeType();
                                    if ($mime != 'image/jpeg')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                                case 'PNG Image':
                                    $mime = $file->getMimeType();
                                    if ($mime != 'image/png')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                                case 'ZIP Archive':
                                    $mime = $file->getMimeType();
                                    if ($mime != 'application/zip')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                                case 'Blender':
                                    $mime = $file->getMimeType();
                                    if ($mime != 'application/x-blender')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                                default:
                                    $mime = $file->getMimeType();
                                    if ($mime != 'application/msword' || $mime != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                    {
                                        throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentSubmissionFileConflict);
                                    }
                                    break;
                            }
                        }
                    }
                }
        }

        $penalty = 0;
        $dueDate = Carbon::parse($model->due_date);
        if(! $dueDate->isSameDay(Carbon::today()))
        {
            switch ($model->policies['late_submission']['policy'])
            {
                case 'No Late Submissions':
                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentNoLateSubmission);
                case 'Accept with Penalty':
                    $penalty = $model->policies['late_submission']['penalty_percentage'];
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
                    break;
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

        $assignmentSubmit = DB::transaction(function () use ($dto, $model, $data, $penalty) {
            $assignmentSubmit = $model->assignmentSubmits()->create([
                'student_id' => $data['studentId'],
                'status' => AssignmentSubmitStatus::NotCorrected,
                'score' => $penalty == 0 ? null : -$penalty,
                'text' => $dto->text ? $dto->text : null,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('AssignmentSubmit/' . $assignmentSubmit->id . '/Files/' . $data['studentId'] . '/Student',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $assignmentSubmit->attachment()->create([
                        'reference_field' => AttachmentReferenceField::AssignmentSubmitStudentFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            $plagiarismCheck = $model->submission_settings['plagiarism_check'];
            if($plagiarismCheck)
            {
                $assignmentSubmit->plagiarism()->create([
                    'status' => PlagiarismStatus::Pending,
                ]);
            }

            $this->checkChallengeSubmitAssignmentOnTimeRule($assignmentSubmit);

            return $assignmentSubmit;
        });

        return (object) $assignmentSubmit->load('assignment', 'student', 'attachments');
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
