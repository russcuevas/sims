<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// AUTH ROUTES
Route::get('/login', [AuthController::class, 'LoginPage'])->name('login.page');
Route::get('/change_password', [AuthController::class, 'ChangePasswordPage'])->name('change.password.page');


// ADMIN ROUTES
Route::get('/admin/dashboard', [DashboardController::class, 'AdminDashboardPage'])->name('admin.dashboard.page');
Route::get('/admin/user_management', [UserController::class, 'AdminUserManagementPage'])->name('admin.user.management.page');
