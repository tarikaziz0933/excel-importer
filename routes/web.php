<?php

use App\Http\Controllers\ImportUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('welcome');
// })->name('home');

// Route::post('/import-users', [ImportUserController::class, 'import']);
// Route::get('/import-users', [ImportUserController::class, 'import']);


Route::get('/', function () {
    return Inertia::render('ImportUsers');
});

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', function () {
//         return Inertia::render('dashboard');
//     })->name('dashboard');
// });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
