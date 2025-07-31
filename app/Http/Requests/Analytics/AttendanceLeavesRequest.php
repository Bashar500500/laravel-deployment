<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AttendanceLeavesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'gt:0'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'year.required' => ValidationType::Required->getMessage(),
    //         'year.integer' => ValidationType::Integer->getMessage(),
    //         'year.gt' => ValidationType::GreaterThanZero->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'year' => FieldName::Year->getMessage(),
    //     ];
    // }
}
