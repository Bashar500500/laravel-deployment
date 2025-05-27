<?php

namespace App\Repositories\Email;

use App\DataTransferObjects\Email\EmailDto;

interface EmailRepositoryInterface
{
    public function all(EmailDto $dto): object;

    public function find(int $id): object;

    public function create(EmailDto $dto): object;

    public function delete(int $id): object;
}
