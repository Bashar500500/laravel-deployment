<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Analytics\AttendanceLeavesRequest;
use App\Http\Requests\Analytics\CourseCompletionRatesAndstudentEngagementRequest;
use App\Http\Requests\Analytics\ActiveProjectsRequest;
use App\Http\Resources\Analytics\InstructorResource;
use App\Http\Resources\Analytics\StatisticsResource;
use App\Http\Resources\Analytics\UpcomingEventsResource;
use App\Http\Resources\Analytics\AttendanceLeavesResource;
use App\Http\Resources\Analytics\TeachingHoursChartResource;
use App\Http\Resources\Analytics\NotificationsResource;
use App\Http\Resources\Analytics\CourseCompletionRatesAndstudentEngagementResource;
use App\Http\Resources\Analytics\ActiveProjectsResource;
use App\Http\Resources\Analytics\StudentOfMonthResource;
use App\Http\Resources\Analytics\AcademicPoliciesResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\User\User;

class AnalyticsController extends Controller
{
    public function __construct(
        ResponseController $controller,
    ) {
        parent::__construct($controller);
    }

    public function instructor(User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = InstructorResource::make(
            $user,
        );

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function statistics(User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = StatisticsResource::make(
            $user,
        );

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function upcomingEvents(User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = UpcomingEventsResource::make(
            $user,
        );

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function attendanceLeaves(AttendanceLeavesRequest $request, User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = new AttendanceLeavesResource($user, $request->validated('year'));

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function teachingHoursChart(User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = TeachingHoursChartResource::make(
            $user,
        );

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function notifications(User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = NotificationsResource::make(
            $user,
        );

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function courseCompletionRatesAndstudentEngagement(CourseCompletionRatesAndstudentEngagementRequest $request, User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = new CourseCompletionRatesAndstudentEngagementResource($user, $request->validated('course_id'));

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function activeProjects(ActiveProjectsRequest $request, User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = new ActiveProjectsResource($user, $request->validated('course_id'));

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function studentOfMonth(User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = StudentOfMonthResource::make(
            $user,
        );

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }

    public function academicPolicies(User $user): JsonResponse
    {
        // $this->authorize('index');

        $data = AcademicPoliciesResource::make(
            $user,
        );

        return $this->controller->setFunctionName(FunctionName::Analytics)
            ->setModelName(ModelName::Analytics)
            ->setData($data)
            ->successResponse();
    }
}
