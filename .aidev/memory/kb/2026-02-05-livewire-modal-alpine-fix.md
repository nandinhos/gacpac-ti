# Lição: Modais Livewire com Alpine.js - Abertura Instantânea

**Data**: 2026-02-05  
**Categoria**: bug  
**Stack**: Laravel 12, PHP 8.4, Livewire 3, Alpine.js  
**Severity**: Médio  
**Origem**: Commit faa44406622db8bcac016a461e63daf3aadc0c00 - fix(custody): corrige modal de upload de documento assinado

---

## Contexto

**Ambiente**: Desenvolvimento  
**Frequência**: Sempre  
**Impacto**: Médio

### Sintoma Observado
Modais em componentes Livewire não abriam instantaneamente ou apresentavam comportamento inconsistente. Ao clicar em botões para abrir modais (upload de documento, baixa de cautela, remoção), havia delay ou falha na abertura.

### Comportamento Esperado
Modais devem abrir imediatamente ao clicar no botão, com transições suaves e estado consistente.

### Evidência
```blade
{{-- ❌ ANTES - Modal com @if (problemático) --}}
@if($showUploadModal)
    <div class="modal">
        {{-- Conteúdo --}}
    </div>
@endif

{{-- Botão --}}
<button wire:click="openUploadModal">Abrir Modal</button>
```

---

## Causa Raiz

### Análise (5 Whys)
1. **Por que falhou?** Modal não abre instantaneamente ou comportamento inconsistente
2. **Por que?** Uso de `@if` diretivo Blade para controlar visibilidade do modal
3. **Por que?** `@if` recria o DOM quando a condição muda, causando delay
4. **Por que?** Livewire precisa fazer round-trip ao servidor para atualizar o estado
5. **Por que?** Falta de uso de Alpine.js para controle client-side do modal

### Tipo de Problema
- [x] Bug de código / [ ] Configuração incorreta / [ ] Dependência desatualizada
- [ ] Race condition / [ ] Limite de recurso / [ ] Falta de validação

---

## Solução

### Correção Aplicada

**1. Componente Livewire - Propriedades de Estado:**
```php
class Show extends Component
{
    use WithFileUploads;

    public CustodyLog $custodyLog;

    // Propriedades para controle de modais
    public $signedDocument;
    public $uploadJustification = '';
    public $showUploadModal = false;      // ✅ Controle do modal upload
    public $showRemoveModal = false;      // ✅ Controle do modal remoção
    public $showCheckinModal = false;     // ✅ Controle do modal baixa
    public $removeJustification = '';
    public $checkinJustification = '';

    public function mount(CustodyLog $custodyLog)
    {
        $this->custodyLog = $custodyLog->load(['user', 'assets']);
    }

    // Métodos para abrir/fechar modais
    public function openUploadModal()
    {
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->signedDocument = null;
        $this->uploadJustification = '';
    }
}
```

**2. View Blade - Estrutura Root Única:**
```blade
{{-- ✅ DEPOIS - Root element único obrigatório --}}
<div>
    {{-- Todo o conteúdo do componente --}}
    
    {{-- Modais controlados por Alpine --}}
    {{-- ... --}}
</div>
```

**3. Modais com Alpine.js (x-show):**
```blade
{{-- ✅ DEPOIS - Modal com x-show (abertura instantânea) --}}
<div 
    x-show="$wire.showUploadModal" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Overlay --}}
        <div 
            x-show="$wire.showUploadModal"
            @click="$wire.closeUploadModal()"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        ></div>

        {{-- Modal Content --}}
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Upload de Documento Assinado') }}
                </h3>
                
                {{-- Formulário --}}
                <div class="mt-4">
                    <input type="file" wire:model="signedDocument" accept=".pdf,.jpg,.jpeg,.png" />
                    @error('signedDocument') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        {{ __('Justificativa') }}
                    </label>
                    <textarea wire:model="uploadJustification" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>

            {{-- Botões --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    wire:click="saveSignedDocument" 
                    wire:loading.attr="disabled"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
                >
                    <span wire:loading.remove wire:target="saveSignedDocument">{{ __('Salvar') }}</span>
                    <span wire:loading wire:target="saveSignedDocument">{{ __('Salvando...') }}</span>
                </button>
                
                <button 
                    @click="$wire.closeUploadModal()" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    {{ __('Cancelar') }}
                </button>
            </div>
        </div>
    </div>
</div>
```

**4. Botões para Abrir Modais:**
```blade
{{-- ✅ Botão usando wire:click --}}
<button 
    wire:click="openUploadModal" 
    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition"
>
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
    </svg>
    {{ __('Upload Documento') }}
</button>

{{-- Botão de Baixa --}}
@if(!$custodyLog->checkin_date)
    <button 
        wire:click="openCheckinModal" 
        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ __('Dar Baixa na Cautela') }}
    </button>
@endif
```

### Por Que Funciona
- `x-show` (Alpine.js) controla visibilidade client-side sem round-trip ao servidor
- `$wire` magic property acessa estado Livewire diretamente do Alpine
- `x-transition` adiciona animações suaves
- `wire:click` chama métodos PHP no componente Livewire
- Root element único (`<div>`) satisfaz requisito do Livewire 3

### Alternativas Consideradas
| Alternativa | Por que não escolhida |
|-------------|----------------------|
| `@if` com Livewire 2 | Livewire 3 exige root element único |
| JavaScript puro | Perde integração reativa do Livewire |
| Alpine.js sem $wire | Não sincroniza estado com backend |

### Validação
- [x] Teste adicionado/atualizado
- [x] Comando de verificação: `php artisan test --filter=CustodyShowTest`

---

## Prevenção

### Checklist para Evitar no Futuro
- [ ] Sempre usar `x-show` para modais em Livewire, nunca `@if`
- [ ] Verificar se componente tem root element único
- [ ] Usar `$wire` para acessar propriedades Livewire do Alpine
- [ ] Adicionar `x-cloak` para evitar flash de conteúdo não-renderizado
- [ ] Implementar transições (`x-transition`) para melhor UX

### Regras de Ouro
1. **Nunca use `@if`** para controlar visibilidade de modais em Livewire
2. **Sempre use `x-show`** com `$wire.property` para controle reativo
3. **Root element único** é obrigatório em todos os componentes Livewire
4. **Limpe estado** ao fechar modal (resetar propriedades)
5. **Use `wire:loading`** para feedback visual durante operações

---

## Referências

- **Commit**: faa44406622db8bcac016a461e63daf3aadc0c00
- **Arquivos**: 
  - `app/Livewire/Custody/Show.php`
  - `resources/views/livewire/custody/show.blade.php`
  - `tests/Feature/CustodyShowTest.php`
- **Documentação**: Livewire 3 Docs, Alpine.js Docs
- **Módulo**: Custody (Cautela)

---

*Documentado seguindo padrão AI Dev Superpowers v3.6*
