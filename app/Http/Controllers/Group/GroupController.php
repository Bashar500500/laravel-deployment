<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Group\GroupService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Group\GroupRequest;
use App\Http\Resources\Group\GroupResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Group\Group;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Enums\Upload\UploadMessage;

class GroupController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected GroupService $groupService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(GroupRequest $request): JsonResponse
    {
        $data = GroupResource::collection(
            $this->groupService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Group)
            ->setData($data)
            ->successResponse();
    }

    public function show(Group $group): JsonResponse
    {
        $data = GroupResource::make(
            $this->groupService->show($group),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Group)
            ->setData($data)
            ->successResponse();
    }

    public function store(GroupRequest $request): JsonResponse
    {
        $data = GroupResource::make(
            $this->groupService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Group)
            ->setData($data)
            ->successResponse();
    }

    public function update(GroupRequest $request, Group $group): JsonResponse
    {
        $data = GroupResource::make(
            $this->groupService->update($request, $group),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Group)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Group $group): JsonResponse
    {
        $data = GroupResource::make(
            $this->groupService->destroy($group),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Group)
            ->setData($data)
            ->successResponse();
    }

    public function join(Group $group): JsonResponse
    {
        $this->groupService->join($group);

        return $this->controller->setFunctionName(FunctionName::Join)
            ->setModelName(ModelName::Group)
            ->setData((object) [])
            ->successResponse();
    }

    public function leave(Group $group): JsonResponse
    {
        $this->groupService->leave($group);

        return $this->controller->setFunctionName(FunctionName::Leave)
            ->setModelName(ModelName::Group)
            ->setData((object) [])
            ->successResponse();
    }

    public function view(Group $group): BinaryFileResponse
    {
        $file = $this->groupService->view($group);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Group $group): BinaryFileResponse
    {
        $file = $this->groupService->download($group);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ImageUploadRequest $request, Group $group): JsonResponse
    {
        $message = $this->uploadService->uploadGroupImage($request, $group);

        return match ($message) {
            UploadMessage::Image => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Image)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Chunk => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Chunk)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function destroyAttachment(Group $group): JsonResponse
    {
        $this->groupService->destroyAttachment($group);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Image)
            ->setData((object) [])
            ->successResponse();
    }
}
