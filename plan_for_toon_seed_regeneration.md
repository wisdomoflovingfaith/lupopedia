---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.0
file.channel: doctrine
file.last_modified_utc: 20260204120000
file.name: "plan_for_toon_seed_regeneration.md"
---

TOON & SEED REGENERATION PLAN
Canonical Python tooling in /scripts/ — DO NOT create or modify Python files for this workflow.
System Version: 3.0.0

---

# TOON & Seed Regeneration Plan (Canonical Workflow)

## Purpose

Regenerate all TOON files and the seed file using the canonical Python scripts. DB credentials come from `lupopedia-config.php` via `scripts/db_config.py`. No hand-editing of TOONs, seed, or Python; run the existing scripts only.

---

## Phase 1 — Regenerate TOONs (COMPLETED)

### 1. Run TOON generator from project root

```bash
python scripts/generate_toon_files.py
```

### 2. Generator must

- Clear `docs/toons/*.toon.json` before writing.
- Introspect schema from the live database.
- Include PK=0 rows where present.
- Include unified registry rows (all from DB).
- Include active agents as actors (actor/agent doctrine): one unified registry row per `lupo_agent_registry` WHERE `is_active = 1`.
- Exclude inactive agents.
- Write updated TOONs to `docs/toons/`.

### 3. Output

- One `.toon.json` per table in `docs/toons/`.
- Console: e.g. `Wrote N TOONs to .../docs/toons`.

---

## Phase 2 — Regenerate seed from TOONs (COMPLETED)

### 1. Run seed generator from project root

```bash
python scripts/generate_seed_from_toons.py
```

### 2. Seed generator must

- Load TOONs from `docs/toons/`.
- Include all PK=0 rows (from DB for tables in TOONs).
- Include all unified registry rows (from DB) plus one row per active agent (entity_type='actor', entity_table='lupo_agent_registry').
- Include one **`lupo_actors`** row per active agent (actor/agent doctrine): every `lupo_agent_registry` WHERE `is_active = 1` gets a corresponding `INSERT INTO lupo_actors` with `actor_id = agent_registry_id`, `actor_type = 'agent'`, `actor_source_type = 'lupo_agent_registry'`.
- Include TOON-defined canonical rows (from each TOON's "data" array).
- Append `ALTER TABLE lupo_actors AUTO_INCREMENT = 10000;`
- Write output to `database/migrations/seed_lupopedia.sql`.

### 3. Output

- Regenerated `database/migrations/seed_lupopedia.sql`.
- Console: Unified registry INSERTs, lupo_actors INSERTs (active agents), PK=0 INSERTs, TOON data INSERTs.

---

## Phase 3 — Do NOT modify

- Doctrine files (e.g. `docs/channels/doctrine/*.md`).
- Schema or migration SQL (other than regenerating the seed file).
- PHP files.
- `lupopedia-config.php`.
- `scripts/actor_agent_doctrine.py` (shared doctrine logic; only run scripts, do not change for this workflow unless doctrine itself is updated).
- Any Python file in `/scripts/` when the task is "run existing scripts only."

---

## After running both scripts — Summary to print

- Number of TOONs generated.
- Number of unified registry rows (and how many are active agents as actors).
- Number of active agents added as **lupo_actors** INSERTs.
- Confirmation that `database/migrations/seed_lupopedia.sql` was regenerated.

---

## Reference doctrine & governance

- **Actor/agent doctrine:** `docs/channels/doctrine/ACTOR_AGENT_DOCTRINE.md` — active agents become unified registry rows and **lupo_actors** rows; actor_id 0–9999 for AI agents; human actors start at 10000.
- **TOON generation governance:** `docs/channels/dev-teams/governance/GOV-TOON-GENERATION-001.md` — canonical generator `scripts/generate_toon_files.py`; TOON location `docs/toons/`; `<table_name>.toon.json`.
- **Shared logic:** `scripts/actor_agent_doctrine.py` — constants and row builders for unified registry and lupo_actors from agent rows.

---

## Completion criteria

- [ ] `python scripts/generate_toon_files.py` run successfully; TOONs in `docs/toons/`.
- [ ] `python scripts/generate_seed_from_toons.py` run successfully; `seed_lupopedia.sql` regenerated.
- [ ] Seed contains: unified registry, **lupo_actors for all active agents**, PK=0 rows, TOON canonical rows, `ALTER TABLE lupo_actors AUTO_INCREMENT = 10000;`
- [ ] Summary printed: TOON count, unified registry count, lupo_actors (active agents) count, seed regeneration confirmed.
