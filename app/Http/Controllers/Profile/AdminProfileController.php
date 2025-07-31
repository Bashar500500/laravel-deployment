<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Profile\AdminProfileService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Profile\AdminProfileRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Profile\Profile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Enums\Upload\UploadMessage;

class AdminProfileController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AdminProfileService $adminProfileService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(AdminProfileRequest $request): JsonResponse
    {
        // $this->authorize('adminIndex', Profile::class);

        $data = ProfileResource::collection(
            $this->adminProfileService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function show(Profile $adminProfile): JsonResponse
    {
        // $this->authorize('adminShow', $adminProfile);

        $data = ProfileResource::make(
            $this->adminProfileService->show($adminProfile),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function store(AdminProfileRequest $request): JsonResponse
    {
        // $this->authorize('adminStore', Profile::class);

        $data = ProfileResource::make(
            $this->adminProfileService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function update(AdminProfileRequest $request, Profile $adminProfile): JsonResponse
    {
        // $this->authorize('adminUpdate', $adminProfile);

        $data = ProfileResource::make(
            $this->adminProfileService->update($request, $adminProfile),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Profile $adminProfile): JsonResponse
    {
        // $this->authorize('adminDestroy', $adminProfile);

        $data = ProfileResource::make(
            $this->adminProfileService->destroy($adminProfile),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Profile)
            ->setData($data)
            ->successResponse();
    }

    public function view(Profile $adminProfile): BinaryFileResponse
    {
        // $this->authorize('adminView', $adminProfile);

        $file = $this->adminProfileService->view($adminProfile);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Profile $adminProfile): BinaryFileResponse
    {
        // $this->authorize('adminDownload', $adminProfile);

        $file = $this->adminProfileService->download($adminProfile);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ImageUploadRequest $request, Profile $adminProfile): JsonResponse
    {
        // $this->authorize('adminUpload', $adminProfile);

        $message = $this->uploadService->uploadAdminProfileImage($request, $adminProfile);

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

    public function destroyAttachment(Profile $adminProfile): JsonResponse
    {
        // $this->authorize('adminDestroyAttachment', $adminProfile);

        $this->adminProfileService->destroyAttachment($adminProfile);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Image)
            ->setData((object) [])
            ->successResponse();
    }
}
