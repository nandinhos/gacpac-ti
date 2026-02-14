<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Sector $sector;
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;

    public function mount(Sector $sector)
    {
        $this->sector = $sector;
        $this->name = $sector->name;
        $this->description = $sector->description ?? '';
        $this->is_active = (bool) $sector->is_active;
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('sectors')->ignore($this->sector->id)],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $this->sector->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        return redirect()->route('sectors.index');
    }

    public function render()
    {
        return view('livewire.sectors.edit')->layout('layouts.sgaiti');
    }
}
