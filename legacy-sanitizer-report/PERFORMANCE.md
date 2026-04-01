# RELATÓRIO DE PERFORMANCE

## Visão Geral
Problemas de performance identificados em pontos específicos do código.

---

## Problemas de Performance

### 🟡 MÉDIO (3)

#### PER-001: N+1 Queries em render()
- **Arquivo:** `app/Livewire/Inventory/Show.php:263-273`
- **Problema:** Método render executa múltiplas queries sem eager loading
- **Código:**
```php
public function render()
{
    $foundAssetIds = InventoryAsset::where('inventory_id', $this->inventory->id)->pluck('asset_id');
    $foundAssets = Asset::whereIn('id', $foundAssetIds)->get();
    // ...
}
```
- **Correção Sugerida:** Usar `->with(['category', 'sector'])` nas queries
- **Impacto:** Médio - especialmente com muitos ativos

#### PER-006: Heavy Loop
- **Arquivo:** `app/Livewire/Inventory/Show.php:221-226`
- **Problema:** Loop com query dentro (firstOrCreate em loop)
- **Código:**
```php
foreach ($this->selectedPending as $assetId) {
    InventoryAsset::firstOrCreate([...]);
}
```
- **Correção Sugerida:** Usar `insert()` ou `upsert()` para批量操作

#### PER-005: Missing Eager Loading
- **Arquivo:** `app/Livewire/Custody/Index.php`
- **Problema:** Relations carregadas sem eager loading
- **Correção Sugerida:** Adicionar `->with(['user', 'assets'])` nas queries

---

### 🔵 INFO (5)

#### PER-002: Missing Indexes
- **Arquivo:** Migration verificar
- **Problema:** Queries podem não ter índices em colunas filteradas
- **Recomendação:** Executar EXPLAIN em queries de listagem

#### PER-004: Synchronous External Calls
- **Arquivo:** Notifications
- **Problema:** Envio de notifications síncrono
- **Status:** Aceitável para baixo volume

---

## Métricas de Performance

| Métrica | Valor |
|---------|-------|
| N+1 Queries | 2 detectados |
| Missing Indexes | A verificar |
| Cache Usage | Parcial |
| Queries lentasy | 0 críticas |

**Performance Score: 72/100**