<?php

namespace App\Factories\Notification;

use Illuminate\Contracts\Container\Container;
use App\Enums\Notification\NotificationType;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\Notification\UserNotificationRepository;
use App\Repositories\Notification\VersionNotificationRepository;
use App\Repositories\Notification\WebsiteNotificationRepository;

class NotificationRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(NotificationType $type): NotificationRepositoryInterface
    {
        return match ($type) {
            NotificationType::User => $this->container->make(UserNotificationRepository::class),
            NotificationType::Version => $this->container->make(VersionNotificationRepository::class),
            NotificationType::Website => $this->container->make(WebsiteNotificationRepository::class),
        };
    }
}
