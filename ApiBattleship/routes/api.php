<?php

use App\Http\Controllers\MissileController;
use App\Http\Controllers\PartieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->prefix('battleship-ia/parties')->group(function () {

    Route::controller(PartieController::class)->group(function () {
        Route::post('/', 'store');
        Route::delete('{partie}', 'destroy');
    });

    Route::controller(MissileController::class)->group(function () {
        Route::post('{partie}/missiles', 'store');
        Route::put('{partie}/missiles/{coordonnees}', 'update');
    });

});
