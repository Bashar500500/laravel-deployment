<?php

namespace App\Repositories\Notification;

use App\Repositories\BaseRepository;
use App\Models\Notification\Notification;
use App\DataTransferObjects\Notification\NotificationDto;
use App\Models\Website\Website;
use Illuminate\Support\Facades\DB;

class WebsiteNotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(Notification $notification) {
        parent::__construct($notification);
    }

    public function all(NotificationDto $dto, array $data): object
    {
        return (object) $this->model->get();
    }

    public function create(NotificationDto $dto): object
    {
        $website = Website::find($dto->issuerId);

        $notification = DB::transaction(function () use ($dto, $website) {
            $notification = $website->notification()->create([
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
