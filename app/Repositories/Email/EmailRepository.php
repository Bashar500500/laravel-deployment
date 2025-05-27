<?php

namespace App\Repositories\Email;

use App\Repositories\BaseRepository;
use App\Models\Email\Email;
use App\DataTransferObjects\Email\EmailDto;
use Illuminate\Support\Facades\DB;

class EmailRepository extends BaseRepository implements EmailRepositoryInterface
{
    public function __construct(Email $email) {
        parent::__construct($email);
    }

    public function all(EmailDto $dto): object
    {
        return (object) $this->model->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id);
    }

    public function create(EmailDto $dto): object
    {
        $email = DB::transaction(function () use ($dto) {
            $email = (object) $this->model->create([
                'user_id' => $dto->userId,
                'subject' => $dto->subject,
                'body' => $dto->body,
            ]);

            return $email;
        });

        return (object) $email;
    }

    public function delete(int $id): object
    {
        $email = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $email;
    }
}
