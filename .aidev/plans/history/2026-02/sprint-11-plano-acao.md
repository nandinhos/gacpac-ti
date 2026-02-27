# Sprint 11 - Plano Detalhado de Ação

**Data:** 2026-02-27
**Fase 1:** ✅ CONCLUÍDA - Levantamento completo realizado

---

## 📊 Análise Completa

### ✅ Arquivos/Pastas MANTER (sem alteração):
- `.aidev/` - AI Dev Superpowers
- `.claude/` - Configurações Claude
- `.gemini/` - Configurações Gemini
- `.git/` - Controle de versão
- `.serena/` - MCP Serena
- `.agent/` - Configuração de agente
- `.geminiignore`
- `.mcp.json`
- `AI_INSTRUCTIONS.md`
- `ANTIGRAVITY.md`
- `GEMINI.md`
- `VERSION`

### 🔄 Arquivos/Pastas CONSOLIDAR/MESCLAR:
- `docs/` → ✅ **MANTER** (documentação do Laravel, não é React)
- `lessons/` → 🔄 **MOVER** para `.aidev/memory/lessons-learned/`
- `project-docs/` → ✅ **MANTER** (docs do projeto Laravel)
- `scripts/` → ✅ **MANTER** (scripts úteis: health-check, boost-mcp, etc)
- `README.md` raiz → 🔄 **MESCLAR** com `backend/README.md`
- `.env` raiz → 🔄 **CONSOLIDAR** com `backend/.env`
- `.env.example` raiz → 🔄 **CONSOLIDAR** com `backend/.env.example`
- `.gitignore` raiz → 🔄 **MESCLAR** com `backend/.gitignore`

### ❌ Arquivos/Pastas REMOVER:
- `public/` NA RAIZ (só contém favicon.svg - versão antiga)
- `vendor/` NA RAIZ (vendor duplicado - usar backend/vendor)
- `dev-rebuild.sh` (script para frontend React - obsoleto)
- `deploy.sh` (menciona MySQL, projeto usa PostgreSQL - desatualizado)

### 🚀 Pasta `backend/` MOVER TODO CONTEÚDO PARA RAIZ:
```
backend/app/              → app/
backend/bootstrap/        → bootstrap/
backend/config/           → config/
backend/database/         → database/
backend/docker/           → docker/
backend/lang/             → lang/
backend/public/           → public/ (SUBSTITUIR public/ existente)
backend/resources/        → resources/
backend/routes/           → routes/
backend/storage/          → storage/
backend/tests/            → tests/
backend/vendor/           → vendor/ (SUBSTITUIR vendor/ existente)
backend/.editorconfig     → .editorconfig
backend/.gitattributes    → .gitattributes
backend/artisan           → artisan
backend/boost.json        → boost.json
backend/composer.json     → composer.json
backend/composer.lock     → composer.lock
backend/Dockerfile        → Dockerfile
backend/jsconfig.json     → jsconfig.json
backend/package.json      → package.json
backend/package-lock.json → package-lock.json
backend/phpunit.xml       → phpunit.xml
backend/postcss.config.js → postcss.config.js
backend/tailwind.config.js → tailwind.config.js
backend/vite.config.js    → vite.config.js
backend/.env              → (mesclar com .env raiz)
backend/.env.example      → (substituir .env.example raiz)
backend/.gitignore        → (mesclar com .gitignore raiz)
backend/README.md         → (mesclar com README.md raiz)
backend/.gemini/          → (REMOVER - duplicado)
```

---

## 🎯 Plano de Execução Detalhado

### FASE 2: Limpeza (30 min)

#### 2.1 - Remover arquivos obsoletos
```bash
rm -rf public/                    # Favicon antigo
rm -rf vendor/                    # Vendor duplicado
rm -f dev-rebuild.sh              # Script React obsoleto
rm -f deploy.sh                   # Script desatualizado
```

#### 2.2 - Consolidar lessons
```bash
mkdir -p .aidev/memory/lessons-learned
mv lessons/*.md .aidev/memory/lessons-learned/
rmdir lessons/
```

### FASE 3: Reorganização (1h)

#### 3.1 - Mesclar arquivos de configuração (ANTES de mover)

**`.env` consolidado:**
```bash
# Criar backup
cp .env .env.backup
cp backend/.env backend/.env.backup

# Mesclar manualmente (próximo passo)
```

**`.gitignore` consolidado:**
```bash
# Mesclar regras únicas de ambos
cat .gitignore backend/.gitignore | sort | uniq > .gitignore.new
# Revisar e aplicar manualmente
```

**`README.md` consolidado:**
```bash
# Mesclar seções relevantes
# Manter estrutura do backend/README.md
# Adicionar informações úteis do README.md raiz
```

#### 3.2 - Mover conteúdo do backend/ para raiz
```bash
# Mover diretórios
mv backend/app .
mv backend/bootstrap .
mv backend/config .
mv backend/database .
mv backend/docker .
mv backend/lang .
mv backend/public .          # Substitui public/ antigo (já removido)
mv backend/resources .
mv backend/routes .
mv backend/storage .
mv backend/tests .
mv backend/vendor .          # Substitui vendor/ antigo (já removido)

# Mover arquivos de configuração
mv backend/.editorconfig .
mv backend/.gitattributes .
mv backend/artisan .
mv backend/boost.json .
mv backend/composer.json .
mv backend/composer.lock .
mv backend/Dockerfile .
mv backend/jsconfig.json .
mv backend/package.json .
mv backend/package-lock.json .
mv backend/phpunit.xml .
mv backend/postcss.config.js .
mv backend/tailwind.config.js .
mv backend/vite.config.js .

# Arquivos já mesclados (não mover, já tratados em 3.1)
# backend/.env (mesclado)
# backend/.env.example (substituir após mesclar)
# backend/.gitignore (mesclado)
# backend/README.md (mesclado)

# Remover diretório backend/ vazio
rm -rf backend/.gemini  # Duplicado
rmdir backend/          # Deve estar vazio agora
```

#### 3.3 - Atualizar docker-compose.yml
```yaml
# Atualizar volumes de:
# - './backend:/var/www/html'
# Para:
# - '.:/var/www/html'

# Aplicar configuração de portas altas
```

### FASE 4: Ajustes de Configuração (30 min)

#### 4.1 - Atualizar .env consolidado
```env
# Variáveis Docker (da raiz)
WWWUSER=1000
WWWGROUP=1000
APP_PORT=8900
VITE_PORT=5173
FORWARD_DB_PORT=54320
PGADMIN_PORT=8950

# Variáveis Laravel (de backend/.env)
APP_NAME=GacpacTI
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost:8900

# ... demais variáveis do Laravel
```

#### 4.2 - Atualizar .gitignore
```gitignore
# Consolidar regras de ambos arquivos
# Garantir que ignora:
/vendor/
/node_modules/
/public/hot
/public/storage
/storage/*.key
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
```

#### 4.3 - Atualizar README.md
- Instruções de setup com nova estrutura
- Comandos Docker atualizados
- Portas corretas (8900, 54320, 8950)

### FASE 5: Testes e Validação (45 min)

#### 5.1 - Recriar containers
```bash
docker compose down -v
docker compose up -d
```

#### 5.2 - Validar aplicação
```bash
docker compose exec laravel.test php artisan migrate:fresh --seed
docker compose exec laravel.test php artisan test
curl http://localhost:8900
```

#### 5.3 - Validar acessos
- http://localhost:8900 (Laravel)
- http://localhost:8950 (pgAdmin)
- localhost:54320 (PostgreSQL)

### FASE 6: Documentação (30 min)

#### 6.1 - Atualizar ROADMAP
- Adicionar Sprint 11 como concluída
- Marcar estrutura refatorada

#### 6.2 - Criar lição aprendida
- Documentar processo de refatoração
- Problemas encontrados e soluções

#### 6.3 - Commit e histórico
```bash
git add -A
git commit -m "refactor: reorganiza estrutura do projeto (React → Laravel)

- Remove pasta backend/, move conteúdo para raiz
- Remove artefatos da versão React
- Consolida arquivos .env e .gitignore
- Atualiza docker-compose.yml com portas altas
- Atualiza documentação

BREAKING CHANGE: Estrutura de diretórios completamente reorganizada

Co-authored-by: AI Orchestrator <ai@gacpac-ti>"
```

---

## ⚠️ Checklist de Segurança

Antes de executar:
- [ ] ✅ Fazer backup completo do projeto
- [ ] ✅ Verificar que não há mudanças não commitadas importantes
- [ ] ✅ Containers podem ser recriados do zero
- [ ] ✅ Banco de dados pode ser recriado com seeders

Durante execução:
- [ ] Executar comandos um por um (não em batch)
- [ ] Validar cada fase antes de prosseguir
- [ ] Manter backups dos arquivos mesclados

Após execução:
- [ ] Aplicação acessível
- [ ] Testes passando
- [ ] Migrações funcionando
- [ ] Commit realizado

---

**Status:** ✅ Plano completo - Pronto para execução
**Tempo estimado total:** ~3h30min
**Próximo passo:** Fazer backup e iniciar Fase 2
