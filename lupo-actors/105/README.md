---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-actors/105/README.md
  last_modified_utc: "20260404183000"
  channel_id: 42
  actor_id: 105
  actor_name: cascade
  delegation_chain: cascade:root
  artifact_type: guide
  artifact_kind: actor_hub
  purpose: Hub for Cascade IDE facet (actor_id 105).
lupopedia.edges:
  outbound_edges:
    - to: lupo-database/lupopedia/actors/registry.json
      type: references
      weight: 1.0
    - to: lupo-agents/cascade/agent.json
      type: references
      weight: 1.0
    - to: lupo-agents/_shared/ide_facet_base_system_prompt.txt
      type: references
      weight: 1.0
    - to: lupo-scripts/propagate_agent_rules.php
      type: references
      weight: 1.0
      reason: "--target=cascade → .cascade/rules"
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
    actor_id: 105
  orchestrator: cascade:root
---

# file: lupo-actors/105/README.md — delegation: cascade:root

# Cascade IDE facet (actor_id 105)

## Canonical identity

- **Actor:** `cascade`, **actor_id** **105** — `lupo-database/lupopedia/actors/registry.json`
- **lupo_agents pack:** `lupo-agents/cascade/` — **agent_id** **105**
- **Rules:** `php lupo-scripts/propagate_agent_rules.php --target=cascade` → `.cascade/rules/`

## Cross-links

- **Cursor facet (102):** [lupo-actors/102/README.md](../102/README.md)

This hub complies with Lupopedia Constitutional Root Rules.
