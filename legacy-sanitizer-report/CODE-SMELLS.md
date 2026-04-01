# RELATÓRIO DE CODE SMELLS

## Visão Geral
Identificados code smells de nível baixo relacionados à estrutura e complexidade.

---

## Code Smells por Severidade

### 🟡 MÉDIO (4)

#### CSM-004: God Class - Inventory/Show.php
- **Arquivo:** `app/Livewire/Inventory/Show.php`
- **Problema:** Classe com 22 métodos, múltiplas responsabilidades
- **Métodos:** openFinalizeModal, closeFinalizeModal, openReopenModal, closeReopenModal, confirmReopen, updatedSelectAllPending, updatedSelectAllFound, mount, findAsset, addUncatalogued, editUncatalogued, cancelEditUncatalogued, updateUncatalogued, removeUncatalogued, bulkFind, bulkRemove, finalize, render + management methods
- **Correção Sugerida:** Extrair trait ou componente para gestão de inventário e модeração

#### CSM-001: Funções grandes
- **Arquivo:** `app/Livewire/Inventory/Show.php:render()`
- **Problema:** Método render com ~20 linhas com múltiplas queries
- **Correção Sugerida:** Extrair queries para serviço dedicado

#### CSM-006: Long Parameter List
- **Arquivo:** `app/Services/AssetService.php:list()`
- **Problema:** Método aceita muitos parâmetros via array
- **Correção Sugerida:** Usar Data Transfer Object ou Form Request

---

### 🔵 INFO (8)

#### CSM-003: Nomes Ruins
- **Arquivo:** `app/Livewire/Inventory/Show.php`
- **Variáveis:** `$qrCodeInput`, `$uncataloguedDescription`, `$notes` - nomes genéricos
- **Status:** Aceitável, nomenclatura padrão Laravel

#### CSM-007: Feature Envy
- **Arquivo:** Services usam muitos atributos de modelos
- **Status:** Padrão aceitável em Laravel

---

## Métricas de Qualidade

| Métrica | Valor |
|---------|-------|
| Funções >200 linhas | 0 |
| God Classes | 1 |
| Código Duplicado | 0 detectado |
| Circular Dependencies | 0 |

**Code Quality Score: 75/100**