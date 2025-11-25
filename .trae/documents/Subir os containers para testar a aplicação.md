## Objetivo
Trocar a exposição do backend da porta 8000 para uma porta alta e subir os containers para testar a aplicação, com verificação automática de disponibilidade de porta.

## Mudanças Propostas
1. docker-compose.yml
   - Alterar `sgaiti.ports` de `"${APP_HOST_PORT:-8000}:8000"` para `"${APP_HOST_PORT:-55050}:8000"` (mantendo a variável `APP_HOST_PORT`, mas com padrão alto).
2. deploy.sh
   - Substituir `DEFAULT_BACKEND_HOST_PORT` por `DEFAULT_APP_HOST_PORT=55050`.
   - Calcular `APP_HOST_PORT` com `find_free_port` (em vez de `BACKEND_HOST_PORT`).
   - Atualizar o `.env` gravando `APP_HOST_PORT` e `VITE_API_URL=http://localhost:${APP_HOST_PORT}/api`.
   - Remover/ignorar `BACKEND_HOST_PORT` para evitar inconsistência (opcional: manter apenas por retrocompatibilidade, mas não usar).
3. .env (raiz)
   - Garantir que exista a chave `APP_HOST_PORT` (será gerada/atualizada pelo deploy).

## Execução (após aplicar as mudanças)
1. Preparação
   - Verificar Docker e Compose instalados.
   - Garantir `.env` presente na raiz (o `deploy.sh` cria se faltar).
2. Subida dos serviços
   - Executar `./deploy.sh` para detectar portas livres, atualizar `.env`, construir e subir os containers.
   - Alternativa manual: `docker compose build && docker compose up -d` (após definir `APP_HOST_PORT` no `.env`).
3. Migrações/Seed
   - `docker compose exec sgaiti php artisan migrate --force`
   - (Opcional) `docker compose exec sgaiti php artisan db:seed --force`
4. Validação
   - Backend: acessar `http://localhost:${APP_HOST_PORT}`.
   - phpMyAdmin: `http://localhost:${PHPMYADMIN_HOST_PORT:-58090}`.
   - Conferir `docker compose ps` e logs se necessário.

## Observações
- O serviço se chama `sgaiti` e o container `sgaiti-app`; use o nome do serviço para `exec`.
- O script atual já possui verificação de portas via `ss`; a mudança concentra a variável em `APP_HOST_PORT` para eliminar divergências entre script e compose.

## Resultado Esperado
- Containers rodando com o backend exposto em uma porta alta disponível (padrão 55050, ajustado automaticamente se ocupada).
- Aplicação acessível e banco migrado/seed aplicado.

## Próximo Passo
Confirme para eu aplicar essas mudanças e executar o plano agora (rodar `deploy.sh`, subir containers e validar).