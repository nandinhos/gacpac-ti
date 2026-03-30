# SPEC — FASE 4: API Completa (Finalização da Migração React → API+Livewire)
**Status:** `[ ] Pendente`
**Pré-requisito:** Fase 3 concluída (Services criados).
**Checkpoint:** Todos os endpoints listados abaixo retornam `200 OK` com Bearer token válido.

---

## Contexto

O sistema foi migrado de React (que consumia a API) para Livewire. A API REST deve ser mantida e completada para:
1. Suportar eventual cliente React legado durante transição
2. Ser a fonte de verdade para integrações externas e mobile
3. Garantir que toda regra de negócio passe pelos Services (não duplicada)

**Módulos existentes na API:** Assets ✅, Sectors ✅, Custody ⚠️ (parcial), Inventory ✅
**Módulos ausentes na API:** Users ❌, Categories ❌, Maintenance ❌, Notifications ❌

---

## Arquivos Afetados

| Arquivo | Tipo | Ação |
|---|---|---|
| `routes/api.php` | MODIFY | Adicionar rotas dos módulos ausentes |
| `app/Http/Controllers/UserController.php` | NEW | Controller REST para usuários |
| `app/Http/Controllers/CategoryController.php` | NEW | Controller REST para categorias |
| `app/Http/Controllers/MaintenanceController.php` | NEW | Controller REST para manutenções |
| `app/Http/Controllers/NotificationController.php` | MODIFY | Completar para API (já existe parcialmente) |
| `app/Http/Resources/UserResource.php` | NEW | API Resource de usuário |
| `app/Http/Resources/CategoryResource.php` | NEW | API Resource de categoria |
| `app/Http/Resources/MaintenanceResource.php` | NEW | API Resource de manutenção |
| `app/Http/Resources/NotificationResource.php` | NEW | API Resource de notificação |
| `tests/Feature/UserControllerTest.php` | NEW | Testes do UserController |
| `tests/Feature/CategoryControllerTest.php` | NEW | Testes do CategoryController |
| `tests/Feature/MaintenanceControllerTest.php` | NEW | Testes do MaintenanceController |
| `tests/Feature/NotificationApiTest.php` | NEW | Testes da API de notificações |

---

## Contrato dos Endpoints

### Mapa de Rotas Final (após esta fase)

```
GET    /api/health                              → status da aplicação (público)
POST   /api/login                               → autenticação (público)
POST   /api/logout                              → logout (auth:sanctum)
GET    /api/me                                  → usuário autenticado (auth:sanctum)

GET    /api/dashboard/stats                     → estatísticas do dashboard

GET    /api/assets                              → listar (paginado, filtros: sector_id, category_id, search)
POST   /api/assets                              → criar
GET    /api/assets/{id}                         → detalhar
PUT    /api/assets/{id}                         → atualizar
DELETE /api/assets/{id}                         → excluir
GET    /api/assets/qr/{qrCode}                  → buscar por QR code
GET    /api/assets/utils/next-qr-code           → próximo QR code disponível
POST   /api/assets/{id}/photos                  → adicionar foto
DELETE /api/assets/{id}/photos/{photoId}        → remover foto
POST   /api/assets/{id}/maintenance             → adicionar manutenção
DELETE /api/assets/{id}/maintenance/{id}        → remover manutenção

GET    /api/sectors                             → listar setores
POST   /api/sectors                             → criar
GET    /api/sectors/{id}                        → detalhar
PUT    /api/sectors/{id}                        → atualizar
DELETE /api/sectors/{id}                        → excluir

GET    /api/categories                          ← NOVO
POST   /api/categories                          ← NOVO
GET    /api/categories/{id}                     ← NOVO
PUT    /api/categories/{id}                     ← NOVO
DELETE /api/categories/{id}                     ← NOVO

GET    /api/users                               ← NOVO
POST   /api/users                               ← NOVO
GET    /api/users/{id}                          ← NOVO
PUT    /api/users/{id}                          ← NOVO
DELETE /api/users/{id}                          ← NOVO
GET    /api/users/active                        ← NOVO
GET    /api/users/sector/{sectorId}             ← NOVO

GET    /api/assets/{assetId}/maintenance        ← NOVO (listar)
POST   /api/assets/{assetId}/maintenance        ← NOVO (criar — unificar com rota existente)
GET    /api/assets/{assetId}/maintenance/{id}   ← NOVO
PUT    /api/assets/{assetId}/maintenance/{id}   ← NOVO
DELETE /api/assets/{assetId}/maintenance/{id}   ← NOVO

GET    /api/custody                             → listar
POST   /api/custody                             → criar
GET    /api/custody/{id}                        → detalhar
PUT    /api/custody/{id}                        → atualizar
DELETE /api/custody/{id}                        → excluir
PUT    /api/custody/{id}/checkin                → fazer check-in
GET    /api/custody/next-number                 → próximo número de cautela
GET    /api/custody-reports                     → relatórios de custódia

GET    /api/inventory                           → listar
POST   /api/inventory                           → criar
GET    /api/inventory/{id}                      → detalhar
PUT    /api/inventory/{id}                      → atualizar
DELETE /api/inventory/{id}                      → excluir
POST   /api/inventory/{id}/found                → marcar item encontrado
POST   /api/inventory/{id}/uncatalogued         → adicionar item não catalogado
PUT    /api/inventory/{id}/complete             → completar inventário
POST   /api/inventory/{id}/reopen              → reabrir inventário
DELETE /api/inventory/{id}/uncatalogued/{uid}   → remover item não catalogado

GET    /api/notifications                       ← NOVO
PATCH  /api/notifications/{id}/read             ← NOVO
PATCH  /api/notifications/read-all              ← NOVO
```

---

## Padrão de Controller (seguir rigorosamente)

```php
<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->service->list($request->only(['search', 'sector_id', 'per_page']));
        return UserResource::collection($users);
    }

    public function store(Request $request): UserResource
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
        ]);

        return new UserResource($this->service->create($validated));
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->load('sector'));
    }

    public function update(Request $request, User $user): UserResource
    {
        $validated = $request->validate([
            'name'      => ['sometimes', 'string', 'max:255'],
            'email'     => ['sometimes', 'email', 'unique:users,email,' . $user->id],
            'sector_id' => ['nullable', 'exists:sectors,id'],
        ]);

        return new UserResource($this->service->update($user, $validated));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);
        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }

    public function active(): AnonymousResourceCollection
    {
        return UserResource::collection($this->service->getActive());
    }

    public function bySector(int $sectorId): AnonymousResourceCollection
    {
        return UserResource::collection($this->service->getBySector($sectorId));
    }
}
```

## Padrão de Resource (seguir rigorosamente)

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'sector_id'  => $this->sector_id,
            'sector'     => $this->whenLoaded('sector'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
```

## Padrão de Teste (seguir rigorosamente)

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_usuario_autenticado_pode_listar_usuarios(): void
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'email']]]);
    }

    public function test_usuario_nao_autenticado_nao_pode_listar(): void
    {
        $this->getJson('/api/users')->assertUnauthorized();
    }

    public function test_pode_criar_usuario(): void
    {
        $data = User::factory()->make()->only(['name', 'email']) + ['password' => 'password123'];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/users', $data);

        $response->assertCreated()
            ->assertJsonStructure(['data' => ['id', 'name', 'email']]);
    }
}
```

---

## Registro de Rotas em `routes/api.php`

Adicionar após os blocos existentes, dentro do grupo `auth:sanctum`:

```php
// Users
Route::apiResource('users', App\Http\Controllers\UserController::class);
Route::get('users/active', [App\Http\Controllers\UserController::class, 'active'])->name('api.users.active');
Route::get('users/sector/{sectorId}', [App\Http\Controllers\UserController::class, 'bySector'])->name('api.users.by-sector');

// Categories
Route::apiResource('categories', App\Http\Controllers\CategoryController::class);

// Maintenance (nested resource)
Route::apiResource('assets/{asset}/maintenance', App\Http\Controllers\MaintenanceController::class)
    ->parameters(['maintenance' => 'maintenanceRecord']);

// Notifications
Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('api.notifications.index');
Route::patch('notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('api.notifications.read');
Route::patch('notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('api.notifications.read-all');
```

---

## Validação Manual dos Endpoints

```bash
# Obter token
TOKEN=$(curl -s -X POST http://localhost:8900/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@gacpac.test","password":"password"}' | jq -r '.token')

# Testar endpoints novos
curl -s -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/users | jq '.data | length'
curl -s -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/users/active | jq
curl -s -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/categories | jq
curl -s -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/notifications | jq

# Todos devem retornar 200 com estrutura { data: [...], meta: {...} }
```

---

## Critérios de Aceite

- [ ] `GET /api/users` retorna `200` com paginação
- [ ] `GET /api/users/active` retorna `200`
- [ ] `GET /api/users/sector/{id}` retorna `200`
- [ ] `GET /api/categories` retorna `200` com paginação
- [ ] `POST /api/categories` retorna `201`
- [ ] `GET /api/assets/{id}/maintenance` retorna `200`
- [ ] `GET /api/notifications` retorna `200`
- [ ] `PATCH /api/notifications/{id}/read` retorna `200`
- [ ] Request sem token retorna `401` em todos os endpoints protegidos
- [ ] `php artisan test` — todos os testes passam (incluindo novos)
- [ ] `php artisan route:list | grep api` mostra todos os endpoints esperados

## Commit Esperado

```
feat(api): completa endpoints rest para todos os modulos do sistema

- cria UserController com rotas active e by-sector
- cria CategoryController com apiResource
- cria MaintenanceController como nested resource de assets
- completa NotificationController para api com markAsRead e markAllAsRead
- cria UserResource, CategoryResource, MaintenanceResource, NotificationResource
- adiciona testes feature para todos os novos controllers
```

## NÃO FAZER

- ❌ Não alterar as rotas `web.php` (Livewire — já funciona)
- ❌ Não alterar componentes Livewire existentes
- ❌ Não criar migrations novas (usar models existentes)
- ❌ Não remover nenhum endpoint existente da API
- ❌ Não usar `DB::` direto nos controllers — usar Services
- ❌ Não retornar arrays `[]` brutos — sempre usar Resources

## NÃO CONFUNDIR

O `NotificationController.php` já existe em `app/Http/Controllers/`. Verificar seu conteúdo antes de criar novo. Apenas adicionar os métodos `markAsRead` e `markAllAsRead` se ausentes.
