# 📑 ÍNDICE TÉCNICO DETALHADO - SGAITI-UM

## 🔍 NAVEGAÇÃO RÁPIDA POR TÓPICOS

---

## 🏗️ ARQUITETURA

### **Stack Completo**
```yaml
Frontend:
  - React 18.3.1 + TypeScript
  - Vite 7.x build tool
  - Tailwind CSS
  - Props drilling (centralizado)

Backend:
  - Laravel 12 (PHP 8.x)
  - MySQL 8.0
  - REST API
  - Form Requests validation

Deploy:
  - Docker Compose
  - Nginx + PHP-FPM
  - Multi-container setup
```

**📖 Documentação**: `MELHORES_PRATICAS_SGAITI.md#arquitetura`

---

## 🔧 CONFIGURAÇÃO E SETUP

### **Comandos Essenciais**
```bash
# Subir ambiente completo
docker-compose up -d

# Backend Laravel
cd backend && php artisan serve --host=0.0.0.0 --port=5050

# Frontend React
npm run dev  # (no diretório raiz)

# Banco de dados
docker-compose up -d mysql
```

**📖 Documentação**: `DOCKER_DEPLOY.md`

### **Portas Padrão**
- Frontend: http://localhost:58100
- Backend: http://localhost:5050/api  
- MySQL: localhost:53106
- phpMyAdmin: http://localhost:58090

---

## 🗃️ BANCO DE DADOS

### **Tabelas Principais**
```sql
assets              -- Ativos de TI (tabela principal)
sectors             -- Setores da unidade
military_users      -- Usuários militares
custody_logs        -- Logs de cautela
inventory_records   -- Registros de inventário
asset_photos        -- Fotos dos ativos (cache)
```

**📖 Documentação**: `DATABASE_SCHEMA.md`, `DATABASE_ANALYSIS_REPORT.md`

### **Campos Críticos Assets**
```sql
-- Campos NOVOS (Laravel)
brand, type, condition, patrimony_number, purchase_value

-- Campos ANTIGOS (compatibilidade)  
manufacturer, condition_rating, patrimony_id, purchase_price

-- Estratégia: HÍBRIDA (aceita ambos)
```

**📖 Documentação**: `SINCRONIZACAO_SCHEMA_REPORT.md`

---

## 🔄 COMPATIBILIDADE

### **Estratégia Híbrida Implementada**
```php
// Backend aceita AMBOS os formatos
$data = $request->only([
    'brand', 'manufacturer',           // brand OU manufacturer
    'condition', 'condition_rating',   // condition OU condition_rating
    'patrimony_number', 'patrimony_id' // patrimony_number OU patrimony_id
]);

// Mapeamento automático
if (!isset($data['brand']) && isset($data['manufacturer'])) {
    $data['brand'] = $data['manufacturer'];
}
```

**📖 Documentação**: `PLANO_SINCRONIZACAO_DEFINITIVO.md`

---

## 🛡️ SEGURANÇA

### **Validação Backend (Form Requests)**
```php
// ✅ SEMPRE usar Form Requests
class StoreAssetRequest extends FormRequest {
    public function rules(): array {
        return [
            'brand' => 'required|string|max:100',
            'type' => 'required|string|in:COMPUTADOR,NOTEBOOK,MONITOR',
            'serial_number' => 'nullable|unique:assets,serial_number',
        ];
    }
}

// ❌ NUNCA usar diretamente
$request->all(); // Vulnerável a Mass Assignment
```

### **Validação Frontend**
```typescript
// Validação antes do envio
const validateAsset = (asset: Asset): string[] => {
    const errors: string[] = [];
    if (!asset.name?.trim()) errors.push('Nome obrigatório');
    if (!asset.type) errors.push('Tipo obrigatório');
    return errors;
};
```

**📖 Documentação**: `MELHORES_PRATICAS_SGAITI.md#seguranca`

---

## 📸 UPLOAD DE ARQUIVOS

### **Implementação Completa**
```php
// Backend - Upload seguro
$request->validate([
    'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240' // 10MB
]);

$photoData = base64_encode(file_get_contents($photo->getPathname()));
cache()->put("asset_{$assetId}_photos", $photos, 3600); // Cache 1h
```

```typescript
// Frontend - Interface responsiva
const handleUpload = async (files: File[]) => {
    setIsUploading(true);
    try {
        await Promise.all(files.map(file => assetsApi.addPhoto(assetId, file)));
        onPhotoChange(); // Recarregar dados
        alert('Upload realizado com sucesso!');
    } finally {
        setIsUploading(false);
    }
};
```

**📖 Documentação**: `MELHORES_PRATICAS_SGAITI.md#upload`

---

## 🎨 FRONTEND PATTERNS

### **Componentes Padrão**
```tsx
// Estrutura padrão de componente
interface ComponentProps {
    data: Type;
    onAction: (item: Type) => void;
    className?: string;
}

const Component: React.FC<ComponentProps> = ({ data, onAction, className }) => {
    const [loading, setLoading] = useState(false);
    
    const handleAction = useCallback(async () => {
        setLoading(true);
        try {
            await onAction(data);
        } finally {
            setLoading(false);
        }
    }, [data, onAction]);
    
    return (
        <div className={`base-classes ${className || ''}`}>
            {/* Conteúdo */}
        </div>
    );
};
```

### **Estado e Props**
```tsx
// Estado centralizado em App.tsx
const App = () => {
    const [assets, setAssets] = useState<Asset[]>([]);
    const [users, setUsers] = useState<MilitaryUser[]>([]);
    
    // Props drilling para componentes filhos
    return (
        <AssetManagement 
            assets={assets}
            onUpdateAsset={handleUpdateAsset}
            onDataChange={loadData}
        />
    );
};
```

**📖 Documentação**: `BACKEND_FRONTEND_SYNC.md`

---

## 🔌 API ENDPOINTS

### **Assets CRUD**
```yaml
GET    /api/assets           # Listar ativos
POST   /api/assets           # Criar ativo
GET    /api/assets/{id}      # Visualizar ativo
PUT    /api/assets/{id}      # Atualizar ativo
DELETE /api/assets/{id}      # Excluir ativo

# Sub-recursos
POST   /api/assets/{id}/photos        # Upload foto
DELETE /api/assets/{id}/photos/{pid}  # Excluir foto
```

### **Outros Endpoints**
```yaml
GET /api/sectors             # Listar setores
GET /api/users               # Listar usuários
GET /api/custody             # Cautelas
GET /api/dashboard/stats     # Estatísticas
POST /api/login              # Autenticação
```

**📖 Documentação**: `API_REFERENCE.md`

---

## 🐛 DEBUG E TROUBLESHOOTING

### **Logs Estruturados**
```typescript
// Frontend - Logs com emojis
console.log("🔍 Upload iniciado...", files);
console.log("📁 Arquivos selecionados:", files.length);
console.log("✅ Upload concluído:", result);
console.error("❌ Erro:", error);
```

```php
// Backend - Laravel logs
Log::info('Asset created', ['asset_id' => $asset->id]);
Log::error('Upload failed', ['error' => $e->getMessage()]);
```

### **Problemas Comuns**
```yaml
CORS Error:
  - Verificar APP_URL no .env
  - Confirmar origins no config/cors.php

Schema Mismatch:
  - Usar estratégia híbrida documentada
  - Verificar mapeamento de campos

Upload Falha:
  - Limite 10MB configurado
  - Validar formato (JPG/PNG/GIF)
  - Cache Laravel funcionando
```

**📖 Documentação**: `MELHORES_PRATICAS_SGAITI.md#debug`

---

## 🚀 DEPLOY

### **Docker Compose**
```yaml
services:
  frontend:
    ports: ["58100:80"]
    build:
      args:
        VITE_API_URL: http://localhost:5050/api
        
  backend:
    ports: ["5050:5050"]
    environment:
      APP_URL: http://localhost:5050
      
  mysql:
    ports: ["53106:3306"]
```

### **Variáveis Críticas**
```bash
# Backend .env
APP_URL=http://localhost:5050
DB_HOST=mysql
DB_CONNECTION=mysql

# Frontend
VITE_API_URL=http://localhost:5050/api
```

**📖 Documentação**: `DOCKER_DEPLOY.md`

---

## ✅ CHECKLISTS

### **Antes de Desenvolver**
- [ ] Consultar `MELHORES_PRATICAS_SGAITI.md`
- [ ] Verificar padrões consolidados
- [ ] Seguir convenções de nomenclatura
- [ ] Usar Form Requests para validação

### **Antes de Deploy**
- [ ] Testes passando
- [ ] Build sem erros
- [ ] Variáveis de ambiente configuradas  
- [ ] Backup do banco
- [ ] Logs limpos

### **Code Review**
- [ ] Seguiu convenções
- [ ] Validações implementadas
- [ ] Tratamento de erros
- [ ] Logs informativos
- [ ] Documentação atualizada

**📖 Documentação**: `MELHORES_PRATICAS_SGAITI.md#checklist`

---

## 📚 DOCUMENTOS DE REFERÊNCIA

### **Por Prioridade de Leitura:**
1. 🔥 `MELHORES_PRATICAS_SGAITI.md` - **OBRIGATÓRIO**
2. 🏗️ `BACKEND_FRONTEND_SYNC.md` - Integração
3. 🗃️ `DATABASE_SCHEMA.md` - Estrutura do banco
4. 🚀 `DOCKER_DEPLOY.md` - Deploy
5. 🔄 `PLANO_SINCRONIZACAO_DEFINITIVO.md` - Compatibilidade
6. 📊 `DATABASE_ANALYSIS_REPORT.md` - Análise técnica
7. 📚 `API_REFERENCE.md` - Endpoints

### **Por Tipo de Trabalho:**
```yaml
Novo Feature:
  - MELHORES_PRATICAS_SGAITI.md
  - BACKEND_FRONTEND_SYNC.md
  
Bug Fix:
  - PLANO_SINCRONIZACAO_DEFINITIVO.md
  - MELHORES_PRATICAS_SGAITI.md#debug
  
Deploy:
  - DOCKER_DEPLOY.md
  - MELHORES_PRATICAS_SGAITI.md#checklist
  
Database:
  - DATABASE_SCHEMA.md
  - DATABASE_ANALYSIS_REPORT.md
```

---

**🎯 OBJETIVO**: Referência técnica completa e economia de tokens  
**📊 RESULTADO**: Desenvolvimento eficiente e padronizado  
**🏆 BENEFÍCIO**: Qualidade profissional consistente