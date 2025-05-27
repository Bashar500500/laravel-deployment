<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Chat\ChatRequest;
use App\Http\Resources\Chat\ChatResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Chat\Chat;

class ChatController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ChatService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(ChatRequest $request): JsonResponse
    {
        $data = (object) ChatResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Chat)
            ->setData($data)
            ->successResponse();
    }

    public function show(Chat $chat): JsonResponse
    {
        $data = ChatResource::make(
            $this->service->show($chat),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Chat)
            ->setData($data)
            ->successResponse();
    }

    public function store(ChatRequest $request): JsonResponse
    {
        $data = ChatResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Chat)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Chat $chat): JsonResponse
    {
        $data = ChatResource::make(
            $this->service->destroy($chat),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Chat)
            ->setData($data)
            ->successResponse();
    }
}
