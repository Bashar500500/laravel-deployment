<?php

namespace App\DataTransferObjects\ForumPost;

use App\Http\Requests\ForumPost\ForumPostRequest;
use App\Enums\ForumPost\ForumPostPostType;

class ForumPostDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $parentPostId,
        public readonly ?ForumPostPostType $postType,
        public readonly ?string $content,
        public readonly ?int $likesCount,
        public readonly ?int $repliesCount,
        public readonly ?bool $isHelpful,
    ) {}

    public static function fromIndexRequest(ForumPostRequest $request): ForumPostDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            parentPostId: null,
            postType: null,
            content: null,
            likesCount: null,
            repliesCount: null,
            isHelpful: null,
        );
    }

    public static function fromStoreRequest(ForumPostRequest $request): ForumPostDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            parentPostId: $request->validated('parent_post_id'),
            postType: ForumPostPostType::from($request->validated('post_type')),
            content: $request->validated('content'),
            likesCount: $request->validated('likes_count'),
            repliesCount: $request->validated('replies_count'),
            isHelpful: $request->validated('is_helpful'),
        );
    }

    public static function fromUpdateRequest(ForumPostRequest $request): ForumPostDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            parentPostId: $request->validated('parent_post_id'),
            postType: $request->validated('post_type') ?
                ForumPostPostType::from($request->validated('post_type')) :
                null,
            content: $request->validated('content'),
            likesCount: $request->validated('likes_count'),
            repliesCount: $request->validated('replies_count'),
            isHelpful: $request->validated('is_helpful'),
        );
    }
}
