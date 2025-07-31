<?php

namespace App\Http\Controllers\QuestionBank;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\QuestionBank\QuestionBankService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\QuestionBank\QuestionBankRequest;
use App\Http\Resources\QuestionBank\QuestionBankResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\QuestionBank\QuestionBank;

class QuestionBankController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected QuestionBankService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(QuestionBankRequest $request): JsonResponse
    {
        // $this->authorize('index', [QuestionBank::class, $request->validated('course_id')]);

        $data = QuestionBankResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::QuestionBank)
            ->setData($data)
            ->successResponse();
    }

    public function show(QuestionBank $questionBank): JsonResponse
    {
        // $this->authorize('show', $questionBank);

        $data = QuestionBankResource::make(
            $this->service->show($questionBank),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::QuestionBank)
            ->setData($data)
            ->successResponse();
    }

    public function store(QuestionBankRequest $request): JsonResponse
    {
        // $this->authorize('store', QuestionBank::class);

        $data = QuestionBankResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::QuestionBank)
            ->setData($data)
            ->successResponse();
    }

    // public function update(QuestionBankRequest $request, QuestionBank $questionBank): JsonResponse
    // {
    //     $data = QuestionBankResource::make(
    //         $this->service->update($request, $questionBank),
    //     );

    //     return $this->controller->setFunctionName(FunctionName::Update)
    //         ->setModelName(ModelName::QuestionBank)
    //         ->setData($data)
    //         ->successResponse();
    // }

    public function destroy(QuestionBank $questionBank): JsonResponse
    {
        // $this->authorize('destroy', $questionBank);

        $data = QuestionBankResource::make(
            $this->service->destroy($questionBank),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::QuestionBank)
            ->setData($data)
            ->successResponse();
    }
}
