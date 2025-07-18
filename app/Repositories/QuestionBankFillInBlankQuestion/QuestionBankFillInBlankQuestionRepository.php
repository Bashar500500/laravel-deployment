<?php

namespace App\Repositories\QuestionBankFillInBlankQuestion;

use App\Repositories\BaseRepository;
use App\Models\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestion;
use App\DataTransferObjects\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\QuestionBankFillInBlankQuestion\AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class QuestionBankFillInBlankQuestionRepository extends BaseRepository implements QuestionBankFillInBlankQuestionRepositoryInterface
{
    public function __construct(QuestionBankFillInBlankQuestion $questionBankFillInBlankQuestion) {
        parent::__construct($questionBankFillInBlankQuestion);
    }

    public function all(QuestionBankFillInBlankQuestionDto $dto): object
    {
        return (object) $this->model->where('question_bank_id', $dto->questionBankId)
            ->with('blanks')
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
            ->load('blanks');
    }

    public function create(QuestionBankFillInBlankQuestionDto $dto): object
    {
        $questionBankFillInBlankQuestion = DB::transaction(function () use ($dto) {
            $questionBankFillInBlankQuestion = (object) $this->model->create([
                'question_bank_id' => $dto->questionBankId,
                'text' => $dto->text,
                'difficulty' => $dto->difficulty,
                'category' => $dto->category,
                'required' => $dto->required,
                'display_options' => $dto->displayOptions,
                'grading_options' => $dto->gradingOptions,
                'settings' => $dto->settings,
                'feedback' => $dto->feedback,
            ]);

            foreach ($dto->blanks as $blank)
            {
                $questionBankFillInBlankQuestion->blank()->create([
                    'correct_answers' => $blank['correct_answers'] ?? null,
                    'points' => $blank['points'] ?? null,
                    'case_sensitive' => $blank['case_sensitive'] ?? null,
                    'exact_match' => $blank['exact_match'] ?? null,
                    'hint' => $blank['hint'] ?? null,
                ]);
            }

            return $questionBankFillInBlankQuestion;
        });

        return (object) $questionBankFillInBlankQuestion->load('blanks');
    }

    public function update(QuestionBankFillInBlankQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankFillInBlankQuestion = DB::transaction(function () use ($dto, $model) {
            $questionBankFillInBlankQuestion = tap($model)->update([
                'text' => $dto->text ? $dto->text : $model->text,
                'difficulty' => $dto->difficulty ? $dto->difficulty : $model->difficulty,
                'category' => $dto->category ? $dto->category : $model->category,
                'required' => $dto->required ? $dto->required : $model->required,
                'display_options' => $dto->displayOptions ? $dto->displayOptions : $model->display_options,
                'grading_options' => $dto->gradingOptions ? $dto->gradingOptions : $model->grading_options,
                'settings' => $dto->settings ? $dto->settings : $model->settings,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            if ($dto->blanks)
            {
                $questionBankFillInBlankQuestion->blanks()->delete();

                foreach ($dto->blanks as $blank)
                {
                    $questionBankFillInBlankQuestion->blank()->create([
                        'correct_answers' => $blank['correct_answers'] ?? null,
                        'points' => $blank['points'] ?? null,
                        'case_sensitive' => $blank['case_sensitive'] ?? null,
                        'exact_match' => $blank['exact_match'] ?? null,
                        'hint' => $blank['hint'] ?? null,
                    ]);
                }
            }

            return $questionBankFillInBlankQuestion;
        });

        return (object) $questionBankFillInBlankQuestion->load('blanks');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $questionBankFillInBlankQuestion = DB::transaction(function () use ($id, $model) {
            $model->blanks()->delete();
            $model->assessmentQuestionBankQuestions()->delete();
            return parent::delete($id);
        });

        return (object) $questionBankFillInBlankQuestion;
    }

    public function addQuestionBankFillInBlankQuestionToAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::QuestionBankFillInBlankQuestion, ForbiddenExceptionMessage::QuestionBankFillInBlankQuestion);
        }

        DB::transaction(function () use ($dto, $model) {
            $model->assessmentQuestionBankQuestion()->create([
                'assessment_id' => $dto->assessmentId,
            ]);
        });
    }

    public function removeQuestionBankFillInBlankQuestionFromAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto $dto, int $id): void
    {
        $model = (object) parent::find($id);
        $exists = $model->assessmentQuestionBankQuestions->where('assessment_id', $dto->assessmentId)->first();

        if (! $exists)
        {
            throw CustomException::notFound('QuestionBankFillInBlankQuestion');
        }

        DB::transaction(function () use ($exists) {
            $exists->delete();
        });
    }
}
