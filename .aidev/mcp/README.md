# MCP - Model Context Protocol

Este diretório contém a configuração do MCP para este projeto.

## Servidores Configurados

### context7
Acesso a documentação e exemplos de código atualizados.

### serena
Navegação inteligente de código com análise semântica.

### basic-memory
Sincronização de lições aprendidas e memória persistente.

## Configurações

### memory-sync.json
Configuração para sincronização automática das lições aprendidas:

```json
{
  "sync_on_lesson_complete": true,
  "mcp_servers": ["basic-memory", "context7"],
  "auto_index": true,
  "cross_project_sync": {
    "enabled": true,
    "filter_tags": ["laravel", "docker", "react", "inertia"]
  }
}
```

## Estrutura

```
mcp/
├── servers/           # Configs de servidores individuais
├── memory/            # Memória persistente
├── memory-sync.json   # Configuração de sincronização
└── README.md          # Este arquivo
```

## Comandos MCP Disponíveis

| Comando | Descrição |
|---------|-----------|
| `mcp__basic-memory__write_note` | Salvar nova lição |
| `mcp__basic-memory__search_notes` | Buscar lições similares |
| `mcp__basic-memory__tag_note` | Adicionar tags |
| `mcp__context7__query-docs` | Consultar documentação |

## Uso

Os servidores MCP são carregados automaticamente pelo AI quando você inicia uma sessão.

## Configuração

Edite `.mcp.json` na raiz do projeto para customizar servidores.

## Teste de Integração

```bash
# Verificar configuração MCP
cat .aidev/mcp/memory-sync.json

# Verificar índice de lições
cat .aidev/memory/kb/.index/lessons-index.json
```
