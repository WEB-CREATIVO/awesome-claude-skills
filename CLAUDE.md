# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What This Repository Is

A curated collection of Claude Skills — reusable instruction packages that teach AI agents how to handle specific task classes. The repo also includes utility scripts for creating and packaging skills. Skills are supported by Claude Code, Claude.ai, the Claude API, and other agent platforms (Codex, Cursor, Gemini CLI, etc.).

## Repository Structure

```
awesome-claude-skills/
├── README.md                    # Master index; the Skills section is auto-validated by CI
├── CONTRIBUTING.md              # Contribution rules and PR process
├── <skill-name>/                # Each top-level folder is one skill
│   ├── SKILL.md                 # Required: YAML frontmatter + Markdown instructions
│   ├── scripts/                 # Optional: Executable helper scripts (Python/Bash)
│   ├── references/              # Optional: Docs loaded into context at runtime
│   └── assets/                  # Optional: Templates, fonts, images used in output
├── skill-creator/               # Meta-skill for building new skills; contains the
│   └── scripts/                 #   init_skill.py, package_skill.py, quick_validate.py
├── composio-skills/             # ~800 auto-generated Composio app-automation skills
└── .github/workflows/
    └── label-ready-skill.yml    # CI: validates README-only PRs that add skill bullets
```

The `composio-skills/` directory is generated content — avoid editing individual skills there manually.

## Skill File Format

Every `SKILL.md` must begin with YAML frontmatter:

```markdown
---
name: hyphen-case-name          # required; lowercase letters, digits, hyphens only
description: One-sentence ...   # required; no angle brackets; triggers when Claude selects skill
license: ...                    # optional
---

# Skill Title

[Markdown instructions written in imperative/infinitive form, not second-person]
```

Rules enforced by `quick_validate.py`:
- `name` must be hyphen-case (`[a-z0-9-]+`), cannot start/end with or contain consecutive hyphens
- `description` cannot contain `<` or `>`

## Skill Tooling (skill-creator/scripts/)

### Initialize a new skill scaffold
```bash
python skill-creator/scripts/init_skill.py <skill-name> --path <output-directory>
```
Creates `<output-directory>/<skill-name>/` with a SKILL.md template and placeholder `scripts/`, `references/`, and `assets/` subdirectories.

### Validate a skill
```bash
python skill-creator/scripts/quick_validate.py <path/to/skill-folder>
```
Exits 0 on success, 1 on failure.

### Package a skill for distribution
```bash
python skill-creator/scripts/package_skill.py <path/to/skill-folder> [output-dir]
```
Runs validation first, then zips the folder into `<skill-name>.zip`.

No build, lint, or test commands exist at the repo level — skills are static Markdown/asset bundles, not compiled code.

## CI: PR Validation (label-ready-skill.yml)

The GitHub Actions workflow fires on every PR and enforces:

1. **Only `README.md` may be modified** — no other files.
2. **All edits must fall within the `## Skills` … `## Getting Started` window** in `README.md`.
3. **At least one new bullet link must be added** in that window.
4. **Every new bullet must link to an external URL** (not `composio.dev` or `anthropic.com`).
5. **No crypto/web3/blockchain/NFT keywords** anywhere in added lines.
6. **Alphabetical order** within each `###` category must be maintained for new bullets.

PRs that pass all checks receive the `ready-to-merge` label automatically.

## Contributing a New External Skill (README-only PR)

When adding an external skill reference to `README.md`:

- Choose the correct `###` category and insert in alphabetical order.
- Follow the exact bullet format (no emojis, consistent punctuation):
  ```markdown
  - [Skill Name](https://external-url) - One-sentence description. *By [@author](https://github.com/author)*
  ```
- Change **only** `README.md`; do not touch any skill folders or other files.

## Contributing a New Local Skill (folder PR)

When adding a skill that lives in this repo:

1. Run `init_skill.py` to scaffold, or create `<skill-name>/SKILL.md` directly.
2. Write SKILL.md in imperative form; keep it under ~5 000 tokens.
3. Add supporting files under `scripts/`, `references/`, or `assets/` only if needed; delete the example placeholders `init_skill.py` generates.
4. Validate: `python skill-creator/scripts/quick_validate.py <skill-name>/`.
5. Add a bullet in `README.md` under the correct category in alphabetical order.
6. PR title: `Add [Skill Name] skill`.

## Skills Architecture: How Loading Works

Skills use progressive disclosure to avoid bloating the agent's context window:

| Level | Content | When loaded |
|-------|---------|-------------|
| 1 | `name` + `description` (~100 tokens) | Always |
| 2 | Full `SKILL.md` body (<5 000 tokens) | When agent selects the skill |
| 3 | `scripts/`, `references/`, `assets/` | On demand by the agent |

Scripts in `scripts/` may be executed directly without being read into context. Reference docs in `references/` are loaded only when the agent decides they are needed. Asset files in `assets/` are never loaded into context — they are used as output material (templates, fonts, images).

## Key Skills in This Repo

- **skill-creator** — the meta-skill for creating other skills; its `scripts/` directory is the canonical tooling for the repo.
- **artifacts-builder** — React + TypeScript + Vite + Tailwind + shadcn/ui pipeline for claude.ai HTML artifacts; uses `scripts/init-artifact.sh` and `scripts/bundle-artifact.sh`.
- **mcp-builder** — guides creation of MCP servers (Python or TypeScript); contains reference docs for MCP best practices.
- **connect / connect-apps** — Composio-backed plugin that lets Claude take real actions across 1 000+ SaaS apps.
- **webapp-testing** — Playwright-based local web app testing.
