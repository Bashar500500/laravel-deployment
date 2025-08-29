<?php

namespace App\Services\ContentEngagement;

use App\Repositories\ContentEngagement\ContentEngagementRepositoryInterface;
use App\Http\Requests\ContentEngagement\ContentEngagementRequest;
use App\Models\ContentEngagement\ContentEngagement;
use App\DataTransferObjects\ContentEngagement\ContentEngagementDto;
use Illuminate\Support\Facades\Auth;

class ContentEngagementService
{
    public function __construct(
        protected ContentEngagementRepositoryInterface $repository,
    ) {}

    public function store(ContentEngagementRequest $request): void
    {
        $dto = ContentEngagementDto::fromRequest($request);
        $data = $this->prepareStoreData();
        $this->repository->create($dto, $data);
    }

    private function prepareStoreData(): array
    {
        return [
            'studentId' => Auth::user()->id,
        ];
    }
}
