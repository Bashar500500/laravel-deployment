<?php

namespace App\Repositories\UserActivity;

use App\DataTransferObjects\UserActivity\UserActivityDto;

interface UserActivityRepositoryInterface
{
    public function create(UserActivityDto $dto, array $data): void;
}
