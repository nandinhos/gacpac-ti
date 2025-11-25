# 📘 Documentação de Banco de Dados e Gerenciamento (SGAITI)

## 🚀 Acesso ao Gerenciador (phpMyAdmin)

O phpMyAdmin foi reconfigurado para **login automático** e acesso direto ao banco correto.

- **URL de Acesso:** [http://localhost:8081](http://localhost:8081)
- **Status:** ✅ Autenticação Automática (Não requer senha no login)
- **Banco Conectado:** `sgaiti_db`

---

## 🔐 Credenciais e Segurança

Embora o phpMyAdmin entre automaticamente, estas são as credenciais reais do banco de dados para uso em `.env` ou conexões externas.

### Usuário da Aplicação (Padrão)
Utilizado pelo Laravel e pelo phpMyAdmin atual.
- **Usuário:** `sgaiti_user`
- **Senha:** `sgaiti_pass_change_me`
- **Permissões:** Acesso total ao banco `sgaiti_db`.

### Usuário Root (Administrador Geral)
Utilizado apenas para manutenção crítica ou alteração de permissões globais.
- **Usuário:** `root`
- **Senha:** `sgaiti_root_pass_change_me`

---

## � Estrutura do Banco de Dados (`sgaiti_db`)

O banco de dados está totalmente migrado e populado. Abaixo, as principais tabelas e suas funções:

### Tabelas Principais (Core)
| Tabela | Descrição | Registros Atuais |
|--------|-----------|------------------|
| `assets` | Tabela mestre de ativos (bens patrimoniais). | **42** |
| `users` | Usuários do sistema (autenticação). | **0** (Use `military_users`) |
| `military_users` | Dados detalhados dos militares/usuários. | **18** |
| `sectors` | Setores/Departamentos onde os ativos ficam. | **10+** |

### Tabelas de Operação
- `custody_logs`: Histórico de quem está com qual ativo (Cautela).
- `inventory_records`: Registros de conferência de inventário.
- `maintenance_records`: Histórico de manutenções dos ativos.
- `asset_photos`: Galeria de fotos dos ativos.

---

## �️ Comandos de Gerenciamento

### Resetar/Limpar Banco (CUIDADO ⚠️)
Apaga tudo e recria as tabelas do zero.
```bash
docker exec sgaiti-app php artisan migrate:fresh
```

### Popular com Dados de Teste
Cria usuários e ativos fictícios se o banco estiver vazio.
```bash
docker exec sgaiti-app php artisan db:seed
```
*Nota: Para criar apenas o admin, use `--class=AdminUserSeeder`*

### Backup Rápido
Gera um arquivo SQL com todos os dados atuais.
```bash
docker exec sgaiti-mysql mysqldump -usgaiti_user -psgaiti_pass_change_me sgaiti_db > backup_sgaiti.sql
```

---

## ⚙️ Configuração no Laravel (`.env`)

Se precisar reconfigurar o backend, estas são as variáveis corretas:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sgaiti_db
DB_USERNAME=sgaiti_user
DB_PASSWORD=sgaiti_pass_change_me
```
