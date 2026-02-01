<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Users\Index;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', Index::class)->name('users.index');
    Route::get('/users/create', App\Livewire\Users\Create::class)->name('users.create');
    Route::get('/users/{user}/edit', App\Livewire\Users\Edit::class)->name('users.edit');

    Route::get('/sectors', App\Livewire\Sectors\Index::class)->name('sectors.index');
    Route::get('/sectors/create', App\Livewire\Sectors\Create::class)->name('sectors.create');
    Route::get('/sectors/{sector}/edit', App\Livewire\Sectors\Edit::class)->name('sectors.edit');

    Route::get('/assets', App\Livewire\Assets\Index::class)->name('assets.index');
    Route::get('/assets/create', App\Livewire\Assets\Create::class)->name('assets.create');
    Route::get('/assets/{asset}/edit', App\Livewire\Assets\Edit::class)->name('assets.edit');

    Route::get('/custody', App\Livewire\Custody\Index::class)->name('custody.index');
    Route::get('/custody/create', App\Livewire\Custody\Create::class)->name('custody.create');
    Route::get('/custody/{custodyLog}/edit', App\Livewire\Custody\Edit::class)->name('custody.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
