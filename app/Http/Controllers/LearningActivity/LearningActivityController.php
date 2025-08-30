<?php

namespace App\Http\Controllers\LearningActivity;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\LearningActivity\LearningActivityService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LearningActivity\LearningActivityRequest;
use App\Http\Resources\LearningActivity\LearningActivityResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\LearningActivity\LearningActivity;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Content\LearningActivityContentUploadRequest;
use App\Enums\Upload\UploadMessage;
use App\Enums\LearningActivity\LearningActivityType;

class LearningActivityController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected LearningActivityService $learningActivityService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(LearningActivityRequest $request): JsonResponse
    {
        // $this->authorize('index', [LearningActivity::class, $request->validated('section_id')]);

        $data = LearningActivityResource::collection(
            $this->learningActivityService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::LearningActivity)
            ->setData($data)
            ->successResponse();
    }

    public function show(LearningActivity $learningActivity): JsonResponse
    {
        // $this->authorize('show', $learningActivity);

        $data = LearningActivityResource::make(
            $this->learningActivityService->show($learningActivity),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::LearningActivity)
            ->setData($data)
            ->successResponse();
    }

    public function store(LearningActivityRequest $request): JsonResponse
    {
        // $this->authorize('store', LearningActivity::class);

        $data = LearningActivityResource::make(
            $this->learningActivityService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::LearningActivity)
            ->setData($data)
            ->successResponse();
    }

    public function update(LearningActivityRequest $request, LearningActivity $learningActivity): JsonResponse
    {
        // $this->authorize('update', $learningActivity);

        $data = LearningActivityResource::make(
            $this->learningActivityService->update($request, $learningActivity),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::LearningActivity)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(LearningActivity $learningActivity): JsonResponse
    {
        // $this->authorize('destroy', $learningActivity);

        $data = LearningActivityResource::make(
            $this->learningActivityService->destroy($learningActivity),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::LearningActivity)
            ->setData($data)
            ->successResponse();
    }

    public function view(LearningActivity $learningActivity): BinaryFileResponse
    {
        // $this->authorize('view', $learningActivity);

        $file = $this->learningActivityService->view($learningActivity);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(LearningActivity $learningActivity): BinaryFileResponse
    {
        // $this->authorize('download', $learningActivity);

        $file = $this->learningActivityService->download($learningActivity);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(LearningActivityContentUploadRequest $request, LearningActivity $learningActivity): JsonResponse
    {
        // $this->authorize('upload', $learningActivity);

        $message = $this->uploadService->uploadLearningActivityContent($request, $learningActivity);

        return match ($message) {
            UploadMessage::Pdf => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Pdf)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Video => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Video)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Audio => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Audio)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Word => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Word)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::PowerPoint => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::PowerPoint)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Zip => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Zip)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Chunk => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Chunk)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function destroyAttachment(LearningActivity $learningActivity): JsonResponse
    {
        // $this->authorize('destroyAttachment', $learningActivity);

        $this->learningActivityService->destroyAttachment($learningActivity);

        return match ($learningActivity->type) {
            LearningActivityType::Pdf => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Pdf)
                ->setData((object) [])
                ->successResponse(),
            LearningActivityType::Video => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Video)
                ->setData((object) [])
                ->successResponse(),
            LearningActivityType::Audio => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Audio)
                ->setData((object) [])
                ->successResponse(),
            LearningActivityType::Word => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Word)
                ->setData((object) [])
                ->successResponse(),
            LearningActivityType::PowerPoint => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::PowerPoint)
                ->setData((object) [])
                ->successResponse(),
            LearningActivityType::Zip => $this->controller->setFunctionName(FunctionName::Delete)
                ->setModelName(ModelName::Zip)
                ->setData((object) [])
                ->successResponse(),
        };
    }
}
