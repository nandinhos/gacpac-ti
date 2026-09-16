---
title: CONTRIBUTING
type: note
permalink: gacpac-ti/contributing
---

# Contribuindo — SGTI-GAC

## Ambiente

Todo desenvolvimento usa **Laravel Sail** — não instale PHP/Node no host. Suba com `docker compose up -d` (ver [INSTALL.md](./INSTALL.md)).

## Branches (CEH clássico)

- `dev` — trabalho diário e features (sincronizada com `origin/dev`)
- `main` — estável, recebe só promoção de `dev` verde; sincronizada com `origin/main`

## Padrão de commits (obrigatório)

```
[feat|fix|refactor|docs|test|style|perf|chore] (escopo): Descrição em PT-BR
```

- 1ª linha: máx 72 caracteres, mín 3 palavras, **português do Brasil**, sem emojis
- Sem `Co-Authored-By` ou rodapés de atribuição
- Escopos: `database`, `models`, `services`, `livewire`, `notifications`, `tests`, `docs`, `api`, `config`, `security`, `devops`, `migrations`, `policies`, `actions`, `listeners`, `jobs`

Ex.: `fix (custody): valida devolução sem termo assinado`

## Antes de commitar

1. `docker compose exec laravel.test php artisan test` — tudo verde (151 testes)
2. `composer audit` — zero advisories novos
3. `npm run build` se mexeu em CSS/JS
4. Nomear **só os arquivos alterados** no commit (sem `git add -A` em árvore suja)

## Rotina do grafo AST

Após refactors grandes (mover/deletar classes, trocar dependências entre módulos):

```bash
graphify update .
```

Isso mantém `graphify-out/` fiel ao código (ignorado no Git, uso local). Sem isso, o AST-first consulta um mapa velho.

## Regras de código

- Regra de negócio em `app/Services`, nunca direto no controller/Livewire
- Toda entrada externa passa por `FormRequest`
- Componentes visuais reutilizáveis em `resources/views/components/ui`
- Testes: nunca pular, remover ou reescrever assert para "fazer passar" — teste vermelho é requisito
- Nunca commitar segredos (`.env` é ignorado), uploads reais ou `storage/framework/views` compiladas