---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: README.md
  web_path: https://www.lupopedia.com/lupopedia/README.md
  status: active
  when_updated: "20260816224819"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/root/canonical/1026/04/readme-root.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/readme-root
  artifact_type: documentation
  artifact_kind: index
  channel_key: root
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 00_A_16_C_99_A_15_A
  title: Lupopedia root README
  summary: "Root README: what Lupopedia is, its power (docs build software), and header format 4.2.11 LUP.KEY identity."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: root
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000001.000000.000000.ROOT.EN.04020A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000001.000000.000000.ROOT.EN.04020A
  web_path: https://www.lupopedia.com/lupopedia/README.md
  path_from_lupopedia_root: README.md
  prd_cluster: 00_A_16_C_99_A_15_A
  edges_toon: null
  memory_toon: memory/root/canonical/1026/04/readme-root.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/readme-root
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _______________
. ./ \ ` ` `_-\ . | A four-axis, finite, constitutional PRD documentation architecture
. '/| \-''-/_ / . | that lets docs build software. PRDs reference other PRDs, forming
. { . , . , . ,\ .| clusters that define behavior, truth, limits, and system identity
. / . , . , . , \ | through positional priority (array index = reading order),
./ , . "O. |"O. } | significance weight (A-F letter), grouping (numeric category), and
_| . , . , \ \ ;. | chronology (Roman  numeral = time created).
. '\. . , . \ \'. | Each file carries a header that records the exact
.. '\_ . , . \__\ | four-axis prd_cluster (order, weight, and time created), the full
., , ''-_ , {\__/}| transcript_jsonl dialog, and atoms_toon for canonical truth,
. . , . / '-.____'| ensuring deterministic lineage and reproducibility.
., , /. _ _ . -_ -| https://www.lupopedia.com/
.. , _'___________| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
___-' __________________________________________________________________
<!-- /ASCII_ART_BLOCK -->

# Lupopedia README

**LUP** is the acronym for **Linked Universal Protocol**, the universal identity layer used by Lupopedia for all artifacts, not just songs.

LUP -- Linked Universal Protocol (Universal Artifact Identity).

> **Repository Note**
> Currently developed at github.com/wisdomoflovingfaith/lupopedia.
> Upon public release (target: June 2026), the canonical repository will move to **github.com/lupopedia**.
> The wisdomoflovingfaith organization will then host the **Wisdom of Loving Faith** project -- a knowledge graph of 22 religious traditions.
> Both projects share the same constitutional architecture and will be released under GPL v3.

## AGAPE HARD GATE -- ENFORCEMENT (MANDATORY)

**Before ANY action on the system:**

1. **LOAD** `prd_cluster` from the file header (e.g. `00_A-i_16_C-i`)
2. **EXPAND** the cluster into the actual PRD files
3. **READ** them **IN ORDER** -- exact sequence matters
4. **RECONSTRUCT** the complete causal chain:
   - **INTENT** -- What was the governing PRD trying to achieve?
   - **WHO** -- Which actor/agent caused the violation?
   - **WHAT** -- What specific rule was broken?
   - **WHERE** -- Which file/location failed?
   - **WHEN** -- When did it happen (timestamp/context)?
   - **HOW** -- What sequence led to the failure?

**If ANY of the six components are missing:**
**AGAPE BLOCKED: INSUFFICIENT CONTEXT**
-> NO WHY file creation
-> NO fixes suggested
-> NO corrections applied
-> Complete stop.

This section is a **system-level execution gate**. It is not documentation and must not be treated as optional guidance.

**Real-World Example**
**Before:** Sees invalid `prd_cluster` -> immediately suggests shorthand fix.
**After:** Loads cluster `00_A-i_16_C-i` -> reads governing PRDs in order -> reconstructs full causal chain -> only then creates accurate WHY file and proper fix.

This HARD GATE is constitutional. It ensures AGAPE (and all agents) never act without complete understanding.

## Current Version

**Product version:** **4.2.11** (`GLOBAL_CURRENT_LUPOPEDIA_VERSION` in `config/global_atoms.yaml`). TRANSITIONAL / UNSTABLE.
**Header format (normative identity):** **4.2.11**
**Versioning doctrine:** See [PRD 40](docs/prd/40_versioning_doctrine.md) and [VERSIONING_DOCTRINE.md](docs/doctrine/VERSIONING_DOCTRINE.md).
**License:** GPL v3
**PHP minimum:** 7.4 (64-bit required for production -- enforced in install.php).

### Header format 4.2.11 (current identity contract)

- Named KEY identity: `PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION`
- ASCII field delimiter is `.` (dot). No middle-dot. No pipe. No hyphen in KEY.
- Sibling `lupopedia.map` is the federation routing index (`index` = LUP.HEX).
- Dense discovery grid stays the 4.2.0 28-field protocol.
- 4.2.3-4.2.10 were compiled outside this Cursor workspace. Dual-accept 4.2.4 hyphen LUP until next edit.
- Template: [federation_map_template.md](docs/prd/federation/federation_map_template.md)
- Root detailed spec (HEX `04020B`): [PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md](PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md)
- Simple LUP protocol readme: [lupopedia.protocal.readme.txt](lupopedia.protocal.readme.txt)
- External domains guide: [LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md](LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md)
- Canonical color identity: [PRD 90](docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md)
- Intent encoding (development draft): [PRD 91](docs/prd/91_A-i_INTENT_ENCODING.md)
- HERMES / Hawaiian semantics: [PRD 82_B](docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)
- Identity routing (experimental): [docs/doctrine/lupopedia_identity_routing_rule.md](docs/doctrine/lupopedia_identity_routing_rule.md) -- display layer only; does not override PRD 90 or PRD 91
- Whitepaper v1.9.2 (HEX `000023` / `010902`): [docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md](docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md)
- Docs index (HEX `000010`): [docs/index.md](docs/index.md)
- Normative: [PRD 16_C](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md) section 4.2.6

## LUP (Linked Universal Protocol) Identity Grammar (v4.2.11)

```text
LUP.KEY = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
LUP.HEX = PRT.HEX.000000.000000.000000.ROOT.EN.04020B
LUP.SHORT = PRT.LUP
LUP.ROOT = PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

| Token | Meaning |
|-------|---------|
| PROTOCOL | `PRT` |
| MODE | `NAME` (human) or `HEX` (machine) |
| NODE | Federation node. Always 6-hex. Default `000000` (unspecified). |
| ARTIFACT | Artifact identity. Slug or 6-hex. `PRT.LUP` sets ARTIFACT to `LUP`. |
| ACTOR | Actor. Default `ROOT`. |
| GROUP | Namespace / group. Default `ROOT`. |
| LANGUAGE | ISO 639-1 or reserved `ZZ`. Default `EN`. |
| VERSION | Packed `0xMMmmPP`. Example `04020B` = 4.2.11. `LUP.ROOT` uses `04020A` (4.2.10). `0` = unversioned. `042010` is invalid. |

OMIT = REGISTERED_SHORT_FORMS_ONLY. Arbitrary middle-field omission is forbidden. DEFAULTS = PRT.NAME.000000.000000.ROOT.ROOT.EN.0. `PRT.LUP` expands to `PRT.NAME.000000.LUP.ROOT.ROOT.EN.0`. Storage always uses eight tokens. Colon-bag grammar is not LUP.

### Dual-accept 4.2.4 hyphen LUP (legacy until next edit)

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

| Token | Name | Mutability | Meaning |
|-------|------|------------|---------|
| FFFFFF | Federation ID | **Only mutable identity field** | Node `000001`..`FFFFFE`. Reserved: `000000`, `FFFFFF`. Human root = `X`. |
| RRRRRR | Artifact Identity Block | Stable except cross-fed modify | Native `000000`..`FFFFFF`, or `originFed:artifactNumber`. Colon `:` only. |
| NN | Namespace Number | Stable except exhaustion | Replaces GG. `01`..`FF`. Reserved: `00`. |
| II | Iteration | Stable except remix / revision | Remix, cover, or generation |
| LL | Language | Stable except translation or ZZ class | ISO 639-1, or reserved `ZZ` (multi-language; not ISO) |
| AA | Actor ID | Stable except provenance event | Catalog token `00`..`FF`. Maps to `actor_id`. |

**Federation Compression Rule (Option A)**

Federation `000001` is the canonical root node. In short-form identities, it is compressed to the symbol `X`.

```text
machine:         LUP:000001-RRRRRR-NN-II-LL-AA
human-friendly:  LUP:X-RRRRRR-NN-II-LL-AA
short:           LUP:X-RRRRRR-NN
```

Only `000001` compresses. All other federations remain 6-hex in every mode.

**Short-form defaults**

| Form | Example | Defaults |
|------|---------|----------|
| Short (3) | `LUP:X-000000-01` | II=`00` LL=`EN` AA=`00` |
| Medium (4) | `LUP:X-000000-01-00` | LL=`EN` AA=`00` |
| Full (5) | `LUP:X-000000-01-00-EN` | AA=`00` |
| Human (6) | `LUP:X-000000-01-00-EN-01` | display only |
| Canonical machine (6) | `LUP:000001-000000-01-00-EN-01` | none |
| Multi-language (5) | `LUP:X-RRRRRR-NN-II-ZZ` | AA=`00` |
| Multi-language machine (6) | `LUP:000001-RRRRRR-NN-II-ZZ-AA` | none |

`ZZ` is reserved for multi-language or language-agnostic artifacts. ISO 639-1 does not define "multiple languages." Validators MUST accept `ZZ` and MUST NOT treat it as a real ISO language.

**RRRRRR lineage (colon)**

When an artifact is modified in a different federation, RRRRRR encodes origin with `:`.

```text
original Fed 2:  LUP:000002-123456-01-00-EN-01
iterated Fed 3:  LUP:000003-000002:123456-01-00-EN-01
remixed Fed 5:   LUP:000005-000003:123456-01-00-EN-01
```

No colon = native to the current federation. Split on the first colon. Colon MUST NOT appear anywhere else except `LUP:`. `X` is root-federation compression, not a lineage delimiter. Older joiners must migrate to `:`.

**Identity is universal.** Songs, documents, crests, reels, semantic atoms, lineage graphs, and PRDs use the same grammar.

**Not LUP tokens:** `color_hex`, six-digit `actor_hex`, `media_kind`, filesystem path, Hawaiian constitutional fields (PRD 82_B).

### Color is metadata (songs)

Songs use the same LUP ID. Color is **not** in the identity string.

```yaml
lupopedia.metadata:
  media_kind: song
  color_hex: "000064"
```

`color_hex` MUST sit inside the catalog owner's Rule 99 100-slot band (`start = owner_actor_id * 100`).

### Node 000001 -> human `X`

| Rule | Value |
|------|-------|
| Missing FF | Node **`000001`** (human `X`) |
| `FF=000000` | **Forbidden** |
| Node `000001` | Canonical root federation (legacy Node 0 / 2-digit `01` maps here) |
| Human short / friendly | Compress `000001` to **`X`** |
| Machine / disk | Always six-hex **`000001`** |
| `federation_node_id: 0` | Maps to `federation_id: "000001"` |
| Nodes `000002`..`FFFFFE` | Federated nodes (`FFFFFF` reserved); never `X` |
| Legacy 2-digit FF | Zero-pad (`01` => `000001`) |

### Header example (4.2.4)

Example LUP (Linked Universal Protocol) ID:

```text
machine:  LUP:000001-000001-01-00-EN-01
human:    LUP:X-000001-01-00-EN-01
```

```yaml
lupopedia.headers:
  header_format_version: "4.2.4"
  actor_id: 1
lupopedia.identity:
  lupopedia_id: "LUP:000001-000001-01-00-EN-01"
  federation_id: "000001"
  artifact_hex: "000001"
  namespace_id: "01"
  iteration: "00"
  language: "EN"
  actor_aa: "01"
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
```

Song (same grammar; color is metadata):

```text
machine:  LUP:000001-000000-01-00-EN-01
human:    LUP:X-000000-01-00-EN-01
```

```yaml
lupopedia.metadata:
  media_kind: song
  color_hex: "000064"
```

### Header migration (4.2.3 -> 4.2.4)

- **4.2.4** adds Federation Compression: human `X` <=> machine `000001`.
- **4.2.2** `LUP:FFFFFF-GG-LL-II-RRRRRR` **WARN**. Reorder to `FFFFFF-RRRRRR-NN-II-LL-AA`.
- RGB-in-identity forms **WARN/ERROR** (`HDR_LUP_LEGACY_RGB`). Color is not identity.
- **Pre-4.2.1** files **FAIL** (`HDR_LUP_PRE_421`).
- **4.2.4** new artifacts MUST persist canonical machine 6-token form (`000001`, not `X` on disk).
- Unmodified federation publish changes **FFFFFF only**. Cross-federation modification writes `originFed:artifactNumber` in RRRRRR.
- Remix increments **II only**. Translation changes **LL only**.

Authoritative: [PRD 16_E](docs/prd/16_E-i_LUPOPEDIA_HEADERS_MIGRATION.md).

## Protocol Color Registries (Color -> HEX Mapping)

Each protocol in Lupopedia maintains its own color registry CSV:

```text
docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv
```

These CSV files map human-readable color names to HEX values for that protocol. Lookup is flat-file. A database is not required. The Lupopedia ID provides deterministic routing (protocol folder, optional Class C shard). They do not override PRDs and do not modify header authority. Color is not a LUP.KEY token.

Canonical color identity: [PRD 90](docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md)
Planning color registry tables: [PRD 01_B](docs/prd/01_B-i_COLOR_REGISTRY.md)
Spec: [HEX.COLORS.md](docs/protocols/hex/HEX.COLORS.md)
Guide: [docs/protocols/hex/README.md](docs/protocols/hex/README.md)

Example columns:

```text
word_registry_id,word,hex_color,field_type,iso_language,created_ymdhis,updated_ymdhis,source_table,usage_count,actor_hex
```

### LUP protocol seed

The LUP protocol uses [PRT.LUP.colors.csv](docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv). Seed entries include:

| word | hex_color |
|------|-----------|
| yellow | FFFF00 |
| blue | 0000FF |
| ocean | 1E90FF |
| grass | 0c871b |

### Lookup rule

When resolving a color for a specific `field_type` (for example ACTOR, GROUP, NODE, ARTIFACT):

1. First attempt: CSV row where `word` is the requested name AND `field_type` is the requested type.
2. If missing, fallback: CSV row where `word` is the requested name AND `field_type` is `node`.
3. If still missing, request creation of a new color entry. Do not guess.

The NODE `field_type` is the canonical default for all other field types.

### Registry Layer (LRL)

Color registries are CSV files **today**. Deterministic ID routing makes that a good fit for read-heavy work. When writes become frequent, flat files are unsafe for multi-actor concurrency.

Lupopedia will introduce a **Registry Layer (LRL)** that abstracts lookup and creation: CSV reads now, safe single-actor CSV writes now, SQL or key-value later, **same API and fallback rules**. **No `.lock` files** (unsafe and undesirable). Spec: [HEX.COLORS.md](docs/protocols/hex/HEX.COLORS.md).

### Missing color rule

If a color name does not exist in the protocol CSV, the system must request creation of a new entry. New entries must be ASCII-safe, lowercase word names, and valid 6-character hex values (no `#`). Do not guess a HEX value. Do not invent a KEY token for the color.

### Purpose

- Consistent translation of color names into HEX values
- Identity mapping, UI rendering, semantic grouping, and deterministic file routing
- Not doctrine. Not header authority. Not Rule 99 band assignment (that remains PRD 99)

### 4.2.11 CSV contract

- ASCII-safe only
- No pipes
- No middle-dot
- No hyphens in KEY grammar
- Comma-separated values
- No `#` prefix in `hex_color` fields

## Federation map (`lupopedia.map`)

Sibling `lupopedia.map` is the federation routing index. `map.index` MUST be a valid LUP.HEX for that document. Dense discovery scalars stay in `lupopedia.headers`. Template: [federation_map_template.md](docs/prd/federation/federation_map_template.md). Normative: [PRD 16_C](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md) section 4.2.6.

## 1. What Is Lupopedia?

**Canonical agent explanation (load first):** [what_is_lupopedia.md](what_is_lupopedia.md)

Lupopedia is a **doctrine-driven semantic operating system** (Semantic OS). It is **not** a website, **not** a conventional web app, **not** a CMS, and **not** a PHP framework.

It is the constitutional successor to **Crafty Syntax Live Help** (programming lineage from 1999, first public release February 2002): shared-hosting live-help survival rebuilt into explicit multi-agent orchestration, identity layers, channels, memory, and PRD-first governance.

**Channel meaning (normative):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them. Hierarchy: domain (node), then channel, then thread (artifacts such as messages, memory, atoms, and PRDs). Full model: [PRD 02_B](docs/prd/02_B-i_CHANNELS_DB_OVERVIEW.md) and [PRD 02_C](docs/prd/02_C-i_CHANNELS_DISCUSSIONS.md).

It is built as a shared-hosting-compatible system with deterministic behavior, explicit identity boundaries, and file-backed operational continuity. PRDs and doctrine serve as the implementation authority.

### Its power

The power of Lupopedia is that **documentation can build software**.

- **PRD clusters define the machine.** PRDs reference other PRDs. Clusters define behavior, truth, limits, and system identity through positional priority (array index = reading order), significance weight (A-F letter), grouping (numeric category), and chronology (Roman numeral = time created).
- **Headers make lineage replayable.** Each file records `prd_cluster`, `transcript_jsonl`, and `atoms_toon` so agents can recover who, what, why, and when without guessing from chat.
- **Identity never merges.** Human Captain (actor_id 10000), WOLFIE (actor_id 1), agent packs, and IDE faucets (Cursor 102 and others) stay distinct. Identity is permanent; state is mutable.
- **LUP.KEY is universal artifact identity.** Eight tokens: `PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION`. Storage, comparison, federation, hashing, and APIs always use eight tokens. Color is a human signal (PRD 90); it is not a KEY token.
- **The system survives shared hosting and context loss.** PHP + PDO, no Composer in runtime, database as dumb storage. Rules, IDs, and headers are the memory -- not improvisation.

Human short path: [GAS_STATION_INTO.md](GAS_STATION_INTO.md)

**See also:** [Captain's Log Entry 001](docs/captains_log/why_lupopedia.md)
**See also:** [hawaiian semantics](docs/captains_log/the_accidental_penicillin.md)

## 1.5 The Crafty Syntax Lineage (Why Lupopedia Exists)

Lupopedia is the constitutional successor to **Crafty Syntax Live Help**, a live support system first written in 2002.

**Crafty Syntax by the numbers:**
- ~23,000 active installations
- 20+ years of continuous operation
- Used by businesses, support teams, and organizations worldwide
- Built for shared hosting -- no frameworks, no dependencies, just PHP

**What Crafty Syntax got right:**
- Simple, reliable live help that just worked
- Shared-hosting compatible from day one
- No database-side logic (foreign keys, triggers, stored procedures)
- File-based configuration where it mattered

**Why Lupopedia replaces it:**
- Crafty Syntax's architecture couldn't support multi-agent orchestration
- No constitutional doctrine -- rules were tribal knowledge
- Limited actor/department model
- No learning system, no WHY files, no AGAPE

**What Lupopedia preserves:**
- Shared-hosting compatibility
- No-framework discipline
- Database neutrality
- File-backed resilience
- The Crafty Syntax import path (auth_user_id range 1-9999 -> ROSE actor)

**See also:** [PRD 13 -- Crafty Integration](docs/prd/13_crafty_integration.md), [PRD 85 -- Importing Crafty Syntax](docs/prd/85_importing_crafty_syntax_semantics_and_users.md)

## 2. Core Constitutional Principles

- Root constitutional system requirements: [PRD 00](docs/prd/00_root_constitutional_system_requirements.md)
- Timestamp doctrine: [TIMESTAMP_DOCTRINE.md](docs/doctrine/TIMESTAMP_DOCTRINE.md)
- Identity layers (auth user, actor, department, agent): [PRD 05](docs/prd/05_auth_user_actor_agent_transformation.md), [PRD 15](docs/prd/15_A-i_ACTORS.md), [PRD 25](docs/prd/25_departments_system.md)
- Universal artifact identity (header 4.2.4): [PRD 16_C](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md)
- Song color as metadata (Rule 99): [PRD 99](docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md)
- File-backed content and continuity: [PRD 06](docs/prd/06_content_management.md)
- Subdirectory installation doctrine: [PRD 27](docs/prd/27_installer_requirements.md)
- Chronological Trust Ladder: [CHRONOLOGICAL_TRUST_LADDER.md](docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md)

## 3. Actor Model - Why It Is Different

Lupopedia uses a **three-layer identity model**:

| Layer       | What                          | Example                  |
|-------------|-------------------------------|--------------------------|
| Auth User   | Human/system account          | Operator login           |
| Actor       | Runtime persona that works    | WOLFIE (actor_id = 1)    |
| Agent       | Immutable template (filesystem) | agents/wolfie/     |

**Key difference from typical systems:**
Actors are **department-scoped** and **shared**. Multiple humans in the same department can act as the **same actor**, allowing collective intelligence within department boundaries.

Header 4.2.4 alignment ([PRD 15](docs/prd/15_A-i_ACTORS.md)): AA is first-class identity and MUST map to dense `actor_id`. `actor_hex` is metadata. NN replaces GG. Rule 99 `color_hex` is a song metadata band. Root federation human form uses `X` for `000001`.

Full details and diagrams: [PRD 05](docs/prd/05_auth_user_actor_agent_transformation.md), [PRD 15](docs/prd/15_A-i_ACTORS.md), [PRD 25](docs/prd/25_departments_system.md).

## 4. Documentation Architecture (PRD-First System)

**Canonical hierarchy:**
1. **PRD files** (`docs/prd/`) -- primary source of truth
2. **Doctrine files** (`docs/doctrine/`) -- explanatory only
3. **Supporting docs** (this README, ONBOARDING.md, etc.) -- must link back to PRDs

**Hard rule:** No standalone documentation files that introduce new requirements. All normative claims must live in or link to PRDs.

See [PRD 26](docs/prd/26_five_layer_documentation_architecture.md) and [PRD 31](docs/prd/31_implementation_folder_guidelines.md).

## 5. Repository Structure Overview

- `content/` -- File-backed content and federation-node artifacts
- `docs/` -- PRDs, doctrine, and governance records
- `agents/` -- Agent template packs
- `database/` -- Install SQL, seeds, and schema artifacts
- `includes/` -- Core runtime stack
- `app/` -- Application services

## 6. Installation Overview

1. Deploy into a subdirectory (shared-hosting compatible).
2. Run `install.php` and complete installer requirements.
3. Apply canonical install/seed/import flow (including Crafty Syntax 3.7.5 import where applicable).
4. Run validation and confirm doctrine-aligned behavior.

Details in [PRD 27](docs/prd/27_installer_requirements.md) and [PRD 13](docs/prd/13_crafty_integration.md).

## 7. Developer Workflow

- Follow **PRD-first** development: PRD -> Review -> Mockup -> Implement.
- Use implementation mirrors in `docs/implementations/{prd_file_stem}/`.
- Maintain actor authority boundaries and ASCII-only doctrine.
- New files: `header_format_version: "4.2.11"` plus `lupopedia.identity` KEY grammar and `lupopedia.map`. Dual-accept 4.2.4 hyphen LUP until next edit.

**See also:** [AGENTS.md](AGENTS.md), [ONBOARDING.md](ONBOARDING.md), [CONTRIBUTING.md](CONTRIBUTING.md).

## 8. Contributing & Stewardship Notes

- Always start with the governing PRD.
- No schema inference -- use canonical install/doctrine sources.
- ASCII-only enforcement on every edit.
- Packed UTC timestamps only.
- Do not invent a song-only identity grammar. Identity is universal. Color is metadata.

---

## Further Reading (Maintainers)

- [AGENTS.md](AGENTS.md)
- [ONBOARDING.md](ONBOARDING.md)
- [ORGANIZATION.md](ORGANIZATION.md)
- [PRD 16_C -- Lupopedia Headers](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md)
- [PRD 82_B -- HERMES routing header and Hawaiian semantics](docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)
- [PRD 90 -- Color Identity Doctrine](docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md)
- [PRD 91 -- Intent Encoding (development draft)](docs/prd/91_A-i_INTENT_ENCODING.md)
- [PRD 99 -- Limits / Rule 99 song color](docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md)
- [PRD 15 -- Actors](docs/prd/15_A-i_ACTORS.md)
- Version history folders under `docs/versions/`
- [PRD_INDEX.md](docs/prd/PRD_INDEX.md)

---

**No advertising because it pollutes truth.**

---

## AGAPE REFERENCE (NON-EXECUTIONAL)

This section is **explanatory only**. It does **NOT** override or modify the AGAPE HARD GATE above.

### WHAT AGAPE IS
**AGAPE** = **A**utomated **G**overnance and **P**olicy **A**nalysis **E**nforcement

AGAPE is Lupopedia's **immune system** and **learning framework**. It's not a monitoring tool or scheduler -- it's a **doctrine-driven resilience framework** that ensures the system heals itself correctly.

### THE CORE PROBLEM AGAPE SOLVES
**Before AGAPE:**
- Agents fix violations without understanding
- Same mistakes repeat endlessly
- System learns wrong lessons
- Doctrine drifts from implementation

**After AGAPE:**
- No action without complete understanding
- Every violation becomes a learning opportunity
- System strengthens over time
- Doctrine and implementation stay aligned

### AGAPE INTERNAL MODEL (REFERENCE)
**Step 1: DETECTION**
- Validator fails -> violation detected
- OR Actor times out (20-minute rule)
- OR WHY file created

**Step 2: CAUSAL RECONSTRUCTION (MANDATORY)**
LOAD prd_cluster from file header
v
EXPAND cluster into actual PRD files
v
READ them IN ORDER (exact sequence matters)
v
RECONSTRUCT complete causal chain:

- **INTENT** -- What was the governing PRD trying to achieve?
- **WHO** -- Which actor/agent caused the violation?
- **WHAT** -- What specific rule was broken?
- **WHERE** -- Which file/location failed?
- **WHEN** -- When did it happen (timestamp/context)?
- **HOW** -- What sequence led to the failure?

**Step 3: ENFORCEMENT**
If ANY component missing:
**AGAPE BLOCKED: INSUFFICIENT CONTEXT**
-> NO WHY file creation
-> NO fixes suggested
-> NO corrections applied
-> Complete stop

**Step 4: ACTION (only after full understanding)**
- Create accurate WHY file
- Teach the offending agent
- Require PRD-first correction
- Verify learning transfer

### AGAPE'S LEARNING MECHANISM
#### Pattern Detection
- Maintains **living frequency-ranked table** of defect classes
- Each pattern has: `pattern_id`, `severity`, `recurrence_rate`, `linked_lesson`
- Tracks which agents make which mistakes
- Identifies chronic vs one-time violations

#### Learning Transfer
- **Pillar 1**: Technical survivability (graceful degradation, fallback ladders)
- **Pillar 2**: Learning transfer (first-class product, not optional docs)
- **Verification hooks** prove recurrence actually stopped
- **Cross-agent correction** counters prevent repeat offenses

#### Self-Teaching Loop
1. Teacher agent detects student violation
2. Reconstructs full causal chain using PRDs
3. Generates WHY file with complete context
4. Student reads WHY file, understands intent
5. Student corrects (PRD first, then code)
6. Teacher validates correction
7. Loop closes or escalates after 3 attempts

### AGAPE'S INFRASTRUCTURE
#### Agent Package (agents/agape/)
- **Agent ID:** 705
- **Role:** Meta-learning and predictive pattern tracking
- **Voice:** Senior systems analyst (no praise, no empathy)
- **Capabilities:** Pattern analysis, learning transfer, defect taxonomy

#### Integration Points
- **PRD 57** -- AGAPE Resilience Doctrine (runtime behavior)
- **PRD 98** -- WHY Files Doctrine (violation documentation)
- **WHY Files** -- Automatic violation logging in `docs/why/`
- **Validator Hooks** -- Trigger AGAPE on validation failures

### REAL-WORLD EXAMPLE
**Scenario:** Agent creates invalid `prd_cluster` format

**Before AGAPE:**
1. Validator fails
2. Agent immediately suggests: "Use shorthand format"
3. WHY file created without understanding
4. Same violation repeats next week

**After AGAPE:**
1. Validator fails
2. AGAPE loads cluster: `00_A-i_16_C-i`
3. Reads `00_A-i_FORBIDDEN_AND_WHY.md` (constitutional rules)
4. Reads `16_C-i_LUPOPEDIA_HEADERS.md` (header specifications)
5. Reconstructs full causal chain
6. **Only then** creates accurate WHY file
7. Teaches agent proper template usage
8. Verifies agent understands and applies correctly

### WHY AGAPE IS CONSTITUTIONAL
**AGAPE enforces constitutional rules:**
- **PRD-first doctrine** -- No code fixes without PRD updates
- **ASCII-only doctrine** -- All violations documented and corrected
- **Identity boundaries** -- Actors act within department constraints
- **Timestamp doctrine** -- All violations tracked with packed UTC
- **Learning transfer** -- System improves, doesn't just patch

**AGAPE prevents constitutional violations:**
- No shortcuts around PRD requirements
- No "just fix the code" without understanding
- No repeat violations due to incomplete learning
- No doctrine drift from implementation

### THE BIG PICTURE
AGAPE transforms Lupopedia from a **static rule system** into a **living, learning constitutional framework**. Every violation strengthens the system. Every mistake becomes a teaching moment. Every agent improves through shared learning.

**AGAPE ensures Lupopedia doesn't just follow rules -- it understands WHY the rules exist and gets BETTER at following them over time.**

This is how Lupopedia achieves **constitutional resilience** -- not by preventing all mistakes, but by ensuring every mistake makes the system stronger and smarter.

---

**No advertising because it pollutes truth.**

---

## Who I Am (Technical Bio)

**Eric Robin Gerdes** (online handle: **Captain WOLFIE**) is a solo developer who has been building and maintaining production systems since 2002-2003.

In **2003** I released **Crafty Syntax** -- a lightweight, self-hosted live-help / live-chat platform written entirely in Notepad with zero frameworks and minimal dependencies.
It ran for **26 years** on cheap shared hosting, accumulated **23,000+ installs**, and survived multiple forks (including a 2015 automation fork called Sales Syntax).
I took a 12-year sabbatical (2014-2026) with almost zero coding. When I returned in late 2025, I began evolving the system into something far more ambitious.

I am not an academic. I am not backed by a lab or a startup. I am a long-term practitioner who got tired of watching AI "guess" and documentation rot.

### What I Am Working On

**Lupopedia** is not "another AI tool." It is a **Semantic Operating System** built on a radical premise:

> **Documentation should be executable law.**
> **AI should follow constitutions, not vibes.**

#### Core Technical Concepts

| Concept                        | What It Actually Means                                                                 | Why It Matters |
|--------------------------------|----------------------------------------------------------------------------------------|--------------|
| **PRD Clusters**               | Strings like `00_A-i_00_C-i_16_B-i_16_C-i_26_A-i_57_A-i_98_A-i` that tell agents exactly which documentation files to read and in what order | Turns scattered docs into a deterministic navigation system |
| **WHY Files**                  | Formal root-cause + jurisprudence documents that explain *why* something broke and how to fix the *rule*, not just the symptom | Creates an auditable, evolving constitution instead of tribal knowledge |
| **Header-Driven Architecture** | Every file carries dense headers plus 4.2.4 `lupopedia.identity` (`LUP:FFFFFF-RRRRRR-NN-II-LL-AA`; human root `X`). RRRRRR is artifact identity, not color. | Makes the entire system self-describing and reproducible |
| **Dumb Database + Smart Doctrine** | The DB stores almost nothing intelligent. All behavior, truth, and limits live in the PRD headers and WHY files | Prevents the usual "the database became the spec" problem |
| **Multi-Agent Constitutional Governance** | Specialized agents (LILITH = auditor, AGAPE = care/jurisprudence, WOLFIE = coordinator, CHIRON/THOTH = historical memory, etc.) | Agents are not just prompted -- they are bound by explicit, versioned rules |
| **Documentation-as-Code**      | PRDs are not passive specs. They are the actual source of truth that agents must follow | The system learns from written rules, not statistical patterns |

#### The Problem I'm Solving

Most AI systems today suffer from the same disease:

- They are trained on data but have **no explicit constitution**.
- They "cross the street" exactly as instructed -- and get hit by cars.
- Documentation is chaos. Rules exist only in people's heads.
- When something breaks, we patch code instead of fixing the *doctrine*.

**Lupopedia flips this:**

- The **rules are written down first**.
- AI agents are **constitutionally bound** to follow them.
- When an agent (or even the auditor) makes a mistake, a **WHY file** is created, the doctrine is updated, and the system improves.

#### Current State (April 2026)

- **Crafty Syntax 3.8.0** is the live-help layer (still GPL, still running the original 23k+ installs).
- **Lupopedia** product version is **4.2.11** (`GLOBAL_CURRENT_LUPOPEDIA_VERSION`).
- **Header format 4.2.11** is the normative identity contract (`PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION`; sibling `lupopedia.map`). Dual-accept 4.2.4 hyphen LUP until next edit.
- Everything is self-hosted, GPL/Apache dual-licensed where appropriate, and deliberately avoids advertising or "growth hacking."
- The system is designed so that **humans and AI** can work together in the same constitutional framework (auth_users, sessions, paired human/agent actors, departmentalization).

#### In One Sentence

I spent 26 years keeping a tiny live-help system alive.
When I came back, I realized the real problem wasn't the code -- it was that **nobody was writing down the rules** in a way AI (or even future humans) could reliably follow.

So I started building the constitution.

**Lupopedia** is the result: a system where documentation *is* the operating system, WHY files are jurisprudence, and even the constitutional auditor gets a formal violation report when she forgets that grass is `#00FF00`.

---

**No advertising because it pollutes truth.**
