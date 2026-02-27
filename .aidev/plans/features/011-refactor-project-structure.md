# Feature 011: Refatoração da Estrutura do Projeto

**Sprint:** Backlog (Não agendado)
**Prioridade:** 🔴 ALTA
**Status:** Planejado
**Data criação:** 2026-02-27

---

## 📋 Contexto de Negócio

O projeto **gacpac-ti** passou por uma refatoração completa de **React para Laravel**, porém a estrutura de diretórios ainda reflete a arquitetura antiga. O sistema Laravel funcional está dentro da pasta `backend/`, mas existem resquícios da versão React que precisam ser removidos ou reorganizados.

### Problema Atual:
- ✅ Sistema Laravel funcional em `backend/`
- ❌ Estrutura de pastas confusa (mistura de React + Laravel)
- ❌ Configuração de containers duplicada/desnecessária
- ❌ Mapeamento de portas conflitante
- ❌ Arquivos e pastas da versão React ainda presentes

---

## 🎯 Objetivos

1. **Limpar estrutura do projeto** - Remover todos os artefatos da versão React
2. **Reorganizar diretórios** - Mover conteúdo de `backend/` para raiz do projeto
3. **Simplificar containers** - Manter apenas os containers necessários para Laravel
4. **Padronizar portas** - Configurar portas de forma clara e sem conflitos
5. **Atualizar documentação** - Refletir nova estrutura em READMEs e docs

---

## 🔍 Levantamento Atual

### Estrutura Atual (Confusa):
```
gacpac-ti/
├── backend/              # Sistema Laravel (ATUAL)
│   ├── app/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── tests/
│   ├── vendor/
│   ├── .env
│   ├── composer.json
│   └── ...
├── .aidev/               # AI Dev Superpowers (MANTER)
├── .git/                 # Git (MANTER)
├── docker-compose.yml    # Containers (REFATORAR)
├── .env                  # Variáveis Docker (REFATORAR)
├── README.md             # Documentação (ATUALIZAR)
└── [OUTROS ARQUIVOS DA VERSÃO REACT - IDENTIFICAR E REMOVER]
```

### Estrutura Desejada (Limpa):
```
gacpac-ti/
├── app/                  # Movido de backend/app
├── bootstrap/            # Movido de backend/bootstrap
├── config/               # Movido de backend/config
├── database/             # Movido de backend/database
├── public/               # Movido de backend/public
├── resources/            # Movido de backend/resources
├── routes/               # Movido de backend/routes
├── storage/              # Movido de backend/storage
├── tests/                # Movido de backend/tests
├── vendor/               # Movido de backend/vendor
├── .aidev/               # AI Dev Superpowers (mantido)
├── .git/                 # Git (mantido)
├── docker/               # Configurações Docker organizadas
├── docker-compose.yml    # Containers simplificados
├── .env                  # Variáveis de ambiente Laravel
├── .env.example          # Template de variáveis
├── artisan               # Movido de backend/artisan
├── composer.json         # Movido de backend/composer.json
├── package.json          # Movido de backend/package.json
├── phpunit.xml           # Movido de backend/phpunit.xml
├── vite.config.js        # Movido de backend/vite.config.js
└── README.md             # Documentação atualizada
```

---

## 📝 Tarefas de Implementação

### Fase 1: Levantamento e Planejamento (2h)
- [ ] 1.1 - Mapear TODOS os arquivos e pastas da raiz do projeto
- [ ] 1.2 - Identificar o que é da versão React (remover)
- [ ] 1.3 - Identificar o que é da versão Laravel (mover)
- [ ] 1.4 - Identificar o que é compartilhado (manter ou ajustar)
- [ ] 1.5 - Criar lista completa de movimentações
- [ ] 1.6 - Fazer backup completo antes de iniciar

### Fase 2: Limpeza (1h)
- [ ] 2.1 - Remover node_modules da versão React (se existir)
- [ ] 2.2 - Remover pasta `src/` ou `frontend/` (se existir)
- [ ] 2.3 - Remover configurações React (webpack, babel, etc)
- [ ] 2.4 - Remover dependências React do package.json raiz (se existir)
- [ ] 2.5 - Remover containers Docker desnecessários

### Fase 3: Reorganização (2h)
- [ ] 3.1 - Mover conteúdo de `backend/*` para raiz do projeto
- [ ] 3.2 - Atualizar `docker-compose.yml` para nova estrutura
- [ ] 3.3 - Ajustar volumes no Docker (remover `./backend`)
- [ ] 3.4 - Configurar portas de forma padronizada (PORTAS ALTAS para evitar conflitos):
  - Laravel: `8900:8000` (porta alta externa, evita conflito)
  - PostgreSQL: `54320:5432` (porta alta externa)
  - pgAdmin: `8950:80` (porta alta externa)
  - Vite: `5173:5173` (mantida, já é alta)
- [ ] 3.5 - Unificar arquivos `.env` (raiz + Laravel)

### Fase 4: Ajustes de Configuração (1h)
- [ ] 4.1 - Atualizar paths no `docker-compose.yml`
- [ ] 4.2 - Atualizar `Dockerfile` se necessário
- [ ] 4.3 - Ajustar scripts no `package.json`
- [ ] 4.4 - Atualizar `.gitignore` para nova estrutura
- [ ] 4.5 - Configurar variáveis de ambiente corretamente

### Fase 5: Testes e Validação (1h)
- [ ] 5.1 - Subir containers e verificar funcionamento
- [ ] 5.2 - Executar migrações e seeders
- [ ] 5.3 - Rodar testes automatizados
- [ ] 5.4 - Acessar aplicação via navegador
- [ ] 5.5 - Validar todas as funcionalidades principais

### Fase 6: Documentação (1h)
- [ ] 6.1 - Atualizar `README.md` com nova estrutura
- [ ] 6.2 - Atualizar documentação de setup
- [ ] 6.3 - Criar guia de migração (para outros devs)
- [ ] 6.4 - Atualizar `.aidev/plans/ROADMAP.md`
- [ ] 6.5 - Documentar decisões e lições aprendidas

---

## 🐳 Configuração Docker Proposta

### docker-compose.yml Simplificado (PORTAS ALTAS):
```yaml
services:
    laravel.test:
        build:
            context: .
            dockerfile: Dockerfile
        ports:
            - '${APP_PORT:-8900}:8000'   # Servidor Laravel (porta alta externa)
            - '${VITE_PORT:-5173}:5173'  # Vite HMR
        environment:
            WWWUSER: '${WWWUSER:-1000}'
            WWWGROUP: '${WWWGROUP:-1000}'
            LARAVEL_SAIL: 1
        volumes:
            - '.:/var/www/html'
        networks:
            - sail
        depends_on:
            - pgsql
        healthcheck:
            test: ["CMD", "curl", "-f", "http://localhost:8000"]
            interval: 30s
            timeout: 10s
            retries: 3

    pgsql:
        image: 'postgres:16'
        ports:
            - '${FORWARD_DB_PORT:-54320}:5432'  # PostgreSQL (porta alta externa)
        environment:
            PGPASSWORD: '${DB_PASSWORD:-secret}'
            POSTGRES_DB: '${DB_DATABASE}'
            POSTGRES_USER: '${DB_USERNAME}'
            POSTGRES_PASSWORD: '${DB_PASSWORD:-secret}'
        volumes:
            - 'sail-pgsql:/var/lib/postgresql/data'
        networks:
            - sail
        healthcheck:
            test: ["CMD", "pg_isready", "-q", "-d", "${DB_DATABASE}", "-U", "${DB_USERNAME}"]
            interval: 10s
            timeout: 5s
            retries: 3

    pgadmin:
        image: 'dpage/pgadmin4'
        ports:
            - '${PGADMIN_PORT:-8950}:80'  # pgAdmin (porta alta externa)
        environment:
            PGADMIN_DEFAULT_EMAIL: '${PGADMIN_EMAIL:-admin@admin.com}'
            PGADMIN_DEFAULT_PASSWORD: '${PGADMIN_PASSWORD:-secret}'
        networks:
            - sail
        depends_on:
            - pgsql

networks:
    sail:
        driver: bridge

volumes:
    sail-pgsql:
        driver: local
```

### Justificativa das Portas Altas:
- **8900** (Laravel): Evita conflito com aplicações comuns na porta 8000
- **54320** (PostgreSQL): Evita conflito com instâncias locais do PostgreSQL (5432)
- **8950** (pgAdmin): Evita conflito com outras ferramentas admin
- **5173** (Vite): Porta padrão do Vite, já é alta e raramente conflita

### Acesso às Aplicações:
- **Laravel:** http://localhost:8900
- **pgAdmin:** http://localhost:8950
- **PostgreSQL:** localhost:54320 (para clients externos)
- **Vite HMR:** http://localhost:5173

---

## ⚠️ Riscos e Mitigações

| Risco | Probabilidade | Impacto | Mitigação |
|-------|---------------|---------|-----------|
| Perda de arquivos importantes | Média | Alto | Fazer backup completo antes de iniciar |
| Quebra de paths em código | Alta | Médio | Fazer busca global por `backend/` no código |
| Containers não sobem | Baixa | Alto | Testar em ambiente local isolado primeiro |
| Conflito de portas | Média | Baixo | Mapear portas em uso antes de configurar |
| Variáveis de ambiente perdidas | Baixa | Médio | Documentar todas as variáveis antes |

---

## 🎯 Critérios de Aceitação

1. ✅ Pasta `backend/` não existe mais
2. ✅ Todos os arquivos Laravel estão na raiz do projeto
3. ✅ Não há resquícios da versão React
4. ✅ `docker-compose up -d` sobe todos os containers sem erros
5. ✅ Aplicação acessível em `http://localhost:8900`
6. ✅ Todos os testes passam (`php artisan test`)
7. ✅ Migrações e seeders funcionam
8. ✅ Documentação atualizada e clara
9. ✅ `.gitignore` adequado para nova estrutura
10. ✅ Commit limpo com histórico preservado

---

## 📦 Dependências

- Nenhuma feature precisa ser concluída antes
- **Bloqueio:** Esta feature deve ser priorizada antes de novas implementações
- **Recomendação:** Executar em ambiente de desenvolvimento/staging primeiro

---

## 🔗 Arquivos Relacionados

- `docker-compose.yml` - Precisa ser atualizado
- `.env` - Precisa ser unificado
- `README.md` - Precisa ser atualizado
- `.gitignore` - Precisa ser revisado
- `.aidev/plans/ROADMAP.md` - Precisa ser atualizado

---

## 📚 Referências

- [Laravel Directory Structure](https://laravel.com/docs/11.x/structure)
- [Docker Compose Best Practices](https://docs.docker.com/compose/compose-file/)
- [Laravel Sail Documentation](https://laravel.com/docs/11.x/sail)

---

## 💡 Notas Adicionais

- Esta refatoração é **CRÍTICA** para manutenibilidade do projeto
- Deve ser feita **ANTES** de onboarding de novos desenvolvedores
- Facilita automação de deploy e CI/CD no futuro
- Melhora significativamente a Developer Experience (DX)

---

**Estimativa Total:** 8 horas
**Complexidade:** Média
**Impacto:** Alto
**ROI:** Muito Alto (simplifica toda manutenção futura)
