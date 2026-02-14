# 🗺️ ROADMAP DE IMPLEMENTAÇÃO - SGAITI

> Documento mestre de planejamento de funcionalidades
> Formato: AI Dev Superpowers Sprint Planning
> Última atualização: 2026-02-13
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

### 📅 SPRINT 1: Funcionalidades Críticas ✅ CONCLUÍDA
**Duração:** 1-2 semanas  
**Objetivo:** Implementar bloqueios críticos  
**Status:** ✅ **VERIFICADA - JÁ IMPLEMENTADA**
**Data verificação:** 2026-02-05

#### Funcionalidades:

##### 1.1 - Criar Novo Inventário ✅
**Prioridade:** 🔴 CRÍTICA  
**Motivo:** Botão existe mas está desabilitado (href="#") - **VERIFICADO: JÁ FUNCIONA**
**Status:** ✅ **CONCLUÍDO - Já estava implementado**  

**Requisitos de Negócio:**
- Permitir criar inventário físico de ativos por setor
- Definir comissão de inventariantes
- Registrar data de início
- Status inicial: "Em Andamento"

**Requisitos Técnicos:** ✅ TODOS IMPLEMENTADOS
- [x] Criar rota POST /inventory - **backend/routes/web.php (linha 32)**
- [x] Criar componente Livewire Inventory/Create - **backend/app/Livewire/Inventory/Create.php**
- [x] Formulário com:
  - Select de Setor (obrigatório) ✅
  - Número da Comissão (opcional, unique) ✅
  - Data de Início (default: hoje) ✅
  - Select de Responsáveis (múltiplos militares) ✅
- [x] Validação: apenas um inventário "Em Andamento" por setor ✅
- [x] Testes: InventoryCreateTest ✅ **backend/tests/Feature/InventoryCreateTest.php**

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

**Nota de Implementação:**
> ✅ **VERIFICADO EM 2026-02-05:** A funcionalidade já estava completamente implementada!
> 
> **Arquivos encontrados:**
> - `backend/app/Livewire/Inventory/Create.php` - Componente funcional
> - `backend/resources/views/livewire/inventory/create.blade.php` - View completa
> - `backend/routes/web.php` - Rota configurada
> - `backend/resources/views/livewire/inventory/index.blade.php` - Botão já aponta para rota correta
> 
> **O que funciona:**
> - Geração automática de número de comissão
> - Validações completas
> - Suporte a comissão com múltiplos membros
> - Notificações ao responsável
> - Redirecionamento após criação
> 
> **Documentação detalhada:** `.aidev/plans/features/001-inventory-create.md`

---

### 📅 SPRINT 2: Gestão de Categorias ✅ CONCLUÍDA
**Duração:** 1 semana  
**Objetivo:** Implementar CRUD completo de categorias  
**Status:** ✅ **IMPLEMENTADA**
**Data início:** 2026-02-05  
**Data conclusão:** 2026-02-05

#### Funcionalidades:

##### 2.1 - CRUD Categorias ✅
**Prioridade:** 🟡 MÉDIA  
**Motivo:** Módulo existe mas views estão vazias - **IMPLEMENTADO**

**Requisitos de Negócio:** ✅ TODOS IMPLEMENTADOS
- [x] Permitir categorização de ativos (Ex: Eletrônicos, Móveis, Veículos)
- [x] Hierarquia de categorias (categoria pai/filho)
- [x] Cores distintas para visualização

**Requisitos Técnicos:** ✅ TODOS IMPLEMENTADOS
- [x] Migration para tabela categories (adicionados parent_id, color, softDeletes)
- [x] Model Category com relação parent/children
- [x] Componente Livewire Categories/Index (listagem com filtros)
- [x] Componente Livewire Categories/Create
- [x] Componente Livewire Categories/Edit
- [x] Formulários com:
  - Nome (obrigatório, unique) ✅
  - Descrição (opcional) ✅
  - Categoria Pai (select, hierárquico) ✅
  - Cor (color picker) ✅
- [x] Validação: não permitir categoria ser pai dela mesma
- [x] Soft delete
- [ ] Testes: CategoryTest (PENDENTE)

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

**Nota de Implementação:**
> ✅ **IMPLEMENTADO EM 2026-02-05:** CRUD completo de categorias com hierarquia!
> 
> **Arquivos criados/modificados:**
> - `backend/database/migrations/2026_02_05_000000_add_hierarchy_to_categories_table.php` - Migration
> - `backend/app/Models/Category.php` - Modelo atualizado com parent/children
> - `backend/app/Livewire/Categories/Index.php` - Listagem com filtros
> - `backend/app/Livewire/Categories/Create.php` - Criação
> - `backend/app/Livewire/Categories/Edit.php` - Edição
> - `backend/resources/views/livewire/categories/index.blade.php` - View listagem
> - `backend/resources/views/livewire/categories/create.blade.php` - View criação
> - `backend/resources/views/livewire/categories/edit.blade.php` - View edição
> - `backend/routes/web.php` - Rotas atualizadas
> 
> **Funcionalidades implementadas:**
> - Hierarquia de categorias (pai/filho)
> - Soft delete
> - Color picker para categorias
> - Validação contra ciclos
> - Filtros por busca e categoria pai
> - Paginação
> - Preview de cor em tempo real
> - Proteção contra exclusão se houver ativos/subcategorias

---

### 📅 SPRINT 3: Manutenção de Ativos ✅ CONCLUÍDA
**Duração:** 1-2 semanas
**Objetivo:** Implementar histórico de manutenções
**Status:** ✅ **VERIFICADA - JÁ IMPLEMENTADA**
**Data verificação:** 2026-02-13

#### Funcionalidades:

##### 3.1 - Registro de Manutenção ✅
**Prioridade:** 🟡 MÉDIA
**Motivo:** API existe, falta UI - **VERIFICADO: JÁ FUNCIONA**
**Status:** ✅ **CONCLUÍDO**

**Requisitos de Negócio:**
- Registrar manutenções preventivas e corretivas
- Controle de custos
- Agendamento de próximas manutenções
- Histórico completo por ativo

**Requisitos Técnicos:** ✅ TODOS IMPLEMENTADOS
- [x] Tabela `maintenance_records` criada (3 migrations)
- [x] Campos: asset_id, type (preventiva/corretiva), description, cost, date, next_maintenance_date, performed_by, notes, is_upgrade, parts_replaced
- [x] Model `MaintenanceRecord` com relação Asset, scopes `upcoming()` e `overdue()`
- [x] Componente Livewire `Maintenance/Index` (histórico com busca e filtros)
- [x] Componente Livewire `Maintenance/Create` (formulário completo)
- [x] Aba "Manutenções" na tela de edição do ativo (embedded)
- [x] Formulário com todos os campos + checkbox de upgrade/modificação
- [x] Listagem com filtros por tipo e busca por texto
- [x] Badge com contagem de manutenções na aba
- [x] Testes: `MaintenanceTest.php` (11 testes)
- [x] Factory: `MaintenanceRecordFactory.php`

**Critérios de Aceitação:**
1. ✅ Na tela do ativo, aba "Manutenções" mostra histórico
2. ✅ Consegue adicionar nova manutenção
3. ✅ Visualiza resumo de manutenções do ativo
4. ✅ Próximas manutenções com indicadores visuais (vermelho=vencida, amarelo=próxima)
5. ✅ Filtros funcionam corretamente

**Arquivos Implementados:**
```
backend/app/Models/MaintenanceRecord.php
backend/app/Livewire/Maintenance/Index.php
backend/app/Livewire/Maintenance/Create.php
backend/resources/views/livewire/maintenance/index.blade.php
backend/resources/views/livewire/maintenance/create.blade.php
backend/tests/Feature/MaintenanceTest.php
backend/database/factories/MaintenanceRecordFactory.php
```

**Dependências:** Módulo de Ativos (já existe)

**Nota de Implementação:**
> ✅ **VERIFICADO EM 2026-02-13:** Funcionalidade completamente implementada!
>
> **Funcionalidades extras implementadas:**
> - Rastreamento de upgrades/modificações em ativos (`is_upgrade`, `parts_replaced`)
> - Campo `is_modified` no ativo para marcar modificações permanentes
> - Modal de confirmação para exclusão
> - Componente embeddable na view de edição do ativo
> - Scopes `upcoming()` e `overdue()` para consultas futuras

---

### 📅 SPRINT 4: Fotos de Ativos ✅ CONCLUÍDA
**Duração:** 1 semana
**Objetivo:** Implementar upload e galeria de fotos
**Status:** ✅ **VERIFICADA - JÁ IMPLEMENTADA**
**Data verificação:** 2026-02-13

#### Funcionalidades:

##### 4.1 - Upload de Fotos ✅
**Prioridade:** 🟡 MÉDIA
**Motivo:** API existe, falta UI - **VERIFICADO: JÁ FUNCIONA**
**Status:** ✅ **CONCLUÍDO**

**Requisitos de Negócio:**
- Anexar fotos do ativo (múltiplas)
- Visualizar galeria
- Definir foto principal
- Excluir fotos

**Requisitos Técnicos:** ✅ TODOS IMPLEMENTADOS
- [x] Tabela `asset_photos` criada (2 migrations: criação + campos adicionais)
- [x] Campos: asset_id, url, caption, uploaded_at, mime_type, is_primary, file_size
- [x] Model `AssetPhoto` com relação Asset, scope `primary()`, accessor `storageUrl`
- [x] Upload integrado no componente `Assets/Edit.php` (aba Fotos)
- [x] Componente standalone `Photos/Index.php` para galeria
- [x] Upload múltiplo com drag-and-drop e preview
- [x] Validação: max 10MB, tipos: jpg, jpeg, png, webp
- [x] Lightbox com navegação por teclado e thumbnails
- [x] Edição inline de legendas
- [x] Definir foto principal (auto-promoção ao deletar)
- [x] Testes: `AssetPhotoTest.php` (14 testes)
- [x] Factory: `AssetPhotoFactory.php`
- [x] Rotas API: POST e DELETE para fotos

**Critérios de Aceitação:**
1. ✅ Na tela do ativo, consegue fazer upload de fotos (aba "Fotos")
2. ✅ Galeria mostra grid responsivo com thumbnails
3. ✅ Clique abre lightbox com navegação e metadados
4. ✅ Consegue definir qual foto é a principal
5. ✅ Consegue excluir fotos (com confirmação)
6. ✅ Validações de tamanho/tipo funcionam
7. ✅ Edição inline de legendas
8. ✅ Auto-promoção de foto ao deletar a principal

**Arquivos Implementados:**
```
backend/app/Models/AssetPhoto.php
backend/app/Livewire/Assets/Edit.php (métodos de foto integrados)
backend/app/Livewire/Photos/Index.php
backend/resources/views/livewire/assets/edit.blade.php (aba Fotos)
backend/resources/views/livewire/photos/index.blade.php
backend/resources/views/components/photo-lightbox.blade.php
backend/tests/Feature/AssetPhotoTest.php
backend/database/factories/AssetPhotoFactory.php
```

**Dependências:** Módulo de Ativos

**Nota de Implementação:**
> ✅ **VERIFICADO EM 2026-02-13:** Funcionalidade completamente implementada!
>
> **Funcionalidades extras implementadas:**
> - Drag-and-drop com preview antes do upload
> - Lightbox completo com navegação por teclado (setas, ESC)
> - Thumbnails strip na parte inferior do lightbox
> - Edição inline de legendas (salvar/cancelar)
> - Exclusão deferida para evitar erros 403 durante morphing do Livewire
> - Metadados exibidos: data upload, tamanho, tipo, status principal
> - Storage organizado por asset: `asset-photos/{asset-id}/`

---

## 📊 RESUMO DE PRIORIDADES

| Sprint | Funcionalidade | Prioridade | Status | Dependências |
|--------|----------------|------------|--------|--------------|
| 1 | ✅ Novo Inventário | 🔴 CRÍTICA | **CONCLUÍDO** | Nenhuma |
| 2 | ✅ CRUD Categorias | 🟡 MÉDIA | **CONCLUÍDO** | Nenhuma |
| 3 | ✅ Manutenção | 🟡 MÉDIA | **CONCLUÍDO** | Ativos |
| 4 | ✅ Fotos | 🟡 MÉDIA | **CONCLUÍDO** | Ativos |

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
**Status:** Ativo  
**Próxima Sprint:** Sprint 7 - Módulo de Relatórios PDF

---

### 📅 SPRINT 7: Relatórios PDF ✅ CONCLUÍDA
**Duração:** 1 semana
**Objetivo:** Implementar geração de PDFs para ativos, manutenção e termos.
**Status:** ✅ **IMPLEMENTADA**
**Data início:** 2026-02-13
**Data conclusão:** 2026-02-13

#### Funcionalidades:

##### 7.1 - Geração de PDFs
**Prioridade:** 🔴 ALTA
**Requisitos Técnicos:**
- [x] Controller `ReportController`.
- [x] Views específicas para impressão (A4).
- [x] Relatórios: Ativos, Manutenção, Termo.

---

### 📅 SPRINT 8: Gestão de Acesso e Auditoria ✅ CONCLUÍDA
**Duração:** 1 semana
**Objetivo:** Implementar RBAC (Roles) e Logs de Auditoria.
**Status:** ✅ **CONCLUÍDA**
**Data conclusão:** 2026-02-13

#### Funcionalidades:

##### 8.1 - Controle de Acesso (ACL) ✅
**Status:** ✅ **CONCLUÍDO**

##### 8.2 - Trilha de Auditoria ✅
**Status:** ✅ **CONCLUÍDO**

---

### 📅 SPRINT 9: Unificação e Expansão de Identidade (A Iniciar)
**Duração:** 1-2 semanas
**Objetivo:** Consolidar `MilitaryUser` e `User` e expandir para estrutura GAC-PAC/ECPs.
**Status:** 📅 AGUARDANDO INÍCIO
**Data início:** 2026-02-13

#### Funcionalidades:

##### 9.1 - Expansão do Schema de Usuário
**Prioridade:** 🔴 CRÍTICA
**Requisitos Técnicos:**
- [ ] Migration `add_extended_fields_to_users_table`.
- [ ] Campos: `is_military`, `force`, `rank`, `organization`, `military_id`.

##### 9.2 - Migração e Unificação de Dados
**Prioridade:** 🔴 CRÍTICA
**Requisitos Técnicos:**
- [ ] Script de migração `military_users` -> `users`.
- [ ] Atualização de FKs em Ativos/Inventários.

##### 9.3 - Painel de Permissões Multi-Unidade
**Prioridade:** 🟡 MÉDIA
**Requisitos Técnicos:**
- [ ] Filtros por Organização e Força no Admin.
- [ ] Gestão de Roles unificada.

---

### 📅 SPRINT 5: Refinamento de UI e Notificações ✅ CONCLUÍDA
**Duração:** 1 semana
**Objetivo:** Ativar menus pendentes e implementar sistema de notificações visual
**Status:** ✅ **CONCLUÍDA**
**Data conclusão:** 2026-02-13

#### Funcionalidades:

##### 5.1 - Navegação e Menus ✅
**Prioridade:** 🟡 MÉDIA
**Status:** ✅ **CONCLUÍDO**
**Requisitos Técnicos:**
- [x] Ativar link "Categorias" no menu principal.
- [x] Ativar/Estruturar link "Relatórios" (placeholder).
- [x] Garantir `wire:navigate` em todos os links.

##### 5.2 - Centro de Notificações ✅
**Prioridade:** 🔴 ALTA
**Status:** ✅ **CONCLUÍDO**
**Requisitos Técnicos:**
- [x] Criar componente Livewire `Notifications/Dropdown`.
- [x] Integrar dropdown no layout principal.
- [x] Ícone de sino com contador de não lidas.
- [x] Lista rápida de notificações com link para ação.
- [x] Marcar como lida ao clicar.
- [x] Testes: `NotificationTest.php` (PASSOU).

---

### 📅 SPRINT 6: Dados e Manutenção ✅ CONCLUÍDA
**Duração:** 1 semana
**Objetivo:** Robustez de dados e limpeza de sistema
**Status:** ✅ **CONCLUÍDA**
**Data conclusão:** 2026-02-13

#### Funcionalidades:

##### 6.1 - Seeders Avançados ✅
**Prioridade:** 🟡 MÉDIA
**Status:** ✅ **CONCLUÍDO**
**Requisitos Técnicos:**
- [x] Refatorar `InventorySeeder` para criar dados históricos.
- [x] Criar e implementar `MaintenanceRecordSeeder` para testar alertas.
- [x] Comando `migrate:fresh --seed` operacional.

##### 6.2 - Housekeeping e Limpeza ✅
**Prioridade:** 🔵 BAIXA
**Status:** ✅ **CONCLUÍDO**
**Requisitos Técnicos:**
- [x] Remover `project-docs/lessons-learned/` (migrados).
- [x] Atualizar `TASKS.md` com status real.
- [x] Corrigir bugs de testes antigos (ilike no SQLite, limites de upload).
