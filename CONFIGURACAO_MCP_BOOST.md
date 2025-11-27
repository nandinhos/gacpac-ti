# 🔧 Configuração do Laravel Boost MCP no Antigravity

## ❌ Problema

A configuração padrão não funciona porque o PHP está **dentro do container Docker**, não no host:

```json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "php",  // ❌ PHP não existe no host
            "args": [
                "artisan",
                "boost:mcp"
            ]
        }
    }
}
```

**Erro**: `exec: "php": executable file not found in $PATH`

---

## ✅ Solução 1: Executar via Docker (RECOMENDADO)

Use esta configuração no arquivo `~/.gemini/antigravity/mcp_config.json`:

```json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "docker",
            "args": [
                "exec",
                "-i",
                "sgaiti-app",
                "php",
                "artisan",
                "boost:mcp"
            ],
            "cwd": "/home/gacpac/projects/gacpac-ti/backend"
        }
    }
}
```

### Explicação:
- **command**: `docker` (executável disponível no host)
- **args**: Executa `php artisan boost:mcp` dentro do container `sgaiti-app`
- **-i**: Modo interativo (necessário para MCP)
- **cwd**: Diretório de trabalho (pasta do backend)

---

## ✅ Solução 2: Instalar PHP no Host (NÃO RECOMENDADO)

Se você realmente quiser instalar PHP no host (não recomendado para este projeto):

```bash
sudo apt update
sudo apt install php-cli php-mbstring php-xml php-curl
```

Depois use a configuração original:

```json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "php",
            "args": [
                "artisan",
                "boost:mcp"
            ],
            "cwd": "/home/gacpac/projects/gacpac-ti/backend"
        }
    }
}
```

**Desvantagens**:
- ❌ Versão do PHP pode ser diferente do container
- ❌ Precisa instalar extensões PHP manualmente
- ❌ Pode causar conflitos de dependências
- ❌ Não usa o ambiente isolado do Docker

---

## 🧪 Testando a Configuração

Depois de configurar, teste se o MCP está funcionando:

```bash
# Testar manualmente o comando
docker exec -i sgaiti-app php artisan boost:mcp

# Ou se instalou PHP no host:
cd /home/gacpac/projects/gacpac-ti/backend
php artisan boost:mcp
```

---

## 📝 Configuração Completa Recomendada

Arquivo: `~/.gemini/antigravity/mcp_config.json`

```json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "docker",
            "args": [
                "exec",
                "-i",
                "sgaiti-app",
                "php",
                "artisan",
                "boost:mcp"
            ],
            "cwd": "/home/gacpac/projects/gacpac-ti/backend",
            "env": {}
        }
    }
}
```

---

## 🔍 Verificação

Para verificar se o container está rodando:

```bash
docker ps --filter name=sgaiti-app
```

Se o container não estiver rodando, inicie-o:

```bash
cd /home/gacpac/projects/gacpac-ti
docker-compose up -d
```

---

## 🆘 Troubleshooting

### Erro: "container not found"
**Solução**: Verifique o nome correto do container
```bash
docker ps --format '{{.Names}}'
```

### Erro: "permission denied"
**Solução**: Verifique se seu usuário está no grupo docker
```bash
groups | grep docker
```

### Erro: "artisan not found"
**Solução**: Verifique o diretório de trabalho (cwd)
```bash
docker exec sgaiti-app ls -la /var/www/html
```

---

## ✅ Resumo

**Use esta configuração**:

```json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "docker",
            "args": ["exec", "-i", "sgaiti-app", "php", "artisan", "boost:mcp"],
            "cwd": "/home/gacpac/projects/gacpac-ti/backend"
        }
    }
}
```

Isso executará o Laravel Boost MCP **dentro do container Docker**, onde o PHP e todas as dependências estão instaladas corretamente! 🚀
