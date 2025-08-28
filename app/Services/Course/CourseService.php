<?php

namespace App\Services\Course;

use App\Factories\Course\CourseRepositoryFactory;
use App\Http\Requests\Course\CourseRequest;
use App\Models\Course\Course;
use App\DataTransferObjects\Course\CourseDto;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    public function __construct(
        protected CourseRepositoryFactory $factory,
    ) {}

    public function index(CourseRequest $request): object
    {
        $dto = CourseDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexAndStoreData('index', $role[0]);
        $repository = $this->factory->make($role[0]);
        return match ($dto->accessType) {
            null => $repository->all($dto, $data),
            default => $repository->allWithFilter($dto, $data),
        };
    }

    public function guestIndex(CourseRequest $request): object
    {
        $dto = CourseDto::fromIndexRequest($request);
        $repository = $this->factory->make('guest');
        return $repository->all($dto, []);
    }

    public function show(Course $course): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($course->id);
    }

    public function store(CourseRequest $request): object
    {
        $dto = CourseDto::fromStoreRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexAndStoreData('store', $role[0]);
        $repository = $this->factory->make($role[0]);
        return $repository->create($dto, $data);
    }

    public function update(CourseRequest $request, Course $course): object
    {
        $dto = CourseDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $course->id);
    }

    public function destroy(Course $course): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($course->id);
    }

    public function view(Course $course): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->view($course->id);
    }

    public function download(Course $course): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->download($course->id);
    }

    public function destroyAttachment(Course $course): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->deleteAttachment($course->id);
    }

    private function prepareIndexAndStoreData(string $function, string $role): array
    {
        return match ($function)
        {
            'index' => [
                "{$role}" => Auth::user(),
            ],
            'store' => [
                'instructorId' => Auth::user()->id,
            ],
        };
    }
}
