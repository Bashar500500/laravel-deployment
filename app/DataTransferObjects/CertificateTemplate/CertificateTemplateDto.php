<?php

namespace App\DataTransferObjects\CertificateTemplate;

use App\Http\Requests\CertificateTemplate\CertificateTemplateRequest;

class CertificateTemplateDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?string $color,
    ) {}

    public static function fromIndexRequest(CertificateTemplateRequest $request): CertificateTemplateDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            color: null,
        );
    }

    public static function fromStoreRequest(CertificateTemplateRequest $request): CertificateTemplateDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            color: $request->validated('color'),
        );
    }

    public static function fromUpdateRequest(CertificateTemplateRequest $request): CertificateTemplateDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            color: $request->validated('color'),
        );
    }
}
