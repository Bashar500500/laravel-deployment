<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;
use App\Models\User\User;
use App\DataTransferObjects\Auth\RegisterDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterRepository extends BaseRepository implements RegisterRepositoryInterface
{
    public function __construct(User $user) {
        parent::__construct($user);
    }

    public function create(RegisterDto $dto): object
    {
        $user = DB::transaction(function () use ($dto) {
            $user = $this->model->create([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),

            ]);

            $user['role'] = $user->assignRole($dto->role);
            return $user;
        });

        return (object) $user;
    }
}
