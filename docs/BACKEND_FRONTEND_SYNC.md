# SINCRONIZAÇÃO BACKEND-FRONTEND

**Sistema de Gestão de Ativos de TI - Unidade Militar**

Última atualização: 2025-10-14

---

## 📋 Objetivo

Este documento mapeia **como os dados fluem entre o backend (MySQL + Express) e o frontend (React + TypeScript)**, identificando discrepâncias e fornecendo guias de conversão.

---

## Arquitetura de Dados

### Camadas de Dados

```
┌──────────────────────────────────────────────────────────┐
│                    FRONTEND (React)                       │
│  - types.ts (TypeScript interfaces)                      │
│  - Estado local (useState)                                │
│  - Nomenclatura: camelCase                                │
└─────────────────────┬────────────────────────────────────┘
                      │
                      │ HTTP/JSON (services/api.ts)
                      │
┌─────────────────────▼────────────────────────────────────┐
│              BACKEND API (Express.js)                     │
│  - Routes: routes/*.js                            │
│  - Conversão: snake_case ↔ camelCase                      │
│  - Validação e lógica de negócio                          │
└─────────────────────┬────────────────────────────────────┘
                      │
                      │ SQL Queries (mysql2)
                      │
┌─────────────────────▼────────────────────────────────────┐
│               DATABASE (MySQL)                            │
│  - Schema: init.sql                               │
│  - Nomenclatura: snake_case                               │
│  - Constraints e validações                               │
└──────────────────────────────────────────────────────────┘
```

---

## Mapeamento de Nomenclatura

### Regras Gerais

| Camada    | Convenção  | Exemplo            |
|-----------|------------|--------------------|
| Frontend  | camelCase  | `assetId`          |
| API Body  | camelCase  | `assetId`          |
| API Response | snake_case | `asset_id`       |
| Database  | snake_case | `asset_id`         |

### Conversão de Nomes

#### Request (Frontend → Backend)

Frontend envia em **camelCase**:
```typescript
const data = {
  cautelaNumber: "001/GAC-PAC/2024",
  userId: "uuid-user",
  checkoutDate: "2024-01-01",
  assetIds: ["uuid1", "uuid2"]
}

await api.post('/api/custody', data);
```

Backend converte para **snake_case** no SQL:
```javascript
await db.query(
  `INSERT INTO custody_logs (cautela_number, user_id, checkout_date) VALUES (?, ?, ?)`,
  [cautelaNumber, userId, checkoutDate]
);
```

#### Response (Backend → Frontend)

Backend retorna **snake_case** direto do MySQL:
```json
{
  "id": "uuid",
  "cautela_number": "001/GAC-PAC/2024",
  "user_id": "uuid-user",
  "checkout_date": "2024-01-01",
  "user_name": "Maj João Silva"
}
```

Frontend **deve usar snake_case** ao acessar:
```typescript
interface CustodyLog {
  id: string;
  cautela_number: string;  // snake_case mantido
  user_id: string;          // snake_case mantido
  checkout_date: string;
  user_name?: string;
}
```

---

## Mapeamento de Tipos por Entidade

### 1. Asset (Ativo)

#### Frontend: `types.ts`

```typescript
export interface Asset {
  id: string;
  qr_code: string;
  name: string;
  category: string;
  subcategory?: string;
  description?: string;
  serial_number?: string;
  patrimony_id?: string;
  manufacturer?: string;
  model?: string;
  acquisition_date?: string;
  warranty_expiry?: string;
  purchase_price?: number;
  status: string;
  condition_rating?: number;
  sector_id?: string;
  location?: string;
  custodian_user_id?: string;
  notes?: string;
  created_at?: string;
  updated_at?: string;
  sector_name?: string;          // Calculado no backend via JOIN
  custodian_name?: string;        // Calculado no backend via JOIN
  custodian_rank?: string;        // Calculado no backend via JOIN
  photos: AssetPhoto[];           // Array populado no backend
  maintenanceHistory: MaintenanceRecord[];  // Array populado no backend
}
```

#### Backend: SQL Query

```sql
SELECT a.*,
       s.name as sector_name,
       u.name as custodian_name,
       u.rank as custodian_rank
FROM assets a
LEFT JOIN sectors s ON a.sector_id = s.id
LEFT JOIN users u ON a.custodian_user_id = u.id
WHERE a.id = ?
```

Depois, no código JavaScript:
```javascript
const [photos] = await db.query('SELECT * FROM asset_photos WHERE asset_id = ?', [asset.id]);
const [maintenance] = await db.query('SELECT * FROM maintenance_history WHERE asset_id = ?', [asset.id]);

asset.photos = photos;
asset.maintenanceHistory = maintenance;
```

#### Campos Calculados vs Campos Reais

| Campo             | Origem          | Tipo       |
|-------------------|-----------------|------------|
| `sector_name`     | JOIN calculado  | Computed   |
| `custodian_name`  | JOIN calculado  | Computed   |
| `custodian_rank`  | JOIN calculado  | Computed   |
| `photos`          | Query separada  | Array      |
| `maintenanceHistory` | Query separada | Array   |

---

### 2. Sector (Setor)

#### Frontend: `types.ts`

```typescript
export interface Sector {
  id: string;
  name: string;
  description?: string;
  created_at?: string;
  updated_at?: string;
}
```

#### Backend: Direto do SQL

```sql
SELECT * FROM sectors ORDER BY name
```

**Mapeamento:** 1:1 direto, sem conversões necessárias.

---

### 3. MilitaryUser (Usuário)

#### Frontend: `types.ts`

```typescript
export interface MilitaryUser {
  id: string;
  name: string;
  rank: string;
  military_id: string;
  sector_id?: string;
  email?: string;
  phone?: string;
  is_active: boolean;          // ⚠️ BOOLEAN
  created_at?: string;
  updated_at?: string;
  sector_name?: string;        // Calculado via JOIN
}
```

#### Backend: Conversão Necessária

MySQL retorna `is_active` como **0/1** (TINYINT), mas frontend espera **boolean**.

**Conversão no backend:**
```javascript
const users = rows.map(user => ({
  ...user,
  is_active: Boolean(user.is_active)  // 0 → false, 1 → true
}));
```

#### Mapeamento Completo

| Frontend (TS)   | Backend (MySQL)    | Conversão          |
|-----------------|--------------------|--------------------|
| `is_active: boolean` | `is_active BOOLEAN (0/1)` | `Boolean(value)` |

---

### 4. CustodyLog (Cautela)

#### Frontend: `types.ts`

```typescript
export interface CustodyLog {
  id: string;
  cautela_number: string;
  user_id: string;
  assetIds?: string[];         // ⚠️ Array calculado
  assets?: Asset[];            // ⚠️ Array de objetos completos
  checkout_date: string;
  checkin_date?: string;
  term_url?: string;
  signed_term_url?: string;
  notes?: string;
  created_at?: string;
  updated_at?: string;
  user_name?: string;          // JOIN calculado
  user_rank?: string;          // JOIN calculado
  military_id?: string;        // JOIN calculado
}
```

#### Backend: Construção Complexa

```javascript
// 1. Query principal
const [custodyLogs] = await db.query(`
  SELECT cl.*,
         u.name as user_name,
         u.rank as user_rank,
         u.military_id
  FROM custody_logs cl
  INNER JOIN users u ON cl.user_id = u.id
`);

// 2. Para cada cautela, buscar ativos
for (let log of custodyLogs) {
  const [assets] = await db.query(`
    SELECT a.*, ca.id as custody_asset_id
    FROM custody_assets ca
    INNER JOIN assets a ON ca.asset_id = a.id
    WHERE ca.custody_log_id = ?
  `, [log.id]);

  log.assetIds = assets.map(a => a.id);
  log.assets = assets;
}
```

#### Relacionamento N:N

```
custody_logs (1) ←→ (N) custody_assets (N) ←→ (1) assets
```

**Tabela intermediária:** `custody_assets`
- `custody_log_id`
- `asset_id`

---

### 5. InventoryRecord (Inventário)

#### Frontend: `types.ts`

```typescript
export interface InventoryRecord {
  id: string;
  commission_number: string;
  start_date: string;
  end_date?: string;
  sector_id?: string;
  status: 'Concluído' | 'Reaberto' | 'Em Andamento';
  notes?: string;
  created_at?: string;
  updated_at?: string;
  sector_name?: string;
  foundItems: InventoryAsset[];       // ⚠️ Array complexo
  pendingItems: Asset[];              // ⚠️ Array complexo
  uncataloguedItems: UncataloguedItem[];  // ⚠️ Array complexo
  reopenHistory?: ReopenHistory[];    // ⚠️ Array complexo
}

export interface InventoryAsset extends Asset {
  observation?: string;  // Campo adicional
}
```

#### Backend: Múltiplas Queries

```javascript
// 1. Query principal
const [inventories] = await db.query(`
  SELECT ir.*, s.name as sector_name
  FROM inventory_records ir
  LEFT JOIN sectors s ON ir.sector_id = s.id
`);

// 2. Para cada inventário
for (let inventory of inventories) {
  // Found items (com observation)
  const [found] = await db.query(`
    SELECT ifi.*, a.*
    FROM inventory_found_items ifi
    INNER JOIN assets a ON ifi.asset_id = a.id
    WHERE ifi.inventory_id = ?
  `, [inventory.id]);

  // Pending items
  const [pending] = await db.query(`
    SELECT ipi.*, a.*
    FROM inventory_pending_items ipi
    INNER JOIN assets a ON ipi.asset_id = a.id
    WHERE ipi.inventory_id = ?
  `, [inventory.id]);

  // Uncatalogued items
  const [uncatalogued] = await db.query(
    'SELECT * FROM inventory_uncatalogued_items WHERE inventory_id = ?',
    [inventory.id]
  );

  // Reopen history
  const [reopenHistory] = await db.query(`
    SELECT irh.*, u.name as user_name, u.rank as user_rank
    FROM inventory_reopen_history irh
    INNER JOIN users u ON irh.reopened_by_user_id = u.id
    WHERE irh.inventory_id = ?
    ORDER BY irh.reopened_at DESC
  `, [inventory.id]);

  inventory.foundItems = found.map(f => ({ ...f, observation: f.observation }));
  inventory.pendingItems = pending;
  inventory.uncataloguedItems = uncatalogued;
  inventory.reopenHistory = reopenHistory;
}
```

#### Tabelas Relacionadas

```
inventory_records (1)
  ├─→ (N) inventory_found_items → assets
  ├─→ (N) inventory_pending_items → assets
  ├─→ (N) inventory_uncatalogued_items
  └─→ (N) inventory_reopen_history → users
```

---

## Discrepâncias Identificadas

### 1. ⚠️ Conversão de Boolean

**Problema:** MySQL BOOLEAN é armazenado como TINYINT (0/1), mas TypeScript espera `true/false`.

**Solução:** Backend deve converter explicitamente:
```javascript
const users = rows.map(user => ({
  ...user,
  is_active: Boolean(user.is_active)
}));
```

**Arquivo:** `routes/users.js` (linhas 32-35, 57-60, 99-102, 150-153)

**Status:** ✅ JÁ IMPLEMENTADO

---

### 2. ⚠️ Arrays Populados no Backend

**Problema:** Frontend espera arrays populados (`photos`, `assets`, `foundItems`, etc.), mas são queries separadas.

**Solução:** Backend executa múltiplas queries e monta objetos compostos.

**Arquivos:**
- `routes/assets.js` (linhas 75-81)
- `routes/custody.js` (linhas 36-47)
- `routes/inventory.js` (linhas 34-70)

**Status:** ✅ JÁ IMPLEMENTADO

---

### 3. ⚠️ Campos Calculados via JOIN

**Problema:** Frontend usa `sector_name`, `custodian_name`, `user_name`, mas não existem no banco.

**Solução:** Backend usa LEFT JOIN para popular campos calculados.

**Exemplo:**
```sql
SELECT a.*, s.name as sector_name
FROM assets a
LEFT JOIN sectors s ON a.sector_id = s.id
```

**Status:** ✅ JÁ IMPLEMENTADO

---

### 4. ⚠️ Nomenclatura snake_case vs camelCase

**Problema:** API aceita camelCase no request body mas retorna snake_case no response.

**Impacto:** Frontend precisa lidar com ambas convenções.

**Exemplo:**
```typescript
// Request (camelCase)
await api.post('/api/custody', {
  cautelaNumber: "001/GAC-PAC/2024",
  userId: "uuid"
});

// Response (snake_case)
{
  cautela_number: "001/GAC-PAC/2024",
  user_id: "uuid"
}
```

**Recomendação:** Padronizar para snake_case em toda API, ou criar camada de transformação.

**Status:** ⚠️ INCONSISTENTE (funciona mas não é ideal)

---

## Guia de Integração Frontend

### Criando Service API (services/api.ts)

```typescript
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.VITE_API_URL || 'http://localhost:5000/api',
  headers: {
    'Content-Type': 'application/json',
  },
});

// GET all assets
export const getAssets = async (filters?: {
  category?: string;
  status?: string;
  sectorId?: string;
  search?: string;
}) => {
  const response = await api.get('/assets', { params: filters });
  return response.data;
};

// GET asset by ID
export const getAssetById = async (id: string) => {
  const response = await api.get(`/assets/${id}`);
  return response.data;
};

// POST create asset
export const createAsset = async (data: {
  qrCode: string;
  name: string;
  category: string;
  // ... outros campos
}) => {
  const response = await api.post('/assets', data);
  return response.data;
};

// PUT update asset
export const updateAsset = async (id: string, data: any) => {
  const response = await api.put(`/assets/${id}`, data);
  return response.data;
};

// DELETE asset
export const deleteAsset = async (id: string) => {
  await api.delete(`/assets/${id}`);
};

export default api;
```

### Usando no Componente React

```typescript
import { useEffect, useState } from 'react';
import { getAssets } from './services/api';
import { Asset } from './types';

function AssetList() {
  const [assets, setAssets] = useState<Asset[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchAssets = async () => {
      try {
        const data = await getAssets({ status: 'Disponível' });
        setAssets(data);
      } catch (error) {
        console.error('Erro ao buscar ativos:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchAssets();
  }, []);

  if (loading) return <div>Carregando...</div>;

  return (
    <div>
      {assets.map(asset => (
        <div key={asset.id}>
          <h3>{asset.name}</h3>
          <p>QR: {asset.qr_code}</p>
          <p>Setor: {asset.sector_name}</p>
        </div>
      ))}
    </div>
  );
}
```

---

## Checklist de Sincronização

Antes de desenvolver, verifique:

- [ ] Os tipos em `/types.ts` correspondem ao schema do banco?
- [ ] A API retorna todos os campos necessários (incluindo JOINs)?
- [ ] Arrays aninhados estão sendo populados corretamente?
- [ ] Campos booleanos estão sendo convertidos?
- [ ] Nomenclatura está consistente (snake_case vs camelCase)?
- [ ] Validações do frontend correspondem às do backend?
- [ ] Error handling está implementado?

---

## Ferramentas de Desenvolvimento

### Testando API com cURL

```bash
# GET assets
curl http://localhost:5000/api/assets

# POST novo ativo
curl -X POST http://localhost:5000/api/assets \
  -H "Content-Type: application/json" \
  -d '{
    "qrCode": "SGAITI-9999",
    "name": "Teste Asset",
    "category": "Computação"
  }'

# GET ativo por QR
curl http://localhost:5000/api/assets/qr/SGAITI-0001
```

### Testando Banco de Dados Diretamente

```bash
# Conectar ao MySQL (Docker)
docker exec -it sgaiti-db mysql -u sgaiti_user -p sgaiti_db

# Queries úteis
SELECT * FROM assets LIMIT 5;
SELECT COUNT(*) FROM custody_logs WHERE checkin_date IS NULL;
DESCRIBE assets;
```

---

**Gerado automaticamente em:** 2025-10-14
**Versão:** 1.0.0
