<?php

namespace App\DataTransferObjects\Profile;

use App\Http\Requests\Profile\UserProfileRequest;
use App\Enums\Profile\ProfileGender;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;

class UserProfileDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?UploadedFile $userImage,
        public readonly ?Carbon $dateOfBirth,
        public readonly ?ProfileGender $gender,
        public readonly ?string $nationality,
        public readonly ?string $phone,
        public readonly ?string $emergencyContactName,
        public readonly ?string $emergencyContactRelation,
        public readonly ?string $emergencyContactPhone,
        public readonly ?UserProfilePermanentAddressDto $userProfilePermanentAddressDto,
        public readonly ?UserProfileTemporaryAddressDto $userProfileTemporaryAddressDto,
        public readonly ?Carbon $enrollmentDate,
        public readonly ?string $batch,
        public readonly ?string $currentSemester,
    ) {}

    public static function fromIndexRequest(UserProfileRequest $request): UserProfileDto
    {
        return new self(
            courseId: $request->validated('course_Id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            userImage: null,
            dateOfBirth: null,
            gender: null,
            nationality: null,
            phone: null,
            emergencyContactName: null,
            emergencyContactRelation: null,
            emergencyContactPhone: null,
            userProfilePermanentAddressDto: null,
            userProfileTemporaryAddressDto: null,
            enrollmentDate: null,
            batch: null,
            currentSemester: null,
        );
    }

    public static function fromStoreRequest(UserProfileRequest $request): UserProfileDto
    {
        return new self(
            courseId: null,
            currentPage: null,
            pageSize: null,
            userImage: $request->validated('user_image') ?
                UploadedFile::createFromBase($request->validated('cover_image')) :
                null,
            dateOfBirth: Carbon::parse($request->validated('date_of_birth')),
            gender: ProfileGender::from($request->validated('gender')),
            nationality: $request->validated('nationality'),
            phone: $request->validated('phone'),
            emergencyContactName: $request->validated('emergency_contact_name'),
            emergencyContactRelation: $request->validated('emergency_contact_relation'),
            emergencyContactPhone: $request->validated('emergency_contact_phone'),
            userProfilePermanentAddressDto: UserProfilePermanentAddressDto::from($request),
            userProfileTemporaryAddressDto: UserProfileTemporaryAddressDto::from($request),
            enrollmentDate: Carbon::parse($request->validated('enrollment_date')),
            batch: $request->validated('batch'),
            currentSemester: $request->validated('current_semester'),
        );
    }

    public static function fromUpdateRequest(UserProfileRequest $request): UserProfileDto
    {
        return new self(
            courseId: null,
            currentPage: null,
            pageSize: null,
            userImage: $request->validated('user_image') ?
                UploadedFile::createFromBase($request->validated('cover_image')) :
                null,
            dateOfBirth: $request->validated('date_of_birth') ?
                Carbon::parse($request->validated('date_of_birth')) :
                null,
            gender: $request->validated('gender') ?
                ProfileGender::from($request->validated('gender')) :
                null,
            nationality: $request->validated('nationality'),
            phone: $request->validated('phone'),
            emergencyContactName: $request->validated('emergency_contact_name'),
            emergencyContactRelation: $request->validated('emergency_contact_relation'),
            emergencyContactPhone: $request->validated('emergency_contact_phone'),
            userProfilePermanentAddressDto: UserProfilePermanentAddressDto::from($request),
            userProfileTemporaryAddressDto: UserProfileTemporaryAddressDto::from($request),
            enrollmentDate: Carbon::parse($request->validated('enrollment_date')),
            batch: $request->validated('batch'),
            currentSemester: $request->validated('current_semester'),
        );
    }
}
