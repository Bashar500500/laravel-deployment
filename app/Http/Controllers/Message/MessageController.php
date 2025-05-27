<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Message\MessageService;
use App\Jobs\GlobalServiceHandlerJob;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Message\MessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Message\Message;

class MessageController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected MessageService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(MessageRequest $request): JsonResponse
    {
        $data = (object) MessageResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Message)
            ->setData($data)
            ->successResponse();
    }

    public function store(MessageRequest $request): JsonResponse
    {
        $message = $this->service->store($request);
        $data = MessageResource::make(
            $message,
        );

        GlobalServiceHandlerJob::dispatch($message);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Message)
            ->setData($data)
            ->successResponse();
    }

    public function update(MessageRequest $request, Message $message): JsonResponse
    {
        $data = MessageResource::make(
            $this->service->update($request, $message),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Message)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Message $message): JsonResponse
    {
        $data = MessageResource::make(
            $this->service->destroy($message),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Message)
            ->setData($data)
            ->successResponse();
    }
}
