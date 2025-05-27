<?php

namespace App\Services\Notification;

use App\Factories\Notification\NotificationRepositoryFactory;
use App\Models\Notification\Notification;
use App\Http\Requests\Notification\NotificationRequest;
use App\DataTransferObjects\Notification\NotificationDto;
use App\Enums\Notification\NotificationType;

class NotificationService
{
    public function __construct(
        protected NotificationRepositoryFactory $factory,
    ) {}

    public function index(NotificationRequest $request): object
    {
        $dto = NotificationDto::fromIndexRequest($request);
        $data = $this->prepareIndexData();
        $repository = $this->factory->make($dto->type);
        return $repository->all($dto, $data);
    }

    public function store(NotificationRequest $request): object
    {
        $dto = NotificationDto::fromStoreRequest($request);
        $repository = $this->factory->make($dto->type);
        return $repository->create($dto);
    }

    public function destroy(Notification $notification): object
    {
        $type = class_basename($notification->notificationable_type);
        $repository = $this->factory->make(NotificationType::getEnum($type));
        return $repository->delete($notification->id);
    }

    private function prepareIndexData(): array
    {
        return [
            // 'userId' => auth()->user()->id
            'userId' => 1,
        ];
    }
}
