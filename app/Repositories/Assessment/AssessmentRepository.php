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

class AssessmentRepository extends BaseRepository implements AssessmentRepositoryInterface
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
        $model = (object) parent::find($dto->assessmentId);
        $points = 0;
        $detailedResults = [];
        $studentId = $data['student']->id;
        $attempts = $data['student']->assessmentSubmits->where('assessment_id', $model->id)->all();
        $grade = $model->grades->where('gradeable_type', ModelTypePath::Assessment->getTypePath())->where('gradeable_id', $model->id)->first();
        $grades = $model->grades->where('gradeable_type', ModelTypePath::Assessment->getTypePath())->where('gradeable_id', $model->id);
        $gradeScoreSum = $grades->sum('points_earned');

        if (count($attempts) == $model->attempts_allowed)
        {
            $grade->update([
                'resubmission' => GradeResubmission::NotAvailable,
            ]);

            throw CustomException::forbidden(ModelName::Assessment, ForbiddenExceptionMessage::AssessmentAttemptsAllowed);
        }

        $assessmentSubmit = DB::transaction(function () use ($dto, $model, $points, $detailedResults, $studentId, $grade, $gradeScoreSum, $grades) {
            foreach ($dto->answers as $answer)
            {
                if ($answer['is_question_bank_question'])
                {
                    switch ($answer['question_type'])
                    {
                        case AssessmentSubmitQuestionType::FillInBlankQuestion->getType():
                            $question = $model->assessmentQuestionBankFillInBlankQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('QuestionBankFillInBlankQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->fillInBlankQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                        case AssessmentSubmitQuestionType::MultipleTypeQuestion->getType():
                            $question = $model->assessmentQuestionBankMultipleTypeQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('QuestionBankMultipleTypeQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->multipleTypeQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                        case AssessmentSubmitQuestionType::ShortAnswerQuestion->getType():
                            $question = $model->assessmentQuestionBankShortAnswerQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('QuestionBankShortAnswerQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->shortAnswerQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                        default:
                            $question = $model->assessmentQuestionBankTrueOrFalseQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('QuestionBankTrueOrFalseQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->trueOrFalseQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                    }
                }
                else
                {
                    switch ($answer['question_type'])
                    {
                        case AssessmentSubmitQuestionType::FillInBlankQuestion->getType():
                            $question = $model->assessmentFillInBlankQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('AssessmentFillInBlankQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->fillInBlankQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                        case AssessmentSubmitQuestionType::MultipleTypeQuestion->getType():
                            $question = $model->assessmentMultipleTypeQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('AssessmentMultipleTypeQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->multipleTypeQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                        case AssessmentSubmitQuestionType::ShortAnswerQuestion->getType():
                            $question = $model->assessmentShortAnswerQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('AssessmentShortAnswerQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->shortAnswerQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                        default:
                            $question = $model->assessmentTrueOrFalseQuestions
                                ->where('id', $answer['question_id'])->first();

                            if (! $question)
                            {
                                throw CustomException::notFound('AssessmentTrueOrFalseQuestion');
                            }

                            if ($question->required)
                            {
                                $data = $this->trueOrFalseQuestion($question, $answer);
                                $points += $data['points'];
                                array_push($detailedResults, $data['result']);
                            }
                            break;
                    }
                }
            }

            $assessmentSubmit = $model->assessmentSubmits()->create([
                'student_id' => $studentId,
                'score' => $points,
                'detailed_results' => $detailedResults,
                'answers' => $dto->answers,
            ]);

            $this->checkChallengeScore80OnAssessmentRule($assessmentSubmit);
            $this->checkChallengeScore100OnAssessmentRule($assessmentSubmit);

            $assessmentMultipleTypeQuestionsPoints = $model->assessmentMultipleTypeQuestions->sum('points');
            $assessmentTrueOrFalseQuestionsPoints = $model->assessmentTrueOrFalseQuestions->sum('points');
            $assessmentShortAnswerQuestionsPoints = $model->assessmentShortAnswerQuestions->sum('points');
            $assessmentFillInBlankQuestionsPoints = $model->assessmentFillInBlankQuestions->sum('points');
            $assessmentQuestionBankMultipleTypeQuestionsPoints = $model->assessmentQuestionBankMultipleTypeQuestions->sum('points');
            $assessmentQuestionBankTrueOrFalseQuestionsPoints = $model->assessmentQuestionBankTrueOrFalseQuestions->sum('points');
            $assessmentQuestionBankShortAnswerQuestionsPoints = $model->assessmentQuestionBankShortAnswerQuestions->sum('points');
            $assessmentQuestionBankFillInBlankQuestionsPoints = 0;
            $assessmentQuestionBankFillInBlankQuestions = $model->assessmentQuestionBankFillInBlankQuestions;
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

            $oldTrendArray = $grade->trend_data;
            $newTrendArray = $oldTrendArray;
            array_push($newTrendArray, $assessmentSubmit->score);
            $trend = $this->calculateTrend($newTrendArray);

            $grade->update([
                'due_date' => $model->available_to,
                'status' => GradeStatus::Submitted,
                'points_earned' => $assessmentSubmit->score,
                'max_points' => $assessmentPoints,
                'percentage' => (1 / ($assessmentPoints / $assessmentSubmit->score)) * 100,
                'class_average' => ($gradeScoreSum / count($grades)),
                'trend' => $trend,
                'trend_data' => $newTrendArray,
                'resubmission' => GradeResubmission::Available,
                'resubmission_due' => $model->available_to,
            ]);

            return $assessmentSubmit;
        });

        return (object) $assessmentSubmit;
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

    public function timerStatus(int $id): void
    {}

    private function fillInBlankQuestion(object $question, array $answer): array
    {
        $points = 0;
        $pointsEarned = 0;
        $pointsPossible = $question->blanks->sum('points');

        foreach ($answer['blanks'] as $item)
        {
            $blank = $question->blanks
                ->where('id', $item['blank_id'])->first();

            if (! $blank)
            {
                throw CustomException::notFound('Blank');
            }

            if ($blank->case_sensitive)
            {
                if(in_array($item['answer'], $blank->correct_answers))
                {
                    $points += $blank->points;
                    $pointsEarned += $blank->points;
                }
            }
            else
            {
                $studentAnswer = trim(strtolower($item['answer']));
                $correctAnswers = array_map('trim', $blank->correct_answers);
                $correctAnswers = array_map('strtolower', $correctAnswers);

                if(in_array($studentAnswer, $correctAnswers))
                {
                    $points += $blank->points;
                    $pointsEarned += $blank->points;
                }
            }
        }

        $result['question_id'] = $question->id;
        $result['correct'] = $pointsEarned > 0 ? true : false;
        $result['points_earned'] = $pointsEarned;
        $result['points_possible'] = $pointsPossible;

        return [
            'points'=> $points,
            'result'=> $result,
        ];
    }

    private function multipleTypeQuestion(object $question, array $answer): array
    {
        $points = 0;

        switch ($question->type)
        {
            case QuestionBankMultipleTypeQuestionType::MultipleChoice:
                $isCorrect = true;

                foreach ($answer['option_ids'] as $item)
                {
                    $option = $question->options
                        ->where('id', $item)->first();

                    if (! $option)
                    {
                        throw CustomException::notFound('Option');
                    }

                    if (! $option->correct)
                    {
                        $isCorrect = false;
                        break;
                    }
                }

                if ($isCorrect)
                {
                    $points += $question->points;
                    $result['question_id'] = $question->id;
                    $result['correct'] = true;
                    $result['points_earned'] = $question->points;
                    $result['points_possible'] = $question->points;
                }
                else
                {
                    $result['question_id'] = $question->id;
                    $result['correct'] = false;
                    $result['points_earned'] = 0;
                    $result['points_possible'] = $question->points;
                }

                return [
                    'points'=> $points,
                    'result'=> $result,
                ];
            default:
                $option = $question->options
                    ->where('id', $answer['option_ids'][0])->first();

                if (! $option)
                {
                    throw CustomException::notFound('Option');
                }

                if ($option->correct)
                {
                    $points += $question->points;
                    $result['question_id'] = $question->id;
                    $result['correct'] = true;
                    $result['points_earned'] = $question->points;
                    $result['points_possible'] = $question->points;
                }
                else
                {
                    $result['question_id'] = $question->id;
                    $result['correct'] = false;
                    $result['points_earned'] = 0;
                    $result['points_possible'] = $question->points;
                }

                return [
                    'points'=> $points,
                    'result'=> $result,
                ];
        }
    }

    private function shortAnswerQuestion(object $question, array $answer): array
    {
        $points = 0;
        $isCorrect = false;

        foreach ($question->accepted_answers as $acceptedAnswer)
        {
            if ($acceptedAnswer['case_sensitive'])
            {
                if($acceptedAnswer['text'] == $answer['answer'])
                {
                    $isCorrect = true;
                    break;
                }
            }
            else
            {
                $studentAnswer = trim(strtolower($answer['answer']));
                $trimedAndLoweredAcceptedAnswer = trim(strtolower($acceptedAnswer['text']));

                if($trimedAndLoweredAcceptedAnswer == $studentAnswer)
                {
                    $isCorrect = true;
                    break;
                }
            }
        }

        if ($isCorrect)
        {
            $points += $question->points;
            $result['question_id'] = $question->id;
            $result['correct'] = true;
            $result['points_earned'] = $question->points;
            $result['points_possible'] = $question->points;
        }
        else
        {
            $result['question_id'] = $question->id;
            $result['correct'] = false;
            $result['points_earned'] = 0;
            $result['points_possible'] = $question->points;
        }

        return [
            'points'=> $points,
            'result'=> $result,
        ];
    }

    private function trueOrFalseQuestion(object $question, array $answer): array
    {
        $points = 0;
        $option = $question->options
            ->where('id', $answer['option_ids'][0])->first();

        if (! $option)
        {
            throw CustomException::notFound('Option');
        }

        if ($option->correct)
        {
            $points += $question->points;
            $result['question_id'] = $question->id;
            $result['correct'] = true;
            $result['points_earned'] = $question->points;
            $result['points_possible'] = $question->points;
        }
        else
        {
            $result['question_id'] = $question->id;
            $result['correct'] = false;
            $result['points_earned'] = 0;
            $result['points_possible'] = $question->points;
        }

        return [
            'points'=> $points,
            'result'=> $result,
        ];
    }

    private function checkChallengeScore80OnAssessmentRule(object $assessmentSubmit): void
    {
        $course = $assessmentSubmit->assessment->course;
        $challenges = $course->instructor->challenges;

        foreach ($challenges as $challenge)
        {
            $challengeRuleBadge = $challenge->challengeRuleBadges
                ->where('contentable_type', ModelTypePath::Rule->getTypePath())
                ->where('contentable_id', 3)->first();

            if ($challengeRuleBadge)
            {
                $challengeCourse = $challenge->challengeCourses->where('course_id', $course->id)->first();

                if ($challengeCourse)
                {
                    $challengeUser = $challenge->challengeUsers->where('student_id', $assessmentSubmit->student_id)->first();

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
                                            $this->checkChallengeScore80OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Weekly:
                                        if ($challenge->created_at->gt(now()->subWeek()))
                                        {
                                            $this->checkChallengeScore80OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Monthly:
                                        if ($challenge->created_at->gt(now()->subMonth()))
                                        {
                                            $this->checkChallengeScore80OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    default:
                                        $this->checkChallengeScore80OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        break;
                                }
                        }
                    }
                }
            }
        }
    }

    private function checkChallengeScore80OnAssessmentRuleDependingOnChallengeType(object $assessmentSubmit, object $challengeRuleBadge): void
    {
        $student = $assessmentSubmit->student;
        $assessment = $assessmentSubmit->assessment;
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

        if ($assessmentSubmit->score >= (0.8 * $assessmentPoints) && $assessmentSubmit->score < $assessmentPoints)
        {
            $this->awardToStudent($student, $challengeRuleBadge, 3);
        }
    }

    private function checkChallengeScore100OnAssessmentRule(object $assessmentSubmit): void
    {
        $course = $assessmentSubmit->assessment->course;
        $challenges = $course->instructor->challenges;

        foreach ($challenges as $challenge)
        {
            $challengeRuleBadge = $challenge->challengeRuleBadges
                ->where('contentable_type', ModelTypePath::Rule->getTypePath())
                ->where('contentable_id', 4)->first();

            if ($challengeRuleBadge)
            {
                $challengeCourse = $challenge->challengeCourses->where('course_id', $course->id)->first();

                if ($challengeCourse)
                {
                    $challengeUser = $challenge->challengeUsers->where('student_id', $assessmentSubmit->student_id)->first();

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
                                            $this->checkChallengeScore100OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Weekly:
                                        if ($challenge->created_at->gt(now()->subWeek()))
                                        {
                                            $this->checkChallengeScore100OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    case ChallengeType::Monthly:
                                        if ($challenge->created_at->gt(now()->subMonth()))
                                        {
                                            $this->checkChallengeScore100OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        }
                                        break;
                                    default:
                                        $this->checkChallengeScore100OnAssessmentRuleDependingOnChallengeType($assessmentSubmit, $challengeRuleBadge);
                                        break;
                                }
                        }
                    }
                }
            }
        }
    }

    private function checkChallengeScore100OnAssessmentRuleDependingOnChallengeType(object $assessmentSubmit, object $challengeRuleBadge): void
    {
        $student = $assessmentSubmit->student;
        $assessment = $assessmentSubmit->assessment;
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

        if ($assessmentSubmit->score == $assessmentPoints)
        {
            $this->awardToStudent($student, $challengeRuleBadge, 4);
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

    private function calculateTrend(array $values): GradeTrend
    {
        $average = array_sum($values) / count($values);

        if ($average >= 0 && $average <= 40) {
            $trend = GradeTrend::Down;
        } elseif ($average >= 41 && $average <= 59) {
            $trend = GradeTrend::Neutral;
        } elseif ($average >= 60 && $average <= 100) {
            $trend = GradeTrend::Up;
        }

        return $trend;
    }
}
