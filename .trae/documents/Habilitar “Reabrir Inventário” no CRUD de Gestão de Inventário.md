## Objetivo
Expor a ação de “Reabrir inventário” no CRUD do módulo Inventário, usando as rotas e controller já existentes, com validação, modal de justificativa e atualização de estado/UI.

## Backend (já existente, alinhar uso)
- Usar rota web `PUT /inventory/{inventory}/reopen` (name `inventory.reopen`) para fluxos Inertia com redirecionamento e histórico.
- Alternativamente, usar `POST /api/inventory/{id}/reopen` para chamadas via `inventoryApi` com retorno JSON.
- Controller `InventoryRecordController@reopen`: atualiza `status='Reaberto'`, limpa `end_date`, grava histórico.

## Frontend Inertia
1. Inventory/Summary
- Adicionar botão “Reabrir inventário” quando `status === 'Concluído'`.
- Ao clicar, abrir modal pedindo justificativa.
- Submeter para `route('inventory.reopen', inventory.id)` via método `put` com `{ justification }`.
- Após sucesso, redirecionar para `Inventory/Show` do inventário reaberto.

2. Inventory/Show
- Exibir status atualizado ('Reaberto') e habilitar campos/ações de continuação.
- Se apropriado, desabilitar “Concluir” até que pendências sejam resolvidas.

3. components/InventoryManagement.tsx / services/api.ts
- Se preferir REST: usar `inventoryApi.reopen(id, { userId, justification })` e alinhar para chamar `POST /api/inventory/{id}/reopen` (em vez de um `PUT /inventory/{id}` ad-hoc).

## Regras/Validação
- Apenas inventários com `status === 'Concluído'` podem ser reabertos.
- Justificativa obrigatória.
- Registrar histórico (`reopenHistory` com `reopened_by_user_id`, `justification`, `reopened_at`).

## UI/UX
- Modal com campo de texto para justificativa, botões Confirmar/Cancelar.
- Feedback: toast/sucesso e atualização de status na UI.

## Resultado Esperado
- A ação “Reabrir inventário” fica acessível e funcional no CRUD.
- Estado e histórico atualizados e visíveis ao usuário.

## Próximo passo
Com sua confirmação, implemento o botão e modal nas páginas Inertia, ajusto o `inventoryApi.reopen` para a rota dedicada e valido fim-a-fim.