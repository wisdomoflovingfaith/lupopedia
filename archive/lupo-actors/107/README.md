---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-actors/107/README.md
  questions_toon: null
  channel_id: 42
  actor_id: 107
  actor_name: trae
  delegation_chain: trae:root
  artifact_type: guide
  artifact_kind: actor_hub
  purpose: Hub for Trae IDE facet (actor_id 107); rule propagation target pending.
lupopedia.edges:
  outbound_edges:
    - to: lupo-database/lupopedia/actors/registry.json
      type: references
      weight: 1.0
    - to: lupo-agents/trae/agent.json
      type: references
      weight: 1.0
    - to: lupo-agents/_shared/ide_facet_base_system_prompt.txt
      type: references
      weight: 1.0
    - to: lupo-docs/doctrine/AGENT_REGISTRY.md
      type: references
      weight: 1.0
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
    actor_id: 107
  orchestrator: trae:root
---

# file: lupo-actors/107/README.md — delegation: trae:root

# Trae IDE facet (actor_id 107)

## Canonical identity

- **Actor:** `trae`, **actor_id** **107** — `lupo-database/lupopedia/actors/registry.json`
- **lupo_agents pack:** `lupo-agents/trae/` — **agent_id** **114**
- **Rules:** no `--target=trae` yet — use `lupo-rules/root/`; optional mirror from `.cursor/rules` after `--target=cursor`. See **AGENT_REGISTRY.md**.

**Note:** `lupo_agents` **agent_id** **107** is reserved for **themis** in `actor_id/registry.json`; Trae uses **114**. Actor id and agent id namespaces differ.

## Cross-links

- **Cursor facet (102):** [lupo-actors/102/README.md](../102/README.md)

This hub complies with Lupopedia Constitutional Root Rules.
