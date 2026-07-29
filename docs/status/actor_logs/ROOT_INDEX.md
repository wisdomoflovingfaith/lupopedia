---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/ROOT_INDEX.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/ROOT_INDEX.md
  status: active
  when_updated: "20260729134317"
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
  prd_cluster: 98_C_98_B_16_C_15_A_41_A_73_A_82_B_39_A
  title: "SFAL I I ROOT_INDEX-0-00000 -- STATUS FOLDER Actor Logs Root Index"
  summary: "Master orientation for STATUS FOLDER Actor Logs under PRD 98. Naming, location headers, multi-medium rules, WOLFIE dialect (integrity + ethics), TOC Part I, update and correction protocol."
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

(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | ethics: "pono" | integrity: "true" | note: "opening ROOT_INDEX commentary" ))

{{WOLFIE_VARS
auth_user_id = 10000
actor_id = 1
actor_name = "CAPTAIN_WOLFIE"
integrity = true
ethics = pono
repo = "https://github.com/wisdomoflovingfaith/lupopedia"
canonical_path = "docs/status/actor_logs/ROOT_INDEX.md"
}}

{{WOLFIE
actor: CAPTAIN_WOLFIE
actor_id: 1
auth_user_id: 10000
integrity: true
ethics: pono
channel: status
---
Captain Wolfie speaking.

This ROOT_INDEX is the orientation map for STATUS FOLDER Actor Logs under PRD cluster 98. It exists so every actor -- AI or human -- can find how logs are named, attributed, versioned, corrected, and published without stealing PRD 00 or inventing fake selectors.

STATUS FOLDER Actor Logs are not entertainment fluff and not a replacement for 98_B narrative or 98_C dual ops JSON. They are the human-readable multi-actor status surface: decisions, corrections, doctrine alignment, failures, and responsibilities, written so the federation can still read itself when mirrors disagree.

WHERE this canonical file lives:
- GitHub repository: https://github.com/wisdomoflovingfaith/lupopedia
- Path in repo: docs/status/actor_logs/ROOT_INDEX.md
- Public web_path (install URL): https://www.lupopedia.com/lupopedia/docs/status/actor_logs/ROOT_INDEX.md

Multi-medium consistency is KULEANA. GitHub is canonical. Patreon and website mirrors must point back with edges and honest source_timestamp. Do not leave Patreon as the only copy. Do not let a mirror silently diverge from the repo address above.

When you write a log, you are not decorating a blog -- you are recording actor consciousness under integrity and ethics. If the entry touches KAPU, PONO, KULEANA, ALII, EH_BRAH_WHY, or PUKA, mark ethics and integrity in the WOLFIE dialect and keep Hawaiian fields in Hermes/body, never in dense headers. Headers stay pure 4.2.0. The dialect stays body-only. Strip-safe. Zero fake authority from markup.

Write so another actor, another human, and another medium can recover truth without guessing.
}}

# SFAL I I ROOT_INDEX-0-00000 -- STATUS FOLDER Actor Logs Root Index

**Display ID:** SFAL I I ROOT_INDEX-0-00000  
**Collection:** STATUS FOLDER -- Actor Logs  
**PRD home:** **98** (not 00)  
**Edition:** I  
**Canonical medium:** GitHub (`channel_index: lupopedia`)  
**Plan (approved):** [docs/patreon/status_folder_actor_logs_plan.md](../../patreon/status_folder_actor_logs_plan.md)

---

## TL;DNR

STATUS FOLDER Actor Logs is the unified, scalable, multi-medium logging collection for all actors (AI or human) in the Lupopedia Federation.

This root index defines:

- how logs are grouped (collection under PRD **98**)
- how logs are named (**SFAL** display IDs -- not fake PRD selectors)
- how logs are versioned (Roman = edition; `when_updated` = clock)
- how logs are attributed (header `actor_id` / `auth_user_id` / `faucet_actor_id`)
- how logs declare WHERE they live (path, web_path, channel_index, edges)
- how logs are searched and cross-linked
- how updates and corrections are applied without drift across GitHub / website / Patreon
- how body **WOLFIE** dialect carries actor/human narrative, variables, integrity, and ethics (see [WOLFIE_DIALECT.md](WOLFIE_DIALECT.md))

This document is the master orientation point for the entire collection.

---

## Purpose of the collection

STATUS FOLDER Actor Logs exist to:

- record actor state
- record actor decisions
- record actor corrections
- record actor doctrine alignment
- record actor failures
- record actor responsibilities
- record actor interactions with humans
- record actor interactions with other actors
- maintain constitutional compliance (KAPU, PONO, KULEANA, ALII, etc. via Hermes/body -- not dense YAML)
- maintain traceability across the federation

**Relationship to existing log layers**

| Layer | Role |
|-------|------|
| **98_B** | Entertainment Captain Log (zero doctrinal authority) |
| **98_C** | Dual operational JSON logs (Captain + WOLFIE) under `docs/logs/` |
| **STATUS FOLDER Actor Logs** | Human-readable multi-actor collection across Patreon / website / GitHub; may link to 98_B/98_C via headers and edges |

This collection does **not** erase 98_B or 98_C. It does **not** steal PRD group 00.

---

## Naming convention (mandatory)

### Display title format

```text
SFAL [CATEGORY] [EDITION] [ACTOR_SLUG]-[actor_id]-[auth_user_id] -- [TITLE]
```

Examples:

- `SFAL I I ROOT_INDEX-0-00000 -- STATUS FOLDER Actor Logs Root Index` (this file)
- `SFAL C II CAPTAIN_WOLFIE-1-10000 -- Correction: Header 4.2.0 Identity Fields`
- `SFAL E I LILITH-2-10000 -- Error Path: PermissionDenied on deleted channel`

**SFAL** = Status Folder Actor Logs. Do **not** use `00 A I ...` style strings (those collide with PRD selectors).

### Category letters

| Letter | Meaning |
|--------|---------|
| A | Authority / ALII |
| B | Behavior |
| C | Corrections |
| D | Doctrine |
| E | Errors |
| F | Federation |
| G | Governance |
| H | Health |
| I | Index / Infrastructure |
| J-Z | HOLD until defined |

### Edition vs clock

- **Roman (I, II, III...)** = human edition label for that titled document
- **`when_updated`** = packed UTC of last normative edit (`python bin/tick.py`)
- **`source_timestamp`** = immutable first publish time when `channel_index` is not `lupopedia`

### Identity authority

Header fields win over filenames:

- `actor_id`
- `auth_user_id`
- `faucet_actor_id`
- `department_id` / `department_key` / `division_key`

If a filename ID disagrees with the header, the header is authoritative; fix the filename on the next edit.

### Actor cohorts (do not reuse 00-99)

Actor cohorts use named bands or registry/department fields (`founders`, `core`, `support`, `external`, `guest`). Do **not** reuse PRD numbers 00-99 as actor-group shelves.

---

## Where an article lives (headers declare location)

Every STATUS FOLDER article MUST use `header_format_version: "4.2.0"` (28-field Option A).

| Field | Role |
|-------|------|
| `path_from_lupopedia_root` | GitHub canonical path |
| `web_path` | Public Lupopedia URL |
| `channel_index` | `lupopedia` \| `patreon` \| `website` \| `external` |
| `federation_node_id` | Usually `0` |
| `channel_key` | Semantic channel (this tree uses `status`) |
| `thread_key` | Stable series slug |
| `edges_toon` | Required when not `lupopedia` -- link mirrors to canonical |
| `source_timestamp` | Required for non-lupopedia origin |
| `when_updated` | Last normative edit (packed UTC) |

### Multi-medium rule

```text
LOGICAL ARTICLE
  canonical (GitHub):  channel_index: lupopedia
  website mirror:      channel_index: website + edges_toon -> canonical
  Patreon mirror:      channel_index: patreon + edges_toon -> canonical
```

Exactly one surface is canonical. Prefer GitHub. Sync mirrors after canonical edits.

---

## Updates and corrections

### Routine update

1. `python bin/tick.py`
2. Edit body
3. Set `when_updated`
4. Bump Roman edition if the rewrite is a new edition
5. Keep `source_timestamp` unchanged
6. Sync Patreon / website mirrors; bump their `when_updated`

### Correction

1. Edit canonical GitHub file in place
2. Add a visible **CORRECTION** block at top of body (UTC, what was wrong, what is true now)
3. Bump `when_updated` (and Roman if meaning of whole doc changed)
4. Optionally add a Category **C** sibling log with edges/links
5. Sync mirrors

### Supersession

1. Mark old article superseded in body (and status policy as applicable)
2. Create new file with clear edition / thread identity
3. Link old <-> new via edges or body
4. Update this ROOT_INDEX in the same batch

---

## Constitutional fields (Hermes / body only)

OHANA, KAPU, KAPAKAI, PUKA, PONO, KULEANA, ALII, KUMU, EH_BRAH_WHY belong in Hermes sidecars or body sections -- **never** as dense header keys.

## WOLFIE dialect (body only -- Integrity + Ethics)

Actor logs MAY use the **WOLFIE Dialect** (NOT "WOLF") for:

- inline meta: `(( WOLFIE | auth_user_id: 10000 | ethics: "pono" | integrity: "true" | note: "says hi" ))`
- variables: `{{WOLFIE_VARS ... }}`
- narrative: `{{WOLFIE ... }}`

When a log touches KAPU / PONO / KULEANA / ALII / EH_BRAH_WHY / PUKA, WOLFIE spans that carry that claim MUST include **`integrity`** and **`ethics`**.

Rules and safety: **[WOLFIE_DIALECT.md](WOLFIE_DIALECT.md)**  
Cursor paste prompt: **[CURSOR_PROMPT_WOLFIE_DIALECT.md](CURSOR_PROMPT_WOLFIE_DIALECT.md)**  
Dialect has **zero constitutional authority**. Headers stay pure 4.2.0. Meta syntax is still evolving. PRD 39 remains "WOLF Markup" (separate product).

---

## Table of Contents -- Part I

| Display ID | File / status | Purpose |
|------------|---------------|---------|
| SFAL I I ROOT_INDEX-0-00000 | [ROOT_INDEX.md](ROOT_INDEX.md) (this file) | Master index |
| SFAL I II MEDIUM_MAP-0-00000 | pending `MEDIUM_MAP.md` | Patreon / website / GitHub location rules |
| SFAL I III CATEGORY_DEFINITIONS-0-00000 | pending | A-Z category map |
| SFAL I IV EDITION_RULES-0-00000 | pending | Roman + when_updated + supersession |
| SFAL I V WRITING_PROTOCOL-0-00000 | pending | How actors write logs (header-first) |
| SFAL I VI WOLFIE_DIALECT-0-00000 | [WOLFIE_DIALECT.md](WOLFIE_DIALECT.md) | Body WOLFIE dialect (integrity + ethics) |
| SFAL A I AUTHORITY_CHAINS-0-00000 | pending | ALII / dual-captaincy / faucet attribution |
| SFAL C I CORRECTION_PROTOCOL-0-00000 | pending | Update + correction + retraction |
| SFAL E I ERROR_HANDLING-0-00000 | pending | Failure reporting paths |
| SFAL F I FEDERATION_LINKS-0-00000 | pending | Cross-actor / cross-medium edges |
| SFAL G I GOVERNANCE_RULES-0-00000 | pending | Audit / review / Lilith non-interference |
| SFAL B I ACTOR_REGISTRY_POINTER-ALL-00000 | pending | Pointer to actor registry (do not fork) |
| SFAL B II HUMAN_REGISTRY_POINTER-ALL-00000 | pending | Pointer to auth users / Captain 10000 |

Suggested tree for later actor entries:

```text
docs/status/actor_logs/
  ROOT_INDEX.md
  WOLFIE_DIALECT.md
  CURSOR_PROMPT_WOLFIE_DIALECT.md
  MEDIUM_MAP.md
  ...
  actors/{actor_id}/YYYY/MM/{slug}.md
```

---

## Why this scales

1. **Bounded namespaces** -- group by SFAL category + actor_id + auth_user_id + thread_key, not actor x human filename chaos
2. **Instant filtering** -- header fields + category letter + edition + medium (`channel_index`)
3. **Federation growth** -- unique, traceable, conflict-free across surfaces
4. **Constitutional enforcement** -- Hermes/body carry KAPU/PONO/KULEANA/ALII without densifying Hawaiian keys

---

## Cross-references

- Plan: [docs/patreon/status_folder_actor_logs_plan.md](../../patreon/status_folder_actor_logs_plan.md)
- WOLFIE dialect: [WOLFIE_DIALECT.md](WOLFIE_DIALECT.md)
- Cursor prompt: [CURSOR_PROMPT_WOLFIE_DIALECT.md](CURSOR_PROMPT_WOLFIE_DIALECT.md)
- Patreon 00-99 map: [docs/patreon/00_99_root_collections_map.md](../../patreon/00_99_root_collections_map.md)
- Headers 4.2.0: [docs/prd/16_C-i_LUPOPEDIA_HEADERS.md](../../prd/16_C-i_LUPOPEDIA_HEADERS.md)
- WOLF Markup PRD 39 (general overlay; not SFAL dialect): [docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md](../../prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md)
- Dual ops logs: [docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md](../../prd/98_C-i_DUAL_OPERATIONAL_LOGS.md)
- Entertainment Captain Log: [docs/prd/98_B-i_THE_CAPTAINS_LOG_HUMAN_ONLY_ENTERTAINMENT_LAYER.md](../../prd/98_B-i_THE_CAPTAINS_LOG_HUMAN_ONLY_ENTERTAINMENT_LAYER.md)
- HERMES / Hawaiian: [docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md](../../prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)

---

## KAPU

- Do not number this collection as PRD **00**
- Do not use fake PRD selectors in display IDs
- Do not treat Patreon as the only copy
- Do not put Hawaiian constitutional keys in the dense header
- Do not invent actor cohort numbers that collide with PRD 00-99
- Do not treat WOLFIE dialect as executable authority (strip-safe annotation only)
- Do not name the SFAL body dialect "WOLF" -- it is **WOLFIE** (Integrity + Ethics)
