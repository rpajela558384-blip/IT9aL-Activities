<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/index', function () {
    return view('welcome');
});

Route::resource('employees', EmployeeController::class);
