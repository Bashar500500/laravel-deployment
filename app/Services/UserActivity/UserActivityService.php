<?php

namespace App\Services\UserActivity;

use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Http\Requests\UserActivity\UserActivityRequest;
use App\Models\UserActivity\UserActivity;
use App\DataTransferObjects\UserActivity\UserActivityDto;
use Illuminate\Support\Facades\Auth;

class UserActivityService
{
    public function __construct(
        protected UserActivityRepositoryInterface $repository,
    ) {}

    public function store(UserActivityRequest $request): void
    {
        $dto = UserActivityDto::fromRequest($request);
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
