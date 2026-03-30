# SPEC — FASE 5: Documentação Consolidada
**Status:** `[ ] Pendente`
**Pré-requisito:** Fases 0–4 concluídas.
**Checkpoint:** Estrutura `/docs/` organizada, sem duplicatas, `CONTRIBUTING.md` existente.

---

## Contexto

A documentação do projeto está fragmentada em 3 locais com sobreposição de conteúdo:

| Local atual | Qtd arquivos | Problema |
|---|---|---|
| `/docs/` | 16 arquivos | Mistura de índices, práticas, análises, features |
| `/project-docs/` | 3 arquivos (INDEX, LEVANTAMENTO, MODULO_INVENTARIO) | Sobreposição com `/docs/` |
| Raiz do projeto | README.md | Index duplicado com docs/README.md |
| `.aidev/` | RELATORIO_FASE4.md, VALIDACAO_FASE4.md, RESULTADO_TESTE_TRIGGERS.md | Documentos de fases antigas poluindo diretório de trabalho |

---

## Estrutura Alvo

```
docs/
├── README.md                           ← Índice ÚNICO da documentação
├── architecture/
│   ├── overview.md                     ← Visão geral da arquitetura do sistema
│   ├── database-schema.md              ← Schema + análise unificados
│   └── api-reference.md                ← Referência completa da API
├── development/
│   ├── best-practices.md               ← Melhores práticas unificadas
│   ├── docker-deploy.md                ← Guia de deploy Docker
│   └── contributing.md                 ← [NOVO] Guia de contribuição
├── features/                           ← Docs por módulo (mover de project-docs/)
│   ├── assets.md
│   ├── custody.md
│   ├── inventory.md
│   ├── cautela-assinada.md
│   └── criacao-cautela.md
└── archive/                            ← Documentos históricos (não excluir, apenas mover)
    ├── RELATORIO_FASE4.md
    ├── VALIDACAO_FASE4.md
    └── RESULTADO_TESTE_TRIGGERS.md
```

---

## Ações Exatas

### Passo 1 — Criar estrutura de diretórios

```bash
mkdir -p docs/architecture docs/development docs/features docs/archive
```

### Passo 2 — Mesclar documentação de melhores práticas

Mesclar `docs/BEST_PRACTICES.MD` + `docs/MELHORES_PRATICAS_SGAITI.md` → `docs/development/best-practices.md`

**Regra de mesclagem:**
- Manter todo o conteúdo único de cada arquivo
- Eliminar parágrafos repetidos (manter o mais completo)
- Título: `# Melhores Práticas de Desenvolvimento — SGAITI`

Após criar o arquivo mesclado:
```bash
git rm docs/BEST_PRACTICES.MD docs/MELHORES_PRATICAS_SGAITI.md
```

### Passo 3 — Mesclar schema e análise de banco

Mesclar `docs/DATABASE_SCHEMA.md` + `docs/DATABASE_ANALYSIS_REPORT.md` → `docs/architecture/database-schema.md`

Após:
```bash
git rm docs/DATABASE_SCHEMA.md docs/DATABASE_ANALYSIS_REPORT.md
```

### Passo 4 — Criar índice único

Mesclar `docs/INDICE_TECNICO.md` + `project-docs/INDEX.md` → `docs/README.md`

Após:
```bash
git rm docs/INDICE_TECNICO.md
```

### Passo 5 — Mover arquivos para nova estrutura

```bash
# API Reference
mv docs/API_REFERENCE.md docs/architecture/api-reference.md

# Docker deploy
mv docs/DOCKER_DEPLOY.md docs/development/docker-deploy.md

# Features
mv docs/SISTEMA_PDF_CAUTELA_ASSINADA.md docs/features/cautela-assinada.md
mv docs/MELHORIA_CRIACAO_CAUTELA.md docs/features/criacao-cautela.md
mv docs/MODAL_CONFIRMATION_SYSTEM.md docs/features/modal-confirmation.md
mv docs/BACKEND_FRONTEND_SYNC.md docs/features/backend-frontend-sync.md
mv docs/IMPLEMENTATION_WATCHER.md docs/features/implementation-watcher.md

# Project docs
mv project-docs/LEVANTAMENTO_FUNCIONALIDADES.md docs/features/levantamento-funcionalidades.md
mv project-docs/MODULO_INVENTARIO.md docs/features/inventario.md

# Arquivar documentos de fase
mv .aidev/RELATORIO_FASE4.md docs/archive/
mv .aidev/VALIDACAO_FASE4.md docs/archive/
mv .aidev/RESULTADO_TESTE_TRIGGERS.md docs/archive/
```

### Passo 6 — Remover diretório project-docs

```bash
git rm -r project-docs/
```

### Passo 7 — Criar `docs/development/contributing.md`

Conteúdo obrigatório do CONTRIBUTING.md:

```markdown
# Guia de Contribuição — SGAITI

## Ambiente de Desenvolvimento

### Pré-requisitos
- Docker e Docker Compose
- PHP 8.4
- Node.js 20+

### Setup Inicial
```bash
docker compose up -d
docker compose exec laravel.test php artisan migrate --seed
```

## Padrões de Commit (Conventional Commits PT-BR)

**Formato:** `tipo(escopo): descrição em português`

**Tipos válidos:**
- `feat` — nova funcionalidade
- `fix` — correção de bug
- `refactor` — refatoração sem mudança de comportamento
- `test` — adição ou correção de testes
- `docs` — documentação
- `chore` — tarefas de manutenção

**Regras obrigatórias:**
- ✅ Português do Brasil
- ✅ Sem emojis
- ✅ Sem `Co-Authored-By`
- ✅ Escopo entre parênteses

**Exemplos:**
```
feat(assets): adiciona validacao de qr_code unico
fix(custody): corrige calculo de prazo na impressao de cautela
refactor(api): migra logica de assets para AssetService
test(users): adiciona testes de criacao e listagem de usuarios
```

## Fluxo TDD (Obrigatório)

```
RED   → Escreva o teste que falha
GREEN → Escreva o mínimo de código para passar
REFACTOR → Refatore mantendo os testes verdes
```

```bash
# Rodar testes
php artisan test

# Rodar teste específico  
php artisan test --filter=AssetServiceTest

# Verificar estilo
./vendor/bin/pint --test
```

## Ferramentas de Desenvolvimento AI

### Laravel Boost (usar durante desenvolvimento)
- Liste rotas: ferramenta `list_routes`
- Inspecione banco: ferramenta `database_schema` ou `database_query`
- Veja último erro: ferramenta `last_error`
- Execute código: ferramenta `tinker`
- Busque documentação Laravel: ferramenta `search_docs`

### Context7 (usar ANTES de implementar)
1. `resolve-library-id` → obter ID da biblioteca
2. `query-docs` → consultar documentação atualizada
3. Implementar com base na documentação retornada

## Verificação Antes do Commit

```bash
# 1. Testes
php artisan test

# 2. Estilo de código
./vendor/bin/pint

# 3. Rota respondendo (obrigatório por regras .aidev)
curl -I http://localhost:8900/rota-alterada

# Se não retornar 200 OK: verificar base de conhecimento antes de corrigir
```

## Camadas da Aplicação

```
routes/ → Controllers → Services → Models
                      ↘ (também usado por)
              Livewire Components → Services → Models
```

- **Controllers:** Thin. Validação via Form Requests, resposta via Resources.
- **Services:** Toda lógica de negócio. Injeção de dependência via construtor.
- **Livewire:** UI interativa. Injetar Services, não acessar Models diretamente.
- **Models:** Eloquent puro. Relationships, scopes, accessors.
```

### Passo 8 — Atualizar README.md raiz

O README.md raiz deve referenciar a nova estrutura de docs:

```markdown
## Documentação

A documentação técnica completa está em [`/docs/`](./docs/README.md):

- [Arquitetura](./docs/architecture/overview.md)
- [Schema do Banco](./docs/architecture/database-schema.md)
- [Referência da API](./docs/architecture/api-reference.md)
- [Melhores Práticas](./docs/development/best-practices.md)
- [Guia de Contribuição](./docs/development/contributing.md)
- [Deploy Docker](./docs/development/docker-deploy.md)
```

---

## Critérios de Aceite

- [ ] `docs/` tem subdiretorias `architecture/`, `development/`, `features/`, `archive/`
- [ ] `docs/development/contributing.md` existe e contém seções de commit, TDD, ferramentas AI
- [ ] `docs/development/best-practices.md` existe (mesclado)
- [ ] `docs/architecture/database-schema.md` existe (mesclado)
- [ ] `docs/README.md` é o índice único
- [ ] `project-docs/` não existe mais no repositório
- [ ] `.aidev/RELATORIO_FASE4.md`, `.aidev/VALIDACAO_FASE4.md` foram movidos para `docs/archive/`
- [ ] `docs/BEST_PRACTICES.MD` não existe (removido)
- [ ] `docs/MELHORES_PRATICAS_SGAITI.md` não existe (removido)
- [ ] README.md raiz tem seção de Documentação com links corretos

## Commit Esperado

```
docs: consolida documentacao em estrutura unica e remove duplicatas

- cria estrutura docs/architecture, docs/development, docs/features, docs/archive
- mescla best-practices e melhores-praticas em docs/development/best-practices.md
- mescla database-schema e database-analysis em docs/architecture/database-schema.md
- cria docs/development/contributing.md com guia completo
- move documentos de fase para docs/archive/
- remove project-docs/ (conteudo migrado para docs/features/)
- atualiza README.md raiz com links para nova estrutura
```

## NÃO FAZER

- ❌ Não excluir nenhum arquivo sem antes verificar se o conteúdo foi migrado
- ❌ Não alterar nenhum arquivo em `app/`, `routes/`, `resources/`
- ❌ Não alterar `.aidev/rules/` (já foi atualizado na Fase 0)
- ❌ Não alterar `docs/GUIA_ECONOMIA_TOKENS.md` (não foi listado para modificação)
- ❌ Não alterar `docs/TASKS.md` sem verificar se está atualizado
