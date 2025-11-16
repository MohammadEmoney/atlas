<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use Illuminate\Support\Facades\Route;

Route::get('employees', [EmployeeController::class,'index']);

Route::get('leave-requests', [LeaveRequestController::class, 'index']);
Route::post('leave-requests', [LeaveRequestController::class, 'store']);