# Levantamento Global de Funcionalidades - SGAITI

Este documento detalha todas as funcionalidades de cada botao e elemento interativo do sistema atual (Livewire 4).

---

## 1. DASHBOARD (`/dashboard`)

### Elementos Interativos

| Elemento | Tipo | Comportamento |
|----------|------|---------------|
| Card "Total de Ativos" | Display | Exibe contagem total de ativos |
| Card "Em Uso" | Display | Exibe ativos com status EM_USO |
| Card "Cautelas Abertas" | Display | Exibe cautelas sem checkin_date |
| Card "Militares" | Display | Exibe total de usuarios militares |
| Tabela "Cautelas Recentes" | Display | Lista ultimas cautelas |
| Link "Ver todas as cautelas" | Navegacao | Redireciona para `/custody` |

### Dados Exibidos na Tabela
- Numero da cautela
- Nome do militar (posto + nome)
- Data de checkout
- Status (badge colorido: Aberta/Baixada)

---

## 2. USUARIOS (`/users`)

### 2.1 Listagem (`/users`)

| Elemento | Tipo | Acao/Comportamento |
|----------|------|-------------------|
| Input "Buscar usuarios..." | Busca | `wire:model.live.debounce.300ms="search"` - Busca em tempo real |
| Botao "Criar Usuario" | Link | Navega para `/users/create` |
| Link "Editar" | Link | `wire:navigate` para `/users/{id}/edit` |
| Botao "Excluir" | Acao | `wire:click="delete(id)"` + `wire:confirm="Tem certeza?"` |
| Paginacao | Navegacao | Links de paginacao Laravel |

### Colunas da Tabela
- Posto/Grad
- Nome
- Identidade Militar
- E-mail
- Acoes (Editar | Excluir)

### 2.2 Criar Usuario (`/users/create`)

| Campo | Tipo | Validacao |
|-------|------|-----------|
| Posto/Graduacao | text | required |
| Nome Completo | text | required |
| Identidade Militar | text | required |
| E-mail | email | required |
| Setor | select | Dropdown com setores ativos |
| Senha | password | required |
| Confirmar Senha | password | required, confirmed |

| Botao | Acao |
|-------|------|
| "Salvar" | `wire:submit="save"` - Cria usuario e redireciona |
| "Cancelar" | Link para `/users` |

### 2.3 Editar Usuario (`/users/{id}/edit`)

| Campo | Tipo | Validacao |
|-------|------|-----------|
| Posto/Graduacao | text | required |
| Nome Completo | text | required |
| Identidade Militar | text | required |
| E-mail | email | required |
| Setor | select | Dropdown com setores ativos |
| Nova Senha | password | opcional (deixar em branco mantem atual) |
| Confirmar Senha | password | confirmed |

| Botao | Acao |
|-------|------|
| "Salvar" | `wire:submit="save"` - Atualiza usuario |
| "Cancelar" | Link para `/users` |

---

## 3. ATIVOS (`/assets`)

### 3.1 Listagem (`/assets`)

| Elemento | Tipo | Acao/Comportamento |
|----------|------|-------------------|
| Input "Buscar..." | Busca | `wire:model.live.debounce.300ms="search"` - Busca por nome, serial, patrimonio |
| Select "Todos Setores" | Filtro | `wire:model.live="sector_id"` - Filtra por setor |
| Select "Todos Tipos" | Filtro | `wire:model.live="type"` - Filtra por tipo |
| Select "Todos Status" | Filtro | `wire:model.live="status"` - Filtra por status |
| Botao "Novo Ativo" | Link | Navega para `/assets/create` |
| Cabecalho "Nome/Modelo" | Ordenacao | `wire:click="sortBy('name')"` - Ordena ASC/DESC |
| Cabecalho "Ident." | Ordenacao | `wire:click="sortBy('patrimony_number')"` |
| Cabecalho "Tipo" | Ordenacao | `wire:click="sortBy('type')"` |
| Cabecalho "Setor" | Ordenacao | `wire:click="sortBy('sector_id')"` |
| Cabecalho "Status" | Ordenacao | `wire:click="sortBy('status')"` |
| Link "Editar" | Link | `wire:navigate` para `/assets/{id}/edit` |
| Botao "Excluir" | Acao | `wire:click="delete(id)"` + confirmacao |
| Paginacao | Navegacao | 10 itens por pagina |

### Colunas da Tabela
- Nome/Modelo (nome + marca/modelo)
- Identificacao (SN + Patrimonio)
- Tipo (badge azul)
- Setor
- Status (badge colorido: DISPONIVEL=verde, EM_USO=azul, MANUTENCAO=amarelo, BAIXADO=vermelho)
- Acoes

### 3.2 Criar Ativo (`/assets/create`)

**Secao: Informacoes Basicas**
| Campo | Tipo | Observacao |
|-------|------|------------|
| Nome do Ativo/Descricao | text | required, autofocus |
| Codigo QR | text + botao | readonly, botao "Gerar" (`wire:click="generateQrCode"`) |
| Marca | text | |
| Modelo | text | |

**Secao: Identificacao**
| Campo | Tipo |
|-------|------|
| Numero de Serie | text |
| Numero de Patrimonio | text |

**Secao: Classificacao & Status**
| Campo | Tipo | Opcoes |
|-------|------|--------|
| Tipo | select | Lista de tipos do sistema |
| Status | select | DISPONIVEL, EM_USO, MANUTENCAO, BAIXADO |
| Condicao | select | Lista de condicoes |

**Secao: Localizacao**
| Campo | Tipo |
|-------|------|
| Setor | select |
| Localizacao Especifica | text | placeholder "Sala 101, Mesa 3" |

**Secao: Financeiro & Notas**
| Campo | Tipo |
|-------|------|
| Data de Aquisicao | date |
| Expiracao da Garantia | date |
| Valor de Compra | number (step=0.01) |
| Observacoes | textarea |

| Botao | Acao |
|-------|------|
| "Salvar" | `wire:submit="save"` |
| "Cancelar" | Link para `/assets` |

### 3.3 Editar Ativo (`/assets/{id}/edit`)

Mesmos campos de criacao, porem:
- Campo QR Code sem botao "Gerar" (readonly)
- Dados pre-preenchidos do ativo

---

## 4. SETORES (`/sectors`)

### 4.1 Listagem (`/sectors`)

| Elemento | Tipo | Acao/Comportamento |
|----------|------|-------------------|
| Input "Buscar setores..." | Busca | `wire:model.live.debounce.300ms="search"` |
| Botao "Criar Setor" | Link | Navega para `/sectors/create` |
| Link "Editar" | Link | `wire:navigate` para `/sectors/{id}/edit` |
| Botao "Excluir" | Acao | `wire:click="delete(id)"` + confirmacao |
| Paginacao | Navegacao | 10 itens por pagina |

### Colunas da Tabela
- Nome
- Descricao (truncada)
- Status (badge: Ativo=verde, Inativo=vermelho)
- Acoes

### 4.2 Criar Setor (`/sectors/create`)

| Campo | Tipo | Validacao |
|-------|------|-----------|
| Nome | text | required |
| Descricao | textarea | |
| Ativo | checkbox | is_active |

### 4.3 Editar Setor (`/sectors/{id}/edit`)

Mesmos campos de criacao com dados pre-preenchidos.

---

## 5. CAUTELAS (`/custody`)

### 5.1 Listagem (`/custody`)

| Elemento | Tipo | Acao/Comportamento |
|----------|------|-------------------|
| Input "Buscar..." | Busca | `wire:model.live.debounce.300ms="search"` - Busca por numero, usuario, ID militar |
| Select "Todos Status" | Filtro | `wire:model.live="status"` - open/closed |
| Botao "Nova Cautela" | Link | Navega para `/custody/create` |
| Link "Detalhes" | Link | `wire:navigate` para `/custody/{id}/edit` |
| Botao "Excluir" | Acao | `wire:click="delete(id)"` + confirmacao especial |
| Paginacao | Navegacao | 10 itens por pagina |

### Views Responsivas
- **Desktop**: Tabela tradicional
- **Mobile**: Cards com layout adaptado

### Colunas da Tabela (Desktop)
- Numero da Cautela
- Usuario (nome + posto)
- Itens (quantidade + lista truncada)
- Periodo (checkout + checkin se houver)
- Status (badge: Aberta=verde, Baixada=cinza)
- Acoes

### Confirmacao de Exclusao
Mensagem especial: "Tem certeza? Isso fara com que os ativos voltem para o status DISPONIVEL."

### 5.2 Nova Cautela (`/custody/create`)

**Campos do Formulario**
| Campo | Tipo | Observacao |
|-------|------|------------|
| Numero da Cautela | text | required, autofocus |
| Usuario Militar | select | Lista com posto + nome + ID militar |
| Data de Saida | date | required |
| Observacoes | textarea | |

**Secao: Selecao de Ativos**
| Elemento | Tipo | Acao |
|----------|------|------|
| Input "Buscar ativo..." | Busca | `wire:model.live.debounce.300ms="searchAsset"` |
| Contador de itens | Display | "X item(s) selecionado(s)" |
| Tabela de ativos | Selecao | Checkbox + `wire:click="toggleAsset(id)"` na linha |
| Botao "Limpar Selecao" | Acao | `wire:click="$set('selectedAssets', [])"` |

**Colunas da Tabela de Ativos**
- Checkbox de selecao
- Ativo (nome + QR code)
- Patrimonio
- Localizacao

| Botao | Acao |
|-------|------|
| "Criar Cautela" | `wire:submit="save"` |
| "Cancelar" | Link para `/custody` |

### 5.3 Detalhes da Cautela (`/custody/{id}/edit`)

**Cabecalho**
- Numero da cautela + Badge de status (ABERTA/BAIXADA)
- Botao "Baixar Cautela (Check-in)" (apenas se aberta)
  - `wire:click="closeCustody"` + confirmacao

**Informacoes Exibidas**
- Usuario Militar (somente leitura)
- Data de Saida (somente leitura)

**Secao: Adicionar Ativo** (apenas se aberta)
| Elemento | Tipo | Acao |
|----------|------|------|
| Input "Buscar para adicionar..." | Busca | `wire:model.live.debounce.300ms="searchAsset"` |
| Dropdown de resultados | Lista | Aparece com strlen > 2 |
| Botao "Adicionar" em cada item | Acao | `wire:click="addAsset(id)"` |

**Tabela de Ativos na Cautela**
| Coluna | Conteudo |
|--------|----------|
| Nome | nome do ativo |
| QR Code | codigo QR |
| Patrimonio | numero de patrimonio |
| Acao | Botao "Remover" (`wire:click="removeAsset(id)"`) - apenas se aberta |

**Secao: Observacoes** (se houver)
- Exibe observacoes em area destacada

| Botao | Acao |
|-------|------|
| "Voltar" | Link para `/custody` |

---

## 6. INVENTARIO (`/inventory`)

### 6.1 Listagem (`/inventory`)

| Elemento | Tipo | Acao/Comportamento |
|----------|------|-------------------|
| Botao "Novo Inventario" | Link | (href="#" - nao implementado) |
| Input "Buscar..." | Busca | `wire:model.live` - por numero da comissao |
| Select "Todos os Setores" | Filtro | `wire:model.live="sector_id"` |
| Select "Todos os Status" | Filtro | `wire:model.live="status"` |
| Icone Olho (Ver Detalhes) | Link | Navega para `/inventory/{id}` |
| Icone Reabrir | Acao | `wire:click="reopen(id)"` - apenas se Concluido |
| Icone Excluir | Acao | `wire:click="delete(id)"` + confirmacao |
| Paginacao | Navegacao | Links de paginacao |

### Colunas da Tabela
- Comissao (numero)
- Setor
- Responsavel
- Inicio (data formatada)
- Status (badge: Concluido=verde, Reaberto=amarelo, Em Andamento=azul)
- Acoes (icones)

### 6.2 Detalhes do Inventario (`/inventory/{id}`)

*View nao analisada neste levantamento - requer verificacao adicional*

---

## 7. CATEGORIAS (`/categories`) - EM DESENVOLVIMENTO

### 7.1 Listagem (`/categories`)
- View vazia (placeholder)

### 7.2 Criar Categoria (`/categories/create`)
- View vazia (placeholder)
- Componente com campos basicos: name, description

---

## RESUMO DE PADROES DE INTERACAO

### Busca em Tempo Real
```html
wire:model.live.debounce.300ms="search"
```

### Filtros
```html
wire:model.live="campo"
```

### Exclusao com Confirmacao
```html
wire:click="delete(id)" wire:confirm="Mensagem"
```

### Navegacao SPA
```html
wire:navigate
```

### Ordenacao de Colunas
```html
wire:click="sortBy('campo')"
```

### Formularios
```html
wire:submit="save"
```

### Selecao de Itens
```html
wire:click="toggleAsset(id)"
wire:model.live="selectedAssets"
```

---

## FUNCIONALIDADES NAO IMPLEMENTADAS (Identificadas)

1. **Fotos de Ativos** - API existe, UI nao implementada
2. **Registros de Manutencao** - API existe, UI nao implementada
3. **Novo Inventario** - Botao existe mas href="#"
4. **Categorias** - Modulo criado mas views vazias
5. **Notificacoes** - Backend existe, UI nao implementada
6. **Relatorios** - Item comentado no menu de navegacao

---

*Documento gerado em: 01/02/2026*
*Versao do Sistema: Livewire 4.1.0 / Laravel 12*
