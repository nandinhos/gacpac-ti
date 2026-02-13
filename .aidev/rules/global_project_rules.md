---
title: Regras Globais
type: rules
permalink: guides/regras-globais
---

# Regras Globais do Projeto

**Idioma**: Português (PT-BR).
- Todos os artefatos, planos, relatórios e mensagens devem ser escritos em português.
- Commits devem seguir modificações convencionais (Conventional Commits) no padrão Escopo (): Descrição em português, porém **SEM EMOJIS**.

**Convenções de Commit**:
- Formato: `tipo(escopo): descrição`
- Exemplo: `feat(assets): adiciona validação de qr_code`
- **NÃO USAR EMOJIS**.

**Verificação de Qualidade**:
- **Sempre** verificar se a página ou rota alterada está respondendo via terminal (ex: `curl -I http://localhost:8900/rota`) e não apenas no navegador.
- Se a resposta não for **200 OK**, deve-se obrigatoriamente verificar o erro na **Base de Conhecimento** e **Lições Aprendidas** antes de tentar correções ad-hoc, visando evitar retrabalho e inconsistências.
