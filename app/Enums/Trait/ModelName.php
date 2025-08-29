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
    case Presentation = 'presentation';
    case Quiz = 'quiz';
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
    case SupportTicket = 'support_ticket';
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
    case Analytics = 'analytics';
    case Instructor = 'instructor';
    case Rubric = 'rubric';
    case RubricCriteria = 'rubric_criteria';
    case Plagiarism = 'plagiarism';
    case ProjectSubmit = 'project_submit';
    case Wiki = 'wiki';
    case WikiComment = 'wiki_comment';
    case WikiRating = 'wiki_rating';
    case Prerequisite = 'prerequisite';
    case Certificate = 'certificate';
    case CertificateTemplate = 'certificate_template';
    case EnrollmentOption = 'enrollment_option';
    case Whiteboard = 'whiteboard';
    case InteractiveContent = 'interactive_content';
    case ReusableContent = 'reusable_content';
    case Audio = 'audio';
    case Word = 'word';
    case PowerPoint = 'power_point';
    case Zip = 'zip';
    case UserActivity = 'user_activity';
    case ForumPost = 'forum_post';
    case PageView = 'page_view';
    case UserInteraction = 'user_interaction';
    case ContentEngagement = 'content_engagement';
    case MediaEngagement = 'media_engagement';
    case LearningGap = 'learning_gap';
    case LearningRecommendation = 'learning_recommendation';
    case CourseReview = 'course_review';
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
            $value =='SupportTicket' => self::SupportTicket,
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
            $value =='Analytics' => self::Analytics,
            $value =='Instructor' => self::Instructor,
            $value =='Rubric' => self::Rubric,
            $value =='RubricCriteria' => self::RubricCriteria,
            $value =='Plagiarism' => self::Plagiarism,
            $value =='ProjectSubmit' => self::ProjectSubmit,
            $value =='Wiki' => self::Wiki,
            $value =='WikiComment' => self::WikiComment,
            $value =='WikiRating' => self::WikiRating,
            $value =='Prerequisite' => self::Prerequisite,
            $value =='Certificate' => self::Certificate,
            $value =='CertificateTemplate' => self::CertificateTemplate,
            $value =='EnrollmentOption' => self::EnrollmentOption,
            $value =='Whiteboard' => self::Whiteboard,
            $value =='InteractiveContent' => self::InteractiveContent,
            $value =='ReusableContent' => self::ReusableContent,
            $value =='Audio' => self::Audio,
            $value =='Word' => self::Word,
            $value =='PowerPoint' => self::PowerPoint,
            $value =='Zip' => self::Zip,
            $value =='UserActivity' => self::UserActivity,
            $value =='ForumPost' => self::ForumPost,
            $value =='PageView' => self::PageView,
            $value =='UserInteraction' => self::UserInteraction,
            $value =='ContentEngagement' => self::ContentEngagement,
            $value =='MediaEngagement' => self::MediaEngagement,
            $value =='LearningGap' => self::LearningGap,
            $value =='LearningRecommendation' => self::LearningRecommendation,
            $value =='CourseReview' => self::CourseReview,
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
            self::SupportTicket => 'SupportTicket',
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
            self::Analytics => 'Analytics',
            self::Instructor => 'Instructor',
            self::Rubric => 'Rubric',
            self::RubricCriteria => 'RubricCriteria',
            self::Plagiarism => 'Plagiarism',
            self::ProjectSubmit => 'ProjectSubmit',
            self::Wiki => 'Wiki',
            self::WikiComment => 'WikiComment',
            self::WikiRating => 'WikiRating',
            self::Prerequisite => 'Prerequisite',
            self::Certificate => 'Certificate',
            self::CertificateTemplate => 'CertificateTemplate',
            self::EnrollmentOption => 'EnrollmentOption',
            self::Whiteboard => 'Whiteboard',
            self::InteractiveContent => 'InteractiveContent',
            self::ReusableContent => 'ReusableContent',
            self::Audio => 'Audio',
            self::Word => 'Word',
            self::PowerPoint => 'PowerPoint',
            self::Zip => 'Zip',
            self::UserActivity => 'UserActivity',
            self::ForumPost => 'ForumPost',
            self::PageView => 'PageView',
            self::UserInteraction => 'UserInteraction',
            self::ContentEngagement => 'ContentEngagement',
            self::MediaEngagement => 'MediaEngagement',
            self::LearningGap => 'LearningGap',
            self::LearningRecommendation => 'LearningRecommendation',
            self::CourseReview => 'CourseReview',
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
