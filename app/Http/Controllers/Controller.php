<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\ResponseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ResponseController $controller
    ) {}
}
