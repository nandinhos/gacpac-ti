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
- **Container:** Docker Compose com Sail (Nginx + PHP-FPM + Supervisor)
- **Testes:** PHPUnit 11 — Feature (22 testes) + Unit (5 testes)
- **Ferramentas AI:** Laravel Boost (MCP), Context7 (MCP)
- **URL local:** `http://localhost:8900`
- **DB Admin:** `http://localhost:8950` (pgAdmin)

## Histórico

- **2026-03-30** — Specs criadas com base na análise de saúde do sistema

---

## Regras Imutáveis para Execução

> ⚠️ Qualquer LLM que for executar uma spec DEVE seguir estas regras sem exceção:

1. **Idioma:** Todo código, commit e comunicação em Português do Brasil (PT-BR)
2. **Commits:** `tipo(escopo): descrição em português` — sem emojis
3. **TDD:** Escrever teste antes do código de produção
4. **Throttle:** Nunca remover o middleware `auth:sanctum` dos grupos protegidos
5. **Services:** Nunca colocar lógica de negócio em Controllers ou Livewire diretamente
6. **Verificação:** Após cada mudança de rota, executar `curl -I http://localhost:8900/rota`
7. **Escopo:** Não alterar arquivos fora do escopo descrito na spec
