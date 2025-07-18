<?php

namespace App\Http\Requests\TimeLimit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TimeLimit\TimeLimitStatus;
use App\Enums\TimeLimit\TimeLimitType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class TimeLimitRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['required', new Enum(TimeLimitStatus::class)],
            'duration_minutes' => ['required', 'integer', 'gt:0'],
            'type' => ['required', new Enum(TimeLimitType::class)],
            'grace_time_minutes' => ['required', 'integer', 'gt:0'],
            'extension_time_minutes' => ['required', 'integer', 'gt:0'],
            'settings' => ['required', 'array'],
            'settings.show_timer' => ['required', 'boolean'],
            'settings.auto_submit' => ['required', 'boolean'],
            'settings.allow_extensions' => ['required', 'boolean'],
            'warnings' => ['required', 'array'],
            'warnings.*.minutes_before' => ['required', 'integer', 'gt:0'],
            'warnings.*.message' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(TimeLimitStatus::class)],
            'duration_minutes' => ['sometimes', 'integer', 'gt:0'],
            'type' => ['sometimes', new Enum(TimeLimitType::class)],
            'grace_time_minutes' => ['sometimes', 'integer', 'gt:0'],
            'extension_time_minutes' => ['sometimes', 'integer', 'gt:0'],
            'settings' => ['sometimes', 'array'],
            'settings.show_timer' => ['required_with:settings', 'boolean'],
            'settings.auto_submit' => ['required_with:settings', 'boolean'],
            'settings.allow_extensions' => ['required_with:settings', 'boolean'],
            'warnings' => ['sometimes', 'array'],
            'warnings.*.minutes_before' => ['required_with:warnings', 'integer', 'gt:0'],
            'warnings.*.message' => ['required_with:warnings', 'string'],
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
    //         'instructor_id.required' => ValidationType::Required->getMessage(),
    //         'instructor_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'duration_minutes.required' => ValidationType::Required->getMessage(),
    //         'duration_minutes.integer' => ValidationType::Integer->getMessage(),
    //         'duration_minutes.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'grace_time_minutes.required' => ValidationType::Required->getMessage(),
    //         'grace_time_minutes.integer' => ValidationType::Integer->getMessage(),
    //         'grace_time_minutes.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'extension_time_minutes.required' => ValidationType::Required->getMessage(),
    //         'extension_time_minutes.integer' => ValidationType::Integer->getMessage(),
    //         'extension_time_minutes.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'settings.required' => ValidationType::Required->getMessage(),
    //         'settings.array' => ValidationType::Array->getMessage(),
    //         'settings.show_timer.required' => ValidationType::Required->getMessage(),
    //         'settings.show_timer.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'settings.show_timer.boolean' => ValidationType::Boolean->getMessage(),
    //         'settings.auto_submit.required' => ValidationType::Required->getMessage(),
    //         'settings.auto_submit.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'settings.auto_submit.boolean' => ValidationType::Boolean->getMessage(),
    //         'settings.allow_extensions.required' => ValidationType::Required->getMessage(),
    //         'settings.allow_extensions.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'settings.allow_extensions.boolean' => ValidationType::Boolean->getMessage(),
    //         'warnings.required' => ValidationType::Required->getMessage(),
    //         'warnings.array' => ValidationType::Array->getMessage(),
    //         'warnings.*.minutes_before.required' => ValidationType::Required->getMessage(),
    //         'warnings.*.minutes_before.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'warnings.*.minutes_before.integer' => ValidationType::Integer->getMessage(),
    //         'warnings.*.minutes_before.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'warnings.*.message.required' => ValidationType::Required->getMessage(),
    //         'warnings.*.message.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'warnings.*.message.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'instructor_id' => FieldName::InstructorId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'duration_minutes' => FieldName::DurationMinutes->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'grace_time_minutes' => FieldName::GraceTimeMinutes->getMessage(),
    //         'extension_time_minutes' => FieldName::ExtensionTimeMinutes->getMessage(),
    //         'settings' => FieldName::Settings->getMessage(),
    //         'settings.show_timer' => FieldName::ShowTimer->getMessage(),
    //         'settings.auto_submit' => FieldName::AutoSubmit->getMessage(),
    //         'settings.allow_extensions' => FieldName::AllowExtensions->getMessage(),
    //         'warnings' => FieldName::Warnings->getMessage(),
    //         'warnings.*.minutes_before' => FieldName::MinutesBefore->getMessage(),
    //         'warnings.*.message' => FieldName::Message->getMessage(),
    //     ];
    // }
}
