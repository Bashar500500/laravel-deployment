<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category\Category;
use App\DataTransferObjects\Category\CategoryDto;

class CategoryService
{

    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {}

    public function index(CategoryRequest $request): object
    {
        $dto = CategoryDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Category $category): object
    {
        return $this->repository->find($category->id);
    }

    public function store(CategoryRequest $request): object
    {
        $dto = CategoryDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(CategoryRequest $request, Category $category): object
    {
        $dto = CategoryDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $category->id);
    }

    public function destroy(Category $category): object
    {
        return $this->repository->delete($category->id);
    }

    public function view(Category $category): string
    {
        return $this->repository->view($category->id);
    }

    public function download(Category $category): string
    {
        return $this->repository->download($category->id);
    }

    public function destroyAttachment(Category $category): void
    {
        $this->repository->deleteAttachment($category->id);
    }
}
