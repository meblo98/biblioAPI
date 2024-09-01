<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LivreController;
use Illuminate\Support\Facades\Route;


Route::post("login", [AuthController::class, "login"]);
Route::get("livres/archives", [LivreController::class, "trashed"])->middleware("auth");
Route::delete('livres/{id}/force-delete', [LivreController::class, "forceDelete"])->middleware("auth");
Route::post('livres/{id}/restore', [LivreController::class, "restore"])->middleware("auth");
Route::apiResource('livres', LivreController::class)->only('index', 'show');
Route::middleware("auth")->group(
    function () {
        Route::get("logout", [AuthController::class, "logout"]);
        Route::apiResource('livres', LivreController::class)->only('store', 'destroy');
        Route::post('livres/{livre}', [LivreController::class, 'update']);
        Route::get("refresh", [AuthController::class, "refresh"]);
    }
);
