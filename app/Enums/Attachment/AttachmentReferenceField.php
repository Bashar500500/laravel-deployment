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
    case AssignmentSubmitFile = 'assignment_submit_file';
}
