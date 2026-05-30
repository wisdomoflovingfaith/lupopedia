---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/15_B_ACTOR_IDENTITY_AND_NAMING_REGISTRY.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/15_B_ACTOR_IDENTITY_AND_NAMING_REGISTRY.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/15-b-actor-identity-naming-registry.toon
  atoms_toon: null
  transcript_jsonl: 0/prd/15-b-actor-identity-naming-registry
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 15_B_00_A-i_15_B-i
  title: 'PRD 15_Z: Actor Identity & Naming Registry'
  summary: Defines actor naming rules, registry structure, typo protection, unknown actor handling, and canonical resolution tooling.
---
# PRD 15_Z: Actor Identity & Naming Registry

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Actor Naming Rules

- Every actor (human, agent, persona, faucet) MUST have a unique, deterministic name and numeric actor_id.
- Names MUST be ASCII, lowercase, and use underscores for spaces (no hyphens, spaces, or Unicode).
- Names MUST NOT be reused for different actor_ids.
- Canonical names are defined in lupo-database/lupopedia/actors/registry.json.
- Aliases MAY be listed but MUST resolve to a single canonical actor_id.

## Registry Structure

- The actor registry is a single JSON file: lupo-database/lupopedia/actors/registry.json.
- Each entry MUST include: actor_id, canonical_name, aliases (optional), persona_type, status, and when_updated.
- The registry is the only source of truth for actor identity and naming.
- All actor lookups, routing, and audit trails MUST resolve through this registry.

## Unknown Actor Handling

- If an actor_id or name is not found in the registry, the system MUST treat it as unknown and log a validation warning.
- Unknown actors MUST NOT be assigned permissions, routing, or write access until explicitly registered.
- All unknown actor events MUST be logged for review.

## Typo Protection

- Actor lookups MUST use exact, case-sensitive matching.
- If a lookup fails, the system MAY suggest the closest canonical name (Levenshtein distance <=2), but MUST NOT auto-correct or assume intent.
- All typo suggestions MUST be logged for audit.

## Tooling (resolve_actor)

- The canonical tool for resolving actor identity is resolve_actor(name_or_id).
- This tool MUST:
  - Accept either a name or actor_id
  - Return the canonical actor_id, name, and registry entry
  - Fail with a validation error if the input is ambiguous or unknown
- All agent, orchestrator, and validator code MUST use resolve_actor for identity resolution.
- No direct lookups or hardcoded actor_ids are allowed outside this tool.

---

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260424000001"
