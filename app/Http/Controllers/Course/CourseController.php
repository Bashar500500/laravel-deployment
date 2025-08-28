<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Course\CourseService;
use App\Services\Global\Upload\UploadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Course\CourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Course\Course;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Enums\Upload\UploadMessage;
use App\Http\Resources\Course\CalendarResource;
use App\Http\Resources\Course\GradeBookResource;

class CourseController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected CourseService $courseService,
        protected UploadService $uploadService,
    ) {
        parent::__construct($controller);
    }

    public function index(CourseRequest $request): JsonResponse
    {
        // $this->authorize('index', Course::class);

        $data = CourseResource::collection(
            $this->courseService->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }

    public function guestIndex(CourseRequest $request): JsonResponse
    {
        $data = CourseResource::collection(
            $this->courseService->guestIndex($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }

    public function show(Course $course): JsonResponse
    {
        // $this->authorize('show', $course);

        $data = CourseResource::make(
            $this->courseService->show($course),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }

    public function store(CourseRequest $request): JsonResponse
    {
        // $this->authorize('store', Course::class);

        $data = CourseResource::make(
            $this->courseService->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }

    public function update(CourseRequest $request, Course $course): JsonResponse
    {
        // $this->authorize('update', $course);

        $data = CourseResource::make(
            $this->courseService->update($request, $course),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Course $course): JsonResponse
    {
        // $this->authorize('destroy', $course);

        $data = CourseResource::make(
            $this->courseService->destroy($course),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }

    public function view(Course $course): BinaryFileResponse
    {
        // $this->authorize('view', $course);

        $file = $this->courseService->view($course);

        return $this->controller->setFile($file)
            ->viewFileResponse();
    }

    public function download(Course $course): BinaryFileResponse
    {
        // $this->authorize('download', $course);

        $file = $this->courseService->download($course);

        return $this->controller->setFile($file)
            ->downloadFileResponse();
    }

    public function upload(ImageUploadRequest $request, Course $course): JsonResponse
    {
        // $this->authorize('upload', $course);

        $message = $this->uploadService->uploadCourseImage($request, $course);

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

    public function destroyAttachment(Course $course): JsonResponse
    {
        // $this->authorize('destroyAttachment', $course);

        $this->courseService->destroyAttachment($course);

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Image)
            ->setData((object) [])
            ->successResponse();
    }

    public function gradeBook(Course $course): JsonResponse
    {
        // $this->authorize('gradeBook', Course::class);

        $data = GradeBookResource::make(
            $course,
        );

        return $this->controller->setFunctionName(FunctionName::GradeBook)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }

    public function calendar(Course $course): JsonResponse
    {
        // $this->authorize('calendar', Course::class);

        $data = CalendarResource::make(
            $course,
        );

        return $this->controller->setFunctionName(FunctionName::Calendar)
            ->setModelName(ModelName::Course)
            ->setData($data)
            ->successResponse();
    }
}
