---
title: README
type: note
permalink: gacpac-ti/readme
---

# SGTI-GAC — Sistema de Gestão de TI do GAC-PAC

**Sistema de Gestão de TI para Unidades Militares da Força Aérea Brasileira**

Controle de ativos de TI, cautelas (empréstimos), inventários, manutenções e pessoal militar, com interface web reativa, API REST autenticada e relatórios em PDF.

---

## 🧱 Stack

| Camada | Tecnologia |
|---|---|
| Runtime | PHP 8.4 via Laravel Sail (Docker) |
| Backend | Laravel 12 + Livewire 4 |
| Frontend | Blade + Tailwind CSS 3 + Vite 7 |
| Banco | PostgreSQL (container `pgsql`) |
| Auth Web | Sessão Laravel + throttle de login |
| Auth API | Laravel Sanctum (tokens) |
| Autorização | Spatie Laravel Permission (papéis e permissões) |
| Relatórios | DomPDF (PDF de cautelas e inventários) |
| Testes | Pest/PHPUnit (`php artisan test`) |

> Porta HTTP local: **8900** (`APP_PORT=8900` → http://localhost:8900)

---

## 🚀 Quick Start

Pré-requisitos: Docker + Docker Compose (Sail cuida do resto).

```bash
# 1. Subir os containers
docker compose up -d --build

# 2. Gerar chave, migrar e popular (dentro do Sail)
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate --seed
docker compose exec laravel.test php artisan storage:link

# 3. Frontend (primeira vez ou após mudar CSS/JS)
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build

# 4. Acessar
# http://localhost:8900/login
# admin@gac.pac.br / admin123 (seed local — troque em produção)
```

Guia completo: [INSTALL.md](./INSTALL.md)

---

## 📂 Estrutura do Projeto

```
├── app/
│   ├── Http/Controllers/   # Controllers web + API (Auth, Asset, Custody…)
│   ├── Http/Requests/      # FormRequests (validação: Store/Update + throttle)
│   ├── Livewire/           # Componentes reativos (Assets, Custody, Inventory…)
│   ├── Models/             # Asset, CustodyLog, InventoryRecord, User, Sector…
│   ├── Services/           # Regra de negócio (CustodyService, AssetService…)
│   └── Notifications/      # Notificações de ativos, cautelas, manutenção
├── resources/views/
│   ├── components/ui/      # Design system Blade (x-ui:button, x-ui:card…)
│   ├── livewire/           # Views dos componentes Livewire
│   └── layouts/            # app, guest, print
├── routes/
│   ├── web.php             # ~63 rotas web (auth + Livewire)
│   └── api.php             # 52 rotas API REST (prefixo api.)
├── database/
│   ├── migrations/         # Schema PostgreSQL
│   └── seeders/            # Admin, papéis, setores, dados de exemplo
├── tests/                  # Suíte automatizada (Feature + Unit)
├── docs/                   # Documentação técnica
└── docker/                 # nginx.conf do Sail
```

---

## 🎯 Funcionalidades

- **Ativos:** cadastro completo, fotos, QR Code, categorias, status e manutenções
- **Cautelas:** emissão, check-in/check-out, termos em PDF, responsável militar
- **Inventários:** comissões, varredura, itens encontrados/pendentes, reabertura
- **Pessoal:** militares com posto/graduação da FAB, vínculo com setores
- **Dashboard:** visão geral com estatísticas e atividades recentes
- **Notificações:** centro de notificações (criação, leitura, contadores)
- **Relatórios:** telas paginadas + exportação em PDF
- **API REST:** 52 endpoints sob `http://localhost:8900/api` — ver [docs/API.md](./docs/API.md)

---

## 🌐 API

**Base URL:** `http://localhost:8900/api` · **Auth:** Bearer token Sanctum (`POST /api/login`) · **Limite:** 60 req/min

Recursos: `assets`, `categories`, `custody`, `inventory`, `maintenance`, `sectors`, `users`, `notifications`, `me`, `health`.

Referência completa: [docs/API.md](./docs/API.md)

---

## 🧪 Testes

```bash
docker compose exec laravel.test php artisan test
```

Suíte com 151 testes (Feature + Unit) cobrindo auth, ativos, fotos, cautelas, API e permissões. Nenhum teste deve ser pulado ou removido para "fazer passar" — teste vermelho é requisito a corrigir.

---

## 📚 Documentação

| Documento | Conteúdo |
|---|---|
| [INSTALL.md](./INSTALL.md) | Instalação passo a passo com Sail |
| [QUICKSTART.md](./QUICKSTART.md) | Ativação do fluxo DEVORQ com IA |
| [ROADMAP.md](./ROADMAP.md) | Fases de mitigação técnica (concluídas) |
| [CONTRIBUTING.md](./CONTRIBUTING.md) | Como contribuir (Sail + Git + commits) |
| [docs/ARCHITECTURE.md](./docs/ARCHITECTURE.md) | Arquitetura e fluxos principais |
| [docs/API.md](./docs/API.md) | Referência da API REST |

---

## 👥 Contexto Militar

Desenvolvido para unidades da **Força Aérea Brasileira (FAB)**:

- Hierarquia e postos/graduações (oficiais, graduados, praças)
- Processos de cautela e inventário militares
- Papéis via Spatie: `admin`, `gestor_ti`, `responsavel_setor`, `usuario`

---

**Uso interno das Unidades Militares da FAB · Versão 1.0.0 · Atualizado em 2026-09-16**
