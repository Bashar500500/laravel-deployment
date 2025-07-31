<?php

namespace App\Http\Controllers\CommunityAccess;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\CommunityAccess\CommunityAccessService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CommunityAccess\CommunityAccessRequest;
use App\Http\Resources\CommunityAccess\CommunityAccessResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\CommunityAccess\CommunityAccess;

class CommunityAccessController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected CommunityAccessService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(CommunityAccessRequest $request): JsonResponse
    {
        // $this->authorize('index', CommunityAccess::class);

        $data = (object) CommunityAccessResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::CommunityAccess)
            ->setData($data)
            ->successResponse();
    }

    public function show(CommunityAccess $communityAccess): JsonResponse
    {
        // $this->authorize('show', $communityAccess);

        $data = CommunityAccessResource::make(
            $this->service->show($communityAccess),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::CommunityAccess)
            ->setData($data)
            ->successResponse();
    }

    public function store(CommunityAccessRequest $request): JsonResponse
    {
        // $this->authorize('store', CommunityAccess::class);

        $data = CommunityAccessResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::CommunityAccess)
            ->setData($data)
            ->successResponse();
    }

    public function update(CommunityAccessRequest $request, CommunityAccess $communityAccess): JsonResponse
    {
        // $this->authorize('update', $communityAccess);

        $data = CommunityAccessResource::make(
            $this->service->update($request, $communityAccess),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::CommunityAccess)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(CommunityAccess $communityAccess): JsonResponse
    {
        // $this->authorize('destroy', $communityAccess);

        $data = CommunityAccessResource::make(
            $this->service->destroy($communityAccess),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::CommunityAccess)
            ->setData($data)
            ->successResponse();
    }
}
