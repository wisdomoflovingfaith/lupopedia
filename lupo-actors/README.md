# Lupo-Actors

Centralized hub for actor-specific resources. Each subdirectory is named by actor ID (e.g. `0`, `1`).

**Full documentation:** [lupo-docs/actors.md](../docs/actors.md)

- **Actor 0** — System (core platform, security, low-level management)
- **Actor 1** — WOLFIE (governing agent, coordination, orchestration)
- **Actor 19** — Anubis (recovery: orphan adoption, quarantine, recovery)
- **Actor 42** — Antigravity (canonical; IDE extensions, VSX)

Each actor directory contains: `apps/`, `lupo-tools/`, `lupo-docs/`, `db-changes/`, `lupo-api/`, `needs/`, and `lupo-prompts/`. Actor 0 also has `logs/` for system scan and queue logs. The `lupo-prompts/` folder holds prompt files (e.g. `flare-header-scan.md` in actor 0) used to guide actor behavior.

**Actor application folder (4.0.67, ROOT doctrine):** Every actor has an `apps/` directory with:
- **skills/skills.md** — Canonical skill registry (SKILL INDEX, SKILL DEFINITIONS, FAUCET COMPATIBILITY, CHANGELOG)
- **lupo-scripts/** — Shell, Python, or Lupopedia-native scripts
- **assets/** — `icons/`, `lupo-images/`, `lupo-prompts/`, `lupo-templates/` (content-addressed)
- **references/** — `schema.md` (how this actor uses skills and assets), `manifest.json` (machine-readable index)

See ROOT doctrine emails and `_template_apps/` for the canonical structure. Regenerate or sync with `lupo-scripts/ensure_actor_apps_structure.ps1`.
