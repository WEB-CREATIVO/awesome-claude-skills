export type Tier = 'low' | 'mid' | 'high'

export type AgentRow = {
  id: string
  type: string
  description: string
  model: string
  tier?: Tier
  score?: number
  routed: boolean
  status: 'running' | 'done' | 'failed'
  activity: string
  toolCount: number
  startedAt: number
  durationMs?: number
}

export type Routing = {
  enabled: boolean
  models: Record<Tier, string>
}

declare module 'claude-code' {
  interface PluginState {
    'agent-dispatch': { agents: AgentRow[]; routing: Routing }
  }
}
