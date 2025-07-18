<?php

namespace App\Repositories\QuestionBank;

use App\Repositories\BaseRepository;
use App\Models\QuestionBank\QuestionBank;
use App\DataTransferObjects\QuestionBank\QuestionBankDto;
use Illuminate\Support\Facades\DB;

class QuestionBankRepository extends BaseRepository implements QuestionBankRepositoryInterface
{
    public function __construct(QuestionBank $questionBank) {
        parent::__construct($questionBank);
    }

    public function all(QuestionBankDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('questionBankMultipleTypeQuestions', 'questionBankTrueOrFalseQuestions', 'questionBankShortAnswerQuestions', 'questionBankFillInBlankQuestions')
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
            ->load('questionBankMultipleTypeQuestions', 'questionBankTrueOrFalseQuestions', 'questionBankShortAnswerQuestions', 'questionBankFillInBlankQuestions');
    }

    public function create(QuestionBankDto $dto): object
    {
        $questionBank = DB::transaction(function () use ($dto) {
            $questionBank = (object) $this->model->create([
                'course_id' => $dto->courseId,
            ]);

            return $questionBank;
        });

        return (object) $questionBank->load('questionBankMultipleTypeQuestions', 'questionBankTrueOrFalseQuestions', 'questionBankShortAnswerQuestions', 'questionBankFillInBlankQuestions');
    }

    // public function update(QuestionBankDto $dto, int $id): object
    // {
    //     $model = (object) parent::find($id);

    //     $questionBank = DB::transaction(function () use ($dto, $model) {
    //         $questionBank = tap($model)->update([
    //         ]);

    //         return $questionBank;
    //     });

    //     return (object) $questionBank->load();
    // }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $questionBank = DB::transaction(function () use ($id, $model) {
            $questionBankMultipleTypeQuestions = $model->questionBankMultipleTypeQuestions;
            $questionBankTrueOrFalseQuestions = $model->questionBankTrueOrFalseQuestions;
            $questionBankShortAnswerQuestions = $model->questionBankShortAnswerQuestions;
            $questionBankFillInBlankQuestions = $model->questionBankFillInBlankQuestions;

            foreach ($questionBankMultipleTypeQuestions as $questionBankMultipleTypeQuestion)
            {
                $questionBankMultipleTypeQuestion->options()->delete();
                $questionBankMultipleTypeQuestion->assessmentQuestionBankQuestions()->delete();
            }
            foreach ($questionBankTrueOrFalseQuestions as $questionBankTrueOrFalseQuestion)
            {
                $questionBankTrueOrFalseQuestion->options()->delete();
                $questionBankTrueOrFalseQuestion->assessmentQuestionBankQuestions()->delete();
            }
            foreach ($questionBankShortAnswerQuestions as $questionBankShortAnswerQuestion)
            {
                $questionBankShortAnswerQuestion->blanks()->delete();
                $questionBankShortAnswerQuestion->assessmentQuestionBankQuestions()->delete();
            }
            foreach ($questionBankFillInBlankQuestions as $questionBankFillInBlankQuestion)
            {
                $questionBankFillInBlankQuestion->assessmentQuestionBankQuestions()->delete();
            }

            return parent::delete($id);
        });

        return (object) $questionBank;
    }
}
