<?php

namespace App\Enums\Exception;

enum ForbiddenExceptionMessage: string
{
    case Chat = 'chat';
    case Notification = 'notification';
    case GroupJoinTwice = 'group_join_twice';
    case GroupCapacityMax = 'group_capacity_max';
    case GroupNotJoined = 'group_not_joined';
    case LearningActivity = 'learning_activity';
    case User = 'user';
    case Attendance = 'attendance';
    case PasswordResetCode = 'password_reset_code';
    case ProjectLeaderNotInCourse = 'project_leader_not_in_course';
    case ProjectGroupNotInCourse = 'project_group_not_in_course';

    public function getDescription(): string
    {
        $key = "Exception/forbiddens.{$this->value}.description";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }
}
