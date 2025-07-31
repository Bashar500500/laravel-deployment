<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Leave\LeaveService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Leave\LeaveRequest;
use App\Http\Resources\Leave\LeaveResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Leave\Leave;

class LeaveController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected LeaveService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(LeaveRequest $request): JsonResponse
    {
        // $this->authorize('index', Leave::class);

        $data = LeaveResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Leave)
            ->setData($data)
            ->successResponse();
    }

    public function show(Leave $leave): JsonResponse
    {
        // $this->authorize('show', $leave);

        $data = LeaveResource::make(
            $this->service->show($leave),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Leave)
            ->setData($data)
            ->successResponse();
    }

    public function store(LeaveRequest $request): JsonResponse
    {
        // $this->authorize('store', Leave::class);

        $data = LeaveResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Leave)
            ->setData($data)
            ->successResponse();
    }

    public function update(LeaveRequest $request, Leave $leave): JsonResponse
    {
        // $this->authorize('update', $leave);

        $data = LeaveResource::make(
            $this->service->update($request, $leave),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Leave)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Leave $leave): JsonResponse
    {
        // $this->authorize('destroy', $leave);

        $data = LeaveResource::make(
            $this->service->destroy($leave),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Leave)
            ->setData($data)
            ->successResponse();
    }
}
