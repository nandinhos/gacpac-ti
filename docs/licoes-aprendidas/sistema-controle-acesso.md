# Lições Aprendidas - Sistema de Controle de Acesso

## Data
2025-11-27

## Contexto
Implementação de sistema completo de controle de acesso granular baseado em perfis de usuário (administrador, comissão, usuário regular) no sistema SGAITI.

## Problema Inicial
Todos os usuários tinham acesso a todas as funcionalidades do sistema, sem restrições baseadas em perfis. O menu do frontend exibia todas as opções para todos os usuários.

## Causa Raiz
1. Policies criadas mas não aplicadas nos controllers
2. Frontend não verificava o perfil do usuário para exibir menus
3. Falta de scopes nos models para filtrar dados automaticamente

## Solução Implementada

### Backend

#### Policies Criadas
- `AssetPolicy`: Controle de acesso para ativos
- `CustodyLogPolicy`: Controle de acesso para cautelas
- `InventoryRecordPolicy`: Controle de acesso para inventários

#### Scopes nos Models
- `MilitaryUser`: Métodos helper `isAdmin()`, `isCommission()`, `isUser()`, `hasAccessToInventory()`
- `CustodyLog`: Scope `forUser()` para filtrar cautelas por perfil
- `InventoryRecord`: Scope `forUser()` para filtrar inventários por perfil

#### Controllers Atualizados
- `CustodyLogController`: Aplicação de `authorize()` em todos os métodos + scope `forUser()`
- `InventoryRecordController`: Aplicação de `authorize()` em todos os métodos + scope `forUser()`

#### Registro de Policies
Policies registradas em `AppServiceProvider` usando `Gate::policy()`

### Frontend

#### Ajuste no Menu
Implementada função `getNavigationItems()` no `SGAITILayout.jsx` que:
- Define todos os itens de menu com suas respectivas roles permitidas
- Filtra menus baseado no `user.user_role`
- Retorna apenas itens permitidos para o perfil atual

#### Estrutura de Permissões por Menu
- Dashboard: admin, commission, user
- Ativos: admin, commission
- Cautelas: admin, commission, user
- Inventário: admin, commission
- Setores: admin
- Usuários: admin
- Relatórios: admin, commission

## Erros Encontrados e Resoluções

### Erro 1: Policies não sendo aplicadas
**Problema**: Controllers não chamavam `authorize()`
**Solução**: Adicionar `$this->authorize()` em todos os métodos dos controllers

### Erro 2: Dados não filtrados por perfil
**Problema**: Queries retornavam todos os dados independente do perfil
**Solução**: Criar scopes `forUser()` e aplicar nas queries dos controllers

### Erro 3: Menu exibindo todas opções
**Problema**: Array de navegação era fixo
**Solução**: Criar função que filtra menus baseado em `user.user_role`

### Erro 4: Frontend não recebia informações de perfil
**Problema**: Verificado que AuthController já retornava `user_role` corretamente
**Solução**: Usar `usePage().props.auth.user.user_role` no frontend

## Boas Práticas Aplicadas

1. Separação de responsabilidades: Policies para autorização, Scopes para filtragem
2. DRY: Métodos helper no model para evitar repetição de código
3. Segurança em camadas: Backend (policies + scopes) + Frontend (menu condicional)
4. Convenções Laravel: Uso correto de policies e gates
5. UX: Menu limpo mostrando apenas opções relevantes

## Pontos de Atenção

1. Policies devem ser registradas no AppServiceProvider
2. Controllers devem chamar `authorize()` explicitamente
3. Scopes devem ser aplicados nas queries dos controllers
4. Frontend deve usar informações do usuário autenticado
5. Recompilar assets após mudanças no frontend

## Comandos Úteis

```bash
# Criar policy
php artisan make:policy NomePolicy --model=NomeModel

# Recompilar assets
npm run build

# Executar seeder de teste
php artisan db:seed --class=AccessControlTestSeeder
```

## Melhorias Futuras

1. Implementar middleware customizado para verificação de roles
2. Criar sistema de permissões mais granular (abilities)
3. Adicionar logs de auditoria para ações sensíveis
4. Implementar cache de permissões para performance
5. Criar testes automatizados para policies

## Referências

- Laravel Policies: https://laravel.com/docs/authorization
- Laravel Query Scopes: https://laravel.com/docs/eloquent#query-scopes
- Inertia.js Shared Data: https://inertiajs.com/shared-data
