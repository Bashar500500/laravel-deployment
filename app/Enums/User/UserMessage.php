<?php

namespace App\Enums\User;

enum UserMessage: string
{
    case StudentAddedToCourse = 'student_added_to_course';
    case StudentCreatedAccountAndAddedToCourse = 'student_created_account_and_added_to_course';
}
