# 🗺️ ROADMAP DE IMPLEMENTAÇÃO - SGAITI

> Documento mestre de planejamento de funcionalidades
> Formato: AI Dev Superpowers Sprint Planning
> Última atualização: 2026-02-05
> Status: Ativo

---

## 📋 VISÃO GERAL

Este documento serve como **fonte única de verdade** para implementação de funcionalidades pendentes no sistema SGAITI. Ele permite:
- ✅ Continuidade entre sessões de desenvolvimento
- ✅ Troca de LLM sem perda de contexto
- ✅ Cache/build do sistema sem perder diretrizes
- ✅ Implementação gradual por sprints
- ✅ Rastreabilidade de decisões

---

## 🎯 SPRINTS PLANEJADOS

### 📅 SPRINT 1: Funcionalidades Críticas
**Duração:** 1-2 semanas  
**Objetivo:** Implementar bloqueios críticos  
**Status:** 🟡 Não iniciado

#### Funcionalidades:

##### 1.1 - Criar Novo Inventário
**Prioridade:** 🔴 CRÍTICA  
**Motivo:** Botão existe mas está desabilitado (href="#")  

**Requisitos de Negócio:**
- Permitir criar inventário físico de ativos por setor
- Definir comissão de inventariantes
- Registrar data de início
- Status inicial: "Em Andamento"

**Requisitos Técnicos:**
- [ ] Criar rota POST /inventory
- [ ] Criar componente Livewire Inventory/Create
- [ ] Formulário com:
  - Select de Setor (obrigatório)
  - Número da Comissão (opcional, unique)
  - Data de Início (default: hoje)
  - Select de Responsáveis (múltiplos militares)
- [ ] Validação: apenas um inventário "Em Andamento" por setor
- [ ] Testes: InventoryCreateTest

**Critérios de Aceitação:**
1. Usuário consegue clicar "Novo Inventário" e abrir formulário
2. Formulário valida campos obrigatórios
3. Ao salvar, cria inventário com status "Em Andamento"
4. Redireciona para página de execução do inventário
5. Não permite criar inventário se já existe um em andamento para o setor

**Arquivos Esperados:**
```
backend/app/Livewire/Inventory/Create.php
backend/resources/views/livewire/inventory/create.blade.php
backend/tests/Feature/InventoryCreateTest.php
```

**Dependências:** Nenhuma (base para outras features)

---

### 📅 SPRINT 2: Gestão de Categorias
**Duração:** 1 semana  
**Objetivo:** Implementar CRUD completo de categorias  
**Status:** 🟡 Não iniciado

#### Funcionalidades:

##### 2.1 - CRUD Categorias
**Prioridade:** 🟡 MÉDIA  
**Motivo:** Módulo existe mas views estão vazias

**Requisitos de Negócio:**
- Permitir categorização de ativos (Ex: Eletrônicos, Móveis, Veículos)
- Hierarquia de categorias (categoria pai/filho)
- Cores distintas para visualização

**Requisitos Técnicos:**
- [ ] Criar migration para tabela categories (se não existir)
- [ ] Model Category com relação parent/children
- [ ] Componente Livewire Category/Index (listagem)
- [ ] Componente Livewire Category/Create
- [ ] Componente Livewire Category/Edit
- [ ] Formulários com:
  - Nome (obrigatório, unique)
  - Descrição (opcional)
  - Categoria Pai (select, opcional, hierárquico)
  - Cor (color picker)
- [ ] Validação: não permitir categoria ser pai dela mesma
- [ ] Soft delete
- [ ] Testes: CategoryTest

**Critérios de Aceitação:**
1. Listagem mostra categorias em árvore hierárquica
2. Cria categoria com ou sem pai
3. Edita categoria existente
4. Exclui categoria (soft delete)
5. Validações impedem ciclos na hierarquia
6. Cores visíveis na listagem

**Arquivos Esperados:**
```
backend/app/Models/Category.php (se não existir)
backend/app/Livewire/Category/Index.php
backend/app/Livewire/Category/Create.php
backend/app/Livewire/Category/Edit.php
backend/resources/views/livewire/category/*.blade.php
backend/tests/Feature/CategoryTest.php
```

**Dependências:** Nenhuma

---

### 📅 SPRINT 3: Manutenção de Ativos
**Duração:** 1-2 semanas  
**Objetivo:** Implementar histórico de manutenções  
**Status:** 🟡 Não iniciado

#### Funcionalidades:

##### 3.1 - Registro de Manutenção
**Prioridade:** 🟡 MÉDIA  
**Motivo:** API existe, falta UI

**Requisitos de Negócio:**
- Registrar manutenções preventivas e corretivas
- Controle de custos
- Agendamento de próximas manutenções
- Histórico completo por ativo

**Requisitos Técnicos:**
- [ ] Verificar se tabela maintenance_logs existe
- [ ] Se não existir, criar migration:
  - asset_id (foreign key)
  - type (preventiva/corretiva)
  - description
  - cost (decimal)
  - maintenance_date
  - next_maintenance_date (opcional)
  - performed_by
  - notes
- [ ] Model MaintenanceLog com relação Asset
- [ ] Componente Livewire Asset/Maintenance/Index (histórico)
- [ ] Componente Livewire Asset/Maintenance/Create
- [ ] Adicionar aba "Manutenções" na tela de show do ativo
- [ ] Formulário com todos os campos
- [ ] Listagem com filtros por tipo e data
- [ ] Alerta para manutenções próximas (badge no dashboard)
- [ ] Testes: MaintenanceTest

**Critérios de Aceitação:**
1. Na tela do ativo, aba "Manutenções" mostra histórico
2. Consegue adicionar nova manutenção
3. Visualiza custo total de manutenções do ativo
4. Dashboard mostra alerta de manutenções próximas
5. Filtros funcionam corretamente

**Arquivos Esperados:**
```
backend/app/Models/MaintenanceLog.php
backend/app/Livewire/Asset/Maintenance/Index.php
backend/app/Livewire/Asset/Maintenance/Create.php
backend/resources/views/livewire/asset/maintenance/*.blade.php
backend/tests/Feature/MaintenanceTest.php
```

**Dependências:** Módulo de Ativos (já existe)

---

### 📅 SPRINT 4: Fotos de Ativos
**Duração:** 1 semana  
**Objetivo:** Implementar upload e galeria de fotos  
**Status:** 🟡 Não iniciado

#### Funcionalidades:

##### 4.1 - Upload de Fotos
**Prioridade:** 🟡 MÉDIA  
**Motivo:** API existe, falta UI

**Requisitos de Negócio:**
- Anexar fotos do ativo (múltiplas)
- Visualizar galeria
- Definir foto principal
- Excluir fotos

**Requisitos Técnicos:**
- [ ] Verificar tabela asset_photos
- [ ] Componente Livewire Asset/Photos/Upload
- [ ] Componente Livewire Asset/Photos/Gallery
- [ ] Upload múltiplo com Livewire
- [ ] Validação: max 5MB, tipos: jpg, png, webp
- [ ] Redimensionamento automático (thumbnail)
- [ ] Storage em disco local ou S3 (configurável)
- [ ] Ordenação drag-and-drop
- [ ] Definir foto principal
- [ ] Testes: AssetPhotoTest

**Critérios de Aceitação:**
1. Na tela do ativo, consegue fazer upload de fotos
2. Galeria mostra thumbnails
3. Clique abre lightbox com foto em tamanho real
4. Consegue definir qual foto é a principal
5. Consegue excluir fotos
6. Validações de tamanho/tipo funcionam

**Arquivos Esperados:**
```
backend/app/Livewire/Asset/Photos/Upload.php
backend/app/Livewire/Asset/Photos/Gallery.php
backend/resources/views/livewire/asset/photos/*.blade.php
backend/tests/Feature/AssetPhotoTest.php
```

**Dependências:** Módulo de Ativos

---

## 📊 RESUMO DE PRIORIDADES

| Sprint | Funcionalidade | Prioridade | Status | Dependências |
|--------|----------------|------------|--------|--------------|
| 1 | Novo Inventário | 🔴 CRÍTICA | 🟡 Pendente | Nenhuma |
| 2 | CRUD Categorias | 🟡 MÉDIA | 🟡 Pendente | Nenhuma |
| 3 | Manutenção | 🟡 MÉDIA | 🟡 Pendente | Ativos |
| 4 | Fotos | 🟡 MÉDIA | 🟡 Pendente | Ativos |

---

## 🎨 PADRÃO DE DOCUMENTAÇÃO DE FEATURE

Para cada funcionalidade implementada, criar arquivo em `.aidev/plans/features/`:

```
.aidev/plans/features/
├── 001-inventory-create.md
├── 002-category-crud.md
├── 003-maintenance-log.md
└── 004-asset-photos.md
```

### Template de Documentação de Feature:

```markdown
# Feature: [Nome da Funcionalidade]

**Sprint:** [Número]  
**Status:** [Não iniciado|Em progresso|Concluído]  
**Data início:** [YYYY-MM-DD]  
**Data conclusão:** [YYYY-MM-DD]

## Contexto de Negócio
[Por que essa funcionalidade é necessária]

## Requisitos
[Lista de requisitos funcionais]

## Arquitetura
[Diagrama/descrição técnica]

## Implementação
### Passos:
1. [Passo 1]
2. [Passo 2]

### Commits:
- `feat: [descricao]`

## Testes
- [ ] Teste 1
- [ ] Teste 2

## Lições Aprendidas
[Documentar aqui após conclusão]
```

---

## 🔄 FLUXO DE TRABALHO

### 1. Antes de começar Sprint:
```bash
# Ler documento de contexto
cat .aidev/plans/ROADMAP.md

# Verificar sprint atual
cat .aidev/state/current-sprint.json

# Ler documentação da feature
cat .aidev/plans/features/XXX-feature-name.md
```

### 2. Durante desenvolvimento:
- Implementar seguindo requisitos
- Documentar decisões no arquivo da feature
- Commit frequente com mensagens descritivas

### 3. Ao finalizar:
- Marcar feature como concluída
- Atualizar ROADMAP.md
- Criar lição aprendida se aplicável
- Atualizar LEVANTAMENTO_FUNCIONALIDADES.md

---

## 📁 ESTRUTURA DE DOCUMENTOS

```
.aidev/
├── plans/
│   ├── ROADMAP.md                    # Este arquivo
│   ├── LEVANTAMENTO_FUNCIONALIDADES.md   # Copia atualizada
│   └── features/                     # Documentação de cada feature
│       ├── 001-inventory-create.md
│       ├── 002-category-crud.md
│       ├── 003-maintenance-log.md
│       └── 004-asset-photos.md
├── state/
│   └── current-sprint.json           # Sprint em andamento
└── memory/
    └── kb/                           # Lições aprendidas
```

---

## 🚀 COMO COMEÇAR

1. **Leia este documento** (ROADMAP.md)
2. **Escolha a Sprint 1** (funcionalidade crítica)
3. **Leia a documentação detalhada** em `.aidev/plans/features/001-inventory-create.md`
4. **Implemente** seguindo o padrão
5. **Documente** progresso no arquivo da feature
6. **Atualize** este ROADMAP ao concluir

---

## 📝 NOTAS IMPORTANTES

- **Não altere este arquivo diretamente** durante implementação
- Use os arquivos de feature específicos para detalhes
- Este é o documento mestre - mantenha-o atualizado apenas ao concluir sprints
- Todas as decisões de negócio devem estar documentadas
- Se trocar de LLM, apresente este documento primeiro

---

**Criado em:** 2026-02-05  
**Versão:** 1.0  
**Status:** Ativo  
**Próxima Sprint:** Sprint 1 - Novo Inventário
