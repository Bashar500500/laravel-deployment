<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepository;
use App\Models\Ticket\Ticket;
use App\DataTransferObjects\Ticket\TicketDto;
use Illuminate\Support\Facades\DB;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    public function __construct(Ticket $ticket) {
        parent::__construct($ticket);
    }

    public function all(TicketDto $dto): object
    {
        return (object) $this->model->with('user')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function allWithFilter(TicketDto $dto): object
    {
        return (object) $this->model->where('category', $dto->category)
            ->with('user')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id)
            ->load('user');
    }

    public function create(TicketDto $dto, array $data): object
    {
        $ticket = DB::transaction(function () use ($dto, $data) {
            $ticket = (object) $this->model->create([
                'user_id' => $data['userId'],
                'date' => $dto->date,
                'subject' => $dto->subject,
                'priority' => $dto->priority,
                'category' => $dto->category,
                'status' => $dto->status,
            ]);

            return $ticket;
        });

        return (object) $ticket;
    }

    public function update(TicketDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $ticket = DB::transaction(function () use ($dto, $model) {
            $ticket = tap($model)->update([
                'date' => $dto->date ? $dto->date : $model->date,
                'subject' => $dto->subject ? $dto->subject : $model->subject,
                'priority' => $dto->priority ? $dto->priority : $model->priority,
                'category' => $dto->category ? $dto->category : $model->category,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $ticket;
        });

        return (object) $ticket->load('user');
    }

    public function delete(int $id): object
    {
        $ticket = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $ticket->load('user');
    }
}
