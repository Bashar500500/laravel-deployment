<?php

namespace App\Enums\Trait;

enum ModelName: string
{
    case Chat = 'chat';
    case Message = 'message';
    case Reply = 'reply';
    case Notification = 'notification';
    case Route = 'route';
    case Website = 'website';
    case Course = 'course';
    case Section = 'section';
    case Group = 'group';
    case LearningActivity = 'learning activity';
    case Image = 'image';
    case Pdf = 'pdf';
    case Video = 'video';
    case File = 'file';
    case Files = 'files';
    case Chunk = 'chunk';
    case User = 'user';
    case Category = 'category';
    case SubCategory = 'sub_category';
    case PasswordReset = 'password_reset';
    case Profile = 'profile';
    case Permission = 'permission';
    case Student = 'student';
    case Holiday = 'holiday';
    case Leave = 'leave';
    case Policy = 'policy';
    case TeachingHour = 'teaching hour';
    case ScheduleTiming = 'schedule timing';
    case Event = 'event';
    case Grade = 'grade';
    case Progress = 'progress';
    case Attendance = 'attendance';
    case UserProfile = 'user_profile';
    case AdminProfile = 'admin_profile';
    case PasswordResetCode = 'password_reset_code';
    case Email = 'email';
    case Question = 'question';
    case Project = 'project';
    case Ticket = 'ticket';
    case CommunityAccess = 'community_access';
    case NoName = '';

    public static function getEnum(string $value): self
    {
        return match (true) {
            $value =='Chat' => self::Chat,
            $value =='Message' => self::Message,
            $value =='Reply' => self::Reply,
            $value =='Notification' => self::Notification,
            $value =='Route' => self::Route,
            $value =='Website' => self::Website,
            $value =='Course' => self::Course,
            $value =='Section' => self::Section,
            $value =='Group' => self::Group,
            $value =='LearningActivity' => self::LearningActivity,
            $value =='Image' => self::Image,
            $value =='Pdf' => self::Pdf,
            $value =='Video' => self::Video,
            $value =='File' => self::File,
            $value =='Files' => self::Files,
            $value =='Chunk' => self::Chunk,
            $value =='User' => self::User,
            $value =='Category' => self::Category,
            $value =='SubCategory' => self::SubCategory,
            $value =='PasswordReset' => self::PasswordReset,
            $value =='Profile' => self::Profile,
            $value =='Permission' => self::Permission,
            $value =='Student' => self::Student,
            $value =='Holiday' => self::Holiday,
            $value =='Leave' => self::Leave,
            $value =='Policy' => self::Policy,
            $value =='TeachingHour' => self::TeachingHour,
            $value =='ScheduleTiming' => self::ScheduleTiming,
            $value =='Event' => self::Event,
            $value =='Grade' => self::Grade,
            $value =='Progress' => self::Progress,
            $value =='Attendance' => self::Attendance,
            $value =='UserProfile' => self::UserProfile,
            $value =='AdminProfile' => self::AdminProfile,
            $value =='PasswordResetCode' => self::PasswordResetCode,
            $value =='Email' => self::Email,
            $value =='Question' => self::Question,
            $value =='Project' => self::Project,
            $value =='Ticket' => self::Ticket,
            $value =='CommunityAccess' => self::CommunityAccess,
        };
    }

    public function getModelName(): string
    {
        return match ($this) {
            self::Chat => 'Chat',
            self::Message => 'Message',
            self::Reply => 'Reply',
            self::Notification => 'Notification',
            self::Route => 'Route',
            self::Website => 'Website',
            self::Course => 'Course',
            self::Section => 'Section',
            self::Group => 'Group',
            self::LearningActivity => 'LearningActivity',
            self::Image => 'Image',
            self::Pdf => 'Pdf',
            self::Video => 'Video',
            self::File => 'File',
            self::Files => 'Files',
            self::Chunk => 'Chunk',
            self::User => 'User',
            self::Category => 'Category',
            self::SubCategory => 'SubCategory',
            self::PasswordReset => 'PasswordReset',
            self::Profile => 'Profile',
            self::Permission => 'Permission',
            self::Student => 'Student',
            self::Holiday => 'Holiday',
            self::Leave => 'Leave',
            self::Policy => 'Policy',
            self::TeachingHour => 'TeachingHour',
            self::ScheduleTiming => 'ScheduleTiming',
            self::Event => 'Event',
            self::Grade => 'Grade',
            self::Progress => 'Progress',
            self::Attendance => 'Attendance',
            self::UserProfile => 'UserProfile',
            self::AdminProfile => 'AdminProfile',
            self::PasswordResetCode => 'PasswordResetCode',
            self::Email => 'Email',
            self::Question => 'Question',
            self::Project => 'Project',
            self::Ticket => 'Ticket',
            self::CommunityAccess => 'CommunityAccess',
        };
    }

    public function getMessage(): string
    {
        $key = "Trait/models.{$this->value}.message";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }
}
