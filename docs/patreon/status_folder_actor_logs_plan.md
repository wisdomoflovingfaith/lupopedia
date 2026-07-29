---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/patreon/status_folder_actor_logs_plan.md
  web_path: https://www.lupopedia.com/lupopedia/docs/patreon/status_folder_actor_logs_plan.md
  status: active
  when_updated: "20260729132944"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/status-folder-actor-logs-plan
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: status_folder_actor_logs
  lupopedia.schema: documentation
  prd_cluster: 98_C_98_B_16_C_15_A_41_A_73_A_82_B
  title: "STATUS FOLDER Actor Logs -- improved multi-medium plan"
  summary: "Review and improved plan for Actor Logs collection across Patreon, website, and GitHub. Headers declare location and update/correction protocol. Collection homes under PRD 98, not 00."
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
# STATUS FOLDER Actor Logs -- Improved Plan (Review)

**Status:** DRAFT PLAN (not a PRD yet)  
**Collection display name:** STATUS FOLDER -- Actor Logs  
**Root PRD home:** **98** (logs / WHY / dual ops) -- NOT 00  
**Header contract:** `header_format_version: "4.2.0"` (Option A, 28 fields)  
**Mediums:** GitHub (canonical file), website (`web_path`), Patreon (mirror / paid / free)

---

## 0. Verdict on the current draft

### Keep (good ideas)

- Unified actor logging for AI + human
- Explicit attribution (actor + auth user)
- Category letters for Authority / Corrections / Errors / etc.
- Human-readable revision labels
- Free index + scalable filtering goals
- Constitutional mapping (KAPU / PONO / KULEANA / ALII) via Hermes -- not densified into YAML

### Fix (blocking problems)

| Draft idea | Problem | Improved rule |
|------------|---------|---------------|
| Root titles start with `00 A I ...` | Collides with **PRD selectors** (`00_A-i`) and steals **PRD group 00** (constitutional root) | Collection root number is **98**. Display IDs use a **log slug**, not a fake PRD selector. |
| Group 00-99 = "Founders / Core / Support" for actors | Collides with **PRD 00-99** Patreon root map | Actor *cohorts* use named bands or registry fields -- do **not** reuse PRD NN as actor-group shelves. |
| Roman numeral as only version | Conflicts with packed UTC `when_updated` and 98_C thread clocks | Roman = **edition label** in title; clock of truth = `when_updated` + optional `source_timestamp` per medium. |
| Identity only in filename | Duplicates and drifts from header | Filename may echo IDs; **header scalars are authoritative**: `actor_id`, `auth_user_id`, `faucet_actor_id`. |
| "Replaces old WOLFIE logs" | Overstates -- 98_B entertainment and 98_C ops logs already exist | STATUS FOLDER is a **collection view / multi-medium index** over actor logs; it does not erase 98_B/98_C. |
| Hermes "4.1.6" | Stale | Cite PRD **82_B** + current header **4.2.0**. |

---

## 1. What this collection is

**STATUS FOLDER -- Actor Logs** is a **multi-medium collection** that indexes and publishes actor status / decision / correction / doctrine-alignment logs for any actor (AI or human) in the federation.

It is:

1. A **Patreon collection** (free index + free/paid entries)
2. A **website-facing** set of pages (same slugs / titles where possible)
3. A **GitHub-canonical** tree under the repo (source of truth for headers and paths)

It is **not**:

- PRD group 00
- A replacement for PRD files
- A place to put Hawaiian fields inside the dense header
- The entertainment-only Captain Log (that remains 98_B narrative)

**Relationship to existing log PRDs**

| Layer | Role |
|-------|------|
| **98_B** | Entertainment Captain Log (zero doctrinal authority) |
| **98_C** | Dual operational JSON logs (Captain + WOLFIE), `docs/logs/` |
| **STATUS FOLDER Actor Logs** | Human-readable multi-actor log *collection* across Patreon / site / GitHub; may **link to** 98_C JSON and 98_B posts via headers + edges |

---

## 2. Where an article lives (headers declare location)

Every STATUS FOLDER article MUST ship `header_format_version: "4.2.0"` and answer WHERE via dense fields -- no guessing.

### 2.1 Canonical location fields

| Field | Meaning for this collection |
|-------|-----------------------------|
| `path_from_lupopedia_root` | GitHub / install path (canonical file) |
| `web_path` | Public Lupopedia URL for the same artifact |
| `channel_index` | Origin medium of *this file*: `lupopedia` (repo), `patreon`, `website`, `external` |
| `federation_node_id` | Usually `0` for home node |
| `channel_key` | Semantic channel (e.g. `development`, `captains_log`, `status`) |
| `thread_key` | Stable thread slug for the log series |
| `edges_toon` | **Required** when `channel_index != lupopedia` -- links Patreon/website mirrors to GitHub canonical |
| `source_timestamp` | **Required** when external/Patreon origin -- immutable first publish time (ISO with Z) |
| `when_updated` | Packed UTC of last normative edit (`YYYYMMDDHHIISS`) from `python bin/tick.py` |

### 2.2 Multi-medium pattern (one logical article, three surfaces)

```text
LOGICAL ARTICLE
  canonical (GitHub):  path_from_lupopedia_root + channel_index: lupopedia
  website mirror:      same body or render; channel_index: website; edges_toon -> canonical
  Patreon mirror:      paid/free post; channel_index: patreon; edges_toon -> canonical
```

**Rule:** Exactly one surface is **canonical**. Prefer GitHub. Patreon/website posts set:

- `channel_index: patreon` or `website`
- non-null `edges_toon` pointing at the GitHub path / edge sidecar
- non-null `source_timestamp` for first publish on that medium
- `when_updated` when the *mirror* is edited (or when canonical is synced)

**Do not** invent a second "true" path. If Patreon is edited first, sync back to GitHub and bump `when_updated` on the canonical file.

### 2.3 Example header (GitHub canonical root index)

```yaml
---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/ROOT_INDEX.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/ROOT_INDEX.md
  status: active
  when_updated: "20260729132944"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/actor-logs-root-index
  artifact_type: documentation
  artifact_kind: guide
  channel_key: status
  federation_node_id: 0
  thread_key: actor_logs_root
  lupopedia.schema: documentation
  prd_cluster: 98_C_16_C_15_A
  title: "STATUS FOLDER Actor Logs -- Root Index"
  summary: "Master index for multi-medium Actor Logs collection under PRD group 98."
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

### 2.4 Example header (Patreon mirror of same article)

```yaml
# Same 28 keys; differences:
#   path_from_lupopedia_root: still the GitHub path (identity of the logical article)
#   web_path: Patreon post URL OR still the lupopedia web_path + edges
#   channel_index: patreon
#   edges_toon: edges/.../status-folder-actor-logs-root.edges.toon
#   source_timestamp: "2026-07-29T13:29:44Z"
#   when_updated: packed UTC of last mirror sync
```

---

## 3. Improved naming (no PRD collision)

### 3.1 Display title format (Patreon / website)

```text
SFAL [CATEGORY] [EDITION] [ACTOR_SLUG]-[actor_id]-[auth_user_id] -- [TITLE]
```

Examples:

- `SFAL I I ROOT_INDEX-0-00000 -- STATUS FOLDER Actor Logs Root Index`
- `SFAL C II CAPTAIN_WOLFIE-1-10000 -- Correction: Header 4.2.0 Identity Fields`
- `SFAL E I LILITH-2-10000 -- Error Path: PermissionDenied on deleted channel`

**SFAL** = Status Folder Actor Logs (collection prefix -- avoids looking like PRD `00_A`).

### 3.2 Category letters (keep; clarify)

| Letter | Meaning | Notes |
|--------|---------|-------|
| A | Authority / ALII | Approvals, captaincy, overrides |
| B | Behavior | Observed actor behavior |
| C | Corrections | Fixes, retractions, supersessions |
| D | Doctrine | Alignment / conflict notes |
| E | Errors | Failures, exceptions |
| F | Federation | Cross-node / cross-actor links |
| G | Governance | Audit, review, escalation |
| H | Health | Fatigue, capacity, Asclepius-adjacent |
| I | Index / Infrastructure | Root indexes, maps, protocols |
| J-Z | HOLD until needed | Do not invent ad hoc |

### 3.3 Edition (Roman) vs clock

- **Roman (I, II, III...)** = human edition of *that titled document* (rewrite wave)
- **`when_updated`** = machine clock of last change (mandatory)
- **New filename** only when the logical article splits; otherwise edit in place and bump Roman + `when_updated`

### 3.4 Identity (authoritative in header)

| Concern | Header field | Filename echo |
|---------|--------------|---------------|
| WHO actor | `actor_id` | `ACTOR_SLUG-actor_id` |
| WHICH human | `auth_user_id` | `-auth_user_id` |
| WHICH faucet | `faucet_actor_id` | optional; do not put in title unless needed |
| DEPARTMENT | `department_id` / `department_key` | optional |
| DIVISION | `division_key` | e.g. `logging` |

Filename IDs that disagree with the header are **invalid** -- header wins; fix filename on next edit.

---

## 4. How to add updates and corrections

### 4.1 Routine update (same article)

1. Run `python bin/tick.py`
2. Edit body
3. Set `when_updated` to printed UTC
4. If the change is a new *edition*, bump Roman in title (`I` -> `II`) and in display name
5. Keep `source_timestamp` unchanged (origin time)
6. Sync mirrors (Patreon / website); bump their `when_updated`; keep `edges_toon` -> canonical

### 4.2 Correction (error, retraction, doctrine fix)

1. Prefer **edit in place** on the canonical GitHub file
2. Add a visible **CORRECTION** block at top of body with:
   - packed UTC of correction
   - what was wrong
   - what is true now
   - pointer to related SFAL C-* log if needed
3. Bump `when_updated`
4. Bump Roman edition if the correction changes meaning of the whole doc
5. Optionally write a sibling **Category C** log that links via `edges_toon` / body links
6. Sync Patreon/website mirrors the same day when possible

### 4.3 Supersession (old article retired)

1. Set old file `status:` to a non-active value allowed by local policy (or keep `active` with body KAPU "SUPERSEDED")
2. New file gets new path + new `thread_key` or clear edition jump
3. Both headers: `edges_toon` (or body links) point old <-> new
4. Index (SFAL I) updated in the same batch

### 4.4 What NOT to do

- Do not invent a new PRD number for each log
- Do not put OHANA/KAPU/... into dense YAML
- Do not change `actor_id` history by rewriting old rows silently -- correct forward
- Do not leave Patreon as the only copy with no GitHub canonical

---

## 5. Improved Table of Contents (Part I)

All under collection **STATUS FOLDER -- Actor Logs**, Patreon root shelf **98** (or non-root companion titled "STATUS FOLDER" that links to 98).

| Display ID | Purpose |
|------------|---------|
| SFAL I I ROOT_INDEX-0-00000 | This master index (orientation) |
| SFAL I II MEDIUM_MAP-0-00000 | Patreon / website / GitHub location rules + header examples |
| SFAL I III CATEGORY_DEFINITIONS-0-00000 | A-Z category map |
| SFAL I IV EDITION_RULES-0-00000 | Roman edition + `when_updated` + supersession |
| SFAL I V WRITING_PROTOCOL-0-00000 | How actors write logs (header-first) |
| SFAL A I AUTHORITY_CHAINS-0-00000 | ALII / dual-captaincy / faucet attribution |
| SFAL C I CORRECTION_PROTOCOL-0-00000 | Update + correction + retraction paths |
| SFAL E I ERROR_HANDLING-0-00000 | Failure reporting paths |
| SFAL F I FEDERATION_LINKS-0-00000 | Cross-actor / cross-medium edges |
| SFAL G I GOVERNANCE_RULES-0-00000 | Audit / review / Lilith non-interference |
| SFAL B I ACTOR_REGISTRY_POINTER-ALL-00000 | Pointer to `database/.../actors/registry.json` (do not fork) |
| SFAL B II HUMAN_REGISTRY_POINTER-ALL-00000 | Pointer to auth users / Captain 10000 (do not fork) |

**Note:** Actor list and human list are **pointers to registries**, not duplicated Patreon-only truth tables.

---

## 6. Actor "groups" without stealing PRD 00-99

Do **not** use 00-99 as actor cohort IDs.

Use one of:

1. Registry fields / department keys (`department_key`, `division_key`)
2. Named cohorts in body: `founders`, `core`, `support`, `external`, `guest`
3. Optional future SFAL index tables that map `actor_id` -> cohort string

Filtering then uses header `actor_id` + cohort tags in body/Hermes -- not fake PRD numbers.

---

## 7. Scaling (revised)

Bounded keys that actually scale:

- `actor_id` (header)
- `auth_user_id` (header)
- `faucet_actor_id` (header)
- category letter (title + optional body tag)
- edition Roman (title)
- `when_updated` (header)
- `thread_key` (header)
- `channel_index` (medium)
- `federation_node_id`

This avoids `actors x humans` filename chaos because the **header** carries the join keys and the **collection index** filters them.

---

## 8. Constitutional fields

Keep OHANA, KAPU, KAPAKAI, PUKA, PONO, KULEANA, ALII, KUMU, EH_BRAH_WHY in:

- Hermes sidecar / routing, or
- body sections of the log

Never as dense header keys 29+.

---

## 9. Suggested repo layout (canonical)

```text
docs/status/actor_logs/
  ROOT_INDEX.md
  MEDIUM_MAP.md
  CATEGORY_DEFINITIONS.md
  EDITION_RULES.md
  WRITING_PROTOCOL.md
  CORRECTION_PROTOCOL.md
  ...
  actors/{actor_id}/
    YYYY/
      MM/
        {slug}.md
```

Patreon collection: **STATUS FOLDER -- Actor Logs** (index FREE; entries free/paid per July 28 policy; headers 4.2.0+).  
Website: render from same paths or mirrored HTML with `channel_index: website` + edges.

---

## 10. Next dependency-ordered steps

1. Captain approve this plan (collection under **98**, SFAL naming, header location rules)
2. Author SFAL I I Root Index + SFAL I II Medium Map as real files under `docs/status/actor_logs/`
3. Optionally propose a thin PRD 98_D later if STATUS FOLDER needs normative teeth (not required to start drafting)
4. Do not migrate corpus headers until validator dual-accept for 4.2.0 is scheduled

---

## 11. Review summary (one paragraph)

The draft's goals are sound, but the `00 A I ...` naming collides with PRD selectors and constitutional group 00. Put the collection under **PRD 98**, use **SFAL** display IDs, make **4.2.0 headers** the authority for WHO/WHERE/WHEN and multi-medium location (`channel_index` + `edges_toon` + `path_from_lupopedia_root` / `web_path`), and treat Roman numerals as edition labels while `when_updated` drives updates and Category **C** plus in-place CORRECTION blocks handle corrections.
