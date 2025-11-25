# 🗄️ PROBLEMA: Múltiplas Instâncias MySQL - Estudo de Caso

## 🔍 **SINTOMA IDENTIFICADO**

Durante o deploy do SGAITI-UM, encontramos uma situação onde:
- ✅ **Laravel conecta com sucesso** e vê todas as tabelas/dados
- ❌ **phpMyAdmin mostra banco vazio** mesmo usando as mesmas credenciais
- ✅ **Migrations executam corretamente** 
- ❌ **Interface web do phpMyAdmin não reflete os dados**

## 🧩 **EVIDÊNCIAS COLETADAS**

### Laravel (Funcionando)
```bash
docker-compose exec sgaiti php artisan tinker --execute="
echo 'Laravel conectado em: ' . config('database.connections.mysql.host');
echo 'Tabelas: ' . count(DB::select('SHOW TABLES'));
echo 'Usuários: ' . DB::table('military_users')->count();
"
# Resultado: 21 tabelas, 17 usuários
```

### MySQL Direto (Funcionando)
```bash
docker-compose exec mysql mysql -u sgaiti_user -p'sgaiti_pass_change_me' sgaiti_db -e "
SELECT COUNT(*) as tabelas FROM information_schema.tables WHERE table_schema='sgaiti_db';
SELECT COUNT(*) as usuarios FROM military_users;
"
# Resultado: 21 tabelas, 17 usuários
```

### phpMyAdmin (Mostrando Vazio)
- Interface: http://localhost:58090
- Credenciais: sgaiti_user / sgaiti_pass_change_me
- Database: sgaiti_db
- Resultado: "Nenhuma tabela encontrada no banco de dados"

## 🔬 **HIPÓTESES INVESTIGADAS**

### 1. **Problema de Configuração PMA_HOST**
```bash
docker-compose exec phpmyadmin env | grep PMA
# PMA_HOST=mysql ✅ Correto
# PMA_USER=sgaiti_user ✅ Correto  
# PMA_PASSWORD=sgaiti_pass_change_me ✅ Correto
```

### 2. **Problema de Rede Docker**
```bash
docker network inspect gacpac-ti_sgaiti-network
# Todos os containers na mesma rede ✅
# IPs resolvendo corretamente ✅
```

### 3. **Múltiplos Containers MySQL**
```bash
docker ps -a | grep mysql
# Apenas um container MySQL ativo ✅
# Nome correto: mysql (não sgaiti-mysql) ✅
```

### 4. **Problema de Volumes Persistentes**
```bash
docker volume ls | grep mysql
# Volume criado e mapeado corretamente ✅
```

### 5. **Cache de Configuração phpMyAdmin**
```bash
docker-compose restart phpmyadmin
# Mesmo após restart, problema persiste ❌
```

## 🎯 **TEORIAS PARA INVESTIGAÇÃO FUTURA**

### Teoria A: Isolation de Databases
**Hipótese:** Laravel e phpMyAdmin conectando em databases diferentes dentro da mesma instância MySQL.

**Investigar:**
```sql
-- Verificar databases existentes
SHOW DATABASES;

-- Verificar qual database cada conexão usa por padrão
SELECT DATABASE();

-- Verificar se existe outro database com nome similar
SHOW DATABASES LIKE '%sgaiti%';
```

### Teoria B: Problema de Charset/Collation
**Hipótese:** Diferenças de charset fazendo phpMyAdmin não reconhecer as tabelas.

**Investigar:**
```sql
-- Verificar charset do database
SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME 
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = 'sgaiti_db';

-- Verificar charset das tabelas
SELECT TABLE_NAME, TABLE_COLLATION 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'sgaiti_db';
```

### Teoria C: Privilégios de Usuário Diferenciados
**Hipótese:** Usuário tem privilégios diferentes para diferentes tipos de conexão.

**Investigar:**
```sql
-- Verificar privilégios completos do usuário
SHOW GRANTS FOR 'sgaiti_user'@'%';
SHOW GRANTS FOR 'sgaiti_user'@'localhost';
SHOW GRANTS FOR 'sgaiti_user'@'172.18.0.3';

-- Verificar informações de conexão ativa
SELECT USER(), CONNECTION_ID(), HOST(), DATABASE();
```

### Teoria D: Problema de Transações/Isolation Level
**Hipótese:** Diferenças no nível de isolamento de transações.

**Investigar:**
```sql
-- Verificar isolation level
SELECT @@transaction_isolation;

-- Verificar transações ativas
SELECT * FROM information_schema.INNODB_TRX;
```

## 📋 **SCRIPT DE DIAGNÓSTICO COMPLETO**

```bash
#!/bin/bash
echo "=== DIAGNÓSTICO MYSQL MÚLTIPLAS INSTÂNCIAS ==="

echo "1. Containers MySQL ativos:"
docker ps | grep mysql

echo "2. Configuração phpMyAdmin:"
docker-compose exec phpmyadmin env | grep PMA

echo "3. Teste Laravel:"
docker-compose exec sgaiti php artisan tinker --execute="
echo 'Host: ' . config('database.connections.mysql.host');
echo 'Database: ' . config('database.connections.mysql.database');
echo 'Tabelas: ' . count(DB::select('SHOW TABLES'));
"

echo "4. Teste MySQL direto:"
docker-compose exec mysql mysql -u sgaiti_user -p'sgaiti_pass_change_me' -e "
USE sgaiti_db;
SELECT 'MySQL direto' as source, COUNT(*) as tabelas 
FROM information_schema.tables 
WHERE table_schema='sgaiti_db';
"

echo "5. Teste conectividade phpMyAdmin -> MySQL:"
docker-compose exec phpmyadmin ping -c 1 mysql

echo "6. Volumes MySQL:"
docker volume ls | grep mysql
docker volume inspect gacpac-ti_mysql_data
```

## 🔧 **SOLUÇÕES TEMPORÁRIAS APLICADAS**

### Solução 1: Rebuild Completo
```bash
# Reconstruir com volumes limpos
docker-compose down -v
docker-compose up -d --build

# Repopular banco
docker-compose exec sgaiti php artisan migrate:fresh --seed
```

### Solução 2: Popular Banco via phpMyAdmin
```bash
# Se phpMyAdmin conseguir conectar mas não ver dados
# Executar migrations diretamente via interface SQL do phpMyAdmin
```

## 📚 **LIÇÕES PARA PRÓXIMOS CASOS**

1. **Implementar diagnóstico automático** antes do deploy
2. **Criar script de validação** pós-deploy
3. **Mapear volumes explicitamente** no docker-compose
4. **Implementar health checks** mais robustos
5. **Documentar rede Docker** detalhadamente

## 🎯 **STATUS DO PROBLEMA**

- **Impacto:** ❌ Baixo (sistema funciona, apenas interface phpMyAdmin afetada)
- **Workaround:** ✅ Populamos banco diretamente, sistema operacional  
- **Urgência:** 🟡 Média (investigar em próximas iterações)
- **Complexidade:** 🔴 Alta (requer análise profunda de Docker networking)

---

**📝 Nota:** Este caso será usado como estudo para melhorar nossa compreensão de isolamento de containers e configuração de redes Docker em projetos futuros.

**Data:** Dezembro 2024  
**Status:** Documentado para investigação futura