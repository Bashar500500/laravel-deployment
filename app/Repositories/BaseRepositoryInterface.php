<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    // public function all(): object;

    public function find(int $id): object;

    public function delete(int $id): object;
}
