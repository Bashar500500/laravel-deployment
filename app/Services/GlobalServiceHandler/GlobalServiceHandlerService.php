<?php

namespace App\Services\RealtimeAndNotification;

use App\Services\Global\Realtime\RealtimeService;
use App\Services\Global\Notification\NotificationService;
use App\Enums\Chat\ChatType;
use App\Enums\Model\ModelTypePath;
use App\Enums\Notification\NotificationData;
use App\Enums\Notification\NotificationTopic;
use App\Models\Message\Message;
use App\Models\Reply\Reply;
use App\Models\Notification\Notification;
use App\Models\Auth\PasswordResetCode;
use App\Services\Global\Email\EmailService;
use App\Models\Email\Email;

class GlobalServiceHandlerService
{
    public function __construct(
        protected RealtimeService $realtimeService,
        protected NotificationService $notificationService,
        protected EmailService $emailService,
    ) {}

    public function handleMessage(Message $message): void
    {
        switch ($message->messageable_type)
        {
            case ModelTypePath::Version->getTypePath():
                return;
            default:
                switch ($message->messageable->type)
                {
                    case ChatType::Direct:
                        $this->realtimeService->sendRealtimeMessage($message);
                        $payload = $this->prepareMessageNotificationData($message);
                        $this->notificationService->sendNotification($payload['fcm_token'], $payload['data']);
                        $payload['receiver']->notification()->create([
                            'title' => $payload['data']['title'],
                            'body' => $payload['data']['body'],
                        ]);
                        return;
                    default:
                        $this->realtimeService->sendRealtimeMessage($message);
                        return;
                }
        }
    }

    public function handleReply(Reply $reply): void
    {
        switch ($reply->message->messageable_type)
        {
            case ModelTypePath::Version->getTypePath():
                return;
            default:
                switch ($reply->message->messageable->type)
                {
                    case ChatType::Direct:
                        $this->realtimeService->sendRealtimeReply($reply);
                        $payload = $this->prepareReplyNotificationData($reply);
                        $this->notificationService->sendNotification($payload['fcm_token'], $payload['data']);
                        $payload['receiver']->notification()->create([
                            'title' => $payload['data']['title'],
                            'body' => $payload['data']['body'],
                        ]);
                        return;
                    default:
                        $this->realtimeService->sendRealtimeReply($reply);
                        return;
                }
        }
    }

    public function handleNotification(Notification $notification): void
    {
        switch ($notification->notificationable_type)
        {
            case ModelTypePath::User->getTypePath():
                $user = $notification->notificationable;
                $payload = $this->prepareNotificationData($notification);
                // $this->notificationService->sendNotification($user->fcm_token, $payload['data']);
                $this->notificationService->sendNotification('cqSDV2sG15AFkfshxI3dgL:APA91bHsCqZJSGx7GbPk6eDHe1Gd-0qPL0Ib_B2DAGOG03MaB7fJuqq-8BUs_8G6vUYfgiugrtrQfyNtjqOzB9aWxhIfuUQa28Nyj8GJt3NnLu-SirRwEtE', $payload['data']);
                return;
            case ModelTypePath::Version->getTypePath():
                $version = $notification->notificationable;
                $topic = NotificationTopic::VersionNotification->getTopic() . '-' . $version->id;
                $payload = $this->prepareNotificationData($notification);
                // $this->notificationService->sendNotificationToTopic($topic, $payload['data']);
                $this->notificationService->sendNotificationToTopic('course-notifications', $payload['data']);
                return;
            default:
                $topic = NotificationTopic::WebsiteNotification->getTopic();
                $payload = $this->prepareNotificationData($notification);
                // $this->notificationService->sendNotificationToTopic($topic, $payload['data']);
                $this->notificationService->sendNotificationToTopic('course-notifications', $payload['data']);
                return;
        }
    }

    public function handlePasswordResetCodeEmail(PasswordResetCode $passwordResetCode): void
    {
        $this->emailService->sendPasswordResetCodeEmail($passwordResetCode);
    }

    public function handleGlobalEmail(Email $email): void
    {
        $this->emailService->sendGlobalEmail($email);
    }

    private function prepareMessageNotificationData(Message $message): mixed
    {
        $sender = $message->user;
        $senderId = $message->user->id;
        $directChat = $message->messageable->directChats->where('user_id', '!=', $senderId)->first();
        $receiver = $directChat->user;

        // $payload['fcm_token'] = $receiver->fcm_token;
        $payload['fcm_token'] = 'cqSDV2sG15AFkfshxI3dgL:APA91bHsCqZJSGx7GbPk6eDHe1Gd-0qPL0Ib_B2DAGOG03MaB7fJuqq-8BUs_8G6vUYfgiugrtrQfyNtjqOzB9aWxhIfuUQa28Nyj8GJt3NnLu-SirRwEtE';
        $payload['data']['title'] = NotificationData::Message->getTitle();
        $payload['data']['body'] = $sender->name . NotificationData::Message->getBody();
        $payload['receiver'] = $receiver;

        return $payload;
    }

    private function prepareReplyNotificationData(Reply $reply): mixed
    {
        $sender = $reply->user;
        $senderId = $reply->user->id;
        $directChat = $reply->message->messageable->directChats->where('user_id', '!=', $senderId)->first();
        $receiver = $directChat->user;

        // $payload['fcm_token'] = $receiver->fcm_token;
        $payload['fcm_token'] = 'cqSDV2sG15AFkfshxI3dgL:APA91bHsCqZJSGx7GbPk6eDHe1Gd-0qPL0Ib_B2DAGOG03MaB7fJuqq-8BUs_8G6vUYfgiugrtrQfyNtjqOzB9aWxhIfuUQa28Nyj8GJt3NnLu-SirRwEtE';
        $payload['data']['title'] = NotificationData::Reply->getTitle();
        $payload['data']['body'] = $sender->name . NotificationData::Reply->getBody();
        $payload['receiver'] = $receiver;

        return $payload;
    }

    private function prepareNotificationData(Notification $notification): mixed
    {
        $payload['data']['title'] = $notification->title;
        $payload['data']['body'] = $notification->body;

        return $payload;
    }
}
