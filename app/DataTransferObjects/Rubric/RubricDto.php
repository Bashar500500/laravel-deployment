<?php

namespace App\DataTransferObjects\Rubric;

use App\Http\Requests\Rubric\RubricRequest;
use App\Enums\Rubric\RubricType;

class RubricDto
{
    public function __construct(
        public readonly ?int $instructorId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $title,
        public readonly ?RubricType $type,
        public readonly ?string $description,
        public readonly ?array $rubricCriterias,
    ) {}

    public static function fromIndexRequest(RubricRequest $request): RubricDto
    {
        return new self(
            instructorId: null,
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            title: null,
            type: null,
            description: null,
            rubricCriterias: null,
        );
    }

    public static function fromStoreRequest(RubricRequest $request): RubricDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            type: RubricType::from($request->validated('type')),
            description: $request->validated('description'),
            rubricCriterias: $request->validated('rubric_criterias'),
        );
    }

    public static function fromUpdateRequest(RubricRequest $request): RubricDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            type: $request->validated('type') ?
                RubricType::from($request->validated('type')) :
                null,
            description: $request->validated('description'),
            rubricCriterias: $request->validated('rubric_criterias'),
        );
    }
}
