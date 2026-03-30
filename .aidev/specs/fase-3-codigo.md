# SPEC — FASE 3: Correções de Código Base e Arquitetura
**Status:** `[ ] Pendente`
**Ambiente:** Laravel Sail (Docker) — todos os comandos PHP/Artisan/Composer/Pint rodam dentro do container.
**Pré-requisito:** Fase 2 concluída.
**Checkpoint:** `./vendor/bin/sail artisan test` sem falhas + `./vendor/bin/sail exec laravel.test ./vendor/bin/pint --test` sem erros.

---

## Referência Rápida de Comandos Sail

```bash
./vendor/bin/sail artisan [comando]             # Artisan dentro do container
./vendor/bin/sail composer [comando]            # Composer dentro do container
./vendor/bin/sail exec laravel.test [binário]   # Executar binários no container
./vendor/bin/sail shell                         # Abrir shell interativo no container
```

---

## Contexto

Esta fase corrige bugs de roteamento, segurança da API, metadados do projeto e cria a camada de Services que elimina duplicação de lógica de negócio entre Controllers (API) e componentes Livewire.

**Por que Services?** O sistema tem lógica duplicada: `AssetController` (API) e `Livewire/Assets/` fazem operações similares sem compartilhar código. Services centralizam essa lógica para que ambos os lados consumam o mesmo código.

---

## Arquivos Afetados

| Arquivo | Tipo | Ação |
|---|---|---|
| `routes/web.php` | MODIFY | Remover rota duplicada linha 46 |
| `routes/api.php` | MODIFY | Remover rota de teste, adicionar throttle |
| `composer.json` | MODIFY | Corrigir nome e descrição do projeto |
| `app/Services/AssetService.php` | NEW | Lógica de negócio de assets |
| `app/Services/CustodyService.php` | NEW | Lógica de negócio de custódia |
| `app/Services/InventoryService.php` | NEW | Lógica de negócio de inventário |
| `app/Services/UserService.php` | NEW | Lógica de negócio de usuários |
| `app/Services/SectorService.php` | NEW | Lógica de negócio de setores |
| `app/Services/CategoryService.php` | NEW | Lógica de negócio de categorias |
| `app/Http/Controllers/AssetController.php` | MODIFY | Injetar AssetService |
| `app/Http/Controllers/CustodyLogController.php` | MODIFY | Injetar CustodyService |
| `app/Http/Controllers/InventoryRecordController.php` | MODIFY | Injetar InventoryService |
| `tests/Unit/Services/AssetServiceTest.php` | NEW | Teste unitário do AssetService |

---

## Ações Exatas

### Passo 0 — Verificar que Sail está rodando

```bash
./vendor/bin/sail ps
# Se não estiver: ./vendor/bin/sail up -d
```

### Passo 1 — Remover rota duplicada em `routes/web.php`

**Arquivo:** `routes/web.php`, linhas 45–46:
```php
Route::livewire('/notifications', App\Livewire\Notifications\Index::class)->name('notifications.index');
Route::livewire('/notifications', App\Livewire\Notifications\Index::class)->name('notifications.index');
```
Manter apenas a linha 45. Remover a linha 46 (idêntica).

### Passo 2 — Corrigir `routes/api.php`

**Remover rota de teste pública (linhas 7-9):**
```diff
-Route::get('test', function () {
-    return 'api ok';
-});
-
 Route::get('health', function () {
```

**Adicionar throttle ao grupo protegido (linha ~19):**
```diff
-Route::name('api.')->middleware(['auth:sanctum'])->group(function () {
+Route::name('api.')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
```

> O throttle `api` usa o limite padrão do Laravel (60 req/min). Se precisar customizar, editar `bootstrap/app.php` na seção `withMiddleware`.

### Passo 3 — Corrigir metadados em `composer.json`

```diff
-"name": "laravel/laravel",
+"name": "gacpac/gacpac-ti",
-"description": "The skeleton application for the Laravel framework.",
+"description": "Sistema de Gestao de Ativos e Cautelas - SGAITI",
```

### Passo 4 — Limpar cache de rotas (dentro do container)

```bash
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan config:clear
```

### Passo 5 — Criar `app/Services/AssetService.php`

```php
<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Asset::query()
            ->with(['category', 'sector'])
            ->when(isset($filters['sector_id']), fn($q) => $q->where('sector_id', $filters['sector_id']))
            ->when(isset($filters['category_id']), fn($q) => $q->where('category_id', $filters['category_id']))
            ->when(isset($filters['search']), fn($q) => $q->where('name', 'ilike', "%{$filters['search']}%"))
            ->paginate($filters['per_page'] ?? 15);
    }

    public function findByQrCode(string $qrCode): Asset
    {
        return Asset::where('qr_code', $qrCode)->firstOrFail();
    }

    public function create(array $data): Asset
    {
        return Asset::create($data);
    }

    public function update(Asset $asset, array $data): Asset
    {
        $asset->update($data);
        return $asset->fresh();
    }

    public function delete(Asset $asset): void
    {
        $asset->delete();
    }

    public function getNextQrCode(): string
    {
        $last = Asset::orderByDesc('qr_code')->value('qr_code');
        if (!$last) {
            return 'QR-00001';
        }
        $number = (int) str_replace('QR-', '', $last);
        return 'QR-' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
    }
}
```

> Os demais Services seguem o **mesmo padrão**, substituindo `Asset` pelo Model correspondente:
> - `CustodyService` → Model: `CustodyLog`
> - `InventoryService` → Model: `InventoryRecord`
> - `UserService` → Model: `User`
> - `SectorService` → Model: `Sector`
> - `CategoryService` → Model: `Category`

### Passo 6 — Criar testes dos Services

**Arquivo:** `tests/Unit/Services/AssetServiceTest.php`

```php
<?php

namespace Tests\Unit\Services;

use App\Models\Asset;
use App\Services\AssetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetServiceTest extends TestCase
{
    use RefreshDatabase;

    private AssetService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AssetService();
    }

    public function test_pode_listar_assets(): void
    {
        Asset::factory()->count(3)->create();
        $result = $this->service->list();
        $this->assertCount(3, $result->items());
    }

    public function test_pode_criar_asset(): void
    {
        $data = Asset::factory()->make()->toArray();
        $asset = $this->service->create($data);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertDatabaseHas('assets', ['id' => $asset->id]);
    }
}
```

### Passo 7 — Executar os testes dentro do container

```bash
./vendor/bin/sail artisan test
```

### Passo 8 — Verificar estilo de código dentro do container

```bash
./vendor/bin/sail exec laravel.test ./vendor/bin/pint --test
```

Se houver erros de estilo, corrigir automaticamente:
```bash
./vendor/bin/sail exec laravel.test ./vendor/bin/pint
```

### Passo 9 — Verificar rotas sem duplicação

```bash
./vendor/bin/sail artisan route:list | grep notifications
# Deve mostrar apenas UMA rota notifications.index
```

### Passo 10 — Verificar rota de API com throttle

```bash
./vendor/bin/sail artisan route:list | grep api
# Deve mostrar o grupo com middleware throttle:api
```

---

## Critérios de Aceite

- [ ] `routes/web.php` sem rota `notifications.index` duplicada
- [ ] `routes/api.php` sem rota `/api/test`
- [ ] `routes/api.php` com middleware `throttle:api` no grupo protegido
- [ ] `composer.json` com `name: gacpac/gacpac-ti`
- [ ] `app/Services/AssetService.php` existe com os 5+ métodos
- [ ] `app/Services/CustodyService.php` existe
- [ ] `app/Services/InventoryService.php` existe
- [ ] `app/Services/UserService.php` existe
- [ ] `app/Services/SectorService.php` existe
- [ ] `app/Services/CategoryService.php` existe
- [ ] `./vendor/bin/sail artisan test` — todos os testes passam
- [ ] `./vendor/bin/sail exec laravel.test ./vendor/bin/pint --test` — sem erros

## Commit Esperado

```
refactor(core): cria camada de services e corrige bugs de rota e segurança

- remove rota duplicada notifications.index em web.php
- remove rota publica /api/test de api.php
- adiciona throttle:api ao grupo protegido da api
- corrige nome e descricao do projeto no composer.json
- cria app/Services com 6 services (Asset, Custody, Inventory, User, Sector, Category)
- refatora AssetController para usar AssetService
- adiciona AssetServiceTest
```

## NÃO FAZER

- ❌ Não rodar `php artisan` diretamente no host — usar `./vendor/bin/sail artisan`
- ❌ Não rodar `./vendor/bin/pint` diretamente no host — usar `./vendor/bin/sail exec laravel.test ./vendor/bin/pint`
- ❌ Não remover o endpoint `/api/health` (usado para monitoramento)
- ❌ Não alterar a lógica dos componentes Livewire existentes nesta fase (isso é Fase 4)
- ❌ Não criar novos Controllers de API nesta fase (isso é Fase 4)
- ❌ Não alterar migrations ou models
