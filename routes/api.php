<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Permission\PermissionToUserController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Reply\ReplyController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\LearningActivity\LearningActivityController;
use App\Http\Controllers\Section\SectionController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\SubCategory\SubCategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\AdminController;
use App\Http\Controllers\Profile\UserProfileController;
use App\Http\Controllers\Profile\AdminProfileController;
use App\Http\Controllers\Holiday\HolidayController;
use App\Http\Controllers\Leave\LeaveController;
use App\Http\Controllers\Policy\PolicyController;
use App\Http\Controllers\TeachingHour\TeachingHourController;
use App\Http\Controllers\ScheduleTiming\ScheduleTimingController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Grade\GradeController;
use App\Http\Controllers\Progress\ProgressController;
use App\Http\Controllers\Attendance\AttendanceController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
////user
Route::middleware('auth:api')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::post('/add-student-to-course', [UserController::class, 'addStudentToCourse']);
});


/////password
Route::post('/forgot-password-code', [AuthController::class, 'sendPasswordResetCode']);
Route::post('/reset-password-code', [AuthController::class, 'verifyPasswordResetCode']);

//////profile
Route::middleware('auth:api')->prefix('user_profile')->group(function () {
    // Route::get('/', [UserProfileController::class, 'show']);
    // Route::post('/', [UserProfileController::class, 'store']);
    // Route::put('/', [UserProfileController::class, 'update']);
});
/////add and delete permissions for user
// Route::middleware(['auth:api'])->prefix('admin')->group(function () {
//     Route::post('permissions/assign', [PermissionController::class, 'assign']);
//     Route::post('permissions/revoke', [PermissionController::class, 'revoke']);
// });
///// permission
Route::middleware(['auth:api'])->prefix('permissions')->group(function () {
    Route::post('/', [PermissionController::class, 'store']);
    Route::get('/', [PermissionController::class, 'index']);
    Route::put('/{permission}', [PermissionController::class, 'update']);
    Route::delete('/{permission}', [PermissionController::class, 'destroy']);
    Route::get('/roles/{role}', [PermissionController::class, 'getPermissionsByRole']);
    Route::get('/users/{user}', [PermissionController::class, 'getPermissionsByUser']);
});


////assign and revoke permission for user
Route::middleware(['auth:api'])->prefix('permissions')->group(function () {
    Route::post('/assign', [PermissionToUserController::class, 'assignPermission']);
    Route::post('/revoke', [PermissionToUserController::class, 'revokePermission']);
});

//     Route::apiResource('chat', ChatController::class)->only(['index', 'show', 'store']);
//     Route::apiResource('message', MessageController::class)->only(['index', 'store']);
//     Route::apiResource('user', UserController::class)->only(['index']);
//     Route::apiResource('notification', NotificationController::class)->except(['show', 'update']);

// });





Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('chat', ChatController::class)->except(['update']);
    Route::apiResource('message', MessageController::class)->except(['show']);
    Route::apiResource('reply', ReplyController::class)->except(['show', 'index']);
    Route::apiResource('notification', NotificationController::class)->except(['show', 'update']);
    Route::apiResource('course', CourseController::class);
    Route::get('view-course-image/{course}', [CourseController::class, 'view']);
    Route::get('download-course-image/{course}', [CourseController::class, 'download']);
    Route::post('upload-course-image/{course}', [CourseController::class, 'upload']);
    Route::delete('delete-course-image/{course}', [CourseController::class, 'destroyAttachment']);
    Route::apiResource('group', GroupController::class);
    Route::get('join-group/{group}', [GroupController::class, 'join']);
    Route::get('leave-group/{group}', [GroupController::class, 'leave']);
    Route::get('view-group-image/{group}', [GroupController::class, 'view']);
    Route::get('download-group-image/{group}', [GroupController::class, 'download']);
    Route::post('upload-group-image/{group}', [GroupController::class, 'upload']);
    Route::delete('delete-group-image/{group}', [GroupController::class, 'destroyAttachment']);
    Route::apiResource('learning-activity', LearningActivityController::class);
    Route::get('view-learning-activity-content/{learningActivity}', [LearningActivityController::class, 'view']);
    Route::get('download-learning-activity-content/{learningActivity}', [LearningActivityController::class, 'download']);
    Route::post('upload-learning-activity-content/{learningActivity}', [LearningActivityController::class, 'upload']);
    Route::delete('delete-learning-activity-content/{learningActivity}', [LearningActivityController::class, 'destroyAttachment']);
    Route::apiResource('section', SectionController::class);
    Route::get('view-section-file/{section}/{fileName}', [SectionController::class, 'view']);
    Route::get('download-section-file/{section}', [SectionController::class, 'download']);
    Route::post('upload-section-file/{section}', [SectionController::class, 'upload']);
    Route::delete('delete-section-file/{section}/{fileName}', [SectionController::class, 'destroyAttachment']);
    Route::apiResource('category', CategoryController::class);
    Route::get('view-category-image/{category}', [CategoryController::class, 'view']);
    Route::get('download-category-image/{category}', [CategoryController::class, 'download']);
    Route::post('upload-category-image/{category}', [CategoryController::class, 'upload']);
    Route::delete('delete-category-image/{category}', [CategoryController::class, 'destroyAttachment']);
    Route::apiResource('sub-category', SubCategoryController::class);
    Route::get('view-sub-category-image/{subCategory}', [SubCategoryController::class, 'view']);
    Route::get('download-sub-category-image/{subCategory}', [SubCategoryController::class, 'download']);
    Route::post('upload-sub-category-image/{subCategory}', [SubCategoryController::class, 'upload']);
    Route::delete('delete-sub-category-image/{subCategory}', [SubCategoryController::class, 'destroyAttachment']);
    Route::apiResource('holiday', HolidayController::class);
    Route::apiResource('leave', LeaveController::class);
    Route::apiResource('policy', PolicyController::class);
    Route::apiResource('teaching-hour', TeachingHourController::class);
    Route::apiResource('schedule-timing', ScheduleTimingController::class);
    Route::apiResource('event', EventController::class);
    Route::get('view-event-file/{event}/{fileName}', [EventController::class, 'view']);
    Route::get('download-event-file/{event}', [EventController::class, 'download']);
    Route::post('upload-event-file/{event}', [EventController::class, 'upload']);
    Route::delete('delete-event-file/{event}/{fileName}', [EventController::class, 'destroyAttachment']);
    Route::apiResource('grade', GradeController::class);
    Route::apiResource('progress', ProgressController::class);
    Route::apiResource('attendance', AttendanceController::class);
    Route::apiResource('profile', UserProfileController::class);
    Route::get('view-profile-image', [UserProfileController::class, 'view']);
    Route::get('download-profile-image', [UserProfileController::class, 'download']);
    Route::post('upload-profile-image', [UserProfileController::class, 'upload']);
    Route::delete('delete-profile-image', [UserProfileController::class, 'destroyAttachment']);
    Route::apiResource('admin-profile', AdminProfileController::class);
    Route::get('view-admin-profile-image/{adminProfile}', [AdminProfileController::class, 'view']);
    Route::get('download-admin-profile-image/{adminProfile}', [AdminProfileController::class, 'download']);
    Route::post('upload-admin-profile-image/{adminProfile}', [AdminProfileController::class, 'upload']);
    Route::delete('delete-admin-profile-image/{adminProfile}', [AdminProfileController::class, 'destroyAttachment']);
    Route::apiResource('user', UserController::class);
    Route::post('add-student-to-course', [UserController::class, 'addStudentToCourse']);
    Route::post('remove-student-from-course', [UserController::class, 'removeStudentFromCourse']);
    Route::apiResource('admin-user', AdminController::class);
// });
// Route::apiResource('user', UserController::class)->only(['index']);


// Route::post('course/{course}', [CourseController::class, 'update']);
// Route::post('group/{group}', [GroupController::class, 'update']);
// Route::post('section/{section}', [SectionController::class, 'update']);
// Route::post('learning_activity/{learningActivity}', [LearningActivityController::class, 'update']);
