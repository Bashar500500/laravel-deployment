<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Event\EventType;
use App\Enums\Event\EventCategory;
use App\Enums\Event\EventRecurrence;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'recurrence' => ['sometimes', new Enum(EventRecurrence::class)],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'name' => ['required', 'string'],
            'groups' => ['sometimes', 'array'],
            'groups.*' => ['required_with:groups', 'exists:groups,id'],
            'type' => ['required', new Enum(EventType::class)],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:h:i A'],
            'end_time' => ['required', 'date_format:h:i A', 'after:start_time'],
            'category' => ['required', new Enum(EventCategory::class)],
            'recurrence' => ['required', new Enum(EventRecurrence::class)],
            'description' => ['required', 'string'],
            'attachments' => ['required', 'array'],
            'attachments.files' => ['sometimes', 'array'],
            'attachments.files.*' => ['required_with:attachments.files', 'file'],
            'attachments.links' => ['sometimes', 'array'],
            'attachments.links.*' => ['required_with:attachments.links', 'url'],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'groups' => ['sometimes', 'array'],
            'groups.*' => ['required_with:groups', 'exists:groups,id'],
            'type' => ['sometimes', new Enum(EventType::class)],
            'date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'start_time' => ['sometimes', 'date_format:h:i A'],
            'end_time' => ['required_with:start_time', 'date_format:h:i A', 'after:start_time'],
            'category' => ['sometimes', new Enum(EventCategory::class)],
            'recurrence' => ['sometimes', new Enum(EventRecurrence::class)],
            'description' => ['sometimes', 'string'],
            'attachments' => ['sometimes', 'array'],
            'attachments.files' => ['sometimes', 'array'],
            'attachments.files.*' => ['required_with:attachments.files', 'file'],
            'attachments.links' => ['sometimes', 'array'],
            'attachments.links.*' => ['required_with:attachments.links', 'url'],
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
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'date.required' => ValidationType::Required->getMessage(),
    //         'date.date' => ValidationType::Date->getMessage(),
    //         'date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'start_time.required' => ValidationType::Required->getMessage(),
    //         'start_time.date_format' => ValidationType::TimeFormat->getMessage(),
    //         'end_time.required' => ValidationType::Required->getMessage(),
    //         'end_time.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'end_time.date_format' => ValidationType::TimeFormat->getMessage(),
    //         'end_time.after' => ValidationType::After->getMessage(),
    //         'category.required' => ValidationType::Required->getMessage(),
    //         'category.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'recurrence.required' => ValidationType::Required->getMessage(),
    //         'recurrence.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'attachments.required' => ValidationType::Required->getMessage(),
    //         'attachments.array' => ValidationType::Array->getMessage(),
    //         'attachments.files.array' => ValidationType::Array->getMessage(),
    //         'attachments.files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'attachments.files.*.file' => ValidationType::File->getMessage(),
    //         'attachments.links.array' => ValidationType::Array->getMessage(),
    //         'attachments.links.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'attachments.links.*.url' => ValidationType::Url->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'date' => FieldName::Date->getMessage(),
    //         'start_time' => FieldName::StartTime->getMessage(),
    //         'end_time' => FieldName::EndTime->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'recurrence' => FieldName::Recurrence->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'attachments' => FieldName::Attachments->getMessage(),
    //         'attachments.files' => FieldName::Files->getMessage(),
    //         'attachments.files.*' => FieldName::Files->getMessage(),
    //         'attachments.links' => FieldName::Links->getMessage(),
    //         'attachments.links.*' => FieldName::Links->getMessage(),
    //     ];
    // }
}
