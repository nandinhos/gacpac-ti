# AGENTS.md - SGAITI-UM Development Guide

## Build/Lint/Test Commands
- **Frontend**: `npm run dev` (dev), `npm run build` (build), `npm run preview` (preview)
- **Backend**: `cd backend && npm run dev` (dev), `npm start` (prod), `npm run seed` (seed data)
- **Docker**: `docker-compose up -d` (start all), `docker-compose down` (stop), `docker-compose logs -f` (logs)
- **No tests implemented** - Jest planned for unit tests, Cypress for E2E

## Architecture Overview
- **Frontend**: React 19 + TypeScript SPA with Vite, Tailwind CSS, prop-drilling state management
- **Backend**: Node.js + Express API with MySQL 8.0, REST endpoints at `/api`
- **Database**: MySQL with entities: sectors, users, assets, custody_logs, inventory_records
- **Docker**: Full-stack orchestration with separate containers for frontend, backend, database
- **Path alias**: `@/` maps to project root for imports

## Code Style Guidelines
- **Imports**: Use `@/` alias (e.g., `import { Asset } from '@/types'`)
- **Types**: Centralized in `types.ts`, enums use Portuguese for military context
- **Language**: Portuguese for UI/military terms, English for code/comments
- **State**: All state managed in App.tsx, passed down as props
- **Components**: Views in `components/`, services in `services/`, backend routes in `backend/routes/`
- **Naming**: camelCase for variables, PascalCase for components/types, snake_case for DB fields
- **Error handling**: Try-catch blocks, console.error for logging, user-friendly Portuguese messages

## Melhores Práticas
- **CORS Configuration**: Sempre expanda as origens permitidas no `backend/server.js` para incluir endereços locais como `http://127.0.0.1:8100` durante o desenvolvimento, para evitar erros de CORS ao testar com diferentes portas.
- **Tailwind CSS Setup**: Use instalação local do Tailwind CSS, não CDN. Configure `postcss.config.js` com sintaxe ES module (`export default`). Adicione diretivas `@tailwind` em `src/index.css`. Atualize `tailwind.config.js` com caminhos corretos dos arquivos (e.g., `./src/**/*.{js,ts,jsx,tsx}`). Remova referências ao CDN no `index.html`.
- **Image Upload Handling**: No backend, garanta conversão correta de Buffer para Base64 para armazenamento e exibição de imagens. No frontend, chame `onDataChange()` após atualizações de detalhes/fotos para forçar o recarregamento dos dados e refletir mudanças imediatamente.
- **API URLs in Production**: No `docker-compose.yml`, use `http://localhost:5050/api` como URL da API no build do frontend para produção, garantindo que o container acesse o backend corretamente via porta exposta.
- **Seed Data**: Mantenha dados de teste no `backend/scripts/seed.js` para facilitar o desenvolvimento e testes, incluindo registros de cautelas (`custody_logs` e `custody_assets`).
- **Desenvolvimento Iterativo**: Para mudanças rápidas no código, copie os arquivos alterados diretamente para o container em execução usando `docker cp` (evite rebuilds desnecessários). Rebuilde assets localmente com `npm run build` e copie a pasta `dist` para `/usr/share/nginx/html` no frontend. Para backend, copie arquivos individuais (e.g., `docker cp ./backend/routes/custody.js sgaiti-backend:/app/routes/custody.js`) e aproveite o hot-reload do nodemon.
