<?php

namespace App\Services\CourseReview;

use App\Repositories\CourseReview\CourseReviewRepositoryInterface;
use App\Http\Requests\CourseReview\CourseReviewRequest;
use App\Models\CourseReview\CourseReview;
use App\DataTransferObjects\CourseReview\CourseReviewDto;
use Illuminate\Support\Facades\Auth;

class CourseReviewService
{
    public function __construct(
        protected CourseReviewRepositoryInterface $repository,
    ) {}

    public function index(CourseReviewRequest $request): object
    {
        $dto = CourseReviewDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(CourseReview $courseReview): object
    {
        return $this->repository->find($courseReview->id);
    }

    public function store(CourseReviewRequest $request): object
    {
        $dto = CourseReviewDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->create($dto, $data);
    }

    public function update(CourseReviewRequest $request, CourseReview $courseReview): object
    {
        $dto = CourseReviewDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $courseReview->id);
    }

    public function destroy(CourseReview $courseReview): object
    {
        return $this->repository->delete($courseReview->id);
    }

    private function prepareStoreAndIndexData(): array
    {
        return [
            'studentId' => Auth::user()->id,
        ];
    }
}
