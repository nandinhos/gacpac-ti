<?php

namespace App\Livewire\Categories;

use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        \App\Models\Category::create($this->only(['name', 'description']));

        return redirect()->route('categories.index');
    }
    public function render()
    {
        return view('livewire.categories.create');
    }
}
