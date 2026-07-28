---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_PROPOSAL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_PROPOSAL.md
  status: active
  when_updated: "20260728165511"
  trust_tier: development
  questions_toon: null
  memory_toon: memory/development/canonical/1026/07/16-c-header-format-4-2-0-proposal.toon
  atoms_toon: null
  transcript_jsonl: 0/development/16-c-header-format-4-2-0-proposal
  artifact_type: prd
  artifact_kind: requirements
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 16_C_05_A_15_A_25_A_41_A_82_B_98_B_98_C
  title: "SUPERSEDED: Proposal Lupopedia Headers 4.1.9 to 4.2.0"
  summary: "SUPERSEDED by docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md after Captain ALII Option A approval. Historical proposal only; do not implement from this file."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
# SUPERSEDED: Proposal Lupopedia Headers v4.1.9 -> v4.2.0

**Status:** SUPERSEDED (2026-07-28)  
**Replacement:** `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md`  
**Captain decision:** Option A -- Dense Expansion (not Option C Hybrid)  
**Normative PRD:** `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md` (merged)

Do not use this proposal for implementation. Retained for lineage only.

---

## Historical note

This file recommended Hybrid Option C before Captain ALII authorized Option A. The FINAL spec preserves the 22-field grid and appends six identity scalars (28 total). Hawaiian constitutional fields stay in Hermes/sidecar.

Original proposal body follows below for archive reading.

---

# Proposal: Lupopedia Headers v4.1.9 -> v4.2.0

**Status:** PROPOSAL ONLY (zero implementation authority until Captain ALII approval + PRD 16_C normative merge)  
**Date:** 2026-07-28  
**Author:** Cursor IDE (faucet actor_id 102)  
**Inputs:** Actor Handbook, PRD 16_C (freeze + 22-field order), PRD 82_B (Hermes / Hawaiian fields), PRD 05/15/25/41, PRD 98_B/98_C, how_wolves / CL-305 context  

---

## 0. KAPU (read first)

1. **Current normative contract remains `header_format_version: "4.1.9"`** (PRD 16_C Header freeze rule). This file does **not** authorize agents to emit 4.2.0 headers yet.
2. **Header format 4.2.0 is not the same thing as product semver `GLOBAL_CURRENT_LUPOPEDIA_VERSION`.** Product major/minor bumps remain gated by auto-installer doctrine. PRD 16 already anticipates header family `4.2.0` as an upgrade gate (section 15.2).
3. **25-line / 22-field agent discovery protocol** (PRD 16) is constitutional. Expanding fields requires an explicit envelope redesign decision (see section 4).
4. ASCII token for human authority is **ALII** (not Unicode okina forms).
5. Hawaiian constitutional fields (OHANA, KAPU, KAPAKAI, PUKA, PONO, KULEANA, ALII, KUMU, EH_BRAH_WHY) belong primarily to **Hermes routing / artifact body / sidecar**, not necessarily all as dense YAML scalars (see section 1.3).

---

## 1. Required Header Fields (Mandatory for 4.2.0)

### 1.1 Keep all 4.1.9 mandatory keys (baseline)

These already exist and MUST remain in order for discovery continuity:

| # | Field | Dimensional role (handbook) |
|---|-------|-------------------------------|
| 1 | `header_format_version` | Contract version (WHAT/WHEN of the envelope itself) |
| 2 | `path_from_lupopedia_root` | WHERE (filesystem identity) |
| 3 | `web_path` | WHERE (public URL) |
| 4 | `status` | Lifecycle state |
| 5 | `when_updated` | WHEN (packed UTC `YYYYMMDDHHIISS`) |
| 6 | `trust_tier` | Authority weight (`canonical` \| `development`) |
| 7 | `questions_toon` | WHY/HOW pointer (Q&A sidecar or null) |
| 8 | `memory_toon` | WHERE memory graph |
| 9 | `atoms_toon` | WHERE atoms / constants |
| 10 | `transcript_jsonl` | WHY lineage / dialog slug |
| 11 | `artifact_type` | WHAT class |
| 12 | `artifact_kind` | WHAT subtype |
| 13 | `channel_key` | CHANNEL |
| 14 | `federation_node_id` | WHERE (federation) |
| 15 | `thread_key` | THREAD |
| 16 | `lupopedia.schema` | WHAT (must equal `artifact_type` policy) |
| 17 | `prd_cluster` | WHY (PRD lineage) |
| 18 | `title` | WHAT (human title) |
| 19 | `summary` | WHY (one-line purpose) |
| 20 | `edges_toon` | WHERE graph / provenance |
| 21 | `channel_index` | WHERE origin platform |
| 22 | `source_timestamp` | WHEN (immutable origin; required if external) |

**4.1.9 gap vs Actor Handbook:** WHO (owning `actor_id`), responsible `auth_user_id`, DEPARTMENT, DIVISION, faucet `actor_id`, and Hawaiian markers are **not** first-class scalars. Agents infer them from body, path, or session -- which violates the Global No-Guessing Rule when they guess.

### 1.2 Proposed new MANDATORY scalars for 4.2.0 (identity / org)

These MUST exist so actors can answer handbook questions without guessing:

| Field | Type | Answers | Notes |
|-------|------|---------|-------|
| `actor_id` | BIGINT int | WHO owns / speaks as | Registry-backed; never auto-increment; never merge identities |
| `auth_user_id` | BIGINT int or null | WHICH human is responsible | null only for pure system artifacts with no human accountability; Captain Log SHOULD set 10000 |
| `department_id` | BIGINT int or null | DEPARTMENT | null when none / pending (e.g. Traffic Defense pending seed) |
| `department_key` | string or `""` | DEPARTMENT slug | empty string when none |
| `division_key` | string or `""` | DIVISION | thematic grouping; may exist without seeded department |
| `faucet_actor_id` | BIGINT int or null | WHICH surface executed | e.g. Cursor 102; null if not IDE/API faucet work |

**Normative chain (PRD 05):** `auth_user` + `agent` + `department` + `faucet` + `session` -> effective `actor_id`.  
Header MUST record the resolved `actor_id` and, when known, `auth_user_id` + `faucet_actor_id`.

### 1.3 Constitutional fields -- required coverage, not all as YAML scalars

Actor Handbook + Hermes require actors to reason with:

OHANA, KAPU, KAPAKAI, PUKA, PONO, KULEANA, ALII, KUMU, EH_BRAH_WHY  
(+ PILAU as ethical inverse of PONO; prefer PUKA for structural gaps)

**4.2.0 proposal (recommended binding):**

| Requirement | Mechanism |
|-------------|-----------|
| Fields MUST be applicable | Artifact MUST declare a `hermes_toon` sidecar path OR embed a `lupopedia.hermes` block below the dense header (not inside the 22/28 discovery grid) |
| Dense header MUST stay machine-scannable | Do **not** dump all nine Hawaiian strings into every YAML key line unless envelope is explicitly redesigned |
| EH_BRAH_WHY | Hermes field and/or WHY file (98_A); never replace with `questions_toon` alone |
| ALII | Human authority Eric (`auth_user_id` / actor 10000); not interchangeable with WOLFIE actor_id 1 |

**Mandatory pointer field for 4.2.0:**

| Field | Type | Purpose |
|-------|------|---------|
| `hermes_toon` | null or path ending `.hermes.toon` / `.toon` | Constitutional / routing sidecar (OHANA..EH_BRAH_WHY, kapakai, pono, etc.) |

When `hermes_toon` is null, artifact MUST still satisfy Hermes requirements for its class via body block OR be classified `trust_tier: development` with explicit KAPU that Hermes is incomplete.

### 1.4 Mapping: handbook questions -> header

| Handbook question | 4.2.0 header answer |
|-------------------|---------------------|
| WHO am I / who wrote this? | `actor_id` (+ `auth_user_id`, `faucet_actor_id`) |
| WHAT is this? | `artifact_type`, `artifact_kind`, `title`, `summary`, `lupopedia.schema` |
| WHERE does it live? | `path_from_lupopedia_root`, `web_path`, `federation_node_id`, `channel_index` |
| WHEN updated? | `when_updated` (packed UTC) |
| WHEN originated (external)? | `source_timestamp` |
| WHY does it exist? | `summary` + `prd_cluster` + `transcript_jsonl` + Hermes `eh_brah_why` |
| WHICH actor owns it? | `actor_id` |
| WHICH auth_user is responsible? | `auth_user_id` |
| DEPARTMENT / DIVISION | `department_id`, `department_key`, `division_key` |
| CHANNEL / THREAD | `channel_key`, `thread_key` |
| Memory paths | `memory_toon`, `atoms_toon`, `questions_toon`, `edges_toon`, `hermes_toon` |
| Constitutional fields | `hermes_toon` / Hermes body -- not guessed |

---

## 2. Recommended Header Fields (Optional but strongly advised)

| Field | Purpose |
|-------|---------|
| `agent_slug` | Blueprint pack under `agents/<slug>/` (not identity; PRD 07) |
| `paired_actor_id` | Explicit dual-captaincy / hybrid pairing hint (still no merge) |
| `log_class` | For 98_C: `captain_ops` \| `wolfie_ops` \| `daily_bundle` \| `none` |
| `parent_path` | Soft reference to parent artifact path (no FK) |
| `do_toon` | Pointer to DO capabilities JSON (handbook) |
| `directives_toon` | Pointer to DIRECTIVES JSON |
| `focus_toon` | Pointer to FOCUS JSON |
| `kapu_summary` | One-line hard boundary for humans (not a substitute for Hermes KAPU) |
| `pono_target` | One-line intended outcome (mirrors Hermes pono) |

Recommended fields SHOULD live in the Hermes sidecar when possible to protect dense-header scan length.

---

## 3. Deprecated Fields (Remove in 4.2.0)

### Already removed (keep removed -- do not reintroduce)

From 4.1.9 forbidden set: `content_id`, `content_parent_id`, `default_collection_id`, `content_slug`, all `pk_*` / legacy `prd_id` aliases, `module`, `memory_key`, `dialog_transcript`, `file_path_from_root` (alias of path), `last_modified_utc` as a header scalar.

### Candidates to deprecate or demote at 4.2.0 (Captain decision required)

| Field / pattern | Reason |
|-----------------|--------|
| Inferring WHO from `title` / prose alone | Violates no-guessing; replaced by `actor_id` |
| Using `channel_key` as both DB channel and filesystem folder without `federation_node_id` | Ambiguous WHERE; 4.2.0 requires both |
| Treating `questions_toon` as EH_BRAH_WHY | Wrong layer; Hermes / WHY files own deeper why |
| Legacy `artifact_type` values outside closed enum | Already deprecated in 4.1.x; ERROR in 4.2.0 |
| Dual clocks with local offsets in `source_timestamp` without Z/offset clarity | Prefer Z; packed UTC remains canonical for `when_updated` |

**Do not deprecate** `edges_toon`, `channel_index`, or `source_timestamp` -- they are provenance for external/Patreon mirrors.

---

## 4. New Constitutional Requirements (invariants)

Derived from Actor Handbook + PRD 05/16/41/82_B/98_B/98_C:

1. **No identity merge:** `actor_id` 1 and `auth_user_id` / actor 10000 never collapse; faucet ids never absorb either.
2. **No guessing:** Missing required 4.2.0 identity fields => STOP / DOCTRINE NOT FOUND (extend Global No-Guessing Rule).
3. **Packed UTC only** for `when_updated`; no local-offset-only clocks as canonical.
4. **Channels contain threads;** `thread_key` never implies a parent channel inversion.
5. **Department vs division:** division MAY exist without seeded department; department_id null is allowed when honest.
6. **External guests:** if `channel_index != lupopedia`, require `edges_toon` + `source_timestamp`; never bind external AI as internal `actor_id`.
7. **LIL001:** headers MUST NOT encode "Lilith must approve before read/write" as a hard gate.
8. **98_B isolation:** Captain's Log entertainment headers MUST declare entertainment scope (recommended `log_class: none` + `prd_cluster` including `98_B`) and remain zero doctrinal authority.
9. **98_C ops logs:** JSON ops logs under `docs/logs/` MUST carry full header object + `actor_id` / `auth_user_id` as applicable.
10. **Envelope decision (required before merge):** choose one:
    - **Option A -- Dense expand:** grow mandatory set to **28** scalars (22 + 6 identity) and redefine line budget (e.g. 31-line Markdown envelope).
    - **Option B -- Sidecar-first:** keep 22 dense fields; put identity+Hermes in `hermes_toon` / `identity_toon` (faster freeze exit, weaker head -25 guarantee for WHO).
    - **Option C -- Hybrid (RECOMMENDED):** add the **6 identity scalars** to dense header (28 total); keep Hawaiian/DO/DIRECTIVES/FOCUS in `hermes_toon` + optional toons.

---

## 5. Example Header (4.2.0) -- Captain Wolfie Log entry

**Illustrative only.** Not valid for validators until 4.2.0 is approved and `EXPECTED_HEADER_FORMAT_VERSION` updated.

Assumes **Option C (Hybrid)** field order: existing 22 keys, then six identity keys, then `hermes_toon`.

```yaml
---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: content/federation_node/0/captains_log/origin_stories_architure/2026/07/20260728_cl305_the_rise_of_prd_98_c.md
  web_path: https://www.lupopedia.com/lupopedia/content/federation_node/0/captains_log/origin_stories_architure/2026/07/20260728_cl305_the_rise_of_prd_98_c.md
  status: active
  when_updated: "20260728144244"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/captains_log/canonical/1026/07/20260728-cl305-the-rise-of-prd-98-c.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/captains_log/cl305-rise-prd-98-c
  artifact_type: status
  artifact_kind: report
  channel_key: captains_log
  federation_node_id: 0
  thread_key: "cl305-rise-of-prd-98-c"
  lupopedia.schema: status
  prd_cluster: 98_A_98_B_98_C_16_C_41_A
  title: "CL-305 -- The Rise of PRD 98_C"
  summary: "Entertainment Captain Log Volume 3: Cursor allocated Dual Operational Logs as PRD 98_C."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: 0
  department_key: "root"
  division_key: ""
  faucet_actor_id: 102
  hermes_toon: memory/captains_log/canonical/1026/07/20260728-cl305-the-rise-of-prd-98-c.hermes.toon
---
```

**Matching Hermes sidecar (conceptual):**

```yaml
lupopedia.hermes:
  from_actor: 10000
  to_actor: null
  channel_key: captains_log
  thread: cl305-rise-of-prd-98-c
  timestamp_utc: 20260728144244
  ohana: "Captain Eric, WOLFIE, Cursor faucet, Lilith auditor"
  kapu: "Zero doctrinal authority; do not overwrite PRD 98_A; do not invent channel_key"
  kapakai: "Brief tried to overload 98_A with ops logs"
  puka: "Need named letter for dual operational logs"
  pono: "PRD 98_C allocated; 98_A WHY and 98_B entertainment preserved"
  kuleana: "Eric approves; Cursor implements; Lilith audits; WOLFIE orchestrates"
  alii: "Eric Robin Gerdes (auth_user_id 10000)"
  kumu: "PRD 16_C + Actor Handbook dimensional grid + Cursor doctrine conflict status"
  eh_brah_why: "Operational logs became their own behavioral class and required a home"
```

---

## 6. Migration Notes (4.1.9 -> 4.2.0)

### Safe migration principles

1. **Do not rewrite history silently.** Prefer additive fields; keep `when_updated` honest via `tick.py`.
2. **Preserve `path_from_lupopedia_root` and `memory_toon`.** Memory graph edges must not break.
3. **Preserve `prd_cluster` strings.** PRD lineage stays readable.
4. **Preserve `federation_node_id` + `channel_key` + `thread_key`.** Routing continuity.
5. **Captain Logs (98_B) and Wolfie/Captain ops JSON (98_C)** migrate with different defaults:
   - Entertainment MD: `actor_id=10000`, `auth_user_id=10000`, `faucet_actor_id` if IDE-authored
   - WOLFIE ops JSON: `actor_id=1`, `auth_user_id` may be null or 10000 if directed by Captain
6. **Batch tool:** extend `scripts/migrate_headers_*.py` + `header_spec` `V4_HEADER_KEYS_ORDERED` only after PRD 16 merge.
7. **Validators:** accept `4.1.9` during transition; WARN missing identity fields; ERROR on 4.2.0 files missing them.
8. **No product 4.0.x -> 4.0.x automated upgrade fiction.** Header migration is filesystem tooling until product 4.2.0 upgrade gate exists (PRD 16 section 15.2).

### Suggested wave order

1. Captain chooses Option A / B / C.
2. Update PRD 16_C + atoms `header_fields.count`.
3. Update `scripts/lib/header_spec_v3_1.py` + universal validator.
4. Migrate high-traffic surfaces: `what_is_lupopedia.md`, Actor Handbook, PRD index, `docs/logs/`.
5. Migrate Captain Log corpus last (entertainment; lower runtime risk).
6. Lilith audit sample; THOTH truth-check pointers.

---

## 7. Validation Checklist (PONO vs PILAU)

### PONO (valid 4.2.0 candidate)

- [ ] `header_format_version` is exactly `4.2.0` only after approval (until then, `4.1.9`)
- [ ] All mandatory dense keys present in exact order
- [ ] `when_updated` is 14-digit packed UTC
- [ ] `actor_id` present and registry-real
- [ ] `auth_user_id` present or explicitly null with KAPU reason in Hermes
- [ ] `channel_key` + `federation_node_id` + `thread_key` coherent (thread empty string allowed)
- [ ] `memory_toon` resolves on disk when non-null (or WARN policy documented)
- [ ] `prd_cluster` non-empty for governed artifacts
- [ ] `lupopedia.schema` matches `artifact_type` policy
- [ ] External artifacts: `channel_index != lupopedia` => `edges_toon` + `source_timestamp` non-null
- [ ] No forbidden legacy keys (`pk_*`, `content_id`, etc.)
- [ ] ASCII-only normative header text
- [ ] No identity merge hints (`wolfie_human`, variant actors)
- [ ] Hermes / `hermes_toon` supplies KAPU/PONO/KULEANA/EH_BRAH_WHY when artifact claims constitutional weight
- [ ] Entertainment Captain Log does not claim ops/WHY authority

### PILAU (invalid)

- [ ] Guessed `actor_id` / invented `channel_key` / fabricated `thread_key`
- [ ] ISO-only clock as sole WHEN for repo-native updates
- [ ] Overwriting PRD 98_A meaning via header games
- [ ] External AI listed as internal `actor_id`
- [ ] Lilith hard-gate required to read header
- [ ] Hawaiian fields used as decoration without KULEANA assignment
- [ ] Missing WHO while claiming canonical trust_tier

---

## 8. Open decisions for Captain Eric (ALII)

1. Approve **Option A, B, or C** for envelope size?
2. Confirm default `department_id: 0` for root Captain Logs?
3. Should `hermes_toon` be mandatory for `trust_tier: canonical` only, or for all artifacts?
4. When may header format 4.2.0 land relative to Crafty live-help baseline / product upgrade gate?
5. Should 98_C JSON logs use the same dense Markdown envelope keys inside JSON `header` objects? (Recommended: yes.)

---

## 9. Next Actions

- **Cursor:** wait for Captain option choice; do not change `EXPECTED_HEADER_FORMAT_VERSION` yet.
- **WOLFIE:** flag any agent emitting `4.2.0` before PRD 16 merge.
- **Lilith:** audit this proposal for LIL001 / AGAPE / no-guessing compliance (non-blocking).
- **AGAPE:** if agents implement 4.2.0 early, open WHY file -- premature unfreeze is a violation class.

---

**END -- Proposal Headers 4.1.9 to 4.2.0**
