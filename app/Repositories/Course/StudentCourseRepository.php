<?php

namespace App\Repositories\Course;

use App\Repositories\BaseRepository;
use App\Models\Course\Course;
use App\DataTransferObjects\Course\CourseDto;
use App\Enums\Course\CourseStatus;
use App\Enums\Upload\UploadMessage;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Storage;

class StudentCourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $course) {
        parent::__construct($course);
    }

    public function all(CourseDto $dto, array $data): object
    {
        return (object) $this->model->whereIn('id', $data['student']->courses->select('id'))
            ->where('status', CourseStatus::Published)
            ->with('attachment', 'students')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function allWithFilter(CourseDto $dto, array $data): object
    {
        return (object) $this->model->whereIn('id', $data['student']->courses->select('id'))
            ->where('access_settings_access_type', $dto->accessType)
            ->with('attachment', 'students')
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
        return (object) parent::find($id)
            ->load('attachment', 'students');
    }

    public function create(CourseDto $dto, array $data): object
    {
        return (object) [];
    }

    public function update(CourseDto $dto, int $id): object
    {
        return (object) [];
    }

    public function delete(int $id): object
    {
        return (object) [];
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Course/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Course/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        return UploadMessage::Image;
    }

    public function deleteAttachment(int $id): void {}
}
