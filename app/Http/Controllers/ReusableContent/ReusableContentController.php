<?php

namespace App\Http\Controllers\ReusableContent;

use App\Enums\ReusableContent\ReusableContentType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\ReusableContent\ReusableContentService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ReusableContent\ReusableContentRequest;
use App\Http\Resources\ReusableContent\ReusableContentResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\ReusableContent\ReusableContent;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Content\ReusableContentContentUploadRequest;
use App\Enums\Upload\UploadMessage;

class ReusableContentController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ReusableContentService $reusableContentService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(ReusableContentRequest $request): JsonResponse
    {
        // $this->authorize('index', [ReusableContent::class, $request->validated('course_id')]);

        $data = ReusableContentResource::collection(
            $this->reusableContentService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::ReusableContent)
            ->setData($data)
            ->successResponse();
    }

    public function show(ReusableContent $reusableContent): JsonResponse
    {
        // $this->authorize('show', $reusableContent);

        $data = ReusableContentResource::make(
            $this->reusableContentService->show($reusableContent),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::ReusableContent)
            ->setData($data)
            ->successResponse();
    }

    public function store(ReusableContentRequest $request): JsonResponse
    {
        // $this->authorize('store', ReusableContent::class);

        $data = ReusableContentResource::make(
            $this->reusableContentService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::ReusableContent)
            ->setData($data)
            ->successResponse();
    }

    public function update(ReusableContentRequest $request, ReusableContent $reusableContent): JsonResponse
    {
        // $this->authorize('update', $reusableContent);

        $data = ReusableContentResource::make(
            $this->reusableContentService->update($request, $reusableContent),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::ReusableContent)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(ReusableContent $reusableContent): JsonResponse
    {
        // $this->authorize('destroy', $reusableContent);

        $data = ReusableContentResource::make(
            $this->reusableContentService->destroy($reusableContent),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::ReusableContent)
            ->setData($data)
            ->successResponse();
    }

    public function view(ReusableContent $reusableContent): BinaryFileResponse
    {
        // $this->authorize('view', $reusableContent);

        $file = $this->reusableContentService->view($reusableContent);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(ReusableContent $reusableContent): BinaryFileResponse
    {
        // $this->authorize('download', $reusableContent);

        $file = $this->reusableContentService->download($reusableContent);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ReusableContentContentUploadRequest $request, ReusableContent $reusableContent): JsonResponse
    {
        // $this->authorize('upload', $reusableContent);

        $message = $this->uploadService->uploadReusableContentFile($request, $reusableContent);

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
            UploadMessage::Pdf => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Pdf)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Chunk => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Chunk)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function destroyAttachment(ReusableContent $reusableContent): JsonResponse
    {
        // $this->authorize('destroyAttachment', $reusableContent);

        $this->reusableContentService->destroyAttachment($reusableContent);

        return match ($reusableContent->content_type) {
            ReusableContentType::Video => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Video)
                ->setData((object) [])
                ->successResponse(),
            ReusableContentType::Presentation => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Presentation)
                ->setData((object) [])
                ->successResponse(),
            ReusableContentType::Quiz => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Quiz)
                ->setData((object) [])
                ->successResponse(),
            ReusableContentType::Pdf => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Pdf)
                ->setData((object) [])
                ->successResponse(),
        };
    }
}
