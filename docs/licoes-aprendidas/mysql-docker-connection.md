# 📚 LIÇÃO APRENDIDA - Conexão MySQL Docker

> **Data:** 2025-11-06  
> **Problema:** Erro de conexão MySQL com Laravel

## 🐛 **PROBLEMA IDENTIFICADO**

### Sintomas
```
SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo for mysql failed: Name does not resolve
```

### Causa Raiz
- Laravel rodando **fora do Docker** tentando conectar ao MySQL **dentro do Docker**
- Configuração `.env` usando hostname `mysql` (válido apenas dentro da rede Docker)
- Host `mysql` não resolve quando Laravel roda diretamente no host

## ✅ **SOLUÇÃO IMPLEMENTADA**

### Passos da Resolução
1. ✅ **Verificação do MySQL**: Container estava rodando corretamente
2. ✅ **Correção do .env**: Alterado `DB_HOST=mysql` para `DB_HOST=127.0.0.1` e `DB_PORT=53106`
3. ✅ **Cache Clear**: `php artisan config:clear` para limpar cache de configuração
4. ✅ **Teste de Conexão**: `mysql -h127.0.0.1 -P53106` funcionou corretamente
5. ✅ **Migration Fresh**: `DB_HOST=127.0.0.1 DB_PORT=53106 php artisan migrate:fresh --seed`

### Resultado Final
- ✅ Todas as 18 migrations executadas com sucesso
- ✅ Seeders executados (Sectors, MilitaryUsers, Assets, CustodyLogs, Inventory)
- ✅ Campo `commission_number` agora é NULLABLE conforme regra de negócio

### Configuração .env Correta
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

## 🎯 **REGRAS PARA EVITAR O ERRO**

### 1. **Laravel Local + MySQL Docker**
```env
DB_HOST=127.0.0.1          # ou localhost
DB_PORT=53106              # porta mapeada no docker-compose.yml
```

### 2. **Laravel Docker + MySQL Docker**
```env
DB_HOST=mysql              # nome do serviço no docker-compose.yml
DB_PORT=3306               # porta interna do container
```

### 3. **Verificação Rápida**
```bash
# Testar conexão MySQL
docker-compose ps                    # verificar se MySQL está UP
mysql -h127.0.0.1 -P53106 -usgaiti_user -p  # testar conexão local
```

## 🚀 **COMANDOS DE TROUBLESHOOT**

```bash
# 1. Verificar status dos containers
docker-compose ps

# 2. Verificar logs do MySQL
docker-compose logs mysql

# 3. Testar conexão direta
mysql -h127.0.0.1 -P53106 -usgaiti_user -psgaiti_pass

# 4. Verificar configuração Laravel
cd backend && php artisan config:clear
cd backend && php artisan migrate:status
```

## 📋 **CHECKLIST DE CONFIGURAÇÃO**

- [ ] Docker MySQL está rodando (`docker-compose ps`)
- [ ] Porta 53106 está mapeada corretamente
- [ ] `.env` usa `127.0.0.1:53106` para desenvolvimento local
- [ ] `.env` usa `mysql:3306` para Laravel em Docker
- [ ] Cache de config limpo (`php artisan config:clear`)

## 🎓 **LIÇÕES PARA O FUTURO**

1. **Sempre verificar** se os serviços Docker estão UP antes de rodar migrations
2. **Documentar** qual ambiente está sendo usado (local vs Docker)
3. **Padronizar** configurações de `.env` para diferentes ambientes
4. **Automatizar** verificações de conectividade no setup

---

**💡 Esta lição nos ajudará a evitar esse erro recorrente e otimizar nosso fluxo de desenvolvimento!**