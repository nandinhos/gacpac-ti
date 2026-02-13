<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $parentId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'parentId' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedParentId()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        
        // Verifica se tem ativos vinculados
        if ($category->assets()->count() > 0) {
            session()->flash('error', 'Não é possível excluir: existem ativos vinculados a esta categoria.');
            return;
        }

        // Verifica se tem categorias filhas
        if ($category->children()->count() > 0) {
            session()->flash('error', 'Não é possível excluir: existem subcategorias vinculadas.');
            return;
        }

        $category->delete();
        session()->flash('message', 'Categoria excluída com sucesso.');
    }

    public function render()
    {
        $query = Category::with(['parent', 'children'])
            ->withCount('assets');

        if ($this->search) {
            $query->where('name', 'ilike', '%' . $this->search . '%');
        }

        if ($this->parentId === 'root') {
            $query->whereNull('parent_id');
        } elseif ($this->parentId) {
            $query->where('parent_id', $this->parentId);
        }

        $categories = $query->orderBy('name')->paginate(10);

        // Carrega todas as categorias para o filtro de pai
        $allCategories = Category::orderBy('name')->get();

        return view('livewire.categories.index', [
            'categories' => $categories,
            'allCategories' => $allCategories,
        ])->layout('layouts.sgaiti');
    }
}
