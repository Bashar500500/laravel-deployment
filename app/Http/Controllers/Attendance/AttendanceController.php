<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Attendance\AttendanceRequest;
use App\Http\Resources\Attendance\AttendanceResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Attendance\Attendance;

class AttendanceController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AttendanceService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AttendanceRequest $request): JsonResponse
    {
        // $this->authorize('index', [Attendance::class, $request->validated('section_id')]);

        $data = AttendanceResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Attendance)
            ->setData($data)
            ->successResponse();
    }

    public function show(Attendance $attendance): JsonResponse
    {
        // $this->authorize('show', $attendance);

        $data = AttendanceResource::make(
            $this->service->show($attendance),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Attendance)
            ->setData($data)
            ->successResponse();
    }

    public function store(AttendanceRequest $request): JsonResponse
    {
        // $this->authorize('store', Attendance::class);

        $data = AttendanceResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Attendance)
            ->setData($data)
            ->successResponse();
    }

    public function update(AttendanceRequest $request, Attendance $attendance): JsonResponse
    {
        // $this->authorize('update', $attendance);

        $data = AttendanceResource::make(
            $this->service->update($request, $attendance),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Attendance)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        // $this->authorize('destroy', $attendance);

        $data = AttendanceResource::make(
            $this->service->destroy($attendance),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Attendance)
            ->setData($data)
            ->successResponse();
    }
}
