<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Section\SectionService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Section\SectionRequest;
use App\Http\Resources\Section\SectionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Section\Section;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Enums\Upload\UploadMessage;

class SectionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected SectionService $sectionService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(SectionRequest $request): JsonResponse
    {
        $data = SectionResource::collection(
            $this->sectionService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Section)
            ->setData($data)
            ->successResponse();
    }

    public function show(Section $section): JsonResponse
    {
        $data = SectionResource::make(
            $this->sectionService->show($section),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Section)
            ->setData($data)
            ->successResponse();
    }

    public function store(SectionRequest $request): JsonResponse
    {
        $data = SectionResource::make(
            $this->sectionService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Section)
            ->setData($data)
            ->successResponse();
    }

    public function update(SectionRequest $request, Section $section): JsonResponse
    {
        $data = SectionResource::make(
            $this->sectionService->update($request, $section),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Section)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Section $section): JsonResponse
    {
        $data = SectionResource::make(
            $this->sectionService->destroy($section),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Section)
            ->setData($data)
            ->successResponse();
    }

    public function view(Section $section, string $fileName): BinaryFileResponse
    {
        $file = $this->sectionService->view($section, $fileName);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Section $section): BinaryFileResponse
    {
        $zip = $this->sectionService->download($section);

        return $this->controller->setZip($zip)
            ->downloadZipResponse();
    }

    public function upload(FileUploadRequest $request, Section $section): JsonResponse
    {
        $message = $this->uploadService->uploadSectionFile($request, $section);

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

    public function destroyAttachment(Section $section, string $fileName): JsonResponse
    {
        $this->sectionService->destroyAttachment($section, $fileName);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::File)
            ->setData((object) [])
            ->successResponse();
    }
}
