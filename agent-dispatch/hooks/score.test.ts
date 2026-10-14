import { test, expect } from 'claude-code/testing'

import { score, tierOf } from './score'

test('tareas triviales caen en low', () => {
  expect(tierOf(score('find the typo in README.md'))).toBe('low')
})

test('refactors y depuración suben a high', () => {
  expect(tierOf(score('Refactor the auth architecture across src/a.ts src/b.ts src/c.ts and debug the race condition'))).toBe('high')
})
