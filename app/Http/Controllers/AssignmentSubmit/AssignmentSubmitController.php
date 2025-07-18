<?php

namespace App\Http\Controllers\AssignmentSubmit;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\AssignmentSubmit\AssignmentSubmitService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssignmentSubmit\AssignmentSubmitRequest;
use App\Http\Resources\AssignmentSubmit\AssignmentSubmitResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssignmentSubmitController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssignmentSubmitService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssignmentSubmitRequest $request): JsonResponse
    {
        $data = AssignmentSubmitResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::AssignmentSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function show(AssignmentSubmit $assignmentSubmit): JsonResponse
    {
        $data = AssignmentSubmitResource::make(
            $this->service->show($assignmentSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::AssignmentSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssignmentSubmitRequest $request, AssignmentSubmit $assignmentSubmit): JsonResponse
    {
        $data = AssignmentSubmitResource::make(
            $this->service->update($request, $assignmentSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::AssignmentSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(AssignmentSubmit $assignmentSubmit): JsonResponse
    {
        $data = AssignmentSubmitResource::make(
            $this->service->destroy($assignmentSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::AssignmentSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function view(AssignmentSubmit $assignmentSubmit, string $fileName): BinaryFileResponse
    {
        $file = $this->service->view($assignmentSubmit, $fileName);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(AssignmentSubmit $assignmentSubmit): BinaryFileResponse
    {
        $zip = $this->service->download($assignmentSubmit);

        return $this->controller->setZip($zip)
            ->downloadZipResponse();
    }
}
