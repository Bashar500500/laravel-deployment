<?php

namespace App\Services\Certificate;

use App\Repositories\Certificate\CertificateRepositoryInterface;
use App\Http\Requests\Certificate\CertificateRequest;
use App\Models\Certificate\Certificate;
use App\DataTransferObjects\Certificate\CertificateDto;
use Illuminate\Support\Facades\Auth;
use App\Enums\Certificate\CertificateType;
use App\Enums\Certificate\CertificateCondition;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;

class CertificateService
{
    public function __construct(
        protected CertificateRepositoryInterface $repository,
    ) {}

    public function index(CertificateRequest $request): object
    {
        $dto = CertificateDto::fromIndexRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->all($dto, $data);
    }

    public function show(Certificate $certificate): object
    {
        return $this->repository->find($certificate->id);
    }

    public function store(CertificateRequest $request): object
    {
        $dto = CertificateDto::fromStoreRequest($request);

        $this->checkCertificateTypeCondition($dto);

        $data = $this->prepareStoreAndIndexData();
        return $this->repository->create($dto, $data);
    }

    public function update(CertificateRequest $request, Certificate $certificate): object
    {
        $dto = CertificateDto::fromUpdateRequest($request);

        $this->checkCertificateTypeCondition($dto);

        return $this->repository->update($dto, $certificate->id);
    }

    public function destroy(Certificate $certificate): object
    {
        return $this->repository->delete($certificate->id);
    }

    private function prepareStoreAndIndexData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }

    private function checkCertificateTypeCondition(CertificateDto $dto): void
    {
        if (
            ($dto->type == CertificateType::Course && $dto->condition != CertificateCondition::CourseCompletion) ||
            ($dto->type == CertificateType::Section && $dto->condition != CertificateCondition::CompleteSection) ||
            ($dto->type == CertificateType::Achievement && $dto->condition != CertificateCondition::FinalScore) ||
            ($dto->type == CertificateType::Participation && $dto->condition != CertificateCondition::SessionAttendance)
        )
        {
            throw CustomException::forbidden(ModelName::Certificate, ForbiddenExceptionMessage::CertificateTypeCondition);
        }
    }
}
