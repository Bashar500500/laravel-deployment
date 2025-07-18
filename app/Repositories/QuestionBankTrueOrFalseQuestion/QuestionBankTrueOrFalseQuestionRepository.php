<?php

namespace App\Repositories\QuestionBankTrueOrFalseQuestion;

use App\Repositories\BaseRepository;
use App\Models\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestion;
use App\DataTransferObjects\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\QuestionBankTrueOrFalseQuestion\AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class QuestionBankTrueOrFalseQuestionRepository extends BaseRepository implements QuestionBankTrueOrFalseQuestionRepositoryInterface
{
    public function __construct(QuestionBankTrueOrFalseQuestion $questionBankTrueOrFalseQuestion) {
        parent::__construct($questionBankTrueOrFalseQuestion);
    }

    public function all(QuestionBankTrueOrFalseQuestionDto $dto): object
    {
        return (object) $this->model->where('question_bank_id', $dto->questionBankId)
            ->with('options')
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
            ->load('options');
    }

    public function create(QuestionBankTrueOrFalseQuestionDto $dto): object
    {
        $questionBankTrueOrFalseQuestion = DB::transaction(function () use ($dto) {
            $questionBankTrueOrFalseQuestion = (object) $this->model->create([
                'question_bank_id' => $dto->questionBankId,
                'text' => $dto->text,
                'points' => $dto->points,
                'difficulty' => $dto->difficulty,
                'category' => $dto->category,
                'required' => $dto->required,
                'correct_answer' => $dto->correctAnswer,
                'labels' => $dto->labels,
                'settings' => $dto->settings,
                'feedback' => $dto->feedback,
            ]);

            $questionBankTrueOrFalseQuestion->option()->createMany([
                [
                    'text' => 'true',
                    'correct' => $dto->correctAnswer == true ? true : false,
                ],
                [
                    'text' => 'false',
                    'correct' => $dto->correctAnswer == false ? true : false,
                ],
            ]);

            return $questionBankTrueOrFalseQuestion;
        });

        return (object) $questionBankTrueOrFalseQuestion->load('options');
    }

    public function update(QuestionBankTrueOrFalseQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankTrueOrFalseQuestion = DB::transaction(function () use ($dto, $model) {
            $questionBankTrueOrFalseQuestion = tap($model)->update([
                'text' => $dto->text ? $dto->text : $model->text,
                'points' => $dto->points ? $dto->points : $model->points,
                'difficulty' => $dto->difficulty ? $dto->difficulty : $model->difficulty,
                'category' => $dto->category ? $dto->category : $model->category,
                'required' => $dto->required ? $dto->required : $model->required,
                'correct_answer' => $dto->correctAnswer ? $dto->correctAnswer : $model->correct_answer,
                'labels' => $dto->labels ? $dto->labels : $model->labels,
                'settings' => $dto->settings ? $dto->settings : $model->settings,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            if ($dto->correctAnswer)
            {
                $questionBankTrueOrFalseQuestion->options()->delete();

                $questionBankTrueOrFalseQuestion->option()->createMany([
                    [
                        'text' => 'true',
                        'correct' => $dto->correctAnswer == true ? true : false,
                    ],
                    [
                        'text' => 'false',
                        'correct' => $dto->correctAnswer == false ? true : false,
                    ],
                ]);
            }

            return $questionBankTrueOrFalseQuestion;
        });

        return (object) $questionBankTrueOrFalseQuestion->load('options');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankTrueOrFalseQuestion = DB::transaction(function () use ($id, $model) {
            $model->options()->delete();
            $model->assessmentQuestionBankQuestions()->delete();
            return parent::delete($id);
        });

        return (object) $questionBankTrueOrFalseQuestion;
    }

    public function addQuestionBankTrueOrFalseQuestionToAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::QuestionBankTrueOrFalseQuestion, ForbiddenExceptionMessage::QuestionBankTrueOrFalseQuestion);
        }

        DB::transaction(function () use ($dto, $model) {
            $model->assessmentQuestionBankQuestion()->create([
                'assessment_id' => $dto->assessmentId,
            ]);
        });
    }

    public function removeQuestionBankTrueOrFalseQuestionFromAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if (! $exists)
        {
            throw CustomException::notFound('QuestionBankTrueOrFalseQuestion');
        }

        DB::transaction(function () use ($exists) {
            $exists->delete();
        });
    }
}
