<?php

namespace App\Services\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Http\Requests\Ticket\TicketRequest;
use App\Models\Ticket\Ticket;
use App\DataTransferObjects\Ticket\TicketDto;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    public function __construct(
        protected TicketRepositoryInterface $repository,
    ) {}

    public function index(TicketRequest $request): object
    {
        $dto = TicketDto::fromIndexRequest($request);
        return match ($dto->category) {
            null => $this->repository->all($dto),
            default => $this->repository->allWithFilter($dto),
        };
    }

    public function show(Ticket $ticket): object
    {
        return $this->repository->find($ticket->id);
    }

    public function store(TicketRequest $request): object
    {
        $dto = TicketDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(TicketRequest $request, Ticket $ticket): object
    {
        $dto = TicketDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $ticket->id);
    }

    public function destroy(Ticket $ticket): object
    {
        return $this->repository->delete($ticket->id);
    }

    private function prepareStoreData(): array
    {
        return [
            'userId' => Auth::user()->id,
        ];
    }
}
