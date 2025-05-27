<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Permission\PermissionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Permission\PermissionRequest;
use App\Http\Requests\Permission\PermissionUserRequest;
use App\Http\Resources\Permission\PermissionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\User\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected PermissionService $service
    ) {
        parent::__construct($controller);
    }

    public function index(PermissionRequest $request): JsonResponse
    {
        $data = PermissionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Permission)
            ->setData($data)
            ->successResponse();
    }

    public function show(Permission $permission): JsonResponse
    {
        $data = PermissionResource::make(
            $this->service->show($permission),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Permission)
            ->setData($data)
            ->successResponse();
    }

    public function store(PermissionRequest $request): JsonResponse
    {
        $data = PermissionResource::make(
            $this->service->create($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Permission)
            ->setData($data)
            ->successResponse();
    }

    public function update(PermissionRequest $request, Permission $permission): JsonResponse
    {
        $data = PermissionResource::make(
            $this->service->update($request, $permission),
        );

        return $this->controller
        ->setFunctionName(FunctionName::Update)
        ->setModelName(ModelName::Permission)
        ->setData($data)
        ->successResponse();
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $data = PermissionResource::make(
            $this->service->destroy($permission),
        );

        return $this->controller
            ->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Permission)
            ->setData($data)
            ->successResponse();
    }

    public function getPermissionsByRole(Role $role): JsonResponse
    {
        $data = PermissionResource::collection(
            $this->service->getPermissionsByRole($role),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Permission)
            ->setData($data)
            ->successResponse();
    }

    public function getPermissionsByUser(User $user): JsonResponse
    {
        $data = PermissionResource::collection(
            $this->service->getPermissionsByUser($user),
        );

        return $this->controller
            ->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Permission)
            ->setData($data)
            ->successResponse();
    }

    public function assignPermission(PermissionUserRequest $request)
    {
        $this->service->assignPermissionToUser($request);

        return $this->controller->setFunctionName(FunctionName::Assign)
        ->setModelName(ModelName::Permission)
        ->setData((object) [])
        ->successResponse();
    }

    public function revokePermission(PermissionUserRequest $request)
    {
        $this->service->revokePermissionFromUser($request);

        return $this->controller->setFunctionName(FunctionName::Revoke)
        ->setModelName(ModelName::Permission)
        ->setData((object) [])
        ->successResponse();
    }
}
