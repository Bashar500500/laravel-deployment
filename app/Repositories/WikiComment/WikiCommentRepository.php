<?php

namespace App\Repositories\WikiComment;

use App\Repositories\BaseRepository;
use App\Models\WikiComment\WikiComment;
use App\DataTransferObjects\WikiComment\WikiCommentDto;
use Illuminate\Support\Facades\DB;

class WikiCommentRepository extends BaseRepository implements WikiCommentRepositoryInterface
{
    public function __construct(WikiComment $wikiComment) {
        parent::__construct($wikiComment);
    }

    public function all(WikiCommentDto $dto): object
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

    public function create(WikiCommentDto $dto, array $data): object
    {
        $wikiComment = DB::transaction(function () use ($dto, $data) {
            $wikiComment = (object) $this->model->create([
                'wiki_id' => $dto->wikiId,
                'user_id' => $data['userId'],
                'comment' => $dto->comment,
            ]);

            return $wikiComment;
        });

        return (object) $wikiComment->load('user');
    }

    public function update(WikiCommentDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $wikiComment = DB::transaction(function () use ($dto, $model) {
            $wikiComment = tap($model)->update([
                'comment' => $dto->comment ? $dto->comment : $model->comment,
            ]);

            return $wikiComment;
        });

        return (object) $wikiComment->load('user');
    }

    public function delete(int $id): object
    {
        $wikiComment = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $wikiComment;
    }
}
