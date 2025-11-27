#!/bin/bash

echo "🔧 Configurando ambiente de desenvolvimento..."
echo ""

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Corrigir permissões do projeto
echo -e "${YELLOW}1. Corrigindo permissões do projeto...${NC}"
sudo chown -R $USER:$USER /home/gacpac/projects/gacpac-ti
sudo chmod -R 775 /home/gacpac/projects/gacpac-ti/backend/storage 2>/dev/null || true
sudo chmod -R 775 /home/gacpac/projects/gacpac-ti/backend/bootstrap/cache 2>/dev/null || true
echo -e "${GREEN}✓ Permissões corrigidas${NC}"
echo ""

# 2. Verificar se Docker está rodando
echo -e "${YELLOW}2. Verificando Docker...${NC}"
if ! docker ps &> /dev/null; then
    echo -e "${RED}✗ Docker não está rodando ou você não tem permissão${NC}"
    echo "Execute: sudo systemctl start docker"
    exit 1
fi
echo -e "${GREEN}✓ Docker está rodando${NC}"
echo ""

# 3. Verificar se container está rodando
echo -e "${YELLOW}3. Verificando container sgaiti-app...${NC}"
if ! docker ps | grep -q sgaiti-app; then
    echo -e "${YELLOW}⚠ Container não está rodando. Iniciando...${NC}"
    docker-compose up -d
    sleep 5
fi

if docker ps | grep -q sgaiti-app; then
    echo -e "${GREEN}✓ Container sgaiti-app está rodando${NC}"
else
    echo -e "${RED}✗ Não foi possível iniciar o container${NC}"
    exit 1
fi
echo ""

# 4. Configurar aliases
echo -e "${YELLOW}4. Configurando aliases no ~/.bashrc...${NC}"

# Backup do bashrc
cp ~/.bashrc ~/.bashrc.backup.$(date +%Y%m%d_%H%M%S)

# Remover aliases antigos se existirem
sed -i '/# SGAITI Docker Aliases/,/# End SGAITI Docker Aliases/d' ~/.bashrc

# Adicionar novos aliases
cat >> ~/.bashrc << 'EOF'

# SGAITI Docker Aliases
alias composer='docker exec sgaiti-app composer'
alias artisan='docker exec sgaiti-app php artisan'
alias npm-docker='docker exec sgaiti-app npm'
alias sgaiti-bash='docker exec -it sgaiti-app bash'
alias sgaiti-logs='docker logs sgaiti-app -f'
alias sgaiti-restart='docker-compose restart sgaiti'
# End SGAITI Docker Aliases
EOF

echo -e "${GREEN}✓ Aliases configurados${NC}"
echo ""

# 5. Verificar Composer no container
echo -e "${YELLOW}5. Verificando Composer no container...${NC}"
COMPOSER_VERSION=$(docker exec sgaiti-app composer --version 2>/dev/null || echo "não instalado")
echo "Composer: $COMPOSER_VERSION"
echo ""

# 6. Mostrar status
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✓ Configuração concluída!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "📝 Aliases criados:"
echo "  - composer      → docker exec sgaiti-app composer"
echo "  - artisan       → docker exec sgaiti-app php artisan"
echo "  - npm-docker    → docker exec sgaiti-app npm"
echo "  - sgaiti-bash   → docker exec -it sgaiti-app bash"
echo "  - sgaiti-logs   → docker logs sgaiti-app -f"
echo "  - sgaiti-restart → docker-compose restart sgaiti"
echo ""
echo "⚡ Para ativar os aliases agora, execute:"
echo -e "${YELLOW}source ~/.bashrc${NC}"
echo ""
echo "📦 Exemplos de uso:"
echo "  composer require laravel/mcp"
echo "  artisan migrate"
echo "  npm-docker run build"
echo "  sgaiti-bash"
echo ""

# 7. Diagnóstico
echo -e "${YELLOW}📊 Diagnóstico do ambiente:${NC}"
echo "Usuário: $(whoami)"
echo "Grupos: $(groups)"
echo "Docker: $(docker --version)"
echo "Container: $(docker ps --filter name=sgaiti-app --format '{{.Names}} - {{.Status}}')"
echo ""
echo "Permissões do projeto:"
ls -ld /home/gacpac/projects/gacpac-ti
ls -ld /home/gacpac/projects/gacpac-ti/backend 2>/dev/null || echo "  backend/ não encontrado"
