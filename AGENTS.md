# AGENTS.md - SGAITI-UM Development Guide (Laravel + Inertia)

## Build/Lint/Test Commands
- **Development**: `cd backend && composer run dev` (runs Laravel serve + Vite + Queue + Logs)
- **Build**: `cd backend && npm run build` (build Vite assets)
- **Tests**: `cd backend && php artisan test` (PHPUnit tests)
- **Database**: `cd backend && php artisan migrate` (run migrations), `php artisan db:seed` (seed data)
- **Docker**: `docker-compose up -d` (start all), `docker-compose down` (stop), `docker-compose logs -f` (logs)

## Architecture Overview
- **Frontend**: Inertia.js + React 18 + TypeScript with Laravel Vite, Tailwind CSS
- **Backend**: Laravel 12 + Sanctum Auth + MySQL 8.0, Inertia responses
- **Database**: MySQL with entities: sectors, military_users, assets, custody_logs, inventory_records
- **Docker**: Laravel + MySQL + phpMyAdmin orchestration
- **Path alias**: Laravel standard paths, Inertia page components in `resources/js/Pages/`

## Code Style Guidelines
- **Controllers**: Laravel controllers in `app/Http/Controllers/`, return Inertia responses
- **Models**: Eloquent models in `app/Models/`, relationships and business logic
- **Views**: Inertia pages in `resources/js/Pages/`, React components with TypeScript
- **Routes**: Web routes in `routes/web.php`, API routes in `routes/api.php`
- **Language**: Portuguese for UI/military terms, English for code/comments
- **Naming**: camelCase for variables, PascalCase for components, snake_case for DB fields
- **Error handling**: Laravel exceptions, validation rules, user-friendly Portuguese messages

## Melhores Práticas
- **Laravel Environment**: Configure `.env` com variáveis do banco MySQL no Docker. Use `php artisan key:generate` para gerar APP_KEY.
- **Inertia.js Setup**: Assets do frontend são compilados pelo Vite Laravel. Configure `vite.config.js` para React e Inertia. Use `npm run dev` durante desenvolvimento.
- **Database**: Execute `php artisan migrate` para criar tabelas. Use `php artisan db:seed` para popular dados de teste.
- **Authentication**: Sistema usa Laravel Sanctum para autenticação de usuários militares com roles (admin, commission, user).
- **File Uploads**: Use Laravel Storage para upload de imagens de assets. Configuração em `config/filesystems.php`.
- **Desenvolvimento Docker**: Use `docker-compose up -d` para ambiente completo. Laravel roda na porta 8000, MySQL na 53106, phpMyAdmin na 58090.
- **Testing**: Execute `php artisan test` para rodar testes PHPUnit. Factories disponíveis para todos os models principais.
