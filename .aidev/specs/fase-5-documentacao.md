# SPEC — FASE 5: Documentação Consolidada
**Status:** `[ ] Pendente`
**Ambiente:** Laravel Sail (Docker) — esta fase trabalha apenas com arquivos do repositório. Nenhum comando Sail de PHP/Artisan é necessário.
**Pré-requisito:** Fases 0–4 concluídas.
**Checkpoint:** Estrutura `/docs/` organizada, sem duplicatas, `CONTRIBUTING.md` existente.

---

## Contexto

A documentação do projeto está fragmentada em 3 locais com sobreposição de conteúdo:

| Local atual | Qtd arquivos | Problema |
|---|---|---|
| `/docs/` | 16 arquivos | Mistura de índices, práticas, análises, features |
| `/project-docs/` | 3 arquivos | Sobreposição com `/docs/` |
| `.aidev/` | 3 arquivos de fases antigas | Poluindo diretório de trabalho ativo |

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
├── features/                           ← Docs por módulo
│   ├── cautela-assinada.md
│   ├── criacao-cautela.md
│   ├── modal-confirmation.md
│   ├── backend-frontend-sync.md
│   ├── implementation-watcher.md
│   ├── levantamento-funcionalidades.md
│   └── inventario.md
└── archive/                            ← Histórico (mover, não excluir)
    ├── RELATORIO_FASE4.md
    ├── VALIDACAO_FASE4.md
    └── RESULTADO_TESTE_TRIGGERS.md
```

---

## Ações Exatas

### Passo 1 — Criar estrutura de diretórios

```bash
# Na máquina host (manipula arquivos do repositório)
mkdir -p docs/architecture docs/development docs/features docs/archive
```

### Passo 2 — Mesclar documentação de melhores práticas

Mesclar `docs/BEST_PRACTICES.MD` + `docs/MELHORES_PRATICAS_SGAITI.md` → `docs/development/best-practices.md`

**Regra de mesclagem:**
- Ler os dois arquivos
- Manter todo o conteúdo único de cada arquivo
- Eliminar parágrafos repetidos (manter o mais completo/detalhado)
- Título do arquivo resultante: `# Melhores Práticas de Desenvolvimento — SGAITI`

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
# Architecture
mv docs/API_REFERENCE.md docs/architecture/api-reference.md

# Development
mv docs/DOCKER_DEPLOY.md docs/development/docker-deploy.md

# Features
mv docs/SISTEMA_PDF_CAUTELA_ASSINADA.md docs/features/cautela-assinada.md
mv docs/MELHORIA_CRIACAO_CAUTELA.md docs/features/criacao-cautela.md
mv docs/MODAL_CONFIRMATION_SYSTEM.md docs/features/modal-confirmation.md
mv docs/BACKEND_FRONTEND_SYNC.md docs/features/backend-frontend-sync.md
mv docs/IMPLEMENTATION_WATCHER.md docs/features/implementation-watcher.md
mv project-docs/LEVANTAMENTO_FUNCIONALIDADES.md docs/features/levantamento-funcionalidades.md
mv project-docs/MODULO_INVENTARIO.md docs/features/inventario.md

# Archive
mv .aidev/RELATORIO_FASE4.md docs/archive/
mv .aidev/VALIDACAO_FASE4.md docs/archive/
mv .aidev/RESULTADO_TESTE_TRIGGERS.md docs/archive/
```

### Passo 6 — Remover diretório project-docs

```bash
git rm -r project-docs/
```

### Passo 7 — Criar `docs/development/contributing.md`

Criar com este conteúdo exato:

```markdown
# Guia de Contribuição — SGAITI

## Ambiente de Desenvolvimento

### Pré-requisitos
- Docker e Docker Compose
- Laravel Sail configurado

### Referência de Comandos (via Laravel Sail)

| Ação | Comando |
|---|---|
| Subir containers | `./vendor/bin/sail up -d` |
| Parar containers | `./vendor/bin/sail down` |
| Shell no container | `./vendor/bin/sail shell` |
| Artisan | `./vendor/bin/sail artisan [cmd]` |
| Composer | `./vendor/bin/sail composer [cmd]` |
| NPM | `./vendor/bin/sail npm [cmd]` |
| Testes | `./vendor/bin/sail artisan test` |
| Pint (style) | `./vendor/bin/sail exec laravel.test ./vendor/bin/pint` |
| Migrations | `./vendor/bin/sail artisan migrate` |
| Tinker | `./vendor/bin/sail artisan tinker` |
| Logs | `./vendor/bin/sail logs -f laravel.test` |

### Setup Inicial
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

## Padrões de Commit (Conventional Commits PT-BR)

**Formato:** `tipo(escopo): descrição em português`

**Tipos válidos:** `feat` | `fix` | `refactor` | `test` | `docs` | `chore`

**Regras obrigatórias:**
- ✅ Português do Brasil
- ✅ Sem emojis
- ✅ Sem `Co-Authored-By`

**Exemplos:**
```
feat(assets): adiciona validacao de qr_code unico
fix(custody): corrige calculo de prazo na impressao de cautela
refactor(api): migra logica de assets para AssetService
test(users): adiciona testes de criacao de usuarios
```

## Fluxo TDD (Obrigatório)

RED → Escreva o teste que falha
GREEN → Escreva o mínimo de código para passar
REFACTOR → Refatore mantendo os testes verdes

```bash
./vendor/bin/sail artisan test --filter=NomeDoTeste
```

## Ferramentas de Desenvolvimento AI

### Laravel Boost (usar durante desenvolvimento)
Requer Sail rodando. Ferramentas disponíveis via MCP:
- `list_routes` — listar rotas registradas
- `database_schema` — ver schema do banco
- `database_query` — executar queries
- `last_error` — ver último erro do log
- `tinker` — executar código PHP
- `search_docs` — documentação Laravel/Livewire

### Context7 (usar ANTES de implementar)
1. `resolve-library-id` → obter ID da biblioteca
2. `query-docs` → consultar documentação atualizada
3. Implementar com base na documentação retornada

## Camadas da Aplicação

```
routes/ → Controllers → Services → Models
                         ↑
              Livewire Components ──┘
```

- **Controllers:** Thin. Validação via Form Requests, resposta via Resources.
- **Services:** Toda lógica de negócio. Injeção de dependência via construtor.
- **Livewire:** UI interativa. Injetar Services, não acessar Models diretamente.
- **Models:** Eloquent puro. Relationships, scopes, accessors.

## Verificação Antes do Commit

```bash
# 1. Testes
./vendor/bin/sail artisan test

# 2. Estilo de código
./vendor/bin/sail exec laravel.test ./vendor/bin/pint

# 3. Rota respondendo (obrigatório)
curl -I http://localhost:8900/rota-alterada
# Se não retornar 200 OK: verificar base de conhecimento antes de corrigir
```
```

### Passo 8 — Atualizar README.md raiz

Adicionar ou substituir a seção de documentação:

```markdown
## Documentação

A documentação técnica está em [`/docs/`](./docs/README.md):

- [Arquitetura](./docs/architecture/overview.md)
- [Schema do Banco](./docs/architecture/database-schema.md)
- [Referência da API](./docs/architecture/api-reference.md)
- [Melhores Práticas](./docs/development/best-practices.md)
- [Guia de Contribuição](./docs/development/contributing.md)
- [Deploy Docker](./docs/development/docker-deploy.md)
```

### Passo 9 — Verificação final

```bash
# Confirmar estrutura correta
find docs/ -name "*.md" | sort

# Confirmar que project-docs não existe mais
ls project-docs/ 2>/dev/null && echo "ERRO: project-docs ainda existe" || echo "OK"

# Confirmar que arquivos antigos foram removidos
ls docs/BEST_PRACTICES.MD 2>/dev/null && echo "ERRO: arquivo antigo ainda existe" || echo "OK"
```

---

## Critérios de Aceite

- [ ] `docs/architecture/` existe com `overview.md`, `database-schema.md`, `api-reference.md`
- [ ] `docs/development/` existe com `best-practices.md`, `docker-deploy.md`, `contributing.md`
- [ ] `docs/features/` existe com documentação dos módulos
- [ ] `docs/archive/` existe com documentos históricos das fases
- [ ] `docs/README.md` é o índice único
- [ ] `project-docs/` não existe mais no repositório
- [ ] `docs/BEST_PRACTICES.MD` não existe
- [ ] `docs/MELHORES_PRATICAS_SGAITI.md` não existe
- [ ] `docs/development/contributing.md` contém seção de comandos Sail
- [ ] README.md raiz tem seção de Documentação com links corretos

## Commit Esperado

```
docs: consolida documentacao em estrutura unica e remove duplicatas

- cria estrutura docs/architecture, docs/development, docs/features, docs/archive
- mescla best-practices duplicados em docs/development/best-practices.md
- mescla database-schema e analysis em docs/architecture/database-schema.md
- cria docs/development/contributing.md com comandos sail e guia completo
- move documentos de fase para docs/archive/
- remove project-docs/ (migrado para docs/features/)
- atualiza README.md com links para nova estrutura
```

## NÃO FAZER

- ❌ Não excluir nenhum arquivo sem antes verificar se o conteúdo foi migrado
- ❌ Não alterar nada em `app/`, `routes/`, `resources/`
- ❌ Não alterar `.aidev/rules/` (já atualizado na Fase 0)
- ❌ Não alterar `docs/GUIA_ECONOMIA_TOKENS.md` (não listado para modificação)
- ❌ Não rodar comandos Sail desnecessários nesta fase — é apenas reorganização de arquivos
