<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::get('test', function () {
    return 'api ok';
});

Route::get('health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

// Authentication routes
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

// Protected routes (authentication required)
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth management
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('me', [App\Http\Controllers\AuthController::class, 'me']);

    // Dashboard routes (all authenticated users)
    Route::get('dashboard/stats', [App\Http\Controllers\DashboardController::class, 'getStats']);

    // Custody routes (all authenticated users can view, based on role)
    Route::get('custody', [App\Http\Controllers\CustodyLogController::class, 'index']);
    Route::get('custody/{id}', [App\Http\Controllers\CustodyLogController::class, 'show']);
    
    // Admin and Commission routes
    // Sectors routes
    Route::apiResource('sectors', App\Http\Controllers\SectorController::class)->names([
        'index' => 'api.sectors.index',
        'store' => 'api.sectors.store',
        'show' => 'api.sectors.show',
        'update' => 'api.sectors.update',
        'destroy' => 'api.sectors.destroy'
    ]);
    
    // Users routes  
    Route::apiResource('users', App\Http\Controllers\MilitaryUserController::class)->names([
        'index' => 'api.users.index',
        'store' => 'api.users.store',
        'show' => 'api.users.show',
        'update' => 'api.users.update',
        'destroy' => 'api.users.destroy'
    ]);
    Route::get('users/active', [App\Http\Controllers\MilitaryUserController::class, 'getActiveUsers']);
    Route::get('users/sector/{sectorId}', [App\Http\Controllers\MilitaryUserController::class, 'getUsersBySector']);
    
    // Assets routes
    Route::apiResource('assets', App\Http\Controllers\AssetController::class)->names([
        'index' => 'api.assets.index',
        'store' => 'api.assets.store',
        'show' => 'api.assets.show',
        'update' => 'api.assets.update',
        'destroy' => 'api.assets.destroy'
    ]);
    Route::get('assets/qr/{qrCode}', [App\Http\Controllers\AssetController::class, 'getByQrCode']);
    Route::get('assets/utils/next-qr-code', [App\Http\Controllers\AssetController::class, 'getNextQrCode']);
    Route::post('assets/{assetId}/photos', [App\Http\Controllers\AssetController::class, 'addPhoto']);
    Route::delete('assets/{assetId}/photos/{photoId}', [App\Http\Controllers\AssetController::class, 'deletePhoto']);
    Route::post('assets/{assetId}/maintenance', [App\Http\Controllers\AssetController::class, 'addMaintenance']);
    Route::delete('assets/{assetId}/maintenance/{maintenanceId}', [App\Http\Controllers\AssetController::class, 'deleteMaintenance']);
    
    // Custody management routes
    Route::post('custody', [App\Http\Controllers\CustodyLogController::class, 'store']);
    Route::put('custody/{id}', [App\Http\Controllers\CustodyLogController::class, 'update']);
    Route::delete('custody/{id}', [App\Http\Controllers\CustodyLogController::class, 'destroy']);
    Route::get('custody/next-number', [App\Http\Controllers\CustodyLogController::class, 'getNextNumber']);
    Route::put('custody/{custody}/checkin', [App\Http\Controllers\CustodyLogController::class, 'checkin']);
    Route::get('custody-reports', [App\Http\Controllers\CustodyLogController::class, 'reports']);

    // Inventory routes (Commission and Admin)
    Route::apiResource('inventory', App\Http\Controllers\InventoryRecordController::class)->names([
        'index' => 'api.inventory.index',
        'store' => 'api.inventory.store',
        'show' => 'api.inventory.show',
        'update' => 'api.inventory.update',
        'destroy' => 'api.inventory.destroy'
    ]);
    Route::post('inventory/{id}/found', [App\Http\Controllers\InventoryRecordController::class, 'addFoundItem']);
    Route::post('inventory/{id}/uncatalogued', [App\Http\Controllers\InventoryRecordController::class, 'addUncataloguedItem']);
    Route::put('inventory/{id}/complete', [App\Http\Controllers\InventoryRecordController::class, 'complete']);
    Route::post('inventory/{id}/reopen', [App\Http\Controllers\InventoryRecordController::class, 'reopen']);
    Route::delete('inventory/{id}/uncatalogued/{uncataloguedId}', [App\Http\Controllers\InventoryRecordController::class, 'deleteUncataloguedItem']);
});
