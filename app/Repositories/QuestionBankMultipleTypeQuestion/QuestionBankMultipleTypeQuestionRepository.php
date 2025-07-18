<?php

namespace App\Repositories\QuestionBankMultipleTypeQuestion;

use App\Repositories\BaseRepository;
use App\Models\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestion;
use App\DataTransferObjects\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\QuestionBankMultipleTypeQuestion\AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class QuestionBankMultipleTypeQuestionRepository extends BaseRepository implements QuestionBankMultipleTypeQuestionRepositoryInterface
{
    public function __construct(QuestionBankMultipleTypeQuestion $questionBankMultipleTypeQuestion) {
        parent::__construct($questionBankMultipleTypeQuestion);
    }

    public function all(QuestionBankMultipleTypeQuestionDto $dto): object
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

    public function create(QuestionBankMultipleTypeQuestionDto $dto): object
    {
        $questionBankMultipleTypeQuestion = DB::transaction(function () use ($dto) {
            $questionBankMultipleTypeQuestion = (object) $this->model->create([
                'question_bank_id' => $dto->questionBankId,
                'type' => $dto->type,
                'text' => $dto->text,
                'points' => $dto->points,
                'difficulty' => $dto->difficulty,
                'category' => $dto->category,
                'required' => $dto->required,
                'additional_settings' => $dto->additionalSettings,
                'settings' => $dto->settings,
                'feedback' => $dto->feedback,
            ]);

            foreach ($dto->options as $option)
            {
                $questionBankMultipleTypeQuestion->option()->create([
                    'text' => $option['text'] ?? null,
                    'correct' => $option['correct'] ?? null,
                    'feedback' => $option['feedback'] ?? null,
                ]);
            }

            return $questionBankMultipleTypeQuestion;
        });

        return (object) $questionBankMultipleTypeQuestion->load('options');
    }

    public function update(QuestionBankMultipleTypeQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankMultipleTypeQuestion = DB::transaction(function () use ($dto, $model) {
            $questionBankMultipleTypeQuestion = tap($model)->update([
                'type' => $dto->type ? $dto->type : $model->type,
                'text' => $dto->text ? $dto->text : $model->text,
                'points' => $dto->points ? $dto->points : $model->points,
                'difficulty' => $dto->difficulty ? $dto->difficulty : $model->difficulty,
                'category' => $dto->category ? $dto->category : $model->category,
                'required' => $dto->required ? $dto->required : $model->required,
                'additional_settings' => $dto->additionalSettings ? $dto->additionalSettings : $model->additional_settings,
                'settings' => $dto->settings ? $dto->settings : $model->settings,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            if ($dto->options)
            {
                $questionBankMultipleTypeQuestion->options()->delete();

                foreach ($dto->options as $option)
                {
                    $questionBankMultipleTypeQuestion->option()->create([
                        'text' => $option['text'] ?? null,
                        'correct' => $option['correct'] ?? null,
                        'feedback' => $option['feedback'] ?? null,
                    ]);
                }
            }

            return $questionBankMultipleTypeQuestion;
        });

        return (object) $questionBankMultipleTypeQuestion->load('options');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankMultipleTypeQuestion = DB::transaction(function () use ($id, $model) {
            $model->options()->delete();
            $model->assessmentQuestionBankQuestions()->delete();
            return parent::delete($id);
        });

        return (object) $questionBankMultipleTypeQuestion;
    }

    public function addQuestionBankMultipleTypeQuestionToAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::QuestionBankMultipleTypeQuestion, ForbiddenExceptionMessage::QuestionBankMultipleTypeQuestion);
        }

        DB::transaction(function () use ($dto, $model) {
            $model->assessmentQuestionBankQuestion()->create([
                'assessment_id' => $dto->assessmentId,
            ]);
        });
    }

    public function removeQuestionBankMultipleTypeQuestionFromAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if (! $exists)
        {
            throw CustomException::notFound('QuestionBankMultipleTypeQuestion');
        }

        DB::transaction(function () use ($exists) {
            $exists->delete();
        });
    }
}
