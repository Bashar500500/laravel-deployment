<?php

namespace App\Http\Requests\WikiRating;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class WikiRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'wiki_id' => ['required', 'exists:wikis,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'wiki_id' => ['required', 'exists:wikis,id'],
            'rating' => ['required', 'decimal:0,2', 'gte:0'],
        ];
    }

    protected function onUpdate() {
        return [
            'rating' => ['required', 'decimal:0,2', 'gte:0'],
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
    //         'wiki_id.required' => ValidationType::Required->getMessage(),
    //         'wiki_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'rating.required' => ValidationType::Required->getMessage(),
    //         'rating.decimal' => ValidationType::Decimal->getMessage(),
    //         'rating.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'wiki_id' => FieldName::WikiId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'rating' => FieldName::Rating->getMessage(),
    //     ];
    // }
}
