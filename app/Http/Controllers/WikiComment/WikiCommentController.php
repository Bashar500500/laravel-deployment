<?php

namespace App\Http\Controllers\WikiComment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\WikiComment\WikiCommentService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\WikiComment\WikiCommentRequest;
use App\Http\Resources\WikiComment\WikiCommentResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\WikiComment\WikiComment;

class WikiCommentController extends Controller   // Add policies
{
    public function __construct(
        ResponseController $controller,
        protected WikiCommentService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(WikiCommentRequest $request): JsonResponse
    {
        $data = WikiCommentResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::WikiComment)
            ->setData($data)
            ->successResponse();
    }

    public function show(WikiComment $wikiComment): JsonResponse
    {
        $data = WikiCommentResource::make(
            $this->service->show($wikiComment),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::WikiComment)
            ->setData($data)
            ->successResponse();
    }

    public function store(WikiCommentRequest $request): JsonResponse
    {
        $data = WikiCommentResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::WikiComment)
            ->setData($data)
            ->successResponse();
    }

    public function update(WikiCommentRequest $request, WikiComment $wikiComment): JsonResponse
    {
        // $this->authorize('update', $wikiComment);

        $data = WikiCommentResource::make(
            $this->service->update($request, $wikiComment),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::WikiComment)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(WikiComment $wikiComment): JsonResponse
    {
        // $this->authorize('destroy', $wikiComment);

        $data = WikiCommentResource::make(
            $this->service->destroy($wikiComment),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::WikiComment)
            ->setData($data)
            ->successResponse();
    }
}
