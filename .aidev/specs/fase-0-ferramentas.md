# SPEC — FASE 0: Ferramentas de Desenvolvimento
**Status:** `[ ] Pendente`
**Ambiente:** Laravel Sail (Docker) — todos os comandos PHP/Artisan/Composer rodam dentro do container.
**Checkpoint:** `./vendor/bin/sail artisan boost:mcp --help` lista 15 ferramentas MCP sem erros.

---

## Contexto

Laravel Boost (`laravel/boost ^1.7`) está listado em `require-dev` do `composer.json` mas **nunca foi instalado/configurado**. Não existem os arquivos `boost.json` nem `.mcp.json` no projeto. O Context7 possui API Key em `.env` (linha 64: `CONTEXT7_API_KEY=...`) mas sem padrão de uso documentado nas regras `.aidev`.

Laravel Boost é um **MCP Server** com 15 ferramentas para AI-assisted development:
- Database Query, Database Schema, List Routes, Last Error, Read Log Entries, Tinker, Search Docs, etc.

> [!IMPORTANT]
> O `boost:install` é um comando interativo. Execute-o com o container rodando via Sail.
> O registro do MCP no Gemini (`gemini mcp add`) é feito na máquina HOST, não dentro do container.

---

## Arquivos Afetados

| Arquivo | Tipo | Ação |
|---|---|---|
| `composer.json` | MODIFY | Adicionar `boost:update` ao `post-update-cmd` |
| `.aidev/rules/generic.md` | MODIFY | Documentar padrão de uso das ferramentas |
| `boost.json` | NEW (gerado) | Configuração do Boost — gerado pelo artisan |
| `.mcp.json` | NEW (gerado) | Configuração MCP — gerado pelo artisan |
| `.ai/` | NEW (gerado) | Guidelines — gerado pelo artisan |

---

## Pré-requisito: Sail rodando

```bash
# Na máquina host — verificar se os containers estão ativos
./vendor/bin/sail up -d

# Confirmar
./vendor/bin/sail ps
```

---

## Ações Exatas

### Passo 1 — Instalar o Laravel Boost (dentro do container via Sail)

```bash
./vendor/bin/sail artisan boost:install
```

O comando perguntará qual IDE/agente configurar. Selecionar **Gemini**.
Isso gera automaticamente:
- `boost.json`
- `.mcp.json` (ou equivalente para Gemini)
- `.ai/guidelines/` com guidelines do Laravel, Livewire, PHPUnit, Pint, TailwindCSS

### Passo 2 — Registrar no Gemini (na máquina HOST — não dentro do container)

Este comando é executado **fora** do container, na máquina host:

```bash
gemini mcp add -s project -t stdio laravel-boost php artisan boost:mcp
```

> O Gemini MCP server chama `php artisan boost:mcp` que precisa ter PHP disponível no PATH do host, OU ajustar o comando para usar Sail. Se PHP não estiver disponível no host, usar:
> ```bash
> gemini mcp add -s project -t stdio laravel-boost ./vendor/bin/sail artisan boost:mcp
> ```

### Passo 3 — Atualizar guidelines (dentro do container via Sail)

```bash
./vendor/bin/sail artisan boost:update
```

### Passo 4 — Modificar `composer.json`

Localizar o bloco `post-update-cmd` (linha ~65) e adicionar a linha do boost:update:

```diff
 "post-update-cmd": [
-    "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
+    "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
+    "@php artisan boost:update --ansi"
 ],
```

### Passo 5 — Documentar padrão de uso em `.aidev/rules/generic.md`

Adicionar ao final do arquivo:

```markdown
## Ferramentas de Desenvolvimento AI

### Laravel Boost (MCP Server)
- **Quando usar:** Durante qualquer sessão de desenvolvimento para inspecionar o estado real da aplicação.
- **Ambiente:** Requer Sail rodando (`./vendor/bin/sail up -d`)
- **Ferramentas chave:**
  - `list_routes` — listar todas as rotas registradas
  - `database_query` — executar queries SQL direto no banco
  - `database_schema` — inspecionar schema atual
  - `last_error` — ler último erro do log
  - `tinker` — executar código PHP no contexto da app
  - `search_docs` — buscar documentação Laravel/Livewire/PHPUnit
- **Atualizar guidelines:** `./vendor/bin/sail artisan boost:update` (após `composer update`)

### Context7 (Documentação Externa)
- **Quando usar:** ANTES de qualquer implementação envolvendo biblioteca ou framework externo.
- **Fluxo obrigatório:**
  1. Chamar `resolve-library-id` com o nome da biblioteca
  2. Chamar `query-docs` com o library ID e a dúvida específica
  3. Basear a implementação na documentação retornada
- **Não substituir por:** suposições ou conhecimento de treinamento desatualizado.
```

---

## Critérios de Aceite

- [ ] `./vendor/bin/sail artisan boost:mcp --help` exibe lista de comandos sem erros
- [ ] `./vendor/bin/sail artisan boost:update` executa e retorna sucesso
- [ ] Arquivo `boost.json` existe na raiz do projeto
- [ ] `.aidev/rules/generic.md` contém seção "Ferramentas de Desenvolvimento AI"
- [ ] `composer.json` contém `boost:update` no `post-update-cmd`

## Commit Esperado

```
chore(tooling): instala e configura laravel boost como mcp server

- executa boost:install para gemini via sail
- adiciona boost:update ao post-update-cmd
- documenta padrao de uso em .aidev/rules/generic.md
```

## NÃO FAZER

- ❌ Não criar arquivos de configuração manualmente (usar `./vendor/bin/sail artisan boost:install`)
- ❌ Não rodar `php artisan` diretamente na máquina host (usar `./vendor/bin/sail artisan`)
- ❌ Não alterar nada em `app/`, `routes/`, `resources/` nesta fase
- ❌ Não modificar `Dockerfile` nesta fase
