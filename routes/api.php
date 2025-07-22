<?php

use App\Http\Controllers\ImportUserController;
use Illuminate\Support\Facades\Route;

Route::post('/import-users', [ImportUserController::class, 'import']);
