<?php

namespace App\Http\Requests\Wiki;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Wiki\WikiType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class WikiRequest extends FormRequest
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
            'type' => ['required', new Enum(WikiType::class)],
            'tags' => ['required', 'array'],
            'tags.*' => ['required', 'string'],
            'collaborators' => ['required', 'array'],
            'collaborators.*' => ['required', 'exists:users,id'],
            'files' => ['sometimes', 'array'],
            'files.*' => ['required_with:files', 'file'],
        ];
    }

    protected function onUpdate() {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'type' => ['sometimes', new Enum(WikiType::class)],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['required_with:tags', 'string'],
            'collaborators' => ['sometimes', 'array'],
            'collaborators.*' => ['required_with:collaborators', 'exists:users,id'],
            'files' => ['sometimes', 'array'],
            'files.*' => ['required_with:files', 'file'],
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
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'tags.required' => ValidationType::Required->getMessage(),
    //         'tags.array' => ValidationType::Array->getMessage(),
    //         'tags.*.required' => ValidationType::Required->getMessage(),
    //         'tags.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'tags.*.string' => ValidationType::String->getMessage(),
    //         'collaborators.required' => ValidationType::Required->getMessage(),
    //         'collaborators.array' => ValidationType::Array->getMessage(),
    //         'collaborators.*.required' => ValidationType::Required->getMessage(),
    //         'collaborators.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'collaborators.*.exists' => ValidationType::Exists->getMessage(),
    //         'files.array' => ValidationType::Array->getMessage(),
    //         'files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'files.*.file' => ValidationType::File->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'tags' => FieldName::Tags->getMessage(),
    //         'tags.*' => FieldName::Tags->getMessage(),
    //         'collaborators' => FieldName::Collaborators->getMessage(),
    //         'collaborators.*' => FieldName::Collaborators->getMessage(),
    //         'files' => FieldName::Files->getMessage(),
    //         'files.*' => FieldName::Files->getMessage(),
    //     ];
    // }
}
