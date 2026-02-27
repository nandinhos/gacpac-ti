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
    public $selectAllPending = false;
    public $selectAllFound = false;

    // Finalize/Reopen Logic
    public $showReopenModal = false;
    public $reopenJustification = '';
    public $showFinalizeModal = false;

    public function openFinalizeModal()
    {
        if ($this->inventory->status === 'Concluído') return;
        $this->showFinalizeModal = true;
        $this->dispatch('open-modal', 'finalize-modal');
    }

    public function closeFinalizeModal()
    {
        $this->showFinalizeModal = false;
        $this->dispatch('close-modal', 'finalize-modal');
    }

    public function openReopenModal()
    {
        $this->reopenJustification = '';
        $this->showReopenModal = true;
        $this->dispatch('open-modal', 'reopen-modal');
    }

    public function closeReopenModal()
    {
        $this->showReopenModal = false;
        $this->dispatch('close-modal', 'reopen-modal');
    }

    public function confirmReopen()
    {
        $this->validate([
            'reopenJustification' => 'required|string|min:5|max:255',
        ]);

        $this->inventory->update([
            'status' => 'Reaberto',
            'end_date' => null,
        ]);

        \App\Models\ReopenHistory::create([
            'inventory_id' => $this->inventory->id,
            'reopened_by_user_id' => auth()->id(),
            'reopened_at' => now(),
            'justification' => $this->reopenJustification,
        ]);

        session()->flash('message', 'Inventário reaberto com sucesso.');
        
        $this->showReopenModal = false;
        $this->dispatch('close-modal', 'reopen-modal');
        
        // Opcional: Recarregar a página para liberar os campos
        return redirect()->route('inventory.show', $this->inventory);
    }

    public function updatedSelectAllPending($value)
    {
        if ($value) {
            $foundAssetIds = InventoryAsset::where('inventory_id', $this->inventory->id)->pluck('asset_id');
            
            $query = Asset::query();
            if ($this->inventory->sector_id) {
                $query->where('sector_id', $this->inventory->sector_id);
            }

            $this->selectedPending = $query->whereNotIn('id', $foundAssetIds)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedPending = [];
        }
    }

    public function updatedSelectAllFound($value)
    {
        if ($value) {
            $this->selectedFound = InventoryAsset::where('inventory_id', $this->inventory->id)
                ->pluck('asset_id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedFound = [];
        }
    }

    public function mount(InventoryRecord $inventory)
    {
        $this->inventory = $inventory->load(['sector', 'responsibleUser']);
        $this->notes = $inventory->notes;
    }

    public function findAsset()
    {
        if ($this->inventory->status === 'Concluído') return;
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
        if ($this->inventory->status === 'Concluído') return;
        $this->validate(['uncataloguedDescription' => 'required|string|max:255']);

        UncataloguedItem::create([
            'inventory_id' => $this->inventory->id,
            'description' => $this->uncataloguedDescription,
            'found_date' => now(),
            'created_by_user_id' => auth()->id(),
        ]);

        $this->uncataloguedDescription = '';
    }

    public $editingUncataloguedId = null;
    public $editingDescription = '';

    public function editUncatalogued($id)
    {
        $item = UncataloguedItem::find($id);
        if (!$item) return;

        // Permission check
        if ($item->created_by_user_id !== auth()->id() && auth()->user()->role !== 'admin') { // Assumindo role 'admin'
             return;
        }

        $this->editingUncataloguedId = $id;
        $this->editingDescription = $item->description;
    }

    public function cancelEditUncatalogued()
    {
        $this->editingUncataloguedId = null;
        $this->editingDescription = '';
    }

    public function updateUncatalogued()
    {
        if ($this->inventory->status === 'Concluído') return;
        
        $this->validate(['editingDescription' => 'required|string|max:255']);

        $item = UncataloguedItem::find($this->editingUncataloguedId);
        
        if ($item) {
             // Permission check again
            if ($item->created_by_user_id !== auth()->id() && auth()->user()->role !== 'admin') {
                 return;
            }

            $item->update(['description' => $this->editingDescription]);
        }

        $this->editingUncataloguedId = null;
        $this->editingDescription = '';
    }

    public function removeUncatalogued($id)
    {
        if ($this->inventory->status === 'Concluído') return;
        UncataloguedItem::where('inventory_id', $this->inventory->id)->where('id', $id)->delete();
    }

    public function bulkFind()
    {
        if ($this->inventory->status === 'Concluído') return;
        if (empty($this->selectedPending)) return;

        foreach ($this->selectedPending as $assetId) {
            InventoryAsset::firstOrCreate([
                'inventory_id' => $this->inventory->id,
                'asset_id' => $assetId,
            ]);
        }

        $this->selectedPending = [];
        $this->selectAllPending = false;
    }

    public function bulkRemove()
    {
        if ($this->inventory->status === 'Concluído') return;
        if (empty($this->selectedFound)) return;

        InventoryAsset::where('inventory_id', $this->inventory->id)
            ->whereIn('asset_id', $this->selectedFound)
            ->delete();

        $this->selectedFound = [];
        $this->selectAllFound = false;
    }

    public function finalize()
    {
        if ($this->inventory->status === 'Concluído') return;
        $this->inventory->update([
            'status' => 'Concluído',
            'end_date' => now(),
            'notes' => $this->notes,
        ]);

        $this->showFinalizeModal = false;
        $this->dispatch('close-modal', 'finalize-modal');

        session()->flash('message', 'Inventário concluído com sucesso.');
        return redirect()->route('inventory.index');
    }

    public function render()
    {
        $foundAssetIds = InventoryAsset::where('inventory_id', $this->inventory->id)->pluck('asset_id');
        
        $foundAssets = Asset::whereIn('id', $foundAssetIds)->get();
        
        $query = Asset::query();
        if ($this->inventory->sector_id) {
            $query->where('sector_id', $this->inventory->sector_id);
        }
        $pendingAssets = $query->whereNotIn('id', $foundAssetIds)->get();

        $uncataloguedItems = UncataloguedItem::where('inventory_id', $this->inventory->id)->get();

        return view('livewire.inventory.show', [
            'foundAssets' => $foundAssets,
            'pendingAssets' => $pendingAssets,
            'uncataloguedItems' => $uncataloguedItems,
        ])->layout('layouts.sgaiti');
    }
}
