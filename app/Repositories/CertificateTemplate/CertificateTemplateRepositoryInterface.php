<?php

namespace App\Repositories\CertificateTemplate;

use App\DataTransferObjects\CertificateTemplate\CertificateTemplateDto;

interface CertificateTemplateRepositoryInterface
{
    public function all(CertificateTemplateDto $dto): object;

    public function find(int $id): object;

    public function create(CertificateTemplateDto $dto): object;

    public function update(CertificateTemplateDto $dto, int $id): object;

    public function delete(int $id): object;
}
