<?php

namespace App\Http\Requests\CommunityAccess;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\CommunityAccess\CommunityAccessAccessCommunity;
use App\Enums\CommunityAccess\CommunityAccessAccessCourseDiscussions;
use App\Enums\CommunityAccess\CommunityAccessCourseDiscussionsLevel;
use App\Enums\CommunityAccess\CommunityAccessInboxCommunication;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class CommunityAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'community' => ['required', 'array'],
            'community.community_enabled' => ['required', 'boolean'],
            'community.access_community' => ['required_if:community.community_enabled,==,true', new Enum(CommunityAccessAccessCommunity::class)],
            'course' => ['required', 'array'],
            'course.course_discussions_enabled' => ['required', 'boolean'],
            'course.permissions' => ['required_if:course.course_discussions_enabled,==,true', 'array'],
            'course.permissions.post_enabled' => ['required_with:course.permissions', 'boolean'],
            'course.permissions.poll_enabled' => ['required_with:course.permissions', 'boolean'],
            'course.permissions.comment_enabled' => ['required_with:course.permissions', 'boolean'],
            'course.reactions' => ['required_if:course.course_discussions_enabled,==,true', 'array'],
            'course.reactions.upvote_enabled' => ['required_with:course.reactions', 'boolean'],
            'course.reactions.like_enabled' => ['required_with:course.reactions', 'boolean'],
            'course.reactions.share_enabled' => ['required_with:course.reactions', 'boolean'],
            'course.attachments' => ['required_if:course.course_discussions_enabled,==,true', 'array'],
            'course.attachments.images_enabled' => ['required_with:course.attachments', 'boolean'],
            'course.attachments.videos_enabled' => ['required_with:course.attachments', 'boolean'],
            'course.attachments.files_enabled' => ['required_with:course.attachments', 'boolean'],
            'course.access_course_discussions' => ['required_if:course.course_discussions_enabled,==,true', new Enum(CommunityAccessAccessCourseDiscussions::class)],
            'course.course_discussions_level' => ['required_if:course.course_discussions_enabled,==,true', new Enum(CommunityAccessCourseDiscussionsLevel::class)],
            'course.inbox_communication' => ['required_if:course.course_discussions_enabled,==,true', new Enum(CommunityAccessInboxCommunication::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'community' => ['sometimes', 'array'],
            'community.community_enabled' => ['required_with:community', 'boolean'],
            'community.access_community' => ['required_if:community.community_enabled,==,true', new Enum(CommunityAccessAccessCommunity::class)],
            'course' => ['sometimes', 'array'],
            'course.course_discussions_enabled' => ['required_with:course', 'boolean'],
            'course.permissions' => ['required_if:course.course_discussions_enabled,==,true', 'array'],
            'course.permissions.post_enabled' => ['required_with:course.permissions', 'boolean'],
            'course.permissions.poll_enabled' => ['required_with:course.permissions', 'boolean'],
            'course.permissions.comment_enabled' => ['required_with:course.permissions', 'boolean'],
            'course.reactions' => ['required_if:course.course_discussions_enabled,==,true', 'array'],
            'course.reactions.upvote_enabled' => ['required_with:course.reactions', 'boolean'],
            'course.reactions.like_enabled' => ['required_with:course.reactions', 'boolean'],
            'course.reactions.share_enabled' => ['required_with:course.reactions', 'boolean'],
            'course.attachments' => ['required_if:course.course_discussions_enabled,==,true', 'array'],
            'course.attachments.images_enabled' => ['required_with:course.attachments', 'boolean'],
            'course.attachments.videos_enabled' => ['required_with:course.attachments', 'boolean'],
            'course.attachments.files_enabled' => ['required_with:course.attachments', 'boolean'],
            'course.access_course_discussions' => ['required_if:course.course_discussions_enabled,==,true', new Enum(CommunityAccessAccessCourseDiscussions::class)],
            'course.course_discussions_level' => ['required_if:course.course_discussions_enabled,==,true', new Enum(CommunityAccessCourseDiscussionsLevel::class)],
            'course.inbox_communication' => ['required_if:course.course_discussions_enabled,==,true', new Enum(CommunityAccessInboxCommunication::class)],
        ];
    }

    public function rules(): array
    {
        if (request()->isMethod('get'))
        {
            return $this->onIndex();
        }
        else if (request()->isMethod('post'))
        {
            return $this->onStore();
        }
        else
        {
            return $this->onUpdate();
        }
    }

    // public function messages(): array
    // {
    //     return [
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'community.required' => ValidationType::Required->getMessage(),
    //         'community.array' => ValidationType::Array->getMessage(),
    //         'community.community_enabled.required' => ValidationType::Required->getMessage(),
    //         'community.community_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'community.community_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'community.access_community.required' => ValidationType::Required->getMessage(),
    //         'community.access_community.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'course.required' => ValidationType::Required->getMessage(),
    //         'course.array' => ValidationType::Array->getMessage(),
    //         'course.course_discussions_enabled.required' => ValidationType::Required->getMessage(),
    //         'course.course_discussions_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.course_discussions_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.permissions.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'course.permissions.array' => ValidationType::Array->getMessage(),
    //         'course.permissions.post_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.permissions.post_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.permissions.poll_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.permissions.poll_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.permissions.comment_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.permissions.comment_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.reactions.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'course.reactions.array' => ValidationType::Array->getMessage(),
    //         'course.reactions.upvote_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.reactions.upvote_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.reactions.like_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.reactions.like_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.reactions.share_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.reactions.share_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.attachments.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'course.attachments.array' => ValidationType::Array->getMessage(),
    //         'course.attachments.images_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.attachments.images_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.attachments.videos_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.attachments.videos_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.attachments.files_enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'course.attachments.files_enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'course.access_course_discussions.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'course.access_course_discussions.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'course.course_discussions_level.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'course.course_discussions_level.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'course.inbox_communication.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'course.inbox_communication.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'community' => FieldName::Community->getMessage(),
    //         'community.community_enabled' => FieldName::CommunityEnabled->getMessage(),
    //         'community.access_community' => FieldName::AccessCommunity->getMessage(),
    //         'course' => FieldName::Course->getMessage(),
    //         'course.course_discussions_enabled' => FieldName::CourseDiscussionsEnabled->getMessage(),
    //         'course.permissions' => FieldName::Permissions->getMessage(),
    //         'course.permissions.post_enabled' => FieldName::PostEnabled->getMessage(),
    //         'course.permissions.poll_enabled' => FieldName::PollEnabled->getMessage(),
    //         'course.permissions.comment_enabled' => FieldName::CommentEnabled->getMessage(),
    //         'course.reactions' => FieldName::Reactions->getMessage(),
    //         'course.reactions.upvote_enabled' => FieldName::UpvoteEnabled->getMessage(),
    //         'course.reactions.like_enabled' => FieldName::LikeEnabled->getMessage(),
    //         'course.reactions.share_enabled' => FieldName::ShareEnabled->getMessage(),
    //         'course.attachments' => FieldName::Attachments->getMessage(),
    //         'course.attachments.images_enabled' => FieldName::ImagesEnabled->getMessage(),
    //         'course.attachments.videos_enabled' => FieldName::VideosEnabled->getMessage(),
    //         'course.attachments.files_enabled' => FieldName::FilesEnabled->getMessage(),
    //         'course.access_course_discussions' => FieldName::AccessCourseDiscussions->getMessage(),
    //         'course.course_discussions_level' => FieldName::CourseDiscussionsLevel->getMessage(),
    //         'course.inbox_communication' => FieldName::InboxCommunication->getMessage(),
    //     ];
    // }
}
