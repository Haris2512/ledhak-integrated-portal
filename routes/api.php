<?php

use App\Http\Controllers\Api\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Api\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Api\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Public\ArticleController as PublicArticleController;
use App\Http\Controllers\Api\Public\InventoryController as PublicInventoryController;
use App\Http\Controllers\Api\Public\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Endpoints (Open Access)
|--------------------------------------------------------------------------
*/
Route::prefix('public')->group(function () {
    // Static organization info
    Route::get('/profile', [ProfileController::class, 'show']);

    // Public Articles catalog
    Route::get('/articles', [PublicArticleController::class, 'index']);
    Route::get('/articles/{slug}', [PublicArticleController::class, 'show']);

    // Public Inventory catalog & QR scanning
    Route::get('/inventory', [PublicInventoryController::class, 'index']);
    Route::get('/inventory/{item_code}', [PublicInventoryController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Authentication Endpoints
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Endpoints (Protected by Sanctum Middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Manage items & status updates
    Route::apiResource('items', AdminItemController::class);

    // Record new loan & return borrowed item
    Route::post('/loans', [AdminLoanController::class, 'store']);
    Route::post('/loans/{loanRecord}/return', [AdminLoanController::class, 'returnLoan']);

    // Manage organization news articles
    Route::apiResource('articles', AdminArticleController::class);
});
