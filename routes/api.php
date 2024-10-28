<?php

use App\Http\Controllers\Api\MovieApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/search', [MovieApiController::class, 'search'])->name('api.movies.search');
    Route::get('/movies/{id}', [MovieApiController::class, 'details'])->name('api.movies.details');
    Route::get('/trending', [MovieApiController::class, 'trending'])->name('api.movies.trending');
});
