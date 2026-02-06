# Lição: Erro de Conexão MySQL Docker - Hostname vs IP

**Data**: 2025-11-06  
**Categoria**: config  
**Stack**: Laravel 12, PHP 8.4, MySQL 8.0, Docker  
**Severity**: Crítico  
**Origem**: project-docs/lessons-learned/mysql-docker-connection.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Intermitente  
**Impacto**: Crítico

### Sintoma Observado
```
SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo for mysql failed: Name does not resolve
```

### Comportamento Esperado
Laravel deve conectar ao MySQL independentemente de rodar dentro ou fora do Docker

### Evidência
```
Laravel rodando FORA do Docker tentando conectar ao MySQL DENTRO do Docker
Configuração .env usando hostname `mysql` (válido apenas dentro da rede Docker)
Host `mysql` não resolve quando Laravel roda diretamente no host
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Erro de conexão MySQL
2. **Por que?** Hostname "mysql" não resolve
3. **Por que?" Laravel está rodando fora do Docker
4. **Por que?** Hostname "mysql" só existe dentro da rede Docker
5. **Por que?** DNS interno do Docker não é acessível de fora

### Tipo de Problema
- [ ] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```env
# Para Laravel rodando FORA do Docker
DB_CONNECTION=mysql
DB_HOST=127.0.0.1          # localhost, não "mysql"
DB_PORT=53106              # porta mapeada do Docker
DB_DATABASE=sgaiti_db
DB_USERNAME=sgaiti_user
DB_PASSWORD=sgaiti_pass

# Para Laravel rodando DENTRO do Docker  
DB_HOST=mysql              # hostname da rede Docker
DB_PORT=3306               # porta interna do container
```

### Por Que Funciona
Quando Laravel roda fora do Docker, precisa usar o IP/porta mapeada do host. Dentro do Docker, pode usar o hostname do serviço.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Rodar Laravel sempre no Docker | Limita flexibilidade de desenvolvimento |
| Usar variável de ambiente separada | Mais complexo que necessário |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `mysql -h127.0.0.1 -P53106 -usgaiti_user -p`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Verificar se Docker MySQL está rodando (`docker-compose ps`)
- [ ] Confirmar porta 53106 está mapeada corretamente
- [ ] Usar `127.0.0.1:53106` para desenvolvimento local
- [ ] Usar `mysql:3306` para Laravel em Docker
- [ ] Limpar cache de config (`php artisan config:clear`)

### Regras de Ouro
1. Sempre verificar se serviços Docker estão UP antes de rodar migrations
2. Documentar qual ambiente está sendo usado (local vs Docker)
3. Padronizar configurações de `.env` para diferentes ambientes
4. Automatizar verificações de conectividade no setup

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/mysql-docker-connection.md
- **Commit/PR**: N/A
- **Documentação**: Docker Networking, Laravel Database Config

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
