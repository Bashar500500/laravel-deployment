<?php

namespace App\Http\Controllers\WikiRating;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\WikiRating\WikiRatingService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\WikiRating\WikiRatingRequest;
use App\Http\Resources\WikiRating\WikiRatingResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\WikiRating\WikiRating;

class WikiRatingController extends Controller  // Add policies
{
    public function __construct(
        ResponseController $controller,
        protected WikiRatingService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(WikiRatingRequest $request): JsonResponse
    {
        $data = WikiRatingResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::WikiRating)
            ->setData($data)
            ->successResponse();
    }

    public function show(WikiRating $wikiRating): JsonResponse
    {
        $data = WikiRatingResource::make(
            $this->service->show($wikiRating),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::WikiRating)
            ->setData($data)
            ->successResponse();
    }

    public function store(WikiRatingRequest $request): JsonResponse
    {
        $data = WikiRatingResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::WikiRating)
            ->setData($data)
            ->successResponse();
    }

    public function update(WikiRatingRequest $request, WikiRating $wikiRating): JsonResponse
    {
        // $this->authorize('update', $wikiRating);

        $data = WikiRatingResource::make(
            $this->service->update($request, $wikiRating),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::WikiRating)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(WikiRating $wikiRating): JsonResponse
    {
        // $this->authorize('destroy', $wikiRating);

        $data = WikiRatingResource::make(
            $this->service->destroy($wikiRating),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::WikiRating)
            ->setData($data)
            ->successResponse();
    }
}
