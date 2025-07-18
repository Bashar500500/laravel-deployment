<?php

namespace App\Repositories\AssessmentTrueOrFalseQuestion;

use App\Repositories\BaseRepository;
use App\Models\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestion;
use App\DataTransferObjects\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionDto;
use Illuminate\Support\Facades\DB;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class AssessmentTrueOrFalseQuestionRepository extends BaseRepository implements AssessmentTrueOrFalseQuestionRepositoryInterface
{
    public function __construct(AssessmentTrueOrFalseQuestion $assessmentTrueOrFalseQuestion) {
        parent::__construct($assessmentTrueOrFalseQuestion);
    }

    public function all(AssessmentTrueOrFalseQuestionDto $dto): object
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

    public function create(AssessmentTrueOrFalseQuestionDto $dto): object
    {
        $assessmentTrueOrFalseQuestion = DB::transaction(function () use ($dto) {
            $assessmentTrueOrFalseQuestion = (object) $this->model->create([
                'assessment_id' => $dto->assessmentId,
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

            $assessmentTrueOrFalseQuestion->option()->createMany([
                [
                    'text' => 'true',
                    'correct' => $dto->correctAnswer == true ? true : false,
                ],
                [
                    'text' => 'false',
                    'correct' => $dto->correctAnswer == false ? true : false,
                ],
            ]);

            return $assessmentTrueOrFalseQuestion;
        });

        return (object) $assessmentTrueOrFalseQuestion->load('options');
    }

    public function update(AssessmentTrueOrFalseQuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentTrueOrFalseQuestion = DB::transaction(function () use ($dto, $model) {
            $assessmentTrueOrFalseQuestion = tap($model)->update([
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
                $assessmentTrueOrFalseQuestion->options()->delete();

                $assessmentTrueOrFalseQuestion->option()->createMany([
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

            return $assessmentTrueOrFalseQuestion;
        });

        return (object) $assessmentTrueOrFalseQuestion->load('options');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentTrueOrFalseQuestion = DB::transaction(function () use ($id, $model) {
            $model->options()->delete();
            return parent::delete($id);
        });

        return (object) $assessmentTrueOrFalseQuestion;
    }

    public function addAssessmentTrueOrFalseQuestionToQuestionBank(int $id): void
    {
        $model = (object) parent::find($id);
        $questionBank = $model->assessment->course->questionBank;

        if (! $questionBank)
        {
            throw CustomException::notFound('QuestionBank');
        }

        $exists = $questionBank->questionBankTrueOrFalseQuestions
            ->where('text', $model->text)
            ->where('points', $model->points)
            ->where('difficulty', $model->difficulty)
            ->where('category', $model->category)
            ->where('required', $model->required)
            ->where('correct_answer', $model->correct_answer)
            ->where('labels', $model->labels)
            ->where('settings', $model->settings)
            ->where('feedback', $model->feedback)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::AssessmentTrueOrFalseQuestion, ForbiddenExceptionMessage::AssessmentTrueOrFalseQuestion);
        }

        DB::transaction(function () use ($questionBank, $model) {
            $questionBankTrueOrFalseQuestion = $questionBank->questionBankTrueOrFalseQuestions()->create([
                'text' => $model->text,
                'points' => $model->points,
                'difficulty' => $model->difficulty->value,
                'category' => $model->category,
                'required' => $model->required,
                'correct_answer' => $model->correct_answer,
                'labels' => $model->labels,
                'settings' => $model->settings,
                'feedback' => $model->feedback,
            ]);

            $questionBankTrueOrFalseQuestion->option()->createMany([
                [
                    'text' => 'true',
                    'correct' => $model->correct_answer == true ? true : false,
                ],
                [
                    'text' => 'false',
                    'correct' => $model->correct_answer == false ? true : false,
                ],
            ]);
        });
    }
}
