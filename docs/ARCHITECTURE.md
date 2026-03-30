# 🏛️ Arquitetura do Sistema (gacpac-ti)

O sistema segue os padrões modernos do Laravel 12, priorizando a separação de responsabilidades e a manutenibilidade através de camadas bem definidas.

##  camadas do Sistema

### 1. Controllers (API & Livewire)
Responsáveis apenas pela orquestração de entrada, validação básica e resposta.
- **Localização:** `app/Http/Controllers/` e `app/Livewire/`
- **Regra:** Nunca conter lógica de negócio complexa. Sempre delegar para **Services**.
- **Autorização:** Utiliza `$this->authorize()` mapeado para **Policies**.

### 2. Services (Lógica de Negócio)
Onde reside a inteligência do sistema. Centralizam a lógica para que seja reutilizável por API, Livewire e Comandos Console.
- **Localização:** `app/Services/`
- **Scripts:** `AssetService`, `CustodyService`, `InventoryService`, `UserService`, `MaintenanceService`, etc.

### 3. Resources (Transformação de Dados)
Padronizam o formato de saída da API, garantindo que mudanças no banco não quebrem o contrato com o frontend.
- **Localização:** `app/Http/Resources/`
- **Padrão:** Sempre retornar datas em formato ISO8601 e tratar campos legados aqui.

### 4. Models & Policies
- **Spatie Permission:** Controle de acesso baseado em roles (`admin`, `auditor`, `operator`, `viewer`).
- **Traits de Auditoria:** Rastreamento sistemático de mudanças via `Auditable`.

## Padrões de Desenvolvimento

- **Ambiente:** Obrigatório o uso do **Laravel Sail** (`./vendor/bin/sail`).
- **Estilo:** Código formatado via **Laravel Pint**.
- **Testes:** TDD obrigatório (Feature Tests por módulo).
