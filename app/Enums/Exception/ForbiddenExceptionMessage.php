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
    case AssessmentFillInBlankQuestion = 'assessment_fill_in_blank_question';
    case AssessmentMultipleTypeQuestion = 'assessment_multiple_type_question';
    case AssessmentShortAnswerQuestion = 'assessment_short_answer_question';
    case AssessmentTrueOrFalseQuestion = 'assessment_true_or_false_question';
    case QuestionBankFillInBlankQuestion = 'question_bank_fill_in_blank_question';
    case QuestionBankMultipleTypeQuestion = 'question_bank_multiple_type_question';
    case QuestionBankShortAnswerQuestion = 'question_bank_short_answer_question';
    case QuestionBankTrueOrFalseQuestion = 'question_bank_true_or_false_question';
    case AssessmentAttemptsAllowed = 'assessment_attempts_allowed';
    case AssignmentNoSubmission = 'assignment_no_submission';
    case AssignmentSubmissionTypeConflict = 'assignment_submission_type_conflict';
    case AssignmentSubmissionFileSize = 'assignment_submission_file_size';
    case AssignmentSubmissionFileConflict = 'assignment_submission_file_conflict';
    case AssignmentNoLateSubmission = 'assignment_no_late_submission';
    case AssignmentSubmissionCutoffDate = 'assignment_submission_cutoff_date';
    case TimerAlreadyStarted = 'timer_already_started';
    case AssessmentNotAvailable = 'assessment_not_available';
    case ChallengeUserExsits = 'challenge_user_exsits';
    case ChallengeHasMaxParticipants = 'challenge_has_max_participants';
    case RubricCriteriaWeightNotEqual = 'rubric_criteria_weight_not_equal';
    case ProjectStartOrEndDate = 'project_start_or_end_date';
    case ProjectMaxSubmits = 'project_max_submits';
    case CertificateTypeCondition = 'certificate_type_condition';
    case InteractiveContent = 'interactive_content';
    case ReusableContent = 'reusable_content';
    case AssignmentAllowedReviews = 'assignment_allowed_reviews';
    case AssignmentCorrectedByStudent = 'assignment_corrected_by_student';
    case AssessmentFinished = 'assessment_finished';
    case ProjectFinished = 'project_finished';
    case AssignmentPeerReviewed = 'assignment_peer_reviewed';
    case AssignmentNotPeerReviewed = 'assignment_not_peer_reviewed';
    case AssignmentRubric = 'assignment_rubric';
    case ProjectRubric = 'project_rubric';
    case InstructorAddStudentToCourse = 'instructor_add_student_to_course';
    case CourseCodeNotCorrect = 'course_code_not_correct';

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
