<?php

namespace App\Repositories\Auth;

use App\DataTransferObjects\Auth\PasswordResetCodeDto;

interface PasswordResetCodeRepositoryInterface
{
    public function updateOrCreate(PasswordResetCodeDto $dto): object;

    public function delete(int $id): object;
}
