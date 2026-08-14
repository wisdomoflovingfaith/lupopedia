---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd_proposals/16_C_HEADER_FORMAT_4_2_1_PROPOSAL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/16_C_HEADER_FORMAT_4_2_1_PROPOSAL.md
  status: active
  when_updated: "20260811132142"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/16-c-header-format-4-2-1-proposal
  artifact_type: prd
  artifact_kind: requirements
  channel_key: development
  federation_node_id: 0
  thread_key: header-format-4-2-1
  lupopedia.schema: prd
  prd_cluster: 16_C-i_16_A-i_82_B-i_99_A-i_15_A-i_05_A-i
  title: "PROPOSAL: Lupopedia Headers 4.2.1 -- Universal LUP identity"
  summary: "APPROVED 20260811. Universal identity LUP:FF-GG-LL-II-RRRRRR merged into PRD 16_C 4.2.1. Validators and runtime code remain a follow-on implementation task."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: headers
  faucet_actor_id: 102
---
# PROPOSAL: Lupopedia Headers v4.2.1 -- Universal Identity

**Status:** APPROVED by Captain ALII 20260811, then **corrected** 20260811133636: `RRRRRR` is `artifact_hex`, not `actor_hex`. Actor and color are metadata. GG is catalog owner. Normative text is PRD 16_C section 4.2.5. Historical `actor_hex` examples below are superseded.  
**Date (UTC packed):** 20260811132142  
**Author voice:** WOLFIE (`actor_id` 1) via CURSOR faucet (`faucet_actor_id` 102) under ERIC (`auth_user_id` 10000)  
**Inputs:** PRD 16_A / 16_C / 16_D / 16_E, PRD 82_B, PRD 99 + federation bullets, Rule 99 Actor <-> Catalog alignment (20260810), PRD 15, existing 4.2.0 FINAL proposal lineage

---

## 0. KAPU (read first)

1. Current normative header contract for **new** authored envelopes is **`header_format_version: "4.2.1"`** (sibling `lupopedia.identity`). The 28-field dense grid remains the 4.2.0 discovery protocol. Existing 4.2.0 files are dual-accepted with WARN.
2. Header format 4.2.1 is **not** a product major/minor bump. Product `GLOBAL_CURRENT_LUPOPEDIA_VERSION` stays on its own atom. Do not invent 4.3.0 from this proposal.
3. Hawaiian constitutional fields remain **out of** the dense discovery grid (Hermes / sidecar / body). 4.2.1 does not densify OHANA..PUKA.
4. Songs are **not** a special identity class. If a rule applies only to songs, it is **metadata**, not identity.
5. ASCII only. Authority token is **ALII**.

---

## 1. Executive Summary

Lupopedia currently has two identity languages:

- **Headers (PRD 16):** filesystem path, `federation_node_id`, `actor_id`, `artifact_type`, transcript slug, and (since 4.2.0) six dense identity scalars.
- **Songs (PRD 99):** catalog + language + iteration + HEX color, with optional Federation ID, Actor <-> Catalog alignment, and a 14.4M practical color ceiling.

That split will drift. Songs look special. Documents invent parallel IDs. Federation then has to rewrite more than one field.

**Header 4.2.1 proposes one universal identity string for every artifact:**

```text
LUP:FF-GG-LL-II-RRRRRR
```

| Token | Name | Mutability | Meaning |
|-------|------|------------|---------|
| FF | Federation ID | **Only mutable identity field** | Which Node universe hosts the live catalog |
| GG | Group ID | Stable | Catalog namespace / group |
| LL | Language | Stable | ISO 639-1 |
| II | Iteration | Stable except explicit version events | Remix, cover, or revision generation |
| RRRRRR | Actor | Stable | 24-bit hex encoding of Catalog / OS `actor_id` |

**Metadata** (not identity) answers WHAT the artifact is: song, document, crest, reel, semantic atom, toon, lineage graph, catalog index. **CC-BY NAME** is metadata. **Rule 99 color slot** is metadata. Hawaiian constitutional fields remain routing/audit metadata.

**Recommendation:** Approve 4.2.1 as the identity unification revision of PRD 16_C. Keep the 4.2.0 28-field discovery grid. Add `lupopedia_id` plus decomposed identity scalars. Do not create a song-only schema.

---

## 2. Research Summary (from PRD 16 and companions)

### 2.1 Current header format (4.1.6 and what replaced it)

**4.1.6 (historical):** PRD 16_A-iii froze the four-axis PRD cluster system (position = priority, letter A-F = significance, number = grouping, Roman = chronology). Headers used a **22-field** discovery envelope. 4.1.6 interpretation rules are **not** to be assumed for new files (16_A-i says 4.1.5/4.1.6 interpretation is obsolete).

**4.1.8 / 4.1.9 (legacy live):** 22 mandatory scalars, 25-line Markdown envelope (`---` + `lupopedia.headers:` + 22 keys + `---`). Three-part preamble for PRDs: YAML, ASCII_ART_BLOCK, HUMAN_SEMANTIC.

**4.2.0 (current contract):** Captain ALII Option A -- Dense Expansion. Fields **1-22 preserved**. Fields **23-28** added: `actor_id`, `auth_user_id`, `department_id`, `department_key`, `division_key`, `faucet_actor_id`. Envelope is 31 Markdown lines. Hawaiian keys forbidden in dense YAML. Product 4.2.0 is TRANSITIONAL/UNSTABLE.

**4.2.1 (this proposal):** Does not throw away 4.2.0. It **unifies identity** so songs and all other artifacts share one ID grammar.

### 2.2 The 22-field header template (PRD 16_A-i / 16_C 4.1.9 order)

Canonical 22-field order (still fields 1-22 of 4.2.0):

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

`artifact_type` is already a **closed enum** (prd, implementation, documentation, doctrine, version-doc, status). That is metadata about kind, not a second identity system -- but it is not yet bound to a universal LUP ID.

### 2.3 Constitutional Hawaiian fields (PRD 82_B; KAPU in 16_C)

These are **routing / audit / ethics metadata**. They MUST NOT enter the dense 4.2.0/4.2.1 discovery grid.

| Field | Role |
|-------|------|
| **OHANA** | Who is in the handoff (participants). Required on Hermes envelopes as a set. |
| **KAPU** | Hard constraints. DO NOT rules. Forbidden actions. |
| **KAPAKAI** | Problem / diagnostic state. What is wrong or missing. |
| **PONO** | Desired outcome. What should be true. Never a boolean. |
| **KULEANA** | Who must fix or perform the work. |
| **ALII** | Who decides / who has authority. |
| **KUMU** | Teacher / source / foundation (PRD, doctrine, person). Not KULEANA and not ALII. |
| **EH_BRAH_WHY** | Audit rationale / root-cause ledger. Why it exists or broke. |
| **PUKA** | Deterministic **structural** gap only (ID continuity, cluster holes, schema holes). Not a generic error. |

Identity (LUP ID) answers **which object**. Hawaiian fields answer **how the ohana should treat that object**. Mixing them collapses audit into naming.

### 2.4 Existing song identity (PRD 99 + federation bullets)

Current song ID conceptual form:

```text
[optional Federation/Node ID][catalog][language][iteration][color]
```

Examples after Actor <-> Catalog alignment (20260810):

- System: `00EN000000`
- Wolfie: `01EN000064`
- Lilith: `02EN0000C8`

Rules:

- Catalog N = OS `actor_id` (no mismatch).
- Color MUST sit in that Actor's 100-slot HEX range (`start = N * 100`).
- Remix/cover: same color, increment iteration.
- Missing Federation ID means Node 0 lookup (today's PRD 99 wording).
- Practical ceiling: 144000 Actors x 100 colors = 14400000; reserved `DBBA00` -> `FFFFFF`.

**Gap:** This grammar is song-shaped (color is load-bearing). Documents have no color slot and currently use `path_from_lupopedia_root` as de-facto identity. 4.2.1 keeps color as **song metadata** and lifts FF/GG/LL/II/Actor into a type-neutral ID.

### 2.5 Actor ranges (SYSTEM, WOLFIE, LILITH, AGAPE, 144000 RGB actors)

| Catalog / Actor N | Identity | Color band (Rule 99) | Actor hex in LUP ID (RRRRRR) |
|-------------------|----------|----------------------|------------------------------|
| 0 | SYSTEM | `000000` -> `000063` | `000000` |
| 1 | WOLFIE | `000064` -> `0000C7` | `000001` |
| 2 | LILITH | `0000C8` -> `00012B` | `000002` |
| 705 | AGAPE | `705*100` .. `+0x63` | `0002C1` |
| 143999 | Last usable RGB catalog actor | `DBB99C` -> `DBB9FF` | `02327F` |

**KAPU:** `RRRRRR` is the **actor number as six-digit hex**, not the actor's first song color. Wolfie identity is `000001`, not `000064`. `000064` remains Wolfie's first **song color slot** (metadata).

Usable catalog actors: `0..143999` (144000). Reserved chromatic zone is locked. Lilith Catalog 2 enforces alignment and the 14.4M practical ceiling.

OS agents above 143999 (example: `meta` 998, `methis` 999, some IDE faucets if treated as actors) are **outside the song-color universe**. 4.2.1 still allows them as `RRRRRR` for **artifact** ownership, but they MUST NOT claim Rule 99 song colors. Faucets remain `faucet_actor_id` metadata, not `RRRRRR`, unless the artifact is truly owned by that facet persona.

### 2.6 Node system (Node 01 vs Node X)

Two numberings exist today. 4.2.1 must name the mapping, not pretend they are already identical.

| Today's PRD 99 / headers | 4.2.1 Federation ID (FF) | Role |
|--------------------------|--------------------------|------|
| `federation_node_id: 0` (lupopedia.com root / this private install in many headers) | **`01`** sovereign private Node | Local catalog. Not globally indexed unless the operator publishes. |
| Missing song Federation ID | Treated as Node **01** after 4.2.1 (legacy "Node 0" maps here) | Default ownership |
| Node 1+ decentralized installs | **`02` .. `FF`** (Node X) | Federated, globally indexed via Node 01 directory |

**Node 01** is sovereign and private. Lookups that omit FF stay on Node 01.  
**Node X** is federated and globally indexed. FF is present and is the only identity field that changes when the same artifact is published outward.

`FF=00` is reserved (unspecified / parse error). Do not emit `00` on new 4.2.1 IDs.

### 2.7 Federation migration rules (today vs 4.2.1)

**Today (PRD 99):** If Federation ID is missing, lookup is Node 0. If present, Node 0 directory -> Node domain -> catalog. Color math is identical on every Node. Intra-Node collisions forbidden; cross-Node color reuse allowed.

**4.2.1 addition:** Migration **rewrites only FF**. GG, LL, II, RRRRRR stay byte-identical.

```text
LUP:01-01-EN-00-000001
->
LUP:03-01-EN-00-000001
```

Same actor, same group, same language, same iteration, new federation home. Lineage edges record the old FF as provenance metadata.

### 2.8 CC-BY NAME metadata rules

PRD 16 does not currently encode Creative Commons attribution as a header scalar. Attribution today is split across `auth_user_id`, `actor_id`, title/summary, and human-facing guides.

**4.2.1 rule:** **CC-BY NAME is metadata, not part of the LUP ID.**

- Field (proposed): `cc_by_name` (string or `""`)
- Optional companion: `cc_license` (default `CC-BY-4.0` when `cc_by_name` is non-empty)
- Changing the displayed name does **not** mint a new LUP ID
- Changing the accountable human (`auth_user_id`) does **not** mint a new LUP ID
- Only FF mutation (federation) or an explicit iteration event (II) changes identity tokens

### 2.9 Identity fields vs metadata fields

| Class | Answers | Examples | May change without new object? |
|-------|---------|----------|--------------------------------|
| **Identity** | Which object, forever | `lupopedia_id`, FF, GG, LL, II, RRRRRR | Only FF on federation; II only on declared iteration |
| **Metadata** | What / how / who-human / where-on-disk | `artifact_type`, `artifact_kind`, `title`, `path_from_lupopedia_root`, `cc_by_name`, `color_hex`, Hawaiian Hermes fields, `faucet_actor_id` | Yes, under audit |

If a field can be true of a song **and** a PRD **and** a crest without changing meaning, it is a candidate identity field. If it describes medium, license, path, or mood, it is metadata.

---

## 3. Full Explanation of the Lupopedia ID System

### 3.1 Format

```text
LUP:FF-GG-LL-II-RRRRRR
```

Grammar (normative):

- Prefix `LUP:` is literal ASCII.
- Separators are ASCII hyphen.
- `FF` = exactly 2 uppercase hex digits (`01`..`FF`; `00` reserved).
- `GG` = exactly 2 uppercase hex digits (catalog namespace / group).
- `LL` = exactly 2 uppercase ISO 639-1 letters (`EN`, `HA`, `ES`, ...).
- `II` = exactly 2 uppercase hex digits (iteration; `00` = original generation).
- `RRRRRR` = exactly 6 uppercase hex digits (actor number).

Total identity is 2+2+2+2+6 structured tokens plus prefix. It is type-neutral.

### 3.2 Field semantics

**FF -- Federation ID (only mutable identity field)**  
Names the Node universe. Node 01 = sovereign private. Node X = federated public index. Moving an artifact between Nodes is a **publication event**, not a new work.

**GG -- Group ID (catalog namespace)**  
A stable namespace inside a Node. Songs, docs, crests, and atoms in the same creative catalog share GG. GG is **not** `artifact_type`. Two songs and one crest may share `GG=01`. A different catalog (another project, another label) uses another GG.

**LL -- Language**  
ISO 639-1 of the **artifact's primary linguistic surface**. A translation is either a new LL with II reset policy (declared at merge) or a linked artifact with its own LUP ID. Default for this repo remains `EN`.

**II -- Iteration**  
Versioning of the **same work**: remix, cover, editorial revision that claims lineage to the base. Base = `00`. Remix/cover increments II and **does not** change RRRRRR or GG. This matches PRD 99 remix rules, generalized to all artifacts.

**RRRRRR -- Actor**  
24-bit hex of Catalog / OS `actor_id`. This is WHY artifacts need actors: every object has an owner in the 144000-actor RGB universe (or a reserved OS actor outside the song-color band). Alignment: decimal `actor_id` 1 = `000001`.

### 3.3 Key principles

1. **Identity is universal**, not tied to artifact type.
2. **Songs are not special.**
3. Artifacts and songs share the **same** identity structure.
4. **Metadata determines type**, not the ID.
5. Federation migration changes **only FF**.
6. All other identity fields remain stable.
7. **CC-BY NAME is metadata**, not part of the ID.
8. **Node 01** is sovereign and private.
9. **Node X** is federated and globally indexed.

### 3.4 Why this system is correct, scalable, and future-proof

**Correct:** It matches how Lupopedia already thinks (actor-owned catalogs, language, iteration, federation) without making HEX color the identity of a markdown file. Color remains a song constraint under Rule 99.

**Scalable:** 256 federation IDs, 256 groups per Node, 256 iterations, and 16.7M addressable actor codes -- while **usable song-color actors stay capped at 144000**. The ID space is larger than the color universe on purpose: documents may be owned by OS actors who do not sing.

**Future-proof:** New media (reels, crests, toons, lineage graphs) add **metadata enums**, not new ID grammars. A 2030 artifact type does not require `LUP2:`. Validators stay one regex plus Rule 99 color checks **only when** `artifact_type` / `artifact_kind` says the object is a song.

**Survivable:** The ID is ASCII, fixed width, filesystem-safe, and independent of install folder name. It can live in YAML, SQL (`VARCHAR`), and filenames without Composer or JSON operators.

---

## 4. Design Goals for Header 4.2.1

### A. Unify identity

All artifacts MUST carry `lupopedia_id` in `LUP:FF-GG-LL-II-RRRRRR` form.

### B. Update the template without a song special case

- Identity fields are universal.
- Artifact type is metadata (`artifact_type` / `artifact_kind` / optional `media_kind`).
- No song-only identity schema.
- No second lineage system besides `prd_cluster` (governance read-order) + LUP ID (object identity) + `edges_toon` (graph).
- No duplicate "content_id vs song_id vs crest_id" dense keys.

### C. Clarity -- why each identity token exists

| Token | Why artifacts need it |
|-------|------------------------|
| **Actor (RRRRRR)** | Ownership, color-universe alignment, audit, "who may mutate." An unowned artifact is not Lupopedia. |
| **Lineage** | `prd_cluster` (which laws applied), `edges_toon` (what it points to), parent LUP IDs in edges -- replayable history. |
| **Iteration (II)** | Same work, new generation, without minting a false new actor or group. |
| **Language (LL)** | Locale-correct surfaces; translations stay addressable; `lupo_t()` world does not hardcode English as identity. |
| **Group ID (GG)** | Catalogs are namespaces. Wolfie's songs, Wolfie's crests, and Wolfie's atoms can share a catalog without sharing a filename. |
| **Federation ID (FF)** | Sovereignty vs global index. Private work stays on 01 until the operator federates. |

### D. Compatibility with 4.2.0

Keep fields 1-28. Add identity block as **fields 29+** OR as a required sibling map `lupopedia.identity` that validators read after the dense grid. Recommended: **sibling map** so the 28-field discovery protocol does not grow again without Captain approval of another dense expansion.

Preferred shape (does not break 25/31-line head discovery of 4.2.0):

```yaml
lupopedia.headers:
  # ... existing 28 fields ...
lupopedia.identity:
  lupopedia_id: "LUP:01-01-EN-00-000001"
  federation_id: "01"
  group_id: "01"
  language: "EN"
  iteration: "00"
  actor_hex: "000001"
```

Dense `actor_id: 1` MUST equal `actor_hex` `000001`. Dense `federation_node_id` MUST map to `federation_id` per section 2.6.

---

## 5. Updated Header Specification (4.2.1)

### 5.1 Unchanged (4.2.0 dense grid)

Fields 1-28 remain as specified in PRD 16_C section 4.2. Hawaiian keys remain forbidden in dense YAML.

### 5.2 New required identity map (`lupopedia.identity`)

| Key | Type | Required | Definition |
|-----|------|----------|------------|
| `lupopedia_id` | string | YES | Full `LUP:FF-GG-LL-II-RRRRRR` |
| `federation_id` | string(2 hex) | YES | FF; must match ID |
| `group_id` | string(2 hex) | YES | GG; catalog namespace |
| `language` | string(2) | YES | ISO 639-1 |
| `iteration` | string(2 hex) | YES | II |
| `actor_hex` | string(6 hex) | YES | RRRRRR; must equal `actor_id` |

Validators:

- Reconstruct `lupopedia_id` from parts; mismatch = ERROR (`HDR_LUP_ID_MISMATCH`).
- `int(actor_hex, 16) == actor_id` or ERROR (`HDR_LUP_ACTOR_ALIGN`).
- Map `federation_node_id` <-> `federation_id` or ERROR (`HDR_LUP_FF_ALIGN`).
- `iteration` increment without `edges_toon` parent pointer = WARN for canonical tier.

### 5.3 New / clarified metadata (not identity)

| Key | Where | Definition |
|-----|-------|------------|
| `artifact_type` / `artifact_kind` | dense 11-12 | What family of artifact |
| `media_kind` | optional metadata | `song` \| `document` \| `crest` \| `reel` \| `semantic_atom` \| `toon` \| `lineage_graph` \| `catalog` \| `other` |
| `color_hex` | metadata; required if `media_kind: song` | Rule 99 slot inside actor range |
| `cc_by_name` | metadata | Attribution display name |
| `cc_license` | metadata | License token; not part of ID |
| Hermes Hawaiian fields | `lupopedia.hermes` | OHANA..PUKA as today |

### 5.4 Lineage rules (single system)

1. Object identity = `lupopedia_id`.
2. Governance lineage = `prd_cluster` (read-order of PRDs).
3. Graph lineage = `edges_toon` / memory edges (parent LUP IDs, remix-of, derived-from).
4. Forbidden: a fourth ID column (`song_uid`, `crest_guid`, UUID).
5. Remix: same GG, LL, RRRRRR; II increments; edge `remix_of` -> prior `lupopedia_id`.
6. Federation: same GG, LL, II, RRRRRR; FF changes; edge `federated_from` -> prior `lupopedia_id`.

---

## 6. Examples (all use the same identity format)

Shared owner for these examples: WOLFIE (`actor_id` 1, `actor_hex` `000001`), group catalog `01`, English, original iteration, Node 01.

### 6.1 Song header (4.2.1)

```yaml
---
lupopedia.headers:
  header_format_version: "4.2.1"
  path_from_lupopedia_root: "music/01/01EN00000064.md"
  web_path: "https://www.lupopedia.com/lupopedia/music/01/01EN00000064.md"
  status: "active"
  when_updated: "20260811131004"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/music/wolfie-base-001"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: music
  federation_node_id: 0
  thread_key: wolfie-catalog
  lupopedia.schema: documentation
  prd_cluster: 99_A-i_16_C-i
  title: "Example base song"
  summary: "Base song in Wolfie catalog; color is metadata."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: music_catalog
  faucet_actor_id: 102
lupopedia.identity:
  lupopedia_id: "LUP:01-01-EN-00-000001"
  federation_id: "01"
  group_id: "01"
  language: "EN"
  iteration: "00"
  actor_hex: "000001"
lupopedia.metadata:
  media_kind: song
  color_hex: "000064"
  cc_by_name: "Eric Robin Gerdes"
  cc_license: "CC-BY-4.0"
---
```

Note: `color_hex: "000064"` is Wolfie's first Rule 99 slot. It is **not** copied into `RRRRRR`.

### 6.2 Document header (4.2.1)

```yaml
lupopedia.identity:
  lupopedia_id: "LUP:01-01-EN-00-000001"
  federation_id: "01"
  group_id: "01"
  language: "EN"
  iteration: "00"
  actor_hex: "000001"
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
  cc_license: "CC-BY-4.0"
```

Same LUP ID as the song **only if** they are the same catalog object (they are not). A real document mints its own iteration or, more often, a distinct work uses the next iteration policy **or** a distinct `group_id` / edge-linked child. **Default for a new document in the same catalog:** new work gets `II` next-free **or** a dedicated GG. Recommended: **one LUP ID per work**, so this document would be `LUP:01-01-EN-01-000001` if it is the next work in group 01, or `LUP:01-02-EN-00-000001` if it is a new group. Shown below as a new group to avoid colliding with the song:

```yaml
lupopedia.identity:
  lupopedia_id: "LUP:01-02-EN-00-000001"
  federation_id: "01"
  group_id: "02"
  language: "EN"
  iteration: "00"
  actor_hex: "000001"
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
```

### 6.3 Crest header (4.2.1)

```yaml
lupopedia.identity:
  lupopedia_id: "LUP:01-03-EN-00-000001"
  federation_id: "01"
  group_id: "03"
  language: "EN"
  iteration: "00"
  actor_hex: "000001"
lupopedia.metadata:
  media_kind: crest
  cc_by_name: "Eric Robin Gerdes"
```

No `color_hex` unless the crest later binds a Rule 99 slot by explicit metadata.

### 6.4 Semantic atom header (4.2.1)

```yaml
lupopedia.identity:
  lupopedia_id: "LUP:01-04-EN-00-000001"
  federation_id: "01"
  group_id: "04"
  language: "EN"
  iteration: "00"
  actor_hex: "000001"
lupopedia.metadata:
  media_kind: semantic_atom
  cc_by_name: ""
```

Atoms may omit CC-BY NAME when they are system constants (`auth_user_id` still records accountability).

### 6.5 Same format, other actors (reference)

```text
LUP:01-01-EN-00-000000   SYSTEM
LUP:01-01-EN-00-000001   WOLFIE
LUP:01-01-EN-00-000002   LILITH
LUP:01-01-EN-00-0002C1   AGAPE (705)
```

---

## 7. Federation Migration Examples

### 7.1 Private Node 01 -> federated Node 03

```text
before: LUP:01-01-EN-00-000001
after:  LUP:03-01-EN-00-000001
```

Unchanged: `01` group, `EN`, `00` iteration, `000001` Wolfie.  
Changed: `01` -> `03` (FF only).

Header delta:

```yaml
# before
federation_node_id: 0          # maps to FF 01
lupopedia.identity.federation_id: "01"
lupopedia.identity.lupopedia_id: "LUP:01-01-EN-00-000001"

# after
federation_node_id: 3          # maps to FF 03
lupopedia.identity.federation_id: "03"
lupopedia.identity.lupopedia_id: "LUP:03-01-EN-00-000001"
```

`edges_toon` MUST record `federated_from: LUP:01-01-EN-00-000001`.

### 7.2 Remix does not use FF

```text
base:  LUP:01-01-EN-00-000001
remix: LUP:01-01-EN-01-000001
```

II changes. FF does not. Color metadata (if song) stays `000064`.

### 7.3 Translation policy (declared)

Default proposal: a translation is a **new LL**, same FF/GG/II/RRRRRR only if it is the same work in another language **and** edges declare `translation_of`. Alternative (stricter): translations mint II=`00` and new LL with edge only. Captain chooses at merge. Recommendation: **same II, change LL, require `translation_of` edge.**

```text
EN: LUP:01-01-EN-00-000001
HA: LUP:01-01-HA-00-000001
```

---

## 8. Risks and Mitigations

| Risk | Mitigation |
|------|------------|
| Colliding song color `000064` with actor hex `000001` | Spec forbids treating color as RRRRRR. Validators compare `color_hex` to Rule 99 range of `actor_id`, not to `actor_hex`. |
| Breaking 4.2.0 28-field head discovery | Put LUP fields in `lupopedia.identity`, not in the dense 28. |
| Node 0 vs Node 01 confusion | Explicit map in section 2.6. Legacy missing FF => 01. Reject emitting FF `00`. |
| OS actors > 143999 claiming song colors | Allow RRRRRR for artifact ownership; reject `media_kind: song` unless `actor_id` is in `0..143999`. |
| Duplicate IDs if GG not allocated | Installer / catalog allocator issues next free GG per actor per Node. Idempotent. |
| Hawaiian fields leaking into identity | Keep 4.2.0 KAPU: `HDR_HAWAIIAN_IN_DENSE` remains ERROR. |
| Corpus rewrite cost | Dual-accept 4.2.0 without identity map (WARN) during migration; ERROR only for new 4.2.1 files. No mass rewrite in the opening bump (same policy as 4.2.0). |
| Product semver panic | Do not bump `GLOBAL_CURRENT_LUPOPEDIA_VERSION` solely for this header proposal. |

---

## 9. Final Recommendation

1. **Adopt** `LUP:FF-GG-LL-II-RRRRRR` as the single object identity for songs **and** all other artifacts.
2. **Keep** PRD 16_C 4.2.0 dense 28-field grid unchanged.
3. **Add** `lupopedia.identity` + optional `lupopedia.metadata` (`media_kind`, `color_hex`, `cc_by_name`).
4. **Treat** songs as artifacts whose metadata includes a Rule 99 `color_hex`.
5. **Mutate only FF** on federation (`LUP:01-01-EN-00-000001` -> `LUP:03-01-EN-00-000001`).
6. **Leave** Hawaiian constitutional fields in Hermes/sidecar.
7. **PRD merge is done.** Validators and runtime code are a follow-on; do not invent extra identity grammars.

This output complies with Lupopedia Constitutional Root Rules.

---

## 10. Approval gate

| Item | State |
|------|-------|
| Proposal file | This document |
| Normative merge target | `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md` |
| Companion updates after approval | PRD 99 song ID, `HOW_TO_LUPOPEDIA_A_SONG.md`, Lilith Rule 99, 16_A template, 16_D examples, 16_E migration |
| Implementation | Follow-on (validators + header adder) |
| Validator | New codes authorized after this merge |

**Captain decision:** Approved. 4.2.1 identity unification is normative.
