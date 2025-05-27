<?php

namespace App\Enums\Trait;

enum FunctionName: string
{
    case Index = 'index';
    case Show = 'show';
    case Store = 'store';
    case Update = 'update';
    case Delete = 'delete';
    case View = 'view';
    case Download = 'download';
    case Join = 'join';
    case Leave = 'leave';
    case Upload = 'upload';
    case Register = 'register';
    case Login = 'login';
    case Logout = 'logout';
    case SendResetCode = 'send_reset_code';
    case VerifyResetCode = 'verify_reset_code';
    case Assign = 'assign';
    case Revoke = 'revoke';
    case AddStudentToCourse = 'student_added_to_course';
    case RemoveStudentFromCourse = 'remove_added_from_course';

    public function getMessage(): string
    {
        $key = "Trait/functions.{$this->value}.message";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }
}
