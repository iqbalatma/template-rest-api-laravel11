<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("v1")->name("v1.")->group(function () {

    Route::prefix("auth")->name("auth.")->controller(\App\Http\Controllers\API\V1\Auth\AuthenticationController::class)->group(function () {
        Route::post("", "authenticate")->name("authenticate");
        Route::post("logout", "logout")->name("logout")->middleware("auth.jwt:access");
        Route::post("refresh", "refresh")->name("refresh")->middleware("auth.jwt:refresh");
    });
});
