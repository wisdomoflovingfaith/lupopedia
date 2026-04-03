---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-actors/104/README.md
  last_modified_utc: "20260404183000"
  channel_id: 42
  actor_id: 104
  actor_name: warp
  delegation_chain: warp:root
  artifact_type: guide
  artifact_kind: actor_hub
  purpose: Hub for Warp facet (actor_id 104); rule propagation target pending.
lupopedia.edges:
  outbound_edges:
    - to: lupo-database/lupopedia/actors/registry.json
      type: references
      weight: 1.0
    - to: lupo-agents/warp/agent.json
      type: references
      weight: 1.0
    - to: lupo-agents/_shared/ide_facet_base_system_prompt.txt
      type: references
      weight: 1.0
    - to: lupo-docs/doctrine/AGENT_REGISTRY.md
      type: references
      weight: 1.0
      reason: Propagation status for Warp
    - to: lupo-actors/102/README.md
      type: references
      weight: 0.85
    - to: AGENTS.md
      type: references
      weight: 1.0
lupopedia.footer:
  last_verified: "20260404183000"
  verified_by:
    identity_type: actor
    actor_id: 104
  orchestrator: warp:root
---

# file: lupo-actors/104/README.md — delegation: warp:root

# Warp facet (actor_id 104)

## Canonical identity

- **Actor:** `warp`, **actor_id** **104** — `lupo-database/lupopedia/actors/registry.json`
- **lupo_agents pack:** `lupo-agents/warp/` — **agent_id** **104**
- **Rules:** no `--target=warp` yet — use `lupo-rules/root/`; optional mirror from `.cursor/rules` after `--target=cursor`. See **AGENT_REGISTRY.md**.

## Cross-links

- **Cursor facet (102):** [lupo-actors/102/README.md](../102/README.md)

This hub complies with Lupopedia Constitutional Root Rules.
