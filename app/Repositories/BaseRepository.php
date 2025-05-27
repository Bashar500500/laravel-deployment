<?php

namespace App\Repositories;

use stdClass;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CustomException;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(
        protected Model $model
    ) {}

    // public function all(): object
    // {
    //     return (object) $this->model->all();
    // }

    public function find(int $id): object
    {
        $model = (object) $this->model->find($id);

        if (! $model)
        {
            throw CustomException::notFound(class_basename($model));
        }

        return (object) $model;
    }

    public function delete(int $id): object
    {
        $model = (object) $this->find($id);

        return (object) tap($model)->delete();
    }
}
