# 🌐 CONECTIVIDADE PROJETO - SGAITI-UM

> **Data:** 2025-11-06  
> **Objetivo:** Controle total da conectividade entre serviços

## 🎯 **MAPA DE CONECTIVIDADE**

### **Arquitetura Atual**
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   DESENVOLVIMENTO   │    │      DOCKER       │    │     DATABASE     │
│   (Local Host)      │    │   (Containers)    │    │    (MySQL)       │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ Laravel:8000    │    │ Laravel:5050    │    │ MySQL:53106     │
│ Vite:5173       │◄──►│ Nginx:58100     │◄──►│ phpMyAdmin:58090│
│ Node:3000       │    │ Backend:5050    │    │ Internal:3306   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### **Configurações por Ambiente**

#### 🖥️ **Desenvolvimento Local**
```env
# backend/.env (DESENVOLVIMENTO)
DB_HOST=127.0.0.1
DB_PORT=53106
DB_DATABASE=sgaiti_db
DB_USERNAME=sgaiti_user
DB_PASSWORD=sgaiti_pass

APP_URL=http://127.0.0.1:8000
VITE_APP_URL=http://127.0.0.1:8000
```

#### 🐳 **Container Docker**
```env
# Container .env (PRODUÇÃO-LIKE)
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sgaiti_db
DB_USERNAME=sgaiti_user
DB_PASSWORD=sgaiti_pass

APP_URL=http://localhost:5050
VITE_APP_URL=http://localhost:5050
```

## 🔧 **COMANDOS DE CONECTIVIDADE**

### **Verificação Database**
```bash
# Teste conexão local
mysql -h127.0.0.1 -P53106 -usgaiti_user -psgaiti_pass -e "SELECT 'Local OK';"

# Teste conexão Docker
docker exec sgaiti-backend php -r "
try {
    \$pdo = new PDO('mysql:host=mysql;port=3306;dbname=sgaiti_db', 'sgaiti_user', 'sgaiti_pass');
    echo 'Docker DB OK\n';
} catch(Exception \$e) {
    echo 'Docker DB Error: ' . \$e->getMessage() . '\n';
}
"
```

### **Verificação Serviços**
```bash
# Status containers
docker-compose ps

# Health check URLs
curl -I http://127.0.0.1:8000     # Laravel Local
curl -I http://localhost:5050     # Laravel Docker  
curl -I http://localhost:58090    # phpMyAdmin
curl -I http://localhost:58100    # Frontend (se usado)

# Teste internal networking
docker exec sgaiti-backend php -r "echo gethostbyname('mysql');"
```

### **Reset Conectividade**
```bash
# Reset completo
docker-compose down
docker network prune -f
docker-compose up -d
sleep 15

# Verificar rede
docker network ls | grep sgaiti
docker network inspect gacpac-ti_sgaiti-network
```

## 🎯 **TROUBLESHOOTING GUIDE**

### **Problema 1: "Connection refused"**
```bash
# Sintomas
SQLSTATE[HY000] [2002] Connection refused

# Diagnóstico
docker-compose ps | grep mysql
mysql -h127.0.0.1 -P53106 -usgaiti_user -psgaiti_pass

# Solução
docker-compose restart mysql
sleep 10
```

### **Problema 2: "Name does not resolve"**
```bash
# Sintomas  
getaddrinfo for mysql failed: Name does not resolve

# Diagnóstico
cat backend/.env | grep DB_HOST

# Solução
# Local: DB_HOST=127.0.0.1
# Docker: DB_HOST=mysql
```

### **Problema 3: "Permission denied"**
```bash
# Sintomas
Permission denied storage/logs/laravel.log

# Solução
LOG_CHANNEL=stderr
VIEW_COMPILED_PATH=/tmp/laravel_views
```

## 🔄 **SCRIPT DE SWITCH AUTOMÁTICO**

### **Configuração Inteligente**
```bash
#!/bin/bash
# switch-env.sh

ENV_TYPE=$1  # "local" ou "docker"

if [ "$ENV_TYPE" = "local" ]; then
    echo "🖥️  Configurando para desenvolvimento local..."
    cd backend
    sed -i 's/DB_HOST=mysql/DB_HOST=127.0.0.1/' .env
    sed -i 's/DB_PORT=3306/DB_PORT=53106/' .env
    sed -i 's|APP_URL=.*|APP_URL=http://127.0.0.1:8000|' .env
    echo "✅ Configurado para local"
    
elif [ "$ENV_TYPE" = "docker" ]; then
    echo "🐳 Configurando para Docker..."
    cd backend
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/DB_PORT=53106/DB_PORT=3306/' .env
    sed -i 's|APP_URL=.*|APP_URL=http://localhost:5050|' .env
    echo "✅ Configurado para Docker"
    
else
    echo "❌ Uso: ./switch-env.sh [local|docker]"
fi
```

### **Uso do Script**
```bash
# Desenvolvimento local
./switch-env.sh local
php artisan serve

# Teste Docker
./switch-env.sh docker
docker-compose restart sgaiti-backend
```

## 📊 **MONITORAMENTO CONECTIVIDADE**

### **Health Check Script**
```bash
#!/bin/bash
# health-check.sh

echo "🔍 SGAITI-UM Health Check"
echo "========================="

# MySQL
if mysql -h127.0.0.1 -P53106 -usgaiti_user -psgaiti_pass -e "SELECT 1;" &>/dev/null; then
    echo "✅ MySQL: OK"
else
    echo "❌ MySQL: ERRO"
fi

# Laravel Local
if curl -s -I http://127.0.0.1:8000 | grep -q "200\|302"; then
    echo "✅ Laravel Local: OK"
else
    echo "❌ Laravel Local: ERRO"
fi

# Laravel Docker
if curl -s -I http://localhost:5050 | grep -q "200\|302"; then
    echo "✅ Laravel Docker: OK"
else
    echo "❌ Laravel Docker: ERRO"
fi

# phpMyAdmin
if curl -s -I http://localhost:58090 | grep -q "200"; then
    echo "✅ phpMyAdmin: OK"
else
    echo "❌ phpMyAdmin: ERRO"
fi

echo "========================="
echo "🏁 Health Check Complete"
```

## 📋 **CHECKLIST CONECTIVIDADE**

### **Antes de Iniciar Desenvolvimento**
- [ ] ✅ Docker MySQL rodando
- [ ] ✅ .env configurado para local
- [ ] ✅ Conexão DB testada
- [ ] ✅ Laravel serve funcionando

### **Antes de Commit**
- [ ] ✅ Teste Docker realizado
- [ ] ✅ Ambos ambientes funcionando
- [ ] ✅ Health check passando
- [ ] ✅ .env local restaurado

### **Troubleshooting**
- [ ] ✅ Containers status verificado
- [ ] ✅ Network connectivity testada
- [ ] ✅ Logs verificados
- [ ] ✅ Configurações validadas

## 🚀 **PRÓXIMOS PASSOS**

1. **Automatização**: Criar scripts de switch automático
2. **CI/CD**: Integrar health checks no pipeline
3. **Monitoring**: Dashboard de status dos serviços
4. **Documentation**: Manter este guia sempre atualizado

---

**🎯 Com este controle de conectividade, teremos desenvolvimento fluido e deploys confiáveis!**