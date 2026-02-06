<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Edit extends Component
{
    public Category $category;
    public $name = '';
    public $description = '';
    public $parent_id = '';
    public $color = '#3B82F6';

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id ?? '';
        $this->color = $category->color ?? '#3B82F6';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $this->category->id,
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome da categoria é obrigatório.',
        'name.unique' => 'Já existe uma categoria com este nome.',
        'parent_id.exists' => 'A categoria pai selecionada não existe.',
        'color.regex' => 'A cor deve estar no formato hexadecimal (ex: #3B82F6).',
    ];

    public function save()
    {
        $this->validate();

        // Verifica se não está tentando ser pai dela mesma
        if ($this->parent_id == $this->category->id) {
            session()->flash('error', 'Uma categoria não pode ser pai dela mesma.');
            return;
        }

        // Verifica ciclo na hierarquia
        if ($this->parent_id) {
            $parent = Category::find($this->parent_id);
            if (!$parent->canBeParentOf($this->category->id)) {
                session()->flash('error', 'A categoria pai selecionada criaria um ciclo na hierarquia.');
                return;
            }
        }

        $this->category->update([
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id ?: null,
            'color' => $this->color,
        ]);

        session()->flash('message', 'Categoria atualizada com sucesso.');
        return redirect()->route('categories.index');
    }

    public function cancel()
    {
        return redirect()->route('categories.index');
    }

    public function render()
    {
        // Busca categorias disponíveis para serem pai (exclui a atual e seus descendentes)
        $availableParents = Category::where('id', '!=', $this->category->id)
            ->orderBy('name')
            ->get()
            ->filter(function ($cat) {
                return $cat->canBeParentOf($this->category->id);
            });

        return view('livewire.categories.edit', [
            'availableParents' => $availableParents,
        ])->layout('layouts.sgaiti');
    }
}
