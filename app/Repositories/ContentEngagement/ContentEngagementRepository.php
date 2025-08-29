<?php

namespace App\Repositories\ContentEngagement;

use App\Repositories\BaseRepository;
use App\Models\ContentEngagement\ContentEngagement;
use App\DataTransferObjects\ContentEngagement\ContentEngagementDto;
use Illuminate\Support\Facades\DB;

class ContentEngagementRepository extends BaseRepository implements ContentEngagementRepositoryInterface
{
    public function __construct(ContentEngagement $contentEngagement) {
        parent::__construct($contentEngagement);
    }

    public function create(ContentEngagementDto $dto, array $data): void
    {
        $contentEngagement = DB::transaction(function () use ($dto, $data) {
            $contentEngagement = (object) $this->model->create([
                'student_id' => $data['studentId'],
                'course_id' => $dto->courseId,
                'content_type' => $dto->contentType,
                'engagement_type' => $dto->engagementType,
                'engagement_value' => $dto->engagementValue,
                'engagement_data' => $dto->engagementData,
            ]);

            return $contentEngagement;
        });
    }
}
