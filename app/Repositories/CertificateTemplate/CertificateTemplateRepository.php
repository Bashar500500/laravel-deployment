<?php

namespace App\Repositories\CertificateTemplate;

use App\Repositories\BaseRepository;
use App\Models\CertificateTemplate\CertificateTemplate;
use App\DataTransferObjects\CertificateTemplate\CertificateTemplateDto;
use Illuminate\Support\Facades\DB;

class CertificateTemplateRepository extends BaseRepository implements CertificateTemplateRepositoryInterface
{
    public function __construct(CertificateTemplate $certificateTemplate) {
        parent::__construct($certificateTemplate);
    }

    public function all(CertificateTemplateDto $dto): object
    {
        return (object) $this->model
            // ->with('')
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
            // ->load('');
    }

    public function create(CertificateTemplateDto $dto): object
    {
        $certificateTemplate = DB::transaction(function () use ($dto) {
            $certificateTemplate = (object) $this->model->create([
                'name' => $dto->name,
                'color' => $dto->color,
            ]);

            return $certificateTemplate;
        });

        return (object) $certificateTemplate;
            // ->load('');
    }

    public function update(CertificateTemplateDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $certificateTemplate = DB::transaction(function () use ($dto, $model) {
            $certificateTemplate = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'color' => $dto->color ? $dto->color : $model->color,
            ]);

            return $certificateTemplate;
        });

        return (object) $certificateTemplate;
            // ->load('');
    }

    public function delete(int $id): object
    {
        $certificateTemplate = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $certificateTemplate;
    }
}
