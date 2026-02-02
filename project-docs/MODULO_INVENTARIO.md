# Modulo Inventario - Levantamento Completo

## Visao Geral

O modulo de Inventario permite realizar a conferencia fisica de ativos de TI por setor, identificando:
- **Itens Encontrados**: Ativos catalogados que foram fisicamente localizados
- **Itens Pendentes**: Ativos do setor que ainda nao foram conferidos
- **Itens Nao Catalogados**: Itens encontrados que nao estao no sistema

---

## Estrutura de Dados

### Tabela: `inventory_records`

| Campo | Tipo | Descricao |
|-------|------|-----------|
| id | bigint | PK |
| commission_number | string (nullable, unique) | Numero da comissao de inventario |
| start_date | date | Data de inicio |
| end_date | date (nullable) | Data de conclusao |
| sector_id | FK -> sectors | Setor sendo inventariado |
| responsible_user_id | FK -> military_users | Militar responsavel |
| status | enum | 'Em Andamento', 'Concluido', 'Reaberto' |
| notes | text (nullable) | Observacoes de auditoria |
| timestamps | | created_at, updated_at |

### Tabela: `inventory_assets` (Itens Encontrados)

| Campo | Tipo | Descricao |
|-------|------|-----------|
| id | bigint | PK |
| inventory_id | FK -> inventory_records | Inventario |
| asset_id | FK -> assets | Ativo encontrado |
| observation | text (nullable) | Observacao do item |
| timestamps | | created_at, updated_at |

### Tabela: `uncatalogued_items` (Itens Nao Catalogados)

| Campo | Tipo | Descricao |
|-------|------|-----------|
| id | bigint | PK |
| inventory_id | FK -> inventory_records | Inventario |
| description | text | Descricao do item |
| location | string (nullable) | Localizacao onde foi encontrado |
| found_date | date | Data em que foi encontrado |
| timestamps | | created_at, updated_at |

### Tabela: `reopen_history` (Historico de Reaberturas)

| Campo | Tipo | Descricao |
|-------|------|-----------|
| id | bigint | PK |
| inventory_id | FK -> inventory_records | Inventario |
| reopened_by_user_id | FK -> military_users | Quem reabriu |
| reopened_at | datetime | Quando foi reaberto |
| justification | text | Justificativa da reabertura |
| timestamps | | created_at, updated_at |

---

## Relacionamentos do Model InventoryRecord

```
InventoryRecord
├── belongsTo: Sector (sector_id)
├── belongsTo: MilitaryUser (responsible_user_id)
├── hasMany: InventoryAsset (inventory_id)
├── hasMany: UncataloguedItem (inventory_id)
├── hasMany: ReopenHistory (inventory_id)
└── belongsToMany: Asset (through inventory_assets)
```

### Atributos Computados (Appends)

| Atributo | Descricao |
|----------|-----------|
| `found_items` | Lista de ativos encontrados com observacoes |
| `uncatalogued_items` | Lista de itens nao catalogados |
| `pending_items` | Ativos do setor que ainda nao foram conferidos |
| `summary` | Resumo: { total, found, pending, uncatalogued } |

---

## Componentes Livewire

### 1. Index (`/inventory`)

**Arquivo:** `app/Livewire/Inventory/Index.php`

#### Propriedades
```php
public $search = '';      // Busca por commission_number
public $status = '';      // Filtro de status
public $sector_id = '';   // Filtro de setor
```

#### Metodos

| Metodo | Descricao |
|--------|-----------|
| `delete(InventoryRecord $inventory)` | Exclui inventario |
| `reopen(InventoryRecord $inventory)` | Reabre inventario (status='Reaberto', end_date=null) |
| `render()` | Renderiza lista paginada (10/pagina) |

#### Funcionalidades da View

| Elemento | Tipo | Comportamento |
|----------|------|---------------|
| Input "Buscar por numero da comissao" | Busca | `wire:model.live="search"` |
| Select "Todos os Setores" | Filtro | `wire:model.live="sector_id"` |
| Select "Todos os Status" | Filtro | `wire:model.live="status"` |
| Botao "Novo Inventario" | Link | `href="#"` **(NAO IMPLEMENTADO)** |
| Icone Olho (Ver Detalhes) | Link | Navega para `/inventory/{id}` |
| Icone Reabrir | Acao | `wire:click="reopen(id)"` - apenas se status='Concluido' |
| Icone Excluir | Acao | `wire:click="delete(id)"` + confirmacao |
| Paginacao | Navegacao | 10 itens por pagina |

#### Colunas da Tabela

| Coluna | Conteudo |
|--------|----------|
| Comissao | commission_number |
| Setor | sector.name |
| Responsavel | responsibleUser.name |
| Inicio | start_date (formato dd/mm/yyyy) |
| Status | Badge colorido |
| Acoes | Icones (ver, reabrir, excluir) |

#### Cores dos Badges de Status

| Status | Cor |
|--------|-----|
| Concluido | Verde (bg-green-100 text-green-800) |
| Reaberto | Amarelo (bg-yellow-100 text-yellow-800) |
| Em Andamento | Azul (bg-blue-100 text-blue-800) |

---

### 2. Show (`/inventory/{id}`)

**Arquivo:** `app/Livewire/Inventory/Show.php`

#### Propriedades
```php
public InventoryRecord $inventory;
public $qrCodeInput = '';              // Input do scanner
public $uncataloguedDescription = '';  // Input para item nao catalogado
public $notes = '';                    // Observacoes de auditoria
public $selectedPending = [];          // IDs de pendentes selecionados
public $selectedFound = [];            // IDs de encontrados selecionados
```

#### Metodos

| Metodo | Descricao | Validacao |
|--------|-----------|-----------|
| `findAsset()` | Busca ativo por QR/serial e adiciona aos encontrados | qrCodeInput required |
| `addUncatalogued()` | Adiciona item nao catalogado | description required, max:255 |
| `removeUncatalogued($id)` | Remove item nao catalogado | - |
| `bulkFind()` | Marca multiplos pendentes como encontrados | selectedPending nao vazio |
| `bulkRemove()` | Remove multiplos itens encontrados | selectedFound nao vazio |
| `finalize()` | Conclui inventario (status='Concluido', end_date=now) | - |

---

## Layout da Tela Show

### Cabecalho
- Titulo: "Inventario: {commission_number}"
- Badge de status (Concluido/Em Andamento)
- Botao "Voltar" -> `/inventory`

### Area do Scanner
```
┌─────────────────────────────────────────────────────────────────┐
│ [Input: QR Code/Serial Number...] [Botao: Encontrar]            │
└─────────────────────────────────────────────────────────────────┘
```

**Comportamento:**
- Input com autofocus
- `wire:submit.prevent="findAsset"`
- Busca por `qr_code` OU `serial_number`
- Se encontrado: adiciona ao inventario, limpa input
- Se ja existe: erro "Este ativo ja foi registrado neste inventario"
- Se nao encontrado: erro "Ativo nao encontrado"
- Dispara evento `asset-found` para feedback

### Grid de 3 Colunas

```
┌────────────────────┬────────────────────┬────────────────────┐
│   PENDENTES (X)    │  CONFERIDOS (X)    │ NAO CATALOGADOS(X) │
├────────────────────┼────────────────────┼────────────────────┤
│ [Marcar Selec.(N)] │ [Remover Selec.(N)]│                    │
├────────────────────┼────────────────────┼────────────────────┤
│ ☐ Asset Name       │ ☐ Asset Name       │ Descricao          │
│   QR/Serial        │   QR/Serial        │ Data encontrado    │
│                    │                    │ [x] (hover)        │
├────────────────────┼────────────────────┼────────────────────┤
│ ☐ Asset Name       │ ☐ Asset Name       │ Descricao          │
│   ...              │   ...              │   ...              │
├────────────────────┼────────────────────┼────────────────────┤
│                    │                    │ [Input + Adicionar]│
└────────────────────┴────────────────────┴────────────────────┘
```

#### Coluna Pendentes (Vermelho)
- Titulo: "Pendentes (count)" - cor vermelha
- Botao "Marcar Selecionados (N)" aparece se ha selecao
  - `wire:click="bulkFind"`
- Lista de ativos do setor NAO conferidos
- Cada item: checkbox + nome + QR/serial
- `wire:model.live="selectedPending"`
- Max-height: 500px com scroll

#### Coluna Conferidos (Verde)
- Titulo: "Conferidos (count)" - cor verde
- Botao "Remover Selecionados (N)" aparece se ha selecao
  - `wire:click="bulkRemove"` (cor laranja)
- Lista de ativos JA conferidos
- Cada item: checkbox + nome + QR/serial
- `wire:model.live="selectedFound"`
- Max-height: 500px com scroll

#### Coluna Nao Catalogados (Cinza)
- Titulo: "Nao Catalogados (count)"
- Lista de itens extras encontrados
- Cada item: descricao + data + botao excluir (aparece no hover)
  - `wire:click="removeUncatalogued(id)"`
- Formulario no rodape:
  - Input para descricao
  - Botao "Adicionar" (`wire:submit.prevent="addUncatalogued"`)
- Max-height: 400px com scroll

### Rodape
```
┌─────────────────────────────────────────────────────────────────┐
│ Observacoes de Auditoria                                         │
│ ┌─────────────────────────────────────────────────────────────┐ │
│ │ [Textarea 4 linhas]                                          │ │
│ └─────────────────────────────────────────────────────────────┘ │
│                                      [Finalizar Inventario]      │
└─────────────────────────────────────────────────────────────────┘
```

- Textarea para `notes`
- Botao "Finalizar Inventario" (verde)
  - `wire:click="finalize"`
  - Define status='Concluido', end_date=now
  - Redireciona para `/inventory`

---

## Funcionalidades Pendentes de Implementacao

### 1. Criar Novo Inventario
**Status:** NAO IMPLEMENTADO

O botao "Novo Inventario" na listagem tem `href="#"` e nao faz nada.

**Campos necessarios:**
- commission_number (nullable, unique)
- start_date (required)
- sector_id (required)
- responsible_user_id (required)
- notes (optional)

**Comportamento esperado:**
- Formulario de criacao
- Validacao dos campos
- Notificar usuario responsavel (InventoryAssignedNotification ja existe)

### 2. Historico de Reabertura
**Status:** MODELO EXISTE, UI NAO IMPLEMENTADA

Quando reabrir um inventario deveria:
- Registrar na tabela `reopen_history`:
  - inventory_id
  - reopened_by_user_id (usuario logado)
  - reopened_at
  - justification (campo a ser solicitado)

**Comportamento esperado:**
- Modal pedindo justificativa antes de reabrir
- Salvar no historico
- Exibir historico de reaberturas nos detalhes

### 3. Editar Inventario
**Status:** NAO IMPLEMENTADO

Nao ha tela de edicao dos dados basicos do inventario:
- commission_number
- responsible_user_id
- sector_id
- notes

### 4. Localizacao de Item Nao Catalogado
**Status:** CAMPO EXISTE, NAO USADO NA UI

A tabela `uncatalogued_items` tem campo `location`, mas a UI nao o utiliza.

### 5. Observacao por Ativo
**Status:** CAMPO EXISTE, NAO USADO NA UI

A tabela `inventory_assets` tem campo `observation`, mas a UI nao permite adicionar.

---

## API REST (Legado)

**Controller:** `InventoryRecordController.php`

| Endpoint | Metodo | Descricao |
|----------|--------|-----------|
| GET /api/inventory | index | Lista inventarios (com filtros) |
| POST /api/inventory | store | Cria inventario |
| GET /api/inventory/{id} | show | Detalhes do inventario |
| PUT /api/inventory/{id} | update | Atualiza inventario |
| DELETE /api/inventory/{id} | destroy | Exclui inventario |
| POST /api/inventory/{id}/found | addFoundItem | Adiciona item encontrado |
| POST /api/inventory/{id}/uncatalogued | addUncataloguedItem | Adiciona nao catalogado |
| POST /api/inventory/{id}/complete | complete | Conclui inventario |
| POST /api/inventory/{id}/reopen | reopen | Reabre inventario |
| DELETE /api/inventory/{id}/uncatalogued/{uid} | deleteUncataloguedItem | Remove nao catalogado |

---

## Testes Existentes

**Arquivo:** `tests/Feature/InventoryFeatureTest.php`

| Teste | Descricao |
|-------|-----------|
| test_can_view_inventory_index | Visualiza listagem |
| test_can_view_inventory_show | Visualiza detalhes (3 colunas) |
| test_can_find_asset_by_qr_code | Encontrar ativo por QR |
| test_can_add_uncatalogued_item | Adicionar item nao catalogado |
| test_can_finalize_inventory | Finalizar inventario |

---

## Resumo de Implementacao

| Funcionalidade | Index | Show | Status |
|----------------|-------|------|--------|
| Listar inventarios | ✅ | - | OK |
| Filtrar por status | ✅ | - | OK |
| Filtrar por setor | ✅ | - | OK |
| Buscar por comissao | ✅ | - | OK |
| Ver detalhes | ✅ | ✅ | OK |
| Scanner QR/Serial | - | ✅ | OK |
| Marcar itens encontrados | - | ✅ | OK |
| Selecao em lote (bulk) | - | ✅ | OK |
| Adicionar nao catalogado | - | ✅ | OK |
| Remover nao catalogado | - | ✅ | OK |
| Observacoes auditoria | - | ✅ | OK |
| Finalizar inventario | - | ✅ | OK |
| Reabrir inventario | ✅ | - | PARCIAL (sem historico) |
| Excluir inventario | ✅ | - | OK |
| **Criar inventario** | - | - | **PENDENTE** |
| **Editar inventario** | - | - | **PENDENTE** |
| **Historico reabertura** | - | - | **PENDENTE** |
| **Localizacao item extra** | - | - | **PENDENTE** |
| **Observacao por ativo** | - | - | **PENDENTE** |

---

*Documento gerado em: 01/02/2026*
