<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\StockController;
use App\Http\Controllers\admin\StockInController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\auth\AuthController;
use Illuminate\Support\Facades\DB;
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


Route::post('/change_password/request', [AuthController::class, 'ChangePasswordRequest'])->name('change.password.request');
Route::get('/reset-password/{token}', function ($token) {
    $record = DB::table('change_passwords')->where('link', $token)->first();
    if (!$record) {
        return view('auth.verify-otp', ['token' => null, 'expired' => true]);
    }

    return view('auth.verify-otp', ['token' => $token, 'expired' => false]);
})->name('reset.password.otp.form');


// Handle OTP verification
Route::post('/verify-otp', [AuthController::class, 'VerifyOtp'])->name('verify.otp');

// Show password reset form after OTP verified
Route::get('/reset-password-form', function () {
    return view('auth.reset-password');
})->name('reset.password.form');

// Handle final password update
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');




Route::post('/logout', [AuthController::class, 'LogoutRequest'])->name('logout.request');



// ADMIN ROUTES
Route::get('/admin/dashboard', [DashboardController::class, 'AdminDashboardPage'])->name('admin.dashboard.page');

// ADMIN USER MANAGEMENT
Route::get('/admin/user_management', [UserController::class, 'AdminUserManagementPage'])->name('admin.user.management.page');
Route::post('/admin/user_management/add', [UserController::class, 'AdminAddUser'])->name('admin.user.add');
Route::post('/admin/user_management/archive/{id}', [UserController::class, 'AdminArchiveUser'])->name('admin.user.archive');
Route::put('/admin/user_management/update/{id}', [UserController::class, 'AdminUpdateUser'])->name('admin.user.update');

// ADMIN STOCK IN MANAGEMENT
Route::get('/admin/stock_in', [StockInController::class, 'StockInPage'])->name('admin.stock.in.page');
Route::post('/admin/stock_in/add_product', [StockInController::class, 'AdminAddProduct'])->name('admin.stock.in.add.product');
Route::post('/admin/stock_in/add_supplier', [StockInController::class, 'AdminAddSupplier'])->name('admin.stock.in.add.supplier');
Route::post('/admin/stock_in/add_batch_product_details', [StockInController::class, 'AdminAddBatchProductDetails'])->name('admin.stock.in.add.batch.product.details');
Route::post('/admin/batch-product-details/{id}/quantity', [StockInController::class, 'updateQuantity']);
Route::post('/admin/products/{productId}/update-price', [StockInController::class, 'UpdateProductPrice'])->name('admin.product.update.price');
Route::get('/admin/batch-product-details/delete/{id}', [StockInController::class, 'AdminRemoveBatchProduct'])->name('admin.batch.product.remove');
Route::post('/admin/raw-stocks-request', [StockInController::class, 'AdminRawStocksRequest'])->name('admin.raw.stocks.request');
Route::post('/admin/archive-raw-stock/{transactId}', [StockInController::class, 'ArchiveRawStock'])->name('admin.archive.raw.stock');


// ADMIN STOCK MANAGEMENT
Route::get('/admin/stock_management', [StockController::class, 'StockManagementPage'])->name('admin.stock.management.page');
