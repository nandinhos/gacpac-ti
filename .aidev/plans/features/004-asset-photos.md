# Feature: Upload e Galeria de Fotos de Ativos

**Sprint:** 4
**Status:** Concluído
**Data início:** 2026-02-06
**Data conclusão:** 2026-02-13

## Contexto de Negócio
O SGAITI precisa permitir o registro fotográfico dos ativos para documentação visual, facilitando identificação, conferência em inventários e registro do estado de conservação dos equipamentos.

## Requisitos

### Funcionais
- [x] Upload de múltiplas fotos por ativo (jpg, jpeg, png, webp).
- [x] Galeria com grid responsivo e thumbnails.
- [x] Lightbox para visualização em tamanho real com navegação.
- [x] Definir foto principal do ativo.
- [x] Edição inline de legendas.
- [x] Exclusão de fotos com confirmação.
- [x] Auto-promoção da próxima foto ao deletar a principal.

### Regras de Negócio
- Tamanho máximo: 10MB por arquivo.
- Tipos aceitos: jpg, jpeg, png, webp.
- Primeira foto é automaticamente definida como principal.
- Storage organizado por ativo: `asset-photos/{asset-id}/`.

## Arquitetura

### Modelo de Dados (`asset_photos`)
- `id` (PK)
- `asset_id` (FK -> assets, cascade delete)
- `url` (varchar)
- `caption` (varchar, nullable)
- `uploaded_at` (timestamp)
- `mime_type` (varchar)
- `is_primary` (boolean, default false)
- `file_size` (unsigned integer, nullable)
- `timestamps`

### Componentes
- `Assets/Edit.php`: Métodos de foto integrados (upload, setPrimary, deletePhoto, updateCaption)
- `Photos/Index.php`: Componente standalone para galeria
- `photo-lightbox.blade.php`: Componente Blade para visualização fullscreen

## Implementação

### Arquivos:
```
app/Models/AssetPhoto.php
app/Livewire/Assets/Edit.php (métodos de foto)
app/Livewire/Photos/Index.php
resources/views/livewire/assets/edit.blade.php (aba Fotos)
resources/views/livewire/photos/index.blade.php
resources/views/components/photo-lightbox.blade.php
tests/Feature/AssetPhotoTest.php
database/factories/AssetPhotoFactory.php
routes/api.php (rotas POST e DELETE)
```

## Testes
- [x] Visualizar aba de fotos.
- [x] Upload de foto única.
- [x] Upload de múltiplas fotos.
- [x] Primeira foto é definida como principal.
- [x] Validação de tipo de arquivo.
- [x] Validação de tamanho de arquivo.
- [x] Definir foto principal.
- [x] Deletar foto e verificar remoção do arquivo.
- [x] Auto-promoção ao deletar foto principal.
- [x] Exibir galeria com contagem e legenda.
- [x] Troca de abas funciona corretamente.

**Arquivo:** `tests/Feature/AssetPhotoTest.php` (14 testes)

## Lições Aprendidas
- Exclusão deferida (deferred deletion) evita erros 403 durante o morphing do Livewire.
- Lightbox com Alpine.js e eventos customizados permite navegação fluida sem reload.
- Organizar storage por asset-id facilita limpeza e backup.
