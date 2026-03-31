# DEVORQ - OpenCode Activation

## Ativar
Digite: `devorq [task]` ou cole este prompt.

## Fluxo
1. /env-context (auto-detect stack)
2. /scope-guard (obrigatório)
3. /pre-flight (valida tipos)
4. TDD (RED→GREEN→REFACTOR)
5. /quality-gate (obrigatório)
6. /session-audit (obrigatório)

## Stack
Laravel, Filament, Python, Shell

## Características OpenCode
-Modo interativo CLI
- Suporte a múltiplas ferramentas
- Execução de comandos shell
- Editor de código integrado

## Rules
- SEMPRE /scope-guard antes de código
- SEMPRE /quality-gate antes de commit
- SEMPRE /session-audit no fim
- Criar checkpoint antes de parar
