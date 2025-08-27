<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\TestController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TodoController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/test', [TestController::class, 'getData']);

Route::apiResource('employees', EmployeeController::class);
Route::get('employees/{employee}/todos', [EmployeeController::class,'todos']);

Route::apiResource('todos', TodoController::class);
Route::post('todos/{todo}/assign', [TodoController::class,'assignEmployee']);
Route::get('todos-filter', [TodoController::class,'filter']);
