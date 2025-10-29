# Guia de Boas Práticas - SGAITI-UM

Este documento reúne soluções e métodos para problemas comuns de desenvolvimento encontrados no projeto, servindo como um guia prático para manter a qualidade e a consistência do código.

---

## Diagnóstico de Dados Incorretos no Frontend

**Problema:** O frontend exibe dados que não correspondem à realidade do banco de dados (ex: nomes de campos errados, status incorreto, listas vazias).

**Causa Raiz:** Geralmente, isso ocorre por uma divergência na "forma" dos dados (o *schema*) entre o banco de dados, a resposta da API do backend e a estrutura que o componente do frontend espera.

### Passo a Passo para Diagnóstico e Correção

Este método foi usado para corrigir a exibição de Ativos e o status de Usuários.

#### 1. Rastreie o Fluxo de Dados (Do Banco para a Tela)

O objetivo é comparar a estrutura de dados em cada etapa do caminho.

- **Banco de Dados (A Fonte da Verdade):**
  - Consulte a documentação do schema em `docs/DATABASE_SCHEMA.md`.
  - Verifique os nomes exatos das colunas (`snake_case`, ex: `military_id`, `is_active`) e seus tipos.

- **API Backend (O Mensageiro):**
  - Abra o arquivo de rota correspondente em `backend/routes/` (ex: `users.js`).
  - Analise a query SQL. Veja se a API retorna os campos como estão ou se usa aliases (ex: `SELECT s.name as sector_name`).
  - Verifique se o backend transforma algum dado antes de enviar (ex: `is_active: Boolean(user.is_active)`).

- **Tipos do Frontend (O Contrato):**
  - Abra o arquivo `types.ts`.
  - Compare as interfaces (`MilitaryUser`, `Asset`, etc.) com a resposta da API que você analisou no passo anterior. **Todos os nomes de campos e seus tipos devem bater exatamente**.

- **Componente Frontend (O Consumidor):**
  - Abra o arquivo do componente React em `components/` (ex: `UserManagement.tsx`).
  - Verifique como a `prop` com os dados está sendo usada no JSX. Por exemplo, se a lista de ativos está sendo exibida com `asset.name` ou com um nome de campo antigo, como `asset.type`.

#### 2. Identifique e Corrija as Divergências

Com a análise completa, você encontrará as diferenças. As correções mais comuns são:

- **Nomes de Campos:** Corrigir o acesso a propriedades no componente (ex: mudar de `user.active` para `user.is_active`).
- **Tipos de Dados:** Garantir que o frontend não está tratando um `string` como `number` (especialmente para IDs).
- **Dados Ausentes:** Se um campo esperado não vem da API, ajuste a rota no backend para incluir esse campo na query SQL.

> **Lição Aprendida:** Um bug de mapeamento de dados em um local (ex: uma tabela) é um forte indicativo de que o mesmo erro existe em outras partes do mesmo componente (ex: modais, formulários, funções de exportação). Ao encontrar uma divergência, faça uma auditoria completa em **todo** o arquivo do componente, procurando por outras ocorrências do mesmo padrão de erro. No nosso caso, o erro `log.cautelaNumber` na função de gerar PDF foi um exemplo disso.

#### 3. Reconstrua e Valide

Após aplicar as correções no código, é crucial reconstruir a imagem Docker do serviço afetado para que as alterações tenham efeito.

```bash
# Reconstrói as imagens (se necessário) e reinicia todos os serviços
docker-compose up -d --build
```

---

### Sobre Testes Unitários

Atualmente, o projeto não possui uma estrutura de testes configurada. A implementação de testes unitários com uma biblioteca como **Vitest** e **React Testing Library** seria um excelente próximo passo para garantir que os componentes se comportem como esperado e para prevenir regressões (ou seja, para que uma correção não quebre outra coisa).

**Próxima Ação Sugerida:** Configurar o ambiente de testes e criar testes para os componentes que corrigimos.
