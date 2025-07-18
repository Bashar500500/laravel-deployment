<?php

namespace App\Services\Holiday;

use App\Repositories\Holiday\HolidayRepositoryInterface;
use App\Http\Requests\Holiday\HolidayRequest;
use App\Models\Holiday\Holiday;
use App\DataTransferObjects\Holiday\HolidayDto;
use Illuminate\Support\Facades\Auth;

class HolidayService
{
    public function __construct(
        protected HolidayRepositoryInterface $repository,
    ) {}

    public function index(HolidayRequest $request): object
    {
        $dto = HolidayDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Holiday $holiday): object
    {
        return $this->repository->find($holiday->id);
    }

    public function store(HolidayRequest $request): object
    {
        $dto = HolidayDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(HolidayRequest $request, Holiday $holiday): object
    {
        $dto = HolidayDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $holiday->id);
    }

    public function destroy(Holiday $holiday): object
    {
        return $this->repository->delete($holiday->id);
    }

    private function prepareStoreData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
