<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sectors'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        Sector::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        return redirect()->route('sectors.index');
    }

    public function render()
    {
        return view('livewire.sectors.create')->layout('layouts.sgaiti');
    }
}
