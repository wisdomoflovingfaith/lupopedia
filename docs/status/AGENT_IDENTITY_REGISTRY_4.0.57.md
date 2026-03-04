# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/AGENT_IDENTITY_REGISTRY_4.0.57
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/AGENT_IDENTITY_REGISTRY_4.0.57"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  agent_name_identity: "Cursor IDE Agent"
  artifact_type: "guide"
  purpose: "Registry as source of truth for agent IDs; optional agent_name_identity in FLARE headers"
  lupo_agent: "cursor"
flare.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 1.0 }
flare.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## Registry as source of truth

Actor and agent IDs are defined in the project's **actor registry**. Do not rely on inline ID lists in docs or code; **tooling MUST read the registry** for:

- Audit trails and delegation chains
- Faucet ownership and agent assignment
- Avoiding drift (e.g. legacy 2038 vs canonical 2 for Lilith)

**Canonical paths (examples):**

- `lupo-database/lupopedia/actors/actor_id/registry.json`
- Per-actor dirs: `lupo-database/lupopedia/actors/actor_id/<id>/`

## Optional agent_name_identity

FLARE headers may include **agent_name_identity** (v4.0.57+): a string for how the agent identifies (e.g. "You are ___" from system prompt). Use it for human-readable logs and UI; always resolve `actor_id` from the registry. See FLARE_DOCTRINE Section 24.

## Examples (from registry / doctrine only)

| Agent (example) | actor_id (example) | agent_name_identity (example) |
|-----------------|--------------------|-------------------------------|
| Captain Wolfie  | 10000              | Captain Wolfie                |
| Cursor          | 1003               | Cursor IDE Agent              |
| Lilith          | 2                  | Lilith Flame Expert           |
| ANUBIS          | 19                 | ANUBIS                        |

All IDs and names are illustrative; resolve from the registry in code and tooling.
