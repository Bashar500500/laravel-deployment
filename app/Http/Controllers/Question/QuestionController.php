<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Question\QuestionService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Question\QuestionRequest;
use App\Http\Resources\Question\QuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Question\Question;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Enums\Upload\UploadMessage;

class QuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected QuestionService $questionService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(QuestionRequest $request): JsonResponse
    {
        $data = QuestionResource::collection(
            $this->questionService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Question)
            ->setData($data)
            ->successResponse();
    }

    public function show(Question $question): JsonResponse
    {
        $data = QuestionResource::make(
            $this->questionService->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Question)
            ->setData($data)
            ->successResponse();
    }

    public function store(QuestionRequest $request): JsonResponse
    {
        $data = QuestionResource::make(
            $this->questionService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Question)
            ->setData($data)
            ->successResponse();
    }

    public function update(QuestionRequest $request, Question $question): JsonResponse
    {
        $data = QuestionResource::make(
            $this->questionService->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Question)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Question $question): JsonResponse
    {
        $data = QuestionResource::make(
            $this->questionService->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Question)
            ->setData($data)
            ->successResponse();
    }

    public function view(Question $question): BinaryFileResponse
    {
        $file = $this->questionService->view($question);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Question $question): BinaryFileResponse
    {
        $file = $this->questionService->download($question);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ImageUploadRequest $request, Question $question): JsonResponse
    {
        $message = $this->uploadService->uploadQuestionImage($request, $question);

        return match ($message) {
            UploadMessage::Image => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Image)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Chunk => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Chunk)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function destroyAttachment(Question $question): JsonResponse
    {
        $this->questionService->destroyAttachment($question);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Image)
            ->setData((object) [])
            ->successResponse();
    }
}
