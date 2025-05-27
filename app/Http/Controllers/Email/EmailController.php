<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Email\EmailService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Email\EmailRequest;
use App\Http\Resources\Email\EmailResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Email\Email;

class EmailController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected EmailService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(EmailRequest $request): JsonResponse
    {
        $data = EmailResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Email)
            ->setData($data)
            ->successResponse();
    }

    public function show(Email $email): JsonResponse
    {
        $data = EmailResource::make(
            $this->service->show($email),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Email)
            ->setData($data)
            ->successResponse();
    }

    public function store(EmailRequest $request): JsonResponse
    {
        $data = EmailResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Email)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Email $email): JsonResponse
    {
        $data = EmailResource::make(
            $this->service->destroy($email),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Email)
            ->setData($data)
            ->successResponse();
    }
}
