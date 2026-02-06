# Lição: Itens Não Catalogados em Branco - Accessor Inconsistente

**Data**: 2025-11-06  
**Categoria**: bug  
**Stack**: Laravel 12, PHP 8.4, React 18  
**Severity**: Alto  
**Origem**: project-docs/lessons-learned/uncatalogued-items-fix.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Sempre  
**Impacto**: Alto

### Sintoma Observado
- Itens não catalogados são salvos no banco corretamente
- Lista mostra quantidade correta (ex: "Itens não Catalogados (2)")
- Conteúdo aparece em branco/vazio
- Botão "Remover" presente mas sem texto

### Comportamento Esperado
Itens não catalogados devem exibir descrição e permitir remoção

### Evidência
```jsx
// ❌ PROBLEMA no JSX
<p>{item.description}</p>  // item não tem .description

// ❌ PROBLEMA no Model
return $item->description; // Retornava só string, sem ID
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Item aparece em branco no frontend
2. **Por que?** Frontend tenta acessar item.description mas recebe string
3. **Por que?** Accessor do Laravel retornava apenas a string de descrição
4. **Por que?** Model foi alterado mas accessor não foi atualizado
5. **Por que?** Falta de testes de integração entre backend e frontend

### Tipo de Problema
- [x] Bug de código / [ ] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```php
// ANTES (❌ Só strings)
public function getUncataloguedItemsAttribute()
{
    return $this->uncataloguedItems()->get()->map(function ($item) {
        return $item->description;  // ❌ Só string
    })->values();
}

// DEPOIS (✅ Objetos completos)
public function getUncataloguedItemsAttribute()
{
    return $this->uncataloguedItems()->get()->map(function ($item) {
        return [
            'id' => $item->id,
            'description' => $item->description,
            'created_at' => $item->created_at,
        ];
    })->values();
}
```

```jsx
// ANTES (❌ Assumia objeto)
<p>{item.description}</p>
<button onClick={() => onRemoveItem(item.id)}>Remover</button>

// DEPOIS (✅ Defensive programming)
<p>{typeof item === 'string' ? item : item.description}</p>
<button onClick={() => onRemoveItem(typeof item === 'string' ? index : item.id)}>Remover</button>
```

### Por Que Funciona
Retornar objetos completos com ID permite operações CRUD no frontend. Defensive programming garante compatibilidade com dados antigos.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Migration de dados | Complexo demais para fix rápido |
| Forçar refresh do banco | Perderia dados existentes |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `php artisan test --filter=UncataloguedItems`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Sempre retornar estrutura completa em accessors
- [ ] Usar defensive programming quando tipo de dado é incerto
- [ ] Testar integração backend-frontend após mudanças no accessor

### Regras de Ouro
1. Accessors devem retornar estruturas consistentes com IDs
2. Frontend sempre deve validar tipo de dados recebidos
3. Logar estrutura de dados em desenvolvimento para debug

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/uncatalogued-items-fix.md
- **Commit/PR**: N/A
- **Documentação**: Laravel Eloquent Accessors & Mutators

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
