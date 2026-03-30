# 📑 Specs de Implementação — gacpac-ti

> Este diretório contém as especificações técnicas detalhadas por fase do ROADMAP.
> Cada spec é **autocontida** e pode ser executada de forma independente por qualquer LLM ou desenvolvedor sem necessidade de contexto adicional.

## Como Usar

1. Verifique o `ROADMAP.md` na raiz do projeto para o status atual de cada fase
2. Abra a spec da fase que será executada
3. Siga os passos **exatamente** como descritos
4. Valide os critérios de aceite ao final
5. Execute o commit exato descrito na spec
6. Atualize o `ROADMAP.md` marcando os checkboxes concluídos

---

## Fases

| Arquivo | Fase | Descrição | Prioridade |
|---|---|---|---|
| [fase-0-ferramentas.md](./fase-0-ferramentas.md) | FASE 0 | Ativar Laravel Boost + Context7 | 🔴 Alta |
| [fase-1-docker.md](./fase-1-docker.md) | FASE 1 | Correções Docker e infraestrutura | 🔴 Alta |
| [fase-2-frontend.md](./fase-2-frontend.md) | FASE 2 | Remover dependências React residuais | 🟡 Média |
| [fase-3-codigo.md](./fase-3-codigo.md) | FASE 3 | Correções de código + camada Services | 🟡 Média |
| [fase-4-api.md](./fase-4-api.md) | FASE 4 | API REST completa (todos os módulos) | 🟡 Média |
| [fase-5-documentacao.md](./fase-5-documentacao.md) | FASE 5 | Consolidar documentação | 🟢 Baixa |

---

## Contexto do Sistema

- **Stack:** Laravel 12 + PHP 8.4 + PostgreSQL 16 + Livewire 4 + TailwindCSS 3 + Vite 7
- **Autenticação:** Laravel Sanctum (API) + Laravel Breeze (Web/Livewire)
- **Permissões:** Spatie Laravel Permission v7
- **Container:** Laravel Sail (Docker Compose) com Nginx + PHP-FPM + Supervisor
  - Serviço principal: `laravel.test`
  - Banco: `pgsql` (PostgreSQL 16)
  - Admin DB: `pgadmin`
- **Testes:** PHPUnit 11 — Feature (22 testes) + Unit (5 testes)
- **Ferramentas AI:** Laravel Boost (MCP), Context7 (MCP)
- **URL local:** `http://localhost:8900`
- **DB Admin:** `http://localhost:8950` (pgAdmin)

---

## Referência de Comandos Sail

> ⚠️ TODOS os comandos PHP, Artisan, Composer e NPM devem ser executados via Sail (dentro do container).

```bash
./vendor/bin/sail up -d                                        # Subir containers
./vendor/bin/sail down                                         # Parar containers
./vendor/bin/sail ps                                           # Status dos containers
./vendor/bin/sail logs -f laravel.test                         # Ver logs em tempo real
./vendor/bin/sail shell                                        # Shell interativo no container

./vendor/bin/sail artisan [comando]                            # Artisan
./vendor/bin/sail artisan test                                 # Todos os testes
./vendor/bin/sail artisan test --filter=NomeDoTeste            # Teste específico
./vendor/bin/sail artisan route:list --path=api                # Listar rotas de API
./vendor/bin/sail artisan route:clear                          # Limpar cache de rotas

./vendor/bin/sail composer [comando]                           # Composer
./vendor/bin/sail npm [comando]                                # NPM
./vendor/bin/sail npm run build                                # Build assets
./vendor/bin/sail npm install                                  # Instalar dependências

./vendor/bin/sail exec laravel.test ./vendor/bin/pint          # Code style (corrigir)
./vendor/bin/sail exec laravel.test ./vendor/bin/pint --test   # Code style (apenas verificar)
```

---

## Regras Imutáveis para Execução

> ⚠️ Qualquer LLM que for executar uma spec DEVE seguir estas regras sem exceção:

1. **Idioma:** Todo código, commit e comunicação em Português do Brasil (PT-BR)
2. **Commits:** `tipo(escopo): descrição em português` — sem emojis
3. **TDD:** Escrever teste antes do código de produção
4. **Sail:** NUNCA rodar `php artisan`, `composer` ou `npm` diretamente no host — sempre via `./vendor/bin/sail`
5. **Services:** Nunca colocar lógica de negócio em Controllers ou Livewire diretamente
6. **Verificação:** Após cada mudança de rota, executar `curl -I http://localhost:8900/rota`
7. **Escopo:** Não alterar arquivos fora do escopo descrito na spec

## Histórico

- **2026-03-30** — Specs criadas e revisadas para Laravel Sail
