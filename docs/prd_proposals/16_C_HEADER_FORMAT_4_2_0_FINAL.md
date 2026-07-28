---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md
  status: active
  when_updated: "20260728165913"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/07/16-c-header-format-4-2-0-final.toon
  atoms_toon: null
  transcript_jsonl: 0/development/16-c-header-format-4-2-0-final
  artifact_type: prd
  artifact_kind: requirements
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 16_C_05_A_15_A_25_A_41_A_82_B_98_B_98_C
  title: "FINAL: Lupopedia Headers 4.2.0 (Option A Dense Expansion)"
  summary: "Captain ALII-approved Option A: 28-scalar dense header. Product version 4.2.0 UNSTABLE. Hawaiian fields Hermes/sidecar. Spec only -- no corpus migration."
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
# FINAL SPEC: Lupopedia Headers v4.2.0 (Option A -- Dense Expansion)

**Status:** FINALIZED under Captain ALII approval (2026-07-28)  
**Envelope decision:** Option A -- Dense Expansion  
**Authority:** Captain ALII (Eric, `auth_user_id` / `actor_id` 10000)  
**Implementer surface:** Cursor faucet (`faucet_actor_id` 102)  
**Product version companion:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = **4.2.0** (TRANSITIONAL / UNSTABLE)  
**Supersedes:** `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_PROPOSAL.md` (proposal; Option C recommendation withdrawn)  
**Corpus rewrite:** NOT authorized by this file alone -- see migration plan. New authored files MAY use 4.2.0 only after PRD 16_C merge lands.

---

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
# Header Option A is DESIGN-COMPLETE; corpus migration is NOT started.
######################################################################
```

---

## 0. KAPU

1. Product semver (`GLOBAL_CURRENT_LUPOPEDIA_VERSION`) is now **aligned at 4.2.0** with `header_format_version` by Captain ALII directive, but **release stability is UNSTABLE** -- do not conflate version equality with production readiness.
2. Hawaiian constitutional fields (OHANA, KAPU, KAPAKAI, PUKA, PONO, KULEANA, ALII, KUMU, EH_BRAH_WHY) MUST NOT enter the dense YAML discovery grid.
3. ASCII authority token is **ALII** (no Unicode okina forms in normative text).
4. Global No-Guessing Rule remains: missing identity scalars MUST NOT be inferred from title/path alone.
5. Do not merge identities: `actor_id`, `auth_user_id`, and `faucet_actor_id` are distinct layers (PRD 05 / Identity Layers).
6. Do **not** mass-rewrite existing headers until migration Phase 3+ is explicitly authorized.
---

## 1. Full field list (28 scalars) -- canonical order

Fields **1-22** are identical to PRD 16_C v4.1.9 (preserved discovery grid).  
Fields **23-28** are the Option A identity expansion.

| # | Field | Type | Null / empty | Dimensional role |
|---|-------|------|--------------|------------------|
| 1 | `header_format_version` | string | never | Contract version; MUST be `"4.2.0"` for new 4.2.0 envelopes |
| 2 | `path_from_lupopedia_root` | string | never | WHERE (repo-relative path) |
| 3 | `web_path` | string | never | WHERE (public URL under install) |
| 4 | `status` | string | never | Lifecycle (`active`, etc.) |
| 5 | `when_updated` | string (14-digit packed UTC) | never | WHEN last normative edit |
| 6 | `trust_tier` | string | never | `canonical` \| `development` (+ legacy WARN) |
| 7 | `questions_toon` | null or path | null ok | Q&A sidecar |
| 8 | `memory_toon` | null or path | null ok | Memory graph |
| 9 | `atoms_toon` | null or path | null ok | Atoms / constants |
| 10 | `transcript_jsonl` | null or slug | null ok | WHY lineage / dialog slug |
| 11 | `artifact_type` | string enum | never | WHAT class |
| 12 | `artifact_kind` | string | never | WHAT subtype |
| 13 | `channel_key` | string | never | CHANNEL |
| 14 | `federation_node_id` | int | never | WHERE federation |
| 15 | `thread_key` | string or null | null/`""` ok | THREAD |
| 16 | `lupopedia.schema` | string | never | MUST equal `artifact_type` |
| 17 | `prd_cluster` | string or null | null ok | WHY / PRD lineage |
| 18 | `title` | string | never | WHAT title |
| 19 | `summary` | string | never | WHY one-liner |
| 20 | `edges_toon` | null or path | null ok (required if external) | Graph / provenance |
| 21 | `channel_index` | string | never | Origin platform |
| 22 | `source_timestamp` | null or ISO8601 | null ok (required if external) | WHEN originated |
| 23 | `actor_id` | BIGINT int | never null | WHO (orchestration identity) |
| 24 | `auth_user_id` | BIGINT int or null | null ok (system-only) | WHICH human accountable |
| 25 | `department_id` | BIGINT int or null | null ok | DEPARTMENT id |
| 26 | `department_key` | string | `""` when none | DEPARTMENT slug |
| 27 | `division_key` | string | `""` when none | DIVISION |
| 28 | `faucet_actor_id` | BIGINT int or null | null ok | WHICH faucet executed |

**Forbidden in dense header (unchanged + reinforced):**  
`content_id`, `content_parent_id`, `default_collection_id`, `content_slug`, `pk_*`, legacy `prd_id`, `module`, `memory_key`, `dialog_transcript`, `file_path_from_root`, `last_modified_utc`, and all Hawaiian constitutional keys listed in section 0.

---

## 2. Envelope line budget (discovery protocol)

### Markdown (`.md`)

```
line 1: ---
line 2: lupopedia.headers:
lines 3-30: exactly 28 scalar key lines (two-space indent)
line 31: ---
line 32+: body
```

**Dense envelope = 31 lines.** Agents MAY discover metadata with `head -31` (Markdown).

### Python / PHP comment grid

```
# -----
# lupopedia.headers:
# <28 key lines>
# -----
```

**Dense comment grid = 31 lines** (open fence + headers label + 28 keys + close fence).

### Invariant

- No blank lines inside the dense block.
- No extra keys inside the dense block.
- No Hawaiian constitutional keys inside the dense block.
- Order MUST match section 1 exactly.

---

## 3. Field types and invariants (identity fields 23-28)

### 3.1 `actor_id` (REQUIRED)

- Type: integer BIGINT (YAML int).
- MUST resolve in actor registry when `trust_tier: canonical`.
- MUST NOT equal `faucet_actor_id` as a substitute for persona identity (faucet is surface; actor is orchestration).
- Dual-captaincy: human Captain remains `10000`; WOLFIE remains `1`; neither replaces the other.

### 3.2 `auth_user_id`

- Type: integer BIGINT or YAML `null`.
- `0` = root auth user (PRD 01) when applicable.
- Captain Log / Captain-authored normative artifacts SHOULD set `10000`.
- `null` only when no human accountability applies (pure system seed with explicit KAPU).

### 3.3 `department_id` / `department_key`

- `department_id`: BIGINT or `null` (pending / unset).
- `department_key`: string; use `""` when unset (never invent a department).
- If both set, key MUST match registry slug for that id when seeded.

### 3.4 `division_key`

- Type: string; thematic grouping (e.g. `headers`, `logging`, `livehelp`).
- MAY be non-empty when `department_id` is null (division without seeded department is allowed).
- Use `""` when unset.

### 3.5 `faucet_actor_id`

- Type: BIGINT or `null`.
- Examples: Cursor `102`, Antigravity `103`, VS Code IDE map per registry.
- MUST NOT be used as `actor_id`.
- External AI guests: leave `null`; set `channel_index: external` (section 4.2.4 PRD 16_C).

---

## 4. WHO / WHAT / WHERE / WHEN / WHY mapping

| Question | Dense header answer |
|----------|---------------------|
| WHO wrote / owns? | `actor_id` (+ `auth_user_id`, `faucet_actor_id`) |
| WHAT is it? | `artifact_type`, `artifact_kind`, `title`, `summary`, `lupopedia.schema` |
| WHERE does it live? | `path_from_lupopedia_root`, `web_path`, `federation_node_id`, `channel_key`, `thread_key`, `channel_index` |
| WHEN updated? | `when_updated` (packed UTC `YYYYMMDDHHIISS`) |
| WHEN originated (external)? | `source_timestamp` |
| WHY exists? | `summary` + `prd_cluster` + `transcript_jsonl` (+ Hermes EH_BRAH_WHY outside dense grid) |
| DEPARTMENT / DIVISION | `department_id`, `department_key`, `division_key` |
| Constitutional Hawaiian | Hermes routing / sidecar / body -- NOT dense scalars |

---

## 5. Actor Handbook alignment

Option A closes the handbook gap that forced agents to guess WHO / DEPARTMENT / faucet:

| Handbook need | 4.1.9 | 4.2.0 Option A |
|---------------|-------|----------------|
| WHO | inferred | `actor_id` |
| Responsible human | inferred | `auth_user_id` |
| DEPARTMENT | missing | `department_id` + `department_key` |
| DIVISION | missing | `division_key` |
| Faucet surface | missing | `faucet_actor_id` |
| OHANA..EH_BRAH_WHY | Hermes | Hermes (unchanged; not densified) |

Handbook onboarding vectors and Dimensional Memory proposals remain **handbook / proposal** unless separately PRD-allocated. This header change does **not** approve Dimensional Memory as a PRD.

---

## 6. PRD lineage

| PRD | Role |
|-----|------|
| **16_C** | Normative header contract (this version lives here after merge) |
| **16_B** | Header atoms / constants companion |
| **05** | Actor / auth / department / faucet chain |
| **15** | Actors model |
| **25** | Permissions / department ACL context |
| **41** | External AI boundary (`channel_index: external`) |
| **82_B** | Hermes / Hawaiian constitutional routing |
| **98_B** | Entertainment Captain Log (zero doctrine authority) |
| **98_C** | Dual operational logs (structured Captain + WOLFIE JSON) |
| **00** | Constitutional root; no-guessing / survivability |

---

## 7. Validation rules (normative for validators after code update)

See also: `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_VALIDATOR_NOTES.md`

| Code / rule | Behavior |
|-------------|----------|
| `HDR_VERSION_420` | Accept `header_format_version: "4.2.0"` |
| `HDR_VERSION_419_LEGACY` | Accept `"4.1.9"` during migration window (WARN on new files after cutover flag) |
| `HDR_FIELD_COUNT_28` | For 4.2.0: exactly 28 keys in order |
| `HDR_FIELD_COUNT_22` | For 4.1.9: exactly 22 keys in order |
| `HDR_ORDER` | Keys must match ordered tuple for declared version |
| `HDR_ACTOR_ID_REQUIRED` | 4.2.0: `actor_id` present and integer |
| `HDR_FAUCET_NOT_ACTOR` | WARN/ERROR if policy equates faucet to persona without both fields |
| `HDR_HAWAIIAN_IN_DENSE` | ERROR if OHANA/KAPU/... keys appear under dense `lupopedia.headers` |
| `HDR_ENVELOPE_31` | Markdown dense block must be 31 lines for 4.2.0 |
| Existing 4.1.9 rules | Retain for edges/channel_index/source_timestamp/schema equality |

**Transition:** Until validator code ships, doctrine is authoritative; tooling may still enforce 4.1.9 only -- do not mass-rewrite.

---

## 8. PONO checklist (before declaring an artifact 4.2.0-complete)

- [ ] `header_format_version` is exactly `"4.2.0"`
- [ ] All 28 keys present in canonical order; no extras in dense block
- [ ] Envelope is 31 lines (Markdown) / 31-line comment grid (py/php)
- [ ] `when_updated` from `python bin/tick.py` (real UTC)
- [ ] `actor_id` set; not guessed from filename
- [ ] `auth_user_id` set or explicitly null with reason in body/Hermes
- [ ] `department_id` / `department_key` / `division_key` honest (empty/null if unset)
- [ ] `faucet_actor_id` matches actual IDE/API surface or null
- [ ] `lupopedia.schema` == `artifact_type`
- [ ] No Hawaiian keys in dense header
- [ ] Hermes/sidecar/body covers KAPU/PONO/EH_BRAH_WHY as required for artifact class
- [ ] External artifacts: `channel_index` + `edges_toon` + `source_timestamp` rules satisfied
- [ ] ASCII-only normative text
- [ ] Dual-captaincy respected (no identity merge)

---

## 9. Example header (Captain Log / normative docs class)

```yaml
---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/logs/2026/07/28/example_captain_ops.md
  web_path: https://www.lupopedia.com/lupopedia/docs/logs/2026/07/28/example_captain_ops.md
  status: active
  when_updated: "20260728165511"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/captain-ops-example
  artifact_type: status
  artifact_kind: session-report
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: status
  prd_cluster: 98_C_16_C_05_A
  title: "Example Captain ops header (4.2.0)"
  summary: "Illustrative 28-field dense header for Option A; not a live ops log row."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: logging
  faucet_actor_id: 102
---
```

Hermes constitutional content (if required) appears **below** the dense block or in a sidecar -- never as fields 29+.

---

## 10. Migration notes (summary)

Full plan: `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MIGRATION_PLAN.md`

1. Merge PRD 16_C normative text (unfreeze 4.1.9; authorize 4.2.0).
2. Update atoms / `header_spec_v3_1.py` to dual-accept 4.1.9 and 4.2.0.
3. New files: prefer 4.2.0.
4. Existing corpus: remain 4.1.9 until batched migration (NOT started by this approval alone).
5. No ALTER TABLE / product major bump implied.

---

## 11. Companion artifacts

| File | Purpose |
|------|---------|
| `16_C_HEADER_FORMAT_4_2_0_MERGE_TEXT.md` | Exact PRD 16_C patch language |
| `16_C_HEADER_FORMAT_4_2_0_VALIDATOR_NOTES.md` | Tooling change list (code not required until scheduled) |
| `16_C_HEADER_FORMAT_4_2_0_MIGRATION_PLAN.md` | Dependency-ordered migration phases |
| `16_C_HEADER_FORMAT_4_2_0_PROPOSAL.md` | Historical proposal (superseded) |

---

This output complies with Lupopedia Constitutional Root Rules.
