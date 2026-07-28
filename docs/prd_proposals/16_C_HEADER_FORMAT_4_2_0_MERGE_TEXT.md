---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MERGE_TEXT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MERGE_TEXT.md
  status: active
  when_updated: "20260728165913"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/07/16-c-header-format-4-2-0-merge-text.toon
  atoms_toon: null
  transcript_jsonl: 0/development/16-c-header-format-4-2-0-merge-text
  artifact_type: prd
  artifact_kind: requirements
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 16_C
  title: "PRD 16_C merge text -- authorize header 4.2.0 Option A"
  summary: "Normative merge blocks for PRD 16_C plus mandatory SYSTEM STATUS UNSTABLE for product 4.2.0. No corpus header rewrite."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: headers
  faucet_actor_id: 102
---
# PRD 16_C Merge Text -- Header Format 4.2.0 (Option A)

**Apply to:** `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md`  
**Authority:** Captain ALII -- Option A Dense Expansion approved  
**Product version:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = **4.2.0** (TRANSITIONAL / UNSTABLE)  
**Effect:** Unfreeze 4.1.9 as sole contract; authorize 4.2.0 as current header format for new work; preserve 4.1.9 as legacy-accept during migration.

```text
######################################################################
# SYSTEM STATUS: UNSTABLE -- MULTIPLE KNOWN BREAKAGES
# Product GLOBAL_CURRENT_LUPOPEDIA_VERSION = 4.2.0 is TRANSITIONAL.
# Canonical narrative: docs/versions/4.2.0/SYSTEM_STATUS_UNSTABLE.md
######################################################################
# 1. Authentication / Permission Errors
#    - ACL propagation failures
#    - PermissionDenied on deleted channels
#    - PermissionDenied on theoretical channels
#    - installer token misalignment
#    - federation node lockouts
# 2. Missing Crafty Syntax Features
#    - legacy Crafty Syntax 3.x features not yet ported
#    - missing interpreter behaviors
#    - missing routing primitives
#    - missing semantic switches
#    - missing memory cluster bridges
# 3. Missing Filesystem Structures
#    - active lupo-channels/ tree missing
#    - active channel_index.md missing
#    - thread manifests missing
#    - dimensional memory map missing
#    - Actors Collection chapters missing
#    - CL-302 missing
#    - hermes_toon sidecars missing
# 4. Traffic Defense Division
#    - PRD 25_B proposal exists
#    - department not seeded
#    - ACL not initialized
#    - division not stable
# 5. 98_C Ops Logs
#    - spine exists
#    - full volume catalog missing
#    - JSON threads incomplete
######################################################################
# This merge authorizes HEADER CONTRACT 4.2.0 and documents product 4.2.0.
# It does NOT authorize corpus header rewriting or claim production stability.
######################################################################
```

Paste or apply the blocks below. Full field semantics: `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md`.

---

## MERGE BLOCK A -- Replace "Header freeze rule (4.1.8)" section

```markdown
## Header freeze rule (updated 4.2.0)

**Note:** Agent replies that modify PRD files MUST return the updated `lupopedia.headers` block so header compliance can be audited without opening the full file. See PRD 50 section 1.2.3.

**Normative**

- The Lupopedia **header contract** for **new authored envelopes** is **`header_format_version: "4.2.0"`** (Option A -- Dense Expansion; Captain ALII approval 2026-07-28).
- **`header_format_version: "4.1.9"`** remains **valid legacy** for existing corpus until batched migration completes. Validators MUST accept both during the migration window.
- The prior freeze at 4.1.9 is **lifted** solely to authorize the 28-field dense grid defined in section 4.2 (v4.2.0).
- During the 4.2.0 contract: **no further dense-field additions** without a new Captain-approved PRD 16_C revision. Hawaiian constitutional fields remain **out of** the dense grid (Hermes / sidecar / body only).
- Agents **MUST** implement new files against 4.2.0 when tooling supports it; until validator dual-accept ships, agents MAY emit 4.1.9 and MUST NOT mass-rewrite the corpus.

**Rationale:** Identity scalars (`actor_id`, `auth_user_id`, `department_id`, `department_key`, `division_key`, `faucet_actor_id`) close Actor Handbook / no-guessing gaps without densifying Hawaiian constitutional vocabulary.

**Forward note:** Further redesign (envelope hybrids, hermes_toon dense pointer, etc.) requires a new proposal and Captain approval. Product semver remains independent of header_format_version.
```

---

## MERGE BLOCK B -- Replace section 4.1 and 4.2 headers / lists

```markdown
### 4.1 Canonical field count and order

Header field count and canonical order are authoritative from:

- `memory/atoms/lupopedia_global_constants.atom.toon` (update required for 4.2.0)
- `constants.header_fields.count`
- `constants.header_fields.order`
- `scripts/lib/header_spec_v3_1.py` (dual tuples after validator update)

| Contract | Count | Dense envelope lines (Markdown) |
|----------|-------|----------------------------------|
| 4.1.9 (legacy) | **22** | **25** |
| 4.2.0 (current) | **28** | **31** |

### 4.2 Canonical field order (v4.2.0)

Fields 1-22 are identical to v4.1.9. Fields 23-28 are mandatory identity scalars (Option A).

1. `header_format_version`
2. `path_from_lupopedia_root`
3. `web_path`
4. `status`
5. `when_updated`
6. `trust_tier`
7. `questions_toon`
8. `memory_toon`
9. `atoms_toon`
10. `transcript_jsonl`
11. `artifact_type`
12. `artifact_kind`
13. `channel_key`
14. `federation_node_id`
15. `thread_key`
16. `lupopedia.schema`
17. `prd_cluster`
18. `title`
19. `summary`
20. `edges_toon`
21. `channel_index`
22. `source_timestamp`
23. `actor_id`
24. `auth_user_id`
25. `department_id`
26. `department_key`
27. `division_key`
28. `faucet_actor_id`

**Removed / forbidden in dense YAML (unchanged):** `content_id`, `content_parent_id`, `default_collection_id`, `content_slug`, legacy `pk_*` / `prd_id` aliases, and **all Hawaiian constitutional keys** (OHANA, KAPU, KAPAKAI, PUKA, PONO, KULEANA, ALII, KUMU, EH_BRAH_WHY). Those remain Hermes routing or sidecar structures (PRD 82_B).

### 4.2.0a Field 23 -- `actor_id`

- **Type:** BIGINT integer (required; never null)
- **Purpose:** WHO -- orchestration identity for the artifact
- **Registry-backed** for `trust_tier: canonical`
- **MUST NOT** be replaced by `faucet_actor_id`

### 4.2.0b Field 24 -- `auth_user_id`

- **Type:** BIGINT integer or `null`
- **Purpose:** WHICH human is accountable
- **Captain / Eric:** `10000` when Captain-authored
- **Root auth user:** `0` when applicable (PRD 01)
- **null** only for pure system artifacts with explicit KAPU

### 4.2.0c Field 25 -- `department_id`

- **Type:** BIGINT integer or `null`
- **Purpose:** DEPARTMENT id; `null` when unset or pending seed

### 4.2.0d Field 26 -- `department_key`

- **Type:** string (use `""` when none)
- **Purpose:** DEPARTMENT slug; MUST NOT invent departments

### 4.2.0e Field 27 -- `division_key`

- **Type:** string (use `""` when none)
- **Purpose:** DIVISION / thematic grouping; MAY be set when department is null

### 4.2.0f Field 28 -- `faucet_actor_id`

- **Type:** BIGINT integer or `null`
- **Purpose:** WHICH IDE/API faucet executed the write
- **Examples:** Cursor `102`, Antigravity `103`
- **External guests:** `null` + `channel_index: external`
```

Retain existing subsections 4.2.1 through 4.2.4 unchanged after the new 4.2.0a-f blocks (or renumber docs cross-refs carefully). Recommended insertion: **after** field 22 list / removed note, **before** current `### 4.2.1 Field 20`.

---

## MERGE BLOCK C -- Agent Role Storage note (section near 275)

Replace deferred language:

```markdown
- schema expansion for identity scalars: **delivered in 4.2.0** (fields 23-28)
- Persistent role storage beyond dense identity MAY still expand later; Hawaiian fields remain Hermes-only
```

---

## MERGE BLOCK D -- PRD 16_C document header + summary (optional first-wave)

When migrating this PRD file itself to 4.2.0 (allowed as merge proof; not a corpus sweep):

- Set `header_format_version: "4.2.0"`
- Append fields 23-28
- Update `when_updated` via `python bin/tick.py`
- Update `summary` to mention 4.2.0 Option A / 28 fields
- Set identity: `actor_id` / `auth_user_id` per stewardship policy; `faucet_actor_id: 102` if Cursor applied the merge

---

## MERGE BLOCK E -- Changelog row

Add to Changelog / Version History table:

```text
| 4.2.0 | 2026-07-28 | Captain ALII Option A: dense header 22 -> 28 (actor_id, auth_user_id, department_id, department_key, division_key, faucet_actor_id). Unfreeze 4.1.9. Hawaiian fields remain Hermes/sidecar. See docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md. |
```

---

## Explicit non-goals of this merge

- No mass rewrite of existing 4.1.9 headers
- No Hawaiian keys in dense grid
- No product version major/minor bump
- No ALTER TABLE / Lupopedia-to-Lupopedia migration
- No Dimensional Memory PRD allocation
