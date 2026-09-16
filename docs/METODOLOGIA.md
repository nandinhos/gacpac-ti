---
title: METODOLOGIA
type: note
permalink: gacpac-ti/docs/metodologia
---

# Metodologia de Desenvolvimento — SGTI-GAC

> Como trabalhamos neste projeto. Leitura obrigatória antes do primeiro commit.

## Filosofia

1. **Escopo antes de código.** Nenhuma implementação começa sem contrato claro do que entra e do que NÃO entra.
2. **Evidência antes de afirmação.** Nada se declara pronto sem teste verde e verificação manual observada.
3. **Pequenos passos rastreáveis.** Commits atômicos, em português, que contam a história do projeto.
4. **Não quebre o verde.** A suíte de testes é o contrato coletivo: vermelho é tarefa, nunca ruído.

## Pipeline (CEH clássico + ferramentas)

- **Branches:** trabalho em `dev` (ou `feature/*` a partir dela); `main` recebe apenas via promoção de `dev` testada e verde. Direto na `main`, só higiene/docs triviais.
- **AST-first:** antes de ler código em volume, consulte o grafo local (`graphify-out/graph.json`, 6 mil+ nós) com `graphify path|explain`; proibido despejar arquivos inteiros no contexto.
- **context-mode:** servidor MCP ativo (copie `.mcp.json.example` para `.mcp.json`, ver [INSTALL](../INSTALL.md)) — execuções sandbox e análises via script, não leitura massiva.

## Fluxo de trabalho (DEVORQ)

Toda task segue o ciclo (detalhes e prompts em [QUICKSTART.md](../QUICKSTART.md)):

```
1. /scope-guard   → contrato de escopo (FAZER / NÃO FAZER / arquivos / done criteria)
2. implementar    → regra de negócio em app/Services, validação em FormRequest
3. /quality-gate  → checklist: testes verdes, Pint, sem N+1, escopo respeitado
4. commit + push  → padrão abaixo, arquivos nomeados (sem git add -A em árvore suja)
5. /session-audit → registrar resultado e lições ao fim da sessão
```

Com IA, ative o modo orquestrador no início da conversa conforme o [QUICKSTART](../QUICKSTART.md). As skills e regras do projeto vivem em `.devorq/`.

## Definition of Done

- [ ] Contrato de escopo cumprido, sem extras silenciosos
- [ ] `php artisan test` verde (151 testes — nenhum pulado, removido ou com assert reescrito)
- [ ] `composer audit` sem advisories novos
- [ ] `npm run build` executado, se mexeu em CSS/JS
- [ ] Verificação manual observada (rota/tela exercitada de verdade)
- [ ] Documentação atualizada, se mudou comportamento, rota ou setup

## Padrão de commits (obrigatório)

```
[feat|fix|refactor|docs|test|style|perf|chore] (escopo): Descrição em PT-BR
```

1ª linha até 72 caracteres, sem emoji, sem `Co-Authored-By`. Escopos: `database`, `models`, `services`, `livewire`, `notifications`, `tests`, `docs`, `api`, `config`, `security`, `devops`, `migrations`, `policies`, `actions`, `listeners`, `jobs`.

## Regras de código

| Camada | Regra |
|---|---|
| Entrada | Todo input externo passa por `FormRequest` (web e API) |
| Negócio | Regra de negócio em `app/Services`, nunca no controller/Livewire |
| Autorização | Via papéis Spatie + Policies; nada de `if role === 'x'` espalhado |
| Visual | Reutilizar `x-ui:*`; nada de markup duplicado entre telas |
| Banco | Migrations para todo cambio de schema; seeders para dados de base |
| Testes | Feature para fluxos, Unit para regras; `Storage::fake` em uploads |

## Papéis no projeto

- **admin** — acesso total · **operator** — operação diária · **auditor** — leitura e fiscalização · **viewer** — leitura básica
- Conta seed local: `admin@gac.pac.br` / `admin123` (trocar em qualquer ambiente exposto)

## Ordem de leitura para começar bem

1. [README.md](../README.md) — visão e quick start
2. [docs/MAPA.md](./MAPA.md) — grafo do projeto (módulos, papéis, ambientes)
3. [docs/ARCHITECTURE.md](./ARCHITECTURE.md) — camadas e fluxos
4. [docs/API.md](./API.md) — referência REST
5. [INSTALL.md](../INSTALL.md) — setup local