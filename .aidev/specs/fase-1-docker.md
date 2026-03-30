# SPEC — FASE 1: Docker e Infraestrutura
**Status:** `[ ] Pendente`
**Ambiente:** Laravel Sail (Docker) — o projeto usa `docker-compose.yml` customizado com serviço `laravel.test`.
**Pré-requisito:** Fase 0 concluída.
**Checkpoint:** `docker compose build` sem erros; container sobe e `curl -I http://localhost:8900` retorna `200 OK`.

---

## Contexto

Existem 3 problemas no `Dockerfile` e 1 conflito de versão:

1. **Conflito PHP:** `composer.json` declara `"php": "^8.2"`, mas o `Dockerfile` usa `php:8.4-fpm`. Ambientes inconsistentes.
2. **Build frontend desativado:** Linha 65 do `Dockerfile` tem `# RUN npm run build` comentada — assets não são compilados no container de produção.
3. **COPY duplicado:** `docker/nginx.conf` é copiado duas vezes para o mesmo destino (linhas 68 e 71).
4. **Comentário duplicado:** `# Copy supervisor configuration` aparece duas vezes (linhas 70 e 74).

**Decisão de versão:** Atualizar `composer.json` para `^8.4` (o container já roda 8.4 com sucesso — é a versão de produção real).

> [!IMPORTANT]
> As edições dos arquivos `Dockerfile` e `composer.json` são feitas diretamente na máquina host (são arquivos do repositório).
> O rebuild do container é feito via `./vendor/bin/sail build` ou `docker compose build`.

---

## Arquivos Afetados

| Arquivo | Tipo | Ação |
|---|---|---|
| `Dockerfile` | MODIFY | Descomentar build, remover duplicatas |
| `composer.json` | MODIFY | Atualizar versão PHP de `^8.2` para `^8.4` |

---

## Ações Exatas

### Passo 1 — Parar os containers antes de editar

```bash
# Na máquina host
./vendor/bin/sail down
```

### Passo 2 — Modificar `composer.json`

**Arquivo:** `/home/gacpac/gacpac-ti/composer.json`
**Linha atual (12):**
```json
"php": "^8.2",
```
**Linha nova:**
```json
"php": "^8.4",
```

### Passo 3 — Modificar `Dockerfile`

**Arquivo:** `/home/gacpac/gacpac-ti/Dockerfile`

**Alteração 1 — Descomentar build (linha 65):**
```diff
-# RUN npm run build
+RUN npm run build
```

**Alteração 2 — Remover COPY duplicado e comentário duplicado (linhas 68–74).**

Conteúdo atual:
```dockerfile
# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default   ← REMOVER esta linha
COPY docker/nginx-main.conf /etc/nginx/nginx.conf

# Copy supervisor configuration                              ← REMOVER este comentário duplicado
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
```

Conteúdo correto após edição:
```dockerfile
# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/nginx-main.conf /etc/nginx/nginx.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
```

### Passo 4 — Rebuild do container via Sail

```bash
# Na máquina host — rebuild completo sem cache
./vendor/bin/sail build --no-cache
```

> Se `sail build` não aceitar `--no-cache`, usar diretamente:
> ```bash
> docker compose build --no-cache
> ```

### Passo 5 — Subir os containers e verificar

```bash
# Subir em background
./vendor/bin/sail up -d

# Aguardar o container estabilizar (PostgreSQL healthcheck pode levar alguns segundos)
sleep 10

# Verificar resposta da aplicação
curl -I http://localhost:8900
```

Resposta esperada: `HTTP/1.1 200 OK` ou `HTTP/1.1 302 Found` (redirect para login).

### Passo 6 — Verificar logs se houver erro

```bash
./vendor/bin/sail logs laravel.test
```

---

## Critérios de Aceite

- [ ] `composer.json` linha 12: `"php": "^8.4"`
- [ ] `Dockerfile` linha 65: `RUN npm run build` (sem `#`)
- [ ] `Dockerfile` sem COPY duplicado de `nginx.conf`
- [ ] `Dockerfile` sem comentário `# Copy supervisor configuration` duplicado
- [ ] `./vendor/bin/sail build --no-cache` retorna exit code 0
- [ ] `./vendor/bin/sail up -d` sobe sem erros
- [ ] `curl -I http://localhost:8900` retorna `HTTP 200` ou `HTTP 302`

## Commit Esperado

```
fix(docker): alinha versao php, habilita build frontend e remove duplicatas

- atualiza composer.json para php ^8.4 (alinhado com imagem do container)
- descomenta RUN npm run build no Dockerfile
- remove COPY duplicado de nginx.conf
- remove comentario duplicado de supervisor
```

## NÃO FAZER

- ❌ Não alterar `docker-compose.yml` (está correto)
- ❌ Não alterar configurações de serviços `pgsql` ou `pgadmin`
- ❌ Não rodar `php` diretamente no host — toda verificação PHP usa `./vendor/bin/sail artisan`
- ❌ Não rebaixar a imagem para `php:8.2` — a decisão é usar 8.4
- ❌ Não alterar nada em `app/`, `routes/`, `resources/` nesta fase
