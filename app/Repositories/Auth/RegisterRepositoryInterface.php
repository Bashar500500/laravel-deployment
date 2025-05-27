<?php

namespace App\Repositories\Auth;

use App\DataTransferObjects\Auth\RegisterDto;

interface RegisterRepositoryInterface
{
    public function create(RegisterDto $dto): object;
}
