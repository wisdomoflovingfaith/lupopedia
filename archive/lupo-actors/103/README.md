---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-actors/103/README.md
  questions_toon: null
  channel_id: 42
  actor_id: 103
  actor_name: antigravity-ide
  delegation_chain: antigravity-ide:root
  artifact_type: guide
  artifact_kind: actor_hub
  purpose: Hub for Antigravity IDE facet (actor_id 103); distinct from Cursor facet (102).
lupopedia.edges:
  outbound_edges:
    - to: lupo-database/lupopedia/actors/registry.json
      type: references
      weight: 1.0
    - to: lupo-agents/antigravity-ide/agent.json
      type: references
      weight: 1.0
    - to: lupo-actors/102/README.md
      type: references
      weight: 0.9
      reason: Cursor facet 102 — do not conflate with 103
    - to: lupo-rules/root/README.md
      type: references
      weight: 1.0
      reason: Canonical rules when IDE has no .cursor load
    - to: AGENTS.md
      type: references
      weight: 1.0
lupopedia.footer:
  last_verified: "20260402231742"
  verified_by:
    identity_type: actor
    actor_id: 103
  orchestrator: antigravity-ide:root
---

# file: lupo-actors/103/README.md — delegation: antigravity-ide:root

# Antigravity IDE facet (actor_id 103)

## Canonical identity

- **Actor:** `antigravity-ide`, **actor_id** **103** — `lupo-database/lupopedia/actors/registry.json`
- **Not Cursor:** **`actor_id` 102** is the **Cursor** product facet only. When working inside Antigravity, use **103** in headers, commits, and channel attribution.
- **lupo_agents pack:** `lupo-agents/antigravity-ide/` — **agent_id** **103**
- **Rules:** `lupo-rules/root/` is canonical; `.cursor/rules` may mirror propagated rules for the repo. Propagation target for Antigravity in `propagate_agent_rules.php` is **pending**—track in `AGENT_REGISTRY.md`.

## Cross-links

- **Cursor facet (102):** [lupo-actors/102/README.md](../102/README.md)

This hub complies with Lupopedia Constitutional Root Rules.
