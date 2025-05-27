<?php

namespace App\Notifications\Firebase;

use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Exceptions\CustomException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class FirebaseNotification
{
    protected $messaging;

    public function __construct()
    {
        $serviceAccountPath = Storage::path('app/json/chat-5d199-firebase-adminsdk-fbsvc-eff6f891a5.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri('https://chat-5d199-default-rtdb.firebaseio.com/');
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification(string $fcm_token, array $data): void
    {
        $message = CloudMessage::withTarget('token', $fcm_token)
            ->withData($data);

        try {
            $this->messaging->send($message);
        } catch (MessagingException $e) {
            throw CustomException::firebase();
        } catch (FirebaseException $e) {
            throw CustomException::firebase();
        }
    }

    public function sendNotificationToTopic(string $topic, array $data): void
    {
        $message = CloudMessage::withTarget('topic', $topic)
            ->withData($data);

        try {
            $this->messaging->send($message);
        } catch (MessagingException $e) {
            throw CustomException::firebase();
        } catch (FirebaseException $e) {
            throw CustomException::firebase();
        }
    }

    public function subscribeToTopic(string $fcm_token, string $topic): void
    {
        try {
            $this->messaging->subscribeToTopic($topic, $fcm_token);
        } catch (MessagingException $e) {
            throw CustomException::firebase();
        } catch (FirebaseException $e) {
            throw CustomException::firebase();
        }
    }

    public function unsubscribeFromAllTopics(string $fcm_token): void
    {
        try {
            $this->messaging->unsubscribeFromAllTopics($fcm_token);
        } catch (MessagingException $e) {
            throw CustomException::firebase();
        } catch (FirebaseException $e) {
            throw CustomException::firebase();
        }
    }
}
