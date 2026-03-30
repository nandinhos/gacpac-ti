<?php

use Illuminate\Http\Request;
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
    Route::get('me', function (Request $request) {
        return new App\Http\Resources\UserResource($request->user()->load('sector'));
    })->name('me');

    // Assets
    Route::apiResource('assets', App\Http\Controllers\AssetController::class);
    Route::get('assets/qr/{qrCode}', [App\Http\Controllers\AssetController::class, 'getByQrCode'])->name('assets.by-qr');
    Route::get('assets/utils/next-qr-code', [App\Http\Controllers\AssetController::class, 'nextQrCode'])->name('assets.next-qr');

    // Sectors
    Route::apiResource('sectors', App\Http\Controllers\SectorController::class);

    // Categories
    Route::apiResource('categories', App\Http\Controllers\CategoryController::class);

    // Users
    Route::apiResource('users', App\Http\Controllers\UserController::class);
    Route::get('users/active', [App\Http\Controllers\UserController::class, 'active'])->name('users.active');
    Route::get('users/sector/{sectorId}', [App\Http\Controllers\UserController::class, 'bySector'])->name('users.by-sector');

    // Maintenance (nested resource)
    Route::apiResource('assets/{asset}/maintenance', App\Http\Controllers\MaintenanceController::class)
        ->parameters(['maintenance' => 'maintenanceRecord']);
    Route::get('maintenance/upcoming', [App\Http\Controllers\MaintenanceController::class, 'upcoming'])->name('maintenance.upcoming');

    // Custody
    Route::apiResource('custody', App\Http\Controllers\CustodyLogController::class);
    Route::put('custody/{custodyLog}/checkin', [App\Http\Controllers\CustodyLogController::class, 'checkin'])->name('custody.checkin');
    Route::get('custody/utils/next-number', [App\Http\Controllers\CustodyLogController::class, 'nextNumber'])->name('custody.next-number');

    // Inventory
    Route::apiResource('inventory', App\Http\Controllers\InventoryRecordController::class);
    Route::put('inventory/{inventoryRecord}/complete', [App\Http\Controllers\InventoryRecordController::class, 'complete'])->name('inventory.complete');
    Route::put('inventory/{inventoryRecord}/reopen', [App\Http\Controllers\InventoryRecordController::class, 'reopen'])->name('inventory.reopen');

    // Notifications
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::patch('notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
