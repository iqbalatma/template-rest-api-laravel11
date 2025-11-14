<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("v1")->name("v1.")->group(function () {
    Route::prefix("auth")->name("auth.")->controller(\App\Http\Controllers\API\V1\Auth\AuthenticationController::class)->group(function () {
        Route::post("", "authenticate")->name("authenticate");
        Route::post("logout", "logout")->name("logout")->middleware("auth.jwt:ACCESS");
        Route::post("refresh", "refresh")->name("refresh")->middleware("auth.jwt:REFRESH");
    });

    Route::prefix("forgot-password")->name("forgot.password")->controller(\App\Http\Controllers\API\V1\Auth\ForgotPasswordController::class)->group(function (){
        Route::post("/request", "request")->name("request");
        Route::post("/reset", "reset")->name("reset");
    });

    Route::middleware("auth.jwt:ACCESS")->group(function () {
        Route::prefix("management")->name("management.")->group(function () {
            Route::prefix("users")->name("users.")->controller(\App\Http\Controllers\API\V1\Management\UserController::class)->group(function () {
                Route::get("", "index")->name("index");
            });

            Route::prefix("permissions")->name("permissions.")->controller(\App\Http\Controllers\API\V1\Management\PermissionController::class)->group(function () {
                Route::get("", "index")->name("index")->middleware("permission:" . \App\Enums\Permission::MANAGEMENT_PERMISSIONS_SHOW->value);
            });

            Route::prefix("roles")->name("roles.")->controller(\App\Http\Controllers\API\V1\Management\RoleController::class)->group(function () {
                Route::get("", "index")->name("index")->middleware("permission:" . \App\Enums\Permission::MANAGEMENT_ROLES_SHOW->value);
                Route::get("{id}", "show")->name("show")->middleware("permission:" . \App\Enums\Permission::MANAGEMENT_ROLES_SHOW->value);;
                Route::post("", "store")->name("store")->middleware("permission:" . \App\Enums\Permission::MANAGEMENT_ROLES_STORE->value);;
                Route::patch("{id}", "update")->name("update")->middleware("permission:" . \App\Enums\Permission::MANAGEMENT_ROLES_UPDATE->value);;
                Route::delete("{id}", "destroy")->name("destroy")->middleware("permission:" . \App\Enums\Permission::MANAGEMENT_ROLES_DESTROY->value);;
            });
        });

        Route::prefix("profiles")->name("profiles.")->controller(\App\Http\Controllers\API\V1\ProfileController::class)->group(function () {
            Route::get("", "show")->name("show");
            Route::patch("", "update")->name("update");
            Route::patch("password", "updatePassword")->name("update.password");
        });
    });

    Route::prefix("master")->name("master.")->group(function () {
        Route::middleware("auth.jwt")->group(function () {
            Route::get("roles", \App\Http\Controllers\API\V1\Master\RoleController::class);
            Route::get("permissions", \App\Http\Controllers\API\V1\Master\PermissionController::class);
            Route::get("genders", \App\Http\Controllers\API\V1\Master\GenderController::class);
        });
    });
});
