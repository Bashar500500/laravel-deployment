<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Ticket\TicketService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Resources\Ticket\TicketResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Ticket\Ticket;

class TicketController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected TicketService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(TicketRequest $request): JsonResponse
    {
        $data = (object) TicketResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Ticket)
            ->setData($data)
            ->successResponse();
    }

    public function show(Ticket $ticket): JsonResponse
    {
        $data = TicketResource::make(
            $this->service->show($ticket),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Ticket)
            ->setData($data)
            ->successResponse();
    }

    public function store(TicketRequest $request): JsonResponse
    {
        $data = TicketResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Ticket)
            ->setData($data)
            ->successResponse();
    }

    public function update(TicketRequest $request, Ticket $ticket): JsonResponse
    {
        $data = TicketResource::make(
            $this->service->update($request, $ticket),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Ticket)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $data = TicketResource::make(
            $this->service->destroy($ticket),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Ticket)
            ->setData($data)
            ->successResponse();
    }
}
