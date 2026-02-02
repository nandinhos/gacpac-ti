# Plano de Organização da Estrutura do Projeto

**Data**: 2026-02-01
**Status**: Planejamento
**Projeto**: gacpac-ti (Refatoração React → Laravel TALL Stack)

---

## 1. Diagnóstico Atual

### 1.1 Estrutura de Diretórios (Raiz)

| Diretório/Arquivo | Status | Ação Recomendada |
|-------------------|--------|------------------|
| `backend/` | ✅ Ativo | Manter - Código Laravel atual |
| `project-docs/` | ✅ Ativo | Manter - Documentação consolidada |
| `docs/` | ⚠️ Legado | Arquivar/Deletar após consolidação |
| `vendor/` | ⚠️ Legado | **DELETAR** - Resquício antigo |
| `public/` | ⚠️ Legado | Avaliar necessidade |
| `.aidev/` | ✅ Ativo | Manter - Config AI Dev |
| `.serena/` | ✅ Ativo | Manter - Config Serena |
| `.gemini/` | ✅ Ativo | Manter - Config Gemini |
| `.claude/` | ✅ Ativo | Manter - Config Claude |
| `scripts/` | ✅ Ativo | Manter |

### 1.2 Código Legado no Backend

| Tipo | Quantidade | Localização | Status |
|------|------------|-------------|--------|
| Arquivos React/JSX | 54 | `backend/resources/js/` | **LEGADO - Marcar para deleção** |
| Views Livewire | 17 | `backend/resources/views/livewire/` | ✅ Ativo |
| Componentes Livewire | 7 | `backend/app/Livewire/` | ✅ Ativo |

### 1.3 Documentação

| Origem | Destino | Status |
|--------|---------|--------|
| `docs/DATABASE_SCHEMA.md` (545 linhas) | `project-docs/tech-reference/` (388 linhas) | ⚠️ **Versão antiga mais completa** |
| `docs/API_REFERENCE.md` | Não consolidado | ⚠️ Pendente |
| `docs/BEST_PRACTICES.MD` | `project-docs/guides/` | Verificar |
| `docs/MELHORES_PRATICAS_SGAITI.md` | `project-docs/guides/` | Verificar |
| `docs/DOCKER_DEPLOY.md` | Não consolidado | ⚠️ Pendente |
| `docs/guias-programacao/` | Vazio | Deletar |
| `docs/licoes-aprendidas/` | Vazio | Deletar |
| `docs/melhores-praticas/` | Vazio | Deletar |
| `docs/memorias/` | Vazio | Deletar |
| `docs/referencia-tecnica/` | Vazio | Deletar |

---

## 2. Problemas de Permissões Docker

### 2.1 Inconsistências Identificadas

```
Dockerfile:
  WORKDIR /app  ← INCORRETO
  Permissões para /app ← INCORRETO

docker-compose.yml:
  Volume: ./backend:/var/www/html ← CORRETO

nginx.conf:
  root /var/www/html/public ← CORRETO
```

### 2.2 Correção Necessária

O Dockerfile precisa usar `/var/www/html` como WORKDIR.

---

## 3. Plano de Ação

### Fase 1: Correção de Infraestrutura (Prioridade Alta)

#### 1.1 Corrigir Dockerfile
- [ ] Alterar WORKDIR de `/app` para `/var/www/html`
- [ ] Atualizar caminhos de permissões
- [ ] Testar build do container

#### 1.2 Validar Permissões
- [ ] Verificar WWWUSER/WWWGROUP sincronizados
- [ ] Testar acesso a storage/logs
- [ ] Testar escrita em cache

### Fase 2: Limpeza de Diretórios (Prioridade Média)

#### 2.1 Remover Pasta `vendor/` da Raiz
```bash
rm -rf /home/nandodev/projects/gacpac-ti/vendor/
```

#### 2.2 Avaliar Pasta `public/` da Raiz
- Contém apenas `favicon.svg`
- Pode ser movido para `backend/public/` se necessário

#### 2.3 Criar Pasta de Legado para Arquivamento
```bash
mkdir -p /home/nandodev/projects/gacpac-ti/_legacy
mv /home/nandodev/projects/gacpac-ti/docs /home/nandodev/projects/gacpac-ti/_legacy/docs-old
```

### Fase 3: Consolidação de Documentação

#### 3.1 Documentos a Consolidar

| Arquivo Original | Ação | Destino |
|------------------|------|---------|
| `docs/DATABASE_SCHEMA.md` | Substituir versão atual | `project-docs/tech-reference/DATABASE_SCHEMA.md` |
| `docs/API_REFERENCE.md` | Copiar | `project-docs/tech-reference/API_REFERENCE.md` |
| `docs/DOCKER_DEPLOY.md` | Copiar | `project-docs/tech-reference/DOCKER_DEPLOY.md` |
| `docs/MELHORES_PRATICAS_SGAITI.md` | Copiar se não existe | `project-docs/guides/` |
| `docs/GUIA_ECONOMIA_TOKENS.md` | Copiar | `project-docs/guides/` |

#### 3.2 Documentos Legado (para `project-docs/legacy/`)

| Arquivo | Motivo |
|---------|--------|
| `docs/BACKEND_FRONTEND_SYNC.md` | Específico do Inertia |
| `docs/IMPLEMENTATION_WATCHER.md` | Específico do Inertia |
| `docs/MODAL_CONFIRMATION_SYSTEM.md` | Específico do React |
| `docs/SISTEMA_PDF_CAUTELA_ASSINADA.md` | Pode ser relevante |

### Fase 4: Arquivamento do Código React/Inertia

#### 4.1 Mover para Legado (NÃO deletar ainda)
```bash
mkdir -p /home/nandodev/projects/gacpac-ti/_legacy/inertia-react
mv /home/nandodev/projects/gacpac-ti/backend/resources/js /home/nandodev/projects/gacpac-ti/_legacy/inertia-react/
```

#### 4.2 Criar Nova Estrutura JS Mínima
O Livewire + Alpine não requer a estrutura React, apenas:
```
backend/resources/js/
├── app.js          (Alpine init)
└── bootstrap.js    (Axios, etc)
```

### Fase 5: Atualização de Referências

#### 5.1 Arquivos a Atualizar
- [ ] `README.md` - Atualizar estrutura
- [ ] `project-docs/INDEX.md` - Adicionar novos docs
- [ ] `.gitignore` - Adicionar `_legacy/`

---

## 4. Estrutura Final Proposta

```
gacpac-ti/
├── .aidev/                 # Config AI Dev Superpowers
├── .claude/                # Config Claude
├── .gemini/                # Config Gemini
├── .serena/                # Config Serena
├── backend/                # Laravel TALL Stack
│   ├── app/
│   │   └── Livewire/       # Componentes Livewire
│   ├── resources/
│   │   ├── js/             # Alpine.js (mínimo)
│   │   └── views/
│   │       └── livewire/   # Views Livewire
│   └── ...
├── project-docs/           # Documentação Consolidada
│   ├── INDEX.md
│   ├── agents/
│   ├── guides/
│   ├── legacy/             # Docs do sistema antigo
│   ├── lessons-learned/
│   ├── migration/
│   ├── plans/
│   └── tech-reference/
├── scripts/                # Scripts utilitários
├── _legacy/                # Código arquivado (NÃO commitar)
│   ├── docs-old/
│   └── inertia-react/
├── .env
├── .env.example
├── .gitignore
├── docker-compose.yml
└── README.md
```

---

## 5. Checklist de Validação

### Antes de Deletar Qualquer Arquivo

- [ ] Backup feito ou commitado
- [ ] Documentação consolidada em `project-docs/`
- [ ] Nenhuma referência ativa ao arquivo
- [ ] Testes passando

### Após Cada Fase

- [ ] `docker-compose up -d` funciona
- [ ] Aplicação carrega no browser
- [ ] Testes passam: `php artisan test`
- [ ] Nenhum erro no log

---

## 6. Próximos Passos Imediatos

1. **Aprovar este plano**
2. **Executar Fase 1** - Corrigir Dockerfile
3. **Executar Fase 2** - Limpeza de diretórios
4. **Executar Fase 3** - Consolidar documentação
5. **Commit intermediário** com mensagem clara

---

*Plano criado por AI Dev Superpowers - Orchestrator Agent*
