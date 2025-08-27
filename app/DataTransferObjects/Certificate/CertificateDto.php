<?php

namespace App\DataTransferObjects\Certificate;

use App\Http\Requests\Certificate\CertificateRequest;
use App\Enums\Certificate\CertificateType;
use App\Enums\Certificate\CertificateCondition;
use App\Enums\Certificate\CertificateStatus;

class CertificateDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $certificateTemplateId,
        public readonly ?CertificateType $type,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?CertificateCondition $condition,
        public readonly ?CertificateStatus $status,
    ) {}

    public static function fromIndexRequest(CertificateRequest $request): CertificateDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            certificateTemplateId: null,
            type: null,
            name: null,
            description: null,
            condition: null,
            status: null,
        );
    }

    public static function fromStoreRequest(CertificateRequest $request): CertificateDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            certificateTemplateId: $request->validated('certificate_template_id'),
            type: CertificateType::from($request->validated('type')),
            name: $request->validated('name'),
            description: $request->validated('description'),
            condition: CertificateCondition::from($request->validated('condition')),
            status: CertificateStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(CertificateRequest $request): CertificateDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            certificateTemplateId: $request->validated('certificate_template_id'),
            type: $request->validated('type') ?
                CertificateType::from($request->validated('type')) :
                null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            condition: $request->validated('condition') ?
                CertificateCondition::from($request->validated('condition')) :
                null,
            status: $request->validated('dastatusy') ?
                CertificateStatus::from($request->validated('status')) :
                null,
        );
    }
}
