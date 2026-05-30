---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/retention_policy.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/retention_policy.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: null
  federation_node_id: 0
  thread_key: retention-policy
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: Retention Policy — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/RETENTION_POLICY.md

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
