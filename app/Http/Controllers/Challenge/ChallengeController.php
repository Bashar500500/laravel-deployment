<?php

namespace App\Http\Controllers\Challenge;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Challenge\ChallengeService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Challenge\ChallengeRequest;
use App\Http\Resources\Challenge\ChallengeResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Challenge\Challenge;

class ChallengeController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ChallengeService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(ChallengeRequest $request): JsonResponse
    {
        // $this->authorize('index', Challenge::class);

        $data = ChallengeResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Challenge)
            ->setData($data)
            ->successResponse();
    }

    public function show(Challenge $challenge): JsonResponse
    {
        // $this->authorize('show', $challenge);

        $data = ChallengeResource::make(
            $this->service->show($challenge),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Challenge)
            ->setData($data)
            ->successResponse();
    }

    public function store(ChallengeRequest $request): JsonResponse
    {
        // $this->authorize('store', Challenge::class);

        $data = ChallengeResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Challenge)
            ->setData($data)
            ->successResponse();
    }

    public function update(ChallengeRequest $request, Challenge $challenge): JsonResponse
    {
        // $this->authorize('update', $challenge);

        $data = ChallengeResource::make(
            $this->service->update($request, $challenge),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Challenge)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Challenge $challenge): JsonResponse
    {
        // $this->authorize('destroy', $challenge);

        $data = ChallengeResource::make(
            $this->service->destroy($challenge),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Challenge)
            ->setData($data)
            ->successResponse();
    }

    public function join(Challenge $challenge): JsonResponse
    {
        // $this->authorize('join', $challenge);

        $this->service->join($challenge);

        return $this->controller->setFunctionName(FunctionName::Join)
            ->setModelName(ModelName::Challenge)
            ->setData((object) [])
            ->successResponse();
    }

    public function leave(Challenge $challenge): JsonResponse
    {
        // $this->authorize('leave', $challenge);

        $this->service->leave($challenge);

        return $this->controller->setFunctionName(FunctionName::Leave)
            ->setModelName(ModelName::Challenge)
            ->setData((object) [])
            ->successResponse();
    }
}
