# 📚 GUIA DE PROGRAMAÇÃO - PADRÕES DE CODIFICAÇÃO SGAITI-UM

## 🎯 VISÃO GERAL

Este documento estabelece os **padrões de codificação** específicos do projeto SGAITI-UM. O objetivo é garantir **consistência**, **manutenibilidade** e **qualidade** em todo o código.

---

## 🏗️ ARQUITETURA GERAL

### **Stack Tecnológica**
- **Backend**: Laravel 11 + PHP 8.2+
- **Frontend**: React 18 + TypeScript + Vite
- **Banco**: MySQL 8.0
- **Autenticação**: Laravel Breeze + Sanctum
- **SPA**: Inertia.js
- **Styling**: Tailwind CSS

### **Padrões Arquiteturais**
- **MVC** no backend (Laravel)
- **Component-based** no frontend (React)
- **Prop drilling** para state management (simples e direto)
- **RESTful API** com recursos aninhados
- **Form Requests** para validação

---

## 🔧 PADRÕES BACKEND (LARAVEL)

### **1. Controllers**

#### **Estrutura Padrão**
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelName;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;

class ModelNameController extends Controller
{
    public function index(Request $request)
    {
        // Filtros e paginação
        $query = ModelName::query();

        // Aplicar filtros
        if ($request->has('filter')) {
            $query->where('field', $request->filter);
        }

        return $query->get();
    }

    public function store(StoreRequest $request)
    {
        try {
            $model = ModelName::create($request->validated());

            return response()->json([
                'message' => 'Recurso criado com sucesso',
                'data' => $model
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar recurso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(ModelName $model)
    {
        return $model->load(['relationships']);
    }

    public function update(UpdateRequest $request, ModelName $model)
    {
        try {
            $model->update($request->validated());

            return response()->json([
                'message' => 'Recurso atualizado com sucesso',
                'data' => $model
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar recurso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ModelName $model)
    {
        try {
            $model->delete();

            return response()->json([
                'message' => 'Recurso excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir recurso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

#### **Regras para Controllers**
- ✅ **Route Model Binding** (`$model` como parâmetro)
- ✅ **Form Requests** para validação
- ✅ **Try-catch** em operações críticas
- ✅ **Respostas JSON padronizadas**
- ✅ **Mensagens em português**
- ✅ **Códigos HTTP apropriados**

### **2. Models**

#### **Estrutura Padrão**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelName extends Model
{
    use HasFactory;

    protected $fillable = [
        'field1',
        'field2',
        // Lista completa de campos fillable
    ];

    protected $casts = [
        'field_date' => 'date',
        'field_boolean' => 'boolean',
        'field_decimal' => 'decimal:2',
        'field_json' => 'array',
    ];

    protected $appends = [
        'computed_field',
    ];

    // Relacionamentos
    public function relationshipName(): BelongsTo|HasMany
    {
        return $this->belongsTo|hasMany(RelatedModel::class);
    }

    // Accessors/Mutators
    public function getComputedFieldAttribute(): mixed
    {
        // Lógica do accessor
    }

    public function setFieldNameAttribute($value): void
    {
        // Lógica do mutator
    }
}
```

#### **Regras para Models**
- ✅ **$fillable** explícito (segurança)
- ✅ **$casts** para tipos corretos
- ✅ **Relacionamentos** tipados
- ✅ **Nomes descritivos** em português para métodos
- ✅ **Accessors/Mutators** quando necessário

### **3. Form Requests**

#### **Estrutura Padrão**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ou lógica de autorização
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:models,email',
            'status' => 'required|in:ativo,inativo',
            'date_field' => 'nullable|date|before:today',
            'numeric_field' => 'nullable|numeric|min:0|max:999999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'email.email' => 'O email deve ser válido',
            'status.in' => 'O status deve ser ativo ou inativo',
            'date_field.before' => 'A data deve ser anterior a hoje',
        ];
    }

    public function prepareForValidation(): void
    {
        // Sanitização de dados
        $this->merge([
            'name' => trim($this->name),
            'email' => strtolower($this->email),
        ]);
    }
}
```

#### **Regras para Form Requests**
- ✅ **Mensagens em português**
- ✅ **Validações apropriadas** por tipo de campo
- ✅ **Sanitização** quando necessária
- ✅ **Regras de negócio** validadas

---

## ⚛️ PADRÕES FRONTEND (REACT + TYPESCRIPT)

### **1. Componentes**

#### **Estrutura Padrão**
```tsx
import React, { useState, useEffect } from 'react';
import { ModelType } from '@/types';

interface ComponentNameProps {
  data: ModelType[];
  onDataChange: () => void;
  loading?: boolean;
  error?: string | null;
}

const ComponentName: React.FC<ComponentNameProps> = ({
  data,
  onDataChange,
  loading = false,
  error = null
}) => {
  const [localState, setLocalState] = useState<LocalStateType | null>(null);

  // Efeitos mínimos - evitar useEffect loops
  useEffect(() => {
    if (data.length > 0 && !localState) {
      // Lógica inicial apenas se necessário
    }
  }, [data.length, localState]);

  const handleAction = async () => {
    try {
      // Lógica da ação
      await apiCall();
      onDataChange(); // Forçar recarregamento
    } catch (err) {
      console.error('Erro:', err);
    }
  };

  if (loading) {
    return <LoadingSpinner />;
  }

  if (error) {
    return <ErrorMessage message={error} />;
  }

  return (
    <div className="component-container">
      {/* JSX limpo e semântico */}
    </div>
  );
};

export default ComponentName;
```

#### **Regras para Componentes**
- ✅ **TypeScript** obrigatório
- ✅ **Props interfaces** bem definidas
- ✅ **useEffect mínimo** (evitar loops)
- ✅ **Error handling** adequado
- ✅ **Loading states** implementados
- ✅ **Nomes em PascalCase**

### **2. Navegação com Inertia.js**

A navegação no projeto é gerenciada pelo Inertia.js, que integra o backend Laravel com o frontend React de forma transparente.

#### **Estrutura Padrão para Links**
Use o componente `<Link>` do Inertia e o helper `route()` do Ziggy para gerar URLs a partir de rotas nomeadas do Laravel.

```tsx
import { Link } from '@inertiajs/react';

// ...

<Link href={route('assets.index')}>
  Ver Ativos
</Link>

<Link href={route('assets.create')} className="button-primary">
  Novo Ativo
</Link>
```

#### **Destacando o Link Ativo**
Para melhorar a experiência do usuário, é crucial destacar o link da página atual. Use o método `route().current()` para verificar a rota ativa e aplicar classes CSS condicionalmente.

```tsx
import { Link, usePage } from '@inertiajs/react';

const MainMenu = () => {
  const navigation = [
    { name: 'Dashboard', routeName: 'dashboard' },
    { name: 'Ativos', routeName: 'assets.index' },
  ];

  return (
    <nav>
      {navigation.map((item) => (
        <Link
          key={item.name}
          href={route(item.routeName)}
          className={
            route().current(item.routeName.replace('.index', '.*'))
              ? 'bg-indigo-50 text-indigo-600' // Estilo ativo
              : 'text-gray-600 hover:bg-gray-50' // Estilo inativo
          }
        >
          {item.name}
        </Link>
      ))}
    </nav>
  );
}
```
**Nota:** O uso de `replace('.index', '.*')` é um padrão útil para fazer com que rotas de um mesmo recurso (ex: `assets.index`, `assets.create`, `assets.show`) ativem o mesmo item de menu.

#### **Regras para Navegação**
- ✅ **Sempre use `<Link>`** para navegação interna para aproveitar os benefícios do Inertia (evita recarregamento completo da página).
- ✅ **Use rotas nomeadas** (`route('...')`) em vez de URLs fixas para facilitar a manutenção.
- ✅ **Implemente um estado ativo** claro para todos os elementos de navegação (menus, abas, etc.).

### **3. Hooks Customizados**

#### **Estrutura Padrão**
```tsx
import { useState, useCallback } from 'react';
import { apiService } from '@/services/api';

interface UseCustomHookReturn {
  data: DataType[];
  loading: boolean;
  error: string | null;
  refetch: () => Promise<void>;
  create: (item: DataType) => Promise<void>;
  update: (id: number, item: Partial<DataType>) => Promise<void>;
  delete: (id: number) => Promise<void>;
}

export const useCustomHook = (): UseCustomHookReturn => {
  const [data, setData] = useState<DataType[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const fetchData = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const result = await apiService.getAll();
      setData(result);
    } catch (err: any) {
      setError(err.message || 'Erro ao carregar dados');
    } finally {
      setLoading(false);
    }
  }, []);

  const create = useCallback(async (item: DataType) => {
    try {
      await apiService.create(item);
      await fetchData(); // Recarregar dados
    } catch (err: any) {
      throw new Error(err.message || 'Erro ao criar item');
    }
  }, [fetchData]);

  // ... outros métodos

  return {
    data,
    loading,
    error,
    refetch: fetchData,
    create,
    update,
    delete: remove
  };
};
```

#### **Regras para Hooks**
- ✅ **useCallback** para funções
- ✅ **Tipagem completa** do return
- ✅ **Error handling** consistente
- ✅ **Auto-refresh** após mutations

### **4. Serviços API**

#### **Estrutura Padrão**
```tsx
// services/api.ts
import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:5000/api',
});

// Interceptors para autenticação
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Serviço específico
export const modelApi = {
  async getAll(params?: any) {
    const response = await api.get('/models', { params });
    return response.data;
  },

  async getById(id: number) {
    const response = await api.get(`/models/${id}`);
    return response.data;
  },

  async create(data: any) {
    const response = await api.post('/models', data);
    return response.data;
  },

  async update(id: number, data: any) {
    const response = await api.put(`/models/${id}`, data);
    return response.data;
  },

  async delete(id: number) {
    await api.delete(`/models/${id}`);
  }
};
```

#### **Regras para API Services**
- ✅ **Interceptors** para auth
- ✅ **Error handling** centralizado
- ✅ **Tipagem** dos parâmetros
- ✅ **Nomes descritivos** em português

---

## 🎨 PADRÕES DE UI/UX

### **1. Componentes Base**

#### **Botões**
```tsx
interface ButtonProps {
  variant?: 'primary' | 'secondary' | 'danger';
  size?: 'sm' | 'md' | 'lg';
  loading?: boolean;
  disabled?: boolean;
  onClick: () => void;
  children: React.ReactNode;
}

const Button: React.FC<ButtonProps> = ({
  variant = 'primary',
  size = 'md',
  loading = false,
  disabled = false,
  onClick,
  children
}) => {
  const baseClasses = "rounded-lg font-medium transition-colors focus:outline-none focus:ring-2";
  const variantClasses = {
    primary: "bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500",
    secondary: "bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-500",
    danger: "bg-red-600 hover:bg-red-700 text-white focus:ring-red-500"
  };
  const sizeClasses = {
    sm: "px-3 py-1.5 text-sm",
    md: "px-4 py-2 text-base",
    lg: "px-6 py-3 text-lg"
  };

  return (
    <button
      className={`${baseClasses} ${variantClasses[variant]} ${sizeClasses[size]}`}
      onClick={onClick}
      disabled={disabled || loading}
    >
      {loading ? <Spinner /> : children}
    </button>
  );
};
```

#### **Formulários**
```tsx
interface FormFieldProps {
  label: string;
  name: string;
  type?: string;
  value: any;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  error?: string;
  required?: boolean;
  placeholder?: string;
}

const FormField: React.FC<FormFieldProps> = ({
  label,
  name,
  type = 'text',
  value,
  onChange,
  error,
  required = false,
  placeholder
}) => (
  <div className="mb-4">
    <label htmlFor={name} className="block text-sm font-medium text-gray-700 mb-1">
      {label} {required && <span className="text-red-500">*</span>}
    </label>
    <input
      type={type}
      id={name}
      name={name}
      value={value}
      onChange={onChange}
      placeholder={placeholder}
      className={`w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 ${
        error
          ? 'border-red-300 focus:ring-red-500'
          : 'border-gray-300 focus:ring-blue-500'
      }`}
      required={required}
    />
    {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
  </div>
);
```

### **2. Layout e Responsividade**

#### **Container Padrão**
```tsx
const PageLayout: React.FC<{title: string, children: React.ReactNode}> = ({ title, children }) => (
  <div className="min-h-screen bg-gray-50">
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">{title}</h1>
      </div>
      <div className="bg-white shadow rounded-lg">
        <div className="p-6">
          {children}
        </div>
      </div>
    </div>
  </div>
);
```

#### **Grid Responsivo**
```tsx
const ResponsiveGrid: React.FC<{children: React.ReactNode}> = ({ children }) => (
  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    {children}
  </div>
);
```

---

## 🧪 PADRÕES DE TESTE

### **1. Testes Backend (PHPUnit)**

#### **Estrutura Padrão**
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ModelName;
use App\Models\User;

class ModelNameControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_can_list_models()
    {
        // Arrange
        ModelName::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/models');

        // Assert
        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_can_create_model_with_valid_data()
    {
        // Arrange
        $data = [
            'name' => 'Test Model',
            'status' => 'ativo'
        ];

        // Act
        $response = $this->postJson('/api/models', $data);

        // Assert
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => ['id', 'name', 'status']
                ]);

        $this->assertDatabaseHas('models', $data);
    }

    public function test_cannot_create_model_with_invalid_data()
    {
        // Arrange
        $data = ['name' => '']; // Invalid

        // Act
        $response = $this->postJson('/api/models', $data);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }
}
```

### **2. Testes Frontend (Jest + RTL)**

#### **Estrutura Padrão**
```tsx
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import ComponentName from './ComponentName';

const mockProps = {
  data: [],
  onDataChange: jest.fn(),
  loading: false,
  error: null
};

describe('ComponentName', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders correctly', () => {
    render(<ComponentName {...mockProps} />);

    expect(screen.getByText('Expected Text')).toBeInTheDocument();
  });

  it('handles loading state', () => {
    render(<ComponentName {...mockProps} loading={true} />);

    expect(screen.getByText('Carregando...')).toBeInTheDocument();
  });

  it('handles error state', () => {
    const errorMessage = 'Erro de teste';
    render(<ComponentName {...mockProps} error={errorMessage} />);

    expect(screen.getByText(errorMessage)).toBeInTheDocument();
  });

  it('calls onDataChange when action is performed', async () => {
    const user = userEvent.setup();
    render(<ComponentName {...mockProps} />);

    await user.click(screen.getByRole('button', { name: 'Ação' }));

    await waitFor(() => {
      expect(mockProps.onDataChange).toHaveBeenCalledTimes(1);
    });
  });
});
```

---

## 📝 PADRÕES DE NOMENCLATURA

### **Backend (PHP/Laravel)**
- **Classes**: `PascalCase` (UserController, AssetModel)
- **Métodos**: `camelCase` (getUsers, createAsset)
- **Variáveis**: `camelCase` ($userData, $assetList)
- **Constants**: `UPPER_SNAKE_CASE` (MAX_UPLOAD_SIZE)
- **Database**: `snake_case` (user_id, created_at)

### **Frontend (TypeScript/React)**
- **Componentes**: `PascalCase` (UserManagement, AssetForm)
- **Funções**: `camelCase` (handleSubmit, fetchData)
- **Variáveis**: `camelCase` (userData, assetList)
- **Constants**: `UPPER_SNAKE_CASE` (API_URL, MAX_FILE_SIZE)
- **Types/Interfaces**: `PascalCase` (User, AssetProps)
- **Enums**: `PascalCase` (AssetStatus, UserRole)

### **Arquivos**
- **Controllers**: `ModelController.php`
- **Models**: `Model.php`
- **Requests**: `StoreModelRequest.php`
- **Components**: `ComponentName.tsx`
- **Services**: `serviceName.ts`
- **Types**: `types.ts`

---

## 🔒 PADRÕES DE SEGURANÇA

### **1. Validação de Entrada**
- ✅ **Form Requests** obrigatórios
- ✅ **Sanitização** de dados
- ✅ **Validação de tipos** rigorosa
- ✅ **Limitação de tamanho** de campos

### **2. Autenticação e Autorização**
- ✅ **Laravel Sanctum** para API
- ✅ **Middleware de auth** em rotas protegidas
- ✅ **Abilities** baseadas em roles
- ✅ **Token refresh** automático

### **3. Proteção contra Ataques**
- ✅ **Mass Assignment** prevenido
- ✅ **SQL Injection** evitado com Eloquent
- ✅ **XSS** prevenido com escaping
- ✅ **CSRF** protegido

---

## 📊 PADRÕES DE PERFORMANCE

### **1. Queries Otimizadas**
```php
// ❌ Ruim - N+1 queries
$assets = Asset::all();
foreach ($assets as $asset) {
    echo $asset->sector->name; // Query extra por asset
}

// ✅ Bom - Eager loading
$assets = Asset::with('sector')->get();
foreach ($assets as $asset) {
    echo $asset->sector->name; // Sem queries extras
}
```

### **2. Cache Estratégico**
```php
// Cache de dados estáticos
$sectors = Cache::remember('sectors', 3600, function () {
    return Sector::all();
});
```

### **3. Paginação**
```php
// Sempre paginar listas grandes
$assets = Asset::paginate(15);
return $assets;
```

---

## 🎯 CHECKLIST DE QUALIDADE

### **Antes de Commit**
- [ ] **Testes passando** (100%)
- [ ] **Linting** sem erros
- [ ] **TypeScript** sem erros
- [ ] **Form Requests** implementados
- [ ] **Tratamento de erros** adequado
- [ ] **Documentação** atualizada

### **Code Review**
- [ ] **Padrões seguidos** conforme este guia
- [ ] **Segurança** verificada
- [ ] **Performance** otimizada
- [ ] **Manutenibilidade** garantida
- [ ] **Testes** cobrindo cenários

---

*Este guia deve ser seguido por toda a equipe de desenvolvimento. Para sugestões de melhoria, abra uma issue no repositório.*
