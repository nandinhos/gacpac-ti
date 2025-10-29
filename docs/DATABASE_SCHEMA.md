# DATABASE SCHEMA - SGAITI-UM

**Sistema de Gestão de Ativos de TI - Unidade Militar**

Última atualização: 2025-10-14

---

## 📋 Sumário

1. [Visão Geral](#visão-geral)
2. [Diagrama de Relacionamento](#diagrama-de-relacionamento)
3. [Tabelas do Banco de Dados](#tabelas-do-banco-de-dados)
4. [Mapeamento Frontend-Backend](#mapeamento-frontend-backend)
5. [Convenções de Nomenclatura](#convenções-de-nomenclatura)
6. [Índices e Performance](#índices-e-performance)

---

## Visão Geral

**Banco de dados:** MySQL 8.0+
**Charset:** utf8mb4
**Collation:** utf8mb4_unicode_ci
**Nome do DB:** `sgaiti_db`

### Entidades Principais

- **sectors** - Setores militares (CHF, ATI, AIT, etc.)
- **users** - Militares (usuários do sistema)
- **assets** - Ativos de TI (equipamentos)
- **asset_photos** - Fotos dos ativos
- **maintenance_history** - Histórico de manutenções
- **custody_logs** - Cautelas (empréstimos de ativos)
- **custody_assets** - Relação N:N entre cautelas e ativos
- **inventory_records** - Registros de inventário
- **inventory_found_items** - Ativos encontrados no inventário
- **inventory_pending_items** - Ativos pendentes no inventário
- **inventory_uncatalogued_items** - Itens não catalogados encontrados
- **inventory_reopen_history** - Histórico de reaberturas de inventário

---

## Diagrama de Relacionamento

```
┌─────────────┐
│   sectors   │
└──────┬──────┘
       │
       ├──────────────┐
       │              │
       ▼              ▼
┌──────────┐    ┌──────────┐
│  users   │    │  assets  │
└────┬─────┘    └────┬─────┘
     │               │
     │               ├──────► asset_photos
     │               ├──────► maintenance_history
     │               │
     ├───────────────┘
     │
     ▼
┌──────────────┐      ┌────────────────┐
│ custody_logs │◄────►│ custody_assets │
└──────────────┘      └────────┬───────┘
                              │
                              ▼
                         ┌─────────┐
                         │ assets  │
                         └─────────┘

┌──────────────────┐
│inventory_records │
└────────┬─────────┘
         │
         ├──────► inventory_found_items
         ├──────► inventory_pending_items
         ├──────► inventory_uncatalogued_items
         └──────► inventory_reopen_history
```

---

## Tabelas do Banco de Dados

### 1. **sectors** (Setores)

Armazena os setores/departamentos da unidade militar.

| Campo       | Tipo         | Restrições           | Descrição                |
|-------------|--------------|----------------------|--------------------------|
| id          | VARCHAR(36)  | PK                   | UUID do setor            |
| name        | VARCHAR(255) | NOT NULL             | Nome do setor            |
| description | TEXT         | NULL                 | Descrição do setor       |
| created_at  | TIMESTAMP    | DEFAULT CURRENT_TS   | Data de criação          |
| updated_at  | TIMESTAMP    | ON UPDATE CURRENT_TS | Data de atualização      |

**Índices:**
- PRIMARY KEY (id)
- FULLTEXT INDEX ft_sectors_search (name, description)

---

### 2. **users** (Usuários Militares)

Armazena os militares que utilizam o sistema.

| Campo       | Tipo         | Restrições           | Descrição                    |
|-------------|--------------|----------------------|------------------------------|
| id          | VARCHAR(36)  | PK                   | UUID do usuário              |
| name        | VARCHAR(255) | NOT NULL             | Nome completo                |
| rank        | VARCHAR(100) | NOT NULL             | Posto/Graduação              |
| military_id | VARCHAR(50)  | UNIQUE, NOT NULL     | Identidade militar           |
| sector_id   | VARCHAR(36)  | FK → sectors(id)     | Setor de lotação             |
| email       | VARCHAR(255) | NULL                 | E-mail                       |
| phone       | VARCHAR(50)  | NULL                 | Telefone                     |
| is_active   | BOOLEAN      | DEFAULT TRUE         | Status ativo/inativo         |
| created_at  | TIMESTAMP    | DEFAULT CURRENT_TS   | Data de criação              |
| updated_at  | TIMESTAMP    | ON UPDATE CURRENT_TS | Data de atualização          |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE INDEX (military_id)
- INDEX idx_sector_id (sector_id)
- INDEX idx_is_active (is_active)
- FULLTEXT INDEX ft_users_search (name, military_id)

**Foreign Keys:**
- sector_id REFERENCES sectors(id) ON DELETE SET NULL

---

### 3. **assets** (Ativos de TI)

Armazena todos os ativos/equipamentos de TI.

| Campo              | Tipo          | Restrições                                           | Descrição                          |
|--------------------|---------------|------------------------------------------------------|------------------------------------|
| id                 | VARCHAR(36)   | PK                                                   | UUID do ativo                      |
| qr_code            | VARCHAR(50)   | UNIQUE, NOT NULL                                     | Código QR (ex: SGAITI-0001)        |
| name               | VARCHAR(255)  | NOT NULL                                             | Nome do ativo                      |
| category           | ENUM          | 'Computação', 'Periféricos', 'Energia', 'Comunicações', 'Outros Ativos de TI' | Categoria do ativo |
| subcategory        | VARCHAR(100)  | NULL                                                 | Subcategoria                       |
| description        | TEXT          | NULL                                                 | Descrição detalhada                |
| serial_number      | VARCHAR(255)  | NULL                                                 | Número de série                    |
| patrimony_id       | VARCHAR(100)  | NULL                                                 | Número patrimonial                 |
| manufacturer       | VARCHAR(255)  | NULL                                                 | Fabricante                         |
| model              | VARCHAR(255)  | NULL                                                 | Modelo                             |
| acquisition_date   | DATE          | NULL                                                 | Data de aquisição                  |
| warranty_expiry    | DATE          | NULL                                                 | Vencimento da garantia             |
| purchase_price     | DECIMAL(10,2) | NULL                                                 | Preço de compra                    |
| status             | ENUM          | 'Em Uso', 'Disponível', 'Manutenção', 'Baixado'     | Status do ativo                    |
| condition_rating   | INT           | CHECK (1-5)                                          | Avaliação de condição (1-5)        |
| sector_id          | VARCHAR(36)   | FK → sectors(id)                                     | Setor atual                        |
| location           | VARCHAR(255)  | NULL                                                 | Localização física                 |
| custodian_user_id  | VARCHAR(36)   | FK → users(id)                                       | Custodiante atual                  |
| notes              | TEXT          | NULL                                                 | Observações                        |
| created_at         | TIMESTAMP     | DEFAULT CURRENT_TS                                   | Data de criação                    |
| updated_at         | TIMESTAMP     | ON UPDATE CURRENT_TS                                 | Data de atualização                |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE INDEX (qr_code)
- INDEX idx_category (category)
- INDEX idx_status (status)
- INDEX idx_sector_id (sector_id)
- INDEX idx_custodian_user_id (custodian_user_id)
- INDEX idx_patrimony_id (patrimony_id)
- INDEX idx_assets_updated_at (updated_at)
- FULLTEXT INDEX ft_assets_search (name, description, serial_number, patrimony_id)

**Foreign Keys:**
- sector_id REFERENCES sectors(id) ON DELETE SET NULL
- custodian_user_id REFERENCES users(id) ON DELETE SET NULL

---

### 4. **asset_photos** (Fotos dos Ativos)

Armazena as fotos/imagens dos ativos.

| Campo       | Tipo         | Restrições           | Descrição                |
|-------------|--------------|----------------------|--------------------------|
| id          | VARCHAR(36)  | PK                   | UUID da foto             |
| asset_id    | VARCHAR(36)  | FK → assets(id), NOT NULL | ID do ativo       |
| url         | VARCHAR(500) | NOT NULL             | URL da foto              |
| caption     | VARCHAR(255) | NULL                 | Legenda/descrição        |
| uploaded_at | TIMESTAMP    | DEFAULT CURRENT_TS   | Data do upload           |

**Índices:**
- PRIMARY KEY (id)
- INDEX idx_asset_id (asset_id)

**Foreign Keys:**
- asset_id REFERENCES assets(id) ON DELETE CASCADE

---

### 5. **maintenance_history** (Histórico de Manutenções)

Armazena o histórico de manutenções dos ativos.

| Campo        | Tipo          | Restrições           | Descrição                     |
|--------------|---------------|----------------------|-------------------------------|
| id           | VARCHAR(36)   | PK                   | UUID do registro              |
| asset_id     | VARCHAR(36)   | FK → assets(id), NOT NULL | ID do ativo              |
| date         | DATE          | NOT NULL             | Data da manutenção            |
| description  | TEXT          | NOT NULL             | Descrição da manutenção       |
| performed_by | VARCHAR(255)  | NULL                 | Quem executou                 |
| cost         | DECIMAL(10,2) | NULL                 | Custo da manutenção           |
| created_at   | TIMESTAMP     | DEFAULT CURRENT_TS   | Data de criação do registro   |

**Índices:**
- PRIMARY KEY (id)
- INDEX idx_asset_id (asset_id)
- INDEX idx_date (date)

**Foreign Keys:**
- asset_id REFERENCES assets(id) ON DELETE CASCADE

---

### 6. **custody_logs** (Cautelas)

Armazena os registros de cautela (empréstimo de ativos).

| Campo            | Tipo         | Restrições           | Descrição                       |
|------------------|--------------|----------------------|---------------------------------|
| id               | VARCHAR(36)  | PK                   | UUID da cautela                 |
| cautela_number   | VARCHAR(50)  | UNIQUE, NOT NULL     | Número da cautela (ex: 001/GAC-PAC/2024) |
| user_id          | VARCHAR(36)  | FK → users(id), NOT NULL | ID do usuário responsável  |
| checkout_date    | DATE         | NOT NULL             | Data de retirada                |
| checkin_date     | DATE         | NULL                 | Data de devolução (NULL = ativa)|
| term_url         | VARCHAR(500) | NULL                 | URL do termo em branco          |
| signed_term_url  | VARCHAR(500) | NULL                 | URL do termo assinado           |
| notes            | TEXT         | NULL                 | Observações                     |
| created_at       | TIMESTAMP    | DEFAULT CURRENT_TS   | Data de criação                 |
| updated_at       | TIMESTAMP    | ON UPDATE CURRENT_TS | Data de atualização             |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE INDEX (cautela_number)
- INDEX idx_user_id (user_id)
- INDEX idx_checkout_date (checkout_date)
- INDEX idx_checkin_date (checkin_date)
- INDEX idx_custody_logs_updated_at (updated_at)

**Foreign Keys:**
- user_id REFERENCES users(id) ON DELETE RESTRICT

---

### 7. **custody_assets** (Ativos em Cautela)

Tabela de relacionamento N:N entre cautelas e ativos.

| Campo           | Tipo         | Restrições           | Descrição                |
|-----------------|--------------|----------------------|--------------------------|
| id              | VARCHAR(36)  | PK                   | UUID da relação          |
| custody_log_id  | VARCHAR(36)  | FK → custody_logs(id), NOT NULL | ID da cautela |
| asset_id        | VARCHAR(36)  | FK → assets(id), NOT NULL | ID do ativo       |
| created_at      | TIMESTAMP    | DEFAULT CURRENT_TS   | Data de criação          |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE KEY unique_custody_asset (custody_log_id, asset_id)
- INDEX idx_custody_log_id (custody_log_id)
- INDEX idx_asset_id (asset_id)

**Foreign Keys:**
- custody_log_id REFERENCES custody_logs(id) ON DELETE CASCADE
- asset_id REFERENCES assets(id) ON DELETE CASCADE

---

### 8. **inventory_records** (Registros de Inventário)

Armazena as sessões de inventário.

| Campo              | Tipo         | Restrições           | Descrição                         |
|--------------------|--------------|----------------------|-----------------------------------|
| id                 | VARCHAR(36)  | PK                   | UUID do inventário                |
| commission_number  | VARCHAR(100) | UNIQUE, NOT NULL     | Número da comissão (ex: CI-ATI-2024/01) |
| start_date         | DATE         | NOT NULL             | Data de início                    |
| end_date           | DATE         | NULL                 | Data de conclusão                 |
| sector_id          | VARCHAR(36)  | FK → sectors(id)     | Setor (NULL = toda unidade)       |
| status             | ENUM         | 'Em Andamento', 'Concluído', 'Reaberto' | Status do inventário |
| notes              | TEXT         | NULL                 | Observações                       |
| created_at         | TIMESTAMP    | DEFAULT CURRENT_TS   | Data de criação                   |
| updated_at         | TIMESTAMP    | ON UPDATE CURRENT_TS | Data de atualização               |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE INDEX (commission_number)
- INDEX idx_status (status)
- INDEX idx_sector_id (sector_id)
- INDEX idx_start_date (start_date)
- INDEX idx_inventory_records_updated_at (updated_at)

**Foreign Keys:**
- sector_id REFERENCES sectors(id) ON DELETE SET NULL

---

### 9. **inventory_found_items** (Itens Encontrados no Inventário)

Ativos que foram encontrados durante o inventário.

| Campo        | Tipo         | Restrições           | Descrição                     |
|--------------|--------------|----------------------|-------------------------------|
| id           | VARCHAR(36)  | PK                   | UUID do registro              |
| inventory_id | VARCHAR(36)  | FK → inventory_records(id), NOT NULL | ID do inventário |
| asset_id     | VARCHAR(36)  | FK → assets(id), NOT NULL | ID do ativo              |
| found_date   | TIMESTAMP    | DEFAULT CURRENT_TS   | Data que foi encontrado       |
| observation  | TEXT         | NULL                 | Observação sobre o ativo      |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE KEY unique_inventory_asset (inventory_id, asset_id)
- INDEX idx_inventory_id (inventory_id)
- INDEX idx_asset_id (asset_id)

**Foreign Keys:**
- inventory_id REFERENCES inventory_records(id) ON DELETE CASCADE
- asset_id REFERENCES assets(id) ON DELETE CASCADE

---

### 10. **inventory_pending_items** (Itens Pendentes no Inventário)

Ativos que ainda não foram encontrados/verificados.

| Campo        | Tipo         | Restrições           | Descrição                     |
|--------------|--------------|----------------------|-------------------------------|
| id           | VARCHAR(36)  | PK                   | UUID do registro              |
| inventory_id | VARCHAR(36)  | FK → inventory_records(id), NOT NULL | ID do inventário |
| asset_id     | VARCHAR(36)  | FK → assets(id), NOT NULL | ID do ativo              |
| added_date   | TIMESTAMP    | DEFAULT CURRENT_TS   | Data que foi adicionado       |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE KEY unique_inventory_pending (inventory_id, asset_id)
- INDEX idx_inventory_id (inventory_id)
- INDEX idx_asset_id (asset_id)

**Foreign Keys:**
- inventory_id REFERENCES inventory_records(id) ON DELETE CASCADE
- asset_id REFERENCES assets(id) ON DELETE CASCADE

---

### 11. **inventory_uncatalogued_items** (Itens Não Catalogados)

Itens encontrados durante inventário que não estão no sistema.

| Campo        | Tipo         | Restrições           | Descrição                     |
|--------------|--------------|----------------------|-------------------------------|
| id           | VARCHAR(36)  | PK                   | UUID do item                  |
| inventory_id | VARCHAR(36)  | FK → inventory_records(id), NOT NULL | ID do inventário |
| description  | TEXT         | NOT NULL             | Descrição do item             |
| location     | VARCHAR(255) | NULL                 | Localização                   |
| found_date   | TIMESTAMP    | DEFAULT CURRENT_TS   | Data que foi encontrado       |

**Índices:**
- PRIMARY KEY (id)
- INDEX idx_inventory_id (inventory_id)

**Foreign Keys:**
- inventory_id REFERENCES inventory_records(id) ON DELETE CASCADE

---

### 12. **inventory_reopen_history** (Histórico de Reabertura de Inventário)

Registra as reaberturas de inventários concluídos.

| Campo               | Tipo         | Restrições           | Descrição                     |
|---------------------|--------------|----------------------|-------------------------------|
| id                  | VARCHAR(36)  | PK                   | UUID do registro              |
| inventory_id        | VARCHAR(36)  | FK → inventory_records(id), NOT NULL | ID do inventário |
| reopened_by_user_id | VARCHAR(36)  | FK → users(id), NOT NULL | Quem reabriu             |
| reopened_at         | TIMESTAMP    | NOT NULL             | Quando foi reaberto           |
| justification       | TEXT         | NOT NULL             | Justificativa                 |
| created_at          | TIMESTAMP    | DEFAULT CURRENT_TS   | Data de criação               |

**Índices:**
- PRIMARY KEY (id)
- INDEX idx_inventory_id (inventory_id)
- INDEX idx_reopened_by (reopened_by_user_id)

**Foreign Keys:**
- inventory_id REFERENCES inventory_records(id) ON DELETE CASCADE
- reopened_by_user_id REFERENCES users(id) ON DELETE RESTRICT

---

## Mapeamento Frontend-Backend

### Convenções de Nomenclatura

| Frontend (TypeScript) | Backend (MySQL)       | API (JSON)            |
|-----------------------|-----------------------|-----------------------|
| camelCase             | snake_case            | camelCase             |
| `assetId`             | `asset_id`            | `assetId`             |
| `sectorName`          | `sector_name`         | `sector_name`         |
| `isActive`            | `is_active` (BOOLEAN) | `isActive` (boolean)  |

### Mapeamento de Tipos

#### Asset (Ativo)

| Frontend (types.ts)     | Backend (init.sql)      | Conversão                |
|-------------------------|-------------------------|--------------------------|
| `id: string`            | `id VARCHAR(36)`        | Direto                   |
| `qr_code: string`       | `qr_code VARCHAR(50)`   | Direto                   |
| `name: string`          | `name VARCHAR(255)`     | Direto                   |
| `category: string`      | `category ENUM`         | Direto                   |
| `status: string`        | `status ENUM`           | Direto                   |
| `photos: AssetPhoto[]`  | Tabela `asset_photos`   | JOIN + mapeamento        |
| `maintenanceHistory`    | Tabela `maintenance_history` | JOIN + mapeamento   |
| `sector_name?: string`  | JOIN com `sectors`      | Campo calculado no SELECT|
| `custodian_name?: string` | JOIN com `users`      | Campo calculado no SELECT|

#### Sector (Setor)

| Frontend                | Backend                 | Conversão                |
|-------------------------|-------------------------|--------------------------|
| `id: string`            | `id VARCHAR(36)`        | Direto                   |
| `name: string`          | `name VARCHAR(255)`     | Direto                   |
| `description?: string`  | `description TEXT`      | Direto                   |

#### MilitaryUser (Usuário)

| Frontend                | Backend                 | Conversão                |
|-------------------------|-------------------------|--------------------------|
| `id: string`            | `id VARCHAR(36)`        | Direto                   |
| `name: string`          | `name VARCHAR(255)`     | Direto                   |
| `rank: string`          | `rank VARCHAR(100)`     | Direto                   |
| `military_id: string`   | `military_id VARCHAR(50)` | Direto                 |
| `is_active: boolean`    | `is_active BOOLEAN`     | Boolean(value) no backend|
| `sector_name?: string`  | JOIN com `sectors`      | Campo calculado          |

#### CustodyLog (Cautela)

| Frontend                | Backend                 | Conversão                |
|-------------------------|-------------------------|--------------------------|
| `id: string`            | `id VARCHAR(36)`        | Direto                   |
| `cautela_number: string`| `cautela_number VARCHAR(50)` | Direto              |
| `assetIds?: string[]`   | Tabela `custody_assets` | Mapeamento de array      |
| `assets?: Asset[]`      | JOIN múltiplo           | Array de objetos         |
| `user_name?: string`    | JOIN com `users`        | Campo calculado          |

#### InventoryRecord (Inventário)

| Frontend                | Backend                 | Conversão                |
|-------------------------|-------------------------|--------------------------|
| `id: string`            | `id VARCHAR(36)`        | Direto                   |
| `commission_number`     | `commission_number`     | Direto                   |
| `foundItems`            | Tabela `inventory_found_items` | Array de InventoryAsset |
| `pendingItems`          | Tabela `inventory_pending_items` | Array de Asset       |
| `uncataloguedItems`     | Tabela `inventory_uncatalogued_items` | Array          |
| `reopenHistory`         | Tabela `inventory_reopen_history` | Array com JOIN     |

---

## Convenções de Nomenclatura

### API Endpoints

Todas as rotas seguem o padrão REST:

```
GET    /api/{resource}          - Listar todos
GET    /api/{resource}/:id      - Buscar por ID
POST   /api/{resource}          - Criar novo
PUT    /api/{resource}/:id      - Atualizar
DELETE /api/{resource}/:id      - Excluir
```

### Recursos Disponíveis

- `/api/sectors` - Setores
- `/api/users` - Usuários
- `/api/assets` - Ativos
  - `/api/assets/qr/:qrCode` - Buscar por QR Code
  - `/api/assets/utils/next-qr-code` - Gerar próximo QR Code
  - `/api/assets/:id/photos` - Gerenciar fotos
  - `/api/assets/:id/maintenance` - Gerenciar manutenções
- `/api/custody` - Cautelas
  - `/api/custody/:id/checkin` - Realizar devolução
- `/api/inventory` - Inventários
  - `/api/inventory/:id/found` - Adicionar item encontrado
  - `/api/inventory/:id/uncatalogued` - Adicionar item não catalogado
  - `/api/inventory/:id/complete` - Concluir inventário
  - `/api/inventory/:id/reopen` - Reabrir inventário
- `/api/dashboard` - Dashboard (estatísticas)

---

## Índices e Performance

### Full-Text Search

As seguintes tabelas possuem índices FULLTEXT para busca:

- **assets**: `name, description, serial_number, patrimony_id`
- **users**: `name, military_id`
- **sectors**: `name, description`

### Índices de Performance

- Todos os campos de data `updated_at` possuem índice
- Foreign keys possuem índice automático
- Campos de busca frequente (status, category, qr_code) possuem índice

### Otimizações Implementadas

1. **Connection Pool** - Pool de conexões MySQL configurado em `backend/config/database.js`
2. **Transações** - Operações críticas (cautelas, inventário) usam transações
3. **Cascade Deletes** - Deleções em cascata para fotos e manutenções
4. **Prevent Deletes** - RESTRICT em foreign keys críticas (user em custody)

---

## Notas de Desenvolvimento

### Antes de Desenvolver

1. **Sempre consulte este documento** antes de fazer alterações no schema
2. **Mantenha sincronizado** - Qualquer mudança no schema deve ser refletida aqui
3. **Migrations** - O arquivo `backend/init.sql` é a fonte da verdade do schema
4. **Types** - O arquivo `/types.ts` deve refletir as estruturas do banco

### Checklist de Desenvolvimento

- [ ] Consultou DATABASE_SCHEMA.md
- [ ] Verificou tipos em /types.ts
- [ ] Testou com dados reais
- [ ] Atualizou documentação se necessário

---

**Gerado automaticamente em:** 2025-10-14
**Versão do Schema:** 1.0.0
