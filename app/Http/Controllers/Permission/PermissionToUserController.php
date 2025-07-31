<?php

namespace App\Http\Controllers\Permission;

use App\DataTransferObjects\Permission\UserPermissionDto;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Http\Requests\Permission\PermissionUserRequest;
use App\Services\Permission\PermissionService;
use Illuminate\Http\Request;

class PermissionToUserController extends Controller
{

    public function __construct(
        ResponseController $controller,
        protected PermissionService $service
    ) {
        parent::__construct($controller);
    }
    public function assignPermission(PermissionUserRequest $request)
    {
        $permission =$this->service->assignPermissionToUser($request);
        return $this->controller
        ->setFunctionName(FunctionName::Assign)
        ->setModelName(ModelName::Permission)
        ->setData($permission)
        ->successResponse();
    }

    public function revokePermission(PermissionUserRequest $request)
    {
        $permission =$this->service->revokePermissionFromUser($request);
        return $this->controller
        ->setFunctionName(FunctionName::Revoke)
        ->setModelName(ModelName::Permission)
        ->setData($permission)
        ->successResponse();
    }
}
