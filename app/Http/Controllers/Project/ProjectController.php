<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Project\ProjectService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Project\Project;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Enums\Upload\UploadMessage;
use App\Http\Requests\Project\ProjectSubmitRequest;
use App\Http\Resources\ProjectSubmit\ProjectSubmitResource;

class ProjectController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ProjectService $projectService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(ProjectRequest $request): JsonResponse
    {
        // $this->authorize('index', [Project::class, $request->validated('course_id')]);

        $data = ProjectResource::collection(
            $this->projectService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Project)
            ->setData($data)
            ->successResponse();
    }

    public function show(Project $project): JsonResponse
    {
        // $this->authorize('show', $project);

        $data = ProjectResource::make(
            $this->projectService->show($project),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Project)
            ->setData($data)
            ->successResponse();
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        // $this->authorize('store', Project::class);

        $data = ProjectResource::make(
            $this->projectService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Project)
            ->setData($data)
            ->successResponse();
    }

    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        // $this->authorize('update', $project);

        $data = ProjectResource::make(
            $this->projectService->update($request, $project),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Project)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Project $project): JsonResponse
    {
        // $this->authorize('destroy', $project);

        $data = ProjectResource::make(
            $this->projectService->destroy($project),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Project)
            ->setData($data)
            ->successResponse();
    }

    public function view(Project $project, string $fileName): BinaryFileResponse
    {
        // $this->authorize('view', $project);

        $file = $this->projectService->view($project, $fileName);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Project $project): BinaryFileResponse
    {
        // $this->authorize('download', $project);

        $zip = $this->projectService->download($project);

        return $this->controller->setZip($zip)
            ->downloadZipResponse();
    }

    public function upload(FileUploadRequest $request, Project $project): JsonResponse
    {
        // $this->authorize('upload', $project);

        $message = $this->uploadService->uploadProjectFile($request, $project);

        return match ($message) {
            UploadMessage::File => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::File)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Chunk => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Chunk)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function destroyAttachment(Project $project, string $fileName): JsonResponse
    {
        // $this->authorize('destroyAttachment', $project);

        $this->projectService->destroyAttachment($project, $fileName);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::File)
            ->setData((object) [])
            ->successResponse();
    }

    public function submit(ProjectSubmitRequest $request): JsonResponse
    {
        // $this->authorize('submit', [Project::class, $request->validated('project_id')]);

        $data = ProjectSubmitResource::make(
            $this->projectService->submit($request),
        );

        return $this->controller->setFunctionName(FunctionName::Submit)
            ->setModelName(ModelName::Project)
            ->setData($data)
            ->successResponse();
    }
}
