<?php

namespace App\Repositories\QuestionBankShortAnswerQuestion;

use App\Repositories\BaseRepository;
use App\Models\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestion;
use App\DataTransferObjects\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\QuestionBankShortAnswerQuestion\AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class QuestionBankShortAnswerQuestionRepository extends BaseRepository implements QuestionBankShortAnswerQuestionRepositoryInterface
{
    public function __construct(QuestionBankShortAnswerQuestion $questionBankShortAnswerQuestion) {
        parent::__construct($questionBankShortAnswerQuestion);
    }

    public function all(QuestionBankShortAnswerQuestionDto $dto): object
    {
        return (object) $this->model->where('question_bank_id', $dto->questionBankId)
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

    public function create(QuestionBankShortAnswerQuestionDto $dto): object
    {
        $questionBankShortAnswerQuestion = DB::transaction(function () use ($dto) {
            $questionBankShortAnswerQuestion = (object) $this->model->create([
                'question_bank_id' => $dto->questionBankId,
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

            return $questionBankShortAnswerQuestion;
        });

        return (object) $questionBankShortAnswerQuestion;
            // ->load();
    }

    public function update(QuestionBankShortAnswerQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankShortAnswerQuestion = DB::transaction(function () use ($dto, $model) {
            $questionBankShortAnswerQuestion = tap($model)->update([
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

            return $questionBankShortAnswerQuestion;
        });

        return (object) $questionBankShortAnswerQuestion;
            // ->load();
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankShortAnswerQuestion = DB::transaction(function () use ($id, $model) {
            $model->assessmentQuestionBankQuestions()->delete();
            return parent::delete($id);
        });

        return (object) $questionBankShortAnswerQuestion;
    }

    public function addQuestionBankShortAnswerQuestionToAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::QuestionBankShortAnswerQuestion, ForbiddenExceptionMessage::QuestionBankShortAnswerQuestion);
        }

        DB::transaction(function () use ($dto, $model) {
            $model->assessmentQuestionBankQuestion()->create([
                'assessment_id' => $dto->assessmentId,
            ]);
        });
    }

    public function removeQuestionBankShortAnswerQuestionFromAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if (! $exists)
        {
            throw CustomException::notFound('QuestionBankShortAnswerQuestion');
        }

        DB::transaction(function () use ($exists) {
            $exists->delete();
        });
    }
}
