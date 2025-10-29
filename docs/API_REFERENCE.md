# API REFERENCE - SGAITI-UM

**Sistema de Gestão de Ativos de TI - Unidade Militar**

Última atualização: 2025-10-14

---

## 📋 Sumário

1. [Visão Geral](#visão-geral)
2. [Autenticação](#autenticação)
3. [Formato de Resposta](#formato-de-resposta)
4. [Endpoints](#endpoints)
   - [Setores](#setores)
   - [Usuários](#usuários)
   - [Ativos](#ativos)
   - [Cautelas](#cautelas)
   - [Inventários](#inventários)
   - [Dashboard](#dashboard)
5. [Códigos de Erro](#códigos-de-erro)

---

## Visão Geral

**Base URL:** `http://localhost:5000/api` (desenvolvimento)

**Base URL (Docker):** `http://backend:5000/api` (produção)

**Content-Type:** `application/json`

**Charset:** UTF-8

---

## Autenticação

Atualmente, a API não implementa autenticação. Futuramente será implementado JWT.

---

## Formato de Resposta

### Sucesso

```json
{
  "id": "uuid-here",
  "field1": "value1",
  "field2": "value2"
}
```

### Erro

```json
{
  "error": "Mensagem de erro em português"
}
```

---

## Endpoints

### Setores

#### GET /api/sectors

Retorna todos os setores.

**Query Parameters:** Nenhum

**Response:**
```json
[
  {
    "id": "uuid-1",
    "name": "Chefia",
    "description": "Chefia da unidade",
    "created_at": "2024-01-01T00:00:00.000Z",
    "updated_at": "2024-01-01T00:00:00.000Z"
  }
]
```

#### GET /api/sectors/:id

Retorna um setor específico.

**Response:**
```json
{
  "id": "uuid-1",
  "name": "Chefia",
  "description": "Chefia da unidade",
  "created_at": "2024-01-01T00:00:00.000Z",
  "updated_at": "2024-01-01T00:00:00.000Z"
}
```

#### POST /api/sectors

Cria um novo setor.

**Body:**
```json
{
  "name": "Novo Setor",
  "description": "Descrição do setor"
}
```

**Response:** 201 Created + objeto do setor criado

#### PUT /api/sectors/:id

Atualiza um setor.

**Body:**
```json
{
  "name": "Nome Atualizado",
  "description": "Nova descrição"
}
```

**Response:** 200 OK + objeto do setor atualizado

#### DELETE /api/sectors/:id

Exclui um setor.

**Validação:** Não permite exclusão se houver usuários ou ativos associados.

**Response:** 200 OK
```json
{
  "message": "Setor excluído com sucesso"
}
```

---

### Usuários

#### GET /api/users

Retorna todos os usuários.

**Query Parameters:**
- `active` (boolean) - Filtrar por usuários ativos/inativos
- `sectorId` (string) - Filtrar por setor

**Response:**
```json
[
  {
    "id": "uuid-1",
    "name": "Maj João Silva",
    "rank": "Major Aviador",
    "military_id": "123456",
    "sector_id": "uuid-setor",
    "email": "joao@fab.mil.br",
    "phone": "(61) 99999-9999",
    "is_active": true,
    "sector_name": "ATI",
    "created_at": "2024-01-01T00:00:00.000Z",
    "updated_at": "2024-01-01T00:00:00.000Z"
  }
]
```

#### GET /api/users/:id

Retorna um usuário específico.

**Response:** Objeto do usuário

#### POST /api/users

Cria um novo usuário.

**Body:**
```json
{
  "name": "Maj João Silva",
  "rank": "Major Aviador",
  "militaryId": "123456",
  "sectorId": "uuid-setor",
  "email": "joao@fab.mil.br",
  "phone": "(61) 99999-9999",
  "isActive": true
}
```

**Validação:**
- `name`, `rank`, `militaryId` são obrigatórios
- `militaryId` deve ser único

**Response:** 201 Created + objeto do usuário criado

#### PUT /api/users/:id

Atualiza um usuário.

**Body:** Mesma estrutura do POST

**Response:** 200 OK + objeto do usuário atualizado

#### DELETE /api/users/:id

Exclui um usuário.

**Validação:** Não permite exclusão se houver cautelas associadas.

**Response:** 200 OK

---

### Ativos

#### GET /api/assets

Retorna todos os ativos.

**Query Parameters:**
- `category` (string) - Filtrar por categoria
- `status` (string) - Filtrar por status
- `sectorId` (string) - Filtrar por setor
- `search` (string) - Buscar em nome, QR code, número de série, patrimônio

**Response:**
```json
[
  {
    "id": "uuid-1",
    "qr_code": "SGAITI-0001",
    "name": "Notebook Dell Latitude",
    "category": "Computação",
    "subcategory": "Notebook",
    "description": "Notebook para uso administrativo",
    "serial_number": "SN123456",
    "patrimony_id": "PAT-001",
    "manufacturer": "Dell",
    "model": "Latitude 5420",
    "acquisition_date": "2024-01-01",
    "warranty_expiry": "2027-01-01",
    "purchase_price": 5000.00,
    "status": "Em Uso",
    "condition_rating": 5,
    "sector_id": "uuid-setor",
    "location": "Sala 101",
    "custodian_user_id": "uuid-user",
    "notes": "Observações",
    "sector_name": "ATI",
    "custodian_name": "Maj João Silva",
    "custodian_rank": "Major Aviador",
    "photos": [
      {
        "id": "uuid-photo",
        "asset_id": "uuid-1",
        "url": "/uploads/photo.jpg",
        "caption": "Vista frontal",
        "uploaded_at": "2024-01-01T00:00:00.000Z"
      }
    ],
    "maintenanceHistory": [
      {
        "id": "uuid-maint",
        "asset_id": "uuid-1",
        "date": "2024-06-01",
        "description": "Troca de HD",
        "performed_by": "Técnico ABC",
        "cost": 500.00,
        "created_at": "2024-06-01T00:00:00.000Z"
      }
    ],
    "created_at": "2024-01-01T00:00:00.000Z",
    "updated_at": "2024-01-01T00:00:00.000Z"
  }
]
```

#### GET /api/assets/:id

Retorna um ativo específico (com fotos e manutenções).

**Response:** Objeto do ativo

#### GET /api/assets/qr/:qrCode

Busca um ativo pelo código QR.

**Response:** Objeto do ativo

#### GET /api/assets/utils/next-qr-code

Gera o próximo código QR disponível.

**Response:**
```json
{
  "qrCode": "SGAITI-0043"
}
```

#### POST /api/assets

Cria um novo ativo.

**Body:**
```json
{
  "qrCode": "SGAITI-0001",
  "name": "Notebook Dell",
  "category": "Computação",
  "subcategory": "Notebook",
  "description": "Descrição",
  "serialNumber": "SN123",
  "patrimonyId": "PAT-001",
  "manufacturer": "Dell",
  "model": "Latitude 5420",
  "acquisitionDate": "2024-01-01",
  "warrantyExpiry": "2027-01-01",
  "purchasePrice": 5000.00,
  "status": "Disponível",
  "conditionRating": 5,
  "sectorId": "uuid-setor",
  "location": "Sala 101",
  "custodianUserId": null,
  "notes": "Observações"
}
```

**Validação:**
- `qrCode`, `name`, `category` são obrigatórios
- `qrCode` deve ser único

**Response:** 201 Created + objeto do ativo criado

#### PUT /api/assets/:id

Atualiza um ativo.

**Body:** Mesma estrutura do POST

**Response:** 200 OK + objeto do ativo atualizado

#### DELETE /api/assets/:id

Exclui um ativo.

**Validação:** Não permite exclusão se houver cautelas associadas.

**Response:** 200 OK

#### POST /api/assets/:id/photos

Adiciona uma foto ao ativo.

**Content-Type:** `multipart/form-data`

**Body:**
- `photo` (file) - Arquivo da imagem (JPEG, JPG, PNG, GIF)
- `caption` (string, opcional) - Legenda da foto

**Validação:**
- Tamanho máximo: 5MB
- Tipos permitidos: jpeg, jpg, png, gif

**Response:** 201 Created
```json
{
  "id": "uuid-photo",
  "asset_id": "uuid-1",
  "url": "/uploads/photo-123456.jpg",
  "caption": "Vista frontal",
  "uploaded_at": "2024-01-01T00:00:00.000Z"
}
```

#### DELETE /api/assets/:id/photos/:photoId

Remove uma foto do ativo.

**Response:** 200 OK

#### POST /api/assets/:id/maintenance

Adiciona um registro de manutenção.

**Body:**
```json
{
  "date": "2024-06-01",
  "description": "Troca de HD",
  "performedBy": "Técnico ABC",
  "cost": 500.00
}
```

**Validação:**
- `date`, `description` são obrigatórios

**Response:** 201 Created + objeto da manutenção

#### DELETE /api/assets/:id/maintenance/:maintenanceId

Remove um registro de manutenção.

**Response:** 200 OK

---

### Cautelas

#### GET /api/custody

Retorna todas as cautelas.

**Query Parameters:**
- `active` (boolean) - Filtrar por cautelas ativas/inativas
- `userId` (string) - Filtrar por usuário

**Response:**
```json
[
  {
    "id": "uuid-1",
    "cautela_number": "001/GAC-PAC/2024",
    "user_id": "uuid-user",
    "checkout_date": "2024-01-01",
    "checkin_date": null,
    "term_url": "/uploads/termo-branco.pdf",
    "signed_term_url": "/uploads/termo-assinado.pdf",
    "notes": "Observações",
    "user_name": "Maj João Silva",
    "user_rank": "Major Aviador",
    "military_id": "123456",
    "assetIds": ["uuid-asset-1", "uuid-asset-2"],
    "assets": [
      {
        "id": "uuid-asset-1",
        "name": "Notebook Dell",
        "qr_code": "SGAITI-0001"
      }
    ],
    "created_at": "2024-01-01T00:00:00.000Z",
    "updated_at": "2024-01-01T00:00:00.000Z"
  }
]
```

#### GET /api/custody/:id

Retorna uma cautela específica.

**Response:** Objeto da cautela

#### POST /api/custody

Cria uma nova cautela (checkout).

**Body:**
```json
{
  "cautelaNumber": "002/GAC-PAC/2024",
  "userId": "uuid-user",
  "checkoutDate": "2024-01-15",
  "assetIds": ["uuid-asset-1", "uuid-asset-2"],
  "termUrl": "/uploads/termo.pdf",
  "notes": "Observações"
}
```

**Validação:**
- `cautelaNumber`, `userId`, `checkoutDate`, `assetIds` são obrigatórios
- `assetIds` deve ter pelo menos 1 item
- `cautelaNumber` deve ser único
- Todos os ativos devem estar com status "Disponível"

**Efeitos colaterais:**
- Ativos passam para status "Em Uso"
- Ativos recebem `custodian_user_id`

**Response:** 201 Created + objeto da cautela

#### PUT /api/custody/:id/checkin

Realiza a devolução da cautela (checkin).

**Body:**
```json
{
  "checkinDate": "2024-02-01",
  "signedTermUrl": "/uploads/termo-assinado.pdf"
}
```

**Validação:**
- `checkinDate` é obrigatório
- Cautela não pode estar já devolvida

**Efeitos colaterais:**
- Ativos passam para status "Disponível"
- Ativos têm `custodian_user_id` removido

**Response:** 200 OK + objeto da cautela

#### PUT /api/custody/:id

Atualiza informações da cautela (apenas notes, URLs).

**Body:**
```json
{
  "notes": "Novas observações",
  "termUrl": "/uploads/novo-termo.pdf",
  "signedTermUrl": "/uploads/novo-termo-assinado.pdf"
}
```

**Response:** 200 OK + objeto da cautela

#### DELETE /api/custody/:id

Exclui uma cautela.

**Validação:** Apenas cautelas já devolvidas podem ser excluídas.

**Response:** 200 OK

---

### Inventários

#### GET /api/inventory

Retorna todos os inventários.

**Query Parameters:**
- `status` (string) - Filtrar por status
- `sectorId` (string) - Filtrar por setor

**Response:**
```json
[
  {
    "id": "uuid-1",
    "commission_number": "CI-ATI-2024/01",
    "start_date": "2024-01-01",
    "end_date": "2024-01-15",
    "sector_id": "uuid-setor",
    "status": "Concluído",
    "notes": "Observações",
    "sector_name": "ATI",
    "foundItems": [
      {
        "id": "uuid-found",
        "inventory_id": "uuid-1",
        "asset_id": "uuid-asset",
        "found_date": "2024-01-02T10:00:00.000Z",
        "observation": "Ativo em bom estado",
        "qr_code": "SGAITI-0001",
        "name": "Notebook Dell"
      }
    ],
    "pendingItems": [
      {
        "id": "uuid-asset",
        "qr_code": "SGAITI-0005",
        "name": "Monitor LG"
      }
    ],
    "uncataloguedItems": [
      {
        "id": "uuid-uncat",
        "inventory_id": "uuid-1",
        "description": "Teclado sem etiqueta",
        "location": "Sala 101",
        "found_date": "2024-01-05T14:00:00.000Z"
      }
    ],
    "reopenHistory": [
      {
        "id": "uuid-reopen",
        "inventory_id": "uuid-1",
        "reopened_by_user_id": "uuid-user",
        "reopened_at": "2024-02-01T09:00:00.000Z",
        "justification": "Encontrado ativo faltante",
        "user_name": "Maj João Silva",
        "user_rank": "Major Aviador",
        "created_at": "2024-02-01T09:00:00.000Z"
      }
    ],
    "created_at": "2024-01-01T00:00:00.000Z",
    "updated_at": "2024-01-15T00:00:00.000Z"
  }
]
```

#### GET /api/inventory/:id

Retorna um inventário específico.

**Response:** Objeto do inventário

#### POST /api/inventory

Cria um novo inventário.

**Body:**
```json
{
  "commissionNumber": "CI-ATI-2024/02",
  "startDate": "2024-03-01",
  "sectorId": "uuid-setor",
  "notes": "Inventário do setor ATI"
}
```

**Validação:**
- `commissionNumber`, `startDate` são obrigatórios
- `commissionNumber` deve ser único

**Efeitos colaterais:**
- Todos os ativos (ou do setor, se especificado) são adicionados à lista de pendentes

**Response:** 201 Created + objeto do inventário

#### POST /api/inventory/:id/found

Adiciona um ativo à lista de encontrados.

**Body:**
```json
{
  "assetId": "uuid-asset",
  "observation": "Ativo em bom estado"
}
```

**Validação:**
- `assetId` é obrigatório

**Efeitos colaterais:**
- Ativo é removido da lista de pendentes (se estiver lá)

**Response:** 201 Created + objeto do item encontrado

#### POST /api/inventory/:id/uncatalogued

Adiciona um item não catalogado.

**Body:**
```json
{
  "description": "Teclado sem etiqueta",
  "location": "Sala 101"
}
```

**Validação:**
- `description` é obrigatório

**Response:** 201 Created + objeto do item não catalogado

#### PUT /api/inventory/:id/complete

Conclui um inventário.

**Body:**
```json
{
  "endDate": "2024-03-15"
}
```

**Validação:**
- `endDate` é obrigatório

**Efeitos colaterais:**
- Status muda para "Concluído"

**Response:** 200 OK + objeto do inventário

#### POST /api/inventory/:id/reopen

Reabre um inventário concluído.

**Body:**
```json
{
  "userId": "uuid-user",
  "justification": "Encontrado ativo faltante após conclusão"
}
```

**Validação:**
- `userId`, `justification` são obrigatórios
- Apenas inventários "Concluído" podem ser reabertos

**Efeitos colaterais:**
- Status muda para "Reaberto"
- `end_date` é limpo (NULL)
- Entrada é adicionada ao histórico de reabertura

**Response:** 200 OK + objeto do inventário

#### DELETE /api/inventory/:id

Exclui um inventário.

**Response:** 200 OK

#### DELETE /api/inventory/:id/uncatalogued/:uncataloguedId

Remove um item não catalogado.

**Response:** 200 OK

---

### Dashboard

#### GET /api/dashboard

Retorna estatísticas gerais do sistema.

**Response:**
```json
{
  "totalAssets": 150,
  "assetsByStatus": {
    "Em Uso": 80,
    "Disponível": 50,
    "Manutenção": 15,
    "Baixado": 5
  },
  "assetsByCategory": {
    "Computação": 60,
    "Periféricos": 50,
    "Energia": 20,
    "Comunicações": 10,
    "Outros Ativos de TI": 10
  },
  "activeCustodies": 12,
  "activeInventories": 2,
  "recentActivity": [
    {
      "type": "asset_created",
      "description": "Novo ativo criado: Notebook Dell",
      "timestamp": "2024-10-14T10:30:00.000Z"
    }
  ]
}
```

---

## Códigos de Erro

### HTTP Status Codes

- `200 OK` - Sucesso
- `201 Created` - Recurso criado com sucesso
- `400 Bad Request` - Erro de validação ou dados inválidos
- `404 Not Found` - Recurso não encontrado
- `500 Internal Server Error` - Erro interno do servidor

### Mensagens de Erro Comuns

```json
{
  "error": "Nome do setor é obrigatório"
}
```

```json
{
  "error": "QR Code já cadastrado"
}
```

```json
{
  "error": "Não é possível excluir setor com usuários ou ativos associados"
}
```

```json
{
  "error": "Ativos não disponíveis: Notebook Dell, Monitor LG"
}
```

---

## Convenções de Nome de Campos

### Enviando para API (Request Body)

Use **camelCase**:
```json
{
  "cautelaNumber": "001/GAC-PAC/2024",
  "userId": "uuid",
  "checkoutDate": "2024-01-01",
  "assetIds": ["uuid1", "uuid2"]
}
```

### Recebendo da API (Response)

Campos do banco vêm em **snake_case**, campos calculados em **snake_case** ou **camelCase**:
```json
{
  "id": "uuid",
  "cautela_number": "001/GAC-PAC/2024",
  "user_id": "uuid",
  "checkout_date": "2024-01-01",
  "user_name": "Maj João Silva",
  "assetIds": ["uuid1", "uuid2"]
}
```

**Nota:** A API backend atualmente **aceita camelCase** nos requests mas **retorna snake_case** nos responses (campos de banco de dados). O frontend deve fazer a conversão adequada.

---

**Gerado automaticamente em:** 2025-10-14
**Versão da API:** 1.0.0
