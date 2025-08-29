<?php

namespace App\DataTransferObjects\Course;

use App\Http\Requests\Course\CourseRequest;
use App\Enums\Course\CourseAccessType;
use App\Enums\Course\CourseLanguage;
use App\Enums\Course\CourseLevel;
use App\Enums\Course\CourseStatus;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;

class CourseDto
{
    public function __construct(
        public readonly ?CourseAccessType $accessType,
        public readonly ?bool $allCourses,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $categoryId,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?CourseLanguage $language,
        public readonly ?CourseLevel $level,
        public readonly ?string $timezone,
        public readonly ?Carbon $startDate,
        public readonly ?Carbon $endDate,
        public readonly ?UploadedFile $coverImage,
        public readonly ?CourseStatus $status,
        public readonly ?int $duration,
        public readonly ?float $price,
        public readonly ?string $code,
        public readonly ?CourseAccessSettingsDto $accessSettingsDto,
        public readonly ?CourseFeaturesDto $featuresDto,
    ) {}

    public static function fromIndexRequest(CourseRequest $request): CourseDto
    {
        return new self(
            accessType: $request->validated('access_type') ? CourseAccessType::from($request->validated('access_type')) : null,
            allCourses: $request->validated('all_courses'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            categoryId: null,
            name: null,
            description: null,
            language: null,
            level: null,
            timezone: null,
            startDate: null,
            endDate: null,
            coverImage: null,
            status: null,
            duration: null,
            price: null,
            code: null,
            accessSettingsDto: null,
            featuresDto: null,
        );
    }

    public static function fromStoreRequest(CourseRequest $request): CourseDto
    {
        return new self(
            accessType: null,
            allCourses: null,
            currentPage: null,
            pageSize: null,
            categoryId: $request->validated('category_id'),
            name: $request->validated('name'),
            description: $request->validated('description'),
            language: $request->validated('language') ?
                CourseLanguage::from($request->validated('language')) :
                null,
            level: $request->validated('level') ?
                CourseLevel::from($request->validated('level')) :
                null,
            timezone: $request->validated('timezone'),
            startDate: Carbon::parse($request->validated('start_date')),
            endDate: Carbon::parse($request->validated('end_date')),
            coverImage: $request->validated('cover_image') ?
                UploadedFile::createFromBase($request->validated('cover_image')) :
                null,
            status: CourseStatus::from($request->validated('status')),
            duration: $request->validated('duration'),
            price: $request->validated('price'),
            code: $request->validated('code'),
            accessSettingsDto: CourseAccessSettingsDto::from($request),
            featuresDto: CourseFeaturesDto::from($request),
        );
    }

    public static function fromUpdateRequest(CourseRequest $request): CourseDto
    {
        return new self(
            accessType: null,
            allCourses: null,
            currentPage: null,
            pageSize: null,
            categoryId: $request->validated('category_id'),
            name: $request->validated('name'),
            description: $request->validated('description'),
            language: $request->validated('language') ?
                CourseLanguage::from($request->validated('language')) :
                null,
            level: $request->validated('level') ?
                CourseLevel::from($request->validated('level')) :
                null,
            timezone: $request->validated('timezone'),
            startDate: $request->validated('start_date') ?
                Carbon::parse($request->validated('start_date')) :
                null,
            endDate: $request->validated('end_date') ?
                Carbon::parse($request->validated('end_date')) :
                null,
            coverImage: $request->validated('cover_image') ?
                UploadedFile::createFromBase($request->validated('cover_image')) :
                null,
            status: $request->validated('status') ?
                CourseStatus::from($request->validated('status')) :
                null,
            duration: $request->validated('duration'),
            price: $request->validated('price'),
            code: $request->validated('code'),
            accessSettingsDto: CourseAccessSettingsDto::from($request),
            featuresDto: CourseFeaturesDto::from($request),
        );
    }
}
