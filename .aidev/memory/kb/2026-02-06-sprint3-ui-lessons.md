---
title: Lições Aprendidas - Sprint 3 (Manutenção de Ativos)
date: 2026-02-06
tags: [frontend, livewire, tailwind, ui-ux, legacy-data]
---

# Lições Aprendidas - Sprint 3

Durante o desenvolvimento do módulo de Manutenção de Ativos e polimento da interface, identificamos os seguintes pontos de atenção e padrões a serem seguidos.

## 1. Padronização de UI/UX (Badges de Status)
**Problema**: Inconsistência visual entre badges de status na listagem e nos detalhes.
**Solução**: Definir um padrão de cores rigoroso e centralizado (ou replicado com exatidão).
- **Em Uso**: Azul (`bg-blue-100 text-blue-800`)
- **Manutenção**: Laranja (`bg-orange-100 text-orange-800`)
- **Disponível**: Verde (`bg-green-100 text-green-800`)
- **Baixado**: Vermelho Escuro (`bg-red-800 text-red-100`)

**Nota**: Ao alterar cores que não eram usadas anteriormente (como `bg-red-800`), é **obrigatório** recompilar os assets (`npm run build`) para que o Tailwind gere as novas classes CSS. Apenas limpar o cache do view não é suficiente.

## 2. Compilação de Assets e Cache
**Problema**: Alterações em classes do Tailwind ou lógica do Blade não refletiam na tela.
**Solução**: O fluxo correto para garantir a aplicação de mudanças de estilo é:
1. `php artisan view:clear` (Limpar cache de views compiladas)
2. `php artisan config:clear` (Garantir que configurações não interfiram)
3. `npm run build` (Recompilar CSS/JS final)

Se houver problemas de permissão (`EACCES`), pode ser necessário rodar `rm -rf public/build` com permissões elevadas antes de recompilar.

## 3. Normalização de Dados Legados
**Problema**: O banco de dados continha variações de status como "Disponível", "DISPONIVEL", "Em Uso", "Manutenção", "MANUTENCAO". Isso quebrava o mapeamento de cores simples.
**Solução**: No frontend (Blade), normalizar a chave antes do lookup.
```php
$normalizedStatus = strtoupper(Str::slug($status, '_')); 
// Ex: "Em Uso" -> "EM_USO"
```
Isso garante robustez na interface mesmo sem sanear o banco imediatamente.

## 4. Modais no Livewire 3
**Padrão Adotado**: Evitar `wire:confirm` nativo do navegador para ações destrutivas importantes.
**Melhor Prática**: Usar **Alpine.js** para controlar a visibilidade de um modal customizado, que então dispara o método Livewire via `$wire`.
- Melhora a UX (não é um popup bloqueante do sistema).
- Permite estilização consistente com o resto da aplicação.
- Padrão consolidado em `resources/views/components/confirm-modal.blade.php`.
