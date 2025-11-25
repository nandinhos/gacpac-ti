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

---

## ⚠️ Notas Importantes
1. O phpMyAdmin antigo (porta 58090) foi removido. **Use apenas a porta 8081**.
2. O banco de dados já possui **42 ativos** cadastrados.
3. Não rode `migrate:fresh` a menos que queira apagar todos esses dados.
