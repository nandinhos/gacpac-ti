# SPEC — FASE 1: Docker e Infraestrutura
**Status:** `[ ] Pendente`
**Pré-requisito:** Fase 0 concluída.
**Checkpoint:** `docker compose build` sem erros, container sobe e `curl -I http://localhost:8900` retorna `200 OK`.

---

## Contexto

Existem 3 problemas no `Dockerfile` e 1 conflito de versão:

1. **Conflito PHP:** `composer.json` declara `"php": "^8.2"`, mas o `Dockerfile` usa `php:8.4-fpm`. Ambientes inconsistentes.
2. **Build frontend desativado:** Linha 65 do `Dockerfile` tem `# RUN npm run build` comentada — assets não são compilados no container de produção.
3. **COPY duplicado:** `docker/nginx.conf` é copiado duas vezes para o mesmo destino (linhas 68 e 71).
4. **Comentário duplicado:** `# Copy supervisor configuration` aparece duas vezes (linhas 70 e 74).

**Decisão de versão:** Atualizar `composer.json` para `^8.4` (o container já roda 8.4 com sucesso — é a versão de produção real).

---

## Arquivos Afetados

| Arquivo | Tipo | Ação |
|---|---|---|
| `Dockerfile` | MODIFY | Descomentar build, remover duplicatas |
| `composer.json` | MODIFY | Atualizar versão PHP de `^8.2` para `^8.4` |

---

## Ações Exatas

### Passo 1 — Modificar `composer.json`

**Arquivo:** `/home/gacpac/gacpac-ti/composer.json`
**Linha atual (12):**
```json
"php": "^8.2",
```
**Linha nova:**
```json
"php": "^8.4",
```

### Passo 2 — Modificar `Dockerfile`

**Arquivo:** `/home/gacpac/gacpac-ti/Dockerfile`

**Alteração 1 — Descomentar build (linha 65):**
```diff
-# RUN npm run build
+RUN npm run build
```

**Alteração 2 — Remover COPY duplicado (linhas 68-74).**

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

### Passo 3 — Testar o Build

```bash
docker compose build --no-cache
```

Deve completar sem erros. Verificar especialmente:
- Etapa `npm run build` — deve compilar os assets
- Etapa `composer install` — deve aceitar PHP 8.4

### Passo 4 — Testar o Container

```bash
docker compose up -d
sleep 5
curl -I http://localhost:8900
```

Resposta esperada: `HTTP/1.1 200 OK` ou redirect `301/302` para `/login`.

---

## Critérios de Aceite

- [ ] `composer.json` linha 12: `"php": "^8.4"`
- [ ] `Dockerfile` linha 65: `RUN npm run build` (sem `#`)
- [ ] `Dockerfile` sem COPY duplicado de `nginx.conf`
- [ ] `Dockerfile` sem comentário `# Copy supervisor configuration` duplicado
- [ ] `docker compose build --no-cache` retorna sucesso (exit code 0)
- [ ] `curl -I http://localhost:8900` retorna `HTTP 200` ou `HTTP 302`

## Commit Esperado

```
fix(docker): alinha versao php, habilita build frontend e remove duplicatas

- atualiza composer.json para php ^8.4 (alinhado com imagem do container)
- descomenta RUN npm run build no Dockerfile
- remove COPY duplicado de nginx.conf (linhas 71)
- remove comentario duplicado de supervisor
```

## NÃO FAZER

- ❌ Não alterar `docker-compose.yml` (está correto)
- ❌ Não alterar configurações de serviços `pgsql` ou `pgadmin`
- ❌ Não alterar nada em `app/`, `routes/`, `resources/` nesta fase
- ❌ Não rebaixar a imagem para `php:8.2` — a decisão é usar 8.4
