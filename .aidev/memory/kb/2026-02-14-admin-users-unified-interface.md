# Lições Aprendidas: Interface Unificada Admin/Users

**Data:** 2026-02-14  
**Categoria:** architecture  
**Stack:** Laravel, PHP, Livewire  
**Severidade:** Médio  
**Tags:** livewire, eloquent, routing, ui-patterns, defensive-programming

## Resumo

Desenvolvimento da interface unificada de gestão de usuários em `/admin/users` revelou padrões importantes sobre relacionamentos Eloquent, consistência visual e organização de código Livewire.

## Lições Aprendidas

### 1. Relacionamentos Eloquent - Nome Consistente

**Problema:** Tentativa de carregar relacionamento `custodyLogs.asset` (singular) quando o método no model está definido como `assets()` (plural).

```php
// ERRO - relacionamento não existe
$user->load(['custodyLogs.asset']);

// CORRETO - nome do método no model
$user->load(['custodyLogs.assets']);
```

**Impacto:** `RelationNotFoundException` ao abrir página de usuários com cautelas.

**Prevenção:** Sempre verificar o nome exato do método de relacionamento no model antes de usar em `load()` ou `with()`.

---

### 2. Spatie Roles - Guard Duplicado

**Problema:** Seeder criava roles para dois guards (`web` e `sanctum`), resultando em 8 roles ao invés de 4.

```php
// ERRO - cria duplicatas
$guards = ['web', 'sanctum'];
foreach ($guards as $guard) {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
}

// CORRETO - apenas web
Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
```

**Impacto:** Checkboxes de roles duplicadas na interface de edição.

**Prevenção:** Manter apenas um guard no seeder e filtrar `Role::where('guard_name', 'web')` nos componentes.

---

### 3. QueryString para Persistência de Estado

**Solução:** Usar atributo `#[Url]` do Livewire 3 para manter aba ativa após refresh.

```php
use Livewire\Attributes\Url;

#[Url]
public $tab = 'profile';
```

**Benefício:** URL atualiza automaticamente (`?tab=custody`), mantendo estado após F5.

---

### 4. Separação de Responsabilidades nas Abas

**Problema:** Aba "Ativos" mostrava todos os ativos do usuário, incluindo os em cautela.

**Solução:** 
- **Ativos:** Apenas itens do setor do usuário que NÃO estão em cautela
- **Cautelas:** Agrupados por log de cautela com seus respectivos itens

```php
// Ativos disponíveis (não em cautela)
Asset::where('sector_id', $user->sector_id)
    ->whereNotIn('id', $custodyAssetIds)
    ->get();

// Cautelas ativas
$user->custodyLogs()
    ->whereNull('checkin_date')
    ->with('assets')
    ->get();
```

---

### 5. Fallback em Campos Legados

**Problema:** Dados armazenados em campos legados (`patrimony_id`, `name`) não apareciam porque a view usava apenas campos novos (`patrimony_number`, `description`).

```php
// ERRO - campos podem estar vazios
{{ $asset->patrimony_number }}
{{ $asset->description }}

// CORRETO - fallback para campos legados
{{ $asset->patrimony_number ?? $asset->patrimony_id ?? 'N/A' }}
{{ $asset->description ?? $asset->name ?? 'Sem descrição' }}
```

---

### 6. Consistência Visual - Padrões do Projeto

**Padrões identificados:**
- Cores: `fab-blue` (#002776) e `fab-blue-hover` (#001a4d)
- Layout: Cards brancos com `shadow-sm sm:rounded-lg`
- Abas: Borda inferior azul quando ativas
- Botões: Posicionados no header direito com ícones Heroicons
- Mensagens: Animação Alpine.js com fade out automático

---

### 7. Estrutura de Rotas Admin

**Padrão adotado:**
```php
Route::middleware(['can:users.manage'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', Index::class)->name('users.index');
        Route::get('/users/create', Create::class)->name('users.create');
        Route::get('/users/{user}', Show::class)->name('users.show');
        Route::get('/users/{user}/edit', Edit::class)->name('users.edit');
    });
```

---

## Checklist para Futuros CRUDs

- [ ] Verificar relacionamentos Eloquent (singular vs plural)
- [ ] Filtrar roles por guard (`where('guard_name', 'web')`)
- [ ] Usar `#[Url]` para persistência de estado de abas
- [ ] Aplicar cores `fab-blue` do Tailwind config
- [ ] Adicionar fallback para campos legados
- [ ] Seguir padrão de cards com `shadow-sm sm:rounded-lg`
- [ ] Posicionar botões no header com ícones
- [ ] Implementar mensagens com Alpine.js fade out
- [ ] Adicionar links clicáveis em tabelas (cursor-pointer)

## Referências

- Arquivos criados:
  - `app/Livewire/Admin/Users/Index.php`
  - `app/Livewire/Admin/Users/Create.php`
  - `app/Livewire/Admin/Users/Show.php`
  - `app/Livewire/Admin/Users/Edit.php`
  - `resources/views/livewire/admin/users/*.blade.php`

- Commits: `f2b2f2a`, `245de6f`

## Autor

AI Dev Agent - Refatoração Admin/Users Sprint 10
