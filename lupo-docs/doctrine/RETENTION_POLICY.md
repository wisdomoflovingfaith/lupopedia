---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260408021859"
  file_path_from_root: "lupo-docs/doctrine/RETENTION_POLICY.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/RETENTION_POLICY.md"
  last_modified_utc: "20260408021859"
  federation_node_id: 0
  channel_id: 42
  thread_id: "retention-policy"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: doctrine
  artifact_kind: constitutional
  purpose: "Retention policies for staging rows and soft-deleted data aligned with the Chronological Trust Ladder"
  tags:
    - doctrine
    - retention
    - staging
    - gc
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md"
      type: references
      weight: 1.0
      reason: "Staging retention supports trust ladder hygiene"
    - to: "lupo-docs/prd/19_garbage_collection_system.md"
      type: references
      weight: 1.0
      reason: "GC system implements purge policy"
lupopedia.footer:
  last_verified: "20260408021859"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# file: Retention Policy — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/RETENTION_POLICY.md

# Retention Policy

## Staging-tier rows (trust ladder)

**Normative:** Soft-deleted **staging-tier** rows (18-digit PK with embedded year **2000–2099** where ladder semantics apply) **MUST** be physically purged **no earlier than 90 days** after **`deleted_ymdhis`** (and **no later than** policy allows without constitutional amendment — default window **90 days** for development alignment).

| Table | Policy | Enforcement |
|-------|--------|-------------|
| `lupo_memory_nodes` (staging-tier rows) | Purge soft-deleted staging **≥ 90 days** after delete timestamp | **PRD 19** Garbage Collection |
| `lupo_memory_edges` (staging-tier rows) | Same | **PRD 19** |

**Classification:** Staging tier is determined by embedded year band and table rules in **CHRONOLOGICAL_TRUST_LADDER.md**; implementors **MUST NOT** purge living canonical (**1000–1999**) or immutable seed rows under this policy.

## Forbidden

- Per-agent or per-operation TTL overrides that bypass this doctrine.
- **Zero-day** immediate purge of soft-deleted staging without explicit constitutional amendment and PRD update.

## Policy changes

Any change to retention periods **MUST** include:

- PRD update (**PRD 19** and affected domain PRDs).
- WOLFIE approval for product policy.
- LILITH audit for constitutional alignment.

---

**Status:** ACTIVE
