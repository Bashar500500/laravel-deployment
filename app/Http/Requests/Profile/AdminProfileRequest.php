<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Profile\ProfileGender;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AdminProfileRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'user_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'date_of_birth' => ['required', 'date', 'date_format:Y-m-d'],
            'gender' => ['required', new Enum(ProfileGender::class)],
            'nationality' => ['required', 'string'],
            'phone' => ['required', 'string', 'min:10'],
            'emergency_contact_name' => ['required', 'string'],
            'emergency_contact_relation' => ['required', 'string'],
            'emergency_contact_phone' => ['required', 'string'],
            'permanent_address' => ['required', 'array'],
            'permanent_address.street' => ['required', 'string'],
            'permanent_address.city' => ['required', 'string'],
            'permanent_address.state' => ['required', 'string'],
            'permanent_address.country' => ['required', 'string'],
            'permanent_address.zip_code' => ['required', 'string'],
            'temporary_address' => ['required', 'array'],
            'temporary_address.street' => ['required', 'string'],
            'temporary_address.city' => ['required', 'string'],
            'temporary_address.state' => ['required', 'string'],
            'temporary_address.country' => ['required', 'string'],
            'temporary_address.zip_code' => ['required', 'string'],
            'enrollment_date' => ['required', 'date', 'date_format:Y-m-d'],
            'batch' => ['required', 'string'],
            'current_semester' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'user_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'date_of_birth' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'gender' => ['sometimes', new Enum(ProfileGender::class)],
            'nationality' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'string', 'min:10'],
            'emergency_contact_name' => ['sometimes', 'string'],
            'emergency_contact_relation' => ['sometimes', 'string'],
            'emergency_contact_phone' => ['sometimes', 'string'],
            'permanent_address' => ['sometimes', 'array'],
            'permanent_address.street' => ['required_with:permanent_address', 'string'],
            'permanent_address.city' => ['required_with:permanent_address', 'string'],
            'permanent_address.state' => ['required_with:permanent_address', 'string'],
            'permanent_address.country' => ['required_with:permanent_address', 'string'],
            'permanent_address.zip_code' => ['required_with:permanent_address', 'string'],
            'temporary_address' => ['sometimes', 'array'],
            'temporary_address.street' => ['required_with:temporary_address', 'string'],
            'temporary_address.city' => ['required_with:temporary_address', 'string'],
            'temporary_address.state' => ['required_with:temporary_address', 'string'],
            'temporary_address.country' => ['required_with:temporary_address', 'string'],
            'temporary_address.zip_code' => ['required_with:temporary_address', 'string'],
            'enrollment_date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'batch' => ['sometimes', 'string'],
            'current_semester' => ['sometimes', 'string'],
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
    //         'user_id.required' => ValidationType::Required->getMessage(),
    //         'user_id.exists' => ValidationType::Exists->getMessage(),
    //         'user_image.image' => ValidationType::Image->getMessage(),
    //         'user_image.mimes' => ValidationType::ImageMimes->getMessage(),
    //         'date_of_birth.required' => ValidationType::Required->getMessage(),
    //         'date_of_birth.date' => ValidationType::Date->getMessage(),
    //         'date_of_birth.date_format' => ValidationType::DateFormat->getMessage(),
    //         'gender.required' => ValidationType::Required->getMessage(),
    //         'gender.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'nationality.required' => ValidationType::Required->getMessage(),
    //         'nationality.string' => ValidationType::String->getMessage(),
    //         'phone.required' => ValidationType::Required->getMessage(),
    //         'phone.string' => ValidationType::String->getMessage(),
    //         'phone.min' => ValidationType::Min->getMessage(),
    //         'emergency_contact_name.required' => ValidationType::Required->getMessage(),
    //         'emergency_contact_name.string' => ValidationType::String->getMessage(),
    //         'emergency_contact_relation.required' => ValidationType::Required->getMessage(),
    //         'emergency_contact_relation.string' => ValidationType::String->getMessage(),
    //         'emergency_contact_phone.required' => ValidationType::Required->getMessage(),
    //         'emergency_contact_phone.string' => ValidationType::String->getMessage(),
    //         'permanent_address.required' => ValidationType::Required->getMessage(),
    //         'permanent_address.array' => ValidationType::Array->getMessage(),
    //         'permanent_address.street.required' => ValidationType::Required->getMessage(),
    //         'permanent_address.street.string' => ValidationType::String->getMessage(),
    //         'permanent_address.city.required' => ValidationType::Required->getMessage(),
    //         'permanent_address.city.string' => ValidationType::String->getMessage(),
    //         'permanent_address.state.required' => ValidationType::Required->getMessage(),
    //         'permanent_address.state.string' => ValidationType::String->getMessage(),
    //         'permanent_address.country.required' => ValidationType::Required->getMessage(),
    //         'permanent_address.country.string' => ValidationType::String->getMessage(),
    //         'permanent_address.zip_code.required' => ValidationType::Required->getMessage(),
    //         'permanent_address.zip_code.string' => ValidationType::String->getMessage(),
    //         'temporary_address.required' => ValidationType::Required->getMessage(),
    //         'temporary_address.array' => ValidationType::Array->getMessage(),
    //         'temporary_address.street.required' => ValidationType::Required->getMessage(),
    //         'temporary_address.street.string' => ValidationType::String->getMessage(),
    //         'temporary_address.city.required' => ValidationType::Required->getMessage(),
    //         'temporary_address.city.string' => ValidationType::String->getMessage(),
    //         'temporary_address.state.required' => ValidationType::Required->getMessage(),
    //         'temporary_address.state.string' => ValidationType::String->getMessage(),
    //         'temporary_address.country.required' => ValidationType::Required->getMessage(),
    //         'temporary_address.country.string' => ValidationType::String->getMessage(),
    //         'temporary_address.zip_code.required' => ValidationType::Required->getMessage(),
    //         'temporary_address.zip_code.string' => ValidationType::String->getMessage(),
    //         'enrollment_date.required' => ValidationType::Required->getMessage(),
    //         'enrollment_date.date' => ValidationType::Date->getMessage(),
    //         'enrollment_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'batch.required' => ValidationType::Required->getMessage(),
    //         'batch.string' => ValidationType::String->getMessage(),
    //         'current_semester.required' => ValidationType::Required->getMessage(),
    //         'current_semester.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'user_id' => FieldName::UserId->getMessage(),
    //         'user_image' => FieldName::UserImage->getMessage(),
    //         'date_of_birth' => FieldName::DateOfBirth->getMessage(),
    //         'gender' => FieldName::Gender->getMessage(),
    //         'nationality' => FieldName::Nationality->getMessage(),
    //         'phone' => FieldName::Phone->getMessage(),
    //         'emergency_contact_name' => FieldName::EmergencyContactName->getMessage(),
    //         'emergency_contact_relation' => FieldName::EmergencyContactRelation->getMessage(),
    //         'emergency_contact_phone' => FieldName::EmergencyContactPhone->getMessage(),
    //         'permanent_address' => FieldName::PermanentAddress->getMessage(),
    //         'permanent_address.street' => FieldName::PermanentAddressStreet->getMessage(),
    //         'permanent_address.city' => FieldName::PermanentAddressCity->getMessage(),
    //         'permanent_address.state' => FieldName::PermanentAddressState->getMessage(),
    //         'permanent_address.country' => FieldName::PermanentAddressCountry->getMessage(),
    //         'permanent_address.zip_code' => FieldName::PermanentAddressZipCode->getMessage(),
    //         'temporary_address' => FieldName::TemporaryAddress->getMessage(),
    //         'temporary_address.street' => FieldName::TemporaryAddressStreet->getMessage(),
    //         'temporary_address.city' => FieldName::TemporaryAddressCity->getMessage(),
    //         'temporary_address.state' => FieldName::TemporaryAddressState->getMessage(),
    //         'temporary_address.country' => FieldName::TemporaryAddressCountry->getMessage(),
    //         'temporary_address.zip_code' => FieldName::TemporaryAddressZipCode->getMessage(),
    //         'enrollment_date' => FieldName::EnrollmentDate->getMessage(),
    //         'batch' => FieldName::Batch->getMessage(),
    //         'current_semester' => FieldName::CurrentSemester->getMessage(),
    //     ];
    // }
}
