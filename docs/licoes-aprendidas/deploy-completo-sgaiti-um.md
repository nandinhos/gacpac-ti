# Deploy Completo SGAITI-UM - Lições Aprendidas

## 🎯 Resumo do Deploy Bem-Sucedido

Este documento registra todas as correções e soluções aplicadas durante o deploy completo do SGAITI-UM (SGTI-GAC) em ambiente Docker, para referência em futuros deploys.

## ✅ Problemas Resolvidos e Soluções

### 1. **Conflitos de Nomes de Rotas (Laravel)**

**Problema:** Rotas da API e Web com nomes duplicados causando erro no cache de rotas.

**Solução:**
```php
// Em routes/api.php - Prefixar todas as rotas API Resource
Route::apiResource('sectors', SectorController::class)->names([
    'index' => 'api.sectors.index',
    'store' => 'api.sectors.store',
    'show' => 'api.sectors.show',
    'update' => 'api.sectors.update',
    'destroy' => 'api.sectors.destroy'
]);
```

**Aplicar para:** sectors, users, assets, inventory

### 2. **Configuração de Portas Corretas**

**Problema:** Aplicação deveria rodar na porta 5050, mas estava configurada para 8000.

**Solução:**
```yaml
# docker-compose.yml
ports:
  - "${APP_HOST_PORT:-5050}:5050"

# backend/docker/nginx.conf  
listen 5050;

# backend/Dockerfile
EXPOSE 5050
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=5050"]
```

### 3. **Estrutura de Diretórios Laravel**

**Problema:** Diretórios `storage` e `bootstrap/cache` não existiam no projeto.

**Solução:**
```bash
mkdir -p backend/storage/framework/{cache/data,sessions,views}
mkdir -p backend/storage/{app/public,logs}
mkdir -p backend/bootstrap/cache

# Adicionar .gitignore adequados em cada diretório
```

### 4. **Ordem de Operações no Dockerfile**

**Problema:** COPY . . sobrescrevia o diretório vendor gerado pelo composer install.

**Solução:**
```dockerfile
# Ordem correta:
COPY . .
RUN composer install --optimize-autoloader --no-scripts --no-dev
RUN npm install
```

### 5. **Permissões de Arquivos e Diretórios**

**Problema:** Laravel não conseguia escrever em storage e views compiladas.

**Solução:**
```bash
# Durante deploy
docker-compose exec sgaiti chmod -R 777 /app/storage /app/bootstrap/cache /tmp
docker-compose exec sgaiti chown -R www-data:www-data /app/storage /app/bootstrap/cache

# No Dockerfile (para persistir)
RUN mkdir -p storage/framework/{cache/data,sessions,views} \
    && mkdir -p storage/{app/public,logs} \
    && mkdir -p bootstrap/cache
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache
```

### 6. **Configuração Nginx + PHP-FPM**

**Problema:** Nginx retornando 502 Bad Gateway.

**Solução:**
```nginx
# backend/docker/nginx.conf
fastcgi_pass 127.0.0.1:9000;  # TCP em vez de socket Unix
listen 5050;  # Porta correta
```

### 7. **Compilação de Assets Vite**

**Problema:** Manifest.json não encontrado, assets não compilados.

**Solução:**
```bash
# Garantir que o build aconteça após npm install
docker-compose exec sgaiti npm install
docker-compose exec sgaiti npm run build

# Verificar se manifest.json foi criado
ls -la /app/public/build/manifest.json
```

### 8. **Sincronização de Arquivos .env**

**Problema:** Dois arquivos .env com configurações diferentes causando conflitos.

**Solução:**
```yaml
# Estrutura correta:
/.env                 # Configurações Docker Compose
/backend/.env         # Configurações Laravel

# Sincronizar senhas entre os dois arquivos
DB_PASSWORD=sgaiti_pass_change_me  # Em ambos
```

### 9. **Configuração MySQL e phpMyAdmin**

**Problema:** Senhas inconsistentes entre containers e cache Laravel.

**Solução:**
```yaml
# docker-compose.yml - Usar mesma senha em todos os lugares
environment:
  MYSQL_PASSWORD: ${MYSQL_PASSWORD:-sgaiti_pass_change_me}
  PMA_PASSWORD: ${MYSQL_PASSWORD:-sgaiti_pass_change_me}
  DB_PASSWORD: ${MYSQL_PASSWORD:-sgaiti_pass_change_me}
```

## 🔄 Sequência de Deploy Recomendada

### 1. Preparação do Ambiente
```bash
# 1. Clonar projeto
git clone [repositorio]
cd [projeto]

# 2. Configurar .env files
cp .env.example .env
cp backend/.env.example backend/.env

# 3. Ajustar senhas nos dois arquivos
# Garantir que DB_PASSWORD seja igual em ambos
```

### 2. Correções de Código
```bash
# 1. Corrigir rotas API (prefixos únicos)
# 2. Verificar configurações de porta (5050)
# 3. Criar estrutura de diretórios Laravel
# 4. Ajustar Dockerfile (ordem de operações)
```

### 3. Deploy Docker
```bash
# 1. Build inicial
docker-compose up -d --build

# 2. Aguardar containers ficarem healthy
docker-compose ps

# 3. Corrigir permissões
docker-compose exec sgaiti chmod -R 777 /app/storage /app/bootstrap/cache /tmp

# 4. Compilar assets
docker-compose exec sgaiti npm install
docker-compose exec sgaiti npm run build
```

### 4. Configuração do Banco
```bash
# 1. Executar migrations
docker-compose exec sgaiti php artisan migrate:fresh --seed

# 2. Criar usuários de teste
docker-compose exec sgaiti php artisan tinker --execute="[código usuários]"

# 3. Limpar caches
docker-compose exec sgaiti php artisan config:clear
docker-compose exec sgaiti php artisan cache:clear
```

## 🎯 Credenciais Padrão para Testes

### Sistema Web
```yaml
URL: http://localhost:5050
Admin: admin001 / admin123
Comissão: comissao001 / comissao123
Usuário: user001 / user123
```

### phpMyAdmin
```yaml
URL: http://localhost:58090
User: sgaiti_user
Pass: sgaiti_pass_change_me
Database: sgaiti_db
```

## ⚠️ Problemas Conhecidos para Investigação

### Banco de Dados - Múltiplas Instâncias
**Sintoma:** phpMyAdmin mostra banco vazio mesmo com Laravel conectando corretamente.

**Possíveis Causas:**
- Volumes Docker persistindo dados antigos
- Múltiplos containers MySQL rodando
- Configurações de rede conflitantes
- phpMyAdmin conectando em instância diferente

**Investigações Futuras:**
- Mapear volumes MySQL detalhadamente
- Verificar networks Docker
- Testar com IP fixo para containers
- Implementar script de verificação de consistência

## 📝 Comandos Úteis de Debug

```bash
# Verificar estrutura de rede
docker network inspect [network-name]

# Verificar volumes persistentes
docker volume ls | grep mysql

# Testar conectividade entre containers
docker-compose exec sgaiti ping mysql

# Verificar configuração Laravel
docker-compose exec sgaiti php artisan config:show database

# Verificar logs específicos
docker-compose logs sgaiti | grep -i error
```

## 🚀 Melhorias para Próximos Deploys

1. **Script de Deploy Automatizado**
   - Automatizar correções de permissões
   - Verificar configurações antes do build
   - Validar conectividade pós-deploy

2. **Validação de Ambiente**
   - Checker de portas disponíveis
   - Verificação de dependências
   - Teste de conectividade MySQL

3. **Documentação de Troubleshooting**
   - Guia de problemas comuns
   - Scripts de diagnóstico
   - Procedimentos de rollback

---

**Data:** Dezembro 2024  
**Versão:** Laravel 12.36.0 + Inertia.js + React 18  
**Status:** ✅ Deploy bem-sucedido e totalmente funcional