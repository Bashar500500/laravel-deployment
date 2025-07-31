<?php

namespace App\Http\Controllers\Rule;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Rule\RuleService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Rule\RuleRequest;
use App\Http\Resources\Rule\RuleResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Rule\Rule;

class RuleController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected RuleService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(RuleRequest $request): JsonResponse
    {
        $data = RuleResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Rule)
            ->setData($data)
            ->successResponse();
    }

    public function show(Rule $rule): JsonResponse
    {
        $data = RuleResource::make(
            $this->service->show($rule),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Rule)
            ->setData($data)
            ->successResponse();
    }

    public function store(RuleRequest $request): JsonResponse
    {
        // $this->authorize('store', Rule::class);

        $data = RuleResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Rule)
            ->setData($data)
            ->successResponse();
    }

    public function update(RuleRequest $request, Rule $rule): JsonResponse
    {
        // $this->authorize('update', $rule);

        $data = RuleResource::make(
            $this->service->update($request, $rule),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Rule)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Rule $rule): JsonResponse
    {
        // $this->authorize('destroy', $rule);

        $data = RuleResource::make(
            $this->service->destroy($rule),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Rule)
            ->setData($data)
            ->successResponse();
    }
}
