# 🏆 MELHORES PRÁTICAS - SGAITI-UM

## 📚 GUIA CONSOLIDADO DE DESENVOLVIMENTO

**Sistema**: SGAITI-UM (Sistema de Gestão de Ativos de TI da Unidade Militar)  
**Versão**: 2.0 (Laravel Backend + React Frontend)  
**Data**: Novembro 2024  

---

## 🎯 ÍNDICE DE DOCUMENTAÇÃO

### 📋 **DOCUMENTOS PRINCIPAIS**
1. **[MELHORES_PRATICAS_SGAITI.md](#)** - Este documento
2. **[PLANO_SINCRONIZACAO_DEFINITIVO.md](#)** - Estratégia de compatibilidade
3. **[SINCRONIZACAO_SCHEMA_REPORT.md](#)** - Análise de schema
4. **[AGENTS.md](#)** - Instruções gerais do projeto

### 📂 **ESTRUTURA DE DOCUMENTAÇÃO**
```
docs/
├── MELHORES_PRATICAS_SGAITI.md     # ← VOCÊ ESTÁ AQUI
├── API_REFERENCE.md                # Referência da API
├── BACKEND_FRONTEND_SYNC.md        # Sincronização
├── DATABASE_SCHEMA.md              # Schema do banco
├── DOCKER_DEPLOY.md                # Deploy com Docker
├── BEST_PRACTICES.MD               # Práticas gerais
└── README.md                       # Visão geral
```

---

## 🔧 ARQUITETURA E TECNOLOGIAS

### **Stack Tecnológico**
```yaml
Backend:
  - Framework: Laravel 12 (PHP)
  - Banco: MySQL 8.0
  - API: REST endpoints (/api)
  - Validação: Form Requests
  - Cache: Laravel Cache (fotos)

Frontend:
  - Framework: React 18.3.1 + TypeScript
  - Build: Vite 7.x
  - CSS: Tailwind CSS
  - Estado: Props drilling (App.tsx centralizado)

Deploy:
  - Docker Compose
  - Nginx (frontend)
  - PHP-FPM (backend)
  - MySQL container
```

### **Portas Padrão**
```yaml
Desenvolvimento:
  - Frontend: http://localhost:58100
  - Backend: http://localhost:5050/api
  - MySQL: localhost:53106
  - phpMyAdmin: http://localhost:58090
```

---

## 📝 CONVENÇÕES DE CÓDIGO

### **Nomenclatura**
```typescript
// ✅ CORRETO
// Variáveis: camelCase
const assetData = {};
const currentUser = {};

// Componentes: PascalCase
const AssetManagement = () => {};
const UserModal = () => {};

// Constantes: UPPER_SNAKE_CASE
const API_BASE_URL = 'http://localhost:5050/api';
const MAX_FILE_SIZE = 10240; // 10MB

// Tipos: PascalCase
interface Asset {}
enum AssetStatus {}
```

```php
// Backend PHP (Laravel)
// Classes: PascalCase
class AssetController {}

// Métodos: camelCase
public function updateAsset() {}

// Propriedades: snake_case (banco)
'serial_number', 'patrimony_id', 'created_at'
```

### **Estrutura de Arquivos**
```
src/
├── components/           # Componentes React
│   ├── AssetManagement.tsx
│   ├── Dashboard.tsx
│   └── ...
├── services/            # APIs e serviços
│   ├── api.ts
│   └── mockData.ts
├── types.ts             # Definições TypeScript
└── index.css           # Estilos globais


├── app/
│   ├── Http/Controllers/  # Controllers Laravel
│   ├── Models/           # Models Eloquent
│   └── Http/Requests/    # Form Requests
├── routes/api.php        # Rotas da API
└── database/migrations/  # Migrações
```

---

## 🛡️ SEGURANÇA E VALIDAÇÃO

### **Form Requests (Backend)**
```php
// ✅ SEMPRE usar Form Requests para validação
class StoreAssetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'brand' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', Rule::in([...])],
            'serial_number' => ['nullable', 'unique:assets'],
        ];
    }

    public function messages(): array
    {
        return [
            'brand.required' => 'A marca é obrigatória.',
            // Mensagens em português
        ];
    }
}

// ❌ NUNCA usar diretamente
$request->all(); // PERIGOSO - Mass Assignment
```

### **Validação Frontend**
```typescript
// ✅ Validação no formulário
const validateForm = (data: Asset): string[] => {
    const errors: string[] = [];
    
    if (!data.name?.trim()) {
        errors.push('Nome é obrigatório');
    }
    
    if (!data.type) {
        errors.push('Tipo é obrigatório');
    }
    
    return errors;
};
```

### **Sanitização de Dados**
```typescript
// ✅ Limpar dados antes do envio
const sanitizeAssetData = (data: Asset): Asset => {
    return {
        ...data,
        name: data.name?.trim(),
        serial_number: data.serial_number?.toUpperCase(),
        // Remover campos vazios
        notes: data.notes?.trim() || undefined,
    };
};
```

---

## 🔄 COMPATIBILIDADE E MIGRAÇÃO

### **Estratégia Híbrida (Implementada)**
```php
// Backend aceita AMBOS os formatos (antigo + novo)
public function update(Request $request, Asset $asset)
{
    $data = $request->only([
        // Campos NOVOS (preferidos)
        'brand', 'type', 'condition', 'patrimony_number',
        // Campos ANTIGOS (compatibilidade)
        'manufacturer', 'condition_rating', 'patrimony_id',
    ]);
    
    // Mapeamento automático antigo → novo
    if (isset($data['manufacturer']) && !isset($data['brand'])) {
        $data['brand'] = $data['manufacturer'];
    }
    
    $asset->update($data);
}
```

### **Mapeamento de Campos**
```yaml
Frontend_Antigo → Backend_Novo:
  manufacturer → brand
  condition_rating → condition (1-5 → NOVO/BOM/REGULAR)
  patrimony_id → patrimony_number
  purchase_price → purchase_value
  category → type (inferido)
```

---

## 📸 UPLOAD DE ARQUIVOS

### **Implementação Segura**
```php
// Backend - Validação robusta
$request->validate([
    'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
]);

// Conversão para Base64 (compatibilidade)
$photoData = base64_encode(file_get_contents($photo->getPathname()));
$photoResponse = [
    'id' => uniqid(),
    'url' => "data:{$mimeType};base64,{$photoData}",
    'created_at' => now()->toISOString()
];
```

```typescript
// Frontend - Interface responsiva
const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files) return;
    
    setIsUploading(true);
    try {
        const files = Array.from(e.target.files);
        await Promise.all(files.map(file => assetsApi.addPhoto(assetId, file)));
        onPhotoChange(); // Recarregar dados
        alert(`${files.length} foto(s) enviada(s) com sucesso!`);
    } catch (error) {
        console.error("Erro no upload:", error);
        alert(`Falha no upload: ${error.message}`);
    } finally {
        setIsUploading(false);
        e.target.value = ''; // Limpar input
    }
};
```

---

## 🎨 INTERFACE E UX

### **Componentes Padrão**
```tsx
// ✅ Componente bem estruturado
interface ComponentProps {
    data: Type;
    onAction: (item: Type) => void;
    className?: string;
}

const Component: React.FC<ComponentProps> = ({ 
    data, 
    onAction, 
    className = '' 
}) => {
    // Estados locais
    const [loading, setLoading] = useState(false);
    
    // Handlers
    const handleClick = useCallback(() => {
        setLoading(true);
        onAction(data);
        setLoading(false);
    }, [data, onAction]);
    
    return (
        <div className={`base-classes ${className}`}>
            {/* Conteúdo */}
        </div>
    );
};
```

### **Estados de Loading**
```tsx
// ✅ Feedback visual para usuário
const UploadButton = ({ isUploading }: { isUploading: boolean }) => (
    <button 
        disabled={isUploading}
        className={`px-4 py-2 rounded ${
            isUploading 
                ? 'bg-gray-600 cursor-not-allowed' 
                : 'bg-blue-600 hover:bg-blue-700'
        }`}
    >
        {isUploading ? (
            <>
                <Spinner className="mr-2" />
                Enviando...
            </>
        ) : (
            'Adicionar Fotos'
        )}
    </button>
);
```

### **Mensagens de Erro**
```typescript
// ✅ Mensagens em português, específicas
const ErrorMessages = {
    UPLOAD_FAILED: 'Falha ao enviar arquivo. Tente novamente.',
    INVALID_FILE: 'Arquivo deve ser uma imagem (JPG, PNG, GIF)',
    FILE_TOO_LARGE: 'Arquivo muito grande. Máximo 10MB.',
    NETWORK_ERROR: 'Erro de conexão. Verifique sua internet.',
};
```

---

## 🗃️ BANCO DE DADOS

### **Migrações**
```php
// ✅ Migrações reversíveis
Schema::table('assets', function (Blueprint $table) {
    // Adicionar novos campos mantendo compatibilidade
    $table->string('brand')->nullable()->after('manufacturer');
    $table->string('type')->nullable()->after('category');
    $table->string('condition')->nullable()->after('condition_rating');
    
    // NÃO remover campos antigos ainda
    // $table->dropColumn('manufacturer'); // ❌ NUNCA na primeira migração
});
```

### **Models Eloquent**
```php
// ✅ Model bem definido
class Asset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Campos NOVOS e ANTIGOS (compatibilidade)
        'brand', 'manufacturer',
        'type', 'condition', 'condition_rating',
        'patrimony_number', 'patrimony_id',
        // ... outros campos
    ];
    
    protected $casts = [
        'acquisition_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    // Relacionamentos
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}
```

---

## 🐛 DEBUG E LOGGING

### **Frontend (React)**
```typescript
// ✅ Logs estruturados com emojis
console.log("🔍 Upload iniciado...", files);
console.log("📁 Arquivos selecionados:", files.length);
console.log("✅ Upload concluído:", result);
console.error("❌ Erro no upload:", error);

// Logs condicionais (produção)
const isDev = process.env.NODE_ENV === 'development';
if (isDev) {
    console.log("🔧 Debug data:", data);
}
```

### **Backend (Laravel)**
```php
// ✅ Logs Laravel
Log::info('Asset created', ['asset_id' => $asset->id]);
Log::error('Upload failed', ['error' => $e->getMessage()]);

// Debug queries (desenvolvimento)
DB::listen(function ($query) {
    Log::debug($query->sql, $query->bindings);
});
```

---

## 🔄 API DESIGN

### **Endpoints RESTful**
```yaml
# ✅ Padrão REST
GET    /api/assets           # Listar
POST   /api/assets           # Criar
GET    /api/assets/{id}      # Visualizar
PUT    /api/assets/{id}      # Atualizar
DELETE /api/assets/{id}      # Excluir

# Sub-recursos
POST   /api/assets/{id}/photos        # Upload foto
DELETE /api/assets/{id}/photos/{pid}  # Excluir foto
```

### **Respostas Padronizadas**
```php
// ✅ Estrutura consistente
// Sucesso
return response()->json([
    'message' => 'Ativo criado com sucesso',
    'data' => $asset
], 201);

// Erro
return response()->json([
    'message' => 'Erro de validação',
    'errors' => $validator->errors()
], 422);
```

---

## 🚀 DEPLOY E PRODUÇÃO

### **Docker Compose**
```yaml
# ✅ Configuração robusta
services:
  frontend:
    build:
      args:
        VITE_API_URL: ${VITE_API_URL:-http://localhost:5050/api}
    ports:
      - "${FRONTEND_HOST_PORT:-58100}:80"
      
  backend:
    environment:
      APP_ENV: production
      APP_DEBUG: false
      DB_HOST: mysql
    ports:
      - "${BACKEND_HOST_PORT:-5050}:5050"
```

### **Variáveis de Ambiente**
```bash
# .env.example
APP_NAME="SGAITI-UM"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:5050

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sgaiti_db
DB_USERNAME=sgaiti_user
DB_PASSWORD=sgaiti_pass

CACHE_DRIVER=file
SESSION_DRIVER=file
```

---

## ✅ CHECKLIST DE QUALIDADE

### **Antes de cada Deploy**
- [ ] Testes passando (backend)
- [ ] Build frontend sem erros
- [ ] Migrações testadas
- [ ] Backup do banco
- [ ] Variáveis de ambiente configuradas
- [ ] Logs de erro limpos
- [ ] Performance testada

### **Code Review**
- [ ] Seguiu convenções de nomenclatura
- [ ] Validações implementadas
- [ ] Tratamento de erros adequado
- [ ] Logs informativos
- [ ] Código documentado
- [ ] Sem hardcoded values

---

## 🎓 LIÇÕES APRENDIDAS

### **Problemas Evitados**
1. **Schema Incompatível**: Estratégia híbrida evita breaking changes
2. **CORS Issues**: Configuração adequada no Laravel
3. **Upload de Fotos**: Cache para persistência sem tabela
4. **Validação**: Form Requests evitam vulnerabilidades
5. **Estado Frontend**: useEffect para dados atualizados

### **Boas Práticas Consolidadas**
1. **Compatibilidade Gradual**: Aceitar formatos antigo + novo
2. **Feedback Visual**: Loading states e mensagens claras
3. **Debug Estruturado**: Logs com emojis e contexto
4. **Validação Dupla**: Frontend + Backend
5. **Documentação Ativa**: Atualizar com mudanças

---

## 📞 PRÓXIMOS PASSOS

### **Melhorias Recomendadas**
1. **Testes Automatizados**: PHPUnit + Jest
2. **Cache Redis**: Performance em produção
3. **File Storage**: Substituir cache por S3/local
4. **Auth Robusta**: Laravel Sanctum
5. **Monitoramento**: Health checks e métricas

### **Refatorações Futuras**
1. **Context API**: Substituir prop drilling
2. **API Paginação**: Para grandes datasets
3. **Lazy Loading**: Otimizar carregamento
4. **PWA**: Funcionalidade offline
5. **Mobile First**: Responsividade completa

---

**📚 Este documento deve ser atualizado a cada nova implementação**  
**🎯 Objetivo: Manter qualidade e consistência no desenvolvimento**  
**🏆 Resultado: Código sustentável e profissional**

---

## 🎯 LIÇÕES APRENDIDAS - MÓDULO INVENTÁRIO

### **Problema: Sincronização Frontend ↔ Backend**

#### **❌ ERRO TÍPICO: Desalinhamento de Campos**
```typescript
// ERRO: Frontend esperando campos que não existem
if (record.foundItems && record.foundItems.length > 0) {
    // Backend retorna 'found_items', não 'foundItems'
}
```

#### **✅ SOLUÇÃO: Padronização de Campos**
```typescript
// CORRETO: Usar exatamente os campos do backend
if (record.found_items && record.found_items.length > 0) {
    foundAssets = record.found_items.map(item => ({
        ...item,
        observation: item.observation || ''
    }));
}
```

#### **🔧 ESTRATÉGIA IMPLEMENTADA:**
1. **Backend**: Atributos calculados com `$appends`
2. **Frontend**: Campos alinhados com snake_case
3. **Validação**: Verificações `.length || 0` para segurança

---

### **Problema: Atributos Undefined em Modais**

#### **❌ ERRO TÍPICO: Acesso Direto Sem Verificação**
```typescript
// ERRO: rank pode ser undefined
{responsibleUser.rank} {responsibleUser.name}

// ERRO: array pode não existir
record.foundAssets.length
```

#### **✅ SOLUÇÃO: Verificações Defensivas**
```typescript
// CORRETO: Fallbacks seguros
{responsibleUser?.rank || 'N/A'} {responsibleUser?.name || 'Usuário não encontrado'}

// CORRETO: Arrays com fallback
record.found_items?.length || 0
```

---

### **Problema: Persistência de Estado Local**

#### **❌ ERRO TÍPICO: Só Atualizar Estado Local**
```typescript
// ERRO: Movimentação não persiste
const handleMoveItems = () => {
    setActiveSession(prev => { /* update local */ });
    // Faltou: chamada para API
};
```

#### **✅ SOLUÇÃO: Update Local + Persistência**
```typescript
// CORRETO: Responsividade + Persistência
const handleMoveItems = async () => {
    // 1. Update local primeiro (responsividade)
    setActiveSession(prev => { /* update local */ });
    
    // 2. Persistir no backend
    await inventoryApi.update(id, { found_items: foundItems });
    
    console.log("✅ Movimentação persistida");
};
```

---

### **Problema: Model Laravel Mal Configurado**

#### **❌ ERRO TÍPICO: Relacionamentos Não Expostos**
```php
// ERRO: Frontend não recebe dados relacionados
public function getFoundItemsAttribute() {
    return $this->inventoryAssets(); // Retorna Query, não dados
}
```

#### **✅ SOLUÇÃO: Atributos Calculados Corretos**
```php
// CORRETO: Dados processados e expostos
protected $appends = ['found_items', 'pending_items', 'summary'];

public function getFoundItemsAttribute() {
    return $this->inventoryAssets()->with('asset')->get()->map(function ($inventoryAsset) {
        $asset = $inventoryAsset->asset;
        if ($asset) {
            $asset->observation = $inventoryAsset->observation;
            return $asset;
        }
        return null;
    })->filter()->values();
}
```

---

### **Problema: Logs Insuficientes para Debug**

#### **❌ ERRO TÍPICO: Logs Vagos**
```typescript
// ERRO: Log não informativo
console.log("Dados salvos");
```

#### **✅ SOLUÇÃO: Logs Estruturados**
```typescript
// CORRETO: Logs detalhados
console.log("✅ Dados carregados:", {
    found: foundAssets.length,
    pending: pendingAssets.length,
    uncatalogued: uncataloguedDescriptions.length,
    foundIds: foundAssets.map(a => a.id),
    pendingIds: pendingAssets.map(a => a.id)
});
```

---

## 🛡️ CHECKLIST ANTI-PANE

### **Antes de Implementar Funcionalidade:**
- [ ] Verificar alinhamento de campos frontend ↔ backend
- [ ] Confirmar se Model Laravel expõe dados necessários
- [ ] Testar com dados undefined/null
- [ ] Implementar logs estruturados para debug

### **Antes de Deploy:**
- [ ] Testar fluxo completo: create → update → reload
- [ ] Verificar contadores e summaries
- [ ] Confirmar persistência após refresh
- [ ] Validar modais e exports

### **Debug de Problemas:**
1. **Verificar API**: `curl -s http://localhost:5050/api/endpoint`
2. **Console Logs**: Verificar erros de undefined/null
3. **Network Tab**: Confirmar payloads corretos
4. **Backend Logs**: `docker-compose logs backend`

---

## 🎯 PATTERNS CONSOLIDADOS

### **Model Laravel com Atributos Calculados:**
```php
protected $appends = ['computed_field'];

public function getComputedFieldAttribute() {
    return $this->relations()->processed()->values();
}
```

### **Frontend com Verificações Defensivas:**
```typescript
const safeData = apiData?.field?.length || 0;
const safeUser = users.find(u => u.id === id) || fallbackUser;
```

### **Persistência com Responsividade:**
```typescript
// 1. Update local (UX responsiva)
setState(newState);

// 2. Persist backend (dados seguros)
await api.update(data);
```

---

**📝 DOCUMENTADO EM:** 2024-11-03  
**📊 STATUS:** Módulo Inventário 100% Funcional  
**🎯 PRÓXIMO:** Aplicar patterns em outros módulos