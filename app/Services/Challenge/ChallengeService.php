<?php

namespace App\Services\Challenge;

use App\Repositories\Challenge\ChallengeRepositoryInterface;
use App\Http\Requests\Challenge\ChallengeRequest;
use App\Models\Challenge\Challenge;
use App\DataTransferObjects\Challenge\ChallengeDto;
use Illuminate\Support\Facades\Auth;

class ChallengeService
{
    public function __construct(
        protected ChallengeRepositoryInterface $repository,
    ) {}

    public function index(ChallengeRequest $request): object
    {
        $dto = ChallengeDto::fromIndexRequest($request);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->all($dto, $data);
    }

    public function show(Challenge $challenge): object
    {
        return $this->repository->find($challenge->id);
    }

    public function store(ChallengeRequest $request): object
    {
        $dto = ChallengeDto::fromStoreRequest($request);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(ChallengeRequest $request, Challenge $challenge): object
    {
        $dto = ChallengeDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $challenge->id);
    }

    public function destroy(Challenge $challenge): object
    {
        return $this->repository->delete($challenge->id);
    }

    public function join(Challenge $challenge): void
    {
        $data = $this->prepareJoinAndLeaveData();
        // $student = $data['student'];

        // if (!is_null($student->challengeUsers->where('challenge_id', $challenge->id)->first()))
        // {
        //     throw CustomException::forbidden(ModelName::Group, ForbiddenExceptionMessage::GroupJoinTwice);
        // }
        // else if ($group->students->count() == $group->capacity_max)
        // {
        //     throw CustomException::forbidden(ModelName::Group, ForbiddenExceptionMessage::GroupCapacityMax);
        // }

        $this->repository->join($challenge->id, $data);
    }

    public function leave(Challenge $challenge): void
    {
        $data = $this->prepareJoinAndLeaveData();
        // $student = $data['student'];

        // if (is_null($student->userCourseGroups->where('group_id', $group->id)->first()))
        // {
        //     throw CustomException::forbidden(ModelName::Group, ForbiddenExceptionMessage::GroupNotJoined);
        // }

        $this->repository->leave($challenge->id, $data);
    }

    private function prepareIndexAndStoreData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }

    private function prepareJoinAndLeaveData(): array
    {
        return [
            'student' => Auth::user(),
        ];
    }
}
