<?php

namespace App\Services\MediaEngagement;

use App\Repositories\MediaEngagement\MediaEngagementRepositoryInterface;
use App\Http\Requests\MediaEngagement\MediaEngagementRequest;
use App\Models\MediaEngagement\MediaEngagement;
use App\DataTransferObjects\MediaEngagement\MediaEngagementDto;
use Illuminate\Support\Facades\Auth;

class MediaEngagementService
{
    public function __construct(
        protected MediaEngagementRepositoryInterface $repository,
    ) {}

    public function store(MediaEngagementRequest $request): void
    {
        $dto = MediaEngagementDto::fromRequest($request);
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
