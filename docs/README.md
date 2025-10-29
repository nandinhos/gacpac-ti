# Documentação do Sistema SGAITI-UM

**Sistema de Gestão de Ativos de TI - Unidade Militar**

Bem-vindo à documentação técnica do SGAITI-UM. Esta pasta contém todos os documentos de referência para desenvolvimento e manutenção do sistema.

---

## 📚 Índice de Documentos

### 1. [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md)
**Referência completa do schema do banco de dados**

Consulte este documento para:
- Estrutura completa de todas as tabelas
- Tipos de dados e constraints
- Relacionamentos entre tabelas
- Índices e otimizações de performance
- Diagrama ER (Entity-Relationship)

**Quando usar:** Antes de criar queries SQL, adicionar campos, ou entender a estrutura de dados.

---

### 2. [API_REFERENCE.md](./API_REFERENCE.md)
**Documentação completa da API REST**

Consulte este documento para:
- Todos os endpoints disponíveis
- Parâmetros de requisição e resposta
- Exemplos de uso
- Códigos de erro
- Validações e regras de negócio

**Quando usar:** Ao integrar frontend com backend, criar novos endpoints, ou testar a API.

---

### 3. [BACKEND_FRONTEND_SYNC.md](./BACKEND_FRONTEND_SYNC.md)
**Guia de sincronização entre backend e frontend**

Consulte este documento para:
- Mapeamento de tipos TypeScript ↔ MySQL
- Convenções de nomenclatura (camelCase vs snake_case)
- Como dados fluem entre camadas
- Discrepâncias conhecidas e soluções
- Exemplos de integração

**Quando usar:** Ao criar novos tipos, sincronizar dados, ou resolver problemas de integração.

---

## 🚀 Workflow de Desenvolvimento

### Antes de Começar Qualquer Tarefa

1. **Consulte a documentação relevante:**
   - Criar/modificar tabela? → `DATABASE_SCHEMA.md`
   - Criar/modificar endpoint? → `API_REFERENCE.md`
   - Integrar frontend? → `BACKEND_FRONTEND_SYNC.md`

2. **Verifique as migrations:**
   - Arquivo principal: `/backend/init.sql`
   - Este é a fonte da verdade do schema

3. **Atualize os tipos do frontend:**
   - Arquivo: `/types.ts`
   - Deve refletir a estrutura do banco

4. **Teste localmente:**
   - Use Docker Compose para ambiente completo
   - Verifique tanto backend quanto frontend

### Ao Adicionar um Novo Campo

**Exemplo: Adicionar campo `warranty_status` à tabela `assets`**

1. **Atualizar schema SQL** (`backend/init.sql`):
```sql
ALTER TABLE assets ADD COLUMN warranty_status ENUM('Ativa', 'Expirada', 'N/A') DEFAULT 'N/A';
```

2. **Atualizar interface TypeScript** (`types.ts`):
```typescript
export interface Asset {
  // ... campos existentes
  warranty_status?: string;
}
```

3. **Atualizar backend routes** (`backend/routes/assets.js`):
```javascript
// No POST e PUT, adicionar warranty_status
const { /* ... */, warrantyStatus } = req.body;
// Adicionar no INSERT/UPDATE
```

4. **Atualizar documentação:**
   - Adicionar campo em `docs/DATABASE_SCHEMA.md`
   - Adicionar campo em `docs/API_REFERENCE.md`
   - Atualizar mapeamento em `docs/BACKEND_FRONTEND_SYNC.md`

5. **Atualizar frontend:**
   - Adicionar input no formulário
   - Atualizar visualização de detalhes

### Ao Criar um Novo Endpoint

**Exemplo: Criar endpoint de relatórios**

1. **Criar rota** (`backend/routes/reports.js`):
```javascript
const express = require('express');
const router = express.Router();

router.get('/asset-summary', async (req, res) => {
  // Implementação
});

module.exports = router;
```

2. **Registrar no server** (`backend/server.js`):
```javascript
app.use('/api/reports', require('./routes/reports'));
```

3. **Documentar** (`docs/API_REFERENCE.md`):
```markdown
### Relatórios

#### GET /api/reports/asset-summary
...
```

4. **Criar service no frontend** (`services/api.ts`):
```typescript
export const getAssetSummary = async () => {
  const response = await api.get('/reports/asset-summary');
  return response.data;
};
```

---

## 🗂️ Estrutura do Projeto

```
/
├── backend/
│   ├── config/
│   │   └── database.js          # Configuração do MySQL
│   ├── routes/
│   │   ├── assets.js            # Endpoints de ativos
│   │   ├── custody.js           # Endpoints de cautelas
│   │   ├── inventory.js         # Endpoints de inventários
│   │   ├── sectors.js           # Endpoints de setores
│   │   └── users.js             # Endpoints de usuários
│   ├── uploads/                 # Arquivos enviados
│   ├── init.sql                 # Schema do banco (SOURCE OF TRUTH)
│   ├── server.js                # Servidor Express
│   └── package.json
│
├── src/                         # Frontend React
│   ├── components/              # Componentes React
│   └── services/
│       └── api.ts               # Integração com API
│
├── docs/                        # 📚 DOCUMENTAÇÃO (VOCÊ ESTÁ AQUI)
│   ├── README.md                # Este arquivo
│   ├── DATABASE_SCHEMA.md       # Schema do banco
│   ├── API_REFERENCE.md         # Referência da API
│   └── BACKEND_FRONTEND_SYNC.md # Sincronização de dados
│
├── types.ts                     # Tipos TypeScript (frontend)
├── docker-compose.yml           # Orquestração Docker
├── CLAUDE.md                    # Instruções para Claude Code
└── README.md                    # README principal do projeto
```

---

## 🔧 Comandos Úteis

### Backend

```bash
# Iniciar servidor de desenvolvimento
cd backend && npm run dev

# Instalar dependências
cd backend && npm install

# Acessar banco de dados (Docker)
docker exec -it sgaiti-db mysql -u sgaiti_user -p sgaiti_db
```

### Frontend

```bash
# Iniciar servidor de desenvolvimento
npm run dev

# Build para produção
npm run build

# Instalar dependências
npm install
```

### Docker

```bash
# Iniciar todos os serviços
docker-compose up -d

# Ver logs
docker-compose logs -f

# Parar serviços
docker-compose down

# Reconstruir e iniciar
docker-compose up --build -d
```

---

## 📊 Convenções e Padrões

### Nomenclatura

| Contexto          | Convenção  | Exemplo               |
|-------------------|------------|-----------------------|
| Tabelas SQL       | snake_case | `custody_logs`        |
| Colunas SQL       | snake_case | `custodian_user_id`   |
| Interfaces TS     | PascalCase | `AssetPhoto`          |
| Propriedades TS   | snake_case | `asset_id`            |
| Variáveis JS/TS   | camelCase  | `assetId`             |
| Funções/Métodos   | camelCase  | `getAssetById()`      |
| Componentes React | PascalCase | `AssetManagement`     |
| Arquivos componentes | PascalCase | `AssetManagement.tsx` |
| Arquivos routes   | kebab-case | `assets.js`           |

### Enums e Constantes

```typescript
// Status de ativos
'Em Uso' | 'Disponível' | 'Manutenção' | 'Baixado'

// Categorias
'Computação' | 'Periféricos' | 'Energia' | 'Comunicações' | 'Outros Ativos de TI'

// Status de inventário
'Em Andamento' | 'Concluído' | 'Reaberto'
```

### Formato de QR Codes

```
SGAITI-XXXX
```
Onde XXXX é um número de 4 dígitos com padding zero (ex: `SGAITI-0001`, `SGAITI-0042`)

### Formato de Números de Cautela

```
XXX/GAC-PAC/YYYY
```
Onde:
- XXX = número sequencial de 3 dígitos
- GAC-PAC = unidade militar
- YYYY = ano

Exemplo: `001/GAC-PAC/2024`

### Formato de Números de Comissão de Inventário

```
CI-XXX-YYYY/NN
```
Onde:
- CI = Comissão de Inventário
- XXX = setor (ex: ATI)
- YYYY = ano
- NN = número sequencial

Exemplo: `CI-ATI-2024/01`

---

## ⚠️ Problemas Conhecidos e Soluções

### 1. Conversão de Boolean

**Problema:** MySQL retorna `is_active` como 0/1, mas TypeScript espera boolean.

**Solução:** Backend converte automaticamente:
```javascript
is_active: Boolean(user.is_active)
```

**Status:** ✅ Resolvido no backend

### 2. Nomenclatura Inconsistente

**Problema:** API aceita camelCase mas retorna snake_case.

**Solução Temporária:** Frontend usa snake_case nos tipos.

**Solução Ideal:** Implementar camada de transformação no backend.

**Status:** ⚠️ Em análise

### 3. Arrays Aninhados

**Problema:** `photos`, `assets`, `foundItems` exigem múltiplas queries.

**Solução:** Backend executa queries adicionais e monta objetos compostos.

**Status:** ✅ Implementado

---

## 📝 Checklist de Desenvolvimento

Antes de fazer commit:

- [ ] Código testado localmente
- [ ] Tipos TypeScript atualizados se necessário
- [ ] Documentação atualizada (DATABASE_SCHEMA, API_REFERENCE, SYNC)
- [ ] Migrations SQL atualizadas se necessário
- [ ] Validações implementadas no backend
- [ ] Error handling implementado
- [ ] Console.logs removidos
- [ ] Código formatado

---

## 🆘 Suporte

Para dúvidas sobre:

- **Estrutura de dados:** Consulte `DATABASE_SCHEMA.md`
- **Endpoints da API:** Consulte `API_REFERENCE.md`
- **Integração:** Consulte `BACKEND_FRONTEND_SYNC.md`
- **Contexto do projeto:** Consulte `/CLAUDE.md`
- **Setup e deploy:** Consulte `/README.md` principal

---

## 📅 Histórico de Atualizações

| Data       | Versão | Mudanças                                    |
|------------|--------|---------------------------------------------|
| 2025-10-14 | 1.0.0  | Criação inicial da documentação completa    |

---

**Última atualização:** 2025-10-14
**Mantenedor:** Equipe de Desenvolvimento SGAITI-UM
