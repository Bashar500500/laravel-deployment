<?php

namespace App\Repositories\PageView;

use App\DataTransferObjects\PageView\PageViewDto;

interface PageViewRepositoryInterface
{
    public function create(PageViewDto $dto, array $data): void;
}
