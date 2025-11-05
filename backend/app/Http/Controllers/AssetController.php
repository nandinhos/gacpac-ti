<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Models\Asset;
use App\Models\MilitaryUser;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Notifications\AssetCreatedNotification;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query();
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('sectorId')) {
            $query->where('sector_id', $request->sectorId);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function () use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%")
                      ->orWhere('patrimony_id', 'like', "%{$search}%");
            });
        }
        return $query->with(['sector', 'custodian'])->get();
    }

    public function store(Request $request)
    {
        try {
            // COMPATIBILIDADE TOTAL: Aceita AMBOS os formatos
            $data = $request->only([
                // Campos NOVOS + ANTIGOS + COMUNS
                'brand', 'manufacturer', 'type', 'condition', 'condition_rating',
                'patrimony_number', 'patrimony_id', 'purchase_value', 'purchase_price',
                'qr_code', 'name', 'model', 'category', 'subcategory', 'description',
                'status', 'sector_id', 'acquisition_date', 'warranty_expiry', 
                'notes', 'serial_number', 'location', 'custodian_user_id',
                'conta', 'categoria_inventario', 'bmp', 'componente', 'situacao',
                'qtd', 'valor_atualizado', 'deprec_acumulada', 'valor_liquido'
            ]);
            
            // MAPEAMENTO INTELIGENTE
            if (isset($data['manufacturer']) && !isset($data['brand'])) {
                $data['brand'] = $data['manufacturer'];
            }
            
            if (isset($data['condition_rating']) && !isset($data['condition'])) {
                $data['condition'] = $this->mapConditionRatingToString($data['condition_rating']);
            }
            
            if (isset($data['patrimony_id']) && !isset($data['patrimony_number'])) {
                $data['patrimony_number'] = $data['patrimony_id'];
            }
            
            if (isset($data['purchase_price']) && !isset($data['purchase_value'])) {
                $data['purchase_value'] = $data['purchase_price'];
            }
            
            // FALLBACKS obrigatórios
            if (!isset($data['qr_code'])) {
                $lastAsset = Asset::orderBy('id', 'desc')->first();
                $nextNumber = $lastAsset ? ($lastAsset->id + 1) : 1;
                $data['qr_code'] = 'SGTI-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
            
            if (!isset($data['name'])) {
                $data['name'] = trim(($data['brand'] ?? $data['manufacturer'] ?? '') . ' ' . ($data['model'] ?? ''));
            }
            
            if (!isset($data['type'])) {
                $data['type'] = $this->inferTypeFromCategory($data['category'] ?? 'OUTROS');
            }
            
            $asset = Asset::create($data);

            // Limpar cache do dashboard após criação
            Cache::forget('dashboard_stats');

            // Notificar almoxarifado/admin sobre novo ativo
            $adminUsers = MilitaryUser::whereIn('user_role', ['admin', 'commission'])->get();
            Notification::send($adminUsers, new AssetCreatedNotification($asset));

            return response()->json([
                'message' => 'Ativo criado com sucesso',
                'data' => $asset
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar ativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Asset $asset)
    {
    // Carregar relações com eager loading
    $asset->load(['sector', 'custodian', 'photos', 'maintenanceRecords']);

    return $asset;
    }

    public function update(Request $request, Asset $asset)
    {
        try {
            // COMPATIBILIDADE TOTAL: Aceita AMBOS os formatos (antigo + novo)
            $data = $request->only([
                // Campos NOVOS (preferidos pelo backend)
                'brand', 'type', 'condition', 'patrimony_number', 'purchase_value',
                
                // Campos ANTIGOS (compatibilidade com frontend atual)
                'manufacturer', 'condition_rating', 'patrimony_id', 'purchase_price',
                
                // Campos COMUNS (sempre aceitos)
                'qr_code', 'name', 'model', 'category', 'subcategory', 'description',
                'status', 'sector_id', 'acquisition_date', 'warranty_expiry', 
                'notes', 'serial_number', 'location', 'custodian_user_id',
                
                // Campos de inventário
                'conta', 'categoria_inventario', 'bmp', 'componente', 'situacao',
                'qtd', 'valor_atualizado', 'deprec_acumulada', 'valor_liquido'
            ]);
            
            // MAPEAMENTO INTELIGENTE: Campos antigos → novos (se não existir novo)
            if (isset($data['manufacturer']) && !isset($data['brand'])) {
                $data['brand'] = $data['manufacturer'];
            }
            
            if (isset($data['condition_rating']) && !isset($data['condition'])) {
                $data['condition'] = $this->mapConditionRatingToString($data['condition_rating']);
            }
            
            if (isset($data['patrimony_id']) && !isset($data['patrimony_number'])) {
                $data['patrimony_number'] = $data['patrimony_id'];
            }
            
            if (isset($data['purchase_price']) && !isset($data['purchase_value'])) {
                $data['purchase_value'] = $data['purchase_price'];
            }
            
            // FALLBACK para campo type obrigatório
            if (!isset($data['type'])) {
                $data['type'] = $this->inferTypeFromCategory($data['category'] ?? 'OUTROS');
            }
            
            $asset->update($data);
            $asset->refresh();

            // Limpar cache do dashboard após atualização
            Cache::forget('dashboard_stats');

            return response()->json([
                'message' => 'Ativo atualizado com sucesso',
                'data' => $asset
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar ativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mapear condition_rating (número) para condition (string)
     */
    private function mapConditionRatingToString($rating)
    {
        switch ($rating) {
            case 5: return 'NOVO';
            case 4: return 'BOM';
            case 3: return 'REGULAR';
            case 2: return 'RUIM';
            case 1: return 'INSERVIVEL';
            default: return 'REGULAR';
        }
    }

    /**
     * Inferir type baseado na category
     */
    private function inferTypeFromCategory($category)
    {
        $categoryMap = [
            'Computação' => 'COMPUTADOR',
            'Periféricos' => 'TECLADO',
            'Rede' => 'ROTEADOR',
            'Comunicações' => 'TELEFONE',
            'Audiovisual' => 'PROJETOR',
            'Armazenamento' => 'HD_EXTERNO'
        ];
        
        return $categoryMap[$category] ?? 'OUTROS';
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        // Limpar cache do dashboard após exclusão
        Cache::forget('dashboard_stats');

        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Add photo to asset
     */
    public function addPhoto(Request $request, $assetId)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
                'caption' => 'nullable|string|max:255'
            ]);

            $asset = Asset::findOrFail($assetId);
            
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoData = base64_encode(file_get_contents($photo->getPathname()));
                $mimeType = $photo->getMimeType();
                
                $photoResponse = [
                    'id' => uniqid(),
                    'url' => "data:{$mimeType};base64,{$photoData}",
                    'caption' => $request->input('caption', ''),
                    'created_at' => now()->toISOString()
                ];

                // Armazenar foto no cache para persistir entre requests
                $cacheKey = "asset_{$assetId}_photos";
                $existingPhotos = cache()->get($cacheKey, []);
                $existingPhotos[] = $photoResponse;
                cache()->put($cacheKey, $existingPhotos, 3600); // Cache por 1 hora
                
                return response()->json($photoResponse, 201);
            }

            return response()->json(['error' => 'No photo uploaded'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao enviar foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete photo from asset
     */
    public function deletePhoto($assetId, $photoId)
    {
        try {
            // Remover foto do cache
            $cacheKey = "asset_{$assetId}_photos";
            $existingPhotos = cache()->get($cacheKey, []);
            
            // Filtrar para remover a foto com o ID especificado
            $filteredPhotos = array_filter($existingPhotos, function($photo) use ($photoId) {
                return $photo['id'] !== $photoId;
            });
            
            // Reindexar o array
            $filteredPhotos = array_values($filteredPhotos);
            
            // Atualizar cache
            cache()->put($cacheKey, $filteredPhotos, 3600);
            
            return response()->json(['message' => 'Photo deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao excluir foto: ' . $e->getMessage()
            ], 500);
        }
    }
}