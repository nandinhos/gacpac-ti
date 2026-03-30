# 🚀 Guia de Contribuição (gacpac-ti)

Bem-vindo ao desenvolvimento do sistema Gacpac-TI. Para garantir a consistência e qualidade do código, seguimos rigorosamente os padrões abaixo.

## 🛠️ Ambiente de Desenvolvimento

Este projeto utiliza **Laravel Sail** (Docker). **NUNCA** execute comandos PHP, Composer ou NPM diretamente no seu host.

```bash
# Subir ambiente
./vendor/bin/sail up -d

# Instalar dependências
./vendor/bin/sail composer install
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev

# Artisan commands
./vendor/bin/sail artisan [comando]
```

## 🎨 Padronização de Código (Code Style)

Antes de realizar qualquer commit, você **DEVE** rodar o formatador:

```bash
./vendor/bin/sail exec laravel.test ./vendor/bin/pint
```

## 🧪 Testes Automatizados

Nenhum código vai para produção sem passar nos testes:

```bash
./vendor/bin/sail artisan test --compact
```

## 🤖 Uso de IA (Gemini + Boost)

Este projeto é otimizado para agentes de IA. Use sempre o **Laravel Boost** como seu co-piloto principal:

1. **Active Guidelines:** O sistema possui regras específicas para cada módulo em `.aidev/rules/`.
2. **MCP Server:** Use as ferramentas `database_schema`, `search_docs` e `tinker` via MCP para decisões baseadas em dados reais da aplicação.

## 📝 Mensagens de Commit

Seguimos o padrão **Conventional Commits** em **Português**:

- `feat(modulo):` nova funcionalidade
- `fix(modulo):` correção de bug
- `refactor(modulo):` alteração de código sem mudar comportamento
- `chore(tooling):` manutenção de ferramentas

**Exemplo:** `feat(assets): adiciona busca por QR Code na API`
