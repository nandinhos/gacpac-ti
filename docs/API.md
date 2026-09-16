---
title: API
type: note
permalink: gacpac-ti/docs/api
---

# API REST — SGTI-GAC

> Gerado a partir de `php artisan route:list --path=api` (52 rotas, prefixo de nomes `api.`).

- **Base URL (local):** `http://localhost:8900/api`
- **Autenticação:** token Sanctum via `POST /api/login` → usar `Authorization: Bearer <token>`
- **Limite:** 60 req/min (throttle `api`); login com throttle dedicado
- **Autorização:** policies por recurso (papéis Spatie)

## Auth

| Método | URI | Nome |
|---|---|---|
| POST | `/api/login` | login (email + password → token) |
| POST | `/api/logout` | `api.` (revoga token) |
| GET | `/api/me` | `api.me` (usuário autenticado) |
| GET | `/api/health` | saúde do serviço |

## Ativos (`AssetController`)

| Método | URI | Nome |
|---|---|---|
| GET | `/api/assets` | `api.assets.index` |
| POST | `/api/assets` | `api.assets.store` |
| GET | `/api/assets/qr/{qrCode}` | `api.assets.by-qr` (busca por QR) |
| GET | `/api/assets/utils/next-qr-code` | `api.assets.next-qr` |
| GET | `/api/assets/{asset}` | `api.assets.show` |
| PUT/PATCH | `/api/assets/{asset}` | `api.assets.update` |
| DELETE | `/api/assets/{asset}` | `api.assets.destroy` |

## Categorias · Setores · Usuários

CRUD padrão (`index/store/show/update/destroy`):

- `/api/categories` → `api.categories.*`
- `/api/sectors` → `api.sectors.*`
- `/api/users` → `api.users.*`, mais `GET /api/users/active` (`api.users.active`) e `GET /api/users/sector/{sectorId}` (`api.users.by-sector`)

## Cautelas (`CustodyLogController`)

| Método | URI | Nome |
|---|---|---|
| GET | `/api/custody` | `api.custody.index` |
| POST | `/api/custody` | `api.custody.store` |
| GET | `/api/custody/utils/next-number` | `api.custody.next-number` |
| GET | `/api/custody/{custody}` | `api.custody.show` |
| PUT/PATCH | `/api/custody/{custody}` | `api.custody.update` |
| DELETE | `/api/custody/{custody}` | `api.custody.destroy` |
| PUT | `/api/custody/{custodyLog}/checkin` | `api.custody.checkin` (devolução) |

## Inventários (`InventoryRecordController`)

| Método | URI | Nome |
|---|---|---|
| GET/POST | `/api/inventory` | `api.inventory.index/store` |
| GET/PUT/PATCH/DELETE | `/api/inventory/{inventory}` | `api.inventory.show/update/destroy` |
| PUT | `/api/inventory/{inventoryRecord}/complete` | `api.inventory.complete` |
| PUT | `/api/inventory/{inventoryRecord}/reopen` | `api.inventory.reopen` |

## Manutenções · Notificações

- Ativos: `/api/assets/{asset}/maintenance` (CRUD `api.maintenance.*`) + `GET /api/maintenance/upcoming`
- `/api/notifications` (`index`), `PATCH /read-all`, `GET /unread-count`, `PATCH /{id}/read`

## Exemplo

```bash
# 1. Login (retorna o token no JSON)
curl -s -X POST http://localhost:8900/api/login \
  -H 'Content-Type: application/json' \
  -d '{"email":"admin@gac.pac.br","password":"admin123"}'

# 2. Listar ativos com o token
curl -s http://localhost:8900/api/assets \
  -H "Authorization: Bearer <token-do-login>" | head -c 500
```