<?php

namespace App\Repositories\UserInteraction;

use App\DataTransferObjects\UserInteraction\UserInteractionDto;

interface UserInteractionRepositoryInterface
{
    public function create(UserInteractionDto $dto, array $data): void;
}
