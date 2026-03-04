# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/AGENT_REGISTRY_REFINEMENT_4.0.57
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "report"
  file_path_from_root: "docs/status/AGENT_REGISTRY_REFINEMENT_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/AGENT_REGISTRY_REFINEMENT_4.0.57"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  agent_name_identity: "Cursor IDE Agent"
  artifact_type: "report"
  artifact_kind: "refinement"
  purpose: "v4.0.57 Agent Registry Canon and agent_name_identity Header"
  mood_rgb: "4169E1"
  traits: ["report", "v4.0.57", "registry", "identity"]
  tags: ["4.0.57", "agent", "registry", "cursor"]
  lupo_agent: "cursor"
flare.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 0.9 }
flame.see:
  mappings:
    - ["docs/status/AGENT_REGISTRY_REFINEMENT_4.0.57.md", "http://www.lupopedia.com/status/AGENT_REGISTRY_REFINEMENT_4.0.57"]
flare.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## 1. Summary

The **actor registry** (e.g. `lupo-database/lupopedia/actors/actor_id/registry.json`) is recognized as the **canonical source** for agent and actor IDs to avoid drift (e.g. legacy 2038 vs canonical 2 for Lilith). FLARE_DOCTRINE Section 18 already required tooling to read the registry; **Section 24 (v4.0.57+)** adds an optional **agent_name_identity** header field — a human-readable string for how the agent identifies (“You are ___” / “who are you?”), improving audit trails and prompt consistency without hardcoding.

---

## 2. Research: Registry and Identity

### 2.1 Registry

- **lupo-database/lupopedia/actors/actor_id/registry.json:** Contains `actors[]` with `id`, `type`, `slug`, `dir`. IDs and slugs are authoritative for resolution; docs and tooling must not maintain separate inline ID lists.
- **FLARE_DOCTRINE Section 18:** States that actor IDs are defined in the project’s actor registry; tooling MUST read the registry; section gives examples only.
- **LILITH_FLAME_FAUCET_REPORT / Section 19:** Canonical Lilith ID is 2; 2038 is legacy. Faucet 7 “Lilith Flame Expert” aligns with a possible `agent_name_identity` value.

### 2.2 Identity string

- **agent_name_identity:** Not stored in the registry file today; it is an optional **FLARE header** field. It can match the agent’s system-prompt identity or faucet name (e.g. “Lilith Flame Expert”, “Cursor IDE Agent”) so that logs and UI show a consistent human-readable name without hardcoding IDs in prose.

---

## 3. Doctrine Updates (Section 24)

### 3.1 Before

Section 18 mandated registry as canonical for IDs and gave examples. There was no standard field for “how the agent identifies” in headers.

### 3.2 After (excerpt)

**Section 24. Agent Identity Fields (v4.0.57+)**

- Registry is the canonical source for actor/agent IDs; Section 18 referenced.
- **Optional agent_name_identity:** String in `flare.headers` (e.g. “Cursor IDE Agent”, “Lilith Flame Expert”). Format: single string; use for human-readable identification and audit trails; does not replace `actor_id` or registry lookup.
- **Rationale:** Prompt alignment, readability, avoid hardcoding. Ties to delegation_chain and faucets (e.g. faucet name can match agent_name_identity).
- **Example table:** Actor ID (example) | agent_name_identity (example) | Registry path (example). All “for illustration only; resolve IDs from registry.”

Section 18 now includes a one-line pointer: “For an optional human-readable agent identity string in headers, see Section 24 (agent_name_identity).”

---

## 4. Documentation Updates

- **docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md:** New short doc — registry as source of truth, optional agent_name_identity, examples table (e.g. only), ref to FLARE_DOCTRINE Section 24.
- **AGENTS.md:** Actor Model section updated to state that actor IDs and agent identity are defined in the registry (e.g. lupo-database/lupopedia/actors/ or actors/registry.json) and that flare.headers may include agent_name_identity for human-readable identification (see FLARE doctrine Section 24).

---

## 5. Examples Table

| Actor ID (example) | agent_name_identity (example) | Registry path (example) |
|--------------------|-------------------------------|-------------------------|
| 10000 | Captain Wolfie | registry / actors/10000 |
| 1003 | Cursor IDE Agent | registry / actors/1003 |
| 2 | Lilith Flame Expert | registry / actors/2; faucet 7 |
| 19 | ANUBIS | registry / actors/19 |

---

## 6. Rationale

- **Registry canonical:** Single source of truth prevents ID drift (e.g. 2038 vs 2 for Lilith, or Cursor vs Antigravity ID mix-ups in older docs). Audit trails and delegation_chain stay correct when tooling reads the registry.
- **agent_name_identity:** Gives a stable, human-readable label for logs and UI; aligns with “You are ___” in system prompts and faucet names; avoids hardcoding agent names in prose while keeping headers self-describing.

---

## 7. Validation

- **flare_validate.py:** Run on this report, AGENT_IDENTITY_REGISTRY_4.0.57.md, and FLARE_DOCTRINE.md; exit code **0**. Canonical order and structure preserved.
- **Registry alignment:** No new hardcoded ID lists in doctrine; Section 18 and 24 use “examples” and “resolve from registry.” Grep for inline actor_id in doctrine: only example tables and “e.g.” references remain; no canonical lists—registry is the source of truth.

---

## 8. Delegation

- **Lilith (actor 2):** Requested for meta-review of this report and of doctrine Section 24 (flame-aligned headers, canonical order, Safety Rule).

---

## 9. Timestamp and Actor

- **Report generated:** 2026-03-06  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **lupo_agent:** cursor  

---

*End of report.*
