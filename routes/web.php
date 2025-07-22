<?php

use App\Http\Controllers\ImportUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('ImportUsers');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
