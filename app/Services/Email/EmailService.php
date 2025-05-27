<?php

namespace App\Services\Email;

use App\Repositories\Email\EmailRepositoryInterface;
use App\Http\Requests\Email\EmailRequest;
use App\Models\Email\Email;
use App\DataTransferObjects\Email\EmailDto;

class EmailService
{
    public function __construct(
        protected EmailRepositoryInterface $repository,
    ) {}

    public function index(EmailRequest $request): object
    {
        $dto = EmailDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Email $email): object
    {
        return $this->repository->find($email->id);
    }

    public function store(EmailRequest $request): object
    {
        $dto = EmailDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function destroy(Email $email): object
    {
        return $this->repository->delete($email->id);
    }
}
