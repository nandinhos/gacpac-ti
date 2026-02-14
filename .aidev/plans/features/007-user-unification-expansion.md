# Proposta Estratégica: Unificação de Identidade e Expansão Organizacional (GAC-PAC)

**Status:** IDEIA VALIDADA / AGUARDANDO SPRINT (Backlog)
**Data:** 13 de Fevereiro de 2026

## 1. Visão Geral
Refatorar o sistema de identidade para unificar `MilitaryUser` e `User`. O objetivo é gerenciar todos os integrantes do GAC-PAC em uma única entidade `User`, permitindo controle granular de acesso e rastreabilidade funcional para toda a estrutura do Grupo.

## 2. Estrutura Organizacional e Forças
O sistema deve abraçar a diversidade do GAC-PAC:
- **Organizações (Locais):** 
    - **GAC-PAC** (Sede - Grupo de Acompanhamento e Controle do Programa Aeronave de Combate)
    - **ECP-GPX** (Escritório de Gavião Peixoto - SP)
    - **ECP-IJA** (Escritório de Itajubá - MG)
    - **ECP-POA** (Escritório de Porto Alegre - RS)
- **Forças/Origem:** 
    - **FAB** (Aeronáutica)
    - **EB** (Exército)
    - **MB** (Marinha)
    - **SC** (Servidores Civis)

## 3. Estrutura de Dados Proposta (Tabela `users` expandida)

Campos a serem adicionados:
- `is_military` (boolean): `true` para militares, `false` para civis.
- `force` (string/enum): Origem (FAB, EB, MB, SC).
- `rank` (string): Posto/Graduação (ex: Cel, Ten, Sgt) ou Cargo (para SC).
- `military_id` (string): Identidade Militar (SARAM) ou CPF (para SC).
- `organization` (string): Unidade de atuação (GAC-PAC, ECP-GPX, ECP-IJA, ECP-POA).
- `sector_id` (foreignId): Setor interno de vínculo.
- `is_active` (boolean): Controle de ativação da conta.

## 4. Estratégia de Transição
1. **Migration:** Expandir a tabela `users`.
2. **Data Migration:** Mapear e mover os 17 militares atuais para a tabela `users`, garantindo que não haja perda de histórico.
3. **Refatoração:** Atualizar FKs em `assets`, `inventory_records` e `maintenance_records` para apontarem para o novo ID de `User`.
4. **Interface:** Atualizar a tela de Permissões para gerenciar Força e Organização.

## 5. Auditoria
Toda alteração nesses campos funcionais deve ser registrada na trilha de auditoria já implementada.

---
*Este plano foi validado e movido para o backlog para futura implementação em Sprints.*
