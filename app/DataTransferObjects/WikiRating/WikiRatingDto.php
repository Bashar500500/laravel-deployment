<?php

namespace App\DataTransferObjects\WikiRating;

use App\Http\Requests\WikiRating\WikiRatingRequest;

class WikiRatingDto
{
    public function __construct(
        public readonly ?int $wikiId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?float $rating,
    ) {}

    public static function fromIndexRequest(WikiRatingRequest $request): WikiRatingDto
    {
        return new self(
            wikiId: $request->validated('wiki_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            rating: null,
        );
    }

    public static function fromStoreRequest(WikiRatingRequest $request): WikiRatingDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            wikiId: $request->validated('wiki_id'),
            rating: $request->validated('rating'),
        );
    }

    public static function fromUpdateRequest(WikiRatingRequest $request): WikiRatingDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            wikiId: null,
            rating: $request->validated('rating'),
        );
    }
}
