#!/bin/bash
# SGAITI-UM Environment Switcher
# Uso: ./switch-env.sh [local|docker]

ENV_TYPE=$1
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKEND_DIR="$PROJECT_ROOT/backend"

echo "🎯 SGAITI-UM Environment Switcher"
echo "=================================="

if [ "$ENV_TYPE" = "local" ]; then
    echo "🖥️  Configurando para desenvolvimento local..."
    
    if [ ! -f "$BACKEND_DIR/.env" ]; then
        echo "⚠️  Arquivo .env não encontrado. Copiando de .env.example..."
        cp "$BACKEND_DIR/.env.example" "$BACKEND_DIR/.env"
    fi
    
    cd "$BACKEND_DIR"
    
    # Configurações de database
    sed -i 's/DB_HOST=mysql/DB_HOST=127.0.0.1/' .env
    sed -i 's/DB_PORT=3306/DB_PORT=53106/' .env
    
    # Configurações de URL
    sed -i 's|APP_URL=.*|APP_URL=http://127.0.0.1:8000|' .env
    sed -i 's|VITE_APP_URL=.*|VITE_APP_URL=http://127.0.0.1:8000|' .env
    
    # Configurações de logs (para evitar problemas de permissão)
    sed -i 's/LOG_CHANNEL=.*/LOG_CHANNEL=stderr/' .env
    
    echo "✅ Configurado para desenvolvimento local"
    echo "🚀 Execute: cd backend && php artisan serve"
    
elif [ "$ENV_TYPE" = "docker" ]; then
    echo "🐳 Configurando para Docker..."
    
    cd "$BACKEND_DIR"
    
    # Configurações de database  
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/DB_PORT=53106/DB_PORT=3306/' .env
    
    # Configurações de URL
    sed -i 's|APP_URL=.*|APP_URL=http://localhost:5050|' .env
    sed -i 's|VITE_APP_URL=.*|VITE_APP_URL=http://localhost:5050|' .env
    
    echo "✅ Configurado para Docker"
    echo "🚀 Execute: docker-compose restart sgaiti-backend"
    
elif [ "$ENV_TYPE" = "status" ]; then
    echo "📊 Status atual da configuração:"
    echo "================================"
    
    if [ -f "$BACKEND_DIR/.env" ]; then
        DB_HOST=$(grep "^DB_HOST=" "$BACKEND_DIR/.env" | cut -d'=' -f2)
        DB_PORT=$(grep "^DB_PORT=" "$BACKEND_DIR/.env" | cut -d'=' -f2)
        APP_URL=$(grep "^APP_URL=" "$BACKEND_DIR/.env" | cut -d'=' -f2)
        
        echo "🗄️  Database: $DB_HOST:$DB_PORT"
        echo "🌐 App URL: $APP_URL"
        
        if [ "$DB_HOST" = "127.0.0.1" ]; then
            echo "📍 Modo: DESENVOLVIMENTO LOCAL"
        elif [ "$DB_HOST" = "mysql" ]; then
            echo "📍 Modo: DOCKER"
        else
            echo "📍 Modo: PERSONALIZADO"
        fi
    else
        echo "❌ Arquivo .env não encontrado"
    fi
    
else
    echo "❌ Uso incorreto!"
    echo ""
    echo "Comandos disponíveis:"
    echo "  ./switch-env.sh local    - Configura para desenvolvimento local"
    echo "  ./switch-env.sh docker   - Configura para Docker"
    echo "  ./switch-env.sh status   - Mostra configuração atual"
    echo ""
    echo "Exemplos:"
    echo "  ./switch-env.sh local && cd backend && php artisan serve"
    echo "  ./switch-env.sh docker && docker-compose restart sgaiti-backend"
fi