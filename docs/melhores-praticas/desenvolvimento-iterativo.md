# 🛠️ Desenvolvimento Iterativo - SGTI-GAC

## 📋 WORKFLOW DE DESENVOLVIMENTO ESTABELECIDO

### **Stack Implementada**
- ✅ **Backend**: Laravel 11 + PHP 8.2 (Container Docker com volume montado)
- ✅ **Frontend**: React 18 + TypeScript + Vite (Renderizado pelo Laravel com Inertia.js)
- ✅ **Database**: MySQL 8.0 (Container Docker)
- ✅ **Comunicação**: Inertia.js para SPA híbrida

### **Estrutura de Arquivos Chave**

```
SGTI-GAC/
├── backend/           # 🐘 Aplicação Laravel
│   ├── app/          # Código PHP (Models, Controllers)
│   ├── resources/    # Contém o código Frontend
│   │   └── js/       # ⚛️ Código React (Components, Pages, Layouts)
│   ├── routes/       # Rotas (web.php para Inertia)
│   └── database/     # Migrations, seeders
├── docker-compose.yml # 🐳 Orquestração dos containers
├── dev-rebuild.sh    # 🔄 Script para forçar o rebuild da imagem do frontend
└── Dockerfile.frontend # 🐳 Definição da imagem do frontend (usado por dev-rebuild.sh)
```

### **Workflow de Desenvolvimento**

#### **1. Backend Laravel** 🚀
As alterações nos arquivos PHP do backend (em `backend/app`, `backend/routes`, etc.) são refletidas instantaneamente no container `backend` graças ao volume montado no `docker-compose.yml`.

```bash
# Edite qualquer arquivo .php no diretório backend/
# As mudanças são aplicadas automaticamente.

# Para executar comandos Artisan:
docker-compose exec backend php artisan <comando>
```

#### **2. Frontend React (com Inertia)** ⚛️
O código fonte do frontend reside em `backend/resources/js`. Como o Laravel compila e versiona esses arquivos, um passo de "build" é necessário para que as alterações apareçam no navegador.

```bash
# 1. Edite os arquivos em backend/resources/js/

# 2. Entre no container do backend para compilar os assets
docker-compose exec backend npm run build

# 3. Atualize o navegador. As alterações estarão visíveis.
```

**Nota:** O script `./dev-rebuild.sh` não é mais o fluxo principal, mas pode ser útil se houver problemas com dependências do Node.js que exijam uma reconstrução completa da imagem Docker.

#### **3. Database MySQL** 🗄️
O banco de dados opera em um container separado e persiste os dados em um volume Docker.

```bash
# Aplicar novas migrations:
docker-compose exec backend php artisan migrate

# Popular o banco com dados de teste:
docker-compose exec backend php artisan db:seed

# Acessar o banco via cliente (ex: DBeaver, DataGrip) na porta 3306.
# Acessar o phpMyAdmin em http://localhost:58090
```

### **Melhores Práticas Estabelecidas**

#### **✅ FAÇA SEMPRE**
- [x] **Backend First**: Defina as rotas e a lógica no Laravel (`web.php`) antes de criar as páginas React.
- [x] **Compile o Frontend**: Sempre rode `docker-compose exec backend npm run build` após fazer alterações no frontend.
- [x] **Use Rotas Nomeadas**: Use o helper `route()` do Inertia para gerar URLs, tanto no PHP quanto no JavaScript.
- [x] **Verifique os Logs**: `docker-compose logs -f backend` é seu melhor amigo para depurar problemas.

#### **❌ EVITE**
- [x] **Esquecer de Compilar**: Alterações no React não aparecerão sem o passo de `npm run build`.
- [x] **Editar `public/build`**: Nunca edite os arquivos compilados diretamente. Eles são gerados automaticamente.
- [x] **Commits sem Teste**: Garanta que a aplicação construa e funcione antes de commitar.

### **Troubleshooting Comum**

#### **Frontend não atualiza** 🔄
**Causa Provável:** Você esqueceu de compilar os assets.
```bash
# Solução:
docker-compose exec backend npm run build
```
**Causa 2:** O cache do seu navegador está servindo uma versão antiga.
**Solução:** Limpe o cache do navegador ou use a aba anônima.

#### **Erro 404 ou 500 após mudança de rota** ⚙️
**Causa Provável:** A rota não foi definida corretamente em `backend/routes/web.php` ou o controller/closure associado tem um erro.
**Solução:** Verifique `docker-compose logs -f backend` para ver a mensagem de erro detalhada do Laravel.

### **Próximos Passos**

#### **Melhorias Planejadas**
- [ ] **Hot Module Replacement (HMR)**: Configurar o Vite para HMR dentro do Docker para um desenvolvimento frontend mais rápido, eliminando a necessidade do `npm run build` a cada mudança.
- [ ] **Testes Automatizados**: Integrar `php artisan test` e testes de frontend em um pipeline de CI/CD.

---

**Este workflow garante desenvolvimento eficiente e consistente entre equipe, evitando problemas de sincronização e garantindo qualidade em produção.** 🎯
