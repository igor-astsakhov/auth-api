<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post( '/register', [ UserController::class, 'store' ] );
Route::post( '/login', [ UserController::class, 'index' ] );

Route::group( [ 'middleware' => [ 'auth:sanctum', 'ability:user:rw'] ], function() {
    Route::get( '/user', [ UserController::class, 'show' ] );
    Route::delete( '/logout', [ UserController::class, 'destroy' ] );
});

Route::fallback(function(){
    return response()->json([ 'message' => 'Route not found' ], 404);
});
