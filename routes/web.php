<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('docs/exceptions/{code}', fn ($code) => $code)->name('docs.exceptions');
