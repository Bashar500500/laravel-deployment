<?php

namespace App\Repositories\Challenge;

use App\Repositories\BaseRepository;
use App\Models\Challenge\Challenge;
use App\DataTransferObjects\Challenge\ChallengeDto;
use Illuminate\Support\Facades\DB;
use App\Models\Rule\Rule;
use App\Models\Badge\Badge;
use App\Enums\Model\ModelTypePath;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class ChallengeRepository extends BaseRepository implements ChallengeRepositoryInterface
{
    public function __construct(Challenge $challenge) {
        parent::__construct($challenge);
    }

    public function all(ChallengeDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $data['instructorId'])
            ->with('challengeCourses', 'challengeRules', 'challengeBadges', 'challengeUsers', 'userAwards')
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
            ->load('challengeCourses', 'challengeRules', 'challengeBadges', 'challengeUsers', 'userAwards');
    }

    public function create(ChallengeDto $dto, array $data): object
    {
        $challenge = DB::transaction(function () use ($dto, $data) {
            $challenge = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'title' => $dto->title,
                'type' => $dto->type,
                'category' => $dto->category,
                'difficulty' => $dto->difficulty,
                'status' => $dto->status,
                'description' => $dto->description,
                'conditions' => $dto->conditions,
                'rewards' => $dto->rewards,
            ]);

            foreach ($dto->courses as $courseId)
            {
                $challenge->challengeCourses()->create([
                    'course_id' => $courseId,
                ]);
            }

            foreach ($dto->rules as $ruleId)
            {
                $rule = Rule::find($ruleId);
                $rule->challengeRuleBadge()->create([
                    'challenge_id' => $challenge->id,
                ]);
            }

            foreach ($dto->badges as $badgeId)
            {
                $badge = Badge::find($badgeId);
                $badge->challengeRuleBadge()->create([
                    'challenge_id' => $challenge->id,
                ]);
            }

            return $challenge;
        });

        return (object) $challenge->load('challengeCourses', 'challengeRules', 'challengeBadges', 'challengeUsers', 'userAwards');
    }

    public function update(ChallengeDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $challenge = DB::transaction(function () use ($dto, $model) {
            $challenge = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'type' => $dto->type ? $dto->type : $model->type,
                'category' => $dto->category ? $dto->category : $model->category,
                'difficulty' => $dto->difficulty ? $dto->difficulty : $model->difficulty,
                'status' => $dto->status ? $dto->status : $model->status,
                'description' => $dto->description ? $dto->description : $model->description,
                'conditions' => $dto->conditions ? $dto->conditions : $model->conditions,
                'rewards' => $dto->rewards ? $dto->rewards : $model->rewards,
            ]);

            if ($dto->courses)
            {
                $challenge->challengeCourses()->delete();

                foreach ($dto->courses as $courseId)
                {
                    $challenge->challengeCourses()->create([
                        'course_id' => $courseId,
                    ]);
                }
            }

            if ($dto->rules)
            {
                $challenge->challengeRuleBadges->where('contentable_type', ModelTypePath::Rule->getTypePath())
                    ->delete();

                foreach ($dto->rules as $ruleId)
                {
                    $rule = Rule::find($ruleId);
                    $rule->challengeRuleBadge()->create([
                        'challenge_id' => $challenge->id,
                    ]);
                }
            }

            if ($dto->badges)
            {
                $challenge->challengeRuleBadges->where('contentable_type', ModelTypePath::Badge->getTypePath())
                    ->delete();

                foreach ($dto->badges as $badgeId)
                {
                    $badge = Badge::find($badgeId);
                    $badge->challengeRuleBadge()->create([
                        'challenge_id' => $challenge->id,
                    ]);
                }
            }

            return $challenge;
        });

        return (object) $challenge->load('challengeCourses', 'challengeRules', 'challengeBadges', 'challengeUsers', 'userAwards');
    }

    public function delete(int $id): object
    {
        $challenge = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $challenge;
    }

    public function join(int $id, array $data): void
    {
        $student = $data['student'];
        $model = (object) parent::find($id);

        $exists = $student->challengeUsers->where('student_id', $student->id)
            ->where('challenge_id', $model->id)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::Challenge, ForbiddenExceptionMessage::ChallengeUserExsits);
        }

        $participants = $model->challengeUsers;
        if (count($participants) == $model->rewards['max_participants'])
        {
            throw CustomException::forbidden(ModelName::Challenge, ForbiddenExceptionMessage::ChallengeHasMaxParticipants);
        }

        DB::transaction(function () use ($student, $model) {
            $model->challengeUsers()->create([
                'student_id' => $student->id,
            ]);
        });
    }

    public function leave(int $id, array $data): void
    {
        $student = $data['student'];
        $model = (object) parent::find($id);

        $exists = $student->challengeUsers->where('student_id', $student->id)
            ->where('challenge_id', $model->id)->first();

        if (! $exists)
        {
            throw CustomException::notFound('Student');
        }

        DB::transaction(function () use ($student, $model) {
            $model->challengeUsers()->where('student_id', $student->id)
                ->where('challenge_id', $model->id)
                ->delete();
        });
    }
}
