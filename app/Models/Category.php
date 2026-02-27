<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'color',
    ];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * Relação: Categoria pai
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relação: Categorias filhas
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relação: Ativos desta categoria
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Verifica se pode ser pai (não pode ser filho de si mesma)
     */
    public function canBeParentOf(int $categoryId): bool
    {
        if ($this->id === $categoryId) {
            return false;
        }

        // Verifica recursivamente se o categoryId é ancestral desta categoria
        $current = $this->parent;
        while ($current) {
            if ($current->id === $categoryId) {
                return false;
            }
            $current = $current->parent;
        }

        return true;
    }

    /**
     * Retorna o caminho completo da categoria (ancestrais + nome)
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $current = $this->parent;

        while ($current) {
            array_unshift($path, $current->name);
            $current = $current->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Retorna o nível na hierarquia (0 = raiz)
     */
    public function getLevelAttribute(): int
    {
        $level = 0;
        $current = $this->parent;

        while ($current) {
            $level++;
            $current = $current->parent;
        }

        return $level;
    }
}
