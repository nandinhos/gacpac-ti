# TASKS.md - SGAITI-UM Development Tasks

This document tracks ongoing development tasks for the SGAITI-UM project.

## Current Development Cycle

### Database Testing and Seeding

- [ ] **Analisar problema de criação de usuários no banco de dados**
  - Status: `COMPLETED`
  - Notes: Initial analysis performed. Identified need for comprehensive database testing.

- [ ] **Criar seeders completos para todos os recursos do sistema**
  - Status: `IN PROGRESS`
  - Notes: Seed script (`backend/scripts/seed.js`) needs to be updated to include more comprehensive data for all entities, especially inventory and custody logs with various states.

- [ ] **Testar operações CRUD de usuários no banco**
  - Status: `PENDING`
  - Notes: Will use `backend/scripts/test-database.js` to verify user CRUD operations.

- [ ] **Testar operações CRUD de ativos no banco**
  - Status: `PENDING`
  - Notes: Will use `backend/scripts/test-database.js` to verify asset CRUD operations.

- [ ] **Testar operações CRUD de setores no banco**
  - Status: `PENDING`
  - Notes: Will use `backend/scripts/test-database.js` to verify sector CRUD operations.

- [ ] **Criar arquivo de tarefas (TASKS.md) para acompanhamento em tempo real**
  - Status: `IN PROGRESS`
  - Notes: This file is being created.

- [ ] **Verificar integridade das tabelas e constraints**
  - Status: `PENDING`
  - Notes: Will use `backend/scripts/test-database.js` to verify database constraints and relationships.

- [ ] **Testar relacionamentos entre tabelas**
  - Status: `PENDING`
  - Notes: Will use `backend/scripts/test-database.js` to verify foreign key relationships.

### Future Enhancements

- [ ] Implementar autenticação JWT
- [ ] Adicionar testes unitários e E2E
- [ ] Configurar deploy em produção com HTTPS
- [ ] Implementar backup automático
- [ ] Adicionar notificações por e-mail
- [ ] Melhorar performance com paginação
- [ ] Implementar permissões baseadas em papéis