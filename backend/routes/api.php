<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('test', function () {
    return 'api ok';
});

// Auth routes (if needed)
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

// Dashboard routes
Route::get('dashboard/stats', [App\Http\Controllers\DashboardController::class, 'getStats']);

// Sectors routes
Route::apiResource('sectors', App\Http\Controllers\SectorController::class);

// Users routes
Route::apiResource('users', App\Http\Controllers\MilitaryUserController::class);
Route::get('users/active', [App\Http\Controllers\MilitaryUserController::class, 'getActiveUsers']);
Route::get('users/sector/{sectorId}', [App\Http\Controllers\MilitaryUserController::class, 'getUsersBySector']);

// Assets routes
Route::apiResource('assets', App\Http\Controllers\AssetController::class);
Route::get('assets/qr/{qrCode}', [App\Http\Controllers\AssetController::class, 'getByQrCode']);
Route::get('assets/utils/next-qr-code', [App\Http\Controllers\AssetController::class, 'getNextQrCode']);
Route::post('assets/{assetId}/photos', [App\Http\Controllers\AssetController::class, 'addPhoto']);
Route::delete('assets/{assetId}/photos/{photoId}', [App\Http\Controllers\AssetController::class, 'deletePhoto']);
Route::post('assets/{assetId}/maintenance', [App\Http\Controllers\AssetController::class, 'addMaintenance']);
Route::delete('assets/{assetId}/maintenance/{maintenanceId}', [App\Http\Controllers\AssetController::class, 'deleteMaintenance']);

// Custody routes
Route::apiResource('custody', App\Http\Controllers\CustodyLogController::class);
Route::get('custody/next-number', [App\Http\Controllers\CustodyLogController::class, 'getNextNumber']);
Route::put('custody/{id}/checkin', [App\Http\Controllers\CustodyLogController::class, 'checkin']);

// Inventory routes
Route::apiResource('inventory', App\Http\Controllers\InventoryRecordController::class);
Route::post('inventory/{id}/found', [App\Http\Controllers\InventoryRecordController::class, 'addFoundItem']);
Route::post('inventory/{id}/uncatalogued', [App\Http\Controllers\InventoryRecordController::class, 'addUncataloguedItem']);
Route::put('inventory/{id}/complete', [App\Http\Controllers\InventoryRecordController::class, 'complete']);
Route::post('inventory/{id}/reopen', [App\Http\Controllers\InventoryRecordController::class, 'reopen']);
Route::delete('inventory/{id}/uncatalogued/{uncataloguedId}', [App\Http\Controllers\InventoryRecordController::class, 'deleteUncataloguedItem']);

// Utility routes
Route::get('health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});