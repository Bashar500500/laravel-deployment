<?php

namespace App\Repositories\AssessmentShortAnswerQuestion;

use App\Repositories\BaseRepository;
use App\Models\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestion;
use App\DataTransferObjects\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionDto;
use Illuminate\Support\Facades\DB;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class AssessmentShortAnswerQuestionRepository extends BaseRepository implements AssessmentShortAnswerQuestionRepositoryInterface
{
    public function __construct(AssessmentShortAnswerQuestion $assessmentShortAnswerQuestion) {
        parent::__construct($assessmentShortAnswerQuestion);
    }

    public function all(AssessmentShortAnswerQuestionDto $dto): object
    {
        return (object) $this->model->where('assessment_id', $dto->assessmentId)
            // ->with()
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
        return (object) parent::find($id);
            // ->load();
    }

    public function create(AssessmentShortAnswerQuestionDto $dto): object
    {
        $assessmentShortAnswerQuestion = DB::transaction(function () use ($dto) {
            $assessmentShortAnswerQuestion = (object) $this->model->create([
                'assessment_id' => $dto->assessmentId,
                'text' => $dto->text,
                'points' => $dto->points,
                'difficulty' => $dto->difficulty,
                'category' => $dto->category,
                'required' => $dto->required,
                'answer_type' => $dto->answerType,
                'character_limit' => $dto->characterLimit,
                'accepted_answers' => $dto->acceptedAnswers,
                'settings' => $dto->settings,
                'feedback' => $dto->feedback,
            ]);

            return $assessmentShortAnswerQuestion;
        });

        return (object) $assessmentShortAnswerQuestion;
            // ->load();
    }

    public function update(AssessmentShortAnswerQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentShortAnswerQuestion = DB::transaction(function () use ($dto, $model) {
            $assessmentShortAnswerQuestion = tap($model)->update([
                'text' => $dto->text ? $dto->text : $model->text,
                'points' => $dto->points ? $dto->points : $model->points,
                'difficulty' => $dto->difficulty ? $dto->difficulty : $model->difficulty,
                'category' => $dto->category ? $dto->category : $model->category,
                'required' => $dto->required ? $dto->required : $model->required,
                'answer_type' => $dto->answerType ? $dto->answerType : $model->answer_type,
                'character_limit' => $dto->characterLimit ? $dto->characterLimit : $model->character_limit,
                'accepted_answers' => $dto->acceptedAnswers ? $dto->acceptedAnswers : $model->accepted_answers,
                'settings' => $dto->settings ? $dto->settings : $model->settings,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            return $assessmentShortAnswerQuestion;
        });

        return (object) $assessmentShortAnswerQuestion;
            // ->load();
    }

    public function delete(int $id): object
    {
        $assessmentShortAnswerQuestion = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $assessmentShortAnswerQuestion;
    }

    public function addAssessmentShortAnswerQuestionToQuestionBank(int $id): void
    {
        $model = (object) parent::find($id);
        $questionBank = $model->assessment->course->questionBank;

        if (! $questionBank)
        {
            throw CustomException::notFound('QuestionBank');
        }

        $exists = $questionBank->questionBankShortAnswerQuestions
            ->where('text', $model->text)
            ->where('points', $model->points)
            ->where('difficulty', $model->difficulty)
            ->where('category', $model->category)
            ->where('required', $model->required)
            ->where('answer_type', $model->answer_type)
            ->where('character_limit', $model->character_limit)
            ->where('accepted_answers', $model->accepted_answers)
            ->where('settings', $model->settings)
            ->where('feedback', $model->feedback)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::AssessmentShortAnswerQuestion, ForbiddenExceptionMessage::AssessmentShortAnswerQuestion);
        }

        DB::transaction(function () use ($questionBank, $model) {
            $questionBank->questionBankShortAnswerQuestions()->create([
                'text' => $model->text,
                'points' => $model->points,
                'difficulty' => $model->difficulty->value,
                'category' => $model->category,
                'required' => $model->required,
                'answer_type' => $model->answer_type->value,
                'character_limit' => $model->character_limit,
                'accepted_answers' => $model->accepted_answers,
                'settings' => $model->settings,
                'feedback' => $model->feedback,
            ]);
        });
    }
}
