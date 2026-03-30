# 🗺️ ROADMAP — Plano de Mitigação Técnica

> **Projeto:** gacpac-ti — Sistema de Gestão de Ativos e Cautelas (SGAITI)
> **Contexto:** Sistema originalmente em React, em processo de migração para Laravel + Livewire com API REST.
> **Estratégia:** Manter e completar a API REST como fonte única de verdade de negócio. Livewire e API consomem a mesma camada de Services.
> **Iniciado em:** 2026-03-30

---

## Legenda de Status

| Ícone | Status |
|---|---|
| `[ ]` | Pendente |
| `[/]` | Em andamento |
| `[x]` | Concluído |
| `[-]` | Cancelado / Não se aplica |

---

## 🔴 FASE 0 — Ferramentas de Desenvolvimento
> **Objetivo:** Ativar Laravel Boost (MCP Server) e padronizar uso do Context7.
> **Checkpoint:** Laravel Boost respondendo via `php artisan boost:mcp`, Context7 documentado nas regras.

- [ ] Executar `php artisan boost:install` (gera `boost.json`, `.mcp.json`, guidelines em `.ai/`)
- [ ] Registrar laravel-boost no Gemini: `gemini mcp add -s project -t stdio laravel-boost php artisan boost:mcp`
- [ ] Documentar padrão de uso do Laravel Boost em `.aidev/rules/generic.md`
- [ ] Documentar padrão de uso do Context7 em `.aidev/rules/generic.md`
- [ ] Adicionar `boost:update` ao script `post-update-cmd` do `composer.json`
- [ ] Validar: `php artisan boost:update` sem erros

**✅ Checkpoint 0 validado quando:** `php artisan boost:mcp --help` retornar lista de 15 ferramentas.

---

## 🔴 FASE 1 — Docker e Infraestrutura
> **Objetivo:** Eliminar conflito de versão PHP, garantir build do frontend em produção.
> **Checkpoint:** `docker compose build` passa sem erros, assets compilados no container.

- [ ] Alinhar versão PHP: atualizar `composer.json` de `^8.2` para `^8.4` (alinhando com `Dockerfile`)
- [ ] Descomentar `RUN npm run build` no `Dockerfile` (linha 65)
- [ ] Remover COPY duplicado do `nginx.conf` no `Dockerfile` (linha 71)
- [ ] Remover comentário duplicado `# Copy supervisor configuration` (linha 74)
- [ ] Testar: `docker compose build --no-cache`
- [ ] Testar: `docker compose up -d && curl -I http://localhost:8900`

**✅ Checkpoint 1 validado quando:** Container sobe, `curl -I http://localhost:8900` retorna `HTTP/1.1 200 OK`.

---

## 🟡 FASE 2 — Limpeza da Stack Frontend (React Residual)
> **Objetivo:** Remover dependências React que eram do sistema legado e não são mais usadas.
> **Checkpoint:** `npm run build` passa sem avisos de React, bundle sem arquivos React.

- [ ] Remover `@headlessui/react` do `package.json`
- [ ] Remover `@vitejs/plugin-react` do `package.json`
- [ ] Remover import e uso do plugin React em `vite.config.js`
- [ ] Executar `npm install` para atualizar `package-lock.json`
- [ ] Executar `npm run build` e verificar ausência de erros
- [ ] Verificar que nenhum componente `.jsx` ou `.tsx` está sendo importado

**✅ Checkpoint 2 validado quando:** `npm run build` sucesso sem referências a React.

---

## 🟡 FASE 3 — Correções de Código Base e Arquitetura
> **Objetivo:** Corrigir bugs de roteamento, segurança e criar camada de Services.
> **Checkpoint:** Suite de testes passando, rotas sem duplicatas, API com throttle.

- [ ] Remover rota duplicada `notifications.index` em `routes/web.php` (linha 46)
- [ ] Remover rota pública `/api/test` de `routes/api.php` (ou mover para ambiente de dev)
- [ ] Adicionar `throttle:api` ao middleware group da API em `routes/api.php`
- [ ] Corrigir nome do projeto em `composer.json`: `laravel/laravel` → `gacpac/gacpac-ti`
- [ ] Corrigir descrição em `composer.json`: descrição genérica → `Sistema de Gestão de Ativos e Cautelas`
- [ ] Criar `app/Services/AssetService.php`
- [ ] Criar `app/Services/CustodyService.php`
- [ ] Criar `app/Services/InventoryService.php`
- [ ] Criar `app/Services/UserService.php`
- [ ] Criar `app/Services/SectorService.php`
- [ ] Criar `app/Services/CategoryService.php`
- [ ] Refatorar `AssetController` para usar `AssetService`
- [ ] Refatorar `CustodyLogController` para usar `CustodyService`
- [ ] Refatorar `InventoryRecordController` para usar `InventoryService`
- [ ] Executar `php artisan test` — todos os testes devem passar

**✅ Checkpoint 3 validado quando:** `php artisan test` sem falhas + `./vendor/bin/pint --test` sem erros de estilo.

---

## 🟡 FASE 4 — API Completa (Finalização da Migração React → API+Livewire)
> **Objetivo:** Todos os módulos devem ter endpoint de API. Livewire consome Services, não lógica direta.
> **Checkpoint:** Todos os endpoints documentados respondendo com autenticação Sanctum.

### Módulos existentes — revisar e refatorar
- [ ] Assets API: revisar e cobrir com testes unitários no `AssetService`
- [ ] Sectors API: revisar e cobrir com testes
- [ ] Custody API: completar endpoints faltantes + testes
- [ ] Inventory API: revisar e cobrir com testes

### Módulos ausentes — criar
- [ ] Criar `app/Http/Controllers/UserController.php` (apiResource)
- [ ] Registrar rotas em `routes/api.php`: `Route::apiResource('users', UserController::class)`
- [ ] Adicionar `GET /api/users/active` e `GET /api/users/sector/{sector}`
- [ ] Criar `app/Http/Resources/UserResource.php`
- [ ] Criar testes: `tests/Feature/UserControllerTest.php`
- [ ] Criar `app/Http/Controllers/CategoryController.php` (apiResource)
- [ ] Registrar rotas: `Route::apiResource('categories', CategoryController::class)`
- [ ] Criar `app/Http/Resources/CategoryResource.php`
- [ ] Criar testes: `tests/Feature/CategoryControllerTest.php`
- [ ] Criar `app/Http/Controllers/MaintenanceController.php`
- [ ] Registrar rotas: `Route::apiResource('assets/{asset}/maintenance', MaintenanceController::class)`
- [ ] Criar `app/Http/Resources/MaintenanceResource.php`
- [ ] Criar testes: `tests/Feature/MaintenanceControllerTest.php`
- [ ] Revisar/completar `NotificationController.php` para API
- [ ] Adicionar `PATCH /api/notifications/{id}/read` e `PATCH /api/notifications/read-all`
- [ ] Criar `app/Http/Resources/NotificationResource.php`
- [ ] Criar testes: `tests/Feature/NotificationControllerTest.php`

### Validação dos endpoints
```bash
TOKEN=$(curl -s -X POST http://localhost:8900/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' | jq -r '.token')

curl -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/users
curl -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/categories
curl -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/assets/{id}/maintenance
curl -H "Authorization: Bearer $TOKEN" http://localhost:8900/api/notifications
```

**✅ Checkpoint 4 validado quando:** Todos os endpoints acima retornam `200 OK` com dados paginados.

---

## 🟢 FASE 5 — Documentação Consolidada
> **Objetivo:** Eliminar documentação duplicada e fragmentada. Estrutura única em `/docs/`.
> **Checkpoint:** Sem arquivos duplicados, CONTRIBUTING.md criado, `/docs/README.md` como índice único.

### Unificações
- [ ] Mesclar `docs/BEST_PRACTICES.MD` + `docs/MELHORES_PRATICAS_SGAITI.md` → `docs/development/best-practices.md`
- [ ] Mesclar `docs/INDICE_TECNICO.md` + `project-docs/INDEX.md` → `docs/README.md`
- [ ] Mesclar `docs/DATABASE_SCHEMA.md` + `docs/DATABASE_ANALYSIS_REPORT.md` → `docs/architecture/database-schema.md`
- [ ] Mover `docs/API_REFERENCE.md` → `docs/architecture/api-reference.md`
- [ ] Mover `docs/DOCKER_DEPLOY.md` → `docs/development/docker-deploy.md`

### Arquivamento
- [ ] Mover `.aidev/RELATORIO_FASE4.md` → `docs/archive/`
- [ ] Mover `.aidev/VALIDACAO_FASE4.md` → `docs/archive/`
- [ ] Mover `.aidev/RESULTADO_TESTE_TRIGGERS.md` → `docs/archive/`

### Novos documentos
- [ ] Criar `docs/development/contributing.md` (guia de contribuição padronizado)
  - Setup do ambiente
  - Padrão de commits (Conventional Commits PT-BR, sem emojis)
  - Fluxo TDD (RED → GREEN → REFACTOR)
  - Como usar Laravel Boost e Context7
  - Checklist de PR
- [ ] Atualizar `README.md` raiz com links para nova estrutura de docs

### Remover projeto-docs
- [ ] Arquivar e remover diretório `project-docs/` (conteúdo migrado para `/docs/`)

**✅ Checkpoint 5 validado quando:** `find docs/ -name "*.md" | wc -l` mostra estrutura organizada sem duplicatas.

---

## 📊 Resumo de Progresso

| Fase | Descrição | Status | Prioridade |
|---|---|---|---|
| 0 | Ferramentas (Boost + Context7) | Pendente | 🔴 Alta |
| 1 | Docker e Infraestrutura | Pendente | 🔴 Alta |
| 2 | Limpeza Frontend React | Pendente | 🟡 Média |
| 3 | Código Base e Services | Pendente | 🟡 Média |
| 4 | API Completa | Pendente | 🟡 Média |
| 5 | Documentação | Pendente | 🟢 Baixa |

---

## 🔖 Histórico de Checkpoints

| Data | Fase | O que foi validado | Resp. |
|---|---|---|---|
| — | — | — | — |

> Atualizar esta tabela após cada checkpoint validado.
