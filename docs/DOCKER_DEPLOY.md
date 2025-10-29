# SGAITI-UM - Guia de Deployment com Docker

Este guia descreve como fazer o deployment do sistema SGAITI-UM (Sistema de Gestão de Ativos de TI - Unidade Militar) usando Docker.

## Arquitetura

O sistema é composto por 4 containers Docker:

1. **MySQL** - Banco de dados (porta 33060)
2. **phpMyAdmin** - Gerenciamento do banco de dados (porta 8090)
3. **Backend API** - API Node.js/Express (porta 5050)
4. **Frontend** - React SPA servido via Nginx (porta 8100)

## Pré-requisitos

- Docker Engine 20.10+
- Docker Compose 2.0+
- 2GB RAM mínimo
- 10GB espaço em disco

## Instalação e Configuração

### 1. Clone o repositório (se aplicável)

```bash
git clone <repository-url>
cd gacpac-ti
```

### 2. Configure as variáveis de ambiente

Copie o arquivo de exemplo e edite conforme necessário:

```bash
cp .env.example .env
```

Edite o arquivo `.env` e altere as seguintes variáveis **OBRIGATORIAMENTE**:

```env
# Altere estas senhas em produção!
MYSQL_ROOT_PASSWORD=sua_senha_root_segura
MYSQL_PASSWORD=sua_senha_user_segura
JWT_SECRET=seu_jwt_secret_muito_seguro_e_aleatorio

# Opcional: Configure a chave da API Gemini se usar features de IA
GEMINI_API_KEY=sua_chave_gemini_api
```

### 3. Build e start dos containers

```bash
# Build das imagens
docker-compose build

# Inicie todos os serviços
docker-compose up -d
```

### 4. Verifique o status dos containers

```bash
docker-compose ps
```

Todos os containers devem estar com status "Up".

### 5. Aguarde a inicialização do banco de dados

O MySQL pode levar alguns segundos para inicializar completamente. Verifique os logs:

```bash
docker-compose logs -f mysql
```

Aguarde até ver a mensagem: `ready for connections`.

### 6. Execute o script de seed (dados iniciais)

Popule o banco de dados com dados de exemplo:

```bash
docker-compose exec backend npm run seed
```

Você deve ver:
```
✓ Connected to database
✓ Data cleared
✓ Inserted 10 sectors
✓ Inserted 14 users
✓ Inserted 42 assets
✓ Seed completed successfully!
```

## Acesso aos Serviços

Após a inicialização bem-sucedida, acesse:

| Serviço | URL | Descrição |
|---------|-----|-----------|
| **Frontend** | http://localhost:8100 | Interface principal do sistema |
| **Backend API** | http://localhost:5050/api | API REST |
| **phpMyAdmin** | http://localhost:8090 | Gerenciamento do banco de dados |
| **Health Check** | http://localhost:5050/health | Status da API |

### Credenciais phpMyAdmin

- **Servidor**: mysql
- **Usuário**: sgaiti_user (ou conforme configurado no .env)
- **Senha**: sgaiti_pass (ou conforme configurado no .env)

## Comandos Úteis

### Ver logs de todos os serviços

```bash
docker-compose logs -f
```

### Ver logs de um serviço específico

```bash
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f mysql
```

### Parar todos os containers

```bash
docker-compose down
```

### Parar e remover todos os dados (volumes)

```bash
docker-compose down -v
```

### Reiniciar um serviço específico

```bash
docker-compose restart backend
docker-compose restart frontend
```

### Acessar o shell de um container

```bash
# Backend
docker-compose exec backend sh

# MySQL
docker-compose exec mysql bash
```

### Backup do banco de dados

```bash
docker-compose exec mysql mysqldump -u sgaiti_user -p sgaiti_db > backup.sql
```

### Restaurar backup do banco de dados

```bash
docker-compose exec -T mysql mysql -u sgaiti_user -p sgaiti_db < backup.sql
```

### Re-executar o seed (apaga todos os dados!)

```bash
docker-compose exec backend npm run seed
```

### Rebuild do frontend após mudanças no código

```bash
# Método 1: Usando o script helper
./dev-rebuild.sh

# Método 2: Comando direto
docker-compose up -d --build frontend
```

**⚠️ IMPORTANTE:** O frontend React/TypeScript precisa ser rebuilado sempre que você fizer alterações em:
- Arquivos TypeScript (.ts, .tsx)
- Tipos (types.ts)
- Componentes React
- Serviços (services/)

O backend Node.js **não** precisa rebuild (reinicia automaticamente com as mudanças).

## Endpoints da API

### Setores
- `GET /api/sectors` - Listar todos os setores
- `GET /api/sectors/:id` - Obter setor específico
- `POST /api/sectors` - Criar novo setor
- `PUT /api/sectors/:id` - Atualizar setor
- `DELETE /api/sectors/:id` - Excluir setor

### Usuários
- `GET /api/users` - Listar todos os usuários
- `GET /api/users/:id` - Obter usuário específico
- `POST /api/users` - Criar novo usuário
- `PUT /api/users/:id` - Atualizar usuário
- `DELETE /api/users/:id` - Excluir usuário

### Ativos
- `GET /api/assets` - Listar todos os ativos
- `GET /api/assets/:id` - Obter ativo específico
- `GET /api/assets/qr/:qrCode` - Buscar ativo por QR Code
- `GET /api/assets/utils/next-qr-code` - Gerar próximo QR Code
- `POST /api/assets` - Criar novo ativo
- `PUT /api/assets/:id` - Atualizar ativo
- `DELETE /api/assets/:id` - Excluir ativo
- `POST /api/assets/:id/photos` - Adicionar foto (multipart/form-data)
- `DELETE /api/assets/:id/photos/:photoId` - Excluir foto
- `POST /api/assets/:id/maintenance` - Adicionar manutenção
- `DELETE /api/assets/:id/maintenance/:maintenanceId` - Excluir manutenção

### Cautelas (Custody)
- `GET /api/custody` - Listar todas as cautelas
- `GET /api/custody/:id` - Obter cautela específica
- `POST /api/custody` - Criar nova cautela (checkout)
- `PUT /api/custody/:id/checkin` - Devolver cautela (checkin)
- `PUT /api/custody/:id` - Atualizar cautela
- `DELETE /api/custody/:id` - Excluir cautela

### Inventários
- `GET /api/inventory` - Listar todos os inventários
- `GET /api/inventory/:id` - Obter inventário específico
- `POST /api/inventory` - Criar novo inventário
- `POST /api/inventory/:id/found` - Adicionar item encontrado
- `POST /api/inventory/:id/uncatalogued` - Adicionar item não catalogado
- `PUT /api/inventory/:id/complete` - Concluir inventário
- `POST /api/inventory/:id/reopen` - Reabrir inventário
- `DELETE /api/inventory/:id` - Excluir inventário
- `DELETE /api/inventory/:id/uncatalogued/:uncataloguedId` - Excluir item não catalogado

### Dashboard
- `GET /api/dashboard/stats` - Obter estatísticas gerais

## Troubleshooting

### Container do MySQL não inicia

1. Verifique se a porta 33060 está disponível:
   ```bash
   lsof -i :33060
   ```

2. Se houver conflito, altere a porta no `docker-compose.yml`

### Erro de conexão com banco de dados

1. Verifique se o container do MySQL está rodando:
   ```bash
   docker-compose ps mysql
   ```

2. Verifique os logs do MySQL:
   ```bash
   docker-compose logs mysql
   ```

3. Aguarde alguns segundos após o start para o MySQL ficar pronto

### Backend não consegue conectar ao banco

1. Verifique as variáveis de ambiente no `.env`
2. Reinicie o backend:
   ```bash
   docker-compose restart backend
   ```

### Upload de fotos não funciona

1. Verifique se o volume `uploads` está montado:
   ```bash
   docker volume ls | grep uploads
   ```

2. Verifique permissões no container:
   ```bash
   docker-compose exec backend ls -la uploads/
   ```

### Frontend não carrega

1. Verifique se o container está rodando:
   ```bash
   docker-compose ps frontend
   ```

2. Verifique logs do Nginx:
   ```bash
   docker-compose logs frontend
   ```

3. Certifique-se de que a variável `VITE_API_URL` aponta para http://localhost:5050/api

## Deployment em Produção

### Checklist de Segurança

- [ ] Altere todas as senhas padrão no `.env`
- [ ] Configure `JWT_SECRET` com valor aleatório e seguro
- [ ] Configure CORS adequadamente (variável `CORS_ORIGIN`)
- [ ] Use HTTPS (configure reverse proxy como Nginx/Traefik)
- [ ] Configure backup automático do banco de dados
- [ ] Limite acesso ao phpMyAdmin ou desabilite em produção
- [ ] Configure firewall para restringir portas
- [ ] Use Docker secrets para informações sensíveis

### Reverse Proxy (Nginx)

Exemplo de configuração Nginx para produção:

```nginx
server {
    listen 80;
    server_name seu-dominio.com.br;

    location / {
        proxy_pass http://localhost:8100;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }

    location /api {
        proxy_pass http://localhost:5050;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }

    location /uploads {
        proxy_pass http://localhost:5050;
        proxy_set_header Host $host;
    }
}
```

### Backup Automatizado

Crie um cron job para backup diário:

```bash
0 2 * * * docker-compose -f /path/to/docker-compose.yml exec -T mysql mysqldump -u sgaiti_user -pSUA_SENHA sgaiti_db | gzip > /backup/sgaiti_$(date +\%Y\%m\%d).sql.gz
```

## Monitoramento

### Health Checks

O backend fornece um endpoint de health check:

```bash
curl http://localhost:5050/health
```

Resposta esperada:
```json
{
  "status": "OK",
  "timestamp": "2024-07-20T10:30:00.000Z"
}
```

### Métricas do Docker

```bash
docker stats
```

## Suporte

Para problemas ou dúvidas:

1. Verifique os logs: `docker-compose logs -f`
2. Consulte a documentação da aplicação
3. Verifique issues no repositório do projeto

## Licença

Este projeto é de uso interno da Força Aérea Brasileira.
