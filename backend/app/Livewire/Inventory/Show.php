<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryRecord;
use App\Models\Asset;
use App\Models\InventoryAsset;
use App\Models\UncataloguedItem;
use Livewire\Component;

class Show extends Component
{
    public InventoryRecord $inventory;
    public $qrCodeInput = '';
    public $uncataloguedDescription = '';
    public $notes = '';
    
    public $selectedPending = [];
    public $selectedFound = [];

    public function mount(InventoryRecord $inventory)
    {
        $this->inventory = $inventory->load(['sector', 'responsibleUser']);
        $this->notes = $inventory->notes;
    }

    public function findAsset()
    {
        $this->validate(['qrCodeInput' => 'required|string']);

        $asset = Asset::where('qr_code', $this->qrCodeInput)
            ->orWhere('serial_number', $this->qrCodeInput)
            ->first();

        if (!$asset) {
            $this->addError('qrCodeInput', 'Ativo não encontrado.');
            return;
        }

        // Check if already in inventory
        $exists = InventoryAsset::where('inventory_id', $this->inventory->id)
            ->where('asset_id', $asset->id)
            ->exists();

        if ($exists) {
            $this->addError('qrCodeInput', 'Este ativo já foi registrado neste inventário.');
            return;
        }

        InventoryAsset::create([
            'inventory_id' => $this->inventory->id,
            'asset_id' => $asset->id,
        ]);

        $this->qrCodeInput = '';
        $this->dispatch('asset-found');
    }

    public function addUncatalogued()
    {
        $this->validate(['uncataloguedDescription' => 'required|string|max:255']);

        UncataloguedItem::create([
            'inventory_id' => $this->inventory->id,
            'description' => $this->uncataloguedDescription,
            'found_date' => now(),
        ]);

        $this->uncataloguedDescription = '';
    }

    public function removeUncatalogued($id)
    {
        UncataloguedItem::where('inventory_id', $this->inventory->id)->where('id', $id)->delete();
    }

    public function bulkFind()
    {
        if (empty($this->selectedPending)) return;

        foreach ($this->selectedPending as $assetId) {
            InventoryAsset::firstOrCreate([
                'inventory_id' => $this->inventory->id,
                'asset_id' => $assetId,
            ]);
        }

        $this->selectedPending = [];
    }

    public function bulkRemove()
    {
        if (empty($this->selectedFound)) return;

        InventoryAsset::where('inventory_id', $this->inventory->id)
            ->whereIn('asset_id', $this->selectedFound)
            ->delete();

        $this->selectedFound = [];
    }

    public function finalize()
    {
        $this->inventory->update([
            'status' => 'Concluído',
            'end_date' => now(),
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Inventário concluído com sucesso.');
        return redirect()->route('inventory.index');
    }

    public function render()
    {
        $foundAssetIds = InventoryAsset::where('inventory_id', $this->inventory->id)->pluck('asset_id');
        
        $foundAssets = Asset::whereIn('id', $foundAssetIds)->get();
        
        $pendingAssets = Asset::where('sector_id', $this->inventory->sector_id)
            ->whereNotIn('id', $foundAssetIds)
            ->get();

        $uncataloguedItems = UncataloguedItem::where('inventory_id', $this->inventory->id)->get();

        return view('livewire.inventory.show', [
            'foundAssets' => $foundAssets,
            'pendingAssets' => $pendingAssets,
            'uncataloguedItems' => $uncataloguedItems,
        ])->layout('layouts.sgaiti');
    }
}
