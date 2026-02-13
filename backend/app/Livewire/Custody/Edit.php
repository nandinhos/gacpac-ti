<?php

namespace App\Livewire\Custody;

use App\Models\Asset;
use App\Models\CustodyLog;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public CustodyLog $custodyLog;
    public $notes;
    public $cautela_number;
    public $user_name;
    public $checkout_date;
    public $checkin_date;

    public function mount(CustodyLog $custodyLog)
    {
        $this->custodyLog = $custodyLog;
        $this->cautela_number = $custodyLog->cautela_number;
        $this->user_name = $custodyLog->user->rank . ' ' . $custodyLog->user->name;
        $this->checkout_date = $custodyLog->checkout_date ? $custodyLog->checkout_date->format('Y-m-d') : '';
        $this->checkin_date = $custodyLog->checkin_date ? $custodyLog->checkin_date->format('Y-m-d') : null;
        $this->notes = $custodyLog->notes;
    }

    public $searchAsset = '';

    public function getAvailableAssetsProperty()
    {
        return Asset::where('status', 'DISPONIVEL')
            ->where(function ($query) {
                $query->where('name', 'ilike', '%' . $this->searchAsset . '%')
                      ->orWhere('qr_code', 'ilike', '%' . $this->searchAsset . '%')
                      ->orWhere('patrimony_number', 'ilike', '%' . $this->searchAsset . '%');
            })
            ->limit(10)
            ->get();
    }

    public function addAsset(Asset $asset)
    {
        if ($this->custodyLog->checkin_date) {
            return;
        }

        if ($asset->status !== 'DISPONIVEL') {
            $this->addError('searchAsset', 'Asset is not available.');
            return;
        }

        DB::transaction(function () use ($asset) {
            $this->custodyLog->assets()->attach($asset->id);
            $asset->update(['status' => 'EM_USO', 'custodian_user_id' => $this->custodyLog->user_id]);
        });

        $this->searchAsset = '';
    }

    public function removeAsset(Asset $asset)
    {
        if ($this->custodyLog->checkin_date) {
            return;
        }

        DB::transaction(function () use ($asset) {
            $this->custodyLog->assets()->detach($asset->id);
            $asset->update(['status' => 'DISPONIVEL', 'custodian_user_id' => null]);
        });
    }

    public function closeCustody()
    {
        DB::transaction(function () {
            $this->custodyLog->update([
                'checkin_date' => now(),
            ]);

            // Release assets
            foreach ($this->custodyLog->assets as $asset) {
                $asset->update(['status' => 'DISPONIVEL', 'custodian_user_id' => null]);
            }
        });

        return redirect()->route('custody.index');
    }

    public function render()
    {
        return view('livewire.custody.edit', [
            'assets' => $this->custodyLog->assets()->get(), // Ensure fresh list
            'availableAssets' => $this->availableAssets,
        ])->layout('layouts.sgaiti');
    }
}
