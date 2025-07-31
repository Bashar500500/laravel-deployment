<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->prepareData();
    }

    private function prepareData(): array
    {
        $notifications = $this->notifications;
        $data = [];
        $index = 0;

        foreach ($notifications as $notification)
        {
            $data[$index]['id'] = $notification->id;
            $data[$index]['type'] = 'notification';
            $data[$index]['title'] = $notification->title;
            $data[$index]['content'] = $notification->body;
            $data[$index]['timestamp'] = $notification->created_at;
            $data[$index]['read'] = $notification->is_read;
            $data[$index]['priority'] = 'high';
            $data[$index]['icon'] = 'x';
            $index += 1;
        }

        return $data;
    }
}
