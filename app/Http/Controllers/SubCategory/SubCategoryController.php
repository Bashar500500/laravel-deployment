<?php

namespace App\Http\Controllers\SubCategory;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\SubCategory\SubCategoryService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\SubCategory\SubCategoryRequest;
use App\Http\Resources\SubCategory\SubCategoryResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\SubCategory\SubCategory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Enums\Upload\UploadMessage;

class SubCategoryController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected SubCategoryService $subCategoryService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(SubCategoryRequest $request): JsonResponse
    {
        // $this->authorize('index', SubCategory::class);

        $data = SubCategoryResource::collection(
            $this->subCategoryService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::SubCategory)
            ->setData($data)
            ->successResponse();
    }

    public function show(SubCategory $subCategory): JsonResponse
    {
        // $this->authorize('show', $subCategory);

        $data = SubCategoryResource::make(
            $this->subCategoryService->show($subCategory),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::SubCategory)
            ->setData($data)
            ->successResponse();
    }

    public function store(SubCategoryRequest $request): JsonResponse
    {
        // $this->authorize('store', SubCategory::class);

        $data = SubCategoryResource::make(
            $this->subCategoryService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::SubCategory)
            ->setData($data)
            ->successResponse();
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory): JsonResponse
    {
        // $this->authorize('update', $subCategory);

        $data = SubCategoryResource::make(
            $this->subCategoryService->update($request, $subCategory),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::SubCategory)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(SubCategory $subCategory): JsonResponse
    {
        // $this->authorize('destroy', $subCategory);

        $data = SubCategoryResource::make(
            $this->subCategoryService->destroy($subCategory),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::SubCategory)
            ->setData($data)
            ->successResponse();
    }

    public function view(SubCategory $subCategory): BinaryFileResponse
    {
        // $this->authorize('view', $subCategory);

        $file = $this->subCategoryService->view($subCategory);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(SubCategory $subCategory): BinaryFileResponse
    {
        // $this->authorize('download', $subCategory);

        $file = $this->subCategoryService->download($subCategory);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ImageUploadRequest $request, SubCategory $subCategory): JsonResponse
    {
        // $this->authorize('upload', $subCategory);

        $message = $this->uploadService->uploadSubCategoryImage($request, $subCategory);

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

    public function destroyAttachment(SubCategory $subCategory): JsonResponse
    {
        // $this->authorize('destroyAttachment', $subCategory);

        $this->subCategoryService->destroyAttachment($subCategory);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Image)
            ->setData((object) [])
            ->successResponse();
    }
}
