# 📚 LIÇÃO APRENDIDA - Permissões Laravel Storage

> **Data:** 2025-11-06  
> **Problema:** Permissões de escrita no storage/framework/views

## 🐛 **PROBLEMA IDENTIFICADO**

### Sintomas
```
file_put_contents(/path/storage/framework/views/xxxxx.php): Failed to open stream: Permission denied
```

### Causa Raiz
- Pastas `storage/` criadas pelo Docker com usuário `root`
- Laravel local rodando com usuário `gacpac` não tem permissão de escrita
- Tentativa de compilar views Blade falha

## ✅ **SOLUÇÕES APLICADAS**

### 1. **Cache de Views Desabilitado**
```env
VIEW_CACHE_DRIVER=null
```

### 2. **Uso do PHP Built-in Server**
```bash
# Ao invés de php artisan serve
php -S localhost:8000 -t public
```

### 3. **Cache de Configuração**
```bash
php artisan config:cache
php artisan route:cache
```

## 🎯 **SOLUÇÕES FUTURAS**

### Para Desenvolvimento Local
```bash
# Opção 1: Recriar storage com permissões corretas
rm -rf storage/framework/{views,cache,sessions}
mkdir -p storage/framework/{views,cache,sessions}
chmod -R 755 storage

# Opção 2: Usar Docker para desenvolvimento
docker-compose up -d sgaiti
```

### Para Produção
```dockerfile
# No Dockerfile
RUN chown -R www-data:www-data /app/storage
RUN chmod -R 755 /app/storage
```

## 🚨 **PREVENÇÃO**

1. **Sempre** verificar permissões após Docker builds
2. **Nunca** rodar Laravel como root em desenvolvimento
3. **Usar** volumes Docker adequados para storage
4. **Configurar** usuários consistentes entre Docker e host

---

**💡 Esta lição nos ajudará a evitar problemas de permissões em futuros deployments!**