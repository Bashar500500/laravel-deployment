<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Notification\NotificationService;
use App\Jobs\GlobalServiceHandlerJob;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Notification\NotificationRequest;
use App\Http\Resources\Notification\NotificationResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Notification\Notification;

class NotificationController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected NotificationService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(NotificationRequest $request): JsonResponse
    {
        $data = (object) NotificationResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Notification)
            ->setData($data)
            ->successResponse();
    }

    public function store(NotificationRequest $request): JsonResponse
    {
        $notification = $this->service->store($request);
        $data = NotificationResource::make(
            $notification,
        );

        GlobalServiceHandlerJob::dispatch($notification);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Notification)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Notification $notification): JsonResponse
    {
        $data = NotificationResource::make(
            $this->service->destroy($notification),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Notification)
            ->setData($data)
            ->successResponse();
    }
}
