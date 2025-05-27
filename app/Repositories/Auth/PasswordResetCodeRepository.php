<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;
use App\Models\Auth\PasswordResetCode;
use App\DataTransferObjects\Auth\PasswordResetCodeDto;
use Illuminate\Support\Facades\DB;

class PasswordResetCodeRepository extends BaseRepository implements PasswordResetCodeRepositoryInterface
{
    public function __construct(PasswordResetCode $passwordResetCode) {
        parent::__construct($passwordResetCode);
    }

    public function updateOrCreate(PasswordResetCodeDto $dto): object
    {
        $passwordResetCode = DB::transaction(function () use ($dto) {
            $passwordResetCode = $this->model->updateOrCreate([
                'email' => $dto->email,
                'code' => $dto->code,
            ]);

            return $passwordResetCode;
        });

        return (object) $passwordResetCode;
    }

    public function delete(int $id): object
    {
        DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) [];
    }
}
