<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\RealtimeAndNotification\GlobalServiceHandlerService;
use App\Enums\Trait\ModelName;
use App\Models\Message\Message;
use App\Models\Reply\Reply;
use App\Models\Notification\Notification;
use App\Services\Global\Realtime\RealtimeService;
use App\Notifications\Firebase\FirebaseNotification;
use App\Services\Global\Notification\NotificationService;
use App\Models\Auth\PasswordResetCode;
use App\Services\Global\Email\EmailService;
use App\Emails\PasswordResetEmail;
use App\Models\Email\Email;
use App\Emails\GlobalEmail;

class GlobalServiceHandlerJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Message|Reply|Notification|PasswordResetCode|Email $model,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $name = class_basename($this->model);

        switch ($name)
        {
            case ModelName::Message->getModelName():
                $this->getInstance()->handleMessage($this->model);
                break;
            case ModelName::Reply->getModelName():
                $this->getInstance()->handleReply($this->model);
                break;
            case ModelName::Notification->getModelName():
                $this->getInstance()->handleNotification($this->model);
                break;
            case ModelName::PasswordResetCode->getModelName():
                $this->getInstance()->handlePasswordResetCodeEmail($this->model);
                break;
            default:
                $this->getInstance()->handleGlobalEmail($this->model);
                break;
        }
    }

    public function getInstance(): GlobalServiceHandlerService
    {
        $realtimeService = new RealtimeService();
        $firebaseNotification = new FirebaseNotification();
        $notificationService = new NotificationService($firebaseNotification);
        $passwordResetEmail = new PasswordResetEmail();
        $globalEmail = new GlobalEmail();
        $emailService = new EmailService($passwordResetEmail, $globalEmail);
        $globalServiceHandlerService = new GlobalServiceHandlerService(
            $realtimeService,
            $notificationService,
            $emailService,
        );

        return $globalServiceHandlerService;
    }
}
