# 📋 PLANO DE IMPLEMENTAÇÃO: Sistema de Lições Aprendidas

> **Status**: Aprovado e pronto para implementação  
> **Destino**: `.aidev/memory/kb/` (pasta oficial AI Dev Superpowers)  
> **Decisões**: project-docs/ será removido após migração (fonte única em .aidev/)

---

## 🎯 **Objetivo**

Consolidar todas as lições dispersas em `project-docs/lessons-learned/` para a pasta oficial `.aidev/memory/kb/` seguindo o formato padronizado da skill `learned-lesson`, com integração MCP e automação.

---

## 📊 **Inventário Atual**

**Total**: 13 lições documentadas em `project-docs/lessons-learned/`

| Categoria | Quantidade |
|-----------|------------|
| **Bug** | 5 |
| **Config** | 8 |
| **Performance** | 2 |
| **Security** | 1 |
| **Architecture** | 3 |

### 📁 Lições Identificadas:

1. `2025-11-06-sgti-gac-system-updates.md` - Renomeação SGAITI-UM → SGTI-GAC
2. `2025-11-06-uncatalogued-items-fix.md` - Itens não catalogados em branco
3. `2025-11-06-mysql-docker-connection.md` - Erro conexão MySQL Docker
4. `comando-aeronautica-fix.md` - Módulo Comando Aeronáutica
5. `conectividade-projeto.md` - Problemas de conectividade
6. `erros-comuns.md` - Erros comuns gerais
7. `erros-comuns-docker-laravel.md` - Docker + Laravel
8. `inventory-crud-improvements.md` - CRUD Inventário
9. `javascript-errors-inertia.md` - Erros JS/Inertia.js
10. `laravel-permissions-fix.md` - Permissões Laravel
11. `laravel-quick-fixes.md` - Fixes rápidos Laravel
12. `vite-cache-issues.md` - Cache Vite
13. `workflow-docker-development.md` - Workflow Docker

---

## 🏗️ **FASE 1: Estrutura de Pastas**

### Estrutura Alvo

```
.aidev/memory/kb/
├── 2025-11-06-sgti-gac-rename.md
├── 2025-11-06-uncatalogued-items-accessor.md
├── 2025-11-06-mysql-docker-host-config.md
├── 2024-XX-XX-comando-aeronautica-fix.md
├── 2024-XX-XX-project-connectivity.md
├── 2024-XX-XX-common-errors-guide.md
├── 2024-XX-XX-docker-laravel-common-errors.md
├── 2024-XX-XX-inventory-crud-optimization.md
├── 2024-XX-XX-inertia-js-defensive-programming.md
├── 2024-XX-XX-laravel-storage-permissions.md
├── 2024-XX-XX-laravel-quick-fixes-collection.md
├── 2024-XX-XX-vite-cache-clear.md
└── 2024-XX-XX-docker-development-workflow.md

.aidev/memory/kb/.index/
└── lessons-index.json (busca rápida)

docs/licoes-aprendidas/
└── (symlinks para .aidev/memory/kb/ ou referências)
```

---

## 📝 **FASE 2: Template Padrão Obrigatório**

Cada lição migrada seguirá este formato:

```markdown
# Lição: [Título Curto Descritivo]

**Data**: YYYY-MM-DD  
**Categoria**: [bug|config|performance|security|architecture|integration|deployment]  
**Stack**: [Laravel 12, PHP 8.4, React 18, Docker, MySQL 8.0, etc]  
**Severity**: [Crítico|Alto|Médio|Baixo]  
**Origem**: [project-docs/lessons-learned/NOME-ORIGINAL.md]

---

## Contexto

**Ambiente**: [Produção|Desenvolvimento|Docker|CI/CD]  
**Frequência**: [Intermitente|Sempre|Rara]  
**Impacto**: [Crítico|Alto|Médio|Baixo]

### Sintoma Observado
[Descrição detalhada do erro/comportamento errado]

### Comportamento Esperado
[O que deveria acontecer]

### Evidência
```[linguagem]
[Stack trace, log, código de erro]
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** [Resposta]
2. **Por que?** [Resposta]
3. **Por que?** [Resposta]
4. **Por que?** [Resposta]
5. **Por que?** [Causa raiz técnica]

### Tipo de Problema
- [x] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```[linguagem]
// Código completo que resolveu o problema
```

### Por Que Funciona
[Explicação técnica detalhada]

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| [Opção 1] | [Razão] |
| [Opção 2] | [Razão] |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `[comando]`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] [Item verificável 1]
- [ ] [Item verificável 2]
- [ ] [Item verificável 3]

### Regras de Ouro
1. [Regra prática aplicável]
2. [Regra prática aplicável]

---

## Referências

- **Arquivo Original**: [project-docs/lessons-learned/...]
- **Commit/PR**: [hash ou link se houver]
- **Documentação**: [link para docs relevantes]

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
```

---

## ⚙️ **FASE 3: Integração MCP**

### 3.1 Configuração Sincronização

**Arquivo**: `.aidev/mcp/memory-sync.json`

```json
{
  "sync_on_lesson_complete": true,
  "mcp_servers": ["basic-memory", "context7"],
  "auto_index": true,
  "index_path": ".aidev/memory/kb/.index/lessons-index.json",
  "backup_before_sync": true,
  "cross_project_sync": {
    "enabled": true,
    "filter_tags": ["laravel", "docker", "react", "inertia"]
  }
}
```

### 3.2 Comandos MCP Disponíveis

| Ação | Comando MCP | Quando Usar |
|------|-------------|-------------|
| **Salvar lição** | `mcp__basic-memory__write_note` | Após completar Step 4 |
| **Buscar similar** | `mcp__basic-memory__search_notes` | Antes de documentar nova lição |
| **Taggar** | `mcp__basic-memory__tag_note` | Categorizar a lição |
| **Contexto código** | `mcp__context7__query-docs` | Buscar docs técnicas |

### 3.3 Fluxo de Sincronização

```
Completação da Lição (Step 4)
           │
           ▼
    ┌──────────────┐
    │  Salvar KB   │ → .aidev/memory/kb/YYYY-MM-DD-topic.md
    └──────────────┘
           │
           ▼
    ┌──────────────┐
    │ Indexar JSON │ → .aidev/memory/kb/.index/lessons-index.json
    └──────────────┘
           │
           ▼
    ┌──────────────┐
    │ MCP Sync     │ → mcp__basic-memory__write_note
    └──────────────┘
           │
           ▼
    ┌──────────────┐
    │ Tag/Categor. │ → mcp__basic-memory__tag_note
    └──────────────┘
```

---

## 🤖 **FASE 4: Automação e Triggers**

### 4.1 Detecção Automática (Triggers YAML)

**Arquivo**: `.aidev/triggers/lesson-capture.yaml`

```yaml
triggers:
  # Trigger 1: Padrões de erro crítico
  - id: error-pattern-critical
    type: error_pattern
    patterns:
      - "SQLSTATE\\[.*\\]"
      - "Exception|Error|Failed"
      - "TypeError.*undefined"
      - "Connection refused"
    severity: high
    action: suggest_learned_lesson
    message: "Detectei um erro crítico. Deseja ativar a skill learned-lesson para documentar?"

  # Trigger 2: Sucesso após debug
  - id: debug-success-keywords
    type: user_intent
    keywords:
      - "corrigimos"
      - "funciona agora"
      - "resolvido"
      - "bug fix"
      - "aprendi"
      - "lição"
      - "memorizar"
    action: activate_learned_lesson_skill
    auto_suggest: true

  # Trigger 3: Finalização de feature complexa
  - id: complex-feature-complete
    type: workflow_state
    state: "feature_complete"
    conditions:
      - steps > 3
      - duration > 30min
    action: prompt_lesson_capture
    message: "Feature complexa completada. Documentar aprendizados?"

  # Trigger 4: Refatoração significativa
  - id: major-refactor
    type: file_change
    patterns:
      - "*.php" # >10 arquivos alterados
      - "*.tsx" # >5 arquivos alterados
    threshold: 10
    action: suggest_lesson
```

### 4.2 Busca Automática de Lições Similares

**Quando um erro ocorre:**

```
Erro detectado: "SQLSTATE[HY000] [2002] Connection refused"

IA automaticamente:
1. Extrai keywords: ["SQLSTATE", "Connection refused", "MySQL"]
2. Busca em .aidev/memory/kb/.index/lessons-index.json
3. Encontra match: 2025-11-06-mysql-docker-host-config.md
4. Sugere: "Encontrei uma lição similar! Deseja consultar?"
```

---

## 🔄 **FASE 5: Fluxo Completo de Aquisição**

### Fluxo 1: Erro Detectado → Documentação

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. ERRO OCORRE                                                   │
│    Ex: "SQLSTATE[HY000] [2002] Connection refused"              │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│ 2. TRIGGER ATIVADO (se habilitado)                              │
│    Padrão detectado → Sugere ativar learned-lesson              │
│    Ou: User diz "aprendi", "corrigimos", "licao"                │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│ 3. SKILL LEARNED-LESSON ATIVADA (4 Steps)                       │
│                                                                  │
│    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │
│    │ Step 1       │→│ Step 2       │→│ Step 3       │→│ ...   │
│    │ Contexto     │  │ Causa Raiz   │  │ Solução      │        │
│    └──────────────┘  └──────────────┘  └──────────────┘        │
│         │                  │                  │                 │
│    CHECKPOINT          CHECKPOINT          CHECKPOINT           │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│ 4. ARMAZENAMENTO (Step 4)                                       │
│    ├─ Salvar: .aidev/memory/kb/YYYY-MM-DD-<topic>.md           │
│    ├─ Indexar: .aidev/memory/kb/.index/lessons-index.json      │
│    ├─ Sync MCP: mcp__basic-memory__write_note                   │
│    └─ Tag: mcp__basic-memory__tag_note                          │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│ 5. CONSULTA FUTURA                                               │
│    Erro similar → Busca automática → Sugere solução             │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📅 **CRONOGRAMA DE IMPLEMENTAÇÃO**

### **Etapa 1: Preparação** (30 min)
- [ ] Criar estrutura de pastas `.aidev/memory/kb/`
- [ ] Criar pasta `.aidev/memory/kb/.index/`
- [ ] Definir template padrão em `.aidev/skills/learned-lesson/TEMPLATE.md`

### **Etapa 2: Migração** (2-3 horas)
- [ ] Migrar 13 lições para formato padrão
- [ ] Renomear arquivos: `YYYY-MM-DD-slug-do-tema.md`
- [ ] Criar índice `.aidev/memory/kb/.index/lessons-index.json`

### **Etapa 3: Configuração MCP** (1 hora)
- [ ] Criar `.aidev/mcp/memory-sync.json`
- [ ] Testar integração `mcp__basic-memory__write_note`
- [ ] Testar busca `mcp__basic-memory__search_notes`

### **Etapa 4: Automação** (1-2 horas)
- [ ] Criar `.aidev/triggers/lesson-capture.yaml`
- [ ] Implementar detecção de padrões de erro
- [ ] Testar triggers com palavras-chave

### **Etapa 5: Validação** (30 min)
- [ ] Simular erro e verificar busca automática
- [ ] Testar fluxo completo: erro → documentação → consulta
- [ ] Commit de todas as alterações

---

## 🎯 **CHECKLIST DE SUCESSO**

- [ ] Todas as 13 lições migradas para `.aidev/memory/kb/`
- [ ] Formato padronizado aplicado em 100% das lições
- [ ] Índice JSON criado e funcional
- [ ] MCP basic-memory sincronizando automaticamente
- [ ] Triggers YAML funcionando (detecção de erros)
- [ ] Busca automática de lições similares operacional
- [ ] Template disponível para novas lições
- [ ] Documentação de uso criada
- [ ] `project-docs/lessons-learned/` removido (fonte única)

---

## 🗂️ **Decisões de Implementação**

| Decisão | Escolha |
|---------|---------|
| **Destino das lições** | `.aidev/memory/kb/` (oficial AI Dev) |
| **project-docs/lessons-learned/** | **Remover após migração** |
| **Fonte de verdade** | Única: `.aidev/memory/kb/` |
| **Datas sem data específica** | A definir durante migração |
| **Integração MCP** | A definir durante implementação |

---

## 📝 **Notas de Implementação**

### Datas a Serem Definidas:
- Lições sem data: usar data estimada baseada no contexto ou data da migração
- Preferência: datas estimadas quando possível identificar

### Integração MCP:
- Opção A: Só sincronização local (mais simples)
- Opção B: Completa com basic-memory global (mais poderoso)
- Decisão durante Etapa 3

### Backup:
- Manter backup de `project-docs/lessons-learned/` em `.aidev/backups/` antes de remover

---

*Plano gerado em: 2026-02-05*  
*Versão do Sistema: AI Dev Superpowers v3.6*  
*Status: ✅ Aprovado e pronto para implementação*
