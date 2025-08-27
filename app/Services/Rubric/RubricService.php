<?php

namespace App\Services\Rubric;

use App\Repositories\Rubric\RubricRepositoryInterface;
use App\Http\Requests\Rubric\RubricRequest;
use App\Models\Rubric\Rubric;
use App\DataTransferObjects\Rubric\RubricDto;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;

class RubricService
{
    public function __construct(
        protected RubricRepositoryInterface $repository,
    ) {}

    public function index(RubricRequest $request): object
    {
        $dto = RubricDto::fromIndexRequest($request);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->all($dto, $data);
    }

    public function show(Rubric $rubric): object
    {
        return $this->repository->find($rubric->id);
    }

    public function store(RubricRequest $request): object
    {
        $dto = RubricDto::fromStoreRequest($request);
        $this->checkData($dto);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(RubricRequest $request, Rubric $rubric): object
    {
        $dto = RubricDto::fromUpdateRequest($request);
        $this->checkData($dto);
        return $this->repository->update($dto, $rubric->id);
    }

    public function destroy(Rubric $rubric): object
    {
        return $this->repository->delete($rubric->id);
    }

    private function prepareIndexAndStoreData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }

    private function checkData(RubricDto $dto): void
    {
        if ($dto->rubricCriterias)
        {
            $sum = 0;

            foreach ($dto->rubricCriterias as $rubricCriteria)
            {
                $sum += $rubricCriteria['weight'];
            }

            if ($sum != 100)
            {
                throw CustomException::forbidden(ModelName::Rubric, ForbiddenExceptionMessage::RubricCriteriaWeightNotEqual);
            }
        }
    }
}
