# Lição: Ordenação de Rotas Laravel - Estáticas vs Dinâmicas

**Data**: 2026-02-13  
**Categoria**: bug  
**Stack**: Laravel 12, PHP 8.4  
**Severity**: Médio  
**Origem**: Erro detectado ao acessar /users/create após definição de /users/{user}

---

## Contexto

**Ambiente**: Desenvolvimento / Produção  
**Frequência**: Sempre que a ordem for violada  
**Impacto**: Alto (Quebra funcionalidades de criação/indexação)

### Sintoma Observado
Erro `Illuminate\Database\QueryException` ou `ModelNotFoundException` ao tentar acessar uma rota estática (ex: `/create`). O banco de dados tenta converter a string "create" em um ID (integer/bigint), resultando em erro de sintaxe SQL.

### Comportamento Esperado
A URL `/users/create` deve carregar o formulário de criação, não tentar buscar um usuário com ID "create".

### Evidência
```
SQLSTATE[22P02]: Invalid text representation: 7 ERROR: invalid input syntax for type bigint: "create"
SQL: select * from "military_users" where "id" = create ...
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** O banco tentou buscar um registro usando a palavra "create" como ID.
2. **Por que?** O Laravel capturou a requisição na rota dinâmica `{user}`.
3. **Por que?** A rota `Route::get('/users/{user}', ...)` foi definida antes de `Route::get('/users/create', ...)`.
4. **Por que?** O roteador do Laravel avalia as rotas na ordem em que são registradas.
5. **Por que?** Falta de observância ao padrão de "Static Routes First".

### Tipo de Problema
- [x] Bug de código / [ ] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
Reordenar as rotas no arquivo `routes/web.php` ou `routes/api.php` para que definições estáticas venham **antes** de parâmetros genéricos.

```php
// ❌ ANTES - Errado
Route::get('/users/{user}', Show::class);
Route::get('/users/create', Create::class); // Nunca será alcançada!

// ✅ DEPOIS - Correto
Route::get('/users/create', Create::class);
Route::get('/users/{user}', Show::class);
```

### Por Que Funciona
O roteador encontra o match exato para `/create` primeiro e interrompe a busca, evitando que o parâmetro coringa `{user}` capture o valor indesejado.

### Validação
- [x] Teste de integração acessando a rota estática.
- [x] Verificação manual no browser.

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Colocou rotas de `create`, `export`, `report` antes de rotas com `{id}`?
- [ ] Usou `whereNumber('id')` em rotas dinâmicas para restringir o tipo do parâmetro?

### Regras de Ouro
1. **Estáticas Primeiro:** Rotas fixas sempre no topo do grupo.
2. **Tipagem de Parâmetros:** Use restrições de parâmetro (ex: `->whereNumber('user')`) para aumentar a segurança do roteamento.

---

## Referências
- Documentação Laravel: [Routing - Route Parameters](https://laravel.com/docs/routing#parameters-regular-expression-constraints)

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
