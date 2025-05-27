<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\ResponseController;

abstract class Controller
{
    public function __construct(
        protected ResponseController $controller
    ) {}
}
