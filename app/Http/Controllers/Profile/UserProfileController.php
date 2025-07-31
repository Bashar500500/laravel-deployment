<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Profile\UserProfileService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Profile\UserProfileRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Enums\Upload\UploadMessage;
use App\Models\Profile\Profile;

class UserProfileController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected UserProfileService $userProfileService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(UserProfileRequest $request): JsonResponse
    {
        // $this->authorize('index');

        $data = ProfileResource::collection(
            $this->userProfileService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function show(Profile $profile): JsonResponse
    {
        // $this->authorize('show', $profile);

        $data = ProfileResource::make(
            $this->userProfileService->show($profile),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function profile(): JsonResponse
    {
        $data = ProfileResource::make(
            $this->userProfileService->profile(),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function store(UserProfileRequest $request): JsonResponse
    {
        $data = ProfileResource::make(
            $this->userProfileService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function update(UserProfileRequest $request): JsonResponse
    {
        // $this->authorize('update', $profile);

        $data = ProfileResource::make(
            $this->userProfileService->update($request),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(): JsonResponse
    {
        // $this->authorize('destroy', $profile);

        $data = ProfileResource::make(
            $this->userProfileService->destroy(),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function view(): BinaryFileResponse
    {
        // $this->authorize('view', $profile);

        $file = $this->userProfileService->view();

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(): BinaryFileResponse
    {
        // $this->authorize('download', $profile);

        $file = $this->userProfileService->download();

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ImageUploadRequest $request): JsonResponse
    {
        // $this->authorize('upload', $profile);

        $message = $this->uploadService->uploadUserProfileImage($request);

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

    public function destroyAttachment(): JsonResponse
    {
        // $this->authorize('destroyAttachment', $profile);

        $this->userProfileService->destroyAttachment();

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Image)
            ->setData((object) [])
            ->successResponse();
    }
}
