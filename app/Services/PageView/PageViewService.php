<?php

namespace App\Services\PageView;

use App\Repositories\PageView\PageViewRepositoryInterface;
use App\Http\Requests\PageView\PageViewRequest;
use App\Models\PageView\PageView;
use App\DataTransferObjects\PageView\PageViewDto;
use Illuminate\Support\Facades\Auth;

class PageViewService
{
    public function __construct(
        protected PageViewRepositoryInterface $repository,
    ) {}

    public function store(PageViewRequest $request): void
    {
        $dto = PageViewDto::fromRequest($request);
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
