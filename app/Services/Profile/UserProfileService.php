<?php

namespace App\Services\Profile;

use App\Factories\Profile\UserProfileRepositoryFactory;
use App\Http\Requests\Profile\UserProfileRequest;
use App\Models\Profile\Profile;
use App\DataTransferObjects\Profile\UserProfileDto;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use Illuminate\Support\Facades\Auth;

class UserProfileService
{
    public function __construct(
        protected UserProfileRepositoryFactory $factory,
    ) {}

    public function index(UserProfileRequest $request): object
    {
        $dto = UserProfileDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexData();
        $repository = $this->factory->make($role[0]);
        return match ($dto->courseId) {
            null => $repository->all($dto, $data),
            default => $repository->allWithFilter($dto, $data),
        };
    }

    public function show(Profile $profile): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($profile->id);
    }

    public function profile(): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find(Auth::user()->profile->id);
    }

    public function store(UserProfileRequest $request): object
    {
        $dto = UserProfileDto::fromStoreRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareStoreAndUpdateData($dto);
        $repository = $this->factory->make($role[0]);
        $profile = Auth::user()->profile;

        if ($profile) {
            throw CustomException::alreadyExists(ModelName::Profile);
        }

        return $repository->create($dto, $data);
    }

    public function update(UserProfileRequest $request): object
    {
        $dto = UserProfileDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareStoreAndUpdateData($dto);
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, Auth::user()->profile->id, $data);
    }

    public function destroy(): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete(Auth::user()->profile->id);
    }

    public function view(): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->view(Auth::user()->profile->id);
    }

    public function download(): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->download(Auth::user()->profile->id);
    }

    public function destroyAttachment(): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->deleteAttachment(Auth::user()->profile->id);
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

    private function prepareIndexData(): array
    {
        return [
            'user' => Auth::user(),
        ];
    }
}
