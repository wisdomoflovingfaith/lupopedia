# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/status/AGENT_IDENTITY_REGISTRY_4.0.57
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/AGENT_IDENTITY_REGISTRY_4.0.57"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "guide"
  artifact_kind: "identity_registry"
  purpose: "Registry as source of truth for agent IDs; optional agent_name_identity in FLARE headers"
  mood_rgb: "4169E1"
  traits: ["canonical", "v4.0.57", "identity", "registry"]
  tags: ["identity", "registry", "agents", "v4.0.57"]
  agent_name_identity: "Cursor IDE Agent"
  lupo_agent: "cursor"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 1.0 }
    - { to: "docs/status/LILITH_FLAME_FAUCET_REPORT.md", type: "references", weight: 0.8 }
    - { to: "docs/status/FLARE_FEDERATION_REFINEMENT_4.0.57.md", type: "references", weight: 0.7 }
lupopedia.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## Registry as source of truth

Actor and agent IDs are defined in the project's **actor registry**. Do not rely on inline ID lists in docs or code; **tooling MUST read the registry** for:

- Audit trails and delegation chains
- Faucet ownership and agent assignment
- Avoiding drift (e.g. legacy 2038 vs canonical 2 for Lilith)

**Registry paths:** The master registry file contains the index of all actors. Per-actor directories hold actor-specific data (faucets, configs, logs).

- **Canonical:** `lupo-database/lupopedia/actors/actor_id/registry.json` (under the project's database root)
- **Per-actor dirs:** `lupo-database/lupopedia/actors/actor_id/<id>/`
- **Shorthand:** Docs may refer to `actors/registry.json` when the database root is implied; tooling should resolve to the canonical path above.

## Optional agent_name_identity

FLARE headers may include **agent_name_identity** (v4.0.57+): a string for how the agent identifies (e.g. "You are ___" from system prompt). Use it for human-readable logs and UI; always resolve `actor_id` from the registry. See FLARE_DOCTRINE **Section 24** (Agent Identity Fields).

## Examples (from registry / doctrine only)

| Agent | actor_id | agent_name_identity |
|-------|----------|---------------------|
| Captain Wolfie | 10000 | Captain Wolfie |
| Cursor | 1003 | Cursor IDE Agent |
| Lilith | 2 | Lilith Flame Expert |
| ANUBIS | 19 | ANUBIS |

*Note: These values are examples only. Always resolve from the registry.*

