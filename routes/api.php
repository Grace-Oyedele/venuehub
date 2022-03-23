<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

/*AUTH ROUTES*/
Route::prefix("auth")->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware("auth:sanctum");
});


/*CLIENT ROUTES*/
Route::prefix("client")->middleware("auth:sanctum")->group(function () {
    Route::prefix("venue")->group(function () {
        Route::post("images", [VenueController::class, "uploadImages"]);
        Route::post("image/featured/{venue_image_id}", [VenueController::class, "makeFeatured"]);
        Route::apiResource("", VenueController::class);
    });

    Route::prefix("booking")->group(function () {
        Route::get("", [BookingController::class, "index"]); //list a logged user bookins
        Route::post("", [BookingController::class, "book"]); //book a venue
    });
});


/*ADMIN ROUTES*/
Route::prefix("admin")->middleware(["auth:sanctum", "role"])->group(function () {
    Route::prefix("venue")->group(function () {
        Route::get("", [VenueController::class, "index"]);
        Route::get("{id}", [VenueController::class, "show"]);
        Route::get("toggle-venue-status/{venue_id}", [AdminController::class, "toggleStatus"]);
    });

    Route::prefix("booking")->group(function () {
        Route::get("", [BookingController::class, "index"]);
    });
});
