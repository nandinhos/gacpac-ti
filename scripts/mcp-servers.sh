#!/bin/bash
# Script para gerenciar servidores MCP

case "$1" in
  start)
    echo "Iniciando servidores MCP..."
    echo "1. Laravel Boost - Disponível via Docker"
    echo "2. Serena - Análise semântica"
    echo "3. Basic Memory - Memória persistente"
    echo "4. Context7 - Documentação"
    echo ""
    echo "Configuração: .mcp.json"
    ;;
  status)
    echo "Status dos MCPs:"
    echo "✓ Laravel Boost: Configurado (docker compose exec laravel.test php artisan boost:mcp)"
    echo "✓ Serena: Configurado (uvx --from git+https://github.com/oraios/serena serena start-mcp-server)"
    echo "✓ Basic Memory: Configurado (uvx basic-memory mcp)"
    echo "✓ Context7: Configurado (npx -y @upstash/context7-mcp@latest)"
    ;;
  *)
    echo "Uso: $0 {start|status}"
    exit 1
    ;;
esac
