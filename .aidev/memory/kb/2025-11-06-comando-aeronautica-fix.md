# Lição: Rota Inexistente para Reabrir Inventário - Comando Aeronáutica

**Data**: 2025-11-06  
**Categoria**: bug  
**Stack**: Laravel 12, PHP 8.4, React 18, Inertia.js  
**Severity**: Alto  
**Origem**: project-docs/lessons-learned/comando-aeronautica-fix.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Sempre  
**Impacto**: Alto

### Sintoma Observado
Botão "Reabrir para Edição" não funcionava. Ao clicar, gerava erro 404 Not Found.

### Comportamento Esperado
Inventário concluído deveria ser reaberto para edição com justificativa

### Evidência
```javascript
// ❌ PROBLEMA: Rota inexistente
href={route('inventory.reopen', { inventory: inventory.id })}
// Gerava: /inventory/2/reopen (404 Not Found)
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Erro 404 ao tentar reabrir inventário
2. **Por que?** Rota estava sendo gerada com objeto ao invés de ID
3. **Por que?** Inertia.js route helper interpreta objeto como parâmetro de query
4. **Por que?** Documentação do Inertia não deixa claro que deve passar ID direto
5. **Por que?** Testes de integração não cobriam este fluxo

### Tipo de Problema
- [x] Bug de código / [ ] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```jsx
// ❌ ANTES - Objeto como parâmetro
<Link
    href={route('inventory.reopen', { inventory: inventory.id })}
    method="put"
>

// ✅ DEPOIS - ID direto como parâmetro
<Link
    href={route('inventory.reopen', inventory.id)}
    method="put"
    data={{ justification: 'Inventário reaberto para correções via interface web' }}
    as="button"
    onBefore={() => confirm('Tem certeza que deseja reabrir?')}
>
    Reabrir para Edição
</Link>
```

### Por Que Funciona
A rota Laravel espera `inventory` como parâmetro de rota (/{inventory}/reopen), não como query string. Passando o ID direto, o Inertia substitui corretamente o placeholder.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Alterar rota no Laravel | Quebraria outras partes do sistema |
| Usar form POST tradicional | Perderia benefícios do Inertia |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `php artisan route:list | grep reopen`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Sempre testar rotas com Inertia.js em ambos ambientes
- [ ] Documentar formato correto de parâmetros no Route::resource
- [ ] Criar teste de integração para fluxos críticos

### Regras de Ouro
1. route() do Inertia recebe ID direto, não objeto
2. Testar rotas PUT/PATCH/DELETE especificamente
3. Verificar se rota existe antes de usar no frontend

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/comando-aeronautica-fix.md
- **Commit/PR**: N/A
- **Documentação**: Inertia.js Routing, Laravel Route Parameters

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
