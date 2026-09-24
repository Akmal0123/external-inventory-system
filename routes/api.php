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
|
*/

// Developer Testing Helper (JWT B Generator)
Route::get('/dev/tokens', [\App\Http\Controllers\Api\DocsController::class, 'tokens']);

// Authentication (Legacy / User Auth)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// =========================================================================
// API v1: Dedicated Service-to-Service Integration Endpoints (JWT B Protected)
// As specified in isolated-integration-service-architecture.md Section 11 & 20
// =========================================================================
Route::prefix('v1')->group(function () {
    // Purchase Order Endpoints (Requires 'purchase-order:read' scope)
    Route::middleware(['jwt.eis:purchase-order:read'])->group(function () {
        Route::get('/purchase-orders/lookup', [PurchaseOrderController::class, 'lookup']);
        Route::get('/purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'pdf']);
        Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'show']);
        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index']);
    });

    // Purchase Request Endpoints (Requires 'purchase-request:read' scope)
    Route::middleware(['jwt.eis:purchase-request:read'])->group(function () {
        Route::get('/purchase-requests/lookup', [PurchaseRequestController::class, 'lookup']);
        Route::get('/purchase-requests/{id}', [PurchaseRequestController::class, 'show']);
        Route::get('/purchase-requests', [PurchaseRequestController::class, 'index']);
    });
});

// =========================================================================
// Legacy API Routes (with OptionalApiAuth for backward compatibility)
// =========================================================================
Route::middleware([OptionalApiAuth::class])->group(function () {
    // Purchase Request Endpoints
    Route::get('/purchase-requests/lookup', [PurchaseRequestController::class, 'lookup']);
    Route::apiResource('purchase-requests', PurchaseRequestController::class);

    // Purchase Order Endpoints
    Route::get('/purchase-orders/lookup', [PurchaseOrderController::class, 'lookup']);
    Route::get('/purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'pdf']);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);

    // Master Data CRUD Endpoints
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('items', ItemController::class);
});
