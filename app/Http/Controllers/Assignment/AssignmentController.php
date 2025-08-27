<?php

namespace App\Http\Controllers\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Assignment\AssignmentService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Assignment\AssignmentRequest;
use App\Http\Resources\Assignment\AssignmentResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Assignment\Assignment;
use App\Http\Requests\Assignment\AssignmentSubmitRequest;
use App\Http\Resources\AssignmentSubmit\AssignmentSubmitResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Enums\Upload\UploadMessage;

class AssignmentController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssignmentService $assignmentService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(AssignmentRequest $request): JsonResponse
    {
        // $this->authorize('index', [Assignment::class, $request->validated('course_id')]);

        $data = AssignmentResource::collection(
            $this->assignmentService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function show(Assignment $assignment): JsonResponse
    {
        // $this->authorize('show', $assignment);

        $data = AssignmentResource::make(
            $this->assignmentService->show($assignment),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function store(AssignmentRequest $request): JsonResponse
    {
        // $this->authorize('store', Assignment::class);

        $data = AssignmentResource::make(
            $this->assignmentService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        // $this->authorize('update', $assignment);

        $data = AssignmentResource::make(
            $this->assignmentService->update($request, $assignment),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Assignment $assignment): JsonResponse
    {
        // $this->authorize('destroy', $assignment);

        $data = AssignmentResource::make(
            $this->assignmentService->destroy($assignment),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function view(Assignment $assignment, string $fileName): BinaryFileResponse
    {
        // $this->authorize('view', $assignment);

        $file = $this->assignmentService->view($assignment, $fileName);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Assignment $assignment): BinaryFileResponse
    {
        // $this->authorize('download', $assignment);

        $zip = $this->assignmentService->download($assignment);

        return $this->controller->setZip($zip)
            ->downloadZipResponse();
    }

    public function upload(FileUploadRequest $request, Assignment $assignment): JsonResponse
    {
        // $this->authorize('upload', $assignment);

        $message = $this->uploadService->uploadAssignmentFile($request, $assignment);

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

    public function destroyAttachment(Assignment $assignment, string $fileName): JsonResponse
    {
        // $this->authorize('destroyAttachment', $assignment);

        $this->assignmentService->destroyAttachment($assignment, $fileName);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::File)
            ->setData((object) [])
            ->successResponse();
    }

    public function submit(AssignmentSubmitRequest $request): JsonResponse
    {
        // $this->authorize('submit', [Assignment::class, $request->validated('assignment_id')]);

        $data = AssignmentSubmitResource::make(
            $this->assignmentService->submit($request),
        );

        return $this->controller->setFunctionName(FunctionName::Submit)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }
}
