# Lição: Erros Comuns Docker + Laravel - Guia Rápido

**Data**: 2025-11-06  
**Categoria**: config  
**Stack**: Laravel 12, PHP 8.4, Docker, MySQL 8.0  
**Severity**: Médio  
**Origem**: project-docs/lessons-learned/erros-comuns-docker-laravel.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Intermitente  
**Impacto**: Médio

### Sintoma Observado
Erros recorrentes durante desenvolvimento com Docker: conexão database, permissões storage, cache de configuração, rotas duplicadas, constraints de migration.

### Comportamento Esperado
Desenvolvimento fluido sem erros de configuração ambiente

### Evidência
```bash
SQLSTATE[HY000] [2002] getaddrinfo for mysql failed
file_put_contents(...storage/framework/views/...): Permission denied
SQLSTATE[23000]: Column 'commission_number' cannot be null
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Múltiplos erros de configuração
2. **Por que?** Falta de padronização entre ambientes
3. **Por que?** Documentação dispersa
4. **Por que?** Sem checklist de validação
5. **Por que?** Processo de setup não automatizado

### Tipo de Problema
- [ ] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada - Problemas e Soluções

**1. Configuração Database Host/Port:**
```bash
# ❌ ANTES - Configuração errada
DB_HOST=mysql          # Não resolve fora do Docker
DB_PORT=3306           # Porta interna

# ✅ DEPOIS - Correto
# Local: DB_HOST=127.0.0.1, DB_PORT=53106
# Docker: DB_HOST=mysql, DB_PORT=3306
```

**2. Permissões Storage Laravel:**
```bash
# ❌ ANTES - Views/cache criados pelo Docker como root
file_put_contents(...): Permission denied

# ✅ DEPOIS - Soluções
VIEW_COMPILED_PATH=/tmp/laravel_views  # Cache temporário
LOG_CHANNEL=stderr                      # Logs para stderr
chown -R $USER:$USER storage bootstrap/cache  # Fix permissions
```

**3. Cache de Configuração:**
```bash
# ❌ ANTES - Configuração cacheada com valores antigos
php artisan config:cache

# ✅ DEPOIS - Sempre limpar antes
php artisan config:clear
php artisan cache:clear
```

**4. Rotas Duplicadas:**
```bash
# ❌ ANTES - web.php com rotas duplicadas
Route::get('/sectors', ...)->name('sectors.index');  // Primeira
Route::get('/sectors', ...)->name('sectors.index');  // Duplicada

# ✅ DEPOIS - Prevenção
php artisan route:list | grep -i duplicate
```

**5. Migration Field Constraints:**
```php
// ❌ ANTES - Constraint muito restritiva
$table->string('commission_number')->unique();  // NOT NULL obrigatório

// ✅ DEPOIS - Considerar regras de negócio
$table->string('commission_number')->nullable()->unique();  // Opcional
```

### Por Que Funciona
Padronização de configurações e checklist de validação eliminam erros comuns de ambiente.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Docker apenas | Limita flexibilidade de desenvolvimento |
| Scripts complexos | Manutenção difícil |

### Validação
- [ ] Teste adicionado/atualizado
- [x] Comando de verificação: `./scripts/health-check.sh`

---

## Prevenção

### Checklist Pre-Commit
- [ ] Local funcionando: http://127.0.0.1:8000
- [ ] Docker funcionando: http://localhost:5050
- [ ] Testes passando: `php artisan test`
- [ ] Assets compilados: `npm run build`
- [ ] Database seeded: Dados de teste OK
- [ ] Configurações corretas: .env local restaurado

### Regras de Ouro
1. **NUNCA** fazer cache de config sem testar antes
2. **SEMPRE** verificar .env antes de Docker
3. **SEPARAR** configurações local vs Docker
4. **TESTAR** ambos ambientes antes de commit
5. **LIMPAR** cache entre mudanças de ambiente

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/erros-comuns-docker-laravel.md
- **Commit/PR**: N/A
- **Documentação**: Laravel Docker, Docker Compose Best Practices

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
