<?php

namespace App\Repositories\SupportTicket;

use App\DataTransferObjects\SupportTicket\SupportTicketDto;

interface SupportTicketRepositoryInterface
{
    public function all(SupportTicketDto $dto, array $data): object;

    public function allWithFilter(SupportTicketDto $dto): object;

    public function find(int $id): object;

    public function create(SupportTicketDto $dto, array $data): object;

    public function update(SupportTicketDto $dto, int $id): object;

    public function delete(int $id): object;
}
