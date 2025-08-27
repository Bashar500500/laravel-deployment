<?php

namespace App\Repositories\Assessment;

use App\Repositories\BaseRepository;
use App\Models\Assessment\Assessment;
use App\DataTransferObjects\Assessment\AssessmentDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\Assessment\AssessmentSubmitDto;
use App\Enums\Assessment\AssessmentSubmitQuestionType;
use App\Enums\Assessment\AssessmentType;
use App\Enums\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionType;
use App\Exceptions\CustomException;
use App\Enums\Model\ModelTypePath;
use App\Enums\Challenge\ChallengeStatus;
use App\Enums\Challenge\ChallengeType;
use App\Models\Rule\Rule;
use App\Models\Badge\Badge;
use App\Enums\UserAward\UserAwardType;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Grade\GradeCategory;
use App\Enums\Grade\GradeResubmission;
use App\Enums\Grade\GradeStatus;
use App\Enums\Grade\GradeTrend;
use Illuminate\Support\Carbon;

class InstructorAssessmentRepository extends BaseRepository implements AssessmentRepositoryInterface
{
    public function __construct(Assessment $assessment) {
        parent::__construct($assessment);
    }

    public function all(AssessmentDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('assessmentMultipleTypeQuestions', 'assessmentTrueOrFalseQuestions', 'assessmentShortAnswerQuestions', 'assessmentFillInBlankQuestions', 'assessmentQuestionBankMultipleTypeQuestions', 'assessmentQuestionBankTrueOrFalseQuestions', 'assessmentQuestionBankShortAnswerQuestions', 'assessmentQuestionBankFillInBlankQuestions', 'course', 'assessmentSubmits', 'grades')
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
            ->load('assessmentMultipleTypeQuestions', 'assessmentTrueOrFalseQuestions', 'assessmentShortAnswerQuestions', 'assessmentFillInBlankQuestions', 'assessmentQuestionBankMultipleTypeQuestions', 'assessmentQuestionBankTrueOrFalseQuestions', 'assessmentQuestionBankShortAnswerQuestions', 'assessmentQuestionBankFillInBlankQuestions', 'course', 'assessmentSubmits', 'grades');
    }

    public function create(AssessmentDto $dto): object
    {
        $assessment = DB::transaction(function () use ($dto) {
            $assessment = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'time_limit_id' => $dto->timeLimitId,
                'type' => $dto->type,
                'title' => $dto->title,
                'description' => $dto->description,
                'status' => $dto->status,
                'weight' => $dto->weight,
                'available_from' => $dto->availableFrom,
                'available_to' => $dto->availableTo,
                'attempts_allowed' => $dto->attemptsAllowed,
                'shuffle_questions' => $dto->shuffleQuestions,
                'feedback_options' => $dto->feedbackOptions,
            ]);

            $assessmentMultipleTypeQuestionsPoints = $assessment->assessmentMultipleTypeQuestions->sum('points');
            $assessmentTrueOrFalseQuestionsPoints = $assessment->assessmentTrueOrFalseQuestions->sum('points');
            $assessmentShortAnswerQuestionsPoints = $assessment->assessmentShortAnswerQuestions->sum('points');
            $assessmentFillInBlankQuestionsPoints = $assessment->assessmentFillInBlankQuestions->sum('points');
            $assessmentQuestionBankMultipleTypeQuestionsPoints = $assessment->assessmentQuestionBankMultipleTypeQuestions->sum('points');
            $assessmentQuestionBankTrueOrFalseQuestionsPoints = $assessment->assessmentQuestionBankTrueOrFalseQuestions->sum('points');
            $assessmentQuestionBankShortAnswerQuestionsPoints = $assessment->assessmentQuestionBankShortAnswerQuestions->sum('points');
            $assessmentQuestionBankFillInBlankQuestionsPoints = 0;
            $assessmentQuestionBankFillInBlankQuestions = $assessment->assessmentQuestionBankFillInBlankQuestions;
            foreach ($assessmentQuestionBankFillInBlankQuestions as $assessmentQuestionBankFillInBlankQuestion)
            {
                $points = $assessmentQuestionBankFillInBlankQuestion->blanks->sum('points');
                $assessmentQuestionBankFillInBlankQuestionsPoints += $points;
            }
            $assessmentPoints = $assessmentMultipleTypeQuestionsPoints +
                                $assessmentTrueOrFalseQuestionsPoints +
                                $assessmentShortAnswerQuestionsPoints +
                                $assessmentFillInBlankQuestionsPoints +
                                $assessmentQuestionBankMultipleTypeQuestionsPoints +
                                $assessmentQuestionBankTrueOrFalseQuestionsPoints +
                                $assessmentQuestionBankShortAnswerQuestionsPoints +
                                $assessmentQuestionBankFillInBlankQuestionsPoints;

            $students = $assessment->course->students;
            foreach ($students as $student)
            {
                $assessment->grade()->create([
                    'student_id' => $student->student_id,
                    'due_date' => $assessment->available_to,
                    'status' => GradeStatus::Missing,
                    'points_earned' => 0,
                    'max_points' => $assessmentPoints,
                    'percentage' => 0,
                    'category' => $assessment->type == AssessmentType::Quiz ? GradeCategory::Quiz : GradeCategory::Exam,
                    'class_average' => 0,
                    'trend' => GradeTrend::Neutral,
                    'trend_data' => [],
                    'resubmission' => GradeResubmission::Available,
                    'resubmission_due' => $assessment->available_to,
                ]);
            }

            return $assessment;
        });

        return (object) $assessment->load('assessmentMultipleTypeQuestions', 'assessmentTrueOrFalseQuestions', 'assessmentShortAnswerQuestions', 'assessmentFillInBlankQuestions', 'assessmentQuestionBankMultipleTypeQuestions', 'assessmentQuestionBankTrueOrFalseQuestions', 'assessmentQuestionBankShortAnswerQuestions', 'assessmentQuestionBankFillInBlankQuestions', 'course', 'assessmentSubmits', 'grades');
    }

    public function update(AssessmentDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $assessment = DB::transaction(function () use ($dto, $model) {
            $assessment = tap($model)->update([
                'time_limit_id' => $dto->timeLimitId ? $dto->timeLimitId : $model->time_limit_id,
                'type' => $dto->type ? $dto->type : $model->type,
                'title' => $dto->title ? $dto->title : $model->title,
                'description' => $dto->description ? $dto->description : $model->description,
                'status' => $dto->status ? $dto->status : $model->status,
                'weight' => $dto->weight ? $dto->weight : $model->weight,
                'available_from' => $dto->availableFrom ? $dto->availableFrom : $model->available_from,
                'available_to' => $dto->availableTo ? $dto->availableTo : $model->available_to,
                'attempts_allowed' => $dto->attemptsAllowed ? $dto->attemptsAllowed : $model->attempts_allowed,
                'shuffle_questions' => $dto->shuffleQuestions ? $dto->shuffleQuestions : $model->shuffle_questions,
                'feedback_options' => $dto->feedbackOptions ? $dto->feedbackOptions : $model->feedback_options,
            ]);

            return $assessment;
        });

        return (object) $assessment->load('assessmentMultipleTypeQuestions', 'assessmentTrueOrFalseQuestions', 'assessmentShortAnswerQuestions', 'assessmentFillInBlankQuestions', 'assessmentQuestionBankMultipleTypeQuestions', 'assessmentQuestionBankTrueOrFalseQuestions', 'assessmentQuestionBankShortAnswerQuestions', 'assessmentQuestionBankFillInBlankQuestions', 'course', 'assessmentSubmits', 'grades');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assessment = DB::transaction(function () use ($id, $model) {
            $assessmentMultipleTypeQuestions = $model->assessmentMultipleTypeQuestions;
            $assessmentTrueOrFalseQuestions = $model->assessmentTrueOrFalseQuestions;
            $assessmentFillInBlankQuestions = $model->assessmentFillInBlankQuestions;

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

            return parent::delete($id);
        });

        return (object) $assessment;
    }

    public function submit(AssessmentSubmitDto $dto, array $data): object
    {
        return (object) [];
    }

    public function startTimer(int $id): void
    {
        $model = (object) parent::find($id);
        $exsits = $model->timers->where('is_submitted', false)->first();

        if ($exsits)
        {
            throw CustomException::forbidden(ModelName::Assessment, ForbiddenExceptionMessage::TimerAlreadyStarted);
        }

        $availableFrom = Carbon::parse($model->available_from);
        $availableTo = Carbon::parse($model->available_to);
        $today = Carbon::today();
        if ($today->isBefore($availableFrom) || $today->isAfter($availableTo))
        {
            throw CustomException::forbidden(ModelName::Assessment, ForbiddenExceptionMessage::AssessmentNotAvailable);
        }

        DB::transaction(function () use ($model) {
            $model->timers()->create([
                'start_time' => now(),
            ]);
        });
    }

    public function pauseTimer(int $id): void
    {
        $model = (object) parent::find($id);
        $timer = $model->timers->where('is_submitted', false)->first();

        DB::transaction(function () use ($timer) {
            $timer->update([
                'is_paused' => true,
                'paused_at' => now(),
            ]);
        });
    }

    public function resumeTimer(int $id): void
    {
        $model = (object) parent::find($id);
        $timer = $model->timers->where('is_submitted', false)->first();

        DB::transaction(function () use ($timer) {
            $pausedDuration = now()->diffInSeconds($timer->paused_at);

            $timer->update([
                'start_time' => $timer->start_time->addSeconds($pausedDuration),
                'is_paused' => false,
                'paused_at' => null,
            ]);
        });
    }

    public function submitTimer(int $id): void
    {
        $model = (object) parent::find($id);
        $timer = $model->timers->where('is_submitted', false)->first();

        DB::transaction(function () use ($timer) {
            $timer->update([
                'is_submitted' => true,
                'submitted_at' => now(),
            ]);
        });
    }

    public function timerStatus(int $id): void {}
}
