# 📊 SCHEMA COMPLETO DO BANCO DE DADOS - SGAITI-UM

## 📋 VISÃO GERAL

O sistema SGAITI-UM utiliza **MySQL 8.0** com uma arquitetura relacional normalizada. O banco contém 11 tabelas principais organizadas em módulos funcionais.

---

## 🏗️ ESTRUTURA GERAL

### **Entidades Principais**
- **sectors** - Setores/unidades militares
- **military_users** - Usuários militares (autenticação + perfis)
- **assets** - Ativos de TI (bens patrimoniais)
- **custody_logs** - Registros de cautelas
- **inventory_records** - Sessões de inventário
- **inventory_assets** - Itens encontrados em inventários
- **uncatalogued_items** - Itens não catalogados
- **asset_photos** - Fotos dos ativos
- **maintenance_records** - Histórico de manutenções
- **reopen_history** - Histórico de reaberturas de inventário
- **custody_assets** - Relacionamento muitos-para-muitos entre cautelas e ativos

---

## 📋 DETALHES DAS TABELAS

### **1. sectors**
**Propósito**: Gerenciar setores/unidades militares

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| name | VARCHAR(255) | NOT NULL | Nome do setor |
| description | TEXT | NULLABLE | Descrição do setor |
| is_active | BOOLEAN | DEFAULT TRUE | Status do setor |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: Nenhum adicional
**Relacionamentos**:
- 1:N com `military_users` (sector_id)
- 1:N com `assets` (sector_id)
- 1:N com `inventory_records` (sector_id)

---

### **2. military_users**
**Propósito**: Usuários militares com autenticação e perfis de acesso

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| name | VARCHAR(255) | NOT NULL | Nome completo |
| rank | VARCHAR(255) | NOT NULL | Posto/graduação militar |
| military_id | VARCHAR(255) | UNIQUE, NOT NULL | ID militar único |
| sector_id | BIGINT UNSIGNED | NULLABLE, FK | Setor de lotação |
| email | VARCHAR(255) | NULLABLE | Email opcional |
| phone | VARCHAR(255) | NULLABLE | Telefone |
| password | VARCHAR(255) | NULLABLE | Senha hasheada |
| is_active | BOOLEAN | DEFAULT TRUE | Usuário ativo |
| user_role | ENUM('user', 'commission', 'admin') | DEFAULT 'user' | Perfil de acesso |
| commission_inventories | JSON | NULLABLE | IDs dos inventários que pode gerenciar |
| email_verified_at | TIMESTAMP | NULLABLE | Verificação de email |
| remember_token | VARCHAR(100) | NULLABLE | Token de remember me |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: military_id (único)
**Relacionamentos**:
- N:1 com `sectors` (sector_id)
- 1:N com `custody_logs` (user_id)
- 1:N com `inventory_records` (responsible_user_id)

---

### **3. assets**
**Propósito**: Gestão completa de ativos de TI

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| qr_code | VARCHAR(255) | UNIQUE, NOT NULL | Código QR único |
| name | VARCHAR(255) | NOT NULL | Nome do ativo |
| **brand** | VARCHAR(255) | NULLABLE | Marca (campo novo) |
| **manufacturer** | VARCHAR(255) | NULLABLE | Fabricante (compatibilidade) |
| model | VARCHAR(255) | NULLABLE | Modelo |
| **type** | VARCHAR(255) | NULLABLE | Tipo de ativo (campo novo) |
| category | VARCHAR(255) | NOT NULL | Categoria |
| subcategory | VARCHAR(255) | NULLABLE | Subcategoria |
| description | TEXT | NULLABLE | Descrição |
| serial_number | VARCHAR(255) | NULLABLE | Número de série |
| **patrimony_number** | VARCHAR(255) | NULLABLE | Número patrimonial (novo) |
| **patrimony_id** | VARCHAR(255) | NULLABLE | ID patrimonial (compatibilidade) |
| acquisition_date | DATE | NULLABLE | Data de aquisição |
| warranty_expiry | DATE | NULLABLE | Vencimento da garantia |
| **purchase_value** | DECIMAL(10,2) | NULLABLE | Valor de compra (novo) |
| **purchase_price** | DECIMAL(10,2) | NULLABLE | Preço de compra (compatibilidade) |
| status | VARCHAR(255) | NOT NULL | Status do ativo |
| **condition** | VARCHAR(255) | NULLABLE | Condição (novo - string) |
| **condition_rating** | INTEGER | NULLABLE | Avaliação de condição (compatibilidade) |
| sector_id | BIGINT UNSIGNED | NULLABLE, FK | Setor atual |
| location | VARCHAR(255) | NULLABLE | Localização física |
| custodian_user_id | BIGINT UNSIGNED | NULLABLE, FK | Usuário responsável |
| notes | TEXT | NULLABLE | Observações |

**Campos de Inventário**:
| conta | VARCHAR(255) | NULLABLE | Conta contábil |
| categoria_inventario | VARCHAR(255) | NULLABLE | Categoria para inventário |
| bmp | VARCHAR(255) | NULLABLE | BMP |
| componente | VARCHAR(255) | NULLABLE | Componente |
| situacao | VARCHAR(255) | NULLABLE | Situação |
| qtd | INTEGER | NULLABLE | Quantidade |
| valor_atualizado | DECIMAL(15,2) | NULLABLE | Valor atualizado |
| deprec_acumulada | DECIMAL(15,2) | NULLABLE | Depreciação acumulada |
| valor_liquido | DECIMAL(15,2) | NULLABLE | Valor líquido |

**Índices**: qr_code (único)
**Relacionamentos**:
- N:1 com `sectors` (sector_id)
- N:1 com `military_users` (custodian_user_id)
- 1:N com `asset_photos` (asset_id)
- 1:N com `maintenance_records` (asset_id)
- N:N com `custody_logs` via `custody_assets`

---

### **4. custody_logs**
**Propósito**: Controle de empréstimos (cautelas) de equipamentos

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| cautela_number | VARCHAR(255) | UNIQUE, NOT NULL | Número da cautela |
| user_id | BIGINT UNSIGNED | NOT NULL, FK | Usuário responsável |
| checkout_date | DATETIME | NOT NULL | Data de retirada |
| checkin_date | DATETIME | NULLABLE | Data de devolução |
| term_url | VARCHAR(255) | NULLABLE | URL do termo em branco |
| signed_term_url | VARCHAR(255) | NULLABLE | URL do termo assinado |
| notes | TEXT | NULLABLE | Observações |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: cautela_number (único)
**Relacionamentos**:
- N:1 com `military_users` (user_id)
- N:N com `assets` via `custody_assets`

---

### **5. custody_assets**
**Propósito**: Relacionamento muitos-para-muitos entre cautelas e ativos

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| custody_log_id | BIGINT UNSIGNED | NOT NULL, FK | ID da cautela |
| asset_id | BIGINT UNSIGNED | NOT NULL, FK | ID do ativo |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: custody_log_id + asset_id (único composto)
**Relacionamentos**:
- N:1 com `custody_logs` (custody_log_id)
- N:1 com `assets` (asset_id)

---

### **6. inventory_records**
**Propósito**: Sessões de inventário físico

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| commission_number | VARCHAR(255) | UNIQUE, NOT NULL | Número da comissão |
| start_date | DATE | NOT NULL | Data de início |
| end_date | DATE | NULLABLE | Data de conclusão |
| sector_id | BIGINT UNSIGNED | NULLABLE, FK | Setor específico |
| responsible_user_id | BIGINT UNSIGNED | NULLABLE, FK | Usuário responsável |
| status | ENUM('Concluído', 'Reaberto', 'Em Andamento') | DEFAULT 'Em Andamento' | Status |
| notes | TEXT | NULLABLE | Observações |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: commission_number (único), status, sector_id, start_date
**Relacionamentos**:
- N:1 com `sectors` (sector_id)
- N:1 com `military_users` (responsible_user_id)
- 1:N com `inventory_assets` (inventory_id)
- 1:N com `uncatalogued_items` (inventory_id)
- 1:N com `reopen_history` (inventory_record_id)

---

### **7. inventory_assets**
**Propósito**: Ativos encontrados durante inventário

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| inventory_id | BIGINT UNSIGNED | NOT NULL, FK | ID do inventário |
| asset_id | BIGINT UNSIGNED | NOT NULL, FK | ID do ativo encontrado |
| observation | TEXT | NULLABLE | Observação do inventário |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: inventory_id + asset_id (único composto)
**Relacionamentos**:
- N:1 com `inventory_records` (inventory_id)
- N:1 com `assets` (asset_id)

---

### **8. uncatalogued_items**
**Propósito**: Itens encontrados mas não catalogados no sistema

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| inventory_id | BIGINT UNSIGNED | NOT NULL, FK | ID do inventário |
| description | TEXT | NOT NULL | Descrição do item |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: inventory_id
**Relacionamentos**:
- N:1 com `inventory_records` (inventory_id)

---

### **9. asset_photos**
**Propósito**: Fotos dos ativos

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| asset_id | BIGINT UNSIGNED | NOT NULL, FK | ID do ativo |
| url | VARCHAR(255) | NOT NULL | URL da foto |
| caption | VARCHAR(255) | NULLABLE | Legenda |
| mime_type | VARCHAR(255) | NOT NULL | Tipo MIME |
| uploaded_at | TIMESTAMP | NOT NULL | Data de upload |

**Índices**: asset_id
**Relacionamentos**:
- N:1 com `assets` (asset_id)

---

### **10. maintenance_records**
**Propósito**: Histórico de manutenções dos ativos

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| asset_id | BIGINT UNSIGNED | NOT NULL, FK | ID do ativo |
| date | DATE | NOT NULL | Data da manutenção |
| description | TEXT | NOT NULL | Descrição |
| performed_by | VARCHAR(255) | NULLABLE | Quem realizou |
| cost | DECIMAL(10,2) | NULLABLE | Custo |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: asset_id
**Relacionamentos**:
- N:1 com `assets` (asset_id)

---

### **11. reopen_history**
**Propósito**: Histórico de reaberturas de inventários

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identificador único |
| inventory_record_id | BIGINT UNSIGNED | NOT NULL, FK | ID do inventário |
| justification | TEXT | NOT NULL | Justificativa |
| reopened_by | BIGINT UNSIGNED | NOT NULL, FK | Quem reabriu |
| reopened_at | TIMESTAMP | NOT NULL | Quando reabriu |
| created_at | TIMESTAMP | NULLABLE | Data de criação |
| updated_at | TIMESTAMP | NULLABLE | Data de atualização |

**Índices**: inventory_record_id
**Relacionamentos**:
- N:1 com `inventory_records` (inventory_record_id)
- N:1 com `military_users` (reopened_by)

---

## 🔗 DIAGRAMA DE RELACIONAMENTOS

```
sectors (1) ────┬─── (N) military_users
                │
                ├─── (N) assets
                │
                └─── (N) inventory_records

military_users (1) ────┬─── (N) custody_logs
                       │
                       ├─── (N) inventory_records
                       │
                       └─── (N) reopen_history (reopened_by)

assets (N) ────┬─── (N) custody_assets
               │
               ├─── (1) custody_logs
               │
               ├─── (N) asset_photos
               │
               ├─── (N) maintenance_records
               │
               └─── (N) inventory_assets

inventory_records (1) ────┬─── (N) inventory_assets
                          │
                          ├─── (N) uncatalogued_items
                          │
                          └─── (N) reopen_history
```

---

## 🎯 CONSIDERAÇÕES IMPORTANTES

### **Campos Duplicados (Compatibilidade)**
O banco possui campos **antigos** e **novos** para facilitar migração:

- `manufacturer` ↔ `brand`
- `patrimony_id` ↔ `patrimony_number`
- `purchase_price` ↔ `purchase_value`
- `condition_rating` (int) ↔ `condition` (string)

### **Enums e Valores Permitidos**

**Asset Status**: Em Uso, Disponível, Manutenção, Baixado
**Asset Categories**: Computação, Periféricos, Energia, Comunicações, Outros Ativos de TI
**User Roles**: user, commission, admin
**Inventory Status**: Concluído, Reaberto, Em Andamento

### **Constraints de Integridade**
- Todas as foreign keys com `ON DELETE` apropriado
- Índices únicos em campos críticos
- Campos obrigatórios bem definidos
- Tipos de dados apropriados

### **Performance**
- Índices compostos em relacionamentos N:N
- Índices em campos de busca frequente
- Campos de data com índices para filtros temporais

---

## 🔧 SCRIPTS DE REFERÊNCIA

### **Popular Dados de Teste**
```bash
# Via Docker
docker-compose exec backend npm run seed

# Via Artisan
php artisan db:seed
```

### **Verificar Estrutura**
```sql
-- Ver todas as tabelas
SHOW TABLES;

-- Descrever tabela específica
DESCRIBE assets;

-- Ver índices
SHOW INDEX FROM assets;
```

---

## 📝 NOTAS DE DESENVOLVIMENTO

- **Migrations**: Sempre criar migrations para alterações no schema
- **Seeders**: Usar para dados iniciais de teste
- **Factories**: Para geração de dados de teste em massa
- **Relacionamentos**: Sempre definir no Model Eloquent correspondente
- **Campos Fillable**: Manter sincronizado com Form Requests

---

*Última atualização: 04/11/2025*
