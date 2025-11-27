# 🚀 Guia Rápido - SGAITI

## 🌐 Links de Acesso

| Serviço | URL | Login | Senha |
|---------|-----|-------|-------|
| **Aplicação** | [http://localhost:5050](http://localhost:5050) | `admin` | `admin123` |
| **Banco de Dados** | [http://localhost:8081](http://localhost:8081) | *(Automático)* | *(Automático)* |

---

## 👥 Usuários de Teste

| Perfil | Military ID | Senha | Função |
|--------|-------------|-------|--------|
| **Admin** | `admin` | `admin123` | Acesso Total |
| **Comissão** | `comissao001` | `comissao123` | Gestão de Inventário |
| **Usuário** | `user001` | `user123` | Visualização Básica |

---

## ⚡ Comandos Mais Usados

**Reiniciar Aplicação:**
```bash
docker-compose restart sgaiti
```

**Ver Logs:**
```bash
docker logs sgaiti-app -f
```

**Acessar Terminal da App:**
```bash
docker exec -it sgaiti-app bash
```

**Limpar Cache (se der erro):**
```bash
docker exec sgaiti-app php artisan optimize:clear
```

**Reconstruir Container (se a aplicação não carregar):**
```bash
docker-compose down sgaiti
docker-compose up -d --build sgaiti
```

---

## 🔧 Troubleshooting

**Erro 404 ao acessar a aplicação:**
1. Verifique se o container está rodando: `docker ps`
2. Reconstrua o container: `docker-compose up -d --build sgaiti`
3. Verifique os logs: `docker logs sgaiti-app -f`

**Assets não carregam (favicon.ico, index.js):**
```bash
cd backend && npm run build
docker-compose restart sgaiti
```

**Permissão negada ao fazer build:**
```bash
sudo chown -R $USER:$USER backend/public/build backend/node_modules
```
