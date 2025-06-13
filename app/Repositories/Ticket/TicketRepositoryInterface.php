<?php

namespace App\Repositories\Ticket;

use App\DataTransferObjects\Ticket\TicketDto;

interface TicketRepositoryInterface
{
    public function all(TicketDto $dto): object;

    public function allWithFilter(TicketDto $dto): object;

    public function find(int $id): object;

    public function create(TicketDto $dto, array $data): object;

    public function update(TicketDto $dto, int $id): object;

    public function delete(int $id): object;
}
