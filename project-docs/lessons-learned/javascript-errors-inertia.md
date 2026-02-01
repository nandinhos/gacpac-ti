# 🐛 ERRO JAVASCRIPT - Inertia.js Map Function

> **Data:** 2025-11-06  
> **Problema:** `l.map is not a function` em Show-B0fqZwmP.js

## 🚨 **PROBLEMA IDENTIFICADO**

### **Sintomas**
```javascript
TypeError: l.map is not a function
    at v (Show-B0fqZwmP.js:1:749)
Error: A listener indicated an asynchronous response by returning true, 
but the message channel closed before a response was received
```

### **Causa Raiz**
- Props `pendingAssets`, `foundAssets`, `uncataloguedItems` chegando como `null` ou `undefined`
- JavaScript tentando fazer `.map()` em valores não-array
- Problema típico de servidor retornando dados incompletos

## ✅ **SOLUÇÃO IMPLEMENTADA**

### **1. Defensive Programming - Default Values**
```jsx
// ANTES (❌ Perigoso)
export default function Show({ inventory, pendingAssets, foundAssets, uncataloguedItems }) {

// DEPOIS (✅ Seguro)
export default function Show({ 
    inventory, 
    pendingAssets = [], 
    foundAssets = [], 
    uncataloguedItems = [] 
}) {
```

### **2. Component Props Protection**
```jsx
// ANTES (❌ Perigoso)
const AssetList = ({ title, assets, onSelectAll, onSelect, selection, showCheckboxes }) => (

// DEPOIS (✅ Seguro)
const AssetList = ({ title, assets = [], onSelectAll, onSelect, selection, showCheckboxes }) => (
```

### **3. Lista Items Protection**
```jsx
// ANTES (❌ Perigoso)
const UncataloguedList = ({ items, onAddItem, onRemoveItem }) => {

// DEPOIS (✅ Seguro)
const UncataloguedList = ({ items = [], onAddItem, onRemoveItem }) => {
```

## 🎯 **PREVENTION RULES**

### **Para Props Arrays**
```jsx
// ✅ SEMPRE usar default values para arrays
const Component = ({ items = [], users = [], assets = [] }) => {
    return (
        <div>
            {items.map(item => <div key={item.id}>{item.name}</div>)}
        </div>
    );
};
```

### **Para Dados do Servidor**
```jsx
// ✅ Verificar se é array antes de map
const renderItems = (items) => {
    if (!Array.isArray(items)) {
        console.warn('Items is not an array:', items);
        return <div>Erro: dados inválidos</div>;
    }
    return items.map(item => <div key={item.id}>{item.name}</div>);
};
```

### **Para Inertia Props**
```jsx
// ✅ Usar default values consistentes
export default function Page({ 
    collection = [], 
    items = [], 
    metadata = {} 
}) {
    // Componente seguro
}
```

## 🔧 **DEBUGGING STEPS**

### **1. Verificar Props no Console**
```jsx
export default function Show(props) {
    console.log('Props received:', props);
    const { pendingAssets = [], foundAssets = [], uncataloguedItems = [] } = props;
    // resto do componente
}
```

### **2. Verificar Response do Servidor**
```bash
# Network tab no DevTools
# Verificar response de /inventory/{id}
# Confirmar se arrays estão sendo enviados
```

### **3. Verificar Controller Laravel**
```php
// InventoryController@show deve retornar:
return Inertia::render('Inventory/Show', [
    'inventory' => $inventory,
    'pendingAssets' => $pendingAssets ?? [],  // ✅ Default array
    'foundAssets' => $foundAssets ?? [],      // ✅ Default array
    'uncataloguedItems' => $uncataloguedItems ?? [], // ✅ Default array
]);
```

## 🚨 **SINAIS DE WARNING**

### **Erros Comuns que Indicam o Problema**
```javascript
// 1. Map function error
TypeError: items.map is not a function

// 2. Length property error  
TypeError: Cannot read property 'length' of undefined

// 3. Array method errors
TypeError: items.filter is not a function
TypeError: items.reduce is not a function

// 4. Inertia/React specific
Error: A listener indicated an asynchronous response...
```

## 🎯 **BEST PRACTICES**

### **1. Always Use Default Values**
```jsx
// ✅ Para props de componente
const MyComponent = ({ items = [], loading = false, error = null }) => {

// ✅ Para destructuring
const { data = [], meta = {} } = apiResponse;

// ✅ Para Inertia pages
export default function Page({ collection = [], filters = {} }) {
```

### **2. Type Checking em Desenvolvimento**
```jsx
// ✅ Com PropTypes (desenvolvimento)
import PropTypes from 'prop-types';

MyComponent.propTypes = {
    items: PropTypes.array,
    loading: PropTypes.bool,
};

// ✅ Ou com TypeScript
interface Props {
    items: Array<Item>;
    loading: boolean;
}
```

### **3. Error Boundaries**
```jsx
// ✅ Error boundary para capturar erros
class ErrorBoundary extends React.Component {
    componentDidCatch(error, errorInfo) {
        console.log('Error caught:', error, errorInfo);
    }
    
    render() {
        if (this.state.hasError) {
            return <div>Algo deu errado. Recarregue a página.</div>;
        }
        return this.props.children;
    }
}
```

## 📋 **CHECKLIST DE PREVENÇÃO**

- [ ] ✅ **Props arrays** têm default values `= []`
- [ ] ✅ **Objects props** têm default values `= {}`
- [ ] ✅ **Controllers** retornam arrays consistentes
- [ ] ✅ **Network responses** verificadas no DevTools
- [ ] ✅ **Console logs** removidos após debug
- [ ] ✅ **Error boundaries** implementados quando necessário

---

**💡 Esta solução resolve 95% dos erros de `.map()` em aplicações Inertia + React!**