---
title: INSTALL
type: note
permalink: gacpac-ti/install
---

# Instalação — SGTI-GAC

Guia de instalação local com **Laravel Sail** (Docker). Tempo estimado: 15–30 min na primeira vez (build das imagens).

## Pré-requisitos

- Docker + Docker Compose
- Git
- 4 GB de RAM livre (Sail + PostgreSQL + build Vite)

> Não é preciso instalar PHP, Composer, Node ou PostgreSQL no host — tudo roda nos containers.

## Passo a passo

### 1. Clonar e entrar no projeto

```bash
git clone <url-do-repo> gacpac-ti
cd gacpac-ti
```

### 2. Arquivo de ambiente

```bash
cp .env.example .env
```

O `.env.example` já vem ajustado para Sail: `APP_PORT=8900`, banco `pgsql` no host `pgsql`, `APP_NAME=SGTI-GAC`.

### 3. Subir os containers

```bash
docker compose up -d --build
```

Serviços: `laravel.test` (PHP 8.4 + Nginx), `pgsql` (PostgreSQL), `pgadmin` (opcional, se habilitado no compose).

### 4. Chave, banco e dados iniciais

```bash
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate --seed
docker compose exec laravel.test php artisan storage:link
```

Os seeders criam papéis (`admin`, `gestor_ti`, `responsavel_setor`, `usuario`), setores de exemplo e o usuário administrador:

- **Login:** `admin@gac.pac.br`
- **Senha:** `admin123` (ambiente local — troque em produção)

### 5. Assets do frontend

```bash
docker compose exec laravel.test npm install   # só na primeira vez
docker compose exec laravel.test npm run build # gera public/build
```

Para desenvolvimento com hot-reload, use `npm run dev` em vez de `build` (o Vite dev server roda dentro do container).

### 6. Acessar e verificar

- App: http://localhost:8900/login
- Health check: http://localhost:8900/up (esperado: `200`)
- Testes: `docker compose exec laravel.test php artisan test` (esperado: 151 passando, 0 falhas)

## Ferramentas de IA (MCP — opcional, padronizado)

O arquivo vivo `.mcp.json` é **ignorado no Git** (pode conter chaves pessoais). Para ficar no padrão da equipe:

```bash
cp .mcp.json.example .mcp.json
# edite .mcp.json e preencha CONTEXT7_API_KEY com a sua chave
# reinicie o agente/IDE para carregar os servidores
```

Servidores padrão: `laravel-boost` (via Sail), `context7` (docs oficiais), `basic-memory` (memória entre sessões), `context-mode` (economia de contexto e sandbox). Nunca commite chaves reais — o template versionado mantém `CONTEXT7_API_KEY` vazia.

## Comandos do dia a dia

```bash
docker compose up -d                    # subir
docker compose down                     # parar
docker compose exec laravel.test php artisan migrate          # novas migrations
docker compose exec laravel.test php artisan test --filter=X  # um teste
docker compose logs -f laravel.test     # logs da app
```

## Problemas comuns

| Sintoma | Causa provável | Solução |
|---|---|---|
| `502` no navegador | Container ainda subindo ou build incompleto | `docker compose up -d --build` e aguarde o health do `pgsql` |
| `500` no login | `APP_KEY` ausente | `php artisan key:generate` dentro do container |
| Página sem CSS | `public/build` ausente | `npm run build` dentro do container |
| Fotos não aparecem | symlink do storage | `php artisan storage:link` dentro do container |
| Porta ocupada | outro serviço na 8900 | ajuste `APP_PORT` no `.env` e recrie (`up -d`) |

## Premissas deste guia

- Instalação **local de desenvolvimento** com Sail; produção exige HTTPS, segredos próprios e `APP_DEBUG=false`.
- Banco padrão é o PostgreSQL do compose; trocar de SGBD exige ajustar `.env` e drivers.
