<?php

namespace App\Services\Event;

use App\Repositories\Event\EventRepositoryInterface;
use App\Http\Requests\Event\EventRequest;
use App\Models\Event\Event;
use App\DataTransferObjects\Event\EventDto;

class EventService
{
    public function __construct(
        protected EventRepositoryInterface $repository,
    ) {}

    public function index(EventRequest $request): object
    {
        $dto = EventDto::fromIndexRequest($request);
        return match ($dto->recurrence) {
            null => $this->repository->all($dto),
            default => $this->repository->allWithFilter($dto),
        };
    }

    public function show(Event $event): object
    {
        return $this->repository->find($event->id);
    }

    public function store(EventRequest $request): object
    {
        $dto = EventDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(EventRequest $request, Event $event): object
    {
        $dto = EventDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $event->id);
    }

    public function destroy(Event $event): object
    {
        return $this->repository->delete($event->id);
    }

    public function view(Event $event, string $fileName): string
    {
        return $this->repository->view($event->id, $fileName);
    }

    public function download(Event $event): string
    {
        return $this->repository->download($event->id);
    }

    public function destroyAttachment(Event $event, string $fileName): void
    {
        $this->repository->deleteAttachment($event->id, $fileName);
    }
}
