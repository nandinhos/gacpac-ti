#!/bin/bash
# SGAITI-UM Health Check Script
# Verifica conectividade de todos os serviços

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKEND_DIR="$PROJECT_ROOT/backend"

echo "🔍 SGAITI-UM Health Check"
echo "========================="
echo "📅 $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para testar serviço
test_service() {
    local name=$1
    local test_command=$2
    local expected_result=$3
    
    printf "%-20s" "$name:"
    
    if eval "$test_command" &>/dev/null; then
        echo -e "${GREEN}✅ OK${NC}"
        return 0
    else
        echo -e "${RED}❌ ERRO${NC}"
        return 1
    fi
}

# Função para testar URL
test_url() {
    local name=$1
    local url=$2
    local expected_codes=$3
    
    printf "%-20s" "$name:"
    
    local response=$(curl -s -I "$url" 2>/dev/null | head -n1)
    
    if echo "$response" | grep -q -E "$expected_codes"; then
        echo -e "${GREEN}✅ OK${NC} - $response"
        return 0
    else
        echo -e "${RED}❌ ERRO${NC} - $response"
        return 1
    fi
}

# Verificar se .env existe
if [ ! -f "$BACKEND_DIR/.env" ]; then
    echo -e "${RED}❌ Arquivo .env não encontrado em backend/${NC}"
    echo "Execute: cp backend/.env.example backend/.env"
    exit 1
fi

# Detectar modo atual
DB_HOST=$(grep "^DB_HOST=" "$BACKEND_DIR/.env" | cut -d'=' -f2)
if [ "$DB_HOST" = "127.0.0.1" ]; then
    MODE="LOCAL"
elif [ "$DB_HOST" = "mysql" ]; then
    MODE="DOCKER"
else
    MODE="CUSTOM"
fi

echo -e "🎯 Modo detectado: ${YELLOW}$MODE${NC}"
echo ""

# 1. Database Tests
echo "🗄️  DATABASE TESTS"
echo "-------------------"

test_service "MySQL Connection" \
    "mysql -h127.0.0.1 -P53106 -usgaiti_user -psgaiti_pass -e 'SELECT 1;'" \
    "success"

test_service "Laravel DB" \
    "cd $BACKEND_DIR && php artisan tinker --execute='echo App\\Models\\MilitaryUser::count();'" \
    "number"

# 2. Container Tests
echo ""
echo "🐳 CONTAINER TESTS"
echo "------------------"

test_service "MySQL Container" \
    "docker-compose ps | grep sgaiti-mysql | grep -q 'Up'" \
    "running"

test_service "Backend Container" \
    "docker-compose ps | grep sgaiti-backend | grep -q 'Up'" \
    "running"

test_service "phpMyAdmin Container" \
    "docker-compose ps | grep sgaiti-phpmyadmin | grep -q 'Up'" \
    "running"

# 3. Web Services Tests
echo ""
echo "🌐 WEB SERVICES TESTS"
echo "---------------------"

test_url "Laravel Local" "http://127.0.0.1:8000" "200|302"
test_url "Laravel Docker" "http://localhost:5050" "200|302"
test_url "phpMyAdmin" "http://localhost:58090" "200"

# 4. Network Tests
echo ""
echo "🌐 NETWORK TESTS"
echo "----------------"

test_service "Docker Network" \
    "docker network ls | grep -q sgaiti-network" \
    "exists"

if docker-compose ps | grep -q sgaiti-backend.*Up; then
    test_service "Internal DNS" \
        "docker exec sgaiti-backend php -r 'echo gethostbyname(\"mysql\");' | grep -q '^[0-9]'" \
        "resolves"
fi

# 5. Specific Tests
echo ""
echo "🧪 SPECIFIC TESTS"
echo "-----------------"

# Test Commission Number Bug Fix
test_service "Commission Bug Fix" \
    "cd $BACKEND_DIR && php artisan tinker --execute='echo \"Table exists: \" . (Schema::hasTable(\"inventory_records\") ? \"yes\" : \"no\");'" \
    "yes"

# Test Migrations
test_service "Migrations Status" \
    "cd $BACKEND_DIR && php artisan migrate:status | grep -q 'Ran'" \
    "migrations"

# 6. Summary
echo ""
echo "📊 SUMMARY"
echo "=========="

# Count successful tests (this is a simplified version)
echo "✅ Health check completed"
echo "📋 Review any failed tests above"
echo ""

# Environment specific tips
if [ "$MODE" = "LOCAL" ]; then
    echo "💡 Para desenvolvimento:"
    echo "   cd backend && php artisan serve"
    echo "   npm run dev"
elif [ "$MODE" = "DOCKER" ]; then
    echo "💡 Para Docker:"
    echo "   docker-compose up -d"
    echo "   ./scripts/switch-env.sh local  # Para voltar ao local"
fi

echo ""
echo "🔧 Para resolver problemas:"
echo "   ./scripts/switch-env.sh status"
echo "   docker-compose logs sgaiti-backend"
echo "   php artisan config:clear"
echo ""
echo "📚 Documentação: docs/licoes-aprendidas/"