# Lição: Renomeação SGAITI-UM para SGTI-GAC

**Data**: 2025-11-06  
**Categoria**: config  
**Stack**: Laravel 12, PHP 8.4, React 18, Inertia.js  
**Severity**: Médio  
**Origem**: project-docs/lessons-learned/sgti-gac-system-updates.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Única (mudança de branding)  
**Impacto**: Médio

### Sintoma Observado
Nomenclatura inconsistente do sistema espalhada pelo código: SGAITI-UM, SGAITI, "Sistema de Gestão de Ativos..." em diferentes arquivos.

### Comportamento Esperado
Nome único e padronizado: SGTI-GAC (Sistema de Gestão de TI do GAC-PAC)

### Evidência
```
❌ ANTES: SGAITI-UM (Sistema de Gestão de Ativos de TI da Unidade Militar)
✅ AGORA: SGTI-GAC (Sistema de Gestão de TI do GAC-PAC)
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Nomenclatura inconsistente espalhada pelo código
2. **Por que?** Mudança de requisito de negócio sem mapeamento completo
3. **Por que?** Falta de arquivo central de configuração de branding
4. **Por que?** APP_NAME não era usado consistentemente em todos os componentes
5. **Por que?** Alguns componentes tinham nomes hardcoded

### Tipo de Problema
- [x] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```php
// backend/.env
APP_NAME="SGTI-GAC"

// backend/.env.example
APP_NAME="SGTI-GAC"
```

```jsx
// PrintReport.jsx - Cabeçalho atualizado
<h1>SISTEMA DE GESTÃO DE TI DO GAC-PAC (SGTI-GAC)</h1>

// Summary.jsx - Títulos atualizados
// Layout geral - Referências corrigidas
```

### Por Que Funciona
Centralização da configuração no .env permite mudanças rápidas e consistentes em todo o sistema.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Substituir strings manualmente | Alto risco de esquecer lugares |
| Criar constante PHP | Não resolve frontend React |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `grep -r "SGAITI-UM" backend/`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Usar APP_NAME em todos os lugares que exibem nome do sistema
- [ ] Criar arquivo de configuração de branding separado
- [ ] Documentar processo de renomeação em CONTRIBUTING.md

### Regras de Ouro
1. Nunca hardcode nomes de sistema em componentes React
2. Sempre usar variáveis de ambiente para branding
3. Criar teste de regressão para nomenclatura

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/sgti-gac-system-updates.md
- **Commit/PR**: N/A
- **Documentação**: Laravel Configuration Docs

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
