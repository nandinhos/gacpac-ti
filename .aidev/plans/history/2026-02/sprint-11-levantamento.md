# Sprint 11 - Levantamento Completo da Estrutura

**Data:** 2026-02-27
**Fase:** 1 - Levantamento e Planejamento
**Responsável:** AI Orchestrator Agent

---

## 📁 Estrutura Atual do Projeto

### Diretórios na Raiz:
```
gacpac-ti/
├── .agent/           # [?] A investigar - possível configuração de agente
├── .aidev/           # ✅ MANTER - AI Dev Superpowers
├── .claude/          # ✅ MANTER - Configurações Claude
├── .gemini/          # ✅ MANTER - Configurações Gemini
├── .git/             # ✅ MANTER - Controle de versão
├── .serena/          # ✅ MANTER - MCP Serena
├── backend/          # 🔄 MOVER CONTEÚDO PARA RAIZ - Sistema Laravel
├── docs/             # [?] A investigar - documentação antiga?
├── lessons/          # [?] A investigar - lições aprendidas antigas?
├── project-docs/     # [?] A investigar - docs do projeto antigo?
├── public/           # ❌ REMOVER - provavelmente React antigo
├── scripts/          # [?] A investigar - scripts do projeto antigo?
└── vendor/           # ❌ REMOVER - vendor na raiz (Laravel está em backend/vendor)
```

### Arquivos na Raiz:
```
├── .env              # ✅ MANTER E AJUSTAR - Variáveis Docker
├── .env.example      # ✅ MANTER E AJUSTAR - Template
├── .geminiignore     # ✅ MANTER
├── .gitignore        # ✅ MANTER E AJUSTAR
├── .mcp.json         # ✅ MANTER - Configuração MCP
├── AI_INSTRUCTIONS.md # ✅ MANTER
├── ANTIGRAVITY.md    # ✅ MANTER - Docs do projeto
├── GEMINI.md         # ✅ MANTER
├── README.md         # ✅ MANTER E ATUALIZAR
├── VERSION           # ✅ MANTER
├── deploy.sh         # [?] A investigar - script antigo?
├── dev-rebuild.sh    # [?] A investigar - script antigo?
└── docker-compose.yml # ✅ MANTER E REFATORAR
```

---

## 🔍 Investigação Necessária

### 1. Diretório `backend/` (Laravel - MOVER PARA RAIZ)
```bash
backend/
├── app/              # → app/
├── bootstrap/        # → bootstrap/
├── config/           # → config/
├── database/         # → database/
├── docker/           # → docker/ (ou remover se desnecessário)
├── lang/             # → lang/
├── public/           # → public/ (substituir public/ da raiz)
├── resources/        # → resources/
├── routes/           # → routes/
├── storage/          # → storage/
├── tests/            # → tests/
├── vendor/           # → vendor/ (substituir vendor/ da raiz)
├── .editorconfig     # → .editorconfig
├── .env              # → .env (mesclar com .env da raiz)
├── .env.example      # → .env.example (substituir)
├── .gemini/          # ❌ REMOVER (duplicado)
├── .gitattributes    # → .gitattributes
├── .gitignore        # → mesclar com .gitignore da raiz
├── artisan           # → artisan
├── boost.json        # → boost.json
├── composer.json     # → composer.json
├── composer.lock     # → composer.lock
├── Dockerfile        # → Dockerfile
├── jsconfig.json     # → jsconfig.json
├── package.json      # → package.json
├── package-lock.json # → package-lock.json
├── phpunit.xml       # → phpunit.xml
├── postcss.config.js # → postcss.config.js
├── README.md         # → mesclar com README.md da raiz
├── tailwind.config.js # → tailwind.config.js
└── vite.config.js    # → vite.config.js
```

### 2. Diretório `docs/` - A INVESTIGAR
- **Ação:** Verificar conteúdo e decidir se mantém ou remove

### 3. Diretório `lessons/` - A INVESTIGAR
- **Ação:** Verificar se são lições da versão React (remover) ou úteis (mover para .aidev/memory/)

### 4. Diretório `project-docs/` - A INVESTIGAR
- **Ação:** Verificar conteúdo e decidir destino

### 5. Diretório `public/` NA RAIZ - INVESTIGAR
- **Ação:** Verificar se é da versão React
- **Decisão:** Provavelmente remover e usar `backend/public/`

### 6. Diretório `scripts/` - A INVESTIGAR
- **Ação:** Verificar se são úteis ou da versão antiga

### 7. Diretório `vendor/` NA RAIZ - REMOVER
- **Ação:** Remover (vendor do PHP deve estar em backend/vendor e será movido)

### 8. Arquivos `deploy.sh` e `dev-rebuild.sh` - A INVESTIGAR
- **Ação:** Verificar se são úteis para o Laravel ou da versão antiga

---

## 📋 Checklist de Levantamento

- [ ] 1.1 - Mapear TODOS os arquivos e pastas da raiz do projeto ✅ PARCIAL
- [ ] 1.2 - Identificar o que é da versão React (remover) ⏳ EM ANDAMENTO
- [ ] 1.3 - Identificar o que é da versão Laravel (mover) ✅ IDENTIFICADO
- [ ] 1.4 - Identificar o que é compartilhado (manter ou ajustar) ⏳ EM ANDAMENTO
- [ ] 1.5 - Criar lista completa de movimentações ⏳ PRÓXIMO
- [ ] 1.6 - Fazer backup completo antes de iniciar ⏳ PENDENTE

---

## 🎯 Próximos Passos

1. Investigar conteúdo de: `docs/`, `lessons/`, `project-docs/`, `public/`, `scripts/`
2. Verificar scripts `deploy.sh` e `dev-rebuild.sh`
3. Criar plano detalhado de movimentação
4. Fazer backup antes de iniciar movimentações

---

**Status:** 🟡 Levantamento em andamento...
