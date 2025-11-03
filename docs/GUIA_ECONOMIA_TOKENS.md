# 💰 GUIA DE ECONOMIA DE TOKENS - SGAITI-UM

## 🎯 ESTRATÉGIAS PARA DESENVOLVIMENTO EFICIENTE

**Objetivo**: Maximizar produtividade minimizando uso de tokens  
**Método**: Padrões consolidados + Documentação estruturada  

---

## 📋 PADRÕES PRONTOS PARA COPIAR

### **🔧 Backend Laravel**

#### **Form Request Padrão**
```php
// Copie e adapte este padrão
class Store[Resource]Request extends FormRequest
{
    public function authorize(): bool { return true; }
    
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:table,email'],
            'status' => ['required', 'string', Rule::in(['ACTIVE', 'INACTIVE'])],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.unique' => 'Este email já está cadastrado.',
        ];
    }
}
```

#### **Controller CRUD Padrão**
```php
class [Resource]Controller extends Controller
{
    public function index()
    {
        return response()->json([Resource]::all());
    }
    
    public function store(Store[Resource]Request $request)
    {
        try {
            $item = [Resource]::create($request->validated());
            return response()->json([
                'message' => '[Resource] criado com sucesso',
                'data' => $item
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar [resource]',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Update[Resource]Request $request, [Resource] $item)
    {
        try {
            $item->update($request->validated());
            return response()->json([
                'message' => '[Resource] atualizado com sucesso',
                'data' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar [resource]',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

### **⚛️ Frontend React**

#### **Componente Padrão**
```tsx
interface [Component]Props {
    data: [Type];
    onAction: (item: [Type]) => void;
    className?: string;
}

const [Component]: React.FC<[Component]Props> = ({ 
    data, 
    onAction, 
    className = '' 
}) => {
    const [loading, setLoading] = useState(false);
    
    const handleAction = useCallback(async () => {
        setLoading(true);
        try {
            await onAction(data);
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao executar ação');
        } finally {
            setLoading(false);
        }
    }, [data, onAction]);
    
    return (
        <div className={`bg-white rounded-lg shadow p-4 ${className}`}>
            {/* Conteúdo */}
            <button 
                onClick={handleAction}
                disabled={loading}
                className={`px-4 py-2 rounded ${
                    loading 
                        ? 'bg-gray-600 cursor-not-allowed' 
                        : 'bg-blue-600 hover:bg-blue-700'
                } text-white`}
            >
                {loading ? 'Carregando...' : 'Ação'}
            </button>
        </div>
    );
};
```

#### **Hook de API Padrão**
```tsx
const use[Resource] = () => {
    const [items, setItems] = useState<[Type][]>([]);
    const [loading, setLoading] = useState(false);
    
    const load = useCallback(async () => {
        setLoading(true);
        try {
            const response = await api.get('/[resource]');
            setItems(response);
        } catch (error) {
            console.error('Erro ao carregar:', error);
        } finally {
            setLoading(false);
        }
    }, []);
    
    const create = useCallback(async (data: [Type]) => {
        const response = await api.post('/[resource]', data);
        setItems(prev => [...prev, response]);
        return response;
    }, []);
    
    const update = useCallback(async (id: string, data: [Type]) => {
        const response = await api.put(`/[resource]/${id}`, data);
        setItems(prev => prev.map(item => 
            item.id === id ? response : item
        ));
        return response;
    }, []);
    
    useEffect(() => { load(); }, [load]);
    
    return { items, loading, create, update, reload: load };
};
```

---

## 🔍 CHECKLIST ANTES DE PERGUNTAR

### **✅ Consulte Primeiro:**
- [ ] `MELHORES_PRATICAS_SGAITI.md` - Padrões consolidados
- [ ] `INDICE_TECNICO.md` - Referência técnica
- [ ] `BACKEND_FRONTEND_SYNC.md` - Integração
- [ ] Este documento - Padrões prontos

### **✅ Problemas Comuns (Soluções Prontas):**

#### **CORS Error**
```bash
# Solução: Verificar APP_URL
# Backend .env
APP_URL=http://localhost:5050

# Config CORS
'allowed_origins' => [
    'http://localhost:58100',
    'http://localhost:3000',
]
```

#### **Schema Mismatch**
```php
// Solução: Estratégia híbrida
$data = $request->only([
    'brand', 'manufacturer',     // Aceita ambos
    'condition', 'condition_rating',
]);

// Mapeamento automático
if (!isset($data['brand']) && isset($data['manufacturer'])) {
    $data['brand'] = $data['manufacturer'];
}
```

#### **Upload de Fotos**
```php
// Backend
$request->validate([
    'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240'
]);

$photoData = base64_encode(file_get_contents($photo->getPathname()));
cache()->put("asset_{$assetId}_photos", $photos, 3600);
```

```tsx
// Frontend
const handleUpload = async (files: File[]) => {
    setIsUploading(true);
    try {
        await Promise.all(files.map(file => api.addPhoto(assetId, file)));
        onPhotoChange(); // Recarregar dados
    } finally {
        setIsUploading(false);
    }
};
```

---

## 📚 REFERÊNCIAS RÁPIDAS

### **Por Tipo de Tarefa:**

#### **Novo CRUD:**
1. Copie padrão de Controller
2. Adapte Form Request
3. Use componente React padrão
4. Siga convenções de nomenclatura

#### **Bug Fix:**
1. `INDICE_TECNICO.md#troubleshooting`
2. Logs estruturados: `console.log("🔍 Debug:", data)`
3. Usar padrões de tratamento de erro

#### **Upload/Arquivo:**
1. Usar padrão de upload documentado
2. Validação: max 10MB, jpeg/png/gif
3. Cache Laravel para persistência

#### **Compatibilidade:**
1. `PLANO_SINCRONIZACAO_DEFINITIVO.md`
2. Estratégia híbrida (aceitar ambos formatos)
3. Mapeamento automático

---

## 🎯 TEMPLATES DE MENSAGENS

### **Para Requests Específicos:**

#### **"Preciso implementar CRUD de [Resource]"**
```
Consultei: MELHORES_PRATICAS_SGAITI.md
Quero implementar: CRUD de [Resource]
Padrão base: Usar template de Controller + Form Request
Específico: [detalhe específico do resource]
```

#### **"Erro de [tipo]"**
```
Consultei: INDICE_TECNICO.md#troubleshooting
Erro: [mensagem exata]
Contexto: [o que estava fazendo]
Já tentei: [soluções da documentação]
```

#### **"Upload não funciona"**
```
Consultei: MELHORES_PRATICAS_SGAITI.md#upload
Implementei: Padrão documentado
Erro específico: [logs do console]
Arquivo: [tipo/tamanho]
```

---

## 💡 DICAS DE ECONOMIA

### **Reutilize Sempre:**
- ✅ Padrões de componentes React
- ✅ Estruturas de Controller Laravel
- ✅ Validações Form Request
- ✅ Tratamento de erros consolidado

### **Evite Perguntar:**
- ❌ Como criar Form Request (use template)
- ❌ Como fazer upload (use padrão)
- ❌ Como resolver CORS (verifique APP_URL)
- ❌ Convenções (consulte documentação)

### **Pergunte Quando:**
- ✅ Lógica específica do negócio
- ✅ Requisitos únicos não documentados
- ✅ Integrações externas
- ✅ Otimizações específicas

---

## 📊 MÉTRICAS DE SUCESSO

### **Antes da Documentação:**
- 🔴 Repetição de perguntas básicas
- 🔴 Reescrita de padrões existentes
- 🔴 Debugging sem estrutura
- 🔴 Inconsistência de código

### **Depois da Documentação:**
- ✅ Consulta autônoma de padrões
- ✅ Reutilização de templates
- ✅ Debugging estruturado
- ✅ Código consistente

### **Economia Estimada:**
- 🎯 **70% menos tokens** em tarefas repetitivas
- 🎯 **50% menos tempo** em debugging
- 🎯 **90% menos perguntas** básicas
- 🎯 **100% mais qualidade** no código

---

**💰 LEMBRE-SE**: A documentação é seu primeiro recurso!  
**🎯 OBJETIVO**: Desenvolver com eficiência e qualidade profissional  
**🏆 RESULTADO**: Economia de tokens + Código sustentável