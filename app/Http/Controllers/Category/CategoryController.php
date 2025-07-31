<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Category\CategoryService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Category\Category;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Enums\Upload\UploadMessage;

class CategoryController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected CategoryService $categoryService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(CategoryRequest $request): JsonResponse
    {
        // $this->authorize('index', Category::class);

        $data = CategoryResource::collection(
            $this->categoryService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Category)
            ->setData($data)
            ->successResponse();
    }

    public function show(Category $category): JsonResponse
    {
        // $this->authorize('show', $category);

        $data = CategoryResource::make(
            $this->categoryService->show($category),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Category)
            ->setData($data)
            ->successResponse();
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        // $this->authorize('store', Category::class);

        $data = CategoryResource::make(
            $this->categoryService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Category)
            ->setData($data)
            ->successResponse();
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        // $this->authorize('update', $category);

        $data = CategoryResource::make(
            $this->categoryService->update($request, $category),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Category)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Category $category): JsonResponse
    {
        // $this->authorize('destroy', $category);

        $data = CategoryResource::make(
            $this->categoryService->destroy($category),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Category)
            ->setData($data)
            ->successResponse();
    }

    public function view(Category $category): BinaryFileResponse
    {
        // $this->authorize('view', $category);

        $file = $this->categoryService->view($category);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Category $category): BinaryFileResponse
    {
        // $this->authorize('download', $category);

        $file = $this->categoryService->download($category);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ImageUploadRequest $request, Category $category): JsonResponse
    {
        // $this->authorize('upload', $category);

        $message = $this->uploadService->uploadCategoryImage($request, $category);

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

    public function destroyAttachment(Category $category): JsonResponse
    {
        // $this->authorize('destroyAttachment', $category);

        $this->categoryService->destroyAttachment($category);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Image)
            ->setData((object) [])
            ->successResponse();
    }
}
