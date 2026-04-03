---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-actors/102/README.md
  last_modified_utc: "20260402233135"
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: guide
  artifact_kind: actor_hub
  purpose: Hub for Cursor IDE facet only (actor_id 102). Antigravity IDE uses actor_id 103 — see lupo-actors/103/README.md.
lupopedia.edges:
  outbound_edges:
    - to: lupo-database/lupopedia/actors/registry.json
      type: references
      weight: 1.0
      reason: Canonical actor_id and delegates_to_actor_id
    - to: lupo-database/lupopedia/actors/actor_id/registry.json
      type: references
      weight: 0.95
      reason: lupo_agents slug to agent_id map includes cursor
    - to: lupo-agents/cursor/agent.json
      type: references
      weight: 1.0
      reason: Runtime pack for this faucet
    - to: lupo-agents/_shared/ide_facet_base_system_prompt.txt
      type: references
      weight: 1.0
      reason: Shared IDE vetoes — single edit surface for all IDE facets
    - to: lupo-scripts/propagate_agent_rules.php
      type: references
      weight: 1.0
      reason: Full rule bundle to .cursor/rules
    - to: AGENTS.md
      type: references
      weight: 1.0
    - to: lupo-docs/doctrine/AGENT_REGISTRY.md
      type: references
      weight: 1.0
    - to: lupo-actors/103/README.md
      type: references
      weight: 1.0
      reason: Antigravity IDE facet 103 — separate from Cursor 102
lupopedia.footer:
  last_verified: "20260402233135"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: cursor:root
---

# file: lupo-actors/102/README.md — delegation: cursor:root

# Cursor IDE facet (actor_id 102)

## Canonical identity

- **Actor:** `cursor`, **actor_id** **102** — Cursor product surface only (`lupo-database/lupopedia/actors/registry.json`)
- **Antigravity IDE** is **103** (`antigravity-ide`) — [lupo-actors/103/README.md](../103/README.md)
- **Orchestration delegate:** **WOLFIE** (`actor_id` **1**) via `delegates_to_actor_id`
- **lupo_agents pack:** `lupo-agents/cursor/` — **agent_id** **102**, registered in `lupo-database/lupopedia/actors/actor_id/registry.json` under `"cursor": 102`
- **Implementation posture:** Align execution with **HEPHAESTUS** (`lupo-agents/hephaestus/`); IDE faucets are treated as implementation surfaces under that archetype in seed commentary

## Full rule set (not in this folder)

Run:

```bash
php lupo-scripts/propagate_agent_rules.php --target=cursor
```

Source: `lupo-rules/root/` → `.cursor/rules/*.mdc` and `.cursor/lupopedia_rules.json`.

## Install SQL note (drift)

`install_new_lupopedia.sql` may still list **Cursor IDE** under a different `actor_id` (e.g. **211** as `cursor-ide`) and use **102** for a Hephaestus **work_agent** row. **File registry** (`actors/registry.json`) is the coordination source for IDE docs and headers until install/seed are explicitly reconciled in a dedicated change.

This hub complies with Lupopedia Constitutional Root Rules.
