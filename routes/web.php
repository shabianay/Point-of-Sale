<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PosController;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/payment-chart', [HomeController::class, 'paymentChartData'])->name('home.payment-chart');
    Route::get('/home/best-products', [HomeController::class, 'bestProductsData'])->name('home.best-products');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add-item', [PosController::class, 'addItem'])->name('pos.add-item');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/calculate-change', [PosController::class, 'calculateChange'])->name('pos.calculate-change');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/delete-image', [ProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/void', [TransactionController::class, 'void'])->name('transactions.void');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
    Route::get('/transactions/{transaction}/receipt-pdf', [TransactionController::class, 'receiptPdf'])->name('transactions.receipt-pdf');

    // Stock
    Route::get('/stock', [StockMovementController::class, 'index'])->name('stock.index');
    Route::get('/stock/restock', [StockMovementController::class, 'createRestock'])->name('stock.restock');
    Route::post('/stock/restock', [StockMovementController::class, 'storeRestock'])->name('stock.restock.store');
    Route::get('/stock/opname', [StockMovementController::class, 'createOpname'])->name('stock.opname');
    Route::post('/stock/opname', [StockMovementController::class, 'storeOpname'])->name('stock.opname.store');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/best-products', [ReportController::class, 'bestProducts'])->name('reports.best-products');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/export-excel/{type}', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/reports/export-pdf/{type}', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel/{type}/{from}/{to}', [ReportController::class, 'exportExcel'])->name('reports.export-excel-range');
    Route::get('/reports/export-pdf/{type}/{from}/{to}', [ReportController::class, 'exportPdf'])->name('reports.export-pdf-range');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Users (Owner only)
    Route::middleware(['role:Owner'])->group(function () {
        Route::resource('users', UserController::class);
    });
});
