<?php

namespace App\Http\Controllers\Plagiarism;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Plagiarism\PlagiarismService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Plagiarism\PlagiarismRequest;
use App\Http\Resources\Plagiarism\PlagiarismResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Plagiarism\Plagiarism;

class PlagiarismController extends Controller   // Add Policies
{
    public function __construct(
        ResponseController $controller,
        protected PlagiarismService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(PlagiarismRequest $request): JsonResponse
    {
        // $this->authorize('index', Plagiarism::class);

        $data = PlagiarismResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Plagiarism)
            ->setData($data)
            ->successResponse();
    }

    public function show(Plagiarism $plagiarism): JsonResponse
    {
        // $this->authorize('show', $plagiarism);

        $data = PlagiarismResource::make(
            $this->service->show($plagiarism),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Plagiarism)
            ->setData($data)
            ->successResponse();
    }

    public function update(PlagiarismRequest $request, Plagiarism $plagiarism): JsonResponse
    {
        // $this->authorize('update', $plagiarism);

        $data = PlagiarismResource::make(
            $this->service->update($request, $plagiarism),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Plagiarism)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Plagiarism $plagiarism): JsonResponse
    {
        // $this->authorize('destroy', $plagiarism);

        $data = PlagiarismResource::make(
            $this->service->destroy($plagiarism),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Plagiarism)
            ->setData($data)
            ->successResponse();
    }
}
