<?php

namespace App\Livewire\Custody;

use App\Models\Asset;
use App\Models\CustodyLog;
use App\Models\User; // Changed from MilitaryUser
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $user_id;
    public $checkout_date;
    public $notes;
    public $cautela_number;

    // Asset Selection
    public $searchAsset = '';
    public $selectedAssets = [];

    public function mount()
    {
        $this->checkout_date = date('Y-m-d');
        $this->generateNumber();
    }

    public function generateNumber()
    {
        $lastId = CustodyLog::max('id') ?? 0;
        $this->cautela_number = 'CAUTELA-' . date('Y') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getAvailableAssetsProperty()
    {
        return Asset::where('status', 'DISPONIVEL')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchAsset . '%')
                      ->orWhere('qr_code', 'like', '%' . $this->searchAsset . '%')
                      ->orWhere('patrimony_number', 'like', '%' . $this->searchAsset . '%');
            })
            ->limit(50) // Limit to prevent overload
            ->get();
    }

    public function toggleAsset($assetId)
    {
        if (in_array($assetId, $this->selectedAssets)) {
            $this->selectedAssets = array_diff($this->selectedAssets, [$assetId]);
        } else {
            $this->selectedAssets[] = $assetId;
        }
    }

    public function save()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'checkout_date' => 'required|date',
            'selectedAssets' => 'required|array|min:1',
            'selectedAssets.*' => 'exists:assets,id', // Basic existence check
            'cautela_number' => 'required|unique:custody_logs,cautela_number',
        ]);

        // Race condition check: Verify if all selected assets are still available
        $unavailableAssets = Asset::whereIn('id', $this->selectedAssets)
                                  ->where('status', '!=', 'DISPONIVEL')
                                  ->count();

        if ($unavailableAssets > 0) {
            $this->addError('selectedAssets', 'Um ou mais itens selecionados não estão mais disponíveis.');
            // Refresh the list
            $this->dispatch('assets-updated'); 
            return;
        }

        DB::transaction(function () {
            $custody = CustodyLog::create([
                'cautela_number' => $this->cautela_number,
                'user_id' => $this->user_id,
                'checkout_date' => $this->checkout_date,
                'notes' => $this->notes,
            ]);

            $custody->assets()->attach($this->selectedAssets);

            // Update assets status
            Asset::whereIn('id', $this->selectedAssets)->update(['status' => 'EM_USO', 'custodian_user_id' => $this->user_id]);
        });

        return redirect()->route('custody.index');
    }

    public function render()
    {
        return view('livewire.custody.create', [
            'users' => User::orderBy('name')->get(),
            'assetsList' => $this->availableAssets, // Use computed property
        ])->layout('layouts.sgaiti');
    }
}
