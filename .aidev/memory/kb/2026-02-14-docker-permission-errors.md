# Lições Aprendidas - Permissões Docker e Migrations

**Data:** 2026-02-14
**Contexto:** Sprint 9 / Unificação de Usuários

## 1. Erro de Permissão em Migrations

### O Problema
Ao executar `php artisan make:migration` via `docker compose exec`, o arquivo de migration gerado dentro do volume pode ser criado com propriedade `root:root` (ou usuário interno do container), resultando em erro `EACCES` (Permission denied) quando o agente ou o usuário host tenta editá-lo.

**Erro:**
`Permission denied writing to file: .../database/migrations/xxxx_xx_xx_xxxxxx_migration.php (EACCES)`

### A Solução
Executar o comando `chown` para ajustar a propriedade dos arquivos para o usuário do host (UID 1000).

```bash
docker compose exec -T laravel.test chown -R 1000:1000 /var/www/html/database/migrations
```

**Prevenção:** Sempre que gerar arquivos via artisan no docker que precisem de edição subsequente pelo agente, verificar permissões ou rodar o chown preventivo se o ambiente não estiver configurado com usuário mapeado (user: 1000:1000).

---

## 2. Persistência em Arquivos de Teste

### O Problema
Ocorreram falhas repetidas ao usar `replace_file_content` em arquivos de teste complexos/grandes, com o erro "target content not found", possivelmente devido a formatação ou caracteres invisíveis.

### A Solução (Workaround)
Em vez de insistir na substituição in-place:
1. Criar um novo arquivo temporário (ex: `NewAccessControlTest.php`).
2. Escrever o conteúdo completo e correto neste arquivo.
3. Executar os testes no arquivo novo.
4. Se passar, renomear/mover o arquivo novo sobre o antigo.

Isso garante atomicidade e evita arquivos corrompidos.

---

## 3. Padronização de Roles (Inglês vs Português)

### O Problema
Testes falharam porque o Seeder criou a role `operator` (inglês) mas o teste buscava `operador` (português).

### A Regra
- **Backend/Database:** Usar SEMPRE chaves e slugs em **Inglês** (`admin`, `operator`, `auditor`, `viewer`).
- **Frontend/Interface:** Usar traduções ou labels para exibir em **Português**.
