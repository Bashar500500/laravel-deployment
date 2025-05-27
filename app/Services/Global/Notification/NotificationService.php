<?php

namespace App\Services\Global\Notification;

use App\Notifications\Firebase\FirebaseNotification;

class NotificationService
{
    public function __construct(
        protected FirebaseNotification $firebaseService,
    ) {}

    public function sendNotification(string $fcm_token, array $data): void
    {
        $this->firebaseService->sendNotification($fcm_token, $data);
    }

    public function sendNotificationToTopic(string $topic, array $data): void
    {
        $this->firebaseService->sendNotificationToTopic($topic, $data);
    }
}
