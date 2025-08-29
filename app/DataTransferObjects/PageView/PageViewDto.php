<?php

namespace App\DataTransferObjects\PageView;

use App\Http\Requests\PageView\PageViewRequest;
use App\Enums\PageView\PageViewPageType;

class PageViewDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?string $pageUrl,
        public readonly ?string $pageTitle,
        public readonly ?PageViewPageType $pageType,
        public readonly ?int $timeOnPage,
        public readonly ?float $scrollDepth,
        public readonly ?bool $isBounce,
    ) {}

    public static function fromRequest(PageViewRequest $request): PageViewDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            pageUrl: $request->validated('page_url'),
            pageTitle: $request->validated('page_title'),
            pageType: PageViewPageType::from($request->validated('page_type')),
            timeOnPage: $request->validated('time_on_page'),
            scrollDepth: $request->validated('scroll_depth'),
            isBounce: $request->validated('is_bounce'),
        );
    }
}
