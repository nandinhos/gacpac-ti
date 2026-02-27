<?php

namespace App\Livewire\Photos;

use App\Models\Asset;
use App\Models\AssetPhoto;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public Asset $asset;

    public $photos = [];
    public string $caption = '';

    public function mount(Asset $asset)
    {
        $this->asset = $asset;
    }

    public function save()
    {
        $this->validate([
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($this->photos as $photo) {
            $path = $photo->store('asset-photos/' . $this->asset->id, 'public');

            $isFirst = $this->asset->photos()->count() === 0;

            AssetPhoto::create([
                'asset_id' => $this->asset->id,
                'url' => $path,
                'caption' => $this->caption,
                'uploaded_at' => now(),
                'mime_type' => $photo->getMimeType(),
                'is_primary' => $isFirst,
                'file_size' => $photo->getSize(),
            ]);
        }

        $this->reset(['photos', 'caption']);
        session()->flash('message', 'Foto(s) enviada(s) com sucesso.');
    }

    public function setPrimary($photoId)
    {
        AssetPhoto::where('asset_id', $this->asset->id)->update(['is_primary' => false]);
        AssetPhoto::where('id', $photoId)->where('asset_id', $this->asset->id)->update(['is_primary' => true]);

        session()->flash('message', 'Foto principal definida com sucesso.');
    }

    public function deletePhoto($photoId)
    {
        $photo = AssetPhoto::where('asset_id', $this->asset->id)->findOrFail($photoId);

        Storage::disk('public')->delete($photo->url);

        $wasPrimary = $photo->is_primary;
        $photo->delete();

        if ($wasPrimary) {
            $next = AssetPhoto::where('asset_id', $this->asset->id)->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        session()->flash('message', 'Foto excluída com sucesso.');
    }

    public function render()
    {
        $assetPhotos = AssetPhoto::where('asset_id', $this->asset->id)
            ->orderByDesc('is_primary')
            ->orderByDesc('uploaded_at')
            ->get();

        return view('livewire.photos.index', [
            'assetPhotos' => $assetPhotos,
        ])->layout('layouts.sgaiti');
    }
}
