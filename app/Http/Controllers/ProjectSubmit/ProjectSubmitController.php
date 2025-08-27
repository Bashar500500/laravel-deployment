<?php

namespace App\Http\Controllers\ProjectSubmit;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\ProjectSubmit\ProjectSubmitService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProjectSubmit\ProjectSubmitRequest;
use App\Http\Resources\ProjectSubmit\ProjectSubmitResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\ProjectSubmit\ProjectSubmit;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectSubmitController extends Controller   // Add Policies
{
    public function __construct(
        ResponseController $controller,
        protected ProjectSubmitService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(ProjectSubmitRequest $request): JsonResponse
    {
        // $this->authorize('index', [ProjectSubmit::class, $request->validated('project_id')]);

        $data = ProjectSubmitResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::ProjectSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function show(ProjectSubmit $projectSubmit): JsonResponse
    {
        // $this->authorize('show', $projectSubmit);

        $data = ProjectSubmitResource::make(
            $this->service->show($projectSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::ProjectSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function update(ProjectSubmitRequest $request, ProjectSubmit $projectSubmit): JsonResponse
    {
        // $this->authorize('update', $projectSubmit);

        $data = ProjectSubmitResource::make(
            $this->service->update($request, $projectSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::ProjectSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(ProjectSubmit $projectSubmit): JsonResponse
    {
        // $this->authorize('destroy', $projectSubmit);

        $data = ProjectSubmitResource::make(
            $this->service->destroy($projectSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::ProjectSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function view(ProjectSubmit $projectSubmit, string $fileName): BinaryFileResponse
    {
        // $this->authorize('view', $projectSubmit);

        $file = $this->service->view($projectSubmit, $fileName);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(ProjectSubmit $projectSubmit): BinaryFileResponse
    {
        // $this->authorize('download', $projectSubmit);

        $zip = $this->service->download($projectSubmit);

        return $this->controller->setZip($zip)
            ->downloadZipResponse();
    }
}
