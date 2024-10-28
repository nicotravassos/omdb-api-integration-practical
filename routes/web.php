<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('movies.search');
});

Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/movies/trending', [MovieController::class, 'trending'])->name('movies.trending');
Route::get('/movies/{id}', [MovieController::class, 'details'])->name('movies.details');

