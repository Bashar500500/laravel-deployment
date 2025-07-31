<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Event\EventService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Event\EventRequest;
use App\Http\Resources\Event\EventResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Event\Event;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Enums\Upload\UploadMessage;

class EventController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected EventService $eventService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(EventRequest $request): JsonResponse
    {
        // $this->authorize('index', [Event::class, $request->validated('course_id')]);

        $data = EventResource::collection(
            $this->eventService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Event)
            ->setData($data)
            ->successResponse();
    }

    public function show(Event $event): JsonResponse
    {
        // $this->authorize('show', $event);

        $data = EventResource::make(
            $this->eventService->show($event),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Event)
            ->setData($data)
            ->successResponse();
    }

    public function store(EventRequest $request): JsonResponse
    {
        // $this->authorize('store', Event::class);

        $data = EventResource::make(
            $this->eventService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Event)
            ->setData($data)
            ->successResponse();
    }

    public function update(EventRequest $request, Event $event): JsonResponse
    {
        // $this->authorize('update', $event);

        $data = EventResource::make(
            $this->eventService->update($request, $event),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Event)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Event $event): JsonResponse
    {
        // $this->authorize('destroy', $event);

        $data = EventResource::make(
            $this->eventService->destroy($event),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Event)
            ->setData($data)
            ->successResponse();
    }

    public function view(Event $event, string $fileName): BinaryFileResponse
    {
        // $this->authorize('view', $event);

        $file = $this->eventService->view($event, $fileName);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Event $event): BinaryFileResponse
    {
        // $this->authorize('download', $event);

        $zip = $this->eventService->download($event);

        return $this->controller->setZip($zip)
            ->downloadZipResponse();
    }

    public function upload(FileUploadRequest $request, Event $event): JsonResponse
    {
        // $this->authorize('upload', $event);

        $message = $this->uploadService->uploadEventFile($request, $event);

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

    public function destroyAttachment(Event $event, string $fileName): JsonResponse
    {
        // $this->authorize('destroyAttachment', $event);

        $this->eventService->destroyAttachment($event, $fileName);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::File)
            ->setData((object) [])
            ->successResponse();
    }
}
