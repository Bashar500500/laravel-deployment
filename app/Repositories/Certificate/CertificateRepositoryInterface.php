<?php

namespace App\Repositories\Certificate;

use App\DataTransferObjects\Certificate\CertificateDto;

interface CertificateRepositoryInterface
{
    public function all(CertificateDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(CertificateDto $dto, array $data): object;

    public function update(CertificateDto $dto, int $id): object;

    public function delete(int $id): object;
}
