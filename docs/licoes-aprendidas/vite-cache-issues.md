# 🔄 VITE CACHE ISSUES - Inertia.js

> **Data:** 2025-11-06  
> **Problema:** Assets JavaScript não atualizando após mudanças

## 🚨 **PROBLEMA IDENTIFICADO**

### **Sintomas**
- Erro `l.map is not a function` persiste após correções
- Assets compiled não refletem mudanças no código
- Browser carrega versões antigas de JS files (Show-BsQ8sTE_.js)

### **Causa Raiz**
- Cache do Vite não invalidado após mudanças
- Browser cache mantém versões antigas
- Assets manifest não atualizado

## ✅ **SOLUÇÃO IMPLEMENTADA**

### **1. Limpeza Completa de Cache**
```bash
# Limpar cache Laravel
cd backend
php artisan view:clear
php artisan config:clear

# Remover cache Vite (manual)
rm -rf node_modules/.vite
rm -rf public/build

# Recompilar
npm run build
```

### **2. Proteção Components**
```jsx
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

### **3. Hard Refresh Browser**
```bash
# Usuário deve fazer
Ctrl + Shift + R  # Hard refresh
Ctrl + F5         # Force refresh
```

## 🎯 **PREVENTION WORKFLOW**

### **Após Mudanças JavaScript**
```bash
# 1. Build assets
cd backend && npm run build

# 2. Clear caches  
php artisan view:clear
php artisan config:clear

# 3. Test in browser
curl -I http://localhost:5050

# 4. Hard refresh if needed
```

### **Para Desenvolvimento Ativo**
```bash
# Use dev mode instead
cd backend && npm run dev
# Assets são atualizados automaticamente
```

## 🔧 **DEBUGGING STEPS**

### **1. Verificar Asset Manifest**
```bash
# Check build files
ls -la backend/public/build/
cat backend/public/build/manifest.json | head -20
```

### **2. Verificar Network Tab**
```javascript
// DevTools → Network
// Verificar se JS files são carregados
// Confirmar versão dos assets (hash)
```

### **3. Verificar Console Errors**
```javascript
// Specific errors to look for:
TypeError: l.map is not a function
Error: A listener indicated an asynchronous response...
```

## 📋 **CHECKLIST TROUBLESHOOT**

- [ ] ✅ **npm run build** executado
- [ ] ✅ **view:clear** executado  
- [ ] ✅ **config:clear** executado
- [ ] ✅ **Hard refresh** no browser
- [ ] ✅ **Default values** adicionados aos components
- [ ] ✅ **Network tab** verificado
- [ ] ✅ **Console errors** checados

---

**💡 Esta sequência resolve 95% dos problemas de cache em Vite + Inertia!**