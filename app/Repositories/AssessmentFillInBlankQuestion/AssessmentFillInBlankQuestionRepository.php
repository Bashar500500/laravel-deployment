<?php

namespace App\Repositories\AssessmentFillInBlankQuestion;

use App\Repositories\BaseRepository;
use App\Models\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestion;
use App\DataTransferObjects\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionDto;
use Illuminate\Support\Facades\DB;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class AssessmentFillInBlankQuestionRepository extends BaseRepository implements AssessmentFillInBlankQuestionRepositoryInterface
{
    public function __construct(AssessmentFillInBlankQuestion $assessmentFillInBlankQuestion) {
        parent::__construct($assessmentFillInBlankQuestion);
    }

    public function all(AssessmentFillInBlankQuestionDto $dto): object
    {
        return (object) $this->model->where('assessment_id', $dto->assessmentId)
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

    public function create(AssessmentFillInBlankQuestionDto $dto): object
    {
        $assessmentFillInBlankQuestion = DB::transaction(function () use ($dto) {
            $assessmentFillInBlankQuestion = (object) $this->model->create([
                'assessment_id' => $dto->assessmentId,
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
                $assessmentFillInBlankQuestion->blank()->create([
                    'correct_answers' => $blank['correct_answers'] ?? null,
                    'points' => $blank['points'] ?? null,
                    'case_sensitive' => $blank['case_sensitive'] ?? null,
                    'exact_match' => $blank['exact_match'] ?? null,
                    'hint' => $blank['hint'] ?? null,
                ]);
            }

            return $assessmentFillInBlankQuestion;
        });

        return (object) $assessmentFillInBlankQuestion->load('blanks');
    }

    public function update(AssessmentFillInBlankQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentFillInBlankQuestion = DB::transaction(function () use ($dto, $model) {
            $assessmentFillInBlankQuestion = tap($model)->update([
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
                $assessmentFillInBlankQuestion->blanks()->delete();

                foreach ($dto->blanks as $blank)
                {
                    $assessmentFillInBlankQuestion->blank()->create([
                        'correct_answers' => $blank['correct_answers'] ?? null,
                        'points' => $blank['points'] ?? null,
                        'case_sensitive' => $blank['case_sensitive'] ?? null,
                        'exact_match' => $blank['exact_match'] ?? null,
                        'hint' => $blank['hint'] ?? null,
                    ]);
                }
            }

            return $assessmentFillInBlankQuestion;
        });

        return (object) $assessmentFillInBlankQuestion->load('blanks');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentFillInBlankQuestion = DB::transaction(function () use ($id, $model) {
            $model->blanks()->delete();
            return parent::delete($id);
        });

        return (object) $assessmentFillInBlankQuestion;
    }

    public function addAssessmentFillInBlankQuestionToQuestionBank(int $id): void
    {
        $model = (object) parent::find($id);
        $questionBank = $model->assessment->course->questionBank;

        if (! $questionBank)
        {
            throw CustomException::notFound('QuestionBank');
        }

        $exists = $questionBank->questionBankFillInBlankQuestions
            ->where('text', $model->text)
            ->where('points', $model->points)
            ->where('difficulty', $model->difficulty)
            ->where('category', $model->category)
            ->where('required', $model->required)
            ->where('display_options', $model->display_options)
            ->where('grading_options', $model->grading_options)
            ->where('settings', $model->settings)
            ->where('feedback', $model->feedback)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::AssessmentFillInBlankQuestion, ForbiddenExceptionMessage::AssessmentFillInBlankQuestion);
        }

        DB::transaction(function () use ($questionBank, $model) {
            $questionBankFillInBlankQuestion = $questionBank->questionBankFillInBlankQuestions()->create([
                'text' => $model->text,
                'points' => $model->points,
                'difficulty' => $model->difficulty->value,
                'category' => $model->category,
                'required' => $model->required,
                'display_options' => $model->display_options,
                'grading_options' => $model->grading_options,
                'settings' => $model->settings,
                'feedback' => $model->feedback,
            ]);

            $blanks = $model->blanks;

            foreach ($blanks as $blank)
            {
                $questionBankFillInBlankQuestion->blank()->create([
                    'correct_answers' => $blank['correct_answers'],
                    'points' => $blank['points'],
                    'case_sensitive' => $blank['case_sensitive'],
                    'exact_match' => $blank['exact_match'],
                    'hint' => $blank['hint'],
                ]);
            }
        });
    }
}
