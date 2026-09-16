---
title: ROADMAP
type: note
permalink: gacpac-ti/roadmap
---

# 🗺️ ROADMAP — Plano de Mitigação Técnica (Finalizado)

> **Projeto:** gacpac-ti — Sistema de Gestão de Ativos e Cautelas (SGAITI)
> **Status Atual:** ✅ Concluído
> **Data de Finalização:** 2026-03-30

---

## 🟢 FASE 0 — Ferramentas de Desenvolvimento
- [x] Laravel Boost instalado e configurado (MCP Server via `.mcp.json`)
- [x] Orquestrador DEVORQ ativo em `.devorq/` (skills, regras e estado)
- [x] `boost:update` no `post-update-cmd` do `composer.json`
- [x] Context7 integrado para documentação externa

## 🟢 FASE 1 — Docker e Infraestrutura
- [x] PHP 8.4 alinhado no Dockerfile e composer.json
- [x] Fix definitivo de permissão NPM no Dockerfile
- [x] Limpeza de duplicatas Nginx/Supervisor no Dockerfile
- [x] Build limpo via Sail validado

## 🟢 FASE 2 — Limpeza Frontend (React Residual)
- [x] Remoção de `@headlessui/react` e `@vitejs/plugin-react`
- [x] Limpeza do `vite.config.js`
- [x] Build frontend Blade/Livewire validado (sem rastro de React)

## 🟢 FASE 3 — Código Base e Serviços
- [x] Camada `app/Services/` implementada para todos os módulos
- [x] Correção de rota duplicada `notifications.index`
- [x] Remoção de endpoints de teste inseguros
- [x] Aplicação de Laravel Pint em todo o código novo

## 🟢 FASE 4 — API REST Completa
- [x] 52 rotas ativas e documentadas
- [x] API Resources para todos os modelos
- [x] Autorização Spatie (Policy-based) em todos os controllers
- [x] Rate Limiter `api` configurado (60 req/min)
- [x] Validado com `ApiIntegrationTest` (100% PASS)

## 🟢 FASE 5 — Consolidação da Documentação
- [x] Estrutura organizada em `/docs` (`ARCHITECTURE.md`, `API.md`)
- [x] Criado `CONTRIBUTING.md` com guia de Sail e Git
- [x] `README.md` e `INSTALL.md` reescritos para a stack real (Laravel 12 + Sail)

## 🟢 FASE 6 — Ecossistema de IA (Boost, Serena, Stitch)
- [x] Laravel Boost (MCP) integrado e validado via Sail
- [x] Serena MCP removida da esteira em 2026-09-16 (bloco no `.mcp.json` + diretório `.serena/`)
- [x] Servidores MCP declarados em `.mcp.json` (Boost, Context7, Basic Memory, context-mode)
- [x] Grafo AST local via Graphify (`graphify-out/`, ignorado no Git) com `dev` sincronizada (topologia CEH clássica)

---

## 📊 Resumo Final de Saúde

| Módulo | Status | Saúde |
|---|---|---|
| **Infra** | ✅ | Docker Otimizado |
| **Segurança** | ✅ | Spatie + Sanctum + Throttle |
| **Código** | ✅ | Padronizado via Services |
| **API** | ✅ | RESTful Completa |
| **Docs** | ✅ | Centralizada |
| **IA/MCP** | ✅ | Boost + Context7 + Basic Memory + context-mode; AST em graphify-out |

> **Próximos Passos Sugeridos:** manter a suíte verde (`php artisan test`, 151 testes), trocar a senha seed do admin em qualquer ambiente exposto e avaliar cache/filas quando o volume de uso crescer.