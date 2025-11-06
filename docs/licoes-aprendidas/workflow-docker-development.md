# 🔄 WORKFLOW - Desenvolvimento Docker + Laravel

> **Data:** 2025-11-06  
> **Objetivo:** Fluxo otimizado para desenvolvimento com Docker

## 🎯 **WORKFLOW COMPLETO DE DESENVOLVIMENTO**

### **FASE 1: Setup Inicial**
```bash
# 1. Clonar/Pull projeto
git pull origin main

# 2. Setup Laravel local
cd backend
cp .env.example .env
composer install
npm install
php artisan key:generate

# 3. Configurar .env para desenvolvimento local
# DB_HOST=127.0.0.1, DB_PORT=53106

# 4. Subir dependências Docker
docker-compose up -d mysql phpmyadmin

# 5. Aguardar MySQL e migrar
sleep 15
php artisan migrate:fresh --seed

# 6. Validar ambiente
php artisan serve &
curl -I http://127.0.0.1:8000
```

### **FASE 2: Desenvolvimento Ativo**
```bash
# Desenvolvimento com hot-reload
cd backend
composer run dev  # Laravel + Vite + Queue + Logs

# OU separadamente:
php artisan serve &
npm run dev &
```

### **FASE 3: Implementação de Feature**
```bash
# 1. Criar branch
git checkout -b feature/nova-funcionalidade

# 2. Desenvolver localmente
# - Editar código
# - Testar em http://127.0.0.1:8000

# 3. Criar/atualizar testes
php artisan make:test NovaFuncionalidadeTest
# Escrever testes unitários

# 4. Validar testes
php artisan test --filter NovaFuncionalidade
```

### **FASE 4: Validação Docker (Pre-Commit)**
```bash
# 1. Salvar configuração local
cp backend/.env backend/.env.local

# 2. Configurar para Docker
docker exec sgaiti-backend sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
docker exec sgaiti-backend sed -i 's/DB_PORT=53106/DB_PORT=3306/' .env

# 3. Testar Docker
docker-compose restart sgaiti-backend
sleep 5
curl -I http://localhost:5050

# 4. Restaurar configuração local
cp backend/.env.local backend/.env
rm backend/.env.local
```

### **FASE 5: Testes Finais**
```bash
# 1. Suite completa de testes
php artisan test

# 2. Build assets
npm run build

# 3. Verificar rotas
php artisan route:list | grep -i error

# 4. Lint/Format (se configurado)
# composer run lint
# npm run lint
```

### **FASE 6: Commit & Push**
```bash
# 1. Add mudanças
git add .

# 2. Commit descritivo
git commit -m "feat: implementa campo commission_number nullable
- Adiciona suporte para conferências inopinadas
- Atualiza migration para campo nullable
- Inclui testes unitários
- Fixes #123"

# 3. Push da branch
git push origin feature/nova-funcionalidade

# 4. Criar Pull Request
# Via GitHub/GitLab interface
```

## 🔧 **COMANDOS ÚTEIS POR FASE**

### **Debug & Troubleshoot**
```bash
# Verificar status containers
docker-compose ps

# Logs em tempo real
docker-compose logs -f sgaiti-backend

# Reset completo
docker-compose down
docker-compose up -d
php artisan migrate:fresh --seed

# Verificar conectividade DB
mysql -h127.0.0.1 -P53106 -usgaiti_user -psgaiti_pass -e "SHOW TABLES;"
```

### **Testes Específicos**
```bash
# Teste específico
php artisan test --filter AssetTest

# Teste com coverage (se configurado)
php artisan test --coverage

# Teste banco específico
php artisan migrate:fresh --seed --env=testing
```

### **Build & Deploy**
```bash
# Assets produção
npm run build

# Cache otimizado
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpeza
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📋 **CHECKLIST POR FASE**

### ✅ **Setup Inicial**
- [ ] MySQL Docker funcionando
- [ ] Laravel local conectando
- [ ] Migrations executadas
- [ ] Seeds populados
- [ ] Serve respondendo

### ✅ **Desenvolvimento**
- [ ] Hot-reload funcionando
- [ ] Database local acessível
- [ ] Funcionalidade implementada
- [ ] Logs sem erros críticos

### ✅ **Pre-Commit**
- [ ] Docker funcionando
- [ ] Testes unitários passando
- [ ] Assets compilados
- [ ] Configuração local restaurada
- [ ] Branch atualizada

### ✅ **Post-Commit**
- [ ] Pull Request criado
- [ ] CI/CD passando (se configurado)
- [ ] Revisão de código solicitada
- [ ] Documentação atualizada

## 🎯 **BOAS PRÁTICAS**

### **DO's**
- ✅ Sempre testar localmente primeiro
- ✅ Usar branches para features
- ✅ Escrever testes para novas funcionalidades
- ✅ Validar Docker antes de commit
- ✅ Commits descritivos e frequentes
- ✅ Documentar mudanças complexas

### **DON'Ts**
- ❌ Nunca commitar .env
- ❌ Não pular testes unitários
- ❌ Não fazer commit diretamente na main
- ❌ Não ignorar warnings de linting
- ❌ Não misturar configurações local/Docker
- ❌ Não commitar código quebrado

## 🚀 **OTIMIZAÇÕES**

### **Scripts Auxiliares**
```bash
# Criar script setup.sh
#!/bin/bash
echo "🚀 Setting up SGAITI-UM development..."
cd backend
cp .env.example .env
composer install
npm install
php artisan key:generate
docker-compose up -d mysql
sleep 15
php artisan migrate:fresh --seed
echo "✅ Setup complete! Run: php artisan serve"
```

### **Aliases Úteis**
```bash
# Adicionar ao ~/.bashrc ou ~/.zshrc
alias sgaiti-dev="cd backend && composer run dev"
alias sgaiti-test="cd backend && php artisan test"
alias sgaiti-docker="docker-compose up -d && sleep 10"
alias sgaiti-reset="docker-compose down && docker-compose up -d && sleep 15 && cd backend && php artisan migrate:fresh --seed"
```

---

**🏆 Com este workflow, teremos desenvolvimento eficiente, testes confiáveis e deployments sem surpresas!**