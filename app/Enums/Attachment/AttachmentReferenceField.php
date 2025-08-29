<?php

namespace App\Enums\Attachment;

enum AttachmentReferenceField: string
{
    case CourseCoverImage = 'course_cover_image';
    case GroupImageUrl = 'group_image';
    case LearningActivityPdfContentFile = 'learning_activity_pdf_content_file';
    case LearningActivityVideoContentFile = 'learning_activity_video_content_file';
    case SectionResourcesFile = 'section_resources_file';
    case SectionResourcesLink = 'section_resources_link';
    case CoverImage = 'cover_image';
    case SubCategoryImage = 'sub_category_image';
    case CategoryImage = 'category_image';
    case EventAttachmentsFile = 'event_attachments_file';
    case EventAttachmentsLink = 'event_attachments_link';
    case UserImage = 'user_image';
    case ProjectFiles = 'project_files';
    case AssignmentSubmitStudentFiles = 'assignment_submit_student_files';
    case AssignmentSubmitInstructorFiles = 'assignment_submit_instructor_files';
    case AssignmentFiles = 'assignment_files';
    case ProjectSubmitStudentFiles = 'project_submit_student_files';
    case ProjectSubmitInstructorFiles = 'project_submit_instructor_files';
    case WikiFiles = 'wiki_files';
    case InteractiveContentVideoFile = 'interactive_content_video_file';
    case InteractiveContentPresentationFile = 'interactive_content_presentation_file';
    case InteractiveContentQuizFile = 'interactive_content_quiz_file';
    case ReusableContentVideoFile = 'reusable_content_video_file';
    case ReusableContentPresentationFile = 'reusable_content_presentation_file';
    case ReusableContentPdfFile = 'reusable_content_pdf_file';
    case ReusableContentQuizFile = 'reusable_content_quiz_file';
    case LearningActivityAudioContentFile = 'learning_activity_audio_content_file';
    case LearningActivityWordContentFile = 'learning_activity_word_content_file';
    case LearningActivityPowerPointContentFile = 'learning_activity_power_point_content_file';
    case LearningActivityZipContentFile = 'learning_activity_zip_content_file';
}
