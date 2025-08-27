<?php

namespace App\Services\Whiteboard;

use App\Repositories\Whiteboard\WhiteboardRepositoryInterface;
use App\Http\Requests\Whiteboard\WhiteboardRequest;
use App\Models\Whiteboard\Whiteboard;
use App\DataTransferObjects\Whiteboard\WhiteboardDto;
use Illuminate\Support\Facades\Auth;

class WhiteboardService
{
    public function __construct(
        protected WhiteboardRepositoryInterface $repository,
    ) {}

    public function index(WhiteboardRequest $request): object
    {
        $dto = WhiteboardDto::fromIndexRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->all($dto, $data);
    }

    public function show(Whiteboard $whiteboard): object
    {
        return $this->repository->find($whiteboard->id);
    }

    public function store(WhiteboardRequest $request): object
    {
        $dto = WhiteboardDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->create($dto, $data);
    }

    public function update(WhiteboardRequest $request, Whiteboard $whiteboard): object
    {
        $dto = WhiteboardDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $whiteboard->id);
    }

    public function destroy(Whiteboard $whiteboard): object
    {
        return $this->repository->delete($whiteboard->id);
    }

    private function prepareStoreAndIndexData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
