---
title: MAPA
type: note
permalink: gacpac-ti/docs/mapa
---

# Mapa do Projeto — SGTI-GAC

> Grafo de alinhamento: quem conversa com quem, quem pode o quê, como a entrega anda. Tudo abaixo foi levantado do código — sem peça decorativa.

## 1. Módulos e dependências

```mermaid
flowchart TB
    subgraph Entradas
        WEB["Web<br/>Blade + Livewire 4"]
        API["API REST<br/>Sanctum + Spatie"]
    end
    subgraph Controladores
        LWC["Livewire<br/>Assets, Custody, Inventory,<br/>Maintenance, Users, Sectors,<br/>Categories, Photos, Reports,<br/>Notifications, Dashboard, Admin"]
        CTL["Controllers API<br/>Asset, CustodyLog, InventoryRecord,<br/>Maintenance, Category, Sector,<br/>User, Notification, Report, Auth"]
    end
    subgraph Servicos["Services (usados pela API)"]
        SVC["Asset, Custody, Inventory,<br/>Maintenance, Category, User"]
    end
    subgraph Modelos["Models → PostgreSQL"]
        MDL["Asset, AssetPhoto, Category,<br/>CustodyLog, InventoryRecord,<br/>InventoryAsset, UncataloguedItem,<br/>ReopenHistory, MaintenanceRecord,<br/>User, Sector, AuditLog"]
    end
    WEB --> LWC
    API --> CTL
    LWC --> MDL
    CTL --> SVC --> MDL
```

Leitura: a Web reativa fala direto com os Models; a API passa por Services. Regra nova entra em Services (padrão em [METODOLOGIA](./METODOLOGIA.md)).

## 2. Papéis e permissões (Spatie, guard `web`)

```mermaid
flowchart LR
    ADMIN["admin<br/>todas as 13 permissões"]
    OPER["operator<br/>assets.*, inventory.view/create,<br/>maintenance.*, reports.view"]
    AUDIT["auditor<br/>assets.view, inventory.view,<br/>maintenance.view,<br/>reports.view, audit.view"]
    VIEW["viewer<br/>assets.view"]
    ADMIN --> OPER
    OPER --> AUDIT
    AUDIT --> VIEW
```

Conjunto real de permissões (`RolesAndPermissionsSeeder`): `assets.view/create/edit/delete`, `inventory.view/create/approve`, `maintenance.view/create/edit`, `reports.view`, `users.manage`, `audit.view`.

## 3. Ambientes e entrega

```mermaid
flowchart LR
    DEV["Dev local<br/>Sail :8900<br/>pgsql + pgadmin"] --> TEST["php artisan test<br/>151 testes verdes"]
    TEST --> MAIN["main<br/>sempre deployável"]
    MAIN --> ORIGIN["origin/main<br/>push direto"]
```

Convenções: [METODOLOGIA](./METODOLOGIA.md) · Setup: [INSTALL.md](../INSTALL.md) · API: [API.md](./API.md) · Arquitetura: [ARCHITECTURE.md](./ARCHITECTURE.md)