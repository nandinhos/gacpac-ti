<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\AssetPhoto;
use App\Models\Sector;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;

use function Illuminate\Support\defer;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Asset $asset;

    // Tab control
    #[Url(as: 'tab')]
    public string $activeTab = 'dados';

    // Basic Info
    public string $name = '';
    public string $brand = '';
    public string $model = '';
    public string $qr_code = '';

    // Identification
    public string $serial_number = '';
    public string $patrimony_number = '';

    // Classification
    public string $type = 'COMPUTADOR';
    public string $category = 'TI';

    // Status & Location
    public string $status = 'DISPONIVEL';
    public string $condition = 'NOVO';
    public ?int $sector_id = null;
    public ?string $location = null;

    // Financial
    public ?string $acquisition_date = null;
    public ?string $warranty_expiry = null;
    public ?string $purchase_value = null;
    public ?string $notes = null;

    // Photos
    public $uploadPhotos = [];
    public string $caption = '';
    public bool $photosReady = false;

    public function mount(Asset $asset)
    {
        $this->asset = $asset;
        $this->name = $asset->name;
        $this->brand = $asset->brand ?? '';
        $this->model = $asset->model ?? '';
        $this->qr_code = $asset->qr_code;
        $this->serial_number = $asset->serial_number ?? '';
        $this->patrimony_number = $asset->patrimony_number ?? '';
        $this->type = $asset->type ?? 'COMPUTADOR';
        $this->category = $asset->category ?? 'TI';
        $this->status = $asset->status;
        $this->condition = $asset->condition ?? 'NOVO';
        $this->sector_id = $asset->sector_id;
        $this->location = $asset->location ?? '';
        $this->acquisition_date = $asset->acquisition_date ? $asset->acquisition_date->format('Y-m-d') : null;
        $this->warranty_expiry = $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : null;
        $this->purchase_value = $asset->purchase_value;
        $this->notes = $asset->notes;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'qr_code' => ['required', 'string', Rule::unique('assets')->ignore($this->asset->id)],

            'serial_number' => ['nullable', 'string', 'max:255'],
            'patrimony_number' => ['nullable', 'string', 'max:255'],

            'type' => ['required', 'string'],
            'category' => ['required', 'string'],

            'status' => ['required', 'string'],
            'condition' => ['required', 'string'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
            'location' => ['nullable', 'string', 'max:255'],

            'acquisition_date' => ['nullable', 'date'],
            'warranty_expiry' => ['nullable', 'date', 'after_or_equal:acquisition_date'],
            'purchase_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->asset->update($validated);

        session()->flash('message', 'Ativo atualizado com sucesso.');
        return redirect()->route('assets.edit', ['asset' => $this->asset, 'tab' => $this->activeTab]);
    }

    public function updatedUploadPhotos()
    {
        $this->validate([
            'uploadPhotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'uploadPhotos.*.image' => 'O arquivo deve ser uma imagem.',
            'uploadPhotos.*.mimes' => 'Formato invalido. Use JPG, JPEG, PNG ou WEBP.',
            'uploadPhotos.*.max' => 'A imagem nao pode ultrapassar 10 MB.',
        ]);

        $this->photosReady = count($this->uploadPhotos) > 0;
    }

    public function removePhoto($index)
    {
        $photos = collect($this->uploadPhotos)->values()->all();
        unset($photos[$index]);
        $this->uploadPhotos = array_values($photos);
        $this->photosReady = count($this->uploadPhotos) > 0;
        $this->resetValidation('uploadPhotos.*');
    }

    public function savePhotos()
    {
        $this->validate([
            'uploadPhotos' => ['required', 'array', 'min:1'],
            'uploadPhotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'caption' => ['nullable', 'string', 'max:255'],
        ], [
            'uploadPhotos.required' => 'Selecione ao menos uma foto.',
            'uploadPhotos.min' => 'Selecione ao menos uma foto.',
            'uploadPhotos.*.image' => 'O arquivo deve ser uma imagem.',
            'uploadPhotos.*.mimes' => 'Formato invalido. Use JPG, JPEG, PNG ou WEBP.',
            'uploadPhotos.*.max' => 'A imagem nao pode ultrapassar 10 MB.',
        ]);

        foreach ($this->uploadPhotos as $photo) {
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

        $this->reset(['uploadPhotos', 'caption', 'photosReady']);
        session()->flash('photo-message', 'Foto(s) enviada(s) com sucesso.');
    }

    public function setPrimary($photoId)
    {
        AssetPhoto::where('asset_id', $this->asset->id)->update(['is_primary' => false]);
        AssetPhoto::where('id', $photoId)->where('asset_id', $this->asset->id)->update(['is_primary' => true]);

        session()->flash('photo-message', 'Foto principal definida com sucesso.');
    }

    public function updateCaption($photoId, $newCaption)
    {
        $newCaption = trim($newCaption);

        AssetPhoto::where('id', $photoId)
            ->where('asset_id', $this->asset->id)
            ->update(['caption' => $newCaption ?: null]);

        session()->flash('photo-message', 'Legenda atualizada com sucesso.');
    }

    public function deletePhoto($photoId)
    {
        $photo = AssetPhoto::where('asset_id', $this->asset->id)->findOrFail($photoId);

        $filePath = $photo->url;
        $wasPrimary = $photo->is_primary;
        $photo->delete();

        if ($wasPrimary) {
            $next = AssetPhoto::where('asset_id', $this->asset->id)->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        // Deleta o arquivo físico após a resposta HTTP ser enviada,
        // evitando 403 durante o morph do Livewire
        defer(fn () => Storage::disk('public')->delete($filePath))->always();

        session()->flash('photo-message', 'Foto excluída com sucesso.');
    }

    public function render()
    {
        $assetPhotos = AssetPhoto::where('asset_id', $this->asset->id)
            ->orderByDesc('is_primary')
            ->orderByDesc('uploaded_at')
            ->get();

        return view('livewire.assets.edit', [
            'sectors' => Sector::where('is_active', true)->orderBy('name')->get(),
            'types' => ['COMPUTADOR', 'MONITOR', 'IMPRESSORA', 'PERIFERICO', 'REDE', 'NOBREAK', 'OUTRO'],
            'statuses' => ['DISPONIVEL', 'EM_USO', 'MANUTENCAO', 'BAIXADO'],
            'conditions' => ['NOVO', 'BOM', 'REGULAR', 'RUIM', 'SUCATA'],
            'assetPhotos' => $assetPhotos,
            'maintenanceCount' => $this->asset->maintenanceRecords()->count(),
        ])->layout('layouts.sgaiti');
    }
}
