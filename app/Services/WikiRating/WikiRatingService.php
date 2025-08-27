<?php

namespace App\Services\WikiRating;

use App\Repositories\WikiRating\WikiRatingRepositoryInterface;
use App\Http\Requests\WikiRating\WikiRatingRequest;
use App\Models\WikiRating\WikiRating;
use App\DataTransferObjects\WikiRating\WikiRatingDto;
use Illuminate\Support\Facades\Auth;

class WikiRatingService
{
    public function __construct(
        protected WikiRatingRepositoryInterface $repository,
    ) {}

    public function index(WikiRatingRequest $request): object
    {
        $dto = WikiRatingDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(WikiRating $wikiRating): object
    {
        return $this->repository->find($wikiRating->id);
    }

    public function store(WikiRatingRequest $request): object
    {
        $dto = WikiRatingDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(WikiRatingRequest $request, WikiRating $wikiRating): object
    {
        $dto = WikiRatingDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $wikiRating->id);
    }

    public function destroy(WikiRating $wikiRating): object
    {
        return $this->repository->delete($wikiRating->id);
    }

    private function prepareStoreData(): array
    {
        return [
            'userId' => Auth::user()->id,
        ];
    }
}
