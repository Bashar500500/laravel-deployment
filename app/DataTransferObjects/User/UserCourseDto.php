<?php
namespace App\DataTransferObjects\User;

use App\Http\Requests\User\AddUserToCourseRequest;
use App\Http\Requests\User\RemoveUserFromCourseRequest;

class UserCourseDto
{
    public function __construct(
        public readonly ?string $email,
        public readonly ?int $courseId,
        public readonly ?string $studentCode,
    ) {}

    public static function fromAddStudentToCourseRequest(AddUserToCourseRequest $request): UserCourseDto
    {
        return new self(
            email: $request->validated('email'),
            courseId: $request->validated('course_id'),
            studentCode: $request->validated('student_code'),
        );
    }

    public static function fromRemoveStudentFromCourseRequest(RemoveUserFromCourseRequest $request): UserCourseDto
    {
        return new self(
            email: null,
            courseId: null,
            studentCode: $request->validated('student_code'),
        );
    }
}
