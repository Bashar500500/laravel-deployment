<?php

namespace App\Http\Controllers\CourseReview;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\CourseReview\CourseReviewService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CourseReview\CourseReviewRequest;
use App\Http\Resources\CourseReview\CourseReviewResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\CourseReview\CourseReview;

class CourseReviewController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected CourseReviewService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(CourseReviewRequest $request): JsonResponse
    {
        // $this->authorize('index', CourseReview::class);

        $data = CourseReviewResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::CourseReview)
            ->setData($data)
            ->successResponse();
    }

    public function show(CourseReview $courseReview): JsonResponse
    {
        // $this->authorize('show', $courseReview);

        $data = CourseReviewResource::make(
            $this->service->show($courseReview),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::CourseReview)
            ->setData($data)
            ->successResponse();
    }

    public function store(CourseReviewRequest $request): JsonResponse
    {
        // $this->authorize('store', CourseReview::class);

        $data = CourseReviewResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::CourseReview)
            ->setData($data)
            ->successResponse();
    }

    public function update(CourseReviewRequest $request, CourseReview $courseReview): JsonResponse
    {
        // $this->authorize('update', $courseReview);

        $data = CourseReviewResource::make(
            $this->service->update($request, $courseReview),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::CourseReview)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(CourseReview $courseReview): JsonResponse
    {
        // $this->authorize('destroy', $courseReview);

        $data = CourseReviewResource::make(
            $this->service->destroy($courseReview),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::CourseReview)
            ->setData($data)
            ->successResponse();
    }
}
