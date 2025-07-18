<?php

namespace App\Repositories\AssessmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\DataTransferObjects\AssessmentSubmit\AssessmentSubmitDto;
use Illuminate\Support\Facades\DB;

class InstructorAssessmentSubmitRepository extends BaseRepository implements AssessmentSubmitRepositoryInterface
{
    public function __construct(AssessmentSubmit $assessmentSubmit) {
        parent::__construct($assessmentSubmit);
    }

    public function all(AssessmentSubmitDto $dto, array $data): object
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

    public function update(AssessmentSubmitDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $assessmentSubmit = DB::transaction(function () use ($dto, $model) {
            $assessmentSubmit = tap($model)->update([
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            return $assessmentSubmit;
        });

        return (object) $assessmentSubmit;
            // ->load();
    }

    public function delete(int $id): object
    {
        $assessmentSubmit = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $assessmentSubmit;
    }
}
