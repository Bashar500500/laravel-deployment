<?php

namespace App\Repositories\ContentEngagement;

use App\DataTransferObjects\ContentEngagement\ContentEngagementDto;

interface ContentEngagementRepositoryInterface
{
    public function create(ContentEngagementDto $dto, array $data): void;
}
