# SPEC — FASE 2: Limpeza da Stack Frontend (React Residual)
**Status:** `[ ] Pendente`
**Pré-requisito:** Fase 1 concluída.
**Checkpoint:** `npm run build` sucesso sem referências a React no bundle de saída.

---

## Contexto

O sistema foi originalmente desenvolvido em React e está sendo migrado para Laravel + Livewire + Blade. A migração do frontend está **completa** — toda a UI hoje é Blade/Livewire. Porém, resquícios do React ainda estão presentes:

- `package.json` contém `@headlessui/react ^2.0.0` e `@vitejs/plugin-react ^4.2.0`
- `vite.config.js` **NÃO** importa o plugin React (já está limpo)
- Nenhum arquivo `.jsx` ou `.tsx` existe em uso produtivo

Essas dependências aumentam o `bundle` desnecessariamente e geram confusão para novos desenvolvedores.

---

## Arquivos Afetados

| Arquivo | Tipo | Ação |
|---|---|---|
| `package.json` | MODIFY | Remover 2 dependências React |
| `package-lock.json` | MODIFY | Atualizado automaticamente pelo `npm install` |
| `vite.config.js` | VERIFY | Confirmar que NÃO tem import do plugin React (não editar) |

---

## Estado Atual Verificado

**`vite.config.js` (NÃO ALTERAR — já está correto):**
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        hmr: { host: 'localhost' },
    },
});
```

**`package.json` atual (devDependencies — MODIFICAR):**
```json
"devDependencies": {
    "@headlessui/react": "^2.0.0",      ← REMOVER
    "@tailwindcss/forms": "^0.5.2",
    "@vitejs/plugin-react": "^4.2.0",   ← REMOVER
    "autoprefixer": "^10.4.24",
    "axios": "^1.11.0",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^2.0.0",
    "postcss": "^8.5.6",
    "tailwindcss": "^3.4.19",
    "vite": "^7.0.7"
}
```

---

## Ações Exatas

### Passo 1 — Remover dependências React de `package.json`

**Arquivo:** `/home/gacpac/gacpac-ti/package.json`

Remover as linhas:
```diff
-"@headlessui/react": "^2.0.0",
-"@vitejs/plugin-react": "^4.2.0",
```

O arquivo final de `devDependencies` deve ficar:
```json
"devDependencies": {
    "@tailwindcss/forms": "^0.5.2",
    "autoprefixer": "^10.4.24",
    "axios": "^1.11.0",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^2.0.0",
    "postcss": "^8.5.6",
    "tailwindcss": "^3.4.19",
    "vite": "^7.0.7"
}
```

### Passo 2 — Atualizar lockfile

```bash
npm install
```

### Passo 3 — Verificar build

```bash
npm run build
```

Deve concluir sem erros.

### Passo 4 — Verificar ausência de React no bundle

```bash
ls public/build/assets/
# Não deve conter arquivos com "react" no nome
grep -r "react" public/build/assets/ || echo "Sem React no bundle"
```

---

## Critérios de Aceite

- [ ] `package.json` não contém `@headlessui/react`
- [ ] `package.json` não contém `@vitejs/plugin-react`
- [ ] `npm install` executa sem erros
- [ ] `npm run build` executa sem erros
- [ ] Nenhum arquivo `.jsx` ou `.tsx` é referenciado no build
- [ ] `vite.config.js` não foi alterado (continua sem plugin React)

## Commit Esperado

```
chore(frontend): remove dependencias react residuais da migracao

- remove @headlessui/react do package.json
- remove @vitejs/plugin-react do package.json
- toda a ui ja utiliza blade e livewire
```

## NÃO FAZER

- ❌ Não alterar `vite.config.js` (já está correto)
- ❌ Não remover `tailwindcss`, `axios`, `autoprefixer`, `postcss` ou `concurrently`
- ❌ Não alterar `resources/js/app.js` ou `resources/css/app.css`
- ❌ Não alterar nada em `app/`, `routes/` nesta fase
