<?php

namespace App\Http\Controllers\ForumPost;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\ForumPost\ForumPostService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ForumPost\ForumPostRequest;
use App\Http\Resources\ForumPost\ForumPostResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\ForumPost\ForumPost;

class ForumPostController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ForumPostService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(ForumPostRequest $request): JsonResponse
    {
        // $this->authorize('index', ForumPost::class);

        $data = ForumPostResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::ForumPost)
            ->setData($data)
            ->successResponse();
    }

    public function show(ForumPost $forumPost): JsonResponse
    {
        // $this->authorize('show', $forumPost);

        $data = ForumPostResource::make(
            $this->service->show($forumPost),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::ForumPost)
            ->setData($data)
            ->successResponse();
    }

    public function store(ForumPostRequest $request): JsonResponse
    {
        // $this->authorize('store', ForumPost::class);

        $data = ForumPostResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::ForumPost)
            ->setData($data)
            ->successResponse();
    }

    public function update(ForumPostRequest $request, ForumPost $forumPost): JsonResponse
    {
        // $this->authorize('update', $forumPost);

        $data = ForumPostResource::make(
            $this->service->update($request, $forumPost),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::ForumPost)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(ForumPost $forumPost): JsonResponse
    {
        // $this->authorize('destroy', $forumPost);

        $data = ForumPostResource::make(
            $this->service->destroy($forumPost),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::ForumPost)
            ->setData($data)
            ->successResponse();
    }
}
