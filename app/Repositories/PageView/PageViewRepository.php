<?php

namespace App\Repositories\PageView;

use App\Repositories\BaseRepository;
use App\Models\PageView\PageView;
use App\DataTransferObjects\PageView\PageViewDto;
use Illuminate\Support\Facades\DB;

class PageViewRepository extends BaseRepository implements PageViewRepositoryInterface
{
    public function __construct(PageView $pageView) {
        parent::__construct($pageView);
    }

    public function create(PageViewDto $dto, array $data): void
    {
        $pageView = DB::transaction(function () use ($dto, $data) {
            $pageView = (object) $this->model->create([
                'student_id' => $data['studentId'],
                'course_id' => $dto->courseId,
                'page_url' => $dto->pageUrl,
                'page_title' => $dto->pageTitle,
                'page_type' => $dto->pageType,
                'time_on_page' => $dto->timeOnPage,
                'scroll_depth' => $dto->scrollDepth,
                'is_bounce' => $dto->isBounce,
            ]);

            return $pageView;
        });
    }
}
