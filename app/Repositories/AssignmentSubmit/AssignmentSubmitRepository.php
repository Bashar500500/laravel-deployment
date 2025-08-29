<?php

namespace App\Repositories\AssignmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\DataTransferObjects\AssignmentSubmit\AssignmentSubmitDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use ZipArchive;

class AssignmentSubmitRepository extends BaseRepository implements AssignmentSubmitRepositoryInterface
{
    public function __construct(AssignmentSubmit $assignmentSubmit) {
        parent::__construct($assignmentSubmit);
    }

    public function all(AssignmentSubmitDto $dto, array $data): object
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

    public function update(AssignmentSubmitDto $dto, int $id, array $data): object
    {
        $model = (object) parent::find($id);

        $assignmentSubmit = DB::transaction(function () use ($dto, $model) {
            $assignmentSubmit = tap($model)->update([
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            return $assignmentSubmit;
        });

        return (object) $assignmentSubmit;
    }

    public function delete(int $id): object
    {
        $assignmentSubmit = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $assignmentSubmit;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('AssignmentSubmit/' . $id . '/Files/' . $model->student_id . '/' . $fileName);

        if (!file_exists($file))
        {
            throw CustomException::notFound('File');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $files = Storage::disk('local')->files('AssignmentSubmit/' . $id . '/Files/' . $model->student_id);

        if (count($files) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Assignment-Submit.zip';
        $zipPath = storage_path('app/private/' . $zipName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $path = Storage::disk('local')->path($file);
                $zip->addFromString(basename($path), file_get_contents($path));
            }
            $zip->close();
        }

        return $zipPath;
    }
}
