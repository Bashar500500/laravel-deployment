<?php
namespace App\DataTransferObjects\User;

use App\Http\Requests\User\AddUserToCourseRequest;
use App\Http\Requests\User\RemoveUserFromCourseRequest;
use App\Http\Requests\User\RemoveStudentFromInstructorListRequest;

class UserCourseDto
{
    public function __construct(
        public readonly ?string $email,
        public readonly ?string $code,
        public readonly ?int $courseId,
        public readonly ?string $studentCode,
        public readonly ?string $studentId,
    ) {}

    public static function fromAddStudentToCourseRequest(AddUserToCourseRequest $request): UserCourseDto
    {
        return new self(
            email: $request->validated('email'),
            code: $request->validated('code'),
            courseId: $request->validated('course_id'),
            studentCode: $request->validated('student_code'),
            studentId: null,
        );
    }

    public static function fromRemoveStudentFromCourseRequest(RemoveUserFromCourseRequest $request): UserCourseDto
    {
        return new self(
            email: null,
            code: null,
            courseId: null,
            studentCode: $request->validated('student_code'),
            studentId: null,
        );
    }

    public static function fromRemoveStudentFromInstructorListRequest(RemoveStudentFromInstructorListRequest $request): UserCourseDto
    {
        return new self(
            email: null,
            code: null,
            courseId: null,
            studentCode: $request->validated('student_code'),
            studentId: $request->validated('student_id'),
        );
    }
}
