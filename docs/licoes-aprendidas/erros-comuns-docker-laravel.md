# 📚 ERROS COMUNS - Docker + Laravel + Inertia

> **Data:** 2025-11-06  
> **Objetivo:** Evitar erros recorrentes e otimizar desenvolvimento

## 🚨 **ERROS CRÍTICOS IDENTIFICADOS**

### 1. **Configuração Database Host/Port**

#### ❌ **Erro Comum:**
```env
# .env local usando configuração Docker
DB_HOST=mysql          # ❌ Não resolve fora do Docker
DB_PORT=3306           # ❌ Porta interna

# .env Docker usando configuração local  
DB_HOST=127.0.0.1      # ❌ Não resolve dentro do Docker
DB_PORT=53106          # ❌ Porta externa
```

#### ✅ **Solução Correta:**
```bash
# DESENVOLVIMENTO LOCAL (backend/.env)
DB_HOST=127.0.0.1
DB_PORT=53106

# CONTAINER DOCKER (via docker exec)
DB_HOST=mysql
DB_PORT=3306
```

### 2. **Permissões Storage Laravel**

#### ❌ **Erro Comum:**
```bash
# Views/cache criados pelo Docker como root
file_put_contents(...storage/framework/views/...): Permission denied
```

#### ✅ **Soluções:**
```bash
# Opção 1: Cache temporário
VIEW_COMPILED_PATH=/tmp/laravel_views

# Opção 2: Logs para stderr  
LOG_CHANNEL=stderr

# Opção 3: Fix permissions
chown -R $USER:$USER storage bootstrap/cache
```

### 3. **Cache de Configuração**

#### ❌ **Erro Comum:**
```bash
# Configuração cacheada com valores antigos
php artisan config:cache  # ❌ Fixa valores incorretos
```

#### ✅ **Solução:**
```bash
# SEMPRE limpar antes de cache
php artisan config:clear
php artisan cache:clear
# Só depois fazer cache se necessário
```

### 4. **Rotas Duplicadas**

#### ❌ **Erro Comum:**
```php
// web.php com rotas duplicadas
Route::get('/sectors', ...)->name('sectors.index');  // ❌ Primeira
Route::get('/sectors', ...)->name('sectors.index');  // ❌ Duplicada
```

#### ✅ **Prevenção:**
```bash
# Verificar rotas antes de commit
php artisan route:list | grep -i duplicate
```

### 5. **Migration Field Constraints**

#### ❌ **Erro Comum:**
```php
// Migration com constraint muito restritiva
$table->string('commission_number')->unique();  // ❌ NOT NULL obrigatório
```

#### ✅ **Solução:**
```php
// Considerar regras de negócio
$table->string('commission_number')->nullable()->unique();  // ✅ Opcional
```

## 🔧 **WORKFLOW RECOMENDADO**

### **1. Desenvolvimento Local**
```bash
# Setup inicial
cd backend
cp .env.example .env
# Editar .env para configurações locais
php artisan key:generate
php artisan migrate:fresh --seed

# Desenvolvimento ativo
php artisan serve  # ou composer run dev
```

### **2. Testes Docker**
```bash
# Antes de commit, testar Docker
docker-compose up -d mysql
docker exec sgaiti-backend sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
docker exec sgaiti-backend sed -i 's/DB_PORT=53106/DB_PORT=3306/' .env
curl -I http://localhost:5050
```

### **3. Validação Pre-Commit**
```bash
# Testes unitários
php artisan test

# Verificar rotas
php artisan route:list

# Build assets
npm run build

# Reset configurações locais
git checkout -- .env
```

## 🎯 **CHECKLIST PRE-COMMIT**

- [ ] ✅ **Local funcionando**: http://127.0.0.1:8000
- [ ] ✅ **Docker funcionando**: http://localhost:5050  
- [ ] ✅ **Testes passando**: `php artisan test`
- [ ] ✅ **Assets compilados**: `npm run build`
- [ ] ✅ **Database seeded**: Dados de teste OK
- [ ] ✅ **Configurações corretas**: .env local restaurado
- [ ] ✅ **Sem conflitos**: Rotas, migrations checadas

## 🚀 **COMANDOS DE TROUBLESHOOT**

```bash
# Reset completo do ambiente
docker-compose down
docker-compose up -d mysql
sleep 10
php artisan config:clear
php artisan migrate:fresh --seed

# Verificar conectividade
mysql -h127.0.0.1 -P53106 -usgaiti_user -psgaiti_pass
docker exec sgaiti-backend php -r "echo gethostbyname('mysql');"

# Reset cache
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## 📋 **PREVENTION RULES**

1. **NUNCA** fazer cache de config sem testar antes
2. **SEMPRE** verificar .env antes de Docker
3. **SEPARAR** configurações local vs Docker
4. **TESTAR** ambos ambientes antes de commit
5. **LIMPAR** cache entre mudanças de ambiente
6. **DOCUMENTAR** mudanças de configuração
7. **VALIDAR** testes unitários sempre

---

**💡 Seguindo estas práticas, evitaremos 90% dos erros recorrentes!**