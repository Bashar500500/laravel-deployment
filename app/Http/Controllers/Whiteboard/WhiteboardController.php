<?php

namespace App\Http\Controllers\Whiteboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Whiteboard\WhiteboardService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Whiteboard\WhiteboardRequest;
use App\Http\Resources\Whiteboard\WhiteboardResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Whiteboard\Whiteboard;

class WhiteboardController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected WhiteboardService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(WhiteboardRequest $request): JsonResponse
    {
        // $this->authorize('index', Whiteboard::class);

        $data = WhiteboardResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Whiteboard)
            ->setData($data)
            ->successResponse();
    }

    public function show(Whiteboard $whiteboard): JsonResponse
    {
        // $this->authorize('show', $whiteboard);

        $data = WhiteboardResource::make(
            $this->service->show($whiteboard),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Whiteboard)
            ->setData($data)
            ->successResponse();
    }

    public function store(WhiteboardRequest $request): JsonResponse
    {
        // $this->authorize('store', Whiteboard::class);

        $data = WhiteboardResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Whiteboard)
            ->setData($data)
            ->successResponse();
    }

    public function update(WhiteboardRequest $request, Whiteboard $whiteboard): JsonResponse
    {
        // $this->authorize('update', $whiteboard);

        $data = WhiteboardResource::make(
            $this->service->update($request, $whiteboard),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Whiteboard)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Whiteboard $whiteboard): JsonResponse
    {
        // $this->authorize('destroy', $whiteboard);

        $data = WhiteboardResource::make(
            $this->service->destroy($whiteboard),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Whiteboard)
            ->setData($data)
            ->successResponse();
    }
}
