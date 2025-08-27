<?php

namespace App\Repositories\Certificate;

use App\Repositories\BaseRepository;
use App\Models\Certificate\Certificate;
use App\DataTransferObjects\Certificate\CertificateDto;
use Illuminate\Support\Facades\DB;

class CertificateRepository extends BaseRepository implements CertificateRepositoryInterface
{
    public function __construct(Certificate $certificate) {
        parent::__construct($certificate);
    }

    public function all(CertificateDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $data['instructorId'])
            ->with('instructor', 'certificateTemplate')
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
        return (object) parent::find($id)->load('instructor', 'certificateTemplate');
    }

    public function create(CertificateDto $dto, array $data): object
    {
        $certificate = DB::transaction(function () use ($dto, $data) {
            $certificate = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'certificate_template_id' => $dto->certificateTemplateId,
                'type' => $dto->type,
                'name' => $dto->name,
                'description' => $dto->description,
                'condition' => $dto->condition,
                'status' => $dto->status,
                'settings' => [],
            ]);

            return $certificate;
        });

        return (object) $certificate->load('instructor', 'certificateTemplate');
    }

    public function update(CertificateDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $certificate = DB::transaction(function () use ($dto, $model) {
            $certificate = tap($model)->update([
                'certificate_template_id' => $dto->certificateTemplateId ? $dto->certificateTemplateId : $model->certificate_template_id,
                'type' => $dto->type ? $dto->type : $model->type,
                'name' => $dto->name ? $dto->name : $model->name,
                'description' => $dto->description ? $dto->description : $model->description,
                'condition' => $dto->condition ? $dto->condition : $model->condition,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $certificate;
        });

        return (object) $certificate->load('instructor', 'certificateTemplate');
    }

    public function delete(int $id): object
    {
        $certificate = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $certificate;
    }
}
