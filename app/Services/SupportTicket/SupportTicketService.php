<?php

namespace App\Services\SupportTicket;

use App\Repositories\SupportTicket\SupportTicketRepositoryInterface;
use App\Http\Requests\SupportTicket\SupportTicketRequest;
use App\Models\SupportTicket\SupportTicket;
use App\DataTransferObjects\SupportTicket\SupportTicketDto;
use Illuminate\Support\Facades\Auth;

class SupportTicketService
{
    public function __construct(
        protected SupportTicketRepositoryInterface $repository,
    ) {}

    public function index(SupportTicketRequest $request): object
    {
        $dto = SupportTicketDto::fromIndexRequest($request);
        return match ($dto->category) {
            null => $this->repository->all($dto),
            default => $this->repository->allWithFilter($dto),
        };
    }

    public function show(SupportTicket $supportTicket): object
    {
        return $this->repository->find($supportTicket->id);
    }

    public function store(SupportTicketRequest $request): object
    {
        $dto = SupportTicketDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(SupportTicketRequest $request, SupportTicket $supportTicket): object
    {
        $dto = SupportTicketDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $supportTicket->id);
    }

    public function destroy(SupportTicket $supportTicket): object
    {
        return $this->repository->delete($supportTicket->id);
    }

    private function prepareStoreData(): array
    {
        return [
            'userId' => Auth::user()->id,
        ];
    }
}
