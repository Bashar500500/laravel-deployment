<?php

namespace App\Services\CertificateTemplate;

use App\Repositories\CertificateTemplate\CertificateTemplateRepositoryInterface;
use App\Http\Requests\CertificateTemplate\CertificateTemplateRequest;
use App\Models\CertificateTemplate\CertificateTemplate;
use App\DataTransferObjects\CertificateTemplate\CertificateTemplateDto;
use Illuminate\Support\Facades\Auth;

class CertificateTemplateService
{
    public function __construct(
        protected CertificateTemplateRepositoryInterface $repository,
    ) {}

    public function index(CertificateTemplateRequest $request): object
    {
        $dto = CertificateTemplateDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(CertificateTemplate $certificateTemplate): object
    {
        return $this->repository->find($certificateTemplate->id);
    }

    public function store(CertificateTemplateRequest $request): object
    {
        $dto = CertificateTemplateDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(CertificateTemplateRequest $request, CertificateTemplate $certificateTemplate): object
    {
        $dto = CertificateTemplateDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $certificateTemplate->id);
    }

    public function destroy(CertificateTemplate $certificateTemplate): object
    {
        return $this->repository->delete($certificateTemplate->id);
    }
}
