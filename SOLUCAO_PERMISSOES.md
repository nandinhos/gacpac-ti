# 🔧 Solução de Permissões e Ambiente de Desenvolvimento

## 📋 Diagnóstico

Você já possui as configurações corretas:
- ✅ Usuário `gacpac` já está no grupo `sudo`
- ✅ Usuário `gacpac` já está no grupo `docker`
- ✅ Docker instalado e funcionando
- ✅ Permissões dos arquivos corretas (owner: gacpac)

**Problema Real**: Você está tentando usar comandos que devem ser executados **dentro do container Docker**, não no host.

---

## 🐳 Usando Composer com Docker (RECOMENDADO)

### Opção 1: Executar Composer dentro do Container

```bash
# Instalar pacotes Laravel/PHP
docker exec sgaiti-app composer require laravel/mcp

# Atualizar dependências
docker exec sgaiti-app composer update

# Instalar todas as dependências
docker exec sgaiti-app composer install
```

### Opção 2: Criar Alias para Facilitar

Adicione ao seu `~/.bashrc`:

```bash
# Alias para Composer via Docker
alias composer='docker exec sgaiti-app composer'

# Alias para Artisan via Docker
alias artisan='docker exec sgaiti-app php artisan'

# Alias para NPM via Docker
alias npm='docker exec sgaiti-app npm'
```

Depois execute:
```bash
source ~/.bashrc
```

Agora você pode usar:
```bash
composer require laravel/mcp
artisan migrate
npm run build
```

---

## 🔐 Corrigir Permissões de Arquivos

Se você está tendo problemas para salvar arquivos, execute:

```bash
# Corrigir permissões do projeto inteiro
sudo chown -R $USER:$USER /home/gacpac/projects/gacpac-ti

# Corrigir permissões específicas do Laravel
sudo chown -R $USER:$USER /home/gacpac/projects/gacpac-ti/backend/storage
sudo chown -R $USER:$USER /home/gacpac/projects/gacpac-ti/backend/bootstrap/cache
sudo chmod -R 775 /home/gacpac/projects/gacpac-ti/backend/storage
sudo chmod -R 775 /home/gacpac/projects/gacpac-ti/backend/bootstrap/cache
```

---

## 💻 Instalar Composer Localmente (OPCIONAL)

Se você realmente precisa do Composer no host (não recomendado para este projeto):

```bash
# Instalar Composer globalmente
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verificar instalação
composer --version
```

---

## 🚫 NÃO Adicionar ao Sudo (Não Recomendado)

**IMPORTANTE**: Você **JÁ ESTÁ** no grupo `sudo`. O problema não é falta de privilégios.

Se você executar comandos com `sudo` no projeto Docker, vai criar arquivos com permissões de root, causando **mais problemas**.

### Por que NÃO usar sudo no desenvolvimento:
1. ❌ Cria arquivos com owner `root` em vez de `gacpac`
2. ❌ Quebra permissões do Docker
3. ❌ Causa conflitos com o IDE/Editor
4. ❌ Risco de segurança desnecessário

---

## ✅ Comandos Corretos para Usar

### Desenvolvimento Laravel (com Docker)

```bash
# Instalar pacote PHP
docker exec sgaiti-app composer require nome/pacote

# Executar migrations
docker exec sgaiti-app php artisan migrate

# Limpar cache
docker exec sgaiti-app php artisan optimize:clear

# Acessar terminal do container
docker exec -it sgaiti-app bash
```

### Gerenciar Containers

```bash
# Ver containers rodando
docker ps

# Reiniciar aplicação
docker-compose restart sgaiti

# Ver logs
docker logs sgaiti-app -f

# Parar tudo
docker-compose down

# Iniciar tudo
docker-compose up -d
```

---

## 🔍 Verificar Permissões Atuais

```bash
# Ver grupos do usuário
groups

# Ver permissões do projeto
ls -la /home/gacpac/projects/gacpac-ti

# Ver permissões do backend
ls -la /home/gacpac/projects/gacpac-ti/backend
```

---

## 📝 Resumo da Solução

1. **Use comandos dentro do container**: `docker exec sgaiti-app composer ...`
2. **Crie aliases** para facilitar o uso diário
3. **Corrija permissões** se necessário com `chown -R $USER:$USER`
4. **NÃO use sudo** para comandos do projeto
5. **NÃO instale Composer localmente** (use o do container)

---

## 🆘 Se Ainda Tiver Problemas

Execute este script de diagnóstico:

```bash
echo "=== Diagnóstico de Ambiente ==="
echo "Usuário: $(whoami)"
echo "Grupos: $(groups)"
echo "Docker: $(docker --version)"
echo "Container rodando: $(docker ps --filter name=sgaiti-app --format '{{.Names}} - {{.Status}}')"
echo "Permissões do projeto:"
ls -ld /home/gacpac/projects/gacpac-ti
ls -ld /home/gacpac/projects/gacpac-ti/backend
```
