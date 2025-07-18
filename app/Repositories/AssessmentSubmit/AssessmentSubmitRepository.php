<?php

namespace App\Repositories\AssessmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\DataTransferObjects\AssessmentSubmit\AssessmentSubmitDto;
use Illuminate\Support\Facades\DB;

class AssessmentSubmitRepository extends BaseRepository implements AssessmentSubmitRepositoryInterface
{
    public function __construct(AssessmentSubmit $assessmentSubmit) {
        parent::__construct($assessmentSubmit);
    }

    public function all(AssessmentSubmitDto $dto, array $data): object
    {
        return (object) $this->model->latest('created_at')
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
    }

    public function delete(int $id): object
    {
        $assessmentSubmit = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $assessmentSubmit;
    }
}
