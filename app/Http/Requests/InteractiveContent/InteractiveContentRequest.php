<?php

namespace App\Http\Requests\InteractiveContent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\InteractiveContent\InteractiveContentType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class InteractiveContentRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'type' => ['required', new Enum(InteractiveContentType::class)],
            'file' => [
                'sometimes',
                'file',
                ($this->request->get('type') == InteractiveContentType::Video->getType() ?
                    'mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm' :
                    $this->request->get('type') == InteractiveContentType::Presentation->getType()) ?
                    'mimes:ppt,pptx,odp' :
                    '',
            ],
        ];
    }

    protected function onUpdate() {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'type' => ['sometimes', new Enum(InteractiveContentType::class)],
            'file' => [
                'required_with:type',
                'file',
                ($this->request->get('type') == InteractiveContentType::Video->getType() ?
                    'mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm' :
                    $this->request->get('type') == InteractiveContentType::Presentation->getType()) ?
                    'mimes:ppt,pptx,odp' :
                    '',
            ],
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
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'rubric_id.required' => ValidationType::Required->getMessage(),
    //         'rubric_id.exists' => ValidationType::Exists->getMessage(),
    //         'leader_id.required' => ValidationType::Required->getMessage(),
    //         'leader_id.exists' => ValidationType::Exists->getMessage(),
    //         'group_id.required' => ValidationType::Required->getMessage(),
    //         'group_id.exists' => ValidationType::Exists->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'start_date.required' => ValidationType::Required->getMessage(),
    //         'start_date.date' => ValidationType::Date->getMessage(),
    //         'start_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'end_date.required' => ValidationType::Required->getMessage(),
    //         'end_date.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'end_date.date' => ValidationType::Date->getMessage(),
    //         'end_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'end_date.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'points.required' => ValidationType::Required->getMessage(),
    //         'points.integer' => ValidationType::Integer->getMessage(),
    //         'max_submits.required' => ValidationType::Required->getMessage(),
    //         'max_submits.integer' => ValidationType::Integer->getMessage(),
    //         'files.array' => ValidationType::Array->getMessage(),
    //         'files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'files.*.file' => ValidationType::File->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'rubric_id' => FieldName::RubricId->getMessage(),
    //         'leader_id' => FieldName::LeaderId->getMessage(),
    //         'group_id' => FieldName::GroupId->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'start_date' => FieldName::StartDate->getMessage(),
    //         'end_date' => FieldName::EndDate->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'points' => FieldName::Points->getMessage(),
    //         'max_submits' => FieldName::MaxSubmits->getMessage(),
    //         'files' => FieldName::Files->getMessage(),
    //         'files.*' => FieldName::Files->getMessage(),
    //     ];
    // }
}
