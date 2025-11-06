# 🎯 SGAITI-UM - Sistema Laravel + Inertia

> Sistema de Gestão de Ativos de TI da Unidade Militar

## 🚀 Quick Start

```bash
# 1. Instalar dependências
cd backend && composer install && npm install

# 2. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 3. Configurar banco de dados (editar .env)
php artisan migrate
php artisan db:seed

# 4. Executar aplicação
composer run dev  # ou php artisan serve + npm run dev

# 5. Acessar
# http://localhost:8000
```

## 🏗️ Arquitetura

- **Backend**: Laravel 12 + PHP 8.3
- **Frontend**: Inertia.js + React 18 + TypeScript
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Build**: Vite + Laravel Mix

## 📁 Estrutura

```
backend/
├── app/Http/Controllers/     # Controllers Inertia
├── app/Models/               # Eloquent Models  
├── resources/js/Pages/       # Views React/Inertia
├── resources/js/Components/  # Componentes React
├── routes/web.php           # Rotas web (Inertia)
├── routes/api.php           # Rotas API (Sanctum)
└── tests/                   # Testes PHPUnit
```

## 🧪 Testes

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos
php artisan test --filter AssetTest
```

## 🐳 Docker

```bash
# Subir ambiente completo
docker-compose up -d

# Logs
docker-compose logs -f sgaiti

# Parar
docker-compose down
```

**Portas:**
- Laravel: 8000
- MySQL: 53106  
- phpMyAdmin: 58090

## 📚 Desenvolvimento

Ver `AGENTS.md` para guias detalhados de desenvolvimento.