# Feature: Gestão de Acesso e Auditoria

**Sprint:** 8
**Status:** Em progresso
**Data início:** 2026-02-13
**Data conclusão:** -

## Contexto de Negócio
Como um sistema militar/corporativo (SGAITI), é imperativo que haja controle estrito sobre quem pode realizar alterações. Além disso, todas as ações críticas devem ser auditadas para garantir a rastreabilidade (quem fez o que e quando).

## Requisitos
### 1. Perfis de Acesso (Roles)
- **Admin**: Acesso total ao sistema.
- **Operador**: Pode gerenciar ativos, manutenções e inventários. Não pode gerenciar usuários ou configurações críticas.
- **Auditor**: Acesso somente leitura a todos os módulos, incluindo relatórios.
- **Visualizador**: Acesso somente leitura limitado (apenas visualização de ativos).

### 2. Permissões Granulares
- `assets.create`, `assets.edit`, `assets.delete`
- `inventory.create`, `inventory.approve`
- `reports.view`
- `users.manage`

### 3. Trilha de Auditoria (Audit Logs)
- Registrar automaticamente eventos de: `created`, `updated`, `deleted`.
- Monitorar Models: `Asset`, `Inventory`, `MaintenanceRecord`, `User`.
- Dados registrados:
    - Usuário responsável
    - Ação
    - Modelo afetado
    - Alterações (old/new values) - *se possível via pacote ou observer simples*
    - IP e User Agent

### 4. Interface de Gestão
- Tela para Admin atribuir Roles a Usuários.
- Tela para Admin/Auditor visualizar Logs de Auditoria.

## Arquitetura
- **Lib**: `spatie/laravel-permission` para ACL.
- **Lib/Pattern**: Observer ou pacote `owen-it/laravel-auditing` (ou implementação leve nativa) para logs. *Decisão: Implementação nativa leve para evitar dependências excessivas, ou spatie/laravel-activitylog se complexidade aumentar.*
- **Policies**: Criar Policies para cada Model principal.
- **Middleware**: Aplicar validação de permissões nas rotas.

## Implementação
### Passos:
1. Instalar `spatie/laravel-permission`.
2. Configurar Roles e Permissions via Seeder (`RolesAndPermissionsSeeder`).
3. Criar tabela `audit_logs` e Model `AuditLog`.
4. Criar Trait `Auditable` para models monitorados.
5. Criar Policies para `Asset`, `Inventory`, `Maintenance`.
6. Implementar tela de gestão de usuários (atribuição de roles).
7. Implementar visualização de logs.

### Commits:
- `feat(acl): instala spatie-permission e configura roles`
- `feat(audit): implementa sistema de logs de auditoria`
- `feat(security): aplica policies e middlewares`
- `feat(ui): cria gestao de usuarios e logs`

## Testes
- [ ] Admin acessa tudo.
- [ ] Operador não consegue deletar usuários.
- [ ] Auditor vê tudo mas não edita.
- [ ] Alteração em Asset gera log de auditoria.
