<?php

namespace App\Http\Requests\ForumPost;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ForumPost\ForumPostPostType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ForumPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'parent_post_id' => ['sometimes', 'exists:forum_posts,id'],
            'post_type' => ['required', new Enum(ForumPostPostType::class)],
            'content' => ['required', 'string'],
            'likes_count' => ['required', 'integer'],
            'replies_count' => ['required', 'integer'],
            'is_helpful' => ['required', 'boolean'],
        ];
    }

    protected function onUpdate() {
        return [
            'parent_post_id' => ['sometimes', 'exists:forum_posts,id'],
            'post_type' => ['sometimes', new Enum(ForumPostPostType::class)],
            'content' => ['sometimes', 'string'],
            'likes_count' => ['sometimes', 'integer'],
            'replies_count' => ['sometimes', 'integer'],
            'is_helpful' => ['sometimes', 'boolean'],
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
    //         'title.required' => ValidationType::Required->getMessage(),
    //         'title.string' => ValidationType::String->getMessage(),
    //         'date.required' => ValidationType::Required->getMessage(),
    //         'date.date' => ValidationType::Date->getMessage(),
    //         'date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'day.required' => ValidationType::Required->getMessage(),
    //         'day.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'date' => FieldName::Date->getMessage(),
    //         'day' => FieldName::Day->getMessage(),
    //     ];
    // }
}
