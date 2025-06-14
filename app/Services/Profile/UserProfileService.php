<?php

namespace App\Services\Profile;

use App\Repositories\Profile\UserProfileRepositoryInterface;
use App\Http\Requests\Profile\UserProfileRequest;
use App\Models\Profile\Profile;
use App\DataTransferObjects\Profile\UserProfileDto;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use Illuminate\Support\Facades\Auth;

class UserProfileService
{
    public function __construct(
        protected UserProfileRepositoryInterface $repository
    ) {}

    public function index(UserProfileRequest $request): object
    {
        $dto = UserProfileDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Profile $profile): object
    {
        return $this->repository->find($profile->id);
    }

    public function profile(): object
    {
        return $this->repository->find(Auth::user()->profile->id);
    }

    public function store(UserProfileRequest $request): object
    {
        $dto = UserProfileDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndUpdateData($dto);
        $profile = Auth::user()->profile;

        if ($profile) {
            throw CustomException::alreadyExists(ModelName::Profile);
        }

        return $this->repository->create($dto, $data);
    }

    public function update(UserProfileRequest $request): object
    {
        $dto = UserProfileDto::fromUpdateRequest($request);
        $data = $this->prepareStoreAndUpdateData($dto);
        return $this->repository->update($dto, Auth::user()->profile->id, $data);
    }

    public function destroy(): object
    {
        return $this->repository->delete(Auth::user()->profile->id);
    }

    public function view(): string
    {
        return $this->repository->view(Auth::user()->profile->id);
    }

    public function download(): string
    {
        return $this->repository->download(Auth::user()->profile->id);
    }

    public function destroyAttachment(): void
    {
        $this->repository->deleteAttachment(Auth::user()->profile->id);
    }

    private function prepareStoreAndUpdateData(UserProfileDto $dto): array
    {
        $data['userId'] = Auth::user()->id;
        $data['permanentAddress']['street'] = $dto->userProfilePermanentAddressDto->street;
        $data['permanentAddress']['city'] = $dto->userProfilePermanentAddressDto->city;
        $data['permanentAddress']['state'] = $dto->userProfilePermanentAddressDto->state;
        $data['permanentAddress']['country'] = $dto->userProfilePermanentAddressDto->country;
        $data['permanentAddress']['zipCode'] = $dto->userProfilePermanentAddressDto->zipCode;
        $data['temporaryAddress']['street'] = $dto->userProfileTemporaryAddressDto->street;
        $data['temporaryAddress']['city'] = $dto->userProfileTemporaryAddressDto->city;
        $data['temporaryAddress']['state'] = $dto->userProfileTemporaryAddressDto->state;
        $data['temporaryAddress']['country'] = $dto->userProfileTemporaryAddressDto->country;
        $data['temporaryAddress']['zipCode'] = $dto->userProfileTemporaryAddressDto->zipCode;
        return $data;
    }
}
