<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {


    Route::post('add/profile', [ProfileController::class, 'store']);
    Route::post('update/profile', [ProfileController::class, 'update']);
    Route::get('get/profile', [ProfileController::class, 'show']);

    Route::post('insert', [TaskController::class, 'store']);
    Route::put('task/update/{id}', [TaskController::class, 'update']);
    Route::delete('delete/{id}', [TaskController::class, 'destroy']);
    Route::get('user/tasks', [TaskController::class, 'show']);
    Route::get('user/tasks/priority', [TaskController::class, 'display_priority']);
    Route::post('user/tasks/favorites', [TaskController::class, 'favorites_tasks']);
    Route::post('task/{id}/categories', [CategoryTaskController::class, 'store']);
    Route::post('task/categories/{name}', [CategoryTaskController::class, 'store_by_name']);

    Route::delete('user/logout', [UserController::class, 'logout']);

    Route::get('user_and_profile/get', [UserController::class, 'getuser']);
});

Route::middleware(['auth:sanctum', 'check.role'])->group(function () {
    Route::post('category/add', [CategoryController::class, 'store']);

    Route::get('all_tasks', [TaskController::class, 'index']);
    Route::get('users/all', [UserController::class, 'all_users']);
    Route::get('category/all_tasks', [CategoryTaskController::class, 'all_tasks_in_category']);
});




Route::post('user/create_account', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'Login']);
