## Objetivo
Atualizar os formulários de perfil/usuário para refletir corretamente `user_role` e `is_active` da tabela `military_users`, alinhando UI e backend.

## Problema identificado
- `user_role` no banco/modelo usa valores minúsculos (`user`, `commission`, `admin`), enquanto alguns componentes usam maiúsculos (`USER`, `COMMISSION`, `ADMIN`).
- `is_active` existe no banco/modelo, mas nem sempre é exibido/editável no perfil.

## Mudanças na UI (Inertia/React)
1. `backend/resources/js/Pages/Users/Edit.jsx`
   - Garantir campos: `user_role` (select ou radio) com valores `user|commission|admin` (minúsculo).
   - Garantir `is_active` (checkbox/switch) bindando boolean.
   - Normalizar valores recebidos: converter maiúsculos para minúsculos antes do submit se necessário.
2. `backend/resources/js/Pages/Users/Create.jsx`
   - Mesma padronização para `user_role` e `is_active`.
3. `backend/resources/js/Pages/Users/Show.jsx` e `Index.jsx`
   - Exibir `user_role` e `is_active` coerentemente (badges/labels) com base em valores minúsculos.
4. `backend/resources/js/Pages/Profile/Partials/UpdateProfileInformationForm.jsx`
   - Exibir `user_role` e `is_active` do usuário logado.
   - Editabilidade:
     - Usuário comum: apenas visualizar `user_role`; não editar `is_active`.
     - Admin: pode editar `is_active` do próprio perfil e, preferencialmente, via tela de usuários o papel/status dos demais.

## Backend
1. Requests
   - `backend/app/Http/Requests/StoreMilitaryUserRequest.php`
   - `backend/app/Http/Requests/UpdateMilitaryUserRequest.php`
   - Validação: `user_role` → `required|in:user,commission,admin`; `is_active` → `boolean`.
2. Controllers
   - `backend/app/Http/Controllers/MilitaryUserController.php`: aceitar `user_role` e `is_active` no `store/update` e persistir.
   - `backend/app/Http/Controllers/ProfileController.php`: permitir atualizar `is_active` apenas para admin (ou manter somente visualização no perfil), nunca `user_role` pelo próprio usuário.
3. Autorização
   - Criar `backend/app/Policies/MilitaryUserPolicy.php` com regras: `updateRole/Status` apenas para `admin`.
   - Registrar em `backend/app/Providers/AuthServiceProvider.php`.
4. Model
   - Confirmar `$fillable` inclui `user_role` e `is_active` (já incluído) e `casts` para boolean (já incluído).

## Migração/compatibilidade de dados
- Se houver registros com `user_role` em maiúsculas no front, padronizar UI para minúsculas. O banco já usa minúsculas.

## Testes/Validação
- Fluxo admin: editar usuário e alterar `user_role` e `is_active`; verificar atualização em `military_users`.
- Fluxo usuário comum: ver `user_role` e `is_active` no perfil; não conseguir alterá-los.
- Verificar lista e detalhes mostram valores coerentes.

## Execução
- Implementar ajustes nos arquivos acima.
- Rodar a aplicação e validar via UI; opcionalmente adicionar testes de request/controller.

## Resultado Esperado
- Formulários e exibição consistentes com os campos de `military_users`.
- Sem erros de validação por case mismatch; autorização respeitada para alteração de papel e status.

## Próximo passo
Confirmando, aplico as mudanças e valido diretamente na aplicação em execução.