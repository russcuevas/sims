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

// DEFAULT ROUTES
Route::get('/', [AuthController::class, 'LoginPage'])->name('login.page');


// AUTH ROUTES
Route::get('/login', [AuthController::class, 'LoginPage'])->name('login.page');
Route::post('/login_request', [AuthController::class, 'LoginRequest'])->name('login.request');
Route::get('/change_password', [AuthController::class, 'ChangePasswordPage'])->name('change.password.page');
Route::post('/logout', [AuthController::class, 'LogoutRequest'])->name('logout.request');



// ADMIN ROUTES
Route::get('/admin/dashboard', [DashboardController::class, 'AdminDashboardPage'])->name('admin.dashboard.page');

// ADMIN USER MANAGEMENT
Route::get('/admin/user_management', [UserController::class, 'AdminUserManagementPage'])->name('admin.user.management.page');
Route::post('/admin/user_management/add', [UserController::class, 'AdminAddUser'])->name('admin.user.add');
Route::post('/admin/user_management/archive/{id}', [UserController::class, 'AdminArchiveUser'])->name('admin.user.archive');
Route::put('/admin/user_management/update/{id}', [UserController::class, 'AdminUpdateUser'])->name('admin.user.update');
