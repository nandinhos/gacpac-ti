<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $parent_id = '';
    public $color = '#3B82F6';

    protected $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
        'description' => 'nullable|string|max:1000',
        'parent_id' => 'nullable|exists:categories,id',
        'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
    ];

    protected $messages = [
        'name.required' => 'O nome da categoria é obrigatório.',
        'name.unique' => 'Já existe uma categoria com este nome.',
        'parent_id.exists' => 'A categoria pai selecionada não existe.',
        'color.regex' => 'A cor deve estar no formato hexadecimal (ex: #3B82F6).',
    ];

    public function save()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id ?: null,
            'color' => $this->color,
        ]);

        session()->flash('message', 'Categoria criada com sucesso.');
        return redirect()->route('categories.index');
    }

    public function cancel()
    {
        return redirect()->route('categories.index');
    }

    public function render()
    {
        // Busca categorias disponíveis para serem pai
        $availableParents = Category::orderBy('name')->get();

        return view('livewire.categories.create', [
            'availableParents' => $availableParents,
        ])->layout('layouts.sgaiti');
    }
}
