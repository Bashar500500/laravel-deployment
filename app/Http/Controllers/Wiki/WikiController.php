<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Wiki\WikiService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Wiki\WikiRequest;
use App\Http\Resources\Wiki\WikiResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Wiki\Wiki;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Enums\Upload\UploadMessage;

class WikiController extends Controller   // Add policies
{
    public function __construct(
        ResponseController $controller,
        protected WikiService $wikiService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(WikiRequest $request): JsonResponse
    {
        $data = WikiResource::collection(
            $this->wikiService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Wiki)
            ->setData($data)
            ->successResponse();
    }

    public function show(Wiki $wiki): JsonResponse
    {
        $data = WikiResource::make(
            $this->wikiService->show($wiki),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Wiki)
            ->setData($data)
            ->successResponse();
    }

    public function store(WikiRequest $request): JsonResponse
    {
        // $this->authorize('store', Wiki::class);

        $data = WikiResource::make(
            $this->wikiService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Wiki)
            ->setData($data)
            ->successResponse();
    }

    public function update(WikiRequest $request, Wiki $wiki): JsonResponse
    {
        // $this->authorize('update', $wiki);

        $data = WikiResource::make(
            $this->wikiService->update($request, $wiki),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Wiki)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Wiki $wiki): JsonResponse
    {
        // $this->authorize('destroy', $wiki);

        $data = WikiResource::make(
            $this->wikiService->destroy($wiki),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Wiki)
            ->setData($data)
            ->successResponse();
    }

    public function view(Wiki $wiki, string $fileName): BinaryFileResponse
    {
        // $this->authorize('view', $wiki);

        $file = $this->wikiService->view($wiki, $fileName);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Wiki $wiki): BinaryFileResponse
    {
        // $this->authorize('download', $wiki);

        $zip = $this->wikiService->download($wiki);

        return $this->controller->setZip($zip)
            ->downloadZipResponse();
    }

    public function upload(FileUploadRequest $request, Wiki $wiki): JsonResponse
    {
        // $this->authorize('upload', $wiki);

        $message = $this->uploadService->uploadWikiFile($request, $wiki);

        return match ($message) {
            UploadMessage::File => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::File)
                ->setData((object) [])
                ->successResponse(),
            UploadMessage::Chunk => $this->controller->setFunctionName(FunctionName::Upload)
                ->setModelName(ModelName::Chunk)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function destroyAttachment(Wiki $wiki, string $fileName): JsonResponse
    {
        // $this->authorize('destroyAttachment', $wiki);

        $this->wikiService->destroyAttachment($wiki, $fileName);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::File)
            ->setData((object) [])
            ->successResponse();
    }
}
