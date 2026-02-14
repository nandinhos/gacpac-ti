# 📦 Archive - Documentos Legados

> Documentos de planejamento antigos e templates do projeto.

---

## 📁 Conteúdo

### Documentos de Planejamento
- **PLANO_IMPLEMENTACAO.md** - Plano antigo de migração de lições aprendidas (2026-02-13)

### Templates
- **templates/** - Templates antigos de documentação

---

## 📋 Reorganização (2026-02-14)

Todos os documentos de **features** foram movidos para `../features/`:

| Arquivo Original | Novo Local |
|-----------------|------------|
| 001-inventory-create.md | ../features/001-inventory-create.md |
| 002-ui-refinement-notifications.md | ../features/002-ui-refinement-notifications.md |
| 003-maintenance-log.md | ../features/003-maintenance-log.md |
| 004-asset-photos.md | ../features/004-asset-photos.md |
| 005-reports-pdf.md | ../features/005-reports-pdf.md |
| 006-access-control-audit.md | ../features/006-access-control-audit.md |

**Motivo:** Features devem permanecer em `features/` como documentação permanente. Apenas sprints concluídas vão para `history/`.

---

## 📊 Estrutura Correta do Projeto

```
.aidev/plans/
├── features/       → Documentação PERMANENTE de todas as funcionalidades (001-008)
├── current/        → Sprint em andamento (vazio atualmente)
├── history/        → Sprints concluídas organizadas por mês (YYYY-MM/)
└── archive/        → Documentos legados e templates (esta pasta)
```

---

*Última atualização: 2026-02-14*
