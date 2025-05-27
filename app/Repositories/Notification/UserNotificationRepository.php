<?php

namespace App\Repositories\Notification;

use App\Repositories\BaseRepository;
use App\Models\Notification\Notification;
use App\DataTransferObjects\Notification\NotificationDto;
use App\Enums\Model\ModelTypePath;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;

class UserNotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(Notification $notification) {
        parent::__construct($notification);
    }

    public function all(NotificationDto $dto, array $data): object
    {
        $user = User::find($data['userId']);
        // $userVersions = $user->userVersions;
        $userVersions = [1,2];

        return (object) $this->model->where('notificationable_type', ModelTypePath::User->getTypePath())
            ->where('notificationable_id', $data['userId'])
            ->orWhere('notificationable_type', ModelTypePath::Version->getTypePath())
            ->whereIn('notificationable_id', $userVersions)
            ->orWhere('notificationable_type', ModelTypePath::Website->getTypePath())
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function create(NotificationDto $dto): object
    {
        $user = User::find($dto->issuerId);

        // if (! $user->fcm_token)
        // {
        //     throw CustomException::forbidden(ModelName::Notification, ForbiddenExceptionMessage::Notification);
        // }

        $notification = DB::transaction(function () use ($dto, $user) {
            $notification = $user->notification()->create([
                'title' => $dto->title,
                'body' => $dto->body,
            ]);

            return $notification;
        });

        return (object) $notification;
    }

    public function delete(int $id): object
    {
        $notification = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $notification;
    }
}
