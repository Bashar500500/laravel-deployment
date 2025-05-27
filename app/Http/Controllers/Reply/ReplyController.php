<?php

namespace App\Http\Controllers\Reply;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Reply\ReplyService;
use App\Jobs\GlobalServiceHandlerJob;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Reply\ReplyRequest;
use App\Http\Resources\Reply\ReplyResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Reply\Reply;

class ReplyController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ReplyService $service,
    ) {
        parent::__construct($controller);
    }

    public function store(ReplyRequest $request): JsonResponse
    {
        $reply = $this->service->store($request);
        $data = ReplyResource::make(
            $reply,
        );

        GlobalServiceHandlerJob::dispatch($reply);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Reply)
            ->setData($data)
            ->successResponse();
    }

    public function update(ReplyRequest $request, Reply $reply): JsonResponse
    {
        $data = ReplyResource::make(
            $this->service->update($request, $reply),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Reply)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Reply $reply): JsonResponse
    {
        $data = ReplyResource::make(
            $this->service->destroy($reply),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Reply)
            ->setData($data)
            ->successResponse();
    }
}
