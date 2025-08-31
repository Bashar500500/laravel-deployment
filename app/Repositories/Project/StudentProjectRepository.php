<?php

namespace App\Repositories\Project;

use App\Repositories\BaseRepository;
use App\Models\Project\Project;
use App\DataTransferObjects\Project\ProjectDto;
use App\DataTransferObjects\Project\ProjectSubmitDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Challenge\ChallengeStatus;
use App\Enums\Challenge\ChallengeType;
use App\Enums\EnrollmentOption\EnrollmentOptionPeriod;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Upload\UploadMessage;
use Illuminate\Support\Carbon;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Model\ModelTypePath;
use App\Enums\ProjectSubmit\ProjectSubmitStatus;
use App\Enums\UserAward\UserAwardType;
use App\Models\Badge\Badge;
use App\Models\Course\Course;
use App\Models\Rule\Rule;
use App\Models\User\User;

class StudentProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project) {
        parent::__construct($project);
    }

    public function all(ProjectDto $dto): object
    {
        $course = Course::find($dto->courseId);
        $period = $course->course->enrollmentOption?->period;

        if ($period && $period == EnrollmentOptionPeriod::AlwaysAvailable)
        {
            return (object) $this->model->where('course_id', $dto->courseId)
                ->with('course', 'leader', 'group', 'projectSubmits', 'attachments')
                ->latest('created_at')
                ->simplePaginate(
                    $dto->pageSize,
                    ['*'],
                    'page',
                    $dto->currentPage,
                );
        }

        return (object) $this->model->where('course_id', $dto->courseId)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->with('course', 'leader', 'group', 'projectSubmits', 'attachments')
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
            ->load('course', 'leader', 'group', 'projectSubmits', 'attachments');
    }

    public function create(ProjectDto $dto): object
    {
        return (object) [];
    }

    public function update(ProjectDto $dto, int $id): object
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

        $exists = Storage::disk('supabase')->exists('Project/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $file = Storage::disk('supabase')->get('Project/' . $model->id . '/Files/' . $fileName);
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
        $zipName = 'Project-Files.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $file = Storage::disk('supabase')->get('Project/' . $model->id . '/Files/' . $attachment?->url);
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

    public function submit(ProjectSubmitDto $dto): object
    {
        $model = (object) parent::find($dto->projectId);
        $period = $model->course->enrollmentOption?->period;

        if ($period && $period == EnrollmentOptionPeriod::BeforeStart)
        {
            $startDate = Carbon::parse($model->start_date);
            $endDate = Carbon::parse($model->end_date);

            if ($startDate->isAfter(Carbon::today()) || $endDate->isBefore(Carbon::today()))
            {
                throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectFinished);
            }
        }

        $projectSubmits = $model->projectSubmits->count();
        $startDate = Carbon::parse($model->start_date);
        $endDate = Carbon::parse($model->end_date);

        if($projectSubmits == $model->max_submits)
        {
            throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectMaxSubmits);
        }

        if($startDate->isAfter(Carbon::today()) || $endDate->isBefore(Carbon::today()))
        {
            throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectStartOrEndDate);
        }

        $projectSubmit = DB::transaction(function () use ($dto, $model) {
            $projectSubmit = $model->projectSubmits()->create([
                'status' => ProjectSubmitStatus::NotCorrected,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('ProjectSubmit/' . $projectSubmit->id . '/Files/Student',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $projectSubmit->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ProjectSubmitStudentFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            $this->checkChallengeScore100OnProjectRule($projectSubmit);

            return $projectSubmit;
        });

        return (object) $projectSubmit;
    }

    private function checkChallengeScore100OnProjectRule(object $projectSubmit): void
    {
        $course = $projectSubmit->project->course;
        $challenges = $course->instructor->challenges;
        $groupStudents = $projectSubmit->project->group->students->pluck('student_id')->toArray();
        $leaderId = $projectSubmit->project->leader_id;
        $allStudentIds = collect($groupStudents)
            ->push($leaderId)
            ->unique()
            ->values();

        foreach ($challenges as $challenge)
        {
            $challengeRuleBadge = $challenge->challengeRuleBadges
                ->where('contentable_type', ModelTypePath::Rule->getTypePath())
                ->where('contentable_id', 6)->first();

            if ($challengeRuleBadge)
            {
                $challengeCourse = $challenge->challengeCourses->where('course_id', $course->id)->first();

                if ($challengeCourse)
                {
                    switch ($challenge->status)
                    {
                        case ChallengeStatus::Active:
                            switch ($challenge->type)
                            {
                                case ChallengeType::Daily:
                                    if ($challenge->updated_at->gt(now()->subDays(1)))
                                    {
                                        foreach ($allStudentIds as $studentId)
                                        {
                                            $challengeUser = $challenge->challengeUsers->where('student_id', $studentId)->first();
                                            if ($challengeUser)
                                            {
                                                $student = User::find($studentId);
                                                $exists = $student->userAwards
                                                    ->where('awardable_type', ModelTypePath::Challenge->getTypePath())
                                                    ->where('awardable_id', $challenge->id)
                                                    ->whereDate('updated_at', '<', now()->subDays(1))
                                                    ->first();
                                                if (! $exists)
                                                {
                                                    $this->checkChallengeScore100OnProjectRuleDependingOnChallengeType($projectSubmit, $challengeRuleBadge, $student);
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case ChallengeType::Weekly:
                                    if ($challenge->created_at->gt(now()->subWeek()))
                                    {
                                        foreach ($allStudentIds as $studentId)
                                        {
                                            $challengeUser = $challenge->challengeUsers->where('student_id', $studentId)->first();
                                            if ($challengeUser)
                                            {
                                                $this->checkChallengeScore100OnProjectRuleDependingOnChallengeType($projectSubmit, $challengeRuleBadge, $studentId);
                                            }
                                        }
                                    }
                                    break;
                                case ChallengeType::Monthly:
                                    if ($challenge->created_at->gt(now()->subMonth()))
                                    {
                                        foreach ($allStudentIds as $studentId)
                                        {
                                            $challengeUser = $challenge->challengeUsers->where('student_id', $studentId)->first();
                                            if ($challengeUser)
                                            {
                                                $this->checkChallengeScore100OnProjectRuleDependingOnChallengeType($projectSubmit, $challengeRuleBadge, $studentId);
                                            }
                                        }
                                    }
                                    break;
                                default:
                                    foreach ($allStudentIds as $studentId)
                                    {
                                        $challengeUser = $challenge->challengeUsers->where('student_id', $studentId)->first();
                                        if ($challengeUser)
                                        {
                                            $this->checkChallengeScore100OnProjectRuleDependingOnChallengeType($projectSubmit, $challengeRuleBadge, $studentId);
                                        }
                                    }
                                    break;
                            }
                    }
                }
            }
        }
    }

    private function checkChallengeScore100OnProjectRuleDependingOnChallengeType(object $projectSubmit, object $challengeRuleBadge, $studentId): void
    {
        $student = User::find($studentId);
        $project = $projectSubmit->project;

        if ($projectSubmit->score == $project->points)
        {
            $this->awardToStudent($student, $challengeRuleBadge, 6);
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
