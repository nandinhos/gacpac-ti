---
title: ARCHITECTURE
type: note
permalink: gacpac-ti/docs/architecture
---

# Arquitetura — SGTI-GAC

> Estado observado do código na branch `main`. Convenção: nada aqui é adivinhação — cada afirmação vem de arquivo, rota ou teste executado.

## Visão geral

Monolito Laravel 12 com duas superfícies sobre o mesmo domínio:

```
Navegador ──► Blade + Livewire 4 ──► Services ──► Eloquent ──► PostgreSQL
Cliente API ─► Sanctum + Spatie ──► Controllers/API Resources ──┘
```

- **Web (`routes/web.php`, ~63 rotas):** páginas Livewire por módulo (`app/Livewire/{Assets,Custody,Inventory,Maintenance,Users,Sectors,Categories,Reports,Photos,Notifications,Admin}`), layout `app`, telas públicas em `layouts/guest`, impressão em `layouts/print`.
- **API (`routes/api.php`, 52 rotas, prefixo `api.`):** controllers em `app/Http/Controllers` com API Resources, auth por token Sanctum (`POST /api/login`), throttle `60 req/min`.
- **Regra de negócio:** concentrada em `app/Services` (ex.: `CustodyService`), chamada por Livewire e controllers — não duplicar lógica nas camadas de entrada.
- **Validação:** `app/Http/Requests` (Store/Update por recurso); login web com throttle anti-brute-force.
- **Autorização:** papéis Spatie (`admin`, `gestor_ti`, `responsavel_setor`, `usuario`) + Policies/Gates; a API aplica autorização policy-based em todos os controllers.
- **Uploads:** fotos de ativos em `storage/app/public/asset-photos`, servidos via symlink `public/storage` (`php artisan storage:link`).
- **Design system:** componentes Blade `x-ui:*` em `resources/views/components/ui` (button, card, input, table, badge, modal…).

## Fluxos principais

### 1. Login web
`GET /login` (guest) → `POST /login` com throttle → sessão + papel Spatie → redirect por perfil. Falha repetida bloqueia temporariamente o IP/usuário.

### 2. Cautela (empréstimo)
Livewire `Custody/*` ou `POST /api/custody` → FormRequest valida → `CustodyService` cria `CustodyLog`, vincula ativo + militar responsável → termo em PDF (`ReportController`) → notificação (`CustodyCreatedNotification`). Devolução via `PUT /api/custody/{id}/checkin`, que libera o ativo.

### 3. Inventário
Comissão criada (`InventoryRecord`) → varredura por QR (`api/assets/qr/{qrCode}` resolve o ativo) → itens marcados encontrado/pendente → conclusão (`complete`) ou reabertura (`reopen`) com histórico.

### 4. API autenticada
`POST /api/login` (email+senha, throttle) → token Sanctum → `Authorization: Bearer` nas demais rotas → `GET /api/me` valida. `POST /api/logout` revoga.

## Modelo de dados (núcleo)

`sectors` → `users` (militares, posto/graduação, `sector_id`) · `categories` → `assets` (QR, status) → `asset_photos`, `maintenance_records` · `custody_logs` (ativo + responsável + retirada/devolução) · `inventory_records` + itens.

## Onde mexer

| Quero… | Mexo em… |
|---|---|
| Nova tela | `app/Livewire/<Modulo>/` + `resources/views/livewire/<modulo>/` + rota em `web.php` |
| Novo endpoint | Controller + `FormRequest` + rota em `api.php` (+ teste em `tests/Feature`) |
| Nova regra de negócio | `app/Services` (nunca no controller/Livewire direto) |
| Novo papel/permissão | `RolesAndPermissionsSeeder` + Policy |
| Novo componente visual | `resources/views/components/ui/` |

## Premissas e lacunas

- **Premissa:** PostgreSQL via Sail é o banco suportado; SQLite/MySQL não são testados pela suíte.
- **Lacuna:** não há cache/queue configurados (tudo síncrono) — ponto de evolução se o volume crescer.