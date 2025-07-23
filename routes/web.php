<?php

// ADMIN CONTROLLER
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

// DELIVERY CONTROLLER
use App\Http\Controllers\delivery\DeliveryDashboardController;
use App\Http\Controllers\delivery\DeliveryDeliveryStatusController;
use App\Http\Controllers\delivery\DeliveryProfileManagement;
use App\Http\Controllers\manager\ManagerArchiveController;
// MANAGER CONTROLLER
use App\Http\Controllers\manager\ManagerDashboardController;
use App\Http\Controllers\manager\ManagerDeliveryStatusController;
use App\Http\Controllers\manager\ManagerPendingDeliveryController;
use App\Http\Controllers\manager\ManagerProcessController;
use App\Http\Controllers\manager\ManagerProfileManagement;
use App\Http\Controllers\manager\ManagerSalesReportController;
use App\Http\Controllers\manager\ManagerStockController;
use App\Http\Controllers\manager\ManagerStockInController;
use App\Http\Controllers\supervisor\SupervisorDashboardController;
use App\Http\Controllers\supervisor\SupervisorStockController;
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










// MANAGER ROUTES
Route::get('/manager/dashboard', [ManagerDashboardController::class, 'ManagerDashboardPage'])->name('manager.dashboard.page');
Route::get('/manager/monthly-sales', [ManagerDashboardController::class, 'ManagerGetMonthlySales'])
    ->name('manager.monthly.sales');
Route::get('/manager/available-products', [ManagerDashboardController::class, 'ManagerGetAvailableProductsByType'])
    ->name('manager.get.available.product');


// MANAGER STOCK IN
Route::get('/manager/stock_in', [ManagerStockInController::class, 'ManagerStockInPage'])->name('manager.stock.in.page');
Route::post('/manager/stock_in/add_product', [ManagerStockInController::class, 'ManagerAddProduct'])->name('manager.stock.in.add.product');
Route::post('/manager/stock_in/add_supplier', [ManagerStockInController::class, 'ManagerAddSupplier'])->name('manager.stock.in.add.supplier');
Route::post('/manager/stock_in/add_batch_product_details', [ManagerStockInController::class, 'ManagerAddBatchProductDetails'])->name('manager.stock.in.add.batch.product.details');
Route::post('/manager/batch-product-details/{id}/quantity', [ManagerStockInController::class, 'ManagerupdateQuantity']);
Route::post('/manager/products/{productId}/update-price', [ManagerStockInController::class, 'ManagerUpdateProductPrice'])->name('manager.product.update.price');
Route::get('/manager/batch-product-details/delete/{id}', [ManagerStockInController::class, 'ManagerRemoveBatchProduct'])->name('manager.batch.product.remove');
Route::post('/manager/raw-stocks-request', [ManagerStockInController::class, 'ManagerRawStocksRequest'])->name('manager.raw.stocks.request');
Route::post('/manager/archive-raw-stock/{transactId}', [ManagerStockInController::class, 'ManagerArchiveRawStock'])->name('manager.archive.raw.stock');


// MANAGER STOCK MANAGEMENT
Route::get('/manager/stock_management', [ManagerStockController::class, 'ManagerStockManagementPage'])->name('manager.stock.management.page');
Route::post('/manager/stock/archive-product/{id}', [ManagerStockController::class, 'ManagerStockArchiveProduct'])->name('manager.stock.archive.product');
Route::post('/manager/stock/update-product/{id}', [ManagerStockController::class, 'ManagerStockUpdateProduct'])->name('manager.stock.update.product');
Route::get('/manager/purchase-order', [ManagerStockController::class, 'ManagerStockPurchaseOrderPage'])->name('manager.purchase.order.page');
Route::post('/manager/submit/purchase-order', [ManagerStockController::class, 'ManagerStockSubmitPO'])->name('manager.stock.submit.po');
Route::get('/manager/purchase-order/view/{po_number}', [ManagerStockController::class, 'ManagerViewPO'])
    ->name('manager.view.po');

// MANAGER PROCESS MANAGEMENT
Route::get('/manager/process_management', [ManagerProcessController::class, 'ManagerProcessManagementPage'])->name('manager.process.management.page');
Route::post('/manager/batch/add-raw-products', [ManagerProcessController::class, 'ManagerAddBatchFetchRawProducts'])
    ->name('manager.batch.add.raw.products');
Route::get('/manager/batch-product-raw-details/delete/{id}', [ManagerProcessController::class, 'ManagerRemoveBatchRawProduct'])->name('manager.batch.raw.product.remove');
Route::post('/manager/add-batch-multiple-product', [ManagerProcessController::class, 'ManagerAddBatchMultipleProduct'])->name('manager.add.batch.multiple.product');
Route::post('/manager/add-batch-finish-product', [ManagerProcessController::class, 'ManagerAddBatchFinishProduct'])->name('manager.batch.finish.product');
Route::post('/manager/submit-finish-products', [ManagerProcessController::class, 'ManagerFinishProductSubmit'])->name('manager.finish.product.submit');
Route::get('/manager/batch-product-finish/delete/{id}', [ManagerProcessController::class, 'ManagerRemoveFinishProduct'])->name('manager.batch.finish.product.remove');
Route::post('/manager/archive-history-finish-product/{transactId}', [ManagerProcessController::class, 'ManagerArchiveHistoryFinishProduct'])->name('manager.archive.history.finish.product');


// MANAGER DELIVERY PENDING MANAGEMENT
Route::get('/manager/pending_delivery', [ManagerPendingDeliveryController::class, 'ManagerPendingDeliveryPage'])->name('manager.pending.management.page');
Route::post('/manager/pending/marked-status/{transact_id}', [ManagerPendingDeliveryController::class, 'ManagerMarkStatusDelivery'])->name('manager.delivery.mark.status');
Route::get('/manager/view/{transact_id}', [ManagerPendingDeliveryController::class, 'ManagerViewDeliveryOrder'])->name('manager.delivery.view');

// MANAGER DELIVERY STATUS MANAGEMENT
Route::get('/manager/delivery_status', [ManagerDeliveryStatusController::class, 'ManagerDeliveryStatusPage'])->name('manager.delivery.status.page');

// MANAGER SALES REPORT MANAGEMENT
Route::get('/manager/sales/report', [ManagerSalesReportController::class, 'ManagerSalesReportPage'])->name('manager.sales.management.page');
Route::post('/manager/sales/add-transaction', [ManagerSalesReportController::class, 'ManagerTransactionAdd'])->name('manager.sales.request.transaction');
Route::post('/manager/sales/report/archive/{id}', [ManagerSalesReportController::class, 'ManagerTransactionArchive'])
    ->name('manager.transaction.archive');


// MANAGER ARCHIVE MANAGEMENT
Route::get('/manager/archive', [ManagerArchiveController::class, 'ManagerArchivePage'])->name('manager.archive.page');
Route::post('/manager/employees/restore/{id}', [ManagerArchiveController::class, 'ManagerRestoreEmployee'])->name('manager.employees.restore');
Route::get('/manager/archive/stocks', [ManagerArchiveController::class, 'ManagerArchiveStocksPage'])->name('manager.archive.stocks.page');
Route::post('/manager/stocks/restore/{id}', [ManagerArchiveController::class, 'ManagerRestoreStocks'])->name('manager.stocks.restore');
Route::get('/manager/archive/stock_in', [ManagerArchiveController::class, 'ManagerArchiveStockInPage'])->name('manager.archive.stock.in.page');
Route::post('/manager/stockin/restore/{transact_id}', [ManagerArchiveController::class, 'ManagerRestoreStockIn'])->name('manager.stockin.restore');
Route::get('/manager/archive/process', [ManagerArchiveController::class, 'ManagerArchiveProcessPage'])->name('manager.archive.process.page');
Route::post('/manager/process/restore/{transact_id}', [ManagerArchiveController::class, 'ManagerRestoreProcess'])->name('manager.process.restore');
Route::get('/manager/archive/delivery', [ManagerArchiveController::class, 'ManagerArchiveDeliveryPage'])->name('manager.archive.delivery.page');
Route::post('/manager/delivery/restore/{transact_id}', [ManagerArchiveController::class, 'ManagerRestoreDelivery'])->name('manager.delivery.restore');
Route::get('/manager/archive/sales', [ManagerArchiveController::class, 'ManagerArchiveSalesPage'])->name('manager.archive.sales.page');
Route::post('/manager/archive/sales/restore/{id}', [ManagerArchiveController::class, 'ManagerRestoreSales'])->name('manager.sales.restore');

// MANAGER EDIT PROFILE
Route::get('/manager/profile/update', [ManagerProfileManagement::class, 'ManagerProfileManagementPage'])->name('manager.profile.page');
Route::post('/manager/profile/update/request', [ManagerProfileManagement::class, 'ManagerUpdateProfile'])->name('manager.profile.update.request');


// SUPERVISOR ROUTE
Route::get('/supervisor/dashboard', [SupervisorDashboardController::class, 'SupervisorDashboardPage'])->name('supervisor.dashboard.page');
Route::get('/supervisor/monthly-sales', [SupervisorDashboardController::class, 'SupervisorGetMonthlySales'])
    ->name('supervisor.monthly.sales');
Route::get('/supervisor/available-products', [SupervisorDashboardController::class, 'SupervisorGetAvailableProductsByType'])
    ->name('supervisor.get.available.product');

// SUPERVISOR STOCK MANAGEMENT
Route::get('/supervisor/stock_management', [SupervisorStockController::class, 'SupervisorStockManagementPage'])->name('supervisor.stock.management.page');
Route::post('/supervisor/stock/archive-product/{id}', [SupervisorStockController::class, 'SupervisorStockArchiveProduct'])->name('supervisor.stock.archive.product');
Route::post('/supervisor/stock/update-product/{id}', [SupervisorStockController::class, 'SupervisorStockUpdateProduct'])->name('supervisor.stock.update.product');
Route::get('/supervisor/purchase-order', [SupervisorStockController::class, 'SupervisorStockPurchaseOrderPage'])->name('supervisor.purchase.order.page');
Route::post('/supervisor/submit/purchase-order', [SupervisorStockController::class, 'SupervisorStockSubmitPO'])->name('supervisor.stock.submit.po');
Route::get('/supervisor/purchase-order/view/{po_number}', [SupervisorStockController::class, 'SupervisorViewPO'])
    ->name('supervisor.view.po');

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
