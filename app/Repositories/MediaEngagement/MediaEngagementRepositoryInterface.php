<?php

namespace App\Repositories\MediaEngagement;

use App\DataTransferObjects\MediaEngagement\MediaEngagementDto;

interface MediaEngagementRepositoryInterface
{
    public function create(MediaEngagementDto $dto, array $data): void;
}
