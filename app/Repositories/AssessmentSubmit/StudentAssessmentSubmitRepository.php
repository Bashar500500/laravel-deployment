<?php

namespace App\Repositories\AssessmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\DataTransferObjects\AssessmentSubmit\AssessmentSubmitDto;
use Illuminate\Support\Facades\DB;

class StudentAssessmentSubmitRepository extends BaseRepository implements AssessmentSubmitRepositoryInterface
{
    public function __construct(AssessmentSubmit $assessmentSubmit) {
        parent::__construct($assessmentSubmit);
    }

    public function all(AssessmentSubmitDto $dto, array $data): object
    {
        return (object) $this->model->where('assessment_id', $dto->assessmentId)
            ->where('student_id', $data['studentId'])
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
        return (object) [];
    }

    public function delete(int $id): object
    {
        return (object) [];
    }
}
