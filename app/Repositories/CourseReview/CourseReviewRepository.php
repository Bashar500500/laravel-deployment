<?php

namespace App\Repositories\CourseReview;

use App\Repositories\BaseRepository;
use App\Models\CourseReview\CourseReview;
use App\DataTransferObjects\CourseReview\CourseReviewDto;
use Illuminate\Support\Facades\DB;

class CourseReviewRepository extends BaseRepository implements CourseReviewRepositoryInterface
{
    public function __construct(CourseReview $courseReview) {
        parent::__construct($courseReview);
    }

    public function all(CourseReviewDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            // ->with('')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id);
            // ->load('');
    }

    public function create(CourseReviewDto $dto, array $data): object
    {
        $courseReview = DB::transaction(function () use ($dto, $data) {
            $courseReview = (object) $this->model->create([
                'student_id' => $data['studentId'],
                'course_id' => $dto->courseId,
                'rating' => $dto->rating,
                'would_recommend' => $dto->wouldRecommend,
            ]);

            return $courseReview;
        });

        return (object) $courseReview;
            // ->load('instructor');
    }

    public function update(CourseReviewDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $courseReview = DB::transaction(function () use ($dto, $model) {
            $courseReview = tap($model)->update([
                'rating' => $dto->rating ? $dto->rating : $model->rating,
                'would_recommend' => $dto->wouldRecommend ? $dto->wouldRecommend : $model->would_recommend,
            ]);

            return $courseReview;
        });

        return (object) $courseReview;
            // ->load('');
    }

    public function delete(int $id): object
    {
        $courseReview = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $courseReview;
    }
}
