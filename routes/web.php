<?php

use App\Http\Controllers\admin\ArchiveController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\DeliveryController;
use App\Http\Controllers\admin\DeliveryStatusController;
use App\Http\Controllers\admin\LogsController;
use App\Http\Controllers\admin\PendingDeliveryController;
use App\Http\Controllers\admin\ProcessController;
use App\Http\Controllers\admin\ProfileManagement;
use App\Http\Controllers\admin\ReturnItemController;
use App\Http\Controllers\admin\SalesReportController;
use App\Http\Controllers\admin\StockController;
use App\Http\Controllers\admin\StockInController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ViewAvailableCarsController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\delivery\DeliveryDashboardController;
use App\Http\Controllers\delivery\DeliveryDeliveryStatusController;
use App\Http\Controllers\delivery\DeliveryProfileManagement;
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

Route::post('/verify-otp', [AuthController::class, 'VerifyOtp'])->name('verify.otp');
Route::get('/reset-password-form', function () {
    return view('auth.reset-password');
})->name('reset.password.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');
Route::post('/logout', [AuthController::class, 'LogoutRequest'])->name('logout.request');



// ADMIN ROUTES
Route::get('/admin/dashboard', [DashboardController::class, 'AdminDashboardPage'])->name('admin.dashboard.page');
Route::get('/admin/monthly-sales', [DashboardController::class, 'AdminGetMonthlySales'])
    ->name('admin.monthly.sales');
Route::get('/admin/available-products', [DashboardController::class, 'AdminGetAvailableProductsByType'])
    ->name('admin.get.available.product');

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
Route::post('/admin/stock/archive-product/{id}', [StockController::class, 'StockArchiveProduct'])->name('admin.stock.archive.product');
Route::post('/admin/stock/update-product/{id}', [StockController::class, 'StockUpdateProduct'])->name('admin.stock.update.product');
Route::get('/admin/purchase-order', [StockController::class, 'StockPurchaseOrderPage'])->name('admin.purchase.order.page');
Route::post('/admin/submit/purchase-order', [StockController::class, 'StockSubmitPO'])->name('admin.stock.submit.po');
Route::get('/admin/purchase-order/view/{po_number}', [StockController::class, 'AdminViewPO'])
    ->name('admin.view.po');


// ADMIN PROCESS MANAGEMENT
Route::get('/admin/process_management', [ProcessController::class, 'ProcessManagementPage'])->name('admin.process.management.page');
Route::post('/admin/batch/add-raw-products', [ProcessController::class, 'AdminAddBatchFetchRawProducts'])
    ->name('admin.batch.add.raw.products');
Route::get('/admin/batch-product-raw-details/delete/{id}', [ProcessController::class, 'AdminRemoveBatchRawProduct'])->name('admin.batch.raw.product.remove');
Route::post('/admin/add-batch-multiple-product', [ProcessController::class, 'AdminAddBatchMultipleProduct'])->name('admin.add.batch.multiple.product');
Route::post('/admin/add-batch-finish-product', [ProcessController::class, 'AddBatchFinishProduct'])->name('admin.batch.finish.product');
Route::post('/admin/submit-finish-products', [ProcessController::class, 'AdminFinishProductSubmit'])->name('admin.finish.product.submit');
Route::get('/admin/batch-product-finish/delete/{id}', [ProcessController::class, 'AdminRemoveFinishProduct'])->name('admin.batch.finish.product.remove');
Route::post('/admin/archive-history-finish-product/{transactId}', [ProcessController::class, 'AdminArchiveHistoryFinishProduct'])->name('admin.archive.history.finish.product');


// ADMIN DELIVERY PREPARING MANAGEMENT
Route::get('/admin/delivery_management', [DeliveryController::class, 'AdminDeliveryPage'])->name('admin.delivery.management.page');
Route::post('/admin/delivery/add-store', [DeliveryController::class, 'AdminDeliveryAddStore'])->name('admin.delivery.add.store');
Route::post('/admin/delivery/add-car', [DeliveryController::class, 'AdminAddCar'])->name('admin.delivery.add.car');
Route::post('/admin/delivery/add-multiple-finish/', [DeliveryController::class, 'AdminDeliverySubmitBatch'])->name('admin.delivery.submit.batch');
Route::get('/admin/delivery/remove-product/{id}', [DeliveryController::class, 'AdminDeliveryRemoveProduct'])
    ->name('admin.delivery.remove.product');
Route::post('/admin/delivery/add', [DeliveryController::class, 'AdminDeliveryAdd'])->name('admin.delivery.add');
Route::get('/delivery/view/{transact_id}', [DeliveryController::class, 'AdminViewDeliveryOrder'])->name('admin.delivery.view');
Route::post('/admin/delivery/archive/{transact_id}', [DeliveryController::class, 'AdminArchiveDeliveryOrder'])->name('admin.delivery.archive');


// ADMIN VIEW AVAILABLE CARS
Route::get('/admin/view/available-cars', [ViewAvailableCarsController::class, 'ViewAvailableCarsPage'])->name('admin.view.available.cars');

// ADMIN DELIVERY PENDING MANAGEMENT
Route::get('/admin/pending_delivery', [PendingDeliveryController::class, 'AdminPendingDeliveryPage'])->name('admin.pending.management.page');
Route::post('/admin/pending/marked-status/{transact_id}', [PendingDeliveryController::class, 'AdminMarkStatusDelivery'])->name('admin.delivery.mark.status');

// ADMIN DELIVERY STATUS MANAGEMENT
Route::get('/admin/delivery_status', [DeliveryStatusController::class, 'AdminDeliveryStatusPage'])->name('admin.delivery.status.page');

// ADMIN RETURN ITEM MANAGEMENT
Route::get('/admin/return_item', [ReturnItemController::class, 'AdminReturnItemPage'])->name('admin.return.item.page');
Route::post('/admin//return/add-multiple-finish', [ReturnItemController::class, 'AdminBatchReturnProductSubmit'])->name('admin.return.submit.item');
Route::post('/admin/return/submit-items', [ReturnItemController::class, 'AdminAddReturnItem'])->name('admin.return.submit');
Route::get('/admin/return/delete/{id}', [ReturnItemController::class, 'AdminDeleteBatchReturnProduct'])->name('admin.batch-return-item.delete');

// ADMIN SALES REPORT MANAGEMENT
Route::get('/admin/sales/report', [SalesReportController::class, 'AdminSalesReportPage'])->name('admin.sales.management.page');
Route::post('/admin/sales/add-transaction', [SalesReportController::class, 'AdminTransactionAdd'])->name('admin.sales.request.transaction');
Route::post('/admin/sales/report/archive/{id}', [SalesReportController::class, 'AdminTransactionArchive'])
    ->name('admin.transaction.archive');

// ADMIN LOGS MANAGEMENT
Route::get('/admin/logs', [LogsController::class, 'AdminLogsPage'])->name('admin.logs.page');

// ADMIN ARCHIVE MANAGEMENT
Route::get('/admin/archive', [ArchiveController::class, 'AdminArchivePage'])->name('admin.archive.page');
Route::post('/admin/employees/restore/{id}', [ArchiveController::class, 'AdminRestoreEmployee'])->name('admin.employees.restore');
Route::get('/admin/archive/stocks', [ArchiveController::class, 'AdminArchiveStocksPage'])->name('admin.archive.stocks.page');
Route::post('/admin/stocks/restore/{id}', [ArchiveController::class, 'AdminRestoreStocks'])->name('admin.stocks.restore');
Route::get('/admin/archive/stock_in', [ArchiveController::class, 'AdminArchiveStockInPage'])->name('admin.archive.stock.in.page');
Route::post('/admin/stockin/restore/{transact_id}', [ArchiveController::class, 'AdminRestoreStockIn'])->name('admin.stockin.restore');
Route::get('/admin/archive/process', [ArchiveController::class, 'AdminArchiveProcessPage'])->name('admin.archive.process.page');
Route::post('/admin/process/restore/{transact_id}', [ArchiveController::class, 'AdminRestoreProcess'])->name('admin.process.restore');
Route::get('/admin/archive/delivery', [ArchiveController::class, 'AdminArchiveDeliveryPage'])->name('admin.archive.delivery.page');
Route::post('/admin/delivery/restore/{transact_id}', [ArchiveController::class, 'AdminRestoreDelivery'])->name('admin.delivery.restore');
Route::get('/admin/archive/sales', [ArchiveController::class, 'AdminArchiveSalesPage'])->name('admin.archive.sales.page');
Route::post('/admin/archive/sales/restore/{id}', [ArchiveController::class, 'AdminRestoreSales'])->name('admin.sales.restore');

// ADMIN EDIT PROFILE
Route::get('/admin/profile/update', [ProfileManagement::class, 'AdminProfileManagementPage'])->name('admin.profile.page');
Route::post('/admin/profile/update/request', [ProfileManagement::class, 'AdminUpdateProfile'])->name('admin.profile.update.request');

// DELIVERY ROUTES

// DELIVERY PENDING MANAGEMENT
Route::get('/delivery/dashboard', [DeliveryDashboardController::class, 'DeliveryPendingDeliveryPage'])->name('delivery.dashboard.page');
Route::post('/delivery/pending/marked-status/{transact_id}', [DeliveryDashboardController::class, 'DeliveryMarkStatusDelivery'])->name('delivery.delivery.mark.status');
Route::get('/delivery/delivery/view/{transact_id}', [DeliveryDashboardController::class, 'DeliveryViewDeliveryOrder'])->name('delivery.delivery.view');


// DELIVERY DELIVERY STATUS
Route::get('/delivery/delivery_status', [DeliveryDeliveryStatusController::class, 'DeliveryDeliveryStatusPage'])->name('delivery.delivery.status.page');


// DELIVERY EDIT PROFILE
Route::get('/delivery/profile/update', [DeliveryProfileManagement::class, 'DeliveryProfileManagementPage'])->name('delivery.profile.page');
Route::post('/delivery/profile/update/request', [DeliveryProfileManagement::class, 'DeliveryUpdateProfile'])->name('delivery.profile.update.request');
