<?php

use Illuminate\Support\Facades\Route;

Route::get('health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

// Authentication routes
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

// Protected routes (authentication required)
Route::name('api.')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    // Auth management
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('me', [App\Http\Controllers\AuthController::class, 'me']);

    // Dashboard routes (all authenticated users)
    Route::get('dashboard/stats', [App\Http\Controllers\DashboardController::class, 'getStats'])->name('dashboard.stats');

    // Custody routes (all authenticated users can view, based on role)
    Route::get('custody', [App\Http\Controllers\CustodyLogController::class, 'index'])->name('custody.index');
    Route::get('custody/{id}', [App\Http\Controllers\CustodyLogController::class, 'show'])->name('custody.show');

    // Admin and Commission routes
    // Sectors routes
    Route::apiResource('sectors', App\Http\Controllers\SectorController::class);

    // Users routes - TODO: Criar UserController
    // Route::apiResource('users', App\Http\Controllers\UserController::class);
    // Route::get('users/active', [App\Http\Controllers\UserController::class, 'getActiveUsers'])->name('users.active');
    // Route::get('users/sector/{sectorId}', [App\Http\Controllers\UserController::class, 'getUsersBySector'])->name('users.sector');

    // Assets routes
    Route::apiResource('assets', App\Http\Controllers\AssetController::class);
    Route::get('assets/qr/{qrCode}', [App\Http\Controllers\AssetController::class, 'getByQrCode'])->name('assets.qr');
    Route::get('assets/utils/next-qr-code', [App\Http\Controllers\AssetController::class, 'getNextQrCode'])->name('assets.next-qr');
    Route::post('assets/{assetId}/photos', [App\Http\Controllers\AssetController::class, 'addPhoto'])->name('assets.photos.add');
    Route::delete('assets/{assetId}/photos/{photoId}', [App\Http\Controllers\AssetController::class, 'deletePhoto'])->name('assets.photos.delete');
    Route::post('assets/{assetId}/maintenance', [App\Http\Controllers\AssetController::class, 'addMaintenance'])->name('assets.maintenance.add');
    Route::delete('assets/{assetId}/maintenance/{maintenanceId}', [App\Http\Controllers\AssetController::class, 'deleteMaintenance'])->name('assets.maintenance.delete');

    // Custody management routes
    Route::post('custody', [App\Http\Controllers\CustodyLogController::class, 'store'])->name('custody.store');
    Route::put('custody/{id}', [App\Http\Controllers\CustodyLogController::class, 'update'])->name('custody.update');
    Route::delete('custody/{id}', [App\Http\Controllers\CustodyLogController::class, 'destroy'])->name('custody.delete');
    Route::get('custody/next-number', [App\Http\Controllers\CustodyLogController::class, 'getNextNumber'])->name('custody.next-number');
    Route::put('custody/{custody}/checkin', [App\Http\Controllers\CustodyLogController::class, 'checkin'])->name('custody.checkin');
    Route::get('custody-reports', [App\Http\Controllers\CustodyLogController::class, 'reports'])->name('custody.reports');

    // Inventory routes (Commission and Admin)
    Route::apiResource('inventory', App\Http\Controllers\InventoryRecordController::class);
    Route::post('inventory/{id}/found', [App\Http\Controllers\InventoryRecordController::class, 'addFoundItem'])->name('inventory.found');
    Route::post('inventory/{id}/uncatalogued', [App\Http\Controllers\InventoryRecordController::class, 'addUncataloguedItem'])->name('inventory.uncatalogued');
    Route::put('inventory/{id}/complete', [App\Http\Controllers\InventoryRecordController::class, 'complete'])->name('inventory.complete');
    Route::post('inventory/{id}/reopen', [App\Http\Controllers\InventoryRecordController::class, 'reopen'])->name('inventory.reopen');
    Route::delete('inventory/{id}/uncatalogued/{uncataloguedId}', [App\Http\Controllers\InventoryRecordController::class, 'deleteUncataloguedItem'])->name('inventory.uncatalogued.delete');
});
