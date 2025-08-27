<?php

namespace App\DataTransferObjects\EnrollmentOption;

use App\Http\Requests\EnrollmentOption\EnrollmentOptionRequest;
use App\Enums\EnrollmentOption\EnrollmentOptionType;
use App\Enums\EnrollmentOption\EnrollmentOptionPeriod;

class EnrollmentOptionDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?EnrollmentOptionType $type,
        public readonly ?EnrollmentOptionPeriod $period,
        public readonly ?bool $allowSelfEnrollment,
        public readonly ?bool $enableWaitingList,
        public readonly ?bool $requireInstructorApproval,
        public readonly ?bool $requirePrerequisites,
        public readonly ?bool $enableNotifications,
        public readonly ?bool $enableEmails,
    ) {}

    public static function fromIndexRequest(EnrollmentOptionRequest $request): EnrollmentOptionDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            type: null,
            period: null,
            allowSelfEnrollment: null,
            enableWaitingList: null,
            requireInstructorApproval: null,
            requirePrerequisites: null,
            enableNotifications: null,
            enableEmails: null,
        );
    }

    public static function fromStoreRequest(EnrollmentOptionRequest $request): EnrollmentOptionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            type: EnrollmentOptionType::from($request->validated('type')),
            period: EnrollmentOptionPeriod::from($request->validated('period')),
            allowSelfEnrollment: $request->validated('allow_self_enrollment'),
            enableWaitingList: $request->validated('enable_waiting_list'),
            requireInstructorApproval: $request->validated('require_instructor_approval'),
            requirePrerequisites: $request->validated('require_prerequisites'),
            enableNotifications: $request->validated('enable_notifications'),
            enableEmails: $request->validated('enable_emails'),
        );
    }

    public static function fromUpdateRequest(EnrollmentOptionRequest $request): EnrollmentOptionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            type: $request->validated('type') ?
                EnrollmentOptionType::from($request->validated('type')) :
                null,
            period: $request->validated('period') ?
                EnrollmentOptionPeriod::from($request->validated('period')) :
                null,
            allowSelfEnrollment: $request->validated('allow_self_enrollment'),
            enableWaitingList: $request->validated('enable_waiting_list'),
            requireInstructorApproval: $request->validated('require_instructor_approval'),
            requirePrerequisites: $request->validated('require_prerequisites'),
            enableNotifications: $request->validated('enable_notifications'),
            enableEmails: $request->validated('enable_emails'),
        );
    }
}
