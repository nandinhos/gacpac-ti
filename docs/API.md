# 🔗 API Documentation (gacpac-ti)

A API do sistema é protegida por **Laravel Sanctum** e utiliza rate limiting de 60 requisições por minuto.

## Autenticação

Envie o token no Header: `Authorization: Bearer <token>`

## Endpoints Principais

### Usuário & Sessão
- `GET /api/me` - Dados do usuário logado
- `GET /api/users` - Lista de usuários (requer `users.manage`)

### Ativos (Assets)
- `GET /api/assets` - Lista de ativos (filtrável por categoria, setor, busca)
- `POST /api/assets` - Criação de novo ativo
- `GET /api/assets/qr/{code}` - Busca detalhada via QR Code
- `GET /api/assets/{id}/maintenance` - Histórico de manutenção do ativo

### Cautelas (Custody)
- `GET /api/custody` - Lista de cautelas ativas
- `PUT /api/custody/{id}/checkin` - Realiza a devolução (baixa) de um item

### Inventário & Manutenção
- `GET /api/inventory` - Lista de inventários
- `GET /api/maintenance/upcoming` - Manutenções programadas para os próximos 30 dias

## Formato de Resposta

Todas as respostas seguem o padrão JSON Resource:

```json
{
  "data": {
    "id": 1,
    "name": "NoteBook Dell",
    "status": "DISPONIVEL",
    ...
  },
  "links": { ... },
  "meta": { ... }
}
```

## Erros Comuns

- `401 Unauthorized`: Token ausente ou inválido.
- `403 Forbidden`: Usuário autenticado mas sem a permissão necessária no Spatie.
- `422 Unprocessable Entity`: Falha na validação dos dados enviados.
- `429 Too Many Requests`: Limite de throttling atingido.
