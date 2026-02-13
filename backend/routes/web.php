<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::livewire('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');

    Route::livewire('/users', App\Livewire\Users\Index::class)->name('users.index');
    Route::livewire('/users/create', App\Livewire\Users\Create::class)->name('users.create');
    Route::livewire('/users/{user}', App\Livewire\Users\Show::class)->name('users.show');
    Route::livewire('/users/{user}/edit', App\Livewire\Users\Edit::class)->name('users.edit');

    Route::livewire('/sectors', App\Livewire\Sectors\Index::class)->name('sectors.index');
    Route::livewire('/sectors/create', App\Livewire\Sectors\Create::class)->name('sectors.create');
    Route::livewire('/sectors/{sector}', App\Livewire\Sectors\Show::class)->name('sectors.show');
    Route::livewire('/sectors/{sector}/edit', App\Livewire\Sectors\Edit::class)->name('sectors.edit');

    Route::livewire('/assets', App\Livewire\Assets\Index::class)->name('assets.index');
    Route::livewire('/assets/create', App\Livewire\Assets\Create::class)->name('assets.create');
    Route::livewire('/assets/{asset}/edit', App\Livewire\Assets\Edit::class)->name('assets.edit');

    Route::livewire('/custody', App\Livewire\Custody\Index::class)->name('custody.index');
    Route::livewire('/custody/create', App\Livewire\Custody\Create::class)->name('custody.create');
    Route::livewire('/custody/{custodyLog}', App\Livewire\Custody\Show::class)->name('custody.show');
    Route::livewire('/custody/{custodyLog}/edit', App\Livewire\Custody\Edit::class)->name('custody.edit');
    Route::livewire('/custody/{custodyLog}/print', App\Livewire\Custody\PrintCautela::class)->name('custody.print');

    Route::livewire('/inventory', App\Livewire\Inventory\Index::class)->name('inventory.index');
    Route::livewire('/inventory/create', App\Livewire\Inventory\Create::class)->name('inventory.create');
    Route::livewire('/inventory/{inventory}', App\Livewire\Inventory\Show::class)->name('inventory.show');
    Route::get('/inventory/{inventory}/pdf', [App\Http\Controllers\InventoryReportController::class, 'download'])->name('inventory.pdf');

    Route::livewire('/categories', App\Livewire\Categories\Index::class)->name('categories.index');
    Route::livewire('/categories/create', App\Livewire\Categories\Create::class)->name('categories.create');
    Route::livewire('/categories/{category}/edit', App\Livewire\Categories\Edit::class)->name('categories.edit');

    Route::livewire('/assets/{asset}/maintenance', App\Livewire\Maintenance\Index::class)->name('maintenance.index');
    Route::livewire('/assets/{asset}/maintenance/create', App\Livewire\Maintenance\Create::class)->name('maintenance.create');

    Route::livewire('/notifications', App\Livewire\Notifications\Index::class)->name('notifications.index');
    Route::livewire('/reports', App\Livewire\Reports\Index::class)->name('reports.index');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
