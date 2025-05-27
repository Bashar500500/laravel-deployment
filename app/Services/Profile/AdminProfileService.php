<?php

namespace App\Services\Profile;

use App\Repositories\Profile\AdminProfileRepositoryInterface;
use App\Http\Requests\Profile\AdminProfileRequest;
use App\Models\Profile\Profile;
use App\DataTransferObjects\Profile\AdminProfileDto;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;

class AdminProfileService
{
    public function __construct(
        protected AdminProfileRepositoryInterface $repository
    ) {}

    public function index(AdminProfileRequest $request): object
    {
        $dto = AdminProfileDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Profile $profile): object
    {
        return $this->repository->find($profile->id);
    }

    public function store(AdminProfileRequest $request): object
    {
        $dto = AdminProfileDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndUpdateData($dto);
        $profile = Profile::where('user_id', $dto->userId)->first();

        if ($profile) {
            throw CustomException::alreadyExists(ModelName::Profile);
        }

        return $this->repository->create($dto, $data);
    }

    public function update(AdminProfileRequest $request, Profile $profile): object
    {
        $dto = AdminProfileDto::fromUpdateRequest($request);
        $data = $this->prepareStoreAndUpdateData($dto);
        return $this->repository->update($dto, $profile->id, $data);
    }

    public function destroy(Profile $profile): object
    {
        return $this->repository->delete($profile->id);
    }

    public function view(Profile $profile): string
    {
        return $this->repository->view($profile->id);
    }

    public function download(Profile $profile): string
    {
        return $this->repository->download($profile->id);
    }

    public function destroyAttachment(Profile $profile): void
    {
        $this->repository->deleteAttachment($profile->id);
    }

    private function prepareStoreAndUpdateData(AdminProfileDto $dto): array
    {
        $data['permanentAddress']['street'] = $dto->adminProfilePermanentAddressDto->street;
        $data['permanentAddress']['city'] = $dto->adminProfilePermanentAddressDto->city;
        $data['permanentAddress']['state'] = $dto->adminProfilePermanentAddressDto->state;
        $data['permanentAddress']['country'] = $dto->adminProfilePermanentAddressDto->country;
        $data['permanentAddress']['zipCode'] = $dto->adminProfilePermanentAddressDto->zipCode;
        $data['temporaryAddress']['street'] = $dto->adminProfileTemporaryAddressDto->street;
        $data['temporaryAddress']['city'] = $dto->adminProfileTemporaryAddressDto->city;
        $data['temporaryAddress']['state'] = $dto->adminProfileTemporaryAddressDto->state;
        $data['temporaryAddress']['country'] = $dto->adminProfileTemporaryAddressDto->country;
        $data['temporaryAddress']['zipCode'] = $dto->adminProfileTemporaryAddressDto->zipCode;
        return $data;
    }
}
