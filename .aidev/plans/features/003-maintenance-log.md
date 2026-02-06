# Feature: Registro de Manutenção

**Sprint:** 3
**Status:** Em progresso
**Data início:** 2026-02-06
**Data conclusão:** -

## Contexto de Negócio
O sistema de gestão de ativos (SGAITI) precisa rastrear o histórico de manutenções (preventivas e corretivas) para controle de custos, garantias e vida útil dos equipamentos. Atualmente não há interface para registrar essas intervenções.

## Requisitos

### Funcionais
- [ ] Registrar manutenções preventivas e corretivas vinculadas a um ativo.
- [ ] Informar data, tipo, custo, descrição, quem realizou e observações.
- [ ] Visualizar histórico de manutenções na ficha do ativo.
- [ ] Calcular custo total de manutenções do ativo.
- [ ] Agendar próxima manutenção (opcional).

### Regras de Negócio
- O custo deve ser registrado em decimal.
- Data da manutenção não pode ser futura.
- Tipo deve ser enum: 'preventive', 'corrective', 'upgrade'.

## Arquitetura

### Modelo de Dados (`maintenance_records`)
- `id` (PK)
- `asset_id` (FK -> assets)
- `type` (varchar)
- `description` (text)
- `cost` (numeric)
- `date` (date) - *Schema uses 'date'*
- `next_maintenance_date` (date)
- `performed_by` (varchar)
- `notes` (text)
- `timestamps`

### Componentes Livewire
- `Asset\Maintenance\Index`: Listagem dentro da aba de detalhes do ativo.
- `Asset\Maintenance\Create`: Modal ou formulário para nova manutenção.

## Implementação

### Passos:
1. Validar Model `MaintenanceRecord` (fillables/casts).
2. Criar Componente Livewire `Asset\Maintenance\Index`.
3. Criar Componente Livewire `Asset\Maintenance\Create` (Modal).
4. Integrar na view `assets.show` (nova aba).
5. Testes automatizados (Feature).

### Commits:
- `feat: implement asset maintenance list component`
- `feat: implement maintenance creation modal`
- `test: add maintenance feature tests`

## Testes
- [ ] Criar manutenção válida.
- [ ] Validar campos obrigatórios.
- [ ] Verificar relacionamento com Asset.
- [ ] Verificar cálculo de totais.

## Lições Aprendidas
[A preencher]
