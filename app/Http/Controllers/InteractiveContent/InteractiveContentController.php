<?php

namespace App\Http\Controllers\InteractiveContent;

use App\Enums\InteractiveContent\InteractiveContentType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\InteractiveContent\InteractiveContentService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\InteractiveContent\InteractiveContentRequest;
use App\Http\Resources\InteractiveContent\InteractiveContentResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\InteractiveContent\InteractiveContent;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Content\InteractiveContentContentUploadRequest;
use App\Enums\Upload\UploadMessage;

class InteractiveContentController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected InteractiveContentService $interactiveContentService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(InteractiveContentRequest $request): JsonResponse
    {
        // $this->authorize('index', [InteractiveContent::class, $request->validated('course_id')]);

        $data = InteractiveContentResource::collection(
            $this->interactiveContentService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::InteractiveContent)
            ->setData($data)
            ->successResponse();
    }

    public function show(InteractiveContent $interactiveContent): JsonResponse
    {
        // $this->authorize('show', $interactiveContent);

        $data = InteractiveContentResource::make(
            $this->interactiveContentService->show($interactiveContent),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::InteractiveContent)
            ->setData($data)
            ->successResponse();
    }

    public function store(InteractiveContentRequest $request): JsonResponse
    {
        // $this->authorize('store', InteractiveContent::class);

        $data = InteractiveContentResource::make(
            $this->interactiveContentService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::InteractiveContent)
            ->setData($data)
            ->successResponse();
    }

    public function update(InteractiveContentRequest $request, InteractiveContent $interactiveContent): JsonResponse
    {
        // $this->authorize('update', $interactiveContent);

        $data = InteractiveContentResource::make(
            $this->interactiveContentService->update($request, $interactiveContent),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::InteractiveContent)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(InteractiveContent $interactiveContent): JsonResponse
    {
        // $this->authorize('destroy', $interactiveContent);

        $data = InteractiveContentResource::make(
            $this->interactiveContentService->destroy($interactiveContent),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::InteractiveContent)
            ->setData($data)
            ->successResponse();
    }

    public function view(InteractiveContent $interactiveContent): BinaryFileResponse
    {
        // $this->authorize('view', $interactiveContent);

        $file = $this->interactiveContentService->view($interactiveContent);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(InteractiveContent $interactiveContent): BinaryFileResponse
    {
        // $this->authorize('download', $interactiveContent);

        $file = $this->interactiveContentService->download($interactiveContent);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(InteractiveContentContentUploadRequest $request, InteractiveContent $interactiveContent): JsonResponse
    {
        // $this->authorize('upload', $interactiveContent);

        $message = $this->uploadService->uploadInteractiveContentFile($request, $interactiveContent);

        return match ($message) {
            UploadMessage::Video => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Video)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Presentation => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Presentation)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Quiz => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Quiz)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Chunk => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Chunk)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function destroyAttachment(InteractiveContent $interactiveContent): JsonResponse
    {
        // $this->authorize('destroyAttachment', $interactiveContent);

        $this->interactiveContentService->destroyAttachment($interactiveContent);

        return match ($interactiveContent->content_type) {
            InteractiveContentType::Video => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Video)
                ->setData((object) [])
                ->successResponse(),
            InteractiveContentType::Presentation => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Presentation)
                ->setData((object) [])
                ->successResponse(),
            InteractiveContentType::Quiz => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Quiz)
                ->setData((object) [])
                ->successResponse(),
        };
    }
}
