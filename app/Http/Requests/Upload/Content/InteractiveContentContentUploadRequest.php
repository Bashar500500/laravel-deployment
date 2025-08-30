<?php

namespace App\Http\Requests\Upload\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;
use App\Enums\InteractiveContent\InteractiveContentType;

class InteractiveContentContentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content_type' => ['required', new Enum(InteractiveContentType::class)],
            'video' => ['required_if:content_type,==,Video', 'missing_if:content_type,==,' . implode(',', InteractiveContentType::Video->getEnumsExceptValue()), 'file', 'mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm'],
            'presentation' => ['required_if:content_type,==,Presentation', 'missing_if:content_type,==,' . implode(',', InteractiveContentType::Presentation->getEnumsExceptValue()), 'file', 'mimes:ppt,pptx,odp'],
            'quiz' => ['required_if:content_type,==,Quiz', 'missing_if:content_type,==,' . implode(',', InteractiveContentType::Quiz->getEnumsExceptValue()), 'file'],
            'dz_uuid' => ['required', 'string', 'uuid'],
            'dz_chunk_index' => ['required', 'integer', 'gte:0'],
            'dz_total_chunk_count' => ['required', 'integer', 'gt:dz_chunk_index'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'content_type.required' => ValidationType::Required->getMessage(),
    //         'content_type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'pdf.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'pdf.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'pdf.file' => ValidationType::File->getMessage(),
    //         'pdf.mimes' => ValidationType::PdfMimes->getMessage(),
    //         'video.RequiredIf' => ValidationType::RequiredIf->getMessage(),
    //         'video.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'video.file' => ValidationType::File->getMessage(),
    //         'video.mimes' => ValidationType::VideoMimes->getMessage(),
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
    //         'content_type' => FieldName::Type->getMessage(),
    //         'pdf' => FieldName::Pdf->getMessage(),
    //         'video' => FieldName::Video->getMessage(),
    //         'dz_chunk_index' => FieldName::DzChunkIndex->getMessage(),
    //         'dz_total_chunk_count' => FieldName::DzTotalChunkCount->getMessage(),
    //     ];
    // }
}
