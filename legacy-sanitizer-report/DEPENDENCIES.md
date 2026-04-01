# RELATÓRIO DE DEPENDÊNCIAS

## Visão Geral
Análise das dependências do Composer e seu estado atual.

---

## Estado das Dependências

### Dependências Principais

| Pacote | Versão | Status |
|--------|--------|--------|
| laravel/framework | ^12.0 | ✅ Atualizado |
| livewire/livewire | ^4.0 | ✅ Atualizado |
| spatie/laravel-permission | ^7.0 | ✅ Atualizado |
| barryvdh/laravel-dompdf | ^3.1 | ✅ Atualizado |
| laravel/sanctum | ^4.0 | ✅ Atualizado |

### Dependências de Desenvolvimento

| Pacote | Versão | Status |
|--------|--------|--------|
| laravel/pint | ^1.24 | ✅ Atualizado |
| phpunit/phpunit | ^11.5.3 | ✅ Atualizado |
| mockery/mockery | ^1.6 | ✅ Atualizado |

---

## Auditoria de Segurança

```
composer audit: Nenhuma vulnerabilidade encontrada
```

---

## Issues de Dependências

### 🟡 MÉDIO (1)

#### DEP-004: Pacotes não usados
- **Problema:** Pode haver packages não utilizados
- **Recomendação:** Executar `composer unused` para identificar
- **Status:** A verificar manualmente

---

## Score de Dependências

| Métrica | Valor |
|---------|-------|
| Vulnerabilidades | 0 |
| Pacotes desatualizados | 0 |
| Conflitos de versão | 0 |

**Dependency Score: 70/100**