# SGAITI-UM - Sistema de Gestão de Ativos de TI

**Sistema de Gestão de Ativos de TI para Unidades Militares da Força Aérea Brasileira**

Sistema completo para gerenciamento de ativos de TI, controle de cautelas, inventários e manutenção de equipamentos, desenvolvido especificamente para as necessidades operacionais das unidades militares da FAB.

---

## 🚀 Tecnologias

### Frontend
- **React 18** + **TypeScript**
- **Vite** (build tool)
- **Tailwind CSS** (estilização)
- **Lucide React** (ícones)

### Backend
- **Node.js** + **Express**
- **MySQL 8.0**
- **Multer** (upload de arquivos)
- **Docker** + **Docker Compose**

---

## 📚 Documentação

**Toda a documentação técnica está organizada na pasta [`/docs`](./docs/):**

- **[docs/README.md](./docs/README.md)** - Índice completo da documentação
- **[docs/DATABASE_SCHEMA.md](./docs/DATABASE_SCHEMA.md)** - Schema completo do banco de dados
- **[docs/API_REFERENCE.md](./docs/API_REFERENCE.md)** - Referência completa da API REST
- **[docs/BACKEND_FRONTEND_SYNC.md](./docs/BACKEND_FRONTEND_SYNC.md)** - Guia de sincronização de dados
- **[docs/DATABASE_ANALYSIS_REPORT.md](./docs/DATABASE_ANALYSIS_REPORT.md)** - Relatório de análise do banco
- **[docs/DOCKER_DEPLOY.md](./docs/DOCKER_DEPLOY.md)** - Guia de deploy com Docker

**⚠️ IMPORTANTE:** Sempre consulte a documentação em `/docs` antes de desenvolver!

---

## 🏃 Quick Start

### Pré-requisitos

- Node.js 18+
- Docker + Docker Compose
- npm ou yarn

### 1. Clone e Instale Dependências

```bash
# Frontend
npm install

# Backend
cd backend && npm install
```

### 2. Configure Variáveis de Ambiente

```bash
# Frontend - criar .env.local
cp .env.example .env.local

# Backend - criar .env
cd backend && cp .env.example .env
```

### 3. Inicie com Docker

```bash
# Iniciar todos os serviços (MySQL + Backend + Frontend)
docker-compose up -d

# Ver logs
docker-compose logs -f

# Acessar aplicação
# Frontend: http://localhost:3000
# Backend API: http://localhost:5000/api
```

### 4. Desenvolvimento Local (sem Docker)

```bash
# Terminal 1 - Backend
cd backend && npm run dev

# Terminal 2 - Frontend
npm run dev
```

---

## 📂 Estrutura do Projeto

```
/
├── backend/                 # API Express + MySQL
│   ├── config/             # Configurações
│   ├── routes/             # Rotas da API
│   ├── uploads/            # Arquivos enviados
│   ├── init.sql            # Schema do banco
│   └── server.js           # Servidor principal
│
├── src/                    # Frontend React
│   ├── components/         # Componentes React
│   └── services/           # Integrações (API, etc.)
│
├── docs/                   # 📚 DOCUMENTAÇÃO COMPLETA
│   ├── README.md           # Índice da documentação
│   ├── DATABASE_SCHEMA.md  # Schema do banco
│   ├── API_REFERENCE.md    # Referência da API
│   └── ...                 # Outros docs
│
├── types.ts                # Tipos TypeScript
├── docker-compose.yml      # Orquestração Docker
├── CLAUDE.md               # Instruções para Claude Code
└── README.md               # Este arquivo
```

---

## 🎯 Funcionalidades

### ✅ Gestão de Ativos
- Cadastro completo de equipamentos de TI
- Códigos QR para rastreamento
- Categorização (Computação, Periféricos, Energia, Comunicações)
- Controle de status (Em Uso, Disponível, Manutenção, Baixado)
- Upload de fotos
- Histórico de manutenções

### ✅ Gestão de Cautelas
- Emissão de cautelas (empréstimo de equipamentos)
- Controle de retirada e devolução
- Upload de termos (branco e assinado)
- Vínculo com militares responsáveis

### ✅ Inventários
- Criação de comissões de inventário
- Varredura por QR Code
- Itens encontrados/pendentes
- Registro de itens não catalogados
- Histórico de reabertura

### ✅ Gestão de Pessoal
- Cadastro de militares
- Posto/graduação da FAB
- Vínculo com setores
- Controle de ativos em custódia

### ✅ Dashboard
- Visão geral do sistema
- Estatísticas em tempo real
- Atividades recentes

---

## 🗄️ Banco de Dados

O sistema utiliza **MySQL 8.0** com as seguintes entidades principais:

- **sectors** - Setores da unidade
- **users** - Militares
- **assets** - Ativos de TI
- **custody_logs** - Cautelas
- **inventory_records** - Inventários

**Ver schema completo:** [docs/DATABASE_SCHEMA.md](./docs/DATABASE_SCHEMA.md)

---

## 🔧 Comandos Úteis

### Docker

```bash
# Iniciar serviços
docker-compose up -d

# Parar serviços
docker-compose down

# Rebuild
docker-compose up --build -d

# Logs
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f db

# Acessar MySQL
docker exec -it sgaiti-db mysql -u sgaiti_user -p sgaiti_db
```

### Desenvolvimento

```bash
# Frontend
npm run dev          # Servidor de desenvolvimento
npm run build        # Build para produção
npm run preview      # Preview da build

# Backend
cd backend
npm run dev          # Servidor com nodemon
npm start            # Servidor de produção
```

---

## 🌐 API Endpoints

**Base URL:** `http://localhost:5000/api`

### Principais Recursos

- `GET /api/sectors` - Listar setores
- `GET /api/users` - Listar usuários
- `GET /api/assets` - Listar ativos
- `GET /api/custody` - Listar cautelas
- `GET /api/inventory` - Listar inventários
- `GET /api/dashboard` - Estatísticas

**Ver documentação completa:** [docs/API_REFERENCE.md](./docs/API_REFERENCE.md)

---

## 🔐 Segurança

⚠️ **Nota:** Atualmente a API não possui autenticação implementada. JWT está planejado para versões futuras.

---

## 🧪 Testes

⚠️ **Testes automatizados não implementados ainda.**

Planejado:
- Jest para testes unitários
- Supertest para testes de API
- Cypress para testes E2E

---

## 📊 Status do Projeto

- ✅ Frontend React funcional
- ✅ Backend API REST completo
- ✅ Banco de dados MySQL estruturado
- ✅ Docker Compose configurado
- ✅ Documentação completa
- ⚠️ Autenticação (em desenvolvimento)
- ⚠️ Testes automatizados (planejado)
- ⚠️ Deploy em produção (planejado)

---

## 👥 Contexto Militar

Sistema desenvolvido para atender às necessidades específicas de unidades da **Força Aérea Brasileira (FAB)**, respeitando:

- Hierarquia e postos/graduações militares
- Nomenclatura e siglas da FAB
- Processos de cautela e inventário militares
- Formato de documentação oficial

**Postos/Graduações suportados:**
- Oficiais: Coronel, Tenente-Coronel, Major, Capitão, 1º Tenente, 2º Tenente
- Graduados: Suboficial, 1º Sargento, 2º Sargento, 3º Sargento
- Praças: Cabo, Soldado

**Especialidades:** Aviador, Especialista, Intendente, BCT, BCO, etc.

---

## 📝 Licença

Este projeto é de uso interno para unidades militares da FAB.

---

## 🤝 Contribuindo

Para contribuir com o projeto:

1. Consulte a [documentação completa](./docs/README.md)
2. Leia o [guia de sincronização](./docs/BACKEND_FRONTEND_SYNC.md)
3. Siga as convenções de código estabelecidas
4. Teste localmente antes de fazer commit
5. Atualize a documentação quando necessário

---

## 📞 Suporte

Para dúvidas ou suporte:

1. Consulte a [documentação](./docs/)
2. Verifique o [schema do banco](./docs/DATABASE_SCHEMA.md)
3. Consulte a [referência da API](./docs/API_REFERENCE.md)

---

**Desenvolvido para as Unidades Militares da Força Aérea Brasileira**

**Versão:** 1.0.0
**Última atualização:** 2025-10-14
