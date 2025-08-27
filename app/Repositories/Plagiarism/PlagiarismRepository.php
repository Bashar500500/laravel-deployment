<?php

namespace App\Repositories\Plagiarism;

use App\Repositories\BaseRepository;
use App\Models\Plagiarism\Plagiarism;
use App\DataTransferObjects\Plagiarism\PlagiarismDto;
use App\Enums\Plagiarism\PlagiarismStatus;
use Illuminate\Support\Facades\DB;

class PlagiarismRepository extends BaseRepository implements PlagiarismRepositoryInterface
{
    public function __construct(Plagiarism $plagiarism) {
        parent::__construct($plagiarism);
    }

    public function all(PlagiarismDto $dto): object
    {
        return (object) $this->model->where('assignment_submit_id', $dto->assignmentSubmitId)
            ->with('assignmentSubmit')
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
            ->load('assignmentSubmit');
    }

    public function update(PlagiarismDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $plagiarism = DB::transaction(function () use ($dto, $model) {
            $plagiarism = tap($model)->update([
                'score' => $dto->score ? $dto->score : $model->score,
                'status' => $dto->score < 30 ? PlagiarismStatus::Clear : PlagiarismStatus::Flagged,
            ]);

            return $plagiarism;
        });

        return (object) $plagiarism->load('assignmentSubmit');
    }

    public function delete(int $id): object
    {
        $plagiarism = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $plagiarism;
    }
}
