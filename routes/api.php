<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);

    Route::post('/articles', [ArticleController::class,'store']);
    Route::put('/articles/{article}', [ArticleController::class,'update']);
    Route::delete('/articles/{article}', [ArticleController::class,'destroy']);
});