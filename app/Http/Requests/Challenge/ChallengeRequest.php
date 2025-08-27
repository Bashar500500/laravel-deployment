<?php

namespace App\Http\Requests\Challenge;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Challenge\ChallengeType;
use App\Enums\Challenge\ChallengeCategory;
use App\Enums\Challenge\ChallengeDifficulty;
use App\Enums\Challenge\ChallengeStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ChallengeRequest extends FormRequest
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
            'type' => ['required', new Enum(ChallengeType::class)],
            'category' => ['required', new Enum(ChallengeCategory::class)],
            'difficulty' => ['required', new Enum(ChallengeDifficulty::class)],
            'status' => ['sometimes', new Enum(ChallengeStatus::class)],
            'description' => ['sometimes', 'string'],
            'courses' => ['required', 'array'],
            'courses.*' => ['required', 'exists:courses,id'],
            'rules' => ['required', 'array'],
            'rules.*' => ['required', 'exists:rules,id'],
            'badges' => ['required', 'array'],
            'badges.*' => ['required', 'exists:badges,id'],
            'conditions' => ['required', 'array'],
            'conditions.duration_days' => ['required', 'integer', 'gte:0'],
            'conditions.start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'conditions.end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'rewards' => ['required', 'array'],
            'rewards.points' => ['required', 'integer', 'gt:0'],
            'rewards.bonus_multiplier' => ['required', 'integer', 'gt:0'],
            'rewards.max_participants' => ['required', 'integer', 'gt:0'],
            'rewards.team_based' => ['required', 'boolean'],
        ];
    }

    protected function onUpdate() {
        return [
            'title' => ['sometimes', 'string'],
            'type' => ['sometimes', new Enum(ChallengeType::class)],
            'category' => ['sometimes', new Enum(ChallengeCategory::class)],
            'difficulty' => ['sometimes', new Enum(ChallengeDifficulty::class)],
            'status' => ['sometimes', new Enum(ChallengeStatus::class)],
            'description' => ['sometimes', 'string'],
            'courses' => ['sometimes', 'array'],
            'courses.*' => ['required_with:courses', 'exists:courses,id'],
            'rules' => ['sometimes', 'array'],
            'rules.*' => ['required_with:rules', 'exists:rules,id'],
            'badges' => ['sometimes', 'array'],
            'badges.*' => ['required_with:badges', 'exists:badges,id'],
            'conditions' => ['sometimes', 'array'],
            'conditions.duration_days' => ['required_with:conditions', 'integer', 'gte:0'],
            'conditions.start_date' => ['required_with:conditions', 'date', 'date_format:Y-m-d'],
            'conditions.end_date' => ['required_with:conditions.start_date', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'rewards' => ['sometimes', 'array'],
            'rewards.points' => ['required_with:rewards', 'integer', 'gt:0'],
            'rewards.bonus_multiplier' => ['required_with:rewards', 'integer', 'gt:0'],
            'rewards.max_participants' => ['required_with:rewards', 'integer', 'gt:0'],
            'rewards.team_based' => ['required_with:rewards', 'boolean'],
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
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'category.required' => ValidationType::Required->getMessage(),
    //         'category.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'difficulty.required' => ValidationType::Required->getMessage(),
    //         'difficulty.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'courses.required' => ValidationType::Required->getMessage(),
    //         'courses.array' => ValidationType::Array->getMessage(),
    //         'courses.*.required' => ValidationType::Required->getMessage(),
    //         'courses.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'courses.*.exists' => ValidationType::Exists->getMessage(),
    //         'rules.required' => ValidationType::Required->getMessage(),
    //         'rules.array' => ValidationType::Array->getMessage(),
    //         'rules.*.required' => ValidationType::Required->getMessage(),
    //         'rules.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rules.*.exists' => ValidationType::Exists->getMessage(),
    //         'badges.required' => ValidationType::Required->getMessage(),
    //         'badges.array' => ValidationType::Array->getMessage(),
    //         'badges.*.required' => ValidationType::Required->getMessage(),
    //         'badges.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'badges.*.exists' => ValidationType::Exists->getMessage(),
    //         'conditions.required' => ValidationType::Required->getMessage(),
    //         'conditions.array' => ValidationType::Array->getMessage(),
    //         'conditions.duration_days.required' => ValidationType::Required->getMessage(),
    //         'conditions.duration_days.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'conditions.duration_days.integer' => ValidationType::Integer->getMessage(),
    //         'conditions.duration_days.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'conditions.start_date.required' => ValidationType::Required->getMessage(),
    //         'conditions.start_date.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'conditions.start_date.date' => ValidationType::Date->getMessage(),
    //         'conditions.start_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'conditions.end_date.required' => ValidationType::Required->getMessage(),
    //         'conditions.end_date.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'conditions.end_date.date' => ValidationType::Date->getMessage(),
    //         'conditions.end_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'conditions.end_date.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'rewards.required' => ValidationType::Required->getMessage(),
    //         'rewards.array' => ValidationType::Array->getMessage(),
    //         'rewards.points.required' => ValidationType::Required->getMessage(),
    //         'rewards.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rewards.points.integer' => ValidationType::Integer->getMessage(),
    //         'rewards.points.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'rewards.bonus_multiplier.required' => ValidationType::Required->getMessage(),
    //         'rewards.bonus_multiplier.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rewards.bonus_multiplier.integer' => ValidationType::Integer->getMessage(),
    //         'rewards.bonus_multiplier.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'rewards.max_participants.required' => ValidationType::Required->getMessage(),
    //         'rewards.max_participants.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rewards.max_participants.integer' => ValidationType::Integer->getMessage(),
    //         'rewards.max_participants.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'rewards.team_based.required' => ValidationType::Required->getMessage(),
    //         'rewards.team_based.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rewards.team_based.boolean' => ValidationType::Boolean->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'difficulty' => FieldName::Difficulty->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'courses' => FieldName::Courses->getMessage(),
    //         'courses.*' => FieldName::Courses->getMessage(),
    //         'rules' => FieldName::Rules->getMessage(),
    //         'rules.*' => FieldName::Rules->getMessage(),
    //         'badges' => FieldName::Badges->getMessage(),
    //         'badges.*' => FieldName::Badges->getMessage(),
    //         'conditions' => FieldName::Conditions->getMessage(),
    //         'conditions.duration_days' => FieldName::DurationDays->getMessage(),
    //         'conditions.start_date' => FieldName::StartDate->getMessage(),
    //         'conditions.end_date' => FieldName::EndDate->getMessage(),
    //         'rewards' => FieldName::Rewards->getMessage(),
    //         'rewards.points' => FieldName::Points->getMessage(),
    //         'rewards.bonus_multiplier' => FieldName::BonusMultiplier->getMessage(),
    //         'rewards.max_participants' => FieldName::MaxParticipants->getMessage(),
    //         'rewards.team_based' => FieldName::TeamBased->getMessage(),
    //     ];
    // }
}
