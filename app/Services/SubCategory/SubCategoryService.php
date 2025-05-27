<?php

namespace App\Services\SubCategory;

use App\Repositories\SubCategory\SubCategoryRepositoryInterface;
use App\Http\Requests\SubCategory\SubCategoryRequest;
use App\Models\SubCategory\SubCategory;
use App\DataTransferObjects\SubCategory\SubCategoryDto;

class SubCategoryService
{
    public function __construct(
        protected SubCategoryRepositoryInterface $repository,
    ) {}

    public function index(SubCategoryRequest $request): object
    {
        $dto = SubCategoryDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(SubCategory $subCategory): object
    {
        return $this->repository->find($subCategory->id);
    }

    public function store(SubCategoryRequest $request): object
    {
        $dto = SubCategoryDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory): object
    {
        $dto = SubCategoryDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $subCategory->id);
    }

    public function destroy(SubCategory $subCategory): object
    {
        return $this->repository->delete($subCategory->id);
    }

    public function view(SubCategory $subCategory): string
    {
        return $this->repository->view($subCategory->id);
    }

    public function download(SubCategory $subCategory): string
    {
        return $this->repository->download($subCategory->id);
    }

    public function destroyAttachment(SubCategory $subCategory): void
    {
        $this->repository->deleteAttachment($subCategory->id);
    }
}
