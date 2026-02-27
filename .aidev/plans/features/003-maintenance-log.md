# Feature: Registro de Manutenção

**Sprint:** 3
**Status:** Concluído
**Data início:** 2026-02-06
**Data conclusão:** 2026-02-13

## Contexto de Negócio
O sistema de gestão de ativos (SGAITI) precisa rastrear o histórico de manutenções (preventivas e corretivas) para controle de custos, garantias e vida útil dos equipamentos. Atualmente não há interface para registrar essas intervenções.

## Requisitos

### Funcionais
- [x] Registrar manutenções preventivas e corretivas vinculadas a um ativo.
- [x] Informar data, tipo, custo, descrição, quem realizou e observações.
- [x] Visualizar histórico de manutenções na ficha do ativo.
- [x] Calcular custo total de manutenções do ativo.
- [x] Agendar próxima manutenção (opcional).
- [x] Rastrear upgrades/modificações em ativos (is_upgrade, parts_replaced).

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
- [x] Criar manutenção válida (corretiva e preventiva).
- [x] Validar campos obrigatórios.
- [x] Verificar relacionamento com Asset.
- [x] Verificar cálculo de totais.
- [x] Filtro por tipo.
- [x] Exclusão de registro.
- [x] Scopes: upcoming() e overdue().
- [x] Validação de data (next_maintenance_date > date).

**Arquivo:** `tests/Feature/MaintenanceTest.php` (11 testes)

## Lições Aprendidas
- Componente embeddable (`isEmbedded`) permite reutilizar a listagem dentro da aba do ativo.
- Exclusão deferida evita erros de morphing do Livewire.
- Scopes no model facilitam consultas futuras para alertas no dashboard.
