<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'Laravel Mobiler' => app()->version(),
    ];
});
