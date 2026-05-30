# Lupo-Actors

Centralized hub for actor-specific resources. **Canonical layout** (see [PRD 00 §5.6](../docs/prd/00_root_constitutional_system_requirements.md#56-actor-id-semantics)): **`actor_id` &lt; 2026** → **`actors/{actor_id}/`**; **`actor_id` ≥ 2026** (typical web/`IdGenerator` ids) → **`actors/YYYY/MM/{actor_id}/`**. Registry field **`dir`** in `database/lupopedia/actors/registry.json` is authoritative. Legacy slug-named folders (e.g. `wolfie/`, `cursor/`) may remain until migrated; do not add new slug-only hubs for registry actors (e.g. COUNTERMEASURE lives under **`actors/111/`**, not `countermeasure/`). **Canonical Lupopedia-owned directories** at repo root use the **`` prefix** (e.g. `actors`, `docs`, `database`, `bin`, `scripts`, `tests`, `rules`).

Exception: package-manager dependency folders such as `node_modules/` (root) and `tools/vsx-extension/node_modules/` are external npm-managed caches, not Lupopedia-owned canonical directories. They keep standard npm naming and must not be renamed to a `` variant.

**Full documentation:** docs/actors.md

- **Actor 0** — System (core platform, security, low-level management)
- **Actor 1** — WOLFIE (governing agent, coordination, orchestration)
- **Actor 19** — Anubis (recovery: orphan adoption, quarantine, recovery)
- **Actor 42** — Antigravity (canonical; IDE extensions, VSX)

Each actor directory contains: `apps/`, `tools/`, `docs/`, `db-changes/`, `api/`, `needs/`, and `prompts/`. Actor 0 also has `logs/` for system scan and queue logs. The `prompts/` folder holds prompt files (e.g. `flare-header-scan.md` in actor 0) used to guide actor behavior.

**Actor application folder (4.0.67, ROOT doctrine):** Every actor has an `apps/` directory with:
- **skills/skills.md** — Canonical skill registry (SKILL INDEX, SKILL DEFINITIONS, FAUCET COMPATIBILITY, CHANGELOG)
- **scripts/** — Shell, Python, or Lupopedia-native scripts
- **assets/** — `icons/`, `images/`, `prompts/`, `templates/` (content-addressed)
- **references/** — `schema.md` (how this actor uses skills and assets), `manifest.json` (machine-readable index)

See ROOT doctrine emails and `_template_apps/` for the canonical structure. Regenerate or sync with `scripts/ensure_actor_apps_structure.ps1`.
