<?php

namespace App\Repositories\Attendance;

use App\Repositories\BaseRepository;
use App\Models\Attendance\Attendance;
use App\DataTransferObjects\Attendance\AttendanceDto;
use Illuminate\Support\Facades\DB;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Model\ModelTypePath;
use App\Enums\Challenge\ChallengeStatus;
use App\Enums\Challenge\ChallengeType;
use App\Models\Rule\Rule;
use App\Models\Badge\Badge;
use App\Enums\UserAward\UserAwardType;

class AttendanceRepository extends BaseRepository implements AttendanceRepositoryInterface
{
    public function __construct(Attendance $attendance) {
        parent::__construct($attendance);
    }

    public function all(AttendanceDto $dto): object
    {
        return (object) $this->model->where('learning_activity_id', $dto->learningActivityId)
            ->with('learningActivity', 'student')
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
            ->load('learningActivity', 'student');
    }

    public function create(AttendanceDto $dto): object
    {
        $exists = $this->model->where('learning_activity_id', $dto->learningActivityId)
            ->where('student_id', $dto->studentId)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::Attendance, ForbiddenExceptionMessage::Attendance);
        }

        $attendance = DB::transaction(function () use ($dto) {
            $attendance = (object) $this->model->create([
                'learning_activity_id' => $dto->learningActivityId,
                'student_id' => $dto->studentId,
                'is_present' => $dto->isPresent,
            ]);

            $student = $attendance->student;
            $section = $attendance->learningActivity->section;

            // $alreadyCompleted = SectionCompletion::where('section_id', $section->id)
            //     ->where('student_id', $student->id)
            //     ->exists();

            // if ($alreadyCompleted) {
            //     return;
            // }

            $totalActivities = $section->learningActivities->count();
            $attendedActivities = Attendance::where('student_id', $student->id)
                ->whereHas('learningActivity', function ($query) use ($section) {
                    $query->where('section_id', $section->id);
                })->count();

            if ($attendedActivities == $totalActivities)
            {
                $student->sectionCompletions()->create([
                    'section_id' => $section->id,
                    'is_complete' => true,
                ]);

                $course = $attendance->learningActivity->section->course;
                $sections = $course->sections;
                $nextSection = $sections->where('id', '>', $section->id)->first();
                $progress = $course->progresses->where('student_id', $attendance->student_id)->first();
                $sectionCompletions = $student->sectionCompletions;
                $count = 0;

                foreach ($sectionCompletions as $item)
                {
                    $sectionCompletionsCourse = $item->section->course;
                    if ($sectionCompletionsCourse->id == $course->id)
                    {
                        $count += 1;
                    }
                }

                $this->checkChallengeCompleteThreeCourseModulesRule($attendance, $dto);
                $this->checkChallengeCourseCompletionRule($attendance, $dto);

                if (! $progress)
                {
                    $course->progresses()->create([
                        'student_id' => $attendance->student_id,
                        'progress' => (1 / (count($sections) / $count)) * 100,
                        'modules' => $count . '/' . count($sections),
                        'upcomig' => $count == count($sections) ? null :
                            $nextSection->title . ' - ' . $nextSection->access_release_date,
                    ]);
                }
                else
                {
                    $progress->update([
                        'progress' => (1 / (count($sections) / $count)) * 100,
                        'modules' => $count . '/' . count($sections),
                        'upcomig' => $count == count($sections) ? null :
                            $nextSection->title . ' - ' . $nextSection->access_release_date,
                    ]);
                }
            }

            return $attendance;
        });

        return (object) $attendance->load('learningActivity', 'student');
    }

    public function update(AttendanceDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $attendance = DB::transaction(function () use ($dto, $model) {
            $attendance = tap($model)->update([
                'is_present' => $dto->isPresent ? $dto->isPresent : $model->is_present,
            ]);

            return $attendance;
        });

        return (object) $attendance->load('learningActivity', 'student');
    }

    public function delete(int $id): object
    {
        $attendance = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $attendance;
    }

    private function checkChallengeCompleteThreeCourseModulesRule(object $attendance, AttendanceDto $dto): void
    {
        $course = $attendance->learningActivity->section->course;
        $challenges = $course->instructor->challenges;

        foreach ($challenges as $challenge)
        {
            $challengeRuleBadge = $challenge->challengeRuleBadges
                ->where('contentable_type', ModelTypePath::Rule->getTypePath())
                ->where('contentable_id', 1)->first();

            if ($challengeRuleBadge)
            {
                $challengeCourse = $challenge->challengeCourses->where('course_id', $course->id)->first();

                if ($challengeCourse)
                {
                    $challengeUser = $challenge->challengeUsers->where('student_id', $dto->studentId)->first();

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
                                            $this->checkChallengeCompleteThreeCourseModulesRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Weekly:
                                        if ($challenge->created_at->gt(now()->subWeek()))
                                        {
                                            $this->checkChallengeCompleteThreeCourseModulesRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Monthly:
                                        if ($challenge->created_at->gt(now()->subMonth()))
                                        {
                                            $this->checkChallengeCompleteThreeCourseModulesRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        }
                                        break;
                                    default:
                                        $this->checkChallengeCompleteThreeCourseModulesRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        break;
                                }
                        }
                    }
                }
            }
        }
    }

    private function checkChallengeCompleteThreeCourseModulesRuleDependingOnChallengeType(object $attendance, object $course, object $challengeRuleBadge): void
    {
        $student = $attendance->student;
        $sectionCompletions = $student->sectionCompletions;
        $count = 0;

        foreach ($sectionCompletions as $item)
        {
            $sectionCompletionsCourse = $item->section->course;
            if ($sectionCompletionsCourse->id == $course->id)
            {
                $count += 1;
            }
            if ($count == 3)
            {
                $this->awardToStudent($student, $challengeRuleBadge, 1);
                break;
            }
        }
    }

    private function checkChallengeCourseCompletionRule(object $attendance, AttendanceDto $dto): void
    {
        $course = $attendance->learningActivity->section->course;
        $challenges = $course->instructor->challenges;

        foreach ($challenges as $challenge)
        {
            $challengeRuleBadge = $challenge->challengeRuleBadges
                ->where('contentable_type', ModelTypePath::Rule->getTypePath())
                ->where('contentable_id', 2)->first();

            if ($challengeRuleBadge)
            {
                $challengeCourse = $challenge->challengeCourses->where('course_id', $course->id)->first();

                if ($challengeCourse)
                {
                    $challengeUser = $challenge->challengeUsers->where('student_id', $dto->studentId)->first();

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
                                            $this->checkChallengeCourseCompletionRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Weekly:
                                        if ($challenge->created_at->gt(now()->subWeek()))
                                        {
                                            $this->checkChallengeCourseCompletionRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Monthly:
                                        if ($challenge->created_at->gt(now()->subMonth()))
                                        {
                                            $this->checkChallengeCourseCompletionRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        }
                                        break;
                                    default:
                                        $this->checkChallengeCourseCompletionRuleDependingOnChallengeType($attendance, $course, $challengeRuleBadge);
                                        break;
                                }
                        }
                    }
                }
            }
        }
    }

    private function checkChallengeCourseCompletionRuleDependingOnChallengeType(object $attendance, object $course, object $challengeRuleBadge): void
    {
        $student = $attendance->student;
        $sectionCompletions = $student->sectionCompletions;
        $count = 0;

        foreach ($sectionCompletions as $item)
        {
            $sectionCompletionsCourse = $item->section->course;
            $sections = $item->section->course->sections;
            if ($sectionCompletionsCourse->id == $course->id)
            {
                $count += 1;
            }
            if ($count == count($sections))
            {
                $this->awardToStudent($student, $challengeRuleBadge, 2);
                break;
            }
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
