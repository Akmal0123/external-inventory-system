<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\PurchaseRequestController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Middleware\OptionalApiAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| External Inventory System API Routes
|--------------------------------------------------------------------------
|
| Base URL: http://localhost:9000/api
| Middleware: OptionalApiAuth (Toggle via .env: API_JWT_ENABLED=true/false)
|
*/

// Authentication (Section 7.1)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// API Routes with Optional JWT Middleware
Route::middleware([OptionalApiAuth::class])->group(function () {

    // Purchase Request Endpoints (Section 8)
    Route::get('/purchase-requests/lookup', [PurchaseRequestController::class, 'lookup']);
    Route::apiResource('purchase-requests', PurchaseRequestController::class);

    // Purchase Order Endpoints (Section 9 & 10)
    Route::get('/purchase-orders/lookup', [PurchaseOrderController::class, 'lookup']);
    Route::get('/purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'pdf']);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);

    // Master Data CRUD Endpoints
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('items', ItemController::class);
});
