# Sistema de Lições Aprendidas - Guia de Uso

> Documentação do sistema de Lições Aprendidas do AI Dev Superpowers
> Versão: 1.0 | Projeto: gacpac-ti

---

## 📍 Estrutura do Sistema

```
.aidev/memory/kb/
├── .index/
│   └── lessons-index.json          # Índice para busca rápida
├── 2025-11-06-*.md                 # Lições documentadas (13 total)
└── [novas-licoes].md               # Novas lições seguem padrão

.aidev/skills/learned-lesson/
└── TEMPLATE.md                     # Template para novas lições

.aidev/mcp/
├── memory-sync.json                # Configuração MCP
└── README.md                       # Documentação MCP
```

---

## 🚀 Como Usar

### 1. Consultar Lições Existentes

**Buscar por categoria:**
```bash
# Ver todas as lições de configuração
grep -l "Categoria.*config" .aidev/memory/kb/*.md

# Ver todas as lições de bug
grep -l "Categoria.*bug" .aidev/memory/kb/*.md
```

**Buscar por palavra-chave:**
```bash
# Buscar lições sobre Docker
grep -r "docker" .aidev/memory/kb/*.md | head -10

# Buscar lições sobre erros específicos
grep -r "SQLSTATE" .aidev/memory/kb/*.md
```

**Usar o índice JSON:**
```bash
# Ver todas as lições de bug
cat .aidev/memory/kb/.index/lessons-index.json | grep -A 5 '"category": "bug"'
```

---

### 2. Criar Nova Lição

**Passo 1: Copiar template**
```bash
cp .aidev/skills/learned-lesson/TEMPLATE.md \
   .aidev/memory/kb/YYYY-MM-DD-nome-da-licao.md
```

**Passo 2: Preencher dados**
- **Data**: YYYY-MM-DD
- **Categoria**: bug | config | performance | security | architecture
- **Stack**: Laravel, PHP, React, Docker, etc.
- **Severity**: Crítico | Alto | Médio | Baixo

**Passo 3: Seguir estrutura**
```markdown
# Lição: [Título]
**Data**: 2025-11-06
**Categoria**: bug
**Stack**: Laravel 12, PHP 8.4
**Severity**: Alto

## Contexto
## Causa Raiz
## Solução
## Prevenção
```

**Passo 4: Atualizar índice**
```bash
# Adicionar entrada ao lessons-index.json
# Ver FASE 6: Automação para atualização automática
```

---

### 3. Fluxo Completo de Documentação

```
1. ERRO OCORRE
   ↓
2. BUSCAR LICAO SIMILAR
   grep -r "erro-especifico" .aidev/memory/kb/
   ↓
3. SE NAO ENCONTRAR → DOCUMENTAR
   - Copiar template
   - Preencher dados
   - Salvar em .aidev/memory/kb/
   ↓
4. ATUALIZAR INDICE
   - Adicionar entrada ao lessons-index.json
   ↓
5. COMMIT
   git add .aidev/memory/kb/
   git commit -m "docs(aidev): adiciona licao sobre [tema]"
```

---

## 📊 Inventário Atual

| Categoria | Quantidade | Arquivos |
|-----------|------------|----------|
| **Bug** | 5 | acessor, rota, defensive-programming, quick-fixes |
| **Config** | 6 | rename, mysql-docker, connectivity, storage-perms, vite-cache, docker-errors |
| **Architecture** | 2 | common-errors, inventory-crud, workflow |

**Total**: 13 lições documentadas

---

## 🔍 Exemplos de Busca

**Erro de conexão MySQL:**
```bash
cat .aidev/memory/kb/2025-11-06-mysql-docker-host-config.md
```

**Problemas com Docker:**
```bash
ls .aidev/memory/kb/ | grep docker
# Resultado:
# 2025-11-06-docker-development-workflow.md
# 2025-11-06-docker-laravel-common-errors.md
# 2025-11-06-mysql-docker-host-config.md
# 2025-11-06-project-connectivity.md
```

**Configurações gerais:**
```bash
grep -l "Categoria.*config" .aidev/memory/kb/*.md
```

---

## ✅ Checklist de Qualidade

Antes de salvar uma nova lição:

- [ ] Título descritivo e curto
- [ ] Data no formato YYYY-MM-DD
- [ ] Categoria correta (bug/config/performance/security/architecture)
- [ ] Stack listada corretamente
- [ ] Severity definida (Crítico/Alto/Médio/Baixo)
- [ ] Contexto com sintoma observado
- [ ] Causa raiz com 5 Whys
- [ ] Solução com código funcional
- [ ] Prevenção com regras de ouro
- [ ] Referências ao arquivo original (se migração)

---

## 🔄 Integração MCP

**Configuração:**
- Arquivo: `.aidev/mcp/memory-sync.json`
- Servidores: `basic-memory`, `context7`
- Sincronização: Automática ao completar lição

**Quando estiver disponível (FASE 4):**
```bash
# Sincronizar todas as lições
aidev mcp sync --all

# Buscar lições similares
aidev mcp search --type=lesson --query="docker"
```

---

## 📚 Referências

- **Template**: `.aidev/skills/learned-lesson/TEMPLATE.md`
- **Índice**: `.aidev/memory/kb/.index/lessons-index.json`
- **Configuração MCP**: `.aidev/mcp/memory-sync.json`
- **Plano Original**: `.aidev/plans/PLANO_IMPLEMENTACAO.md`

---

## 🎯 Próximos Passos

1. **FASE 6 (Upgrade Nativo)**: Automação com triggers YAML
2. **Busca Inteligente**: Integração com MCP basic-memory
3. **Cross-Project**: Compartilhar lições entre projetos

---

**Criado em:** 2026-02-05  
**Versão:** 1.0  
**Status:** ✅ Operacional
