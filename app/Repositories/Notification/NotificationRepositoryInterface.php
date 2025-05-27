<?php

namespace App\Repositories\Notification;

use App\DataTransferObjects\Notification\NotificationDto;

interface NotificationRepositoryInterface
{
    public function all(NotificationDto $dto, array $data): object;

    public function create(NotificationDto $dto): object;

    public function delete(int $id): object;
}
