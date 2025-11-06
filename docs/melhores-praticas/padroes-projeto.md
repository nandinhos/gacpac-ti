# 📋 MELHORES PRÁTICAS - PADRÕES DO PROJETO SGAITI-UM

## 🎯 OBJETIVO

Este documento consolida as **melhores práticas** estabelecidas durante o desenvolvimento do SGAITI-UM, evitando anti-patterns e garantindo qualidade consistente.

---

## 🏗️ ARQUITETURA E DESIGN

### **1. Padrão MVC Estruturado**
```
Laravel (Backend)
├── Controllers/     # Lógica de negócio, validação
├── Models/         # Relacionamentos, regras de negócio
├── Requests/       # Validação de entrada
├── Services/       # Lógica complexa reutilizável
└── Resources/      # Transformação de dados API

React (Frontend)
├── Components/     # UI reutilizável
├── Services/       # Chamadas API
├── Types/          # Definições TypeScript
└── Utils/          # Funções auxiliares
```

### **2. State Management Simples**
```tsx
// ✅ BOM: Prop drilling direto (SGAITI-UM)
<App>
  <Dashboard data={assets} onUpdate={handleUpdate} />
  <AssetManagement assets={assets} onDataChange={loadData} />
</App>

// ❌ RUIM: Redux desnecessário para apps simples
// Evite complexidade excessiva
```

### **3. API RESTful Consistente**
```php
// ✅ BOM: Endpoints padronizados
GET    /api/assets           # Listar
POST   /api/assets           # Criar
GET    /api/assets/{id}      # Detalhes
PUT    /api/assets/{id}      # Atualizar
DELETE /api/assets/{id}      # Excluir
GET    /api/assets/search    # Busca
```

---

## 🔒 SEGURANÇA COMO PRIORIDADE

### **1. Form Requests Obrigatórios**
```php
// ✅ BOM: Sempre usar Form Request
public function store(StoreAssetRequest $request) {
    return Asset::create($request->validated());
}

// ❌ RUIM: Nunca usar $request->all()
public function store(Request $request) {
    return Asset::create($request->all()); // VULNERÁVEL!
}
```

### **2. Validação Robusta**
```php
// ✅ BOM: Validação completa
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'military_id' => [
            'required',
            'string',
            'regex:/^[A-Z0-9-]+$/',
            Rule::unique('military_users')->ignore($this->user)
        ],
        'sector_id' => 'required|exists:sectors,id',
        'user_role' => 'required|in:user,commission,admin'
    ];
}
```

### **3. Autenticação Breeze**
```php
// ✅ BOM: Usar Breeze/Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('assets', AssetController::class);
});

// ❌ RUIM: Autenticação customizada
// Problemas de manutenção e segurança
```

---

## 🧪 TESTES COMO HABITO

### **1. Testes Automatizados Obrigatórios**
```php
// ✅ BOM: Cobertura completa
public function test_can_create_asset_with_valid_data()
{
    $data = [
        'name' => 'Notebook Dell',
        'brand' => 'Dell',
        'serial_number' => 'ABC123',
        'category' => 'Computação',
        'status' => 'Disponível'
    ];

    $response = $this->postJson('/api/assets', $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('assets', $data);
}
```

### **2. Factories para Dados Consistentes**
```php
// ✅ BOM: Factories bem estruturadas
class AssetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'qr_code' => 'SGAITI-' . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'name' => $this->faker->randomElement(['Notebook', 'Monitor', 'Teclado']) . ' ' . $this->faker->company,
            'brand' => $this->faker->company,
            'category' => $this->faker->randomElement(['Computação', 'Periféricos', 'Energia']),
            'status' => $this->faker->randomElement(['Em Uso', 'Disponível', 'Manutenção']),
            'sector_id' => Sector::factory(),
        ];
    }
}
```

### **3. Testes de Segurança**
```php
// ✅ BOM: Testar vulnerabilidades
public function test_cannot_create_asset_with_mass_assignment()
{
    $maliciousData = [
        'name' => 'Asset Test',
        'is_admin' => true, // Campo que não deveria existir
        'password' => 'hacked' // Campo sensível
    ];

    $response = $this->postJson('/api/assets', $maliciousData);

    $response->assertStatus(422); // Deve falhar
    $this->assertDatabaseMissing('assets', ['is_admin' => true]);
}
```

---

## ⚡ PERFORMANCE OTIMIZADA

### **1. Eager Loading Sistemático**
```php
// ✅ BOM: Carregar relacionamentos
public function index()
{
    return Asset::with(['sector', 'custodian'])->paginate(15);
}

// ❌ RUIM: N+1 queries
$assets = Asset::all(); // Sem relacionamentos
foreach ($assets as $asset) {
    echo $asset->sector->name; // Query extra por asset!
}
```

### **2. Paginação Obrigatória**
```php
// ✅ BOM: Sempre paginar
public function index(Request $request)
{
    $query = Asset::with('sector');

    // Filtros...
    if ($request->category) {
        $query->where('category', $request->category);
    }

    return $query->paginate(15); // Nunca ->get() para listas grandes
}
```

### **3. Cache Estratégico**
```php
// ✅ BOM: Cache de dados estáticos
public function getSectors()
{
    return Cache::remember('sectors', 3600, function () {
        return Sector::select('id', 'name')->get();
    });
}
```

---

## 🔄 MANUTENIBILIDADE

### **1. Código Auto-Documentado**
```php
// ✅ BOM: Nomes descritivos
class CustodyLogController extends Controller
{
    public function checkin(CustodyLog $custody, Request $request)
    {
        // Método claro sobre o que faz
    }
}

// ❌ RUIM: Nomes genéricos
class AssetController extends Controller
{
    public function update($id) // O que atualiza? Como?
    {
        // Código confuso
    }
}
```

### **2. Tratamento de Erros Consistente**
```php
// ✅ BOM: Try-catch padronizado
public function store(StoreAssetRequest $request)
{
    try {
        $asset = Asset::create($request->validated());

        return response()->json([
            'message' => 'Ativo criado com sucesso',
            'data' => $asset
        ], 201);

    } catch (\Exception $e) {
        Log::error('Erro ao criar ativo', [
            'error' => $e->getMessage(),
            'data' => $request->validated()
        ]);

        return response()->json([
            'message' => 'Erro interno do servidor'
        ], 500);
    }
}
```

### **3. Separação de Responsabilidades**
```php
// ✅ BOM: Service layer para lógica complexa
class InventoryService
{
    public function processInventory(InventoryRecord $inventory)
    {
        // Lógica complexa isolada
        $this->validateItems($inventory);
        $this->updateStatuses($inventory);
        $this->generateReport($inventory);
    }
}

class InventoryController extends Controller
{
    public function complete(CompleteInventoryRequest $request, InventoryRecord $inventory)
    {
        app(InventoryService::class)->processInventory($inventory);

        return response()->json(['message' => 'Inventário concluído']);
    }
}
```

---

## 🎨 FRONTEND CONSISTENTE

### **1. Componentes Reutilizáveis**
```tsx
// ✅ BOM: Componente base reutilizável
interface ButtonProps {
  variant?: 'primary' | 'secondary' | 'danger';
  size?: 'sm' | 'md' | 'lg';
  loading?: boolean;
  children: React.ReactNode;
  onClick: () => void;
}

const Button: React.FC<ButtonProps> = ({
  variant = 'primary',
  size = 'md',
  loading = false,
  children,
  onClick
}) => (
  <button
    className={getButtonClasses(variant, size)}
    onClick={onClick}
    disabled={loading}
  >
    {loading ? <Spinner /> : children}
  </button>
);

// Uso consistente em toda a aplicação
<Button variant="primary" onClick={handleSave}>Salvar</Button>
```

### **2. Hooks Customizados**
```tsx
// ✅ BOM: Lógica reutilizável
const useAssets = () => {
  const [assets, setAssets] = useState<Asset[]>([]);
  const [loading, setLoading] = useState(false);

  const loadAssets = useCallback(async () => {
    setLoading(true);
    try {
      const data = await assetsApi.getAll();
      setAssets(data);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    loadAssets();
  }, [loadAssets]);

  return { assets, loading, refetch: loadAssets };
};
```

### **3. TypeScript Rigoroso**
```tsx
// ✅ BOM: Tipagem completa
interface Asset {
  id: number;
  name: string;
  brand?: string;
  manufacturer?: string; // Compatibilidade
  category: string;
  status: AssetStatus;
  sector_id?: number;
  sector_name?: string; // Campo computado
  photos: AssetPhoto[];
  created_at?: string;
}

// ❌ RUIM: any everywhere
const handleData = (data: any) => {
  console.log(data.name); // Sem segurança de tipo
};
```

---

## 🚫 ANTI-PATTERNS A EVITAR

### **1. God Classes/Components**
```php
// ❌ RUIM: Controller fazendo tudo
class AssetController extends Controller
{
    public function store(Request $request)
    {
        // Validação manual
        if (!$request->name) throw new Exception('Nome obrigatório');

        // Lógica de negócio
        $qrCode = $this->generateQrCode();

        // Acesso direto ao banco
        DB::table('assets')->insert([...]);

        // Envio de email
        Mail::to('admin@fab.mil.br')->send(new AssetCreated());

        // Log
        Log::info('Asset created');
    }
}
```

### **2. useEffect Loops**
```tsx
// ❌ RUIM: Loop infinito
useEffect(() => {
  const fetchData = async () => {
    const data = await api.getData();
    setData(data);
  };

  fetchData(); // Sem dependências = loop infinito
}, []); // Array vazio mas função não memoizada

// ✅ BOM: Callback memoizado
const fetchData = useCallback(async () => {
  const data = await api.getData();
  setData(data);
}, []);

useEffect(() => {
  fetchData();
}, [fetchData]);
```

### **3. Queries Ineficientes**
```php
// ❌ RUIM: Queries desnecessárias
public function getAssetsWithSectors()
{
    $assets = Asset::all(); // Query 1

    foreach ($assets as $asset) {
        $asset->sector; // Query N (N+1 problem)
        $asset->custodian; // Query N
        $asset->photos; // Query N
    }

    return $assets; // Queries = 1 + (N × 3)
}

// ✅ BOM: Eager loading
public function getAssetsWithSectors()
{
    return Asset::with(['sector', 'custodian', 'photos'])->get(); // Query = 4 (1 principal + 3 joins)
}
```

### **4. Estado Global Desnecessário**
```tsx
// ❌ RUIM: Redux para app simples
const assetSlice = createSlice({
  name: 'assets',
  initialState: [],
  reducers: {
    setAssets: (state, action) => action.payload,
    addAsset: (state, action) => [...state, action.payload],
    // ... 10+ reducers para 3 campos
  }
});

// ✅ BOM: Prop drilling direto
const App = () => {
  const [assets, setAssets] = useState([]);

  return (
    <AssetManagement
      assets={assets}
      onAssetsChange={setAssets}
    />
  );
};
```

---

## 📋 CHECKLIST DE QUALIDADE

### **Commit Checklist**
- [ ] **Testes passando** (`php artisan test`)
- [ ] **Linting OK** (`./vendor/bin/phpcs`)
- [ ] **TypeScript OK** (sem erros no IDE)
- [ ] **Form Requests** implementados
- [ ] **Eager loading** aplicado
- [ ] **Paginação** em listas
- [ ] **Tratamento de erros** adequado

### **Code Review Checklist**
- [ ] **Segurança**: Form Requests, validação, auth
- [ ] **Performance**: Eager loading, paginação, cache
- [ ] **Manutenibilidade**: Nomes claros, responsabilidade única
- [ ] **Testes**: Cobertura adequada, cenários edge
- [ ] **Consistência**: Padrões do projeto seguidos

### **Deploy Checklist**
- [ ] **Migrations** executadas
- [ ] **Seeders** rodados
- [ ] **Permissões** corretas
- [ ] **Environment** configurado
- [ ] **Backup** realizado
- [ ] **Monitoramento** ativo

---

## 🎯 PRINCÍPIOS GUIADORES

### **1. Segurança First**
> Toda funcionalidade deve ser segura por padrão. Validação, autenticação e autorização são obrigatórios.

### **2. Performance Matters**
> Otimizar queries, usar cache, implementar paginação. Performance não é opcional.

### **3. Manutenibilidade é Rei**
> Código legível, bem estruturado e documentado. Facilite a vida de quem virá depois.

### **4. Testes São Obrigatórios**
> Não há código em produção sem testes. Testes garantem qualidade e previnem regressões.

### **5. Consistência é Fundamental**
> Seguir padrões estabelecidos. Consistência > criatividade em projetos maduros.

### **6. Fail Fast, Fail Safe**
> Erros devem ser tratados graciosamente. Nunca expor detalhes internos para usuários.

---

*Estas práticas foram validadas durante o desenvolvimento do SGAITI-UM e devem ser seguidas por toda a equipe. Elas garantem qualidade, segurança e manutenibilidade do sistema.*
