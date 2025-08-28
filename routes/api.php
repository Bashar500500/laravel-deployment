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
use App\Http\Controllers\CommunityAccess\CommunityAccessController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\SupportTicket\SupportTicketController;
use App\Http\Controllers\Assessment\AssessmentController;
use App\Http\Controllers\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionController;
use App\Http\Controllers\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionController;
use App\Http\Controllers\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionController;
use App\Http\Controllers\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionController;
use App\Http\Controllers\AssessmentSubmit\AssessmentSubmitController;
use App\Http\Controllers\Assignment\AssignmentController;
use App\Http\Controllers\AssignmentSubmit\AssignmentSubmitController;
use App\Http\Controllers\Badge\BadgeController;
use App\Http\Controllers\Certificate\CertificateController;
use App\Http\Controllers\CertificateTemplate\CertificateTemplateController;
use App\Http\Controllers\Challenge\ChallengeController;
use App\Http\Controllers\EnrollmentOption\EnrollmentOptionController;
use App\Http\Controllers\InteractiveContent\InteractiveContentController;
use App\Http\Controllers\Plagiarism\PlagiarismController;
use App\Http\Controllers\Prerequisite\PrerequisiteController;
use App\Http\Controllers\ProjectSubmit\ProjectSubmitController;
use App\Http\Controllers\QuestionBank\QuestionBankController;
use App\Http\Controllers\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionController;
use App\Http\Controllers\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionController;
use App\Http\Controllers\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionController;
use App\Http\Controllers\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionController;
use App\Http\Controllers\ReusableContent\ReusableContentController;
use App\Http\Controllers\Rubric\RubricController;
use App\Http\Controllers\Rule\RuleController;
use App\Http\Controllers\TimeLimit\TimeLimitController;
use App\Http\Controllers\Whiteboard\WhiteboardController;
use App\Http\Controllers\Wiki\WikiController;
use Illuminate\Support\Facades\Storage;use Illuminate\Http\Request;

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
Route::middleware(['auth:api', 'throttle:ip-limit'])->group(function () {
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
    Route::get('grade-book/{course}', [CourseController::class, 'gradeBook']);
    Route::get('calendar/{course}', [CourseController::class, 'calendar']);
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
    Route::get('get-profile', [UserProfileController::class, 'profile']);
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
    Route::get('get-user', [UserController::class, 'user']);
    Route::post('add-student-to-course', [UserController::class, 'addStudentToCourse']);
    Route::post('remove-student-from-course', [UserController::class, 'removeStudentFromCourse']);
    Route::get('instructor-file-names', [UserController::class, 'instructorFileNames']);
    Route::get('student-file-names', [UserController::class, 'studentFileNames']);
    Route::post('remove-student-from-instructor-list', [UserController::class, 'removeStudentFromInstructorList']);
    Route::apiResource('admin-user', AdminController::class);
    Route::apiResource('project', ProjectController::class);
    Route::get('view-project-file/{project}/{fileName}', [ProjectController::class, 'view']);
    Route::get('download-project-file/{project}', [ProjectController::class, 'download']);
    Route::post('upload-project-file/{project}', [ProjectController::class, 'upload']);
    Route::delete('delete-project-file/{project}/{fileName}', [ProjectController::class, 'destroyAttachment']);
    Route::post('submit-project', [ProjectController::class, 'submit']);
    Route::apiResource('project-submit', ProjectSubmitController::class)->except(['store']);
    Route::get('view-project-submit-file/{projectSubmit}/{fileName}', [ProjectSubmitController::class, 'view']);
    Route::get('download-project-submit-file/{projectSubmit}', [ProjectSubmitController::class, 'download']);
    Route::apiResource('support-ticket', SupportTicketController::class);
    Route::apiResource('community-access', CommunityAccessController::class);
    Route::apiResource('assessment', AssessmentController::class);
    Route::post('submit-assessment', [AssessmentController::class, 'submit']);
    Route::get('start-timer/{assessment}', [AssessmentController::class, 'startTimer']);
    Route::get('pause-timer/{assessment}', [AssessmentController::class, 'pauseTimer']);
    Route::get('resume-timer/{assessment}', [AssessmentController::class, 'resumeTimer']);
    Route::get('submit-timer/{assessment}', [AssessmentController::class, 'submitTimer']);
    Route::get('assessment-fill-in-blank-question', [AssessmentFillInBlankQuestionController::class, 'index']);
    Route::get('assessment-fill-in-blank-question/{question}', [AssessmentFillInBlankQuestionController::class, 'show']);
    Route::post('assessment-fill-in-blank-question', [AssessmentFillInBlankQuestionController::class, 'store']);
    Route::put('assessment-fill-in-blank-question/{question}', [AssessmentFillInBlankQuestionController::class, 'update']);
    Route::delete('assessment-fill-in-blank-question/{question}', [AssessmentFillInBlankQuestionController::class, 'destroy']);
    Route::get('add-assessment-fill-in-blank-question-to-question-bank/{question}', [AssessmentFillInBlankQuestionController::class, 'addAssessmentFillInBlankQuestionToQuestionBank']);
    Route::get('assessment-multiple-type-question', [AssessmentMultipleTypeQuestionController::class, 'index']);
    Route::get('assessment-multiple-type-question/{question}', [AssessmentMultipleTypeQuestionController::class, 'show']);
    Route::post('assessment-multiple-type-question', [AssessmentMultipleTypeQuestionController::class, 'store']);
    Route::put('assessment-multiple-type-question/{question}', [AssessmentMultipleTypeQuestionController::class, 'update']);
    Route::delete('assessment-multiple-type-question/{question}', [AssessmentMultipleTypeQuestionController::class, 'destroy']);
    Route::get('add-assessment-multiple-type-question-to-question-bank/{question}', [AssessmentMultipleTypeQuestionController::class, 'addAssessmentMultipleTypeQuestionToQuestionBank']);
    Route::get('assessment-short-answer-question', [AssessmentShortAnswerQuestionController::class, 'index']);
    Route::get('assessment-short-answer-question/{question}', [AssessmentShortAnswerQuestionController::class, 'show']);
    Route::post('assessment-short-answer-question', [AssessmentShortAnswerQuestionController::class, 'store']);
    Route::put('assessment-short-answer-question/{question}', [AssessmentShortAnswerQuestionController::class, 'update']);
    Route::delete('assessment-short-answer-question/{question}', [AssessmentShortAnswerQuestionController::class, 'destroy']);
    Route::get('add-assessment-short-answer-question-to-question-bank/{question}', [AssessmentShortAnswerQuestionController::class, 'addAssessmentShortAnswerQuestionToQuestionBank']);
    Route::get('assessment-true-or-false-question', [AssessmentTrueOrFalseQuestionController::class, 'index']);
    Route::get('assessment-true-or-false-question/{question}', [AssessmentTrueOrFalseQuestionController::class, 'show']);
    Route::post('assessment-true-or-false-question', [AssessmentTrueOrFalseQuestionController::class, 'store']);
    Route::put('assessment-true-or-false-question/{question}', [AssessmentTrueOrFalseQuestionController::class, 'update']);
    Route::delete('assessment-true-or-false-question/{question}', [AssessmentTrueOrFalseQuestionController::class, 'destroy']);
    Route::get('add-assessment-true-or-false-question-to-question-bank/{question}', [AssessmentTrueOrFalseQuestionController::class, 'addAssessmentTrueOrFalseQuestionToQuestionBank']);
    Route::apiResource('assessment-submit', AssessmentSubmitController::class)->except(['store']);
    Route::apiResource('assignment', AssignmentController::class);
    Route::get('view-assignment-file/{assignment}/{fileName}', [AssignmentController::class, 'view']);
    Route::get('download-assignment-file/{assignment}', [AssignmentController::class, 'download']);
    Route::post('upload-assignment-file/{assignment}', [AssignmentController::class, 'upload']);
    Route::delete('delete-assignment-file/{assignment}/{fileName}', [AssignmentController::class, 'destroyAttachment']);
    Route::post('submit-assignment', [AssignmentController::class, 'submit']);
    Route::apiResource('assignment-submit', AssignmentSubmitController::class)->except(['store']);
    Route::get('view-assignment-submit-file/{assignmentSubmit}/{fileName}', [AssignmentSubmitController::class, 'view']);
    Route::get('download-assignment-submit-file/{assignmentSubmit}', [AssignmentSubmitController::class, 'download']);
    Route::apiResource('badge', BadgeController::class);
    Route::apiResource('challenge', ChallengeController::class);
    Route::get('join-challenge/{challenge}', [ChallengeController::class, 'join']);
    Route::get('leave-challenge/{challenge}', [ChallengeController::class, 'leave']);
    Route::apiResource('question-bank', QuestionBankController::class)->except(['update']);
    Route::get('question-bank-fill-in-blank-question', [QuestionBankFillInBlankQuestionController::class, 'index']);
    Route::get('question-bank-fill-in-blank-question/{question}', [QuestionBankFillInBlankQuestionController::class, 'show']);
    Route::post('question-bank-fill-in-blank-question', [QuestionBankFillInBlankQuestionController::class, 'store']);
    Route::put('question-bank-fill-in-blank-question/{question}', [QuestionBankFillInBlankQuestionController::class, 'update']);
    Route::delete('question-bank-fill-in-blank-question/{question}', [QuestionBankFillInBlankQuestionController::class, 'destroy']);
    Route::post('add-question-bank-fill-in-blank-question-to-assessment/{question}', [QuestionBankFillInBlankQuestionController::class, 'addQuestionBankFillInBlankQuestionToAssessment']);
    Route::post('remove-question-bank-fill-in-blank-question-from-assessment/{question}', [QuestionBankFillInBlankQuestionController::class, 'removeQuestionBankFillInBlankQuestionFromAssessment']);
    Route::get('question-bank-multiple-type-question', [QuestionBankMultipleTypeQuestionController::class, 'index']);
    Route::get('question-bank-multiple-type-question/{question}', [QuestionBankMultipleTypeQuestionController::class, 'show']);
    Route::post('question-bank-multiple-type-question', [QuestionBankMultipleTypeQuestionController::class, 'store']);
    Route::put('question-bank-multiple-type-question/{question}', [QuestionBankMultipleTypeQuestionController::class, 'update']);
    Route::delete('question-bank-multiple-type-question/{question}', [QuestionBankMultipleTypeQuestionController::class, 'destroy']);
    Route::post('add-question-bank-multiple-type-question-to-assessment/{question}', [QuestionBankMultipleTypeQuestionController::class, 'addQuestionBankMultipleTypeQuestionToAssessment']);
    Route::post('remove-question-bank-multiple-type-question-from-assessment/{question}', [QuestionBankMultipleTypeQuestionController::class, 'removeQuestionBankMultipleTypeQuestionFromAssessment']);
    Route::get('question-bank-short-answer-question', [QuestionBankShortAnswerQuestionController::class, 'index']);
    Route::get('question-bank-short-answer-question/{question}', [QuestionBankShortAnswerQuestionController::class, 'show']);
    Route::post('question-bank-short-answer-question', [QuestionBankShortAnswerQuestionController::class, 'store']);
    Route::put('question-bank-short-answer-question/{question}', [QuestionBankShortAnswerQuestionController::class, 'update']);
    Route::delete('question-bank-short-answer-question/{question}', [QuestionBankShortAnswerQuestionController::class, 'destroy']);
    Route::post('add-question-bank-short-answer-question-to-assessment/{question}', [QuestionBankShortAnswerQuestionController::class, 'addQuestionBankShortAnswerQuestionToAssessment']);
    Route::post('remove-question-bank-short-answer-question-from-assessment/{question}', [QuestionBankShortAnswerQuestionController::class, 'removeQuestionBankShortAnswerQuestionFromAssessment']);
    Route::get('question-bank-true-or-false-question', [QuestionBankTrueOrFalseQuestionController::class, 'index']);
    Route::get('question-bank-true-or-false-question/{question}', [QuestionBankTrueOrFalseQuestionController::class, 'show']);
    Route::post('question-bank-true-or-false-question', [QuestionBankTrueOrFalseQuestionController::class, 'store']);
    Route::put('question-bank-true-or-false-question/{question}', [QuestionBankTrueOrFalseQuestionController::class, 'update']);
    Route::delete('question-bank-true-or-false-question/{question}', [QuestionBankTrueOrFalseQuestionController::class, 'destroy']);
    Route::post('add-question-bank-true-or-false-question-to-assessment/{question}', [QuestionBankTrueOrFalseQuestionController::class, 'addQuestionBankTrueOrFalseQuestionToAssessment']);
    Route::post('remove-question-bank-true-or-false-question-from-assessment/{question}', [QuestionBankTrueOrFalseQuestionController::class, 'removeQuestionBankTrueOrFalseQuestionFromAssessment']);
    Route::apiResource('certificate', CertificateController::class);
    Route::apiResource('certificate-template', CertificateTemplateController::class);
    Route::apiResource('enrollment-option', EnrollmentOptionController::class);
    Route::apiResource('plagiarism', PlagiarismController::class)->except(['store']);
    Route::apiResource('prerequisite', PrerequisiteController::class);
    Route::apiResource('rubric', RubricController::class);
    Route::apiResource('whiteboard', WhiteboardController::class);
    Route::apiResource('wiki', WikiController::class);
    Route::get('view-wiki-file/{wiki}/{fileName}', [WikiController::class, 'view']);
    Route::get('download-wiki-file/{wiki}', [WikiController::class, 'download']);
    Route::post('upload-wiki-file/{wiki}', [WikiController::class, 'upload']);
    Route::delete('delete-wiki-file/{wiki}/{fileName}', [WikiController::class, 'destroyAttachment']);
    Route::apiResource('interactive-content', InteractiveContentController::class);
    Route::get('view-interactive-content-file/{interactiveContent}/{fileName}', [InteractiveContentController::class, 'view']);
    Route::get('download-interactive-content-file/{interactiveContent}', [InteractiveContentController::class, 'download']);
    Route::post('upload-interactive-content-file/{interactiveContent}', [InteractiveContentController::class, 'upload']);
    Route::delete('delete-interactive-content-file/{interactiveContent}/{fileName}', [InteractiveContentController::class, 'destroyAttachment']);
    Route::apiResource('reusable-content', ReusableContentController::class);
    Route::get('view-reusable-content-file/{reusableContent}/{fileName}', [ReusableContentController::class, 'view']);
    Route::get('download-reusable-content-file/{reusableContent}', [ReusableContentController::class, 'download']);
    Route::post('upload-reusable-content-file/{reusableContent}', [ReusableContentController::class, 'upload']);
    Route::delete('delete-reusable-content-file/{reusableContent}/{fileName}', [ReusableContentController::class, 'destroyAttachment']);
    Route::apiResource('rule', RuleController::class);
    Route::apiResource('time-limit', TimeLimitController::class);
});
Route::middleware(['throttle:ip-limit'])->group(function () {
    Route::get('guest-courses', [CourseController::class, 'guestIndex']);
});

// Route::post('course/{course}', [CourseController::class, 'update']);
// Route::post('group/{group}', [GroupController::class, 'update']);
// Route::post('section/{section}', [SectionController::class, 'update']);
// Route::post('learning_activity/{learningActivity}', [LearningActivityController::class, 'update']);

Route::middleware(['throttle:ip-limit'])->group(function () {
    Route::get('testcourse', [CourseController::class, 'index']);
});


Route::post('/private-image', function (Request $request) {
    $file = $request->file('file');
    $path = Storage::disk('supabase')->putFile('Section/' . 1 . '/Files', $file);
    return response()->json(['path' => $path]);
});
