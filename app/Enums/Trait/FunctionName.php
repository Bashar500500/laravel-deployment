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
    case AddAssessmentFillInBlankQuestionToQuestionBank = 'add_assessment_fill_in_blank_question_to_question_bank';
    case AddAssessmentMultipleTypeQuestionToQuestionBank = 'add_assessment_multiple_type_question_to_question_bank';
    case AddAssessmentShortAnswerQuestionToQuestionBank = 'add_assessment_short_answer_question_to_question_bank';
    case AddAssessmentTrueOrFalseQuestionToQuestionBank = 'add_assessment_true_or_false_question_to_question_bank';
    case AddQuestionBankFillInBlankQuestionToAssessment = 'add_question_bank_fill_in_blank_question_to_assessment';
    case RemoveQuestionBankFillInBlankQuestionFromAssessment = 'remove_question_bank_fill_in_blank_question_from_assessment';
    case AddQuestionBankMultipleTypeQuestionToAssessment = 'add_question_bank_multiple_type_question_to_assessment';
    case RemoveQuestionBankMultipleTypeQuestionFromAssessment = 'remove_question_bank_multiple_type_question_from_assessment';
    case AddQuestionBankShortAnswerQuestionToAssessment = 'add_question_bank_short_answer_question_to_assessment';
    case RemoveQuestionBankShortAnswerQuestionFromAssessment = 'remove_question_bank_short_answer_question_from_assessment';
    case AddQuestionBankTrueOrFalseQuestionToAssessment = 'add_question_bank_true_or_false_question_to_assessment';
    case RemoveQuestionBankTrueOrFalseQuestionFromAssessment = 'remove_question_bank_true_or_false_question_from_assessment';
    case Submit = 'submit';
    case StartTimer = 'start_timer';
    case PauseTimer = 'pause_timer';
    case ResumeTimer = 'resume_timer';
    case SubmitTimer = 'submit_timer';
    case TimerStatus = 'timer_status';
    case Analytics = 'analytics';
    case InstructorFileNames = 'instructor_file_names';
    case RemoveStudentFromInstructorList = 'remove_student_from_instructor_list';
    case GradeBook = 'grade_book';
    case Calendar = 'calendar';

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
