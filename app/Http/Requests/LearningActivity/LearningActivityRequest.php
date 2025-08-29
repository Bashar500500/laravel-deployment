<?php

namespace App\Http\Requests\LearningActivity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\LearningActivity\LearningActivityType;
use App\Enums\LearningActivity\LearningActivityStatus;
use App\Enums\LearningActivity\LearningActivityCompletionType;
use App\Enums\LearningActivity\LearningActivityMetadataDifficulty;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class LearningActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'section_id' => ['required', 'exists:sections,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'section_id' => ['required', 'exists:sections,id'],
            'type' => ['required', new Enum(LearningActivityType::class)],
            'title' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(LearningActivityStatus::class)],
            'flags' => ['sometimes', 'array'],
            'flags.isFreePreview' => ['sometimes', 'boolean'],
            'flags.isCompulsory' => ['sometimes', 'boolean'],
            'flags.requiresEnrollment' => ['sometimes', 'boolean'],
            'content' => ['sometimes', 'array'],
            'content.data' => ['sometimes', 'array'],
            'content.data.pdf' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Pdf->getEnumsExceptValue()), 'file', 'mimes:pdf'],
            'content.data.video' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Video->getEnumsExceptValue()), 'file', 'mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm'],
            'content.data.audio' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Audio->getEnumsExceptValue()), 'file', 'mimes:mp3,wav,ogg,m4a'],
            'content.data.word' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Word->getEnumsExceptValue()), 'file', 'mimes:doc,docx'],
            'content.data.power_point' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::PowerPoint->getEnumsExceptValue()), 'file', 'mimes:ppt,pptx'],
            'content.data.zip' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Zip->getEnumsExceptValue()), 'file', 'mimes:zip'],
            'content.data.interactive_content_id' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::InteractiveContent->getEnumsExceptValue()), 'exists:interactive_contents,id'],
            'content.data.reusable_content_id' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::ReusableContent->getEnumsExceptValue()), 'exists:reusable_contents,id'],
            'content.data.captions' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::LiveSession->getEnumsExceptValue()), 'array'],
            'content.data.captions.url' => ['required_with:content.data.captions', 'missing_if:content.type,==,' . implode(',', LearningActivityType::LiveSession->getEnumsExceptValue()), 'url'],
            'thumbnailUrl' => ['sometimes', 'url'],
            'completion' => ['sometimes', 'array'],
            'completion.type' => ['sometimes', new Enum(LearningActivityCompletionType::class)],
            'completion.minDuration' => ['sometimes', 'missing_if:content.type,==,View' . implode(',', LearningActivityCompletionType::View->getEnumsExceptValue()), 'integer', 'gt:0'],
            'completion.passingScore' => ['sometimes', 'missing_if:content.type,==,Score' . implode(',', LearningActivityCompletionType::Score->getEnumsExceptValue()), 'integer', 'gt:0'],
            'completion.rules' => ['sometimes', 'missing_if:content.type,==,Composite' . implode(',', LearningActivityCompletionType::Composite->getEnumsExceptValue()), 'array'],
            'availability' => ['required', 'array'],
            'availability.start' => ['required_with:availability', 'date', 'date_format:Y-m-d'],
            'availability.end' => ['required_with:availability', 'date', 'date_format:Y-m-d', 'after_or_equal:availability.start'],
            'availability.timezone' => ['sometimes', 'regex:^(?:Z|[+-](?:2[0-3]|[01][0-9]):[0-5][0-9])$^'],
            'discussion' => ['sometimes', 'array'],
            'discussion.enabled' => ['sometimes', 'boolean'],
            'discussion.moderated' => ['sometimes', 'boolean'],
            'metadata' => ['sometimes', 'array'],
            'metadata.difficulty' => ['sometimes', new Enum(LearningActivityMetadataDifficulty::class)],
            'metadata.keywords' => ['sometimes', 'array'],
        ];
    }

    protected function onUpdate() {
        return [
            'type' => ['sometimes', new Enum(LearningActivityType::class)],
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(LearningActivityStatus::class)],
            'flags' => ['sometimes', 'array'],
            'flags.isFreePreview' => ['sometimes', 'boolean'],
            'flags.isCompulsory' => ['sometimes', 'boolean'],
            'flags.requiresEnrollment' => ['sometimes', 'boolean'],
            'content' => ['required_with:type', 'array'],
            'content.data' => ['required_with:content', 'array'],
            'content.data.pdf' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Pdf->getEnumsExceptValue()), 'file', 'mimes:pdf'],
            'content.data.video' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Video->getEnumsExceptValue()), 'file', 'mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm'],
            'content.data.audio' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Audio->getEnumsExceptValue()), 'file', 'mimes:mp3,wav,ogg,m4a'],
            'content.data.word' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Word->getEnumsExceptValue()), 'file', 'mimes:doc,docx'],
            'content.data.power_point' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::PowerPoint->getEnumsExceptValue()), 'file', 'mimes:ppt,pptx'],
            'content.data.zip' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::Zip->getEnumsExceptValue()), 'file', 'mimes:zip'],
            'content.data.interactive_content_id' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::InteractiveContent->getEnumsExceptValue()), 'exists:interactive_contents,id'],
            'content.data.reusable_content_id' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::ReusableContent->getEnumsExceptValue()), 'exists:reusable_contents,id'],
            'content.data.captions' => ['sometimes', 'missing_if:content.type,==,' . implode(',', LearningActivityType::LiveSession->getEnumsExceptValue()), 'array'],
            'content.data.captions.url' => ['required_with:content.data.captions', 'missing_if:content.type,==,' . implode(',', LearningActivityType::LiveSession->getEnumsExceptValue()), 'url'],
            'thumbnailUrl' => ['sometimes', 'url'],
            'completion' => ['sometimes', 'array'],
            'completion.type' => ['required_with:completion', new Enum(LearningActivityCompletionType::class)],
            'completion.minDuration' => ['sometimes', 'missing_if:content.type,==,View' . implode(',', LearningActivityCompletionType::View->getEnumsExceptValue()), 'integer', 'gt:0'],
            'completion.passingScore' => ['sometimes', 'missing_if:content.type,==,Score' . implode(',', LearningActivityCompletionType::Score->getEnumsExceptValue()), 'integer', 'gt:0'],
            'completion.rules' => ['sometimes', 'missing_if:content.type,==,Composite' . implode(',', LearningActivityCompletionType::Composite->getEnumsExceptValue()), 'array'],
            'availability' => ['sometimes', 'array'],
            'availability.start' => ['required_with:availability', 'date', 'date_format:Y-m-d'],
            'availability.end' => ['required_with:availability', 'date', 'date_format:Y-m-d', 'after_or_equal:availability.start'],
            'availability.timezone' => ['sometimes', 'regex:^(?:Z|[+-](?:2[0-3]|[01][0-9]):[0-5][0-9])$^'],
            'discussion' => ['sometimes', 'array'],
            'discussion.enabled' => ['sometimes', 'boolean'],
            'discussion.moderated' => ['sometimes', 'boolean'],
            'metadata' => ['sometimes', 'array'],
            'metadata.difficulty' => ['sometimes', new Enum(LearningActivityMetadataDifficulty::class)],
            'metadata.keywords' => ['sometimes', 'array'],
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
    //         'section_id.required' => ValidationType::Required->getMessage(),
    //         'section_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'title.required' => ValidationType::Required->getMessage(),
    //         'title.string' => ValidationType::String->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'flags.required' => ValidationType::Required->getMessage(),
    //         'flags.array' => ValidationType::Array->getMessage(),
    //         'flags.isFreePreview.required' => ValidationType::Required->getMessage(),
    //         'flags.isFreePreview.boolean' => ValidationType::Boolean->getMessage(),
    //         'flags.isCompulsory.required' => ValidationType::Required->getMessage(),
    //         'flags.isCompulsory.boolean' => ValidationType::Boolean->getMessage(),
    //         'flags.requiresEnrollment.required' => ValidationType::Required->getMessage(),
    //         'flags.requiresEnrollment.boolean' => ValidationType::Boolean->getMessage(),
    //         'content.required' => ValidationType::Required->getMessage(),
    //         'content.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'content.array' => ValidationType::Array->getMessage(),
    //         'content.type.required' => ValidationType::Required->getMessage(),
    //         'content.type.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'content.type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'content.type.same' => ValidationType::Same->getMessage(),
    //         'content.data.required' => ValidationType::Required->getMessage(),
    //         'content.data.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'content.data.array' => ValidationType::Array->getMessage(),
    //         'content.data.pdf.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.pdf.file' => ValidationType::File->getMessage(),
    //         'content.data.pdf.mimes' => ValidationType::PdfMimes->getMessage(),
    //         'content.data.sizeMB.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'content.data.sizeMB.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.sizeMB.integer' => ValidationType::Integer->getMessage(),
    //         'content.data.sizeMB.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'content.data.pages.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'content.data.pages.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.pages.integer' => ValidationType::Integer->getMessage(),
    //         'content.data.pages.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'content.data.watermark.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.watermark.boolean' => ValidationType::Boolean->getMessage(),
    //         'content.data.video.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.video.file' => ValidationType::File->getMessage(),
    //         'content.data.video.mimes' => ValidationType::VideoMimes->getMessage(),
    //         'content.data.duration.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'content.data.duration.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.duration.integer' => ValidationType::Integer->getMessage(),
    //         'content.data.duration.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'content.data.captions.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.captions.array' => ValidationType::Array->getMessage(),
    //         'content.data.captions.language.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'content.data.captions.language.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.captions.language.string' => ValidationType::String->getMessage(),
    //         'content.data.captions.url.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'content.data.captions.url.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'content.data.captions.url.url' => ValidationType::Url->getMessage(),
    //         'thumbnailUrl.url' => ValidationType::Url->getMessage(),
    //         'completion.required' => ValidationType::Required->getMessage(),
    //         'completion.array' => ValidationType::Array->getMessage(),
    //         'completion.type.required' => ValidationType::Required->getMessage(),
    //         'completion.type.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'completion.type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'completion.minDuration.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'completion.minDuration.integer' => ValidationType::Integer->getMessage(),
    //         'completion.minDuration.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'completion.passingScore.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'completion.passingScore.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'completion.passingScore.integer' => ValidationType::Integer->getMessage(),
    //         'completion.passingScore.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'completion.rules.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'completion.rules.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'completion.rules.array' => ValidationType::Array->getMessage(),
    //         'availability.required' => ValidationType::Required->getMessage(),
    //         'availability.array' => ValidationType::Array->getMessage(),
    //         'availability.start.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'availability.start.date' => ValidationType::Date->getMessage(),
    //         'availability.start.date_format' => ValidationType::DateFormat->getMessage(),
    //         'availability.end.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'availability.end.date' => ValidationType::Date->getMessage(),
    //         'availability.end.date_format' => ValidationType::DateFormat->getMessage(),
    //         'availability.end.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'availability.timezone.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'availability.timezone.regex' => ValidationType::Regex->getMessage(),
    //         'discussion.array' => ValidationType::Array->getMessage(),
    //         'discussion.enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'discussion.enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'discussion.moderated.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'discussion.moderated.boolean' => ValidationType::Boolean->getMessage(),
    //         'metadata.array' => ValidationType::Array->getMessage(),
    //         'metadata.difficulty.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'metadata.keywords.array' => ValidationType::Array->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'section_id' => FieldName::SectionId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'flags' => FieldName::Flags->getMessage(),
    //         'flags.*.isFreePreview' => FieldName::IsFreePreview->getMessage(),
    //         'flags.*.isCompulsory' => FieldName::IsCompulsory->getMessage(),
    //         'flags.*.requiresEnrollment' => FieldName::RequiresEnrollment->getMessage(),
    //         'content' => FieldName::Content->getMessage(),
    //         'content.type' => FieldName::Type->getMessage(),
    //         'content.data' => FieldName::Data->getMessage(),
    //         'content.data.pdf' => FieldName::Pdf->getMessage(),
    //         'content.data.sizeMB' => FieldName::SizeMB->getMessage(),
    //         'content.data.pages' => FieldName::Pages->getMessage(),
    //         'content.data.watermark' => FieldName::Watermark->getMessage(),
    //         'content.data.video' => FieldName::Video->getMessage(),
    //         'content.data.duration' => FieldName::Duration->getMessage(),
    //         'content.data.captions' => FieldName::Captions->getMessage(),
    //         'content.data.captions.language' => FieldName::Language->getMessage(),
    //         'content.data.captions.url' => FieldName::Url->getMessage(),
    //         'thumbnailUrl' => FieldName::ThumbnailUrl->getMessage(),
    //         'completion' => FieldName::Completion->getMessage(),
    //         'completion.type' => FieldName::Type->getMessage(),
    //         'completion.minDuration' => FieldName::MinDuration->getMessage(),
    //         'completion.passingScore' => FieldName::PassingScore->getMessage(),
    //         'completion.rules' => FieldName::Rules->getMessage(),
    //         'availability' => FieldName::Availability->getMessage(),
    //         'availability.start' => FieldName::Start->getMessage(),
    //         'availability.end' => FieldName::End->getMessage(),
    //         'availability.timezone' => FieldName::Timezone->getMessage(),
    //         'discussion' => FieldName::Discussion->getMessage(),
    //         'discussion.enabled' => FieldName::Enabled->getMessage(),
    //         'discussion.moderated' => FieldName::Moderated->getMessage(),
    //         'metadata' => FieldName::Metadata->getMessage(),
    //         'metadata.difficulty' => FieldName::Difficulty->getMessage(),
    //         'metadata.keywords' => FieldName::Keywords->getMessage(),
    //     ];
    // }
}
