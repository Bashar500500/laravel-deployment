<?php

namespace App\Repositories\SupportTicket;

use App\Repositories\BaseRepository;
use App\Models\SupportTicket\SupportTicket;
use App\DataTransferObjects\SupportTicket\SupportTicketDto;
use Illuminate\Support\Facades\DB;

class SupportTicketRepository extends BaseRepository implements SupportTicketRepositoryInterface
{
    public function __construct(SupportTicket $supportTicket) {
        parent::__construct($supportTicket);
    }

    public function all(SupportTicketDto $dto, array $data): object
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

    public function allWithFilter(SupportTicketDto $dto): object
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

    public function create(SupportTicketDto $dto, array $data): object
    {
        $supportTicket = DB::transaction(function () use ($dto, $data) {
            $supportTicket = (object) $this->model->create([
                'user_id' => $data['userId'],
                'date' => $dto->date,
                'subject' => $dto->subject,
                'priority' => $dto->priority,
                'category' => $dto->category,
                'status' => $dto->status,
            ]);

            return $supportTicket;
        });

        return (object) $supportTicket->load('user');
    }

    public function update(SupportTicketDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $supportTicket = DB::transaction(function () use ($dto, $model) {
            $supportTicket = tap($model)->update([
                'date' => $dto->date ? $dto->date : $model->date,
                'subject' => $dto->subject ? $dto->subject : $model->subject,
                'priority' => $dto->priority ? $dto->priority : $model->priority,
                'category' => $dto->category ? $dto->category : $model->category,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $supportTicket;
        });

        return (object) $supportTicket->load('user');
    }

    public function delete(int $id): object
    {
        $supportTicket = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $supportTicket;
    }
}
