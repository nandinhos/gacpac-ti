# Lição: Defensive Programming em React - Valores Nulos do Backend

**Data**: 2025-11-06  
**Categoria**: bug  
**Stack**: React 18, TypeScript, Inertia.js  
**Severity**: Médio  
**Origem**: project-docs/lessons-learned/javascript-errors-inertia.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Intermitente  
**Impacto**: Médio

### Sintoma Observado
```javascript
TypeError: l.map is not a function
    at v (Show-B0fqZwmP.js:1:749)
```

Props `pendingAssets`, `foundAssets`, `uncataloguedItems` chegando como `null` ou `undefined`, causando erro ao tentar usar `.map()`.

### Comportamento Esperado
Componentes devem lidar graciosamente com dados ausentes ou nulos do backend

### Evidência
```javascript
// ❌ Erro quando dados são null
items.map(item => ...) // TypeError: items.map is not a function
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Erro ao tentar fazer map em valor não-array
2. **Por que?** Props chegaram como null/undefined do backend
3. **Por que?** Controller não garantiu retorno de arrays vazios
4. **Por que?** Frontend não protegeu contra valores nulos
5. **Por que?** Falta de defensive programming nos componentes

### Tipo de Problema
- [x] Bug de código / [ ] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```tsx
// ❌ ANTES - Perigoso
export default function Show({ inventory, pendingAssets, foundAssets, uncataloguedItems }) {
    return (
        <div>
            {pendingAssets.map(item => ...)} // 💥 Erro se null
        </div>
    );
}

// ✅ DEPOIS - Seguro com default values
export default function Show({ 
    inventory, 
    pendingAssets = [], 
    foundAssets = [], 
    uncataloguedItems = [] 
}) {
    return (
        <div>
            {pendingAssets.map(item => ...)} // ✅ Seguro
        </div>
    );
}
```

```php
// ✅ Backend também deve garantir arrays
return Inertia::render('Inventory/Show', [
    'inventory' => $inventory,
    'pendingAssets' => $pendingAssets ?? [],
    'foundAssets' => $foundAssets ?? [],
    'uncataloguedItems' => $uncataloguedItems ?? [],
]);
```

### Por Que Funciona
Default values garantem que arrays sempre existam, mesmo que backend retorne null. Coalescing operator `??` no PHP também previne null.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| TypeScript strict mode | Não previne runtime errors |
| PropTypes | Apenas warnings, não previne crash |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `console.log(typeof props.pendingAssets)`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Sempre usar default values `= []` para props arrays
- [ ] Sempre usar default values `= {}` para props objetos
- [ ] Backend sempre retornar arrays consistentes
- [ ] Verificar network responses no DevTools

### Regras de Ouro
1. **Nunca assuma** que props serão arrays - sempre use default
2. **Sempre verifique** se é array antes de métodos como map/filter
3. **Backend e frontend** devem ambos proteger contra null
4. **Error boundaries** para capturar erros não-previstos

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/javascript-errors-inertia.md
- **Commit/PR**: N/A
- **Documentação**: React Default Props, Inertia.js Shared Data

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
