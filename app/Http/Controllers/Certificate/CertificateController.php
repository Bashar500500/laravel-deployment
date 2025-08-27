<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Certificate\CertificateService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Certificate\CertificateRequest;
use App\Http\Resources\Certificate\CertificateResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Certificate\Certificate;

class CertificateController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected CertificateService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(CertificateRequest $request): JsonResponse
    {
        // $this->authorize('index', Certificate::class);

        $data = CertificateResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Certificate)
            ->setData($data)
            ->successResponse();
    }

    public function show(Certificate $certificate): JsonResponse
    {
        // $this->authorize('show', $certificate);

        $data = CertificateResource::make(
            $this->service->show($certificate),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Certificate)
            ->setData($data)
            ->successResponse();
    }

    public function store(CertificateRequest $request): JsonResponse
    {
        // $this->authorize('store', Certificate::class);

        $data = CertificateResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Certificate)
            ->setData($data)
            ->successResponse();
    }

    public function update(CertificateRequest $request, Certificate $certificate): JsonResponse
    {
        // $this->authorize('update', $certificate);

        $data = CertificateResource::make(
            $this->service->update($request, $certificate),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Certificate)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Certificate $certificate): JsonResponse
    {
        // $this->authorize('destroy', $certificate);

        $data = CertificateResource::make(
            $this->service->destroy($certificate),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Certificate)
            ->setData($data)
            ->successResponse();
    }
}
