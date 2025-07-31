<?php

namespace App\Http\Controllers\Policy;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Policy\PolicyService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Policy\PolicyRequest;
use App\Http\Resources\Policy\PolicyResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Policy\Policy;

class PolicyController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected PolicyService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(PolicyRequest $request): JsonResponse
    {
        // $this->authorize('index', Policy::class);

        $data = (object) PolicyResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Policy)
            ->setData($data)
            ->successResponse();
    }

    public function show(Policy $policy): JsonResponse
    {
        // $this->authorize('show', $policy);

        $data = PolicyResource::make(
            $this->service->show($policy),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Policy)
            ->setData($data)
            ->successResponse();
    }

    public function store(PolicyRequest $request): JsonResponse
    {
        // $this->authorize('store', Policy::class);

        $data = PolicyResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Policy)
            ->setData($data)
            ->successResponse();
    }

    public function update(PolicyRequest $request, Policy $policy): JsonResponse
    {
        // $this->authorize('update', $policy);

        $data = PolicyResource::make(
            $this->service->update($request, $policy),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Policy)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Policy $policy): JsonResponse
    {
        // $this->authorize('destroy', $policy);

        $data = PolicyResource::make(
            $this->service->destroy($policy),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Policy)
            ->setData($data)
            ->successResponse();
    }
}
