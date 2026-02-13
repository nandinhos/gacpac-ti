# TASKS.md - SGAITI Development Tasks

Este documento rastreia as tarefas de desenvolvimento em andamento para o projeto SGAITI.

## Ciclo de Desenvolvimento Atual (Sprints 5 e 6)

### UI e Experiência do Usuário (UX)
- [x] **Ativar links de navegação pendentes**
  - Status: `COMPLETED`
  - Notas: Categorias e Relatórios adicionados ao menu.
- [x] **Implementar Centro de Notificações**
  - Status: `COMPLETED`
  - Notas: Dropdown de notificações em tempo real e página de histórico `/notifications`.

### Banco de Dados e Qualidade
- [x] **Criar seeders robustos e realistas**
  - Status: `COMPLETED`
  - Notas: Implementado `MaintenanceRecordSeeder` e atualizado `InventorySeeder` com dados históricos.
- [x] **Testar operações CRUD principais**
  - Status: `COMPLETED`
  - Notas: Verificado via testes de Feature automatizados (Inventory, Maintenance, Photos, Category).

### Manutenção e Housekeeping
- [x] **Limpeza de documentação legada**
  - Status: `COMPLETED`
  - Notas: Removido `project-docs/lessons-learned/` após migração para `.aidev/memory/kb/`.
- [x] **Sincronização de Roadmap**
  - Status: `COMPLETED`
  - Notas: Atualizado ROADMAP.md após conclusão das sprints.

## Melhorias Futuras (Backlog)

- [ ] **Implementar Autenticação JWT para API Mobile**
- [ ] **Módulo de Relatórios em PDF (DomPDF)**
  - Notas: Já estruturado o link e componente placeholder.
- [ ] **Configurar Backup Automático do Banco**
- [ ] **Integração com Sistema de E-mail (Notificações Externas)**
- [ ] **Melhorar Performance (Cache de Categorias/Ativos)**
- [ ] **Gestão de Permissões Baseadas em Papéis (Roles/ACL)**
