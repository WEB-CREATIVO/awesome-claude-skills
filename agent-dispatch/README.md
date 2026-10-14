# agent-dispatch

Mod de Claude Code: panel en vivo de los subagentes (estado, modelo, herramienta actual) y enrutado
automático de modelo según la complejidad del prompt.

## Instalación

En una sesión de terminal:

```
/plugin install agent-dispatch --marketplace WEB-CREATIVO/awesome-claude-skills
```

Desde un shell:

```
claude plugin install agent-dispatch --marketplace WEB-CREATIVO/awesome-claude-skills
```

En la app de escritorio: + junto al prompt → Plugins → Add marketplace con `WEB-CREATIVO/awesome-claude-skills` → Add plugin.

## Uso

- `/agents` abre el panel.
- `/route [on|off] [low=<modelo>] [mid=<modelo>] [high=<modelo>]` activa/desactiva el enrutado o cambia el
  modelo de cada nivel (por defecto `haiku` / `sonnet` / `opus`). El cambio vale solo para la sesión actual.
- Si la llamada al agente ya fija un modelo, se respeta.

La puntuación de complejidad (`hooks/score.ts`) es una heurística sin calibrar.
