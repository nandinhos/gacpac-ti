#!/bin/bash
# Script para rebuild do frontend durante desenvolvimento

echo "🔄 Rebuilding frontend..."
docker-compose up -d --build frontend

echo ""
echo "✅ Frontend rebuild concluído!"
echo ""
echo "📍 Acesse: http://localhost:8100"
echo ""
echo "💡 Dica: Use este script sempre que alterar código TypeScript/React"
echo "   Exemplo: ./dev-rebuild.sh"
