<?php

namespace App\Repositories\WikiRating;

use App\Repositories\BaseRepository;
use App\Models\WikiRating\WikiRating;
use App\DataTransferObjects\WikiRating\WikiRatingDto;
use Illuminate\Support\Facades\DB;

class WikiRatingRepository extends BaseRepository implements WikiRatingRepositoryInterface
{
    public function __construct(WikiRating $wikiRating) {
        parent::__construct($wikiRating);
    }

    public function all(WikiRatingDto $dto): object
    {
        return (object) $this->model->where('wiki_id', $dto->wikiId)
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
        return (object) parent::find($id)->load('user');
    }

    public function create(WikiRatingDto $dto, array $data): object
    {
        $wikiRating = DB::transaction(function () use ($dto, $data) {
            $wikiRating = (object) $this->model->create([
                'wiki_id' => $dto->wikiId,
                'user_id' => $data['userId'],
                'rating' => $dto->rating,
            ]);

            return $wikiRating;
        });

        return (object) $wikiRating->load('user');
    }

    public function update(WikiRatingDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $wikiRating = DB::transaction(function () use ($dto, $model) {
            $wikiRating = tap($model)->update([
                'rating' => $dto->rating ? $dto->rating : $model->rating,
            ]);

            return $wikiRating;
        });

        return (object) $wikiRating->load('user');
    }

    public function delete(int $id): object
    {
        $wikiRating = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $wikiRating;
    }
}
