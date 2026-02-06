# Lição: Permissões Laravel Storage - Docker vs Local

**Data**: 2025-11-06  
**Categoria**: config  
**Stack**: Laravel 12, PHP 8.4, Docker  
**Severity**: Médio  
**Origem**: project-docs/lessons-learned/laravel-permissions-fix.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Intermitente  
**Impacto**: Médio

### Sintoma Observado
```
file_put_contents(/path/storage/framework/views/xxxxx.php): 
Failed to open stream: Permission denied
```

### Comportamento Esperado
Laravel deve conseguir compilar views Blade e escrever logs sem erros de permissão

### Evidência
Pastas `storage/` criadas pelo Docker com usuário `root`, Laravel local rodando com usuário `gacpac` sem permissão de escrita.

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Erro de permissão ao escrever views
2. **Por que?** Pasta storage criada pelo Docker com usuário root
3. **Por que?** Laravel local roda com usuário diferente (gacpac)
4. **Por que?** Docker e host não compartilham mesmo UID/GID
5. **Por que?** Falta de configuração de usuário consistente

### Tipo de Problema
- [ ] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```bash
# Solução 1: Desabilitar cache de views
# .env
VIEW_COMPILED_PATH=/tmp/laravel_views

# Solução 2: Usar PHP built-in server
php -S localhost:8000 -t public

# Solução 3: Recriar storage com permissões corretas
rm -rf storage/framework/{views,cache,sessions}
mkdir -p storage/framework/{views,cache,sessions}
chmod -R 755 storage

# Solução 4: Docker para desenvolvimento
docker-compose up -d sgaiti
```

### Para Produção (Dockerfile)
```dockerfile
RUN chown -R www-data:www-data /app/storage
RUN chmod -R 755 /app/storage
```

### Por Que Funciona
Evita conflito de permissões entre usuário do host e container Docker.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Rodar Laravel como root | Risco de segurança |
| Volume compartilhado com mesmo UID | Complexo de configurar |

### Validação
- [ ] Teste adicionado/atualizado
- [x] Comando de verificação: `php artisan view:clear`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Verificar permissões após Docker builds
- [ ] Nunca rodar Laravel como root em desenvolvimento
- [ ] Usar volumes Docker adequados para storage
- [ ] Configurar usuários consistentes entre Docker e host

### Regras de Ouro
1. Sempre verificar permissões após Docker builds
2. Usar VIEW_COMPILED_PATH=/tmp em desenvolvimento
3. Criar usuário Docker com mesmo UID do host

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/laravel-permissions-fix.md
- **Commit/PR**: N/A
- **Documentação**: Laravel File Storage, Docker Best Practices

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
