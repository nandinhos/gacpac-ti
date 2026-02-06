# Lição: Erros Comuns Consolidados - Guia de Prevenção

**Data**: 2025-11-06  
**Categoria**: architecture  
**Stack**: Laravel 12, PHP 8.4, React 18, TypeScript, Docker  
**Severity**: Alto  
**Origem**: project-docs/lessons-learned/erros-comuns.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Sempre  
**Impacto**: Alto

### Sintoma Observado
Múltiplos erros recorrentes durante desenvolvimento: relacionamentos quebrados, N+1 queries, loops infinitos useEffect, inconsistências schema.

### Comportamento Esperado
Desenvolvimento fluido com padrões estabelecidos que previnem erros comuns

### Evidência
- Testes falhando (36% → 100%)
- Queries excessivas (50+ → 4-6 por página)
- Performance degradada (5-10s → 1-2s load)

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Múltiplos bugs em produção
2. **Por que?** Falta de padrões estabelecidos
3. **Por que?** Código desenvolvido sem revisão
4. **Por que?** Ausência de testes automatizados
5. **Por que?** Deadlines apertados sacrificando qualidade

### Tipo de Problema
- [x] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [x] Falta de validação

---

## Solução

### Correção Aplicada - Principais Padrões

**1. Relacionamentos Eloquent - Chave Estrangeira Explícita:**
```php
// ❌ ANTES - Convenção implícita
public function reopenHistory()
{
    return $this->hasMany(ReopenHistory::class); // Procura inventory_record_id
}

// ✅ DEPOIS - Chave estrangeira explícita
public function reopenHistory()
{
    return $this->hasMany(ReopenHistory::class, 'inventory_id');
}
```

**2. Eager Loading Obrigatório:**
```php
// ❌ ANTES - N+1 queries
$assets = Asset::all();
foreach ($assets as $asset) {
    echo $asset->sector->name; // +1 query
}

// ✅ DEPOIS - Eager loading
$assets = Asset::with(['sector', 'custodian'])->get();
```

**3. Form Requests em Todos Controllers:**
```php
// ❌ ANTES - Vulnerável
public function store(Request $request) {
    return Asset::create($request->all());
}

// ✅ DEPOIS - Seguro
public function store(StoreAssetRequest $request) {
    return Asset::create($request->validated());
}
```

**4. useCallback para useEffect:**
```tsx
// ❌ ANTES - Loop infinito
useEffect(() => {
    fetchData(); // Re-render constante
}, []);

// ✅ DEPOIS - Controlado
const fetchData = useCallback(async () => {
    const data = await api.getData();
    setData(data);
}, []);

useEffect(() => {
    fetchData();
}, [fetchData]);
```

**5. Persistência Real no Backend:**
```tsx
// ❌ ANTES - Só local
const handleSave = () => {
    setInventoryRecords(updatedRecords);
};

// ✅ DEPOIS - Persistência real
const handleSave = async () => {
    await inventoryApi.update(inventoryId, data);
    await loadData();
};
```

### Por Que Funciona
Padrões estabelecidos eliminam decisões ad-hoc e garantem consistência em todo o codebase.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| ESLint/PHPStan apenas | Ferramentas não substituem padrões |
| Code review manual | Não escala para equipe grande |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `php artisan test`

---

## Prevenção

### Checklist para Evitar no Futuro
**Checklist de Segurança:**
- [ ] Form Request implementado?
- [ ] Mass Assignment protegido?
- [ ] Validação adequada?
- [ ] Autenticação obrigatória?

**Checklist de Performance:**
- [ ] Eager loading aplicado?
- [ ] Paginação implementada?
- [ ] Queries otimizadas?

**Checklist de Qualidade:**
- [ ] Testes automatizados?
- [ ] TypeScript sem erros?
- [ ] Code review aprovado?

### Regras de Ouro
1. **TDD Obrigatório** - Teste antes do código
2. **Eager Loading Sempre** - Nunca N+1
3. **Form Requests Obrigatórios** - Sem exceção
4. **useCallback para Dependências** - Evita loops
5. **Schema Dual** - Durante migrações

---

## Métricas de Melhoria

| Métrica | Antes | Depois | Melhoria |
|---------|-------|--------|----------|
| Testes Passando | 36% | 100% | +64% |
| Endpoints Funcionais | 87.5% | 100% | +12.5% |
| Tempo de Load | 5-10s | 1-2s | -80% |
| Queries por Página | 50+ | 4-6 | -88% |

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/erros-comuns.md
- **Commit/PR**: N/A
- **Documentação**: Laravel Best Practices, React Patterns

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
