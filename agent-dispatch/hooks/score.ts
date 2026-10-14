import type { Tier } from '../types'

const HIGH =
  /\b(refactor|architect|architecture|design|migrat|security|vulnerab|race condition|concurren|performance|optimi[sz]e|root cause|debug|investigate|rewrite|multi-?file|across the (repo|codebase)|end-to-end|integration|algorithm|diseñ|arquitectur|refactoriz|migra|seguridad|depura|investiga|reescrib|optimiza)/gi
const LOW =
  /\b(rename|list|find|grep|search|locate|summari[sz]e|format|typo|lint|count|read|show|print|busca|lista|renombra|resume|formatea|cuenta|muestra)\b/i

/** Heurística de complejidad: 0 (trivial) .. 10 (muy compleja). */
export const score = (prompt: string): number => {
  let s = 3
  const words = prompt.trim().split(/\s+/).length
  if (words > 40) s += 1
  if (words > 120) s += 1
  if (words > 300) s += 1
  const files = new Set(prompt.match(/[\w./-]+\.\w{1,5}\b/g) ?? []).size
  if (files >= 3) s += 1
  if (files >= 6) s += 1
  const steps = (prompt.match(/^\s*(\d+[.)]|[-*])\s/gm) ?? []).length
  if (steps >= 4) s += 1
  const hits = new Set((prompt.match(HIGH) ?? []).map(m => m.toLowerCase())).size
  if (hits > 0) s += Math.min(4, 2 + (hits - 1))
  else if (LOW.test(prompt)) s -= 2
  return Math.max(0, Math.min(10, s))
}

export const tierOf = (s: number): Tier => (s <= 3 ? 'low' : s <= 6 ? 'mid' : 'high')
