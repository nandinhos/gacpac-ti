# 🚀 Features - Documentação Permanente

> Funcionalidades planejadas e implementadas no SGTI-GAC.
> Esta pasta serve como **referência permanente** de todas as features do sistema.

---

## 📋 Todas as Features (001-008)

### Feature 001 - Criar Novo Inventário
- **Arquivo:** [001-inventory-create.md](001-inventory-create.md)
- **Sprint:** 1
- **Status:** ✅ Concluída (05/02/2026)
- **Objetivo:** Implementar criação completa de inventários físicos de ativos

### Feature 002 - Refinamento de UI e Notificações
- **Arquivo:** [002-ui-refinement-notifications.md](002-ui-refinement-notifications.md)
- **Sprint:** 5-6
- **Status:** ✅ Concluída (13/02/2026)
- **Objetivo:** Melhorar UX com navegação completa e sistema de notificações em tempo real

### Feature 003 - Registro de Manutenção
- **Arquivo:** [003-maintenance-log.md](003-maintenance-log.md)
- **Sprint:** 3
- **Status:** ✅ Concluída (13/02/2026)
- **Objetivo:** Sistema completo de registro e histórico de manutenções de ativos

### Feature 004 - Upload de Fotos de Ativos
- **Arquivo:** [004-asset-photos.md](004-asset-photos.md)
- **Sprint:** 4
- **Status:** ✅ Concluída (13/02/2026)
- **Objetivo:** Permitir upload e visualização de fotos dos ativos

### Feature 005 - Relatórios PDF
- **Arquivo:** [005-reports-pdf.md](005-reports-pdf.md)
- **Sprint:** 7
- **Status:** ✅ Concluída (13/02/2026)
- **Objetivo:** Geração de relatórios em PDF (ativos, cautelas, manutenção)

### Feature 006 - Gestão de Acesso e Auditoria
- **Arquivo:** [006-access-control-audit.md](006-access-control-audit.md)
- **Sprint:** 8
- **Status:** ✅ Concluída (13/02/2026)
- **Objetivo:** Sistema de controle de acesso com roles e auditoria completa

### Feature 007 - Unificação e Expansão de Identidade
- **Arquivo:** [007-user-unification-expansion.md](007-user-unification-expansion.md)
- **Sprint:** 9
- **Status:** ✅ Concluída (13/02/2026)
- **Objetivo:** Unificar militares e civis em todas as unidades (GAC-PAC/ECPs)

### Feature 008 - Interface Unificada Admin/Users
- **Arquivo:** [008-admin-users-unified-interface.md](008-admin-users-unified-interface.md)
- **Sprint:** 10
- **Status:** ✅ Concluída (14/02/2026)
- **Objetivo:** Interface administrativa completa de gestão de usuários

---

## 📊 Estatísticas

- **Total de features:** 8
- **Status:** Todas concluídas ✅
- **Período:** Sprints 1-10 (05/02/2026 - 14/02/2026)

---

## 📋 Fluxo de Documentação

### Estrutura do Sistema de Planejamento

```
.aidev/plans/
├── features/       → Documentação PERMANENTE (nunca é removida)
├── current/        → Sprint em andamento (apenas 1 por vez)
├── history/        → Sprints concluídas (organizadas por YYYY-MM/)
└── archive/        → Documentos legados e templates
```

### Como funciona:

1. **features/** - Documentação detalhada permanece aqui para sempre
2. **current/** - Arquivo de sprint ativa (ex: `sprint-11-nome.md`)
3. **history/YYYY-MM/** - Ao concluir, sprint é movida de `current/` para `history/`

**Importante:** Features NÃO são movidas. Apenas sprints.

---

## 📚 Histórico de Reorganização

### 2026-02-14: Consolidação de Features
- Movidas features 001-006 de `archive/` para `features/`
- Renomeada `005-sprint-refinement.md` para `002-ui-refinement-notifications.md`
- Todas as 8 features agora estão centralizadas em `features/`
- `archive/` agora contém apenas documentos legados

---

*Última atualização: 2026-02-14*
