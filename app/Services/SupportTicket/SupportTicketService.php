<?php

namespace App\Services\SupportTicket;

use App\Factories\SupportTicket\SupportTicketRepositoryFactory;
use App\Http\Requests\SupportTicket\SupportTicketRequest;
use App\Models\SupportTicket\SupportTicket;
use App\DataTransferObjects\SupportTicket\SupportTicketDto;
use Illuminate\Support\Facades\Auth;

class SupportTicketService
{
    public function __construct(
        protected SupportTicketRepositoryFactory $factory,
    ) {}

    public function index(SupportTicketRequest $request): object
    {
        $dto = SupportTicketDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareStoreAndIndexData();
        $repository = $this->factory->make($role[0]);
        return match ($dto->category) {
            null => $repository->all($dto, $data),
            default => $repository->allWithFilter($dto, $data),
        };
    }

    public function show(SupportTicket $supportTicket): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($supportTicket->id);
    }

    public function store(SupportTicketRequest $request): object
    {
        $dto = SupportTicketDto::fromStoreRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareStoreAndIndexData();
        $repository = $this->factory->make($role[0]);
        return $repository->create($dto, $data);
    }

    public function update(SupportTicketRequest $request, SupportTicket $supportTicket): object
    {
        $dto = SupportTicketDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $supportTicket->id);
    }

    public function destroy(SupportTicket $supportTicket): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($supportTicket->id);
    }

    private function prepareStoreAndIndexData(): array
    {
        return [
            'userId' => Auth::user()->id,
        ];
    }
}
