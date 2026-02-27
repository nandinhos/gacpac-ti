# 🐳 Permissões de Arquivos Docker

## Problema
Arquivos criados por comandos `artisan make:*` dentro do container Docker ficam com owner `root:root`, causando erro de permissão no VS Code:
```
EACCES: permission denied, open '/path/to/file.php'
```

## Causa
- Docker roda como root por padrão
- Arquivos criados dentro do container herdam owner root
- Usuário local não tem permissão de escrita

## Solução

### Correção Imediata
Após executar qualquer `make:*` no Docker:
```bash
sudo chown -R $USER:$USER 
```

### Para diretórios específicos:
```bash
sudo chown -R $USER:$USER app/Livewire/
sudo chown -R $USER:$USER resources/views/livewire/
```

### Storage e Links Simbólicos
Configurar permissões do storage:
```bash
docker compose exec laravel.test mkdir -p storage/app/public/signed-documents
docker compose exec laravel.test chmod -R 775 storage/
docker compose exec laravel.test php artisan storage:link --force
```

## Prevenção
Adicionar ao `.bashrc` ou criar alias:
```bash
alias sail-chown='sudo chown -R $USER:$USER .'
```

Ou executar após cada `make:*`:
```bash
docker compose exec laravel.test php artisan make:livewire Foo && sudo chown -R $USER:$USER app/Livewire/
```

## Referências
- Laravel Sail Documentation
- Docker Volume Permissions

---
**Data**: 2026-02-04  
**Contexto**: Migração módulo Cautela - arquivos Show.php e PrintCautela.php
