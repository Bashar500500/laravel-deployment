<?php

namespace App\Http\Controllers\CertificateTemplate;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\CertificateTemplate\CertificateTemplateService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CertificateTemplate\CertificateTemplateRequest;
use App\Http\Resources\CertificateTemplate\CertificateTemplateResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\CertificateTemplate\CertificateTemplate;

class CertificateTemplateController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected CertificateTemplateService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(CertificateTemplateRequest $request): JsonResponse
    {
        // $this->authorize('index', CertificateTemplate::class);

        $data = CertificateTemplateResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::CertificateTemplate)
            ->setData($data)
            ->successResponse();
    }

    public function show(CertificateTemplate $certificateTemplate): JsonResponse
    {
        // $this->authorize('show', $certificateTemplate);

        $data = CertificateTemplateResource::make(
            $this->service->show($certificateTemplate),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::CertificateTemplate)
            ->setData($data)
            ->successResponse();
    }

    public function store(CertificateTemplateRequest $request): JsonResponse
    {
        // $this->authorize('store', CertificateTemplate::class);

        $data = CertificateTemplateResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::CertificateTemplate)
            ->setData($data)
            ->successResponse();
    }

    public function update(CertificateTemplateRequest $request, CertificateTemplate $certificateTemplate): JsonResponse
    {
        // $this->authorize('update', $certificateTemplate);

        $data = CertificateTemplateResource::make(
            $this->service->update($request, $certificateTemplate),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::CertificateTemplate)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(CertificateTemplate $certificateTemplate): JsonResponse
    {
        // $this->authorize('destroy', $certificateTemplate);

        $data = CertificateTemplateResource::make(
            $this->service->destroy($certificateTemplate),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::CertificateTemplate)
            ->setData($data)
            ->successResponse();
    }
}
