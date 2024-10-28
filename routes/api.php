<?php

use App\Http\Controllers\Api\MovieApiController;

Route::get('/search', [MovieApiController::class, 'search']);
Route::get('/movies/{id}', [MovieApiController::class, 'details']);
Route::get('/trending', [MovieApiController::class, 'trending']);
