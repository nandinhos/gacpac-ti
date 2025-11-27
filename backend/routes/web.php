<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (apenas para admin)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Redirecionar comissão e usuários para cautelas
        if ($user->user_role !== 'admin') {
            return redirect()->route('custody.index');
        }
        
        // Buscar estatísticas dos assets (apenas para admin)
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
        $user = auth()->user();
        $custodyLogs = \App\Models\CustodyLog::with(['user', 'assets'])
            ->forUser($user)
            ->orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('Custody/Index', ['custodyLogs' => $custodyLogs]);
    })->name('custody.index');

    Route::get('/custody/create', function () {
        // Apenas admin pode criar cautelas
        $user = auth()->user();
        if ($user->user_role !== 'admin') {
            abort(403, 'Você não tem permissão para criar cautelas.');
        }
        
        $users = \App\Models\MilitaryUser::where('is_active', true)->orderBy('name')->get();
        $assets = \App\Models\Asset::where('status', 'Disponível')->orderBy('name')->get();
        
        $lastCustody = \App\Models\CustodyLog::orderBy('cautela_number', 'desc')->first();
        if ($lastCustody) {
            $lastNumber = (int) preg_replace('/[^0-9]/', '', Str::before($lastCustody->cautela_number, '/'));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        $nextCautelaNumber = sprintf('%03d/GAC-PAC/%d', $nextNumber, date('Y'));

        return Inertia::render('Custody/Create', [
            'users' => $users,
            'assets' => $assets,
            'nextCautelaNumber' => $nextCautelaNumber,
        ]);
    })->name('custody.create');

    Route::post('/custody', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'cautelaNumber' => 'required|string|max:50|unique:custody_logs,cautela_number',
            'userId' => 'required|exists:military_users,id',
            'checkoutDate' => 'required|date',
            'assetIds' => 'required|array|min:1',
            'assetIds.*' => 'exists:assets,id',
            'notes' => 'nullable|string',
        ]);

        $assets = \App\Models\Asset::whereIn('id', $validated['assetIds'])->get();
        foreach ($assets as $asset) {
            if ($asset->status !== 'Disponível') {
                return back()->withErrors(['assetIds' => "O ativo {$asset->name} não está disponível."]);
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $custody = \App\Models\CustodyLog::create([
                'cautela_number' => $validated['cautelaNumber'],
                'user_id' => $validated['userId'],
                'checkout_date' => $validated['checkoutDate'],
                'notes' => $validated['notes'],
            ]);

            $custody->assets()->attach($validated['assetIds']);

            \App\Models\Asset::whereIn('id', $validated['assetIds'])->update([
                'status' => 'Em Uso',
                'custodian_user_id' => $validated['userId'],
            ]);
        });

        return redirect()->route('custody.index')->with('success', 'Cautela criada com sucesso!');
    })->name('custody.store');

    Route::get('/custody/{custody}', function (\App\Models\CustodyLog $custody) {
        $custody->load(['user', 'assets']);
        return Inertia::render('Custody/Show', ['log' => $custody]);
    })->name('custody.show');

    Route::put('/custody/{custody}/checkin', function (\Illuminate\Http\Request $request, \App\Models\CustodyLog $custody) {
        $validated = $request->validate(['checkinDate' => 'required|date']);

        if ($custody->checkin_date) {
            return back()->withErrors(['checkin' => 'Esta cautela já foi devolvida.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $custody) {
            $custody->update(['checkin_date' => $validated['checkinDate']]);
            foreach ($custody->assets as $asset) {
                $asset->update(['status' => 'Disponível', 'custodian_user_id' => null]);
            }
        });

        return redirect()->route('custody.index')->with('success', 'Cautela devolvida com sucesso!');
    })->name('custody.checkin');

    Route::get('/custody/reports', function () {
        return Inertia::render('Custody/Reports', [
            // Dados que podem ser passados para o componente React
        ]);
    })->name('custody.reports');

    // Rota para impressão/PDF da cautela
    Route::get('/custody/{custody}/print', function (\App\Models\CustodyLog $custody) {
        $custody->load(['user.sector', 'assets']);
        return Inertia::render('Custody/PrintCautela', ['custodyLog' => $custody]);
    })->name('custody.print');

    // Rota para upload do documento assinado
    Route::post('/custody/{custody}/upload-signed-document', function (\Illuminate\Http\Request $request, \App\Models\CustodyLog $custody) {
        // Log básico que deve sempre aparecer
        error_log('UPLOAD TESTE: Rota chamada para custody ID: ' . $custody->id);
        \Log::info('Início do upload de documento para custody ID: ' . $custody->id);
        
        $validated = $request->validate([
            'signed_document' => 'required|file|mimes:pdf,jpeg,jpg,png|max:10240', // 10MB max
            'justification' => 'required|string|max:500'
        ]);

        \Log::info('Validação passou, arquivo: ' . $request->file('signed_document')->getClientOriginalName());

        // Armazenar o arquivo
        $file = $request->file('signed_document');
        
        // Limpar o número da cautela para nome de arquivo seguro
        $cleanCautelaNumber = preg_replace('/[^A-Za-z0-9\-_]/', '_', $custody->cautela_number);
        $extension = $file->getClientOriginalExtension();
        $filename = 'cautela_' . $cleanCautelaNumber . '_signed_' . time() . '.' . $extension;
        
        \Log::info('Nome do arquivo será: ' . $filename);
        
        // Salvar o arquivo
        $path = $file->storeAs('signed_documents', $filename, 'public');
        
        if (!$path) {
            \Log::error('Falha ao salvar arquivo no storage');
            return back()->withErrors(['upload' => 'Falha ao salvar arquivo']);
        }

        \Log::info('Arquivo salvo em: ' . $path);

        // Atualizar o registro
        $updateResult = $custody->update([
            'signed_document_url' => $path,
            'signed_document_uploaded_at' => now(),
            'signed_document_justification' => $validated['justification']
        ]);

        \Log::info('Resultado da atualização do banco: ' . ($updateResult ? 'TRUE' : 'FALSE'));

        // Verificar se é requisição AJAX (mas não Inertia)
        if (($request->ajax() || $request->wantsJson()) && !$request->header('X-Inertia')) {
            error_log('UPLOAD TESTE: Retornando JSON response');
            return response()->json([
                'message' => 'Documento enviado com sucesso!',
                'signed_document_url' => $path
            ]);
        }
        
        // Para requisições Inertia, redirecionar de volta com mensagem de sucesso
        return redirect()->back()->with('success', 'Documento enviado com sucesso!');
    })->name('custody.upload-signed-document');

    // Rota para servir arquivos de documento assinado
    Route::get('/custody/{custody}/signed-document', function (\App\Models\CustodyLog $custody) {
        if (!$custody->signed_document_url) {
            abort(404, 'Documento não encontrado');
        }

        // O caminho já está no formato correto (signed_documents/filename.ext)
        $fullPath = storage_path('app/public/' . $custody->signed_document_url);

        if (!file_exists($fullPath)) {
            abort(404, 'Arquivo não encontrado no servidor');
        }

        // Determinar o tipo MIME
        $mimeType = mime_content_type($fullPath);
        $filename = basename($fullPath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    })->name('custody.signed-document');

    // Rota para download do documento assinado
    Route::get('/custody/{custody}/signed-document/download', function (\App\Models\CustodyLog $custody) {
        if (!$custody->signed_document_url) {
            abort(404, 'Documento não encontrado');
        }

        // O caminho já está no formato correto (signed_documents/filename.ext)
        $fullPath = storage_path('app/public/' . $custody->signed_document_url);

        if (!file_exists($fullPath)) {
            abort(404, 'Arquivo não encontrado no servidor');
        }

        // Nome amigável para download
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $downloadName = 'Cautela_' . $custody->cautela_number . '_Assinada.' . $extension;

        return response()->download($fullPath, $downloadName);
    })->name('custody.signed-document-download');

    // Rota para remover documento assinado
    Route::delete('/custody/{custody}/remove-signed-document', function (\Illuminate\Http\Request $request, \App\Models\CustodyLog $custody) {
        $validated = $request->validate([
            'justification' => 'required|string|max:500'
        ]);

        try {
            // Remover arquivo físico se existir
            if ($custody->signed_document_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($custody->signed_document_url);
            }

            // Limpar dados do banco
            $custody->update([
                'signed_document_url' => null,
                'signed_document_uploaded_at' => null,
                'signed_document_justification' => null,
                'signed_document_removed_at' => now(),
                'signed_document_removal_justification' => $validated['justification']
            ]);

            return redirect()->back()->with('success', 'Documento removido com sucesso!');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao remover documento: ' . $e->getMessage()], 500);
        }
    })->name('custody.remove-signed-document');

    // Inventory Management
    Route::get('/inventory', function () {
        $user = auth()->user();
        $inventoryRecords = \App\Models\InventoryRecord::with(['sector', 'responsibleUser'])
            ->forUser($user)
            ->orderBy('start_date', 'desc')
            ->get();
        return Inertia::render('Inventory/Index', ['inventoryRecords' => $inventoryRecords]);
    })->name('inventory.index');

    Route::get('/inventory/create', function () {
        $users = \App\Models\MilitaryUser::where('is_active', true)->orderBy('name')->get();
        $sectors = \App\Models\Sector::where('is_active', true)->orderBy('name')->get();
        return Inertia::render('Inventory/Create', ['users' => $users, 'sectors' => $sectors]);
    })->name('inventory.create');

    Route::post('/inventory', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'sector_id' => 'required|string',
            'responsible_user_id' => 'required|exists:military_users,id',
            'commission_number' => 'nullable|string|max:255|unique:inventory_records',
            'start_date' => 'required|date',
        ]);

        $inventory = \App\Models\InventoryRecord::create([
            'sector_id' => $validated['sector_id'] === 'global' ? null : $validated['sector_id'],
            'responsible_user_id' => $validated['responsible_user_id'],
            'commission_number' => $validated['commission_number'],
            'start_date' => $validated['start_date'],
        ]);

        return redirect()->route('inventory.show', $inventory)->with('success', 'Inventário iniciado com sucesso!');
    })->name('inventory.store');

    Route::get('/inventory/{inventory}', function (\App\Models\InventoryRecord $inventory) {
        // Se o inventário está concluído, mostrar resumo
        if ($inventory->status === 'Concluído') {
            return redirect()->route('inventory.summary', $inventory);
        }
        
        // Se está em andamento, mostrar página de edição
        $inventory->load(['sector', 'responsibleUser']);

        // Buscar todos os assets do escopo
        $allAssetsQuery = \App\Models\Asset::query();
        if ($inventory->sector_id) {
            $allAssetsQuery->where('sector_id', $inventory->sector_id);
        }
        $allAssetsInScope = $allAssetsQuery->get();

        // Buscar assets já encontrados neste inventário
        $foundAssetIds = $inventory->inventoryAssets()->pluck('asset_id')->toArray();
        
        // Separar pendentes e encontrados
        $pendingAssets = $allAssetsInScope->whereNotIn('id', $foundAssetIds)->values();
        $foundAssets = $allAssetsInScope->whereIn('id', $foundAssetIds)->values();
        
        // Buscar itens não catalogados
        $uncataloguedItems = $inventory->uncataloguedItems()->get();

        return Inertia::render('Inventory/Show', [
            'inventory' => $inventory,
            'pendingAssets' => $pendingAssets->toArray(),
            'foundAssets' => $foundAssets->toArray(),
            'uncataloguedItems' => $uncataloguedItems->toArray(),
        ]);
    })->name('inventory.show');

    // Rota para resumo de inventários concluídos
    Route::get('/inventory/{inventory}/summary', function (\App\Models\InventoryRecord $inventory) {
        $inventory->load(['sector', 'responsibleUser']);

        // Buscar todos os assets do escopo
        $allAssetsQuery = \App\Models\Asset::query();
        if ($inventory->sector_id) {
            $allAssetsQuery->where('sector_id', $inventory->sector_id);
        }
        $allAssetsInScope = $allAssetsQuery->get();

        // Buscar assets já encontrados neste inventário
        $foundAssetIds = $inventory->inventoryAssets()->pluck('asset_id')->toArray();
        
        // Separar pendentes e encontrados
        $pendingAssets = $allAssetsInScope->whereNotIn('id', $foundAssetIds)->values();
        $foundAssets = $allAssetsInScope->whereIn('id', $foundAssetIds)->values();
        
        // Buscar itens não catalogados
        $uncataloguedItems = $inventory->uncataloguedItems()->get();

        return Inertia::render('Inventory/Summary', [
            'inventory' => $inventory,
            'pendingAssets' => $pendingAssets->toArray(),
            'foundAssets' => $foundAssets->toArray(),
            'uncataloguedItems' => $uncataloguedItems->toArray(),
        ]);
    })->name('inventory.summary');

    // Rota para gerar relatório de impressão
    Route::get('/inventory/{inventory}/print', function (\App\Models\InventoryRecord $inventory) {
        $inventory->load(['sector', 'responsibleUser']);

        // Buscar todos os assets do escopo
        $allAssetsQuery = \App\Models\Asset::query();
        if ($inventory->sector_id) {
            $allAssetsQuery->where('sector_id', $inventory->sector_id);
        }
        $allAssetsInScope = $allAssetsQuery->get();

        // Buscar assets já encontrados neste inventário
        $foundAssetIds = $inventory->inventoryAssets()->pluck('asset_id')->toArray();
        
        // Separar pendentes e encontrados
        $pendingAssets = $allAssetsInScope->whereNotIn('id', $foundAssetIds)->values();
        $foundAssets = $allAssetsInScope->whereIn('id', $foundAssetIds)->values();
        
        // Buscar itens não catalogados
        $uncataloguedItems = $inventory->uncataloguedItems()->get();

        return Inertia::render('Inventory/PrintReport', [
            'inventory' => $inventory,
            'pendingAssets' => $pendingAssets->toArray(),
            'foundAssets' => $foundAssets->toArray(),
            'uncataloguedItems' => $uncataloguedItems->toArray(),
        ]);
    })->name('inventory.printReport');

    Route::put('/inventory/{inventory}', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory) {
        $validated = $request->validate([
            'status' => 'required|in:Concluído,Reaberto,Em Andamento',
            'notes' => 'nullable|string',
        ]);

        $inventory->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'end_date' => $validated['status'] === 'Concluído' ? now() : null,
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventário finalizado!');
    })->name('inventory.update');

    Route::post('/inventory/{inventory}/find', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory) {
        $validated = $request->validate(['qr_code' => 'required|string']);
        $asset = \App\Models\Asset::where('qr_code', $validated['qr_code'])->first();

        if (!$asset) {
            return back()->withErrors(['qr_code' => 'Ativo não encontrado.']);
        }

        $inventory->assets()->syncWithoutDetaching([$asset->id]);

        return redirect()->route('inventory.show', $inventory);
    })->name('inventory.findAsset');

    Route::post('/inventory/{inventory}/uncatalogued', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory) {
        $validated = $request->validate(['description' => 'required|string']);
        $inventory->uncataloguedItems()->create([
            'description' => $validated['description'],
            'found_date' => now(),
        ]);
        return redirect()->route('inventory.show', $inventory);
    })->name('inventory.addUncatalogued');

    Route::delete('/inventory/{inventory}/uncatalogued/{item}', function (\App\Models\InventoryRecord $inventory, \App\Models\UncataloguedItem $item) {
        $item->delete();
        return redirect()->route('inventory.show', $inventory);
    })->name('inventory.removeUncatalogued');

    Route::put('/inventory/{inventory}/uncatalogued/{item}', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory, \App\Models\UncataloguedItem $item) {
        $validated = $request->validate(['description' => 'required|string']);
        $item->update(['description' => $validated['description']]);
        return redirect()->route('inventory.show', $inventory);
    })->name('inventory.editUncatalogued');

    Route::post('/inventory/{inventory}/bulk-find', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory) {
        $validated = $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
        ]);

        $inventory->assets()->syncWithoutDetaching($validated['asset_ids']);

        return redirect()->route('inventory.show', $inventory);
    })->name('inventory.bulkFind');

    Route::post('/inventory/{inventory}/bulk-remove', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory) {
        $validated = $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
        ]);

        $inventory->assets()->detach($validated['asset_ids']);

        return redirect()->route('inventory.show', $inventory);
    })->name('inventory.bulkRemove');

    Route::delete('/inventory/{inventory}', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory) {
        $validated = $request->validate([
            'justification' => 'required|string|min:10',
        ]);

        // Here you might want to log the justification before deleting
        // For now, we just delete.
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Inventário excluído com sucesso!');
    })->name('inventory.destroy');

    Route::put('/inventory/{inventory}/reopen', function (\Illuminate\Http\Request $request, \App\Models\InventoryRecord $inventory) {
        $validated = $request->validate([
            'justification' => 'required|string|min:10',
        ]);

        if ($inventory->status !== 'Concluído') {
            return back()->withErrors(['reopen' => 'Apenas inventários concluídos podem ser reabertos.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($inventory, $validated, $request) {
            $inventory->update([
                'status' => 'Reaberto',
                'end_date' => null,
            ]);

            $inventory->reopenHistory()->create([
                'reopened_by_user_id' => $request->user()->id,
                'justification' => $validated['justification'],
                'reopened_at' => now(),
            ]);
        });

        return redirect()->route('inventory.show', $inventory)->with('success', 'Inventário reaberto com sucesso! Agora você pode fazer edições.');
    })->name('inventory.reopen');


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

    // Users Management  
    Route::get('/users', function () {
        $users = \App\Models\MilitaryUser::with('sector')
            ->orderBy('name')
            ->get();

        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    })->name('users.index');

    Route::get('/users/create', function () {
        $sectors = \App\Models\Sector::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('Users/Create', [
            'sectors' => $sectors
        ]);
    })->name('users.create');

    Route::post('/users', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rank' => 'required|string|max:100',
            'military_id' => 'required|string|max:20|unique:military_users',
            'sector_id' => 'required|exists:sectors,id',
            'email' => 'nullable|email|max:255|unique:military_users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_role' => 'required|in:user,admin,commission',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['commission_inventories'] = $validated['user_role'] === 'commission' ? [] : null;

        $user = \App\Models\MilitaryUser::create($validated);

        return redirect()->route('users.show', $user)->with('success', 'Usuário criado com sucesso!');
    })->name('users.store');

    Route::get('/users/{user}', function (\App\Models\MilitaryUser $user) {
        $user->load('sector');

        return Inertia::render('Users/Show', [
            'user' => $user
        ]);
    })->name('users.show');

    Route::get('/users/{user}/edit', function (\App\Models\MilitaryUser $user) {
        $user->load('sector');
        $sectors = \App\Models\Sector::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'sectors' => $sectors
        ]);
    })->name('users.edit');

    Route::put('/users/{user}', function (\Illuminate\Http\Request $request, \App\Models\MilitaryUser $user) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rank' => 'required|string|max:100',
            'military_id' => 'required|string|max:20|unique:military_users,military_id,' . $user->id,
            'sector_id' => 'required|exists:sectors,id',
            'email' => 'nullable|email|max:255|unique:military_users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'user_role' => 'required|in:user,admin,commission',
            'is_active' => 'boolean',
        ]);

        // Só atualiza senha se foi fornecida
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $validated['commission_inventories'] = $validated['user_role'] === 'commission' ? ($user->commission_inventories ?? []) : null;

        $user->update($validated);

        return redirect()->route('users.show', $user)->with('success', 'Usuário atualizado com sucesso!');
    })->name('users.update');

    Route::delete('/users/{user}', function (\App\Models\MilitaryUser $user) {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    })->name('users.destroy');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::put('/notifications/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
