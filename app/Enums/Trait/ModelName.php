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
    case Assessment = 'assessment';
    case AssessmentFillInBlankQuestion = 'assessment_fill_in_blank_question';
    case AssessmentMultipleTypeQuestion = 'assessment_multiple_type_question';
    case AssessmentShortAnswerQuestion = 'assessment_short_answer_question';
    case AssessmentTrueOrFalseQuestion = 'assessment_true_or_false_question';
    case QuestionBank = 'question_bank';
    case QuestionBankFillInBlankQuestion = 'question_bank_fill_in_blank_question';
    case QuestionBankMultipleTypeQuestion = 'question_bank_multiple_type_question';
    case QuestionBankShortAnswerQuestion = 'question_bank_short_answer_question';
    case QuestionBankTrueOrFalseQuestion = 'question_bank_true_or_false_question';
    case TimeLimit = 'time_limit';
    case Assignment = 'assignment';
    case Challenge = 'challenge';
    case Rule = 'rule';
    case Badge = 'badge';
    case AssessmentSubmit = 'assessment_submit';
    case AssignmentSubmit = 'assignment_submit';
    case Blank = 'blank';
    case Option = 'option';
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
            $value =='Assessment' => self::Assessment,
            $value =='AssessmentFillInBlankQuestion' => self::AssessmentFillInBlankQuestion,
            $value =='AssessmentMultipleTypeQuestion' => self::AssessmentMultipleTypeQuestion,
            $value =='AssessmentShortAnswerQuestion' => self::AssessmentShortAnswerQuestion,
            $value =='AssessmentTrueOrFalseQuestion' => self::AssessmentTrueOrFalseQuestion,
            $value =='QuestionBank' => self::QuestionBank,
            $value =='QuestionBankFillInBlankQuestion' => self::QuestionBankFillInBlankQuestion,
            $value =='QuestionBankMultipleTypeQuestion' => self::QuestionBankMultipleTypeQuestion,
            $value =='QuestionBankShortAnswerQuestion' => self::QuestionBankShortAnswerQuestion,
            $value =='QuestionBankTrueOrFalseQuestion' => self::QuestionBankTrueOrFalseQuestion,
            $value =='TimeLimit' => self::TimeLimit,
            $value =='Assignment' => self::Assignment,
            $value =='Challenge' => self::Challenge,
            $value =='Rule' => self::Rule,
            $value =='Badge' => self::Badge,
            $value =='AssessmentSubmit' => self::AssessmentSubmit,
            $value =='AssignmentSubmit' => self::AssignmentSubmit,
            $value =='Blank' => self::Blank,
            $value =='Option' => self::Option,
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
            self::Assessment => 'Assessment',
            self::AssessmentFillInBlankQuestion => 'AssessmentFillInBlankQuestion',
            self::AssessmentMultipleTypeQuestion => 'AssessmentMultipleTypeQuestion',
            self::AssessmentShortAnswerQuestion => 'AssessmentShortAnswerQuestion',
            self::AssessmentTrueOrFalseQuestion => 'AssessmentTrueOrFalseQuestion',
            self::QuestionBank => 'QuestionBank',
            self::QuestionBankFillInBlankQuestion => 'QuestionBankFillInBlankQuestion',
            self::QuestionBankMultipleTypeQuestion => 'QuestionBankMultipleTypeQuestion',
            self::QuestionBankShortAnswerQuestion => 'QuestionBankShortAnswerQuestion',
            self::QuestionBankTrueOrFalseQuestion => 'QuestionBankTrueOrFalseQuestion',
            self::TimeLimit => 'TimeLimit',
            self::Assignment => 'Assignment',
            self::Challenge => 'Challenge',
            self::Rule => 'Rule',
            self::Badge => 'Badge',
            self::AssessmentSubmit => 'AssessmentSubmit',
            self::AssignmentSubmit => 'AssignmentSubmit',
            self::Blank => 'Blank',
            self::Option => 'Option',
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
