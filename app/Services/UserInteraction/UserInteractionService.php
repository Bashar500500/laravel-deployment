<?php

namespace App\Services\UserInteraction;

use App\Repositories\UserInteraction\UserInteractionRepositoryInterface;
use App\Http\Requests\UserInteraction\UserInteractionRequest;
use App\Models\UserInteraction\UserInteraction;
use App\DataTransferObjects\UserInteraction\UserInteractionDto;
use Illuminate\Support\Facades\Auth;

class UserInteractionService
{
    public function __construct(
        protected UserInteractionRepositoryInterface $repository,
    ) {}

    public function store(UserInteractionRequest $request): void
    {
        $dto = UserInteractionDto::fromRequest($request);
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
