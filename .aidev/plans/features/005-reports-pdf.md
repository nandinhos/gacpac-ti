# Feature: Módulo de Relatórios PDF

**Sprint:** 7
**Status:** Concluído
**Data início:** 2026-02-13
**Data conclusão:** 2026-02-13

## Contexto de Negócio
Necessidade de extrair dados do sistema para conferência física, auditorias e arquivamento documental (ofício). O formato PDF é exigido para formalização.

## Requisitos
### 1. Dashboard de Relatórios
- Página centralizadora (`/reports`)
- Cards para diferentes tipos de relatório
- Filtros específicos para cada tipo

### 2. Tipos de Relatório
#### A. Relatório Geral de Ativos
- Filtros: Setor, Categoria, Status, Data de Aquisição
- Colunas: Tombo, Descrição, Categoria, Local, Valor, Status
- Totalizadores: Valor total, Quantidade

#### B. Relatório de Manutenção
- Filtros: Ativo, Período, Tipo (Preventiva/Corretiva)
- Colunas: Data, Ativo, Tipo, Custo, Realizado Por
- Totalizadores: Custo total no período

#### C. Relatório de Termo de Responsabilidade (Cautela)
- Filtro: Responsável (Militar selecionado via banco)
- Conteúdo: Texto padrão de termo de responsabilidade + lista de ativos
- Assinaturas: Responsável e Chefe do Setor

## Decisões Técnicas
- **Performed By**: No relatório de manutenção, optou-se por usar o campo `performed_by` (string) em vez de relacionamento direto com usuários, permitindo registro de técnicos externos ou legados.
- **DomPDF**: Configurado para papel A4, com quebras de página automáticas e cabeçalho fixo via CSS.

## Arquitetura
- **Lib**: `barryvdh/laravel-dompdf` (já instalada)
- **Controller**: `ReportController` para gerar o download/stream do PDF (evitar overhead do Livewire para binary responses pesadas).
- **Livewire**: `Reports/Index` para interface de seleção e filtros.
- **Views**:
  - `reports/pdf/assets.blade.php`
  - `reports/pdf/maintenance.blade.php`
  - `reports/pdf/responsibility.blade.php`
- **Estilo PDF**: CSS inline ou específico para impressão (A4 layout).

## Implementação
### Passos:
1. Criar `ReportController` com métodos para cada relatório.
2. Criar views Blade otimizadas para PDF (sem layout da aplicação, fundo branco, tabelas compactas).
3. Implementar `Reports/Index.php` com formulários de filtro.
4. Conectar formulários ao Controller via target `_blank`.
5. Adicionar link no menu principal (já existe, ativar).

### Commits:
- `feat(reports): cria estrutura de controller e views pdf`
- `feat(reports): implementa relatorio de ativos`
- `feat(reports): implementa relatorio de manutencao`
- `feat(reports): implementa termo de responsabilidade`

## Testes
## Testes
- [x] Gerar PDF de ativos filtrado por setor
- [x] Gerar PDF de manutenção por período
- [x] Validar layout e quebra de página
- [x] Testes automatizados (`tests/Feature/ReportTest.php`) aprovados.
