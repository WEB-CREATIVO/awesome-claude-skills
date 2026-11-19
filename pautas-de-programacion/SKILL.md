---
name: pautas-de-programacion
description: Pautas de comportamiento para que Claude programe con menos errores (pensar antes de escribir, mantener la simplicidad, hacer cambios quirúrgicos, verificar objetivos) y para crear skills nuevas siguiendo template-skill. Úsala al escribir o modificar código, o al crear una skill.
---

# Pautas de programación

Pautas de comportamiento para reducir los errores habituales de los LLM al programar. Combínalas con las instrucciones específicas del proyecto.

**Compromiso:** estas pautas priorizan la cautela sobre la velocidad. En tareas triviales (un typo, un cambio de una línea), usa el criterio.

## 1. Pensar antes de programar

**No asumas. No ocultes dudas. Expón los compromisos.**

Antes de implementar:
- Declara tus supuestos de forma explícita. Si dudas, pregunta.
- Si hay varias interpretaciones posibles, preséntalas; no elijas en silencio.
- Si existe un enfoque más simple, dilo. Discrepa cuando haga falta.
- Si algo no está claro, detente, nombra qué te confunde y pregunta.

## 2. Simplicidad primero

**El mínimo código que resuelva el problema. Nada especulativo.**

- Ninguna funcionalidad más allá de lo pedido.
- Ninguna abstracción para código de un solo uso.
- Ninguna "flexibilidad" o "configurabilidad" que no se haya pedido.
- Ningún manejo de errores para escenarios imposibles.
- Si escribes 200 líneas y podrían ser 50, reescribe.

Pregúntate: "¿Diría un ingeniero senior que esto está sobrecomplicado?". Si la respuesta es sí, simplifica.

## 3. Cambios quirúrgicos

**Toca solo lo necesario. Limpia solo lo que tú ensucies.**

Al editar código existente:
- No "mejores" código, comentarios o formato adyacentes.
- No refactorices lo que no está roto.
- Respeta el estilo existente, aunque tú lo harías distinto.
- Si ves código muerto ajeno a la tarea, menciónalo; no lo borres.

Cuando tus cambios dejen huérfanos:
- Elimina imports, variables o funciones que TUS cambios hayan dejado sin uso.
- No elimines código muerto preexistente salvo que te lo pidan.

La prueba: cada línea modificada debe poder rastrearse directamente hasta la petición del usuario.

## 4. Ejecución orientada a objetivos

**Define criterios de éxito. Itera hasta verificarlos.**

Convierte las tareas en objetivos verificables:
- "Añadir validación" → "Escribir tests con entradas inválidas y hacerlos pasar".
- "Arreglar el bug" → "Escribir un test que lo reproduzca y hacerlo pasar".
- "Refactorizar X" → "Comprobar que los tests pasan antes y después".

En tareas de varios pasos, expón un plan breve:
```
1. [Paso] → verificar: [comprobación]
2. [Paso] → verificar: [comprobación]
3. [Paso] → verificar: [comprobación]
```

Criterios de éxito sólidos permiten iterar de forma autónoma. Criterios débiles ("que funcione") obligan a pedir aclaraciones constantemente.

## 5. Crear skills nuevas

**Sigue `template-skill/` y `CONTRIBUTING.md`. No inventes otra estructura.**

Antes de crear:
- Confirma que resuelve un caso de uso real, no hipotético, y que no duplica una skill existente. Búscalo en el repo antes de empezar.
- Si el caso de uso viene de otra persona o fuente, atribúyelo.

Al crear:
- Carpeta nueva en minúsculas y con guiones (`nombre-de-la-skill/`) que contenga un `SKILL.md`.
- El `SKILL.md` empieza con frontmatter de dos campos: `name` (igual al nombre de la carpeta) y `description` (una frase: qué hace y cuándo usarla).
- Después del frontmatter sigue la plantilla de `CONTRIBUTING.md`: Cuándo usar la skill, Qué hace, Cómo usarla, Ejemplo, Consejos.
- Incluye ejemplos prácticos y redáctala para que la entienda alguien no técnico.
- Si la skill puede hacer algo destructivo, que pida confirmación antes de actuar.
- Que sea portable entre Claude.ai, Claude Code y API cuando aplique, y no afirmes que la probaste si no lo hiciste.

Al terminar:
- Añádela a `README.md` en la categoría que corresponda, en orden alfabético, con el formato `- [Nombre](./carpeta/) - Descripción en una frase.`, sin emojis y con la puntuación de las entradas vecinas.
- Cambia solo lo necesario en el `README.md`: una línea nueva, sin reformatear el resto.

---

**Señales de que funcionan:** menos cambios innecesarios en los diffs, menos reescrituras por sobrecomplicación, y preguntas aclaratorias antes de implementar en lugar de después de cometer errores.

---

## Atribución

Adaptación al español (más la sección 5, propia de este repo) de `CLAUDE.md` del repositorio `andrej-karpathy-skills` (licencia MIT), que se inspira en observaciones públicas de Andrej Karpathy sobre fallos de los LLM al programar. No hay constancia de que Karpathy haya escrito o avalado este texto.

## Uso permanente (opcional)

Una skill se carga cuando su descripción encaja con la tarea. Si quieres que estas pautas estén activas siempre, copia el contenido a un `CLAUDE.md` en la raíz de tu proyecto, porque Claude Code solo carga automáticamente ese nombre de archivo.
