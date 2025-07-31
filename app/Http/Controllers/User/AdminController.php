<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\User\AdminService;
use App\Http\Requests\User\AdminRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\User\User;

class AdminController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AdminService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AdminRequest $request): JsonResponse
    {
        // $this->authorize('adminIndex', User::class);

        $data = UserResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function show(User $adminUser): JsonResponse
    {
        // $this->authorize('adminShow', User::class);

        $data = UserResource::make(
            $this->service->show($adminUser),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function store(AdminRequest $request): JsonResponse
    {
        // $this->authorize('adminStore', User::class);

        $data = UserResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function update(AdminRequest $request, User $adminUser): JsonResponse
    {
        // $this->authorize('adminUpdate', User::class);

        $data = UserResource::make(
            $this->service->update($request, $adminUser),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(User $adminUser): JsonResponse
    {
        // $this->authorize('adminDestroy', User::class);

        $data = UserResource::make(
            $this->service->destroy($adminUser),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }
}
