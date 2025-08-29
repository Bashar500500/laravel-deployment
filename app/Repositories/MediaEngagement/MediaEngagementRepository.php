<?php

namespace App\Repositories\MediaEngagement;

use App\Repositories\BaseRepository;
use App\Models\MediaEngagement\MediaEngagement;
use App\DataTransferObjects\MediaEngagement\MediaEngagementDto;
use Illuminate\Support\Facades\DB;

class MediaEngagementRepository extends BaseRepository implements MediaEngagementRepositoryInterface
{
    public function __construct(MediaEngagement $mediaEngagement) {
        parent::__construct($mediaEngagement);
    }

    public function create(MediaEngagementDto $dto, array $data): void
    {
        $mediaEngagement = DB::transaction(function () use ($dto, $data) {
            $mediaEngagement = (object) $this->model->create([
                'student_id' => $data['studentId'],
                'course_id' => $dto->courseId,
                'media_type' => $dto->mediaType,
                'watch_time' => $dto->watchTime,
                'completion_percentage' => $dto->completionPercentage,
                'play_count' => $dto->playCount,
                'engagement_score' => $dto->engagementScore,
            ]);

            return $mediaEngagement;
        });
    }
}
