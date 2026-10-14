import { atom, read, update } from 'claude-code'
import type { Register } from 'claude-code'

import type { AgentRow, Routing, Tier } from '../types'
import { score, tierOf } from './score'

const PANE = 'agent-dispatch'
const REF = { plugin: 'agent-dispatch' } as const

// Alias por defecto (el motor los resuelve al modelo más reciente de cada familia);
// se pueden cambiar con /route low=<modelo> mid=<modelo> high=<modelo>.
const DEFAULTS: Routing = {
  enabled: true,
  models: { low: 'haiku', mid: 'sonnet', high: 'opus' },
}

const agents = atom({ ...REF, key: 'agents' } as const, [])
const routing = atom({ ...REF, key: 'routing' } as const, DEFAULTS)

const short = (s: string, n: number) => (s.length > n ? s.slice(0, n - 1) + '…' : s)

const describe = (tool: string, args: Record<string, unknown>) => {
  const arg = args.command ?? args.file_path ?? args.pattern ?? args.path ?? args.url ?? args.query
  return typeof arg === 'string' ? `${tool} ${short(arg, 60)}` : tool
}

const patch = (id: string, fn: (a: AgentRow) => AgentRow) => (list: AgentRow[]) =>
  list.map(a => (a.id === id ? fn(a) : a))

const parseRoute = (args: string, cur: Routing): { next: Routing; text: string } => {
  const next: Routing = { enabled: cur.enabled, models: { ...cur.models } }
  for (const tok of args.trim().split(/\s+/).filter(Boolean)) {
    if (tok === 'on') next.enabled = true
    else if (tok === 'off') next.enabled = false
    else {
      const m = /^(low|mid|high)=(\S+)$/.exec(tok)
      if (!m) return { next: cur, text: `No entiendo "${tok}". Uso: /route [on|off] [low=<modelo>] [mid=<modelo>] [high=<modelo>]` }
      next.models[m[1] as Tier] = m[2] ?? ''
    }
  }
  const { low, mid, high } = next.models
  return {
    next,
    text: `Enrutado ${next.enabled ? 'activo' : 'desactivado'}: low→${low}, mid→${mid}, high→${high}`,
  }
}

export const register: Register = on => {
  on('session.start', async ($, e, next) => {
    await $.command.register({ name: 'agents', description: 'Abre el panel de agentes (qué hace cada uno)' })
    await $.command.register({
      name: 'route',
      description: 'Enrutado de modelo por complejidad: /route [on|off] [low=..] [mid=..] [high=..]',
    })
    void $.ui.open({ id: PANE, title: 'Agentes' })

    return next(e)
  })

  on('command.run', { command: 'agents' }, async $ => {
    await $.ui.open({ id: PANE, title: 'Agentes' })

    return { text: 'Panel de agentes abierto.' }
  })

  on('command.run', { command: 'route' }, async ($, e) => {
    const cur = await read($, routing)
    const { next, text } = parseRoute(e.args, cur)
    await update($, routing, () => next)

    return { text }
  })

  // Elige el modelo según la complejidad, salvo que la llamada ya fije uno.
  on('agent.spawn', async ($, e, next) => {
    const cfg = await read($, routing)
    const s = score(e.prompt)
    const tier = tierOf(s)
    const isRouted = cfg.enabled && e.model === undefined
    const model = isRouted ? cfg.models[tier] : e.model
    const res = await next(isRouted ? { ...e, model } : e)

    if (res.deny === undefined && res.agentId !== undefined) {
      const row: AgentRow = {
        id: res.agentId,
        type: e.subagentType,
        description: e.description,
        model: res.model,
        tier,
        score: s,
        routed: isRouted,
        status: 'running',
        activity: 'arrancando…',
        toolCount: 0,
        startedAt: await $.clock.now(),
      }
      await update($, agents, list => [...list, row].slice(-50))
    }

    return res
  })

  on('tool.call', async ($, e, next) => {
    const id = e.agentId
    if (id !== undefined) {
      const text = describe(String((e as { tool?: unknown }).tool ?? 'tool'), e as unknown as Record<string, unknown>)
      await update($, agents, patch(id, a => ({ ...a, activity: text, toolCount: a.toolCount + 1 })))
    }

    return next(e)
  })

  on('turn.complete', async ($, e, next) => {
    const id = e.agentId
    if (id !== undefined) {
      const failed = e.reason === 'error' || e.reason === 'refusal' || e.reason === 'aborted'
      await update(
        $,
        agents,
        patch(id, a => ({
          ...a,
          status: failed ? 'failed' : 'done',
          activity: failed ? `terminó: ${e.reason}` : short(e.answer.replace(/\s+/g, ' '), 70) || 'terminado',
          durationMs: e.durationMs,
        })),
      )
    }

    return next(e)
  })

  on('ui.render', { component: 'Pane', requestId: PANE }, async ($, e) => {
    const { Box, Text } = $.ui.resolve(e)
    const list = await read($, agents)
    const cfg = await read($, routing)
    const room = Math.max(1, Math.floor(((e.viewport?.rows ?? 24) - 4) / 2))
    const running = list.filter(a => a.status === 'running').length

    return (
      <Box flexDirection="column">
        <Text bold>
          {running} activos · {list.length} total · enrutado {cfg.enabled ? 'on' : 'off'} (low→{cfg.models.low} mid→
          {cfg.models.mid} high→{cfg.models.high})
        </Text>
        {list.length === 0 && <Text dimColor>Aún no hay subagentes.</Text>}
        {list.slice(-room).map(a => (
          <Box flexDirection="column">
            <Text dimColor={a.status !== 'running'}>
              {a.status === 'running' ? '●' : a.status === 'done' ? '✓' : '✗'} {a.type} · {short(a.description, 30)} ·{' '}
              {a.model}
              {a.routed ? ` (auto ${a.tier}, ${a.score}/10)` : ' (fijado)'}
            </Text>
            <Text dimColor>
              {'   '}
              {a.toolCount} tools · {a.durationMs !== undefined ? `${Math.round(a.durationMs / 1000)}s · ` : ''}
              {a.activity}
            </Text>
          </Box>
        ))}
      </Box>
    )
  })
}
