<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\User\UserService;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Enums\User\UserMessage;
use App\Http\Requests\User\AddUserToCourseRequest;
use App\Http\Requests\User\RemoveUserFromCourseRequest;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\InstructorFileNamesResource;
use App\Http\Resources\User\StudentFileNamesResource;
use App\Http\Requests\User\RemoveStudentFromInstructorListRequest;

class UserController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected UserService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(UserRequest $request): JsonResponse
    {
        $data = UserResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function show(User $user): JsonResponse
    {
        // $this->authorize('userShow', [User::class, $user->id]);

        $data = UserResource::make(
            $this->service->show($user),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function user(): JsonResponse
    {
        $data = UserResource::make(
            $this->service->user(),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function store(UserRequest $request): JsonResponse
    {
        // $this->authorize('userStore', User::class);

        $data = UserResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function update(UserRequest $request): JsonResponse
    {
        $data = UserResource::make(
            $this->service->update($request),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(): JsonResponse
    {
        $data = UserResource::make(
            $this->service->destroy(),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function addStudentToCourse(AddUserToCourseRequest $request): JsonResponse
    {
        // $this->authorize('addStudentToCourse', [User::class, $request->validated('course_id')]);

        $message = $this->service->addStudentToCourse($request);

        return match ($message) {
            UserMessage::StudentAddedToCourse => $this->controller->setFunctionName(FunctionName::AddStudentToCourse)
                ->setModelName(ModelName::Student)
                ->setData((object) [])
                ->successResponse(),
            UserMessage::StudentCreatedAccountAndAddedToCourse => $this->controller->setFunctionName(FunctionName::AddStudentToCourse)
                ->setModelName(ModelName::Student)
                ->setData((object) [])
                ->successResponse(),
        };
    }

    public function removeStudentFromCourse(RemoveUserFromCourseRequest $request): JsonResponse
    {
        // $this->authorize('removeStudentFromCourse', [User::class, $request->validated('student_code')]);

        $this->service->removeStudentFromCourse($request);

        return $this->controller->setFunctionName(FunctionName::RemoveStudentFromCourse)
            ->setModelName(ModelName::Student)
            ->setData((object) [])
            ->successResponse();
    }

    public function instructorFileNames(): JsonResponse
    {
        // $this->authorize('instructorFileNames', User::class);

        $user = Auth::user();

        $data = InstructorFileNamesResource::collection(
            collect($user->ownedCourses),
        );

        return $this->controller->setFunctionName(FunctionName::InstructorFileNames)
            ->setModelName(ModelName::Instructor)
            ->setData($data)
            ->successResponse();
    }

    public function studentFileNames(): JsonResponse
    {
        // $this->authorize('studentFileNames', User::class);

        $user = Auth::user();

        $data = $user->enrolledCourses
            ? $user->enrolledCourses->map(
                fn($course) => new StudentFileNamesResource($course, $user->id)
            )
            : (object) [];

        return $this->controller->setFunctionName(FunctionName::InstructorFileNames)
            ->setModelName(ModelName::Student)
            ->setData($data)
            ->successResponse();
    }

    public function removeStudentFromInstructorList(RemoveStudentFromInstructorListRequest $request): JsonResponse
    {
        // $this->authorize('removeStudentFromInstructorList', [User::class, $request->validated('student_id')]);

        $this->service->removeStudentFromInstructorList($request);

        return $this->controller->setFunctionName(FunctionName::RemoveStudentFromInstructorList)
            ->setModelName(ModelName::Student)
            ->setData((object) [])
            ->successResponse();
    }
}
