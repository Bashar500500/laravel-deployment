<?php

namespace App\Http\Requests\Upload\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;
use App\Enums\LearningActivity\LearningActivityType;

class LearningActivityContentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content_type' => ['required', new Enum(LearningActivityType::class)],
            'pdf' => ['required_if:content_type,==,PDF', 'missing_if:content_type,==,' . implode(',', LearningActivityType::Pdf->getEnumsExceptValue()), 'file', 'mimes:pdf'],
            'video' => ['required_if:content_type,==,Video', 'missing_if:content_type,==,' . implode(',', LearningActivityType::Video->getEnumsExceptValue()), 'file', 'mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm'],
            'audio' => ['required_if:content_type,==,Audio', 'missing_if:content_type,==,' . implode(',', LearningActivityType::Audio->getEnumsExceptValue()), 'file', 'mimes:mp3,wav,ogg,m4a'],
            'word' => ['required_if:content_type,==,Word', 'missing_if:content_type,==,' . implode(',', LearningActivityType::Word->getEnumsExceptValue()), 'file', 'mimes:doc,docx'],
            'power_point' => ['required_if:content_type,==,PowerPoint', 'missing_if:content_type,==,' . implode(',', LearningActivityType::PowerPoint->getEnumsExceptValue()), 'file', 'mimes:ppt,pptx'],
            'zip' => ['required_if:content_type,==,Zip', 'missing_if:content_type,==,' . implode(',', LearningActivityType::Zip->getEnumsExceptValue()), 'file', 'mimes:zip'],
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
