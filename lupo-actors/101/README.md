---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-actors/101/README.md
  last_modified_utc: "20260404183000"
  channel_id: 42
  actor_id: 101
  actor_name: windsurf
  delegation_chain: windsurf:root
  artifact_type: guide
  artifact_kind: actor_hub
  purpose: Hub for Windsurf IDE facet (actor_id 101).
lupopedia.edges:
  outbound_edges:
    - to: lupo-database/lupopedia/actors/registry.json
      type: references
      weight: 1.0
    - to: lupo-agents/windsurf/agent.json
      type: references
      weight: 1.0
    - to: lupo-agents/_shared/ide_facet_base_system_prompt.txt
      type: references
      weight: 1.0
    - to: lupo-scripts/propagate_agent_rules.php
      type: references
      weight: 1.0
      reason: "--target=windsurf → .windsurf/rules"
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
    actor_id: 101
  orchestrator: windsurf:root
---

# file: lupo-actors/101/README.md — delegation: windsurf:root

# Windsurf IDE facet (actor_id 101)

## Canonical identity

- **Actor:** `windsurf`, **actor_id** **101** — `lupo-database/lupopedia/actors/registry.json`
- **lupo_agents pack:** `lupo-agents/windsurf/` — **agent_id** **101**
- **Rules:** `php lupo-scripts/propagate_agent_rules.php --target=windsurf` → `.windsurf/rules/`

## Cross-links

- **Cursor facet (102):** [lupo-actors/102/README.md](../102/README.md)

This hub complies with Lupopedia Constitutional Root Rules.
