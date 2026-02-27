# Lição: Workflow Docker Development - Processo Completo

**Data**: 2025-11-06  
**Categoria**: architecture  
**Stack**: Laravel 12, PHP 8.4, Docker, React 18  
**Severity**: Médio  
**Origem**: project-docs/lessons-learned/workflow-docker-development.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Sempre  
**Impacto**: Alto

### Sintoma Observado
Falta de processo padronizado para desenvolvimento com Docker, levando a erros de configuração entre ambientes local e Docker.

### Comportamento Esperado
Workflow claro e reproduzível para desenvolvimento, testes e deploy

### Evidência
```bash
# Problemas recorrentes:
- Configuração .env misturada entre local e Docker
- Esquecer de testar no Docker antes de commit
- Falta de padronização entre desenvolvedores
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que problemas?** Configurações inconsistentes
2. **Por que?** Cada dev faz do seu jeito
3. **Por que?** Falta de processo documentado
4. **Por que?** Onboarding não inclui workflow
5. **Por que?** Documentação existente dispersa

### Tipo de Problema
- [ ] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [x] Falta de validação

---

## Solução

### Correção Aplicada - Workflow Completo

**FASE 1: Setup Inicial**
```bash
# 1. Clonar/Pull projeto
git pull origin main

# 2. Setup Laravel local
cd backend
cp .env.example .env
composer install
npm install
php artisan key:generate

# 3. Configurar .env para desenvolvimento local
# DB_HOST=127.0.0.1, DB_PORT=53106

# 4. Subir dependências Docker
docker-compose up -d mysql phpmyadmin

# 5. Aguardar MySQL e migrar
sleep 15
php artisan migrate:fresh --seed

# 6. Validar ambiente
php artisan serve &
curl -I http://127.0.0.1:8000
```

**FASE 2: Desenvolvimento Ativo**
```bash
cd backend
composer run dev  # Laravel + Vite + Queue + Logs
```

**FASE 3: Validação Docker (Pre-Commit)**
```bash
# 1. Salvar configuração local
cp .env .env.local

# 2. Configurar para Docker
docker exec sgaiti-backend sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env

# 3. Testar Docker
docker-compose restart sgaiti-backend
sleep 5
curl -I http://localhost:5050

# 4. Restaurar configuração local
cp .env.local .env
rm .env.local
```

**FASE 4: Testes Finais**
```bash
# Suite completa
php artisan test
npm run build
php artisan route:list | grep -i error
```

### Por Que Funciona
Padronização elimina erros de configuração e garante consistência entre desenvolvedores.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Apenas Docker | Limita flexibilidade de desenvolvimento |
| Apenas local | Não testa ambiente de produção |

### Validação
- [ ] Teste adicionado/atualizado
- [x] Comando de verificação: `./scripts/health-check.sh`

---

## Prevenção

### Checklist por Fase
**Setup:**
- [ ] MySQL Docker funcionando
- [ ] Laravel local conectando
- [ ] Migrations executadas

**Pre-Commit:**
- [ ] Docker funcionando
- [ ] Testes passando
- [ ] Assets compilados
- [ ] Configuração local restaurada

### Regras de Ouro
1. **Sempre** testar localmente primeiro
2. **Sempre** usar branches para features
3. **Sempre** escrever testes para novas funcionalidades
4. **Sempre** validar Docker antes de commit
5. **NUNCA** commitar .env
6. **NUNCA** commitar código quebrado

---

## Aliases Úteis
```bash
# Adicionar ao ~/.bashrc ou ~/.zshrc
alias sgaiti-dev="cd backend && composer run dev"
alias sgaiti-test="cd backend && php artisan test"
alias sgaiti-docker="docker-compose up -d && sleep 10"
alias sgaiti-reset="docker-compose down && docker-compose up -d && sleep 15 && cd backend && php artisan migrate:fresh --seed"
```

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/workflow-docker-development.md
- **Commit/PR**: N/A
- **Documentação**: Docker Best Practices, Laravel Development

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
