<?php

namespace App\Repositories\AssessmentMultipleTypeQuestion;

use App\Repositories\BaseRepository;
use App\Models\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestion;
use App\DataTransferObjects\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionDto;
use Illuminate\Support\Facades\DB;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class AssessmentMultipleTypeQuestionRepository extends BaseRepository implements AssessmentMultipleTypeQuestionRepositoryInterface
{
    public function __construct(AssessmentMultipleTypeQuestion $assessmentMultipleTypeQuestion) {
        parent::__construct($assessmentMultipleTypeQuestion);
    }

    public function all(AssessmentMultipleTypeQuestionDto $dto): object
    {
        return (object) $this->model->where('assessment_id', $dto->assessmentId)
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

    public function create(AssessmentMultipleTypeQuestionDto $dto): object
    {
        $assessmentMultipleTypeQuestion = DB::transaction(function () use ($dto) {
            $assessmentMultipleTypeQuestion = (object) $this->model->create([
                'assessment_id' => $dto->assessmentId,
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
                $assessmentMultipleTypeQuestion->option()->create([
                    'text' => $option['text'] ?? null,
                    'correct' => $option['correct'] ?? null,
                    'feedback' => $option['feedback'] ?? null,
                ]);
            }

            return $assessmentMultipleTypeQuestion;
        });

        return (object) $assessmentMultipleTypeQuestion->load('options');
    }

    public function update(AssessmentMultipleTypeQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentMultipleTypeQuestion = DB::transaction(function () use ($dto, $model) {
            $assessmentMultipleTypeQuestion = tap($model)->update([
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
                $assessmentMultipleTypeQuestion->options()->delete();

                foreach ($dto->options as $option)
                {
                    $assessmentMultipleTypeQuestion->option()->create([
                        'text' => $option['text'] ?? null,
                        'correct' => $option['correct'] ?? null,
                        'feedback' => $option['feedback'] ?? null,
                    ]);
                }
            }

            return $assessmentMultipleTypeQuestion;
        });

        return (object) $assessmentMultipleTypeQuestion->load('options');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentMultipleTypeQuestion = DB::transaction(function () use ($id, $model) {
            $model->options()->delete();
            return parent::delete($id);
        });

        return (object) $assessmentMultipleTypeQuestion;
    }

    public function addAssessmentMultipleTypeQuestionToQuestionBank(int $id): void
    {
        $model = (object) parent::find($id);
        $questionBank = $model->assessment->course->questionBank;

        if (! $questionBank)
        {
            throw CustomException::notFound('QuestionBank');
        }

        $exists = $questionBank->questionBankMultipleTypeQuestions
            ->where('type', $model->type)
            ->where('text', $model->text)
            ->where('points', $model->points)
            ->where('difficulty', $model->difficulty)
            ->where('category', $model->category)
            ->where('required', $model->required)
            ->where('additional_settings', $model->additional_settings)
            ->where('settings', $model->settings)
            ->where('feedback', $model->feedback)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::AssessmentMultipleTypeQuestion, ForbiddenExceptionMessage::AssessmentMultipleTypeQuestion);
        }

        DB::transaction(function () use ($questionBank, $model) {
            $questionBankMultipleTypeQuestion = $questionBank->questionBankMultipleTypeQuestions()->create([
                'type' => $model->type->value,
                'text' => $model->text,
                'points' => $model->points,
                'difficulty' => $model->difficulty->value,
                'category' => $model->category,
                'required' => $model->required,
                'additional_settings' => $model->additional_settings,
                'settings' => $model->settings,
                'feedback' => $model->feedback,
            ]);

            $options = $model->options;

            foreach ($options as $option)
            {
                $questionBankMultipleTypeQuestion->option()->create([
                    'text' => $option['text'],
                    'correct' => $option['correct'],
                    'feedback' => $option['feedback'],
                ]);
            }
        });
    }
}
