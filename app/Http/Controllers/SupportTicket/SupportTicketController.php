<?php

namespace App\Http\Controllers\SupportTicket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\SupportTicket\SupportTicketService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\SupportTicket\SupportTicketRequest;
use App\Http\Resources\SupportTicket\SupportTicketResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\SupportTicket\SupportTicket;

class SupportTicketController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected SupportTicketService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(SupportTicketRequest $request): JsonResponse
    {
        // $this->authorize('index', SupportTicket::class);

        $data = (object) SupportTicketResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::SupportTicket)
            ->setData($data)
            ->successResponse();
    }

    public function show(SupportTicket $supportTicket): JsonResponse
    {
        // $this->authorize('show', $supportTicket);

        $data = SupportTicketResource::make(
            $this->service->show($supportTicket),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::SupportTicket)
            ->setData($data)
            ->successResponse();
    }

    public function store(SupportTicketRequest $request): JsonResponse
    {
        // $this->authorize('store', SupportTicket::class);

        $data = SupportTicketResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::SupportTicket)
            ->setData($data)
            ->successResponse();
    }

    public function update(SupportTicketRequest $request, SupportTicket $supportTicket): JsonResponse
    {
        // $this->authorize('update', $supportTicket);

        $data = SupportTicketResource::make(
            $this->service->update($request, $supportTicket),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::SupportTicket)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(SupportTicket $supportTicket): JsonResponse
    {
        // $this->authorize('destroy', $supportTicket);

        $data = SupportTicketResource::make(
            $this->service->destroy($supportTicket),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::SupportTicket)
            ->setData($data)
            ->successResponse();
    }
}
