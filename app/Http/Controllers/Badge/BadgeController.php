<?php

namespace App\Http\Controllers\Badge;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Badge\BadgeService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Badge\BadgeRequest;
use App\Http\Resources\Badge\BadgeResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Badge\Badge;

class BadgeController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected BadgeService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(BadgeRequest $request): JsonResponse
    {
        // $this->authorize('index', Badge::class);

        $data = BadgeResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Badge)
            ->setData($data)
            ->successResponse();
    }

    public function show(Badge $badge): JsonResponse
    {
        // $this->authorize('show', $badge);

        $data = BadgeResource::make(
            $this->service->show($badge),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Badge)
            ->setData($data)
            ->successResponse();
    }

    public function store(BadgeRequest $request): JsonResponse
    {
        // $this->authorize('store', Badge::class);

        $data = BadgeResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Badge)
            ->setData($data)
            ->successResponse();
    }

    public function update(BadgeRequest $request, Badge $badge): JsonResponse
    {
        // $this->authorize('update', $badge);

        $data = BadgeResource::make(
            $this->service->update($request, $badge),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Badge)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Badge $badge): JsonResponse
    {
        // $this->authorize('destroy', $badge);

        $data = BadgeResource::make(
            $this->service->destroy($badge),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Badge)
            ->setData($data)
            ->successResponse();
    }
}
