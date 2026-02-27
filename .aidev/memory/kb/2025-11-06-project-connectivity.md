# Lição: Configuração de Conectividade entre Ambientes Local e Docker

**Data**: 2025-11-06  
**Categoria**: config  
**Stack**: Laravel 12, PHP 8.4, Docker, MySQL 8.0  
**Severity**: Alto  
**Origem**: project-docs/lessons-learned/conectividade-projeto.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Intermitente  
**Impacto**: Alto

### Sintoma Observado
Conectividade inconsistente entre serviços dependendo se Laravel roda local ou no Docker. Configurações de .env precisavam ser alteradas manualmente.

### Comportamento Esperado
Switch automático entre configurações local e Docker sem alteração manual

### Evidência
```bash
# Erros frequentes:
SQLSTATE[HY000] [2002] Connection refused
getaddrinfo for mysql failed: Name does not resolve
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Conexão MySQL falha quando muda ambiente
2. **Por que?** .env usa configuração fixa (DB_HOST=mysql ou 127.0.0.1)
3. **Por que?** Não há detecção automática de ambiente
4. **Por que?** Falta script de switch de ambiente
5. **Por que?** Documentação não padronizava processo

### Tipo de Problema
- [ ] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```bash
#!/bin/bash
# switch-env.sh

ENV_TYPE=$1  # "local" ou "docker"

if [ "$ENV_TYPE" = "local" ]; then
    echo "🖥️  Configurando para desenvolvimento local..."
    cd backend
    sed -i 's/DB_HOST=mysql/DB_HOST=127.0.0.1/' .env
    sed -i 's/DB_PORT=3306/DB_PORT=53106/' .env
    sed -i 's|APP_URL=.*|APP_URL=http://127.0.0.1:8000|' .env
    echo "✅ Configurado para local"
    
elif [ "$ENV_TYPE" = "docker" ]; then
    echo "🐳 Configurando para Docker..."
    cd backend
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/DB_PORT=53106/DB_PORT=3306/' .env
    sed -i 's|APP_URL=.*|APP_URL=http://localhost:5050|' .env
    echo "✅ Configurado para Docker"
fi
```

```env
# Para Laravel rodando FORA do Docker
DB_HOST=127.0.0.1
DB_PORT=53106
APP_URL=http://127.0.0.1:8000

# Para Laravel rodando DENTRO do Docker  
DB_HOST=mysql
DB_PORT=3306
APP_URL=http://localhost:5050
```

### Por Que Funciona
Script automatiza a troca de configurações baseado no ambiente desejado, eliminando erro humano.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Variável de ambiente global | Não resolve problema de múltiplos devs |
| Docker apenas | Limita flexibilidade de desenvolvimento |

### Validação
- [ ] Teste adicionado/atualizado
- [x] Comando de verificação: `./switch-env.sh local && cat .env | grep DB_HOST`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Sempre usar script switch-env.sh ao trocar ambiente
- [ ] Verificar conectividade após switch (`docker-compose ps`)
- [ ] Documentar qual ambiente está sendo usado no README

### Regras de Ouro
1. Nunca editar .env manualmente - sempre usar script
2. Verificar containers Docker antes de rodar migrações
3. Testar conexão: `mysql -h127.0.0.1 -P53106`

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/conectividade-projeto.md
- **Commit/PR**: N/A
- **Documentação**: Docker Networking, Docker Compose

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
