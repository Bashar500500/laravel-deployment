<?php

namespace App\Http\Controllers\PageView;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\PageView\PageViewService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PageView\PageViewRequest;
use App\Http\Resources\PageView\PageViewResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\PageView\PageView;

class PageViewController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected PageViewService $service,
    ) {
        parent::__construct($controller);
    }

    public function store(PageViewRequest $request): JsonResponse
    {
        // $this->authorize('store', PageView::class);

        $this->service->store($request);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::PageView)
            ->setData((object) [])
            ->successResponse();
    }
}
