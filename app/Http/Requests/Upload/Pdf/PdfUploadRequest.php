<?php

namespace App\Http\Requests\Upload\Pdf;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class PdfUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pdf' => ['required', 'file', 'mimes:pdf'],
            'dz_uuid' => ['required', 'string', 'uuid'],
            'dz_chunk_index' => ['required', 'integer', 'gte:0'],
            'dz_total_chunk_count' => ['required', 'integer', 'gt:dz_chunk_index'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'pdf.required' => ValidationType::Required->getMessage(),
    //         'pdf.file' => ValidationType::File->getMessage(),
    //         'pdf.mimes' => ValidationType::PdfMimes->getMessage(),
    //         'dz_uuid.required' => ValidationType::Required->getMessage(),
    //         'dz_uuid.string' => ValidationType::Integer->getMessage(),
    //         'dz_uuid.uuid' => ValidationType::Uuid->getMessage(),
    //         'dz_chunk_index.required' => ValidationType::Required->getMessage(),
    //         'dz_chunk_index.integer' => ValidationType::Integer->getMessage(),
    //         'dz_chunk_index.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'dz_total_chunk_count.required' => ValidationType::Required->getMessage(),
    //         'dz_total_chunk_count.integer' => ValidationType::Integer->getMessage(),
    //         'dz_total_chunk_count.gt' => ValidationType::GreaterThan->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'pdf' => FieldName::Pdf->getMessage(),
    //         'dz_chunk_index' => FieldName::DzChunkIndex->getMessage(),
    //         'dz_total_chunk_count' => FieldName::DzTotalChunkCount->getMessage(),
    //     ];
    // }
}
