<?php

declare(strict_types=1);

use App\Http\Controllers\API\v1\Auth\LoginController;
use App\Http\Controllers\API\v1\Auth\RegisterCompanyController;
use App\Http\Controllers\API\v1\Auth\RegisterPharmacyController;
use App\Http\Controllers\API\v1\Company\CompanyOrdersIndexController;
use App\Http\Controllers\API\v1\Company\CompanyOrdersStoreController;
use App\Http\Controllers\API\v1\Company\CompanyOrdersUpdateStatusController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Auth (Public)
    Route::prefix('auth')->group(function () {
        Route::post('/register/company',  RegisterCompanyController::class);
        Route::post('/register/pharmacy', RegisterPharmacyController::class);
        Route::post('/login',             LoginController::class);
    });

    // Auth (Protected)
    Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
        Route::post('/logout', \App\Http\Controllers\API\v1\Auth\LogoutController::class);
    });

    // Company Routes (Protected)
    Route::middleware('auth:sanctum')->prefix('company')->group(function () {
        Route::get('/orders',                  CompanyOrdersIndexController::class);
        Route::post('/orders',                 CompanyOrdersStoreController::class);
        Route::patch('/orders/{order}/status', CompanyOrdersUpdateStatusController::class);

        // Products Management
        Route::get('/products',             [App\Http\Controllers\API\v1\Company\ProductController::class, 'index']);
        Route::post('/products',            [App\Http\Controllers\API\v1\Company\ProductController::class, 'store']);
        Route::patch('/products/{product}',   [App\Http\Controllers\API\v1\Company\ProductController::class, 'update']);


        // Pharmacies List
        Route::get('/pharmacies', function() {
            return sendSuccessResponse('تم جلب الصيدليات', \App\Models\Pharmacy::all());
        });
    });

    // Pharmacy Routes (Protected)
    Route::middleware('auth:sanctum')->prefix('pharmacy')->group(function () {
        // Companies & Products Browsing
        Route::get('/companies',            App\Http\Controllers\API\v1\Pharmacy\BrowseCompaniesController::class);
        Route::get('/companies/{company}/products', [App\Http\Controllers\API\v1\Pharmacy\ProductController::class, 'index']);
        Route::get('/products/{product}',   [App\Http\Controllers\API\v1\Pharmacy\ProductController::class, 'show']);

        // Orders
        Route::get('/orders',               [App\Http\Controllers\API\v1\Pharmacy\OrderController::class, 'index']);
        Route::post('/orders',              [App\Http\Controllers\API\v1\Pharmacy\OrderController::class, 'store']);
        Route::get('/orders/{order}',       [App\Http\Controllers\API\v1\Pharmacy\OrderController::class, 'show']);
    });

    // Admin Routes (Protected)
    Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
        Route::get('/companies',              [App\Http\Controllers\API\v1\Admin\CompanyManagementController::class, 'index']);
        Route::patch('/companies/{company}/toggle-status', [App\Http\Controllers\API\v1\Admin\CompanyManagementController::class, 'toggleStatus']);
    });

    // Invoice Management (ERP Finance)
    Route::middleware('auth:sanctum')->prefix('invoices')->group(function () {
        Route::get('/',                 [App\Http\Controllers\API\v1\InvoiceController::class, 'index']);
        Route::post('/',                [App\Http\Controllers\API\v1\InvoiceController::class, 'store']);
        Route::get('/{invoice}',        [App\Http\Controllers\API\v1\InvoiceController::class, 'show']);
        Route::delete('/{invoice}',     [App\Http\Controllers\API\v1\InvoiceController::class, 'destroy']);
        Route::post('/{invoice}/confirm', [App\Http\Controllers\API\v1\InvoiceController::class, 'confirm']);
        Route::post('/return',          [App\Http\Controllers\API\v1\InvoiceController::class, 'storeReturn']);
        
        // Payments
        Route::get('/{invoice}/payments',  [App\Http\Controllers\API\v1\InvoiceController::class, 'indexPayments']);
        Route::post('/{invoice}/payments', [App\Http\Controllers\API\v1\InvoiceController::class, 'storePayment']);
    });


});
