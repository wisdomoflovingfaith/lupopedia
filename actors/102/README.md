---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/102/README.md
  web_path: https://www.lupopedia.com/lupopedia/actors/102/README.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: guide
  artifact_kind: actor_hub
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# file: actors/102/README.md — delegation: cursor:root

# Cursor IDE facet (actor_id 102)

## Canonical identity

- **Actor:** `cursor`, **actor_id** **102** — Cursor product surface only (`database/lupopedia/actors/registry.json`)
- **Antigravity IDE** is **103** (`antigravity-ide`) — [actors/103/README.md](../103/README.md)
- **Orchestration delegate:** **WOLFIE** (`actor_id` **1**) via `delegates_to_actor_id`
- **lupo_agents pack:** `agents/cursor/` — **agent_id** **102**, registered in `database/lupopedia/actors/actor_id/registry.json` under `"cursor": 102`
- **Implementation posture:** Align execution with **HEPHAESTUS** (`agents/hephaestus/`); IDE faucets are treated as implementation surfaces under that archetype in seed commentary

## Full rule set (not in this folder)

Run:

```bash
php scripts/propagate_agent_rules.php --target=cursor
```

Source: `rules/root/` → `.cursor/rules/*.mdc` and `.cursor/lupopedia_rules.json`.

## Install SQL note (drift)

`install_new_lupopedia.sql` may still list **Cursor IDE** under a different `actor_id` (e.g. **211** as `cursor-ide`) and use **102** for a Hephaestus **work_agent** row. **File registry** (`actors/registry.json`) is the coordination source for IDE docs and headers until install/seed are explicitly reconciled in a dedicated change.

This hub complies with Lupopedia Constitutional Root Rules.
