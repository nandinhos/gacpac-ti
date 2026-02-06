# Lição: Cache Vite - Assets Não Atualizando

**Data**: 2025-11-06  
**Categoria**: config  
**Stack**: Laravel 12, Vite, React 18, Inertia.js  
**Severity**: Médio  
**Origem**: project-docs/lessons-learned/vite-cache-issues.md

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Intermitente  
**Impacto**: Médio

### Sintoma Observado
- Erro `l.map is not a function` persiste após correções
- Assets compiled não refletem mudanças no código
- Browser carrega versões antigas de JS files

### Comportamento Esperado
Assets JavaScript devem refletir imediatamente as mudanças no código fonte

### Evidência
```javascript
TypeError: l.map is not a function
    at v (Show-BsQ8sTE_.js:1:749)
// Erro persiste mesmo após correção do código
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Erro persiste após correção
2. **Por que?** Browser carregando versão antiga do JS
3. **Por que?** Cache do Vite não foi invalidado
4. **Por que?** Build não gerou novo hash para o arquivo
5. **Por que?** node_modules/.vite e public/build não limpos

### Tipo de Problema
- [ ] Bug de código / [x] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada
```bash
# 1. Limpar cache Laravel
cd backend
php artisan view:clear
php artisan config:clear

# 2. Remover cache Vite
rm -rf node_modules/.vite
rm -rf public/build

# 3. Recompilar
npm run build

# 4. Hard refresh no browser
Ctrl + Shift + R  # ou Ctrl + F5
```

### Proteção Components
```tsx
// Index.jsx
export default function Index({ inventoryRecords = [] }) {

// Show.jsx  
export default function Show({ 
    inventory, 
    pendingAssets = [], 
    foundAssets = [], 
    uncataloguedItems = [] 
}) {
```

### Para Desenvolvimento Ativo
```bash
# Use dev mode para hot-reload
npm run dev
# Assets atualizados automaticamente
```

### Por Que Funciona
Limpeza completa do cache força regeneração dos assets com novo hash.

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| Disabilitar cache no browser | Afeta performance |
| Usar dev mode em produção | Não recomendado |

### Validação
- [ ] Teste adicionado/atualizado
- [x] Comando de verificação: `ls -la backend/public/build/`

---

## Prevenção

### Checklist Troubleshoot
- [ ] npm run build executado
- [ ] view:clear executado
- [ ] config:clear executado
- [ ] Hard refresh no browser
- [ ] Default values adicionados aos components

### Regras de Ouro
1. **Sempre** limpar cache após mudanças em JS
2. **Usar** `npm run dev` durante desenvolvimento ativo
3. **Verificar** network tab para confirmar versão do asset
4. **Fazer** hard refresh (Ctrl+F5) após builds

---

## Referências

- **Arquivo Original**: project-docs/lessons-learned/vite-cache-issues.md
- **Commit/PR**: N/A
- **Documentação**: Vite Caching, Laravel Vite Plugin

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
