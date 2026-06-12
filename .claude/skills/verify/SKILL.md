---
name: verify
description: Run the full local CI pipeline (PHP lint check, Prettier, ESLint, PHPStan level 7, Pest tests) to confirm changes are clean before committing.
---

Run the full CI check from the project root:

```bash
composer run ci:check
```

This runs four steps in sequence:
1. `lint:check` — PHP code style via Pint (reports issues, no auto-fix)
2. `format:check` — Prettier formatting check for JS/TS files
3. `types:check` — PHPStan level 7 static analysis via Larastan
4. `test` — Pest PHP test suite with in-memory SQLite

Report any failures clearly. If lint or format checks fail, tell the user they can auto-fix with:
- PHP: `composer run lint`
- JS/TS: `pnpm format`
