<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        // Buscar estatísticas dos assets
        $totalAssets = \App\Models\Asset::count();
        $activeCustody = \App\Models\CustodyLog::whereNull('checkin_date')->count();
        $pendingInventory = 0; // TODO: implementar lógica de inventário
        $totalUsers = \App\Models\MilitaryUser::count();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalAssets' => $totalAssets,
                'activeCustody' => $activeCustody,
                'pendingInventory' => $pendingInventory,
                'totalUsers' => $totalUsers,
            ]
        ]);
    })->name('dashboard');

    // Assets Management
    Route::get('/assets', function () {
        $assets = \App\Models\Asset::with('sector')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Assets/Index', [
            'assets' => $assets
        ]);
    })->name('assets.index');

    Route::get('/assets/create', function () {
        $sectors = \App\Models\Sector::all();

        return Inertia::render('Assets/Create', [
            'sectors' => $sectors
        ]);
    })->name('assets.create');

    Route::post('/assets', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:assets',
            'patrimony_number' => 'nullable|string|max:50|unique:assets',
            'type' => 'required|in:COMPUTADOR,NOTEBOOK,MONITOR,IMPRESSORA,TELEFONE,OUTROS',
            'condition' => 'required|in:NOVO,BOM,REGULAR,RUIM,DEFEITUOSO',
            'sector_id' => 'required|exists:sectors,id',
            'purchase_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $asset = \App\Models\Asset::create($validated);

        return redirect()->route('assets.show', $asset)->with('success', 'Ativo criado com sucesso!');
    })->name('assets.store');

    Route::get('/assets/{asset}', function (\App\Models\Asset $asset) {
        $asset->load('sector', 'photos');

        return Inertia::render('Assets/Show', [
            'asset' => $asset
        ]);
    })->name('assets.show');

    Route::get('/assets/{asset}/edit', function (\App\Models\Asset $asset) {
        $asset->load('sector');
        $sectors = \App\Models\Sector::all();

        return Inertia::render('Assets/Edit', [
            'asset' => $asset,
            'sectors' => $sectors
        ]);
    })->name('assets.edit');

    Route::put('/assets/{asset}', function (\Illuminate\Http\Request $request, \App\Models\Asset $asset) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:assets,serial_number,' . $asset->id,
            'patrimony_number' => 'nullable|string|max:50|unique:assets,patrimony_number,' . $asset->id,
            'type' => 'required|in:COMPUTADOR,NOTEBOOK,MONITOR,IMPRESSORA,TELEFONE,OUTROS',
            'condition' => 'required|in:NOVO,BOM,REGULAR,RUIM,DEFEITUOSO',
            'sector_id' => 'required|exists:sectors,id',
            'purchase_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.show', $asset)->with('success', 'Ativo atualizado com sucesso!');
    })->name('assets.update');

    Route::delete('/assets/{asset}', function (\App\Models\Asset $asset) {
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Ativo excluído com sucesso!');
    })->name('assets.destroy');

    // Custody Management
    Route::get('/custody', function () {
        return Inertia::render('Custody/Index');
    })->name('custody.index');

    // Inventory Management
    Route::get('/inventory', function () {
        return Inertia::render('Inventory/Index');
    })->name('inventory.index');

    // Sectors Management
    Route::get('/sectors', function () {
        return Inertia::render('Sectors/Index');
    })->name('sectors.index');

    // Users Management
    Route::get('/users', function () {
        return Inertia::render('Users/Index');
    })->name('users.index');

    // Reports
    Route::get('/reports', function () {
        return Inertia::render('Reports/Index');
    })->name('reports.index');

    // Sectors Management
    Route::get('/sectors', function () {
        $sectors = \App\Models\Sector::withCount(['users', 'assets'])
            ->orderBy('name')
            ->get();

        return Inertia::render('Sectors/Index', [
            'sectors' => $sectors
        ]);
    })->name('sectors.index');

    Route::get('/sectors/create', function () {
        return Inertia::render('Sectors/Create');
    })->name('sectors.create');

    Route::post('/sectors', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:sectors',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $sector = \App\Models\Sector::create($validated);

        return redirect()->route('sectors.show', $sector)->with('success', 'Setor criado com sucesso!');
    })->name('sectors.store');

    Route::get('/sectors/{sector}', function (\App\Models\Sector $sector) {
        $sector->load('users', 'assets');

        return Inertia::render('Sectors/Show', [
            'sector' => $sector
        ]);
    })->name('sectors.show');

    Route::get('/sectors/{sector}/edit', function (\App\Models\Sector $sector) {
        return Inertia::render('Sectors/Edit', [
            'sector' => $sector
        ]);
    })->name('sectors.edit');

    Route::put('/sectors/{sector}', function (\Illuminate\Http\Request $request, \App\Models\Sector $sector) {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:sectors,name,' . $sector->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $sector->update($validated);

        return redirect()->route('sectors.show', $sector)->with('success', 'Setor atualizado com sucesso!');
    })->name('sectors.update');

    Route::delete('/sectors/{sector}', function (\App\Models\Sector $sector) {
        $sector->delete();

        return redirect()->route('sectors.index')->with('success', 'Setor excluído com sucesso!');
    })->name('sectors.destroy');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
