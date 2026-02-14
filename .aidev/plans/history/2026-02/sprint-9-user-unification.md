# 🏃 Sprint 9: Unificação e Expansão de Identidade

**Status:** ✅ CONCLUÍDA
**Início:** 2026-02-13
**Conclusão:** 2026-02-13
**Feature Relacionada:** [007-user-unification-expansion.md](../features/007-user-unification-expansion.md)

---

## 🎯 Objetivos da Sprint
1.  Expandir a tabela `users` para suportar dados funcionais e organizacionais.
2.  Unificar `MilitaryUser` em `User`, centralizando a identidade.
3.  Atualizar a interface administrativa para gerenciar Força (FAB, EB, MB, SC) e Localidade (GAC-PAC, ECPs).

---

## 📝 Plano de Implementação (Checklist Técnico)

### Fase 1: Preparação do Banco de Dados
- [x] **Task 1.1:** Criar e executar a migration `add_extended_fields_to_users_table`.
- [x] **Task 1.2:** Validar a estrutura da tabela `users` via terminal.

### Fase 2: Unificação de Modelos e Dados
- [x] **Task 2.1:** Atualizar o Model `User.php` com novos campos e relações.
- [x] **Task 2.2:** Criar script de migração de dados (`military_users` -> `users`).
- [x] **Task 2.3:** Executar migração de dados e validar integridade.

### Fase 3: Refatoração de Relacionamentos
- [x] **Task 3.1:** Localizar todas as tabelas que usam `military_user_id` ou similar.
- [x] **Task 3.2:** Atualizar FKs para apontarem para o novo ID de `User`.

### Fase 4: Interface Administrativa (Livewire)
- [x] **Task 4.1:** Atualizar `UserManagement.php` para listar novos campos.
- [x] **Task 4.2:** Adicionar filtros por Organização e Força.
- [x] **Task 4.3:** Implementar edição de dados funcionais no modal.

---

## 🚦 Status das Tasks
- 🔴 Bloqueado
- 🟡 Em Andamento
- 🟢 Concluído

---

*Documento de controle ativo do orquestrador aidev.*
