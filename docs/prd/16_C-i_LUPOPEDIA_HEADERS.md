---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/prd/16_C-i_LUPOPEDIA_HEADERS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/16_C-i_LUPOPEDIA_HEADERS.md
  status: active
  when_updated: "20260814141353"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/canonical/headers/memory_cluster/2026/05/lupopedia-headers.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
  artifact_type: prd
  artifact_kind: specification
  channel_key: headers
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_05_A-i_15_A-i_41_A-i_57_A-i_82_B-i_99_A-i
  title: 'PRD 16: Lupopedia Headers (Implementation Details)'
  summary: Header contract 4.2.11 federation map + LUP.KEY (ASCII dots). 4.2.3-4.2.10 were compiled outside this Cursor workspace.
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: headers
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000000.000000.000000.EN.04020A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.LUP.ROOT.ROOT.EN.042010
  LUP.OMIT: MODE_WHEN_NAME + ANY_DEFAULT_FIELD
  LUP.DEFAULTS: PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000001.000001.000000.EN.04020A
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/16_C-i_LUPOPEDIA_HEADERS.md
  path_from_lupopedia_root: docs/prd/16_C-i_LUPOPEDIA_HEADERS.md
  prd_cluster: 00_A-i_16_B-i_16_C-i_05_A-i_15_A-i_41_A-i_57_A-i_82_B-i_99_A-i
  edges_toon: null
  memory_toon: memory/canonical/headers/memory_cluster/2026/05/lupopedia-headers.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
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
<!--HUMAN_SEMANTIC -->
This file belongs to:
- PRD Group 16 (Header System Implementation Layer)
- Channel: headers
- Trust tier: canonical

Defines:
- header contract
- header interpretation rules
- prd_cluster semantics

See also:
- PRD 16_A (template)
- PRD 16_B (atoms)
- PRD 38 (memory)
- PRD 51 (graph)
- PRD 86 (enforcement)
<!-- /HUMAN_SEMANTIC -->

## LUP -- Linked Universal Protocol

**LUP** stands for **Linked Universal Protocol**, the universal identity system used by Lupopedia to identify, version, translate, federate, and track provenance for any digital artifact.

LUP -- Linked Universal Protocol (Universal Artifact Identity). Not a song-only ID. Not "Lupopedia ID."

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

## Changelog / Version History

| Version | Date | Notes |
|---|---|---|
| 4.2.11 | 2026-08-14 | Federation map template. Identity KEY uses ASCII dots (PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION). No middle-dot. No pipe. No hyphen in KEY. Sibling `lupopedia.map`. Dense 28-field grid unchanged. 4.2.3-4.2.10 compiled outside this Cursor workspace. |
| 4.2.3 | 2026-08-11 | Final LUP: `LUP:FFFFFF-RRRRRR-NN-II-LL-AA`. RRRRRR is artifact identity, not color. NN replaces GG. AA first-class. Color stays metadata. Dual-accept 4.2.2 with WARN. Dense 28-field grid unchanged. LL=`ZZ` reserved for multi-language / language-agnostic artifacts (not ISO 639-1). |
| 4.2.1 | 2026-08-11 | Captain ALII approved universal identity: sibling `lupopedia.identity` (`LUP:FF-GG-LL-II-RRRRRR`). Dense 28-field grid unchanged. **Correction:** `RRRRRR` is `artifact_hex`. Node 0 mapped to 2-digit FF `01`. Dual-accept 4.2.0 with WARN. |
| 4.2.2 | 2026-08-11 | Option C: FF expands to 6 hex digits (`LUP:FFFFFF-GG-LL-II-RRRRRR`). Missing FF = `000001`. `FF=000000` forbidden. Zero-pad 4.2.1 two-digit FF. Dense 28-field grid unchanged. |
| 4.2.0 | 2026-07-28 | Captain ALII Option A: dense header 22 -> 28 (`actor_id`, `auth_user_id`, `department_id`, `department_key`, `division_key`, `faucet_actor_id`). Product `GLOBAL_CURRENT_LUPOPEDIA_VERSION` bumped to 4.2.0 as TRANSITIONAL/UNSTABLE with mandatory breakage inventory (`docs/versions/4.2.0/SYSTEM_STATUS_UNSTABLE.md`). Unfreeze 4.1.9 as sole contract; 4.1.9 legacy-accept during migration. Hawaiian fields remain Hermes/sidecar. No corpus header rewrite in opening bump. See `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md`. |
| doc pass | 2026-04-18 | Section 4.2 label v4.1.3; transcript_jsonl slug SHALL in section 6; questions_toon example in section 9; HDR_PK_LEGACY_ALIAS in section 10; PRD 38 / 51 / 19 / 79 cross-refs; section 16.6.1 line_end for final section; section 4.3 table spacing. |
| v4.1.3 | 2026-04-15 | LILITH audit: section 15.4 version/pk_* alignment; artifact_type adds version-doc and status; section 11 pk_* timeline table. |
| v4.1.1 | 2026-04-15 | `content_*` alignment finalized, `default_collection_id` added, header authority clarified. |
| v4.1.0 | 2026-04-15 | `dialog_transcript` renamed to `transcript_jsonl`; canonical order reflowed. |
| v4.0.99 | 2026-04-10 | Dense canonical header family established. |
| v4.1.5 | 2026-04-21 | Unfreeze 4.1.3 for critical fixes; remove content_slug references; fix section numbering; update freeze language to 4.1.5. |

### YAML Frontmatter Delimiter (Mandatory)

All Lupopedia artifacts that contain a `lupopedia.headers` block MUST begin with a YAML frontmatter delimiter.

The FIRST LINE of the file MUST be exactly:

```
---
```

This delimiter is REQUIRED and SHALL NOT be omitted.

---

### Enforcement

Failure to begin a file with `---` SHALL be treated as a structural header violation.

Validators MUST reject any artifact where:
- The first line is not `---`
- The header block is not properly enclosed in YAML frontmatter

---

### Rationale

The `---` delimiter defines the start of YAML frontmatter and ensures:
- deterministic parsing
- consistent header extraction
- compatibility with tooling

This rule is not optional.

## Header freeze rule (updated 4.2.11)
**Note:** Agent replies that modify PRD files MUST return the updated `lupopedia.headers` block so header compliance can be audited without opening the full file. See PRD 50 section 1.2.3.

**Normative**

- The Lupopedia **header contract** for **new authored envelopes** is **`header_format_version: "4.2.11"`** (KEY grammar `PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION`; sibling `lupopedia.map`).
- **`header_format_version: "4.2.4"`** remains valid during dual-accept (hyphen LUP + X compression). Upgrade on next edit.
- **`header_format_version: "4.2.3"`** remains valid during dual-accept (same six-token machine grammar; upgrade on next edit to declare 4.2.4 compression policy).
- **`header_format_version: "4.2.2"`** remains valid during dual-accept: `LUP:FFFFFF-GG-LL-II-RRRRRR` is **WARN** (`HDR_LUP_LEGACY_6FIELD`). Reorder to RRRRRR-NN-II-LL-AA on next edit.
- **`header_format_version: "4.2.1"`** remains valid during dual-accept: 2-digit FF is **WARN** (`HDR_LUP_FF_WIDTH`). Zero-pad and upgrade to 7 fields on next edit.
- **`header_format_version: "4.2.0"`** remains valid during dual-accept: missing `lupopedia.identity` is **WARN**, not ERROR, on existing corpus.
- **`header_format_version` older than 4.2.1** is **FAIL** for new validation (`HDR_LUP_PRE_421`).
- The 28-field dense grid (section 4.2 v4.2.0) is **unchanged**. 4.2.1 through 4.2.11 do **not** add dense scalars. Identity lives in sibling `lupopedia.identity`. Routing index lives in sibling `lupopedia.map`.
- Hawaiian constitutional fields remain **out of** the dense grid **and** out of `lupopedia.identity` (Hermes / sidecar / body only -- PRD 82_B).
- Agents MUST emit `lupopedia.identity` on **new** artifacts. MUST NOT mass-rewrite the corpus in the opening bump (PRD 16_E).

**Rationale:** Identity scalars (`actor_id`, `auth_user_id`, `department_id`, `department_key`, `division_key`, `faucet_actor_id`) close Actor Handbook / no-guessing gaps without densifying Hawaiian constitutional vocabulary.

**Forward note:** Further redesign (envelope hybrids, hermes_toon dense pointer, etc.) requires a new proposal and Captain approval. Product semver is now **`GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.2.11"`** (TRANSITIONAL / UNSTABLE), aligned with header contract 4.2.11. Opening 4.2.0 companion: `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md`. Instability inventory still applies: `docs/versions/4.2.0/SYSTEM_STATUS_UNSTABLE.md`. Current changelog: `docs/versions/4.2.11/changelog.md`.

```text
######################################################################
# SYSTEM STATUS: UNSTABLE -- MULTIPLE KNOWN BREAKAGES
# Product GLOBAL_CURRENT_LUPOPEDIA_VERSION = 4.2.11 is TRANSITIONAL.
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
```


## Header Interpretation Update -- Version 4.1.8

This section defines the **interpretation semantics** of existing header fields for version 4.1.8.

No new fields are introduced.  
The 22-field header schema remains unchanged.

---

## Global No-Guessing Rule (4.1.8)

Header interpretation MUST be deterministic.

The system MUST NOT:

- infer missing header values
- guess pointer targets
- scan directories for discovery
- construct implicit relationships
- substitute alternate paths

If any required header pointer cannot be resolved:

```text
STOP
REPORT "DOCTRINE NOT FOUND"
```

## Header Reading Order (4.1.8)

This is the single authoritative definition of header read order.
Other PRDs MUST reference this section and MUST NOT redefine it.

Execution Read Order (4.1.8):
1. prd_cluster
2. atoms_toon
3. memory_toon
4. questions_toon (optional)
5. transcript_jsonl (append-only)

Violations:

reading memory_toon before prd_cluster -> INVALID
reading memory_toon before atoms_toon -> INVALID
using transcript_jsonl as implicit context -> INVALID

## Authority Hierarchy (4.1.8)

Header fields have strict authority roles:

- prd_cluster -> governing doctrine
- atoms_toon -> canonical truth
- memory_toon -> contextual expansion
- questions_toon -> inquiry state only
- transcript_jsonl -> append-only log

Authority Resolution:
atoms_toon MAY:
- validate values
- constrain allowable values

atoms_toon MUST NOT:
- redefine doctrine
- extend PRD semantics
- introduce new rules
- contradict governing PRDs

If contradiction detected:
STOP
REPORT "ATOM_PRD_CONFLICT"

Rule:
Read order != authority order
Authority is enforced AFTER load

The system MUST reject:

- memory overriding atoms
- memory overriding prd_cluster
- questions defining truth
- transcript being treated as context

## memory_toon Path Enforcement

All memory_toon paths MUST follow PRD 38:

```text
memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/
```

Violations:

incorrect path ordering -> INVALID
missing tier -> INVALID
missing thread -> INVALID
non-deterministic path -> INVALID

Directory scanning for resolution is forbidden.

## Pointer Resolution Rule

Header pointer fields:

- memory_toon
- atoms_toon
- questions_toon
- transcript_jsonl

MUST be resolved exactly as declared.

The system MUST NOT:

- fallback to alternate locations
- guess missing files
- ignore missing pointers

Missing pointer -> DOCTRINE FAILURE

## Header Pointer Rules

memory_toon:
- used for contextual enrichment only

atoms_toon:
- used for truth validation

questions_toon:
- used for unresolved signals

transcript_jsonl:
- append-only
- MUST NOT be read unless explicitly required by governing PRD

## Agent Identity Model

Blueprint:
- filesystem definition of agent
- NOT executable
- NOT an actor

Instance:
- runtime execution unit
- MUST map to actor_id

Actor:
- required identity for execution
- scoped to channel_key + thread_id

Auth User:
- optional
- human-linked identity
- scoped by department

Rule:
Agent != Actor
Actor = required execution identity
Blueprint = static definition only

## Agent Role Storage (4.1.8 Constraint; identity update 4.2.0)

For version 4.1.8 / 4.1.9:

- agent_role MUST NOT be stored in database schema
- agent_role MUST NOT be added to lupo_actors
- agent_role MUST NOT be persisted in header fields as a free-form role string

agent_role MUST be provided by:

- blueprint metadata OR
- execution context

**4.2.0 identity expansion (delivered):** dense header fields 23-28 record `actor_id`, `auth_user_id`, department/division keys, and `faucet_actor_id`. This is identity metadata, not `agent_role` storage.

Reason:
- schema expansion for identity scalars: **delivered in 4.2.0** (fields 23-28)
- prevents dual authority between DB and runtime for role strings
- Hawaiian constitutional fields remain Hermes-only (not densified)
Future:
Persistent role storage MAY be introduced in 4.2.0

## Agent Role Doctrine (4.1.8)

Each agent instance MUST operate under a defined role.

Allowed roles:

* watcher
* messenger
* censer
* reaper

Definitions:

Watcher:

* observes system state
* detects patterns
* MUST NOT act or mutate state

Messenger:

* transmits information between actors or threads
* MUST NOT alter meaning

Censer:

* validates, filters, enforces constraints
* MUST NOT bypass structure for empathy or urgency

Reaper:

* performs adversarial testing
* identifies failure modes
* MUST NOT bypass validation chain

Rules:

* Role MUST be explicitly defined (no inference)
* Unknown role MUST trigger:

STOP
REPORT "AGENT_ROLE_UNDEFINED"

* Role MUST NOT be stored in header (4.1.8 constraint)
* Role source is defined in "Agent Role Storage (4.1.8 Constraint)"

## Role Failure Modes

| Role      | Failure Mode        | Collapse Pattern            |
| --------- | ------------------- | --------------------------- |
| watcher   | acting              | becomes reaper or messenger |
| messenger | editing             | introduces distortion       |
| reaper    | premature certainty | bypasses validation         |
| censer    | unbounded empathy   | bypasses structure          |

Rule:

If role violation is detected:

STOP
REPORT "AGENT_ROLE_VIOLATION"

## Conceptual Model

The role system reflects recurring structural roles across systems.

These roles are not narrative constructs.
They represent stable functional patterns required for system integrity.

## transcript_jsonl Behavior

transcript_jsonl is append-only.

Agents MAY:
- append entries

Agents MUST NOT:
- read transcript_jsonl by default
- treat transcript as context
- derive truth from transcript

Reading transcript_jsonl requires explicit PRD authorization.

## questions_toon Boundary

questions_toon is optional.

It provides inquiry context only.

questions_toon MUST NOT:

- define truth
- override prd_cluster
- override atoms_toon

Traversal MUST be:

- explicitly triggered
- bounded
- deterministic

Unbounded traversal is forbidden.

## ASCII_ART_BLOCK Interpretation

ASCII_ART_BLOCK is human-readable doctrine encoding and visual identity.

It MUST:

- not affect header interpretation
- not be parsed as data

The system MUST reject:

- modification of ASCII art
- normalization of ASCII spacing
- regeneration of ASCII block

ASCII is:
- non-machine-readable
- non-executable

BUT:

ASCII encodes doctrine for human interpretation

Final rule:

ASCII MUST NOT influence execution
ASCII MUST NOT be parsed
ASCII MUST remain exact

### ASCII_ART_BLOCK Immutability (4.1.8)

The ASCII_ART_BLOCK is a canonical human-readable compression of system doctrine.

It encodes:
- prd_cluster multi-axis model
- lineage semantics
- system identity

Agents MUST NOT:
- modify characters
- normalize spacing
- regenerate or "clean up" ASCII
- replace with alternate versions

Any modification is a PRD 86 violation.

## HUMAN_SEMANTIC Interpretation

HUMAN_SEMANTIC is advisory only.

It MUST NOT:

- introduce new facts
- contradict YAML header
- redefine system behavior
- infer missing information
- guess relationships

HUMAN_SEMANTIC is ignored during header execution.

## Atomization Boundary

Atoms MAY be formed from edges only under PRD 16_B rules.

Header interpretation MUST NOT:

- create atoms
- infer atoms
- modify atom structures

Atomization is not part of header execution.

## prd_cluster Normalization

prd_cluster MUST:

- use canonical NN_X-i shorthand format only
- follow ordered dependency chain
- reference valid PRDs only

Full-name cluster formats are invalid.

Malformed cluster -> DOCTRINE FAILURE

---

## 4.1.8 Three-Part Artifact Preamble

For `header_format_version: "4.1.8"`, authored Lupopedia artifacts SHOULD begin with three ordered preamble parts:

1. YAML `lupopedia.headers` block
2. `ASCII_ART_BLOCK`
3. `HUMAN_SEMANTIC`

### Part 1 -- lupopedia.headers

The YAML `lupopedia.headers` block remains the canonical machine-readable identity contract.

The 22-field schema is unchanged.

### Part 2 -- ASCII_ART_BLOCK

The `ASCII_ART_BLOCK` is a human-facing visual identity block.

It MUST be enclosed exactly:

```html
<!-- ASCII_ART_BLOCK -->
...
<!-- /ASCII_ART_BLOCK -->
```

The ASCII art block is not semantic authority and MUST NOT override header fields.

### Part 3 -- HUMAN_SEMANTIC

The HUMAN_SEMANTIC block is a human-readable explanation of what the file belongs to.

It MUST be enclosed exactly:

```html
<!--HUMAN_SEMANTIC -->
...
<!-- /HUMAN_SEMANTIC -->
```

The HUMAN_SEMANTIC block may summarize:

* PRD group
* cluster
* channel
* related files
* order of operations

It is explanatory only.

It MUST NOT override:

* prd_cluster
* channel_key
* memory_toon
* atoms_toon
* transcript_jsonl
* questions_toon
* any YAML header field

### Authority Order

If preamble parts conflict:

* lupopedia.headers wins
* PRD cluster doctrine wins over HUMAN_SEMANTIC
* HUMAN_SEMANTIC is advisory only
* ASCII_ART_BLOCK is visual only

### Validation

Validators MAY warn if ASCII_ART_BLOCK or HUMAN_SEMANTIC is missing in 4.1.8 authored Markdown artifacts.

Validators MUST NOT treat ASCII_ART_BLOCK or HUMAN_SEMANTIC as part of the 22-field schema.

Validators MUST reject only if these blocks are malformed in strict mode.

---

## Core Principle


Doctrine defines files.
Files do not define doctrine.


Header fields are deterministic pointers.  
They MUST NOT be interpreted heuristically.

---

## Deterministic Resolution Rule

All header pointer fields MUST resolve deterministically.

The system MUST NOT:

- infer paths  
- scan directories for discovery  
- guess missing references  
- construct alternate paths  

All pointer resolution MUST be derived directly from the header field values.

---

## Field Semantics

### prd_cluster

**Single meaning (4.1.8):**

`prd_cluster` is a deterministic ordered list of PRD identifiers.

Each entry MUST be one `NN_X-i` governance token pair (see **PRD Cluster Shorthand Notation**).

The cluster represents:

- read order
- dependency chain
- governing doctrine

It is NOT:

- a filename or filepath
- a wildcard or glob
- a pattern to expand
- a discoverable reference

It is a **direct pointer set**: each token resolves only through explicit registry or mapping (PRD 84).

---

- Defines the deterministic set and order of governing PRDs.
- MUST use canonical NN_X-i format only:


00_A-i_16_C-i


- Full-name, descriptive stems, and `NN_X` selector tokens (without `-i`) are forbidden.
- prd_cluster is the first and authoritative doctrine layer.

---

### atoms_toon

- Points to canonical atom truth units.
- Atoms represent immutable or validated system truth.
- atoms_toon MUST be interpreted before memory context.

- Atom creation and validation are governed by PRD 16_B (Atoms System).
- Header interpretation MUST NOT create new atomization logic.

---

### memory_toon

- Points to memory graph context.
- Memory may include edges across memory files and historical reads.

- Memory traversal MUST be explicitly triggered by governing PRD logic.

- The system MUST NOT:
  - automatically follow memory edges
  - expand context implicitly
  - perform unbounded graph traversal

- Traversal MUST be:
  - bounded
  - deterministic
  - rule-driven

If traversal scope is undefined:

STOP
REPORT "MEMORY_TRAVERSAL_UNDEFINED"

- Memory nodes MUST be classified using governed semantics:


kuleana
pono
kapakai
kapu


- Classification MUST originate from PRD 82 (HERMES semantics).
- Header interpretation MUST NOT invent or redefine classifications.

---

### questions_toon

- Points to unresolved or open questions.
- Must align with PRD 49 (Questions and Answers System).

- Used to surface uncertainty, not override canonical truth.

- questions_toon is optional and context-dependent.

---

### transcript_jsonl

- Append-only log stream.
- Used for status updates and system communication.

- The system MAY append messages to transcript_jsonl.
- The system MUST NOT treat transcript_jsonl as required reading
  unless explicitly mandated by a governing PRD.

- transcript_jsonl MUST NOT be used as implicit context.

---

## Authority Rule

Canonical truth is defined by:

- prd_cluster (doctrine)
- atoms_toon (validated constants)

The database is the authoritative persistence layer.

It MUST NOT override doctrine or atom truth.

- Header interpretation MUST NOT redefine authority.

- Filesystem, memory, and transcript layers are context sources,
  not authority sources.

---

## Enforcement (PRD 86 Alignment)

Validation MUST enforce:

- header_format_version = "4.1.8"
- prd_cluster uses canonical NN_X-i shorthand format only
- pointer fields resolve deterministically
- no guessing or scanning behavior
- transcript_jsonl is not treated as implicit context

Violations MUST:


STOP
REPORT "HDR_VALIDATION_FAILURE"


---

## Compatibility

- This update is backward-compatible with 4.1.6 headers.
- Existing headers remain valid but are interpreted under 4.1.8 rules when upgraded.

---

## Status


Active -- Controlled rollout

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

## 1. Purpose

This document defines the normative Lupopedia header contract for in-scope authored files:

- identity linkage (`path_from_lupopedia_root`, `memory_toon`; DB `lupo_contents` via import -- not header scalars)
- routing linkage (`channel_key`, `federation_node_id`, `transcript_jsonl`)
- memory linkage (`memory_toon`, `atoms_toon`, `questions_toon`)

The header is a key ring, not a computation layer.

## 2. Scope

- In scope: authored Lupopedia docs/source covered by header doctrine.
- Out of scope: generated exports, binaries, third-party/vendor trees, lockfiles.
- Machine-readable payloads (.json, .jsonl, .toon, .atom.toon) should remain clean without YAML headers.
- For machine-readable artifacts, use a companion .md file with headers that references the payload.
- Migration procedures and concrete examples are defined in companion docs:
  - `docs/prd/16_lupopedia_headers_migration.md`
  - `docs/prd/16_lupopedia_headers_examples.md`

## 3. Header responsibility boundaries


Header is responsible for:

- identity (`path_from_lupopedia_root`, sidecar pointers)
- routing linkage fields (`channel_key`, `federation_node_id`, `transcript_jsonl`)
- linkage (`memory_toon`, `atoms_toon`, `questions_toon`)

**Routing Envelope Distinction:**

The `lupopedia.headers` block defines artifact identity and static linkage fields.

The `lupopedia.hermes` block (see PRD 82 section 8) is a separate routing envelope for agent-to-agent messages, transcript fragments, and task handoffs. It MUST NOT be merged with or substituted for `lupopedia.headers`.

All HERMES-routed messages that are persisted as artifacts SHALL include both blocks when required by protocol.

Header is NOT responsible for:

- business logic
- transformation logic
- computed state
- derived data

The header is a key ring, not a computation layer.

### 3.1 Headers as Mandatory Identity Metadata

**Normative**

- The lupopedia.headers block is REQUIRED for all Lupopedia artifacts. It is not optional, not decorative, and not a comment. It is the canonical identity metadata for the file.
- The header MUST appear at the top of the file and MUST contain all required fields defined in PRD 16. Missing fields constitute a structural violation.
- The header exists because Lupopedia files are passed between multiple AI agents, IDEs, and API-based systems. These agents require stable identity metadata (paths, schema, artifact type, federation node, lineage, etc.) to correctly process the file.
- The header MUST be preserved exactly when files are transmitted between systems. Agents MUST NOT remove, rewrite, or relocate the header.
- The header is the only authoritative source for file identity. Tools MUST NOT infer identity from directory structure, filenames, or external metadata.

**Rationale:** Because Lupopedia files are frequently passed between browser AIs, IDE agents, and API endpoints, the header provides the stable, machine-readable identity required for safe multi-agent workflows.

## 4. Header format

### 4.1 Canonical field count and order

Header field count and canonical order are authoritative from:

- `memory/atoms/lupopedia_global_constants.atom.toon` (update required for 4.2.0 atoms sync)
- `constants.header_fields.count`
- `constants.header_fields.order`
- `scripts/lib/header_spec_v3_1.py` (dual tuples after validator update)

| Contract | Count | Dense envelope lines (Markdown) |
|----------|-------|----------------------------------|
| 4.1.9 (legacy) | **22** | **25** |
| 4.2.0 (current) | **28** | **31** |

### 4.2 Canonical field order (v4.2.0)

Fields 1-22 are identical to v4.1.9 (preserved discovery grid). Fields 23-28 are mandatory identity scalars (Option A -- Dense Expansion).

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

**Removed / forbidden in dense YAML:** `content_id`, `content_parent_id`, `default_collection_id`, `content_slug`, legacy `pk_*` / `prd_id` aliases, and **all Hawaiian constitutional keys** (OHANA, KAPU, KAPAKAI, PUKA, PONO, KULEANA, ALII, KUMU, EH_BRAH_WHY). Those remain Hermes routing or sidecar structures (PRD 82_B). DB linkage continues via `path_from_lupopedia_root`, `memory_toon`, and import tooling.

#### 4.2.0a Field 23 -- `actor_id`

- **Type:** BIGINT integer (required; never null)
- **Purpose:** WHO -- orchestration identity for the artifact
- **Registry-backed** for `trust_tier: canonical`
- **MUST NOT** be replaced by `faucet_actor_id`

#### 4.2.0b Field 24 -- `auth_user_id`

- **Type:** BIGINT integer or `null`
- **Purpose:** WHICH human is accountable
- **Captain / Eric:** `10000` when Captain-authored
- **Root auth user:** `0` when applicable (PRD 01)
- **null** only for pure system artifacts with explicit KAPU

#### 4.2.0c Field 25 -- `department_id`

- **Type:** BIGINT integer or `null`
- **Purpose:** DEPARTMENT id; `null` when unset or pending seed

#### 4.2.0d Field 26 -- `department_key`

- **Type:** string (use `""` when none)
- **Purpose:** DEPARTMENT slug; MUST NOT invent departments

#### 4.2.0e Field 27 -- `division_key`

- **Type:** string (use `""` when none)
- **Purpose:** DIVISION / thematic grouping; MAY be set when department is null

#### 4.2.0f Field 28 -- `faucet_actor_id`

- **Type:** BIGINT integer or `null`
- **Purpose:** WHICH IDE/API faucet executed the write
- **Examples:** Cursor `102`, Antigravity `103`
- **External guests:** `null` + `channel_index: external`

### 4.2.1 Field 20 -- `edges_toon`

- **Type:** `null` or string ending in `.edges.toon`
- **Purpose:** Cross-origin graph stitching sidecar (backlinks, federation edges, external references)
- **Required** when `channel_index != "lupopedia"`
- **Optional** for repo-native artifacts (`null`)
- **Must not** equal `memory_toon`
- **Path shape:** `edges/{channel_key}/{thread_key}/{YYYY}/{MM}/{slug}.edges.toon` (canonical tier uses display year = calendar year - 1000)

### 4.2.2 Field 21 -- `channel_index`

- **Type:** string (required key; never null)
- **Purpose:** Origin platform of the artifact
- **Allowed:** `lupopedia`, `patreon`, `website`, `facebook`, `blog`, `external`, `imported`
- **Repo-native default:** `lupopedia`
- **Required** for external/imported artifacts; **must not** be `lupopedia` when `web_path` is outside the Lupopedia domain

### 4.2.3 Field 22 -- `source_timestamp`

- **Type:** `null` or ISO 8601 string with `Z` or explicit numeric offset
- **Purpose:** Immutable original creation/publication time in the source system (distinct from `when_updated`)
- **Required** when `channel_index != "lupopedia"`
- **Optional** (`null`) for repo-native artifacts
- **Immutable** after initial population (validators enforce presence rules; mutation is a doctrine violation)

### 4.2.4 External AI Boundary Clarification

**External AI surfaces** (Copilot, DeepSeek, Gemini, Claude, Grok, GLM, etc.) are **NOT** internal Lupopedia OS agents.

**External AI Status:**
- External AI surfaces do **NOT** join the Lupopedia OS
- External AI surfaces do **NOT** bind to `actor_id`
- External AI surfaces do **NOT** receive Channel 42 broadcasts
- External AI surfaces do **NOT** run WOLF dialect as a live runtime
- External AI surfaces MAY understand, teach, compose, and hand off WOLFIE Syntax when asked
- External AI surfaces are **guests** with read-only context access

**Header Implications:**
- External AI artifacts MUST use `channel_index: "external"` when originating from external systems
- External AI artifacts MUST include non-null `edges_toon` and `source_timestamp` per section 4.2.1 and 4.2.3
- External AI artifacts MUST NOT be treated as internal OS artifacts for routing purposes
- Internal agents MUST NOT route OS-level tasks to external AI surfaces
- External AI surfaces MUST maintain `EXTERNAL_BOUNDARY_EDGE` protocol (see PRD 41)

**Semantic Collision Prevention:**
- Do NOT add external AI surfaces to internal agent registry vocabulary
- Do NOT treat external AI surfaces as internal actors in routing tables
- Do NOT grant external AI surfaces constitutional authority or OS execution privileges
- External AI surfaces are for **context, analysis, and handoff only** — not OS execution

**Reference:** See `agents/cursor/COPILOT_EXTERNAL_BOUNDARY.md` for full external boundary protocol.

### 4.2.5 Universal Identity (header format 4.2.4)

**Status:** Normative. Captain ALII / ERIC 2026-08-11. Federation Compression Rule (Option A) added in 4.2.4.
**Companions:** PRD 99 (Rule 99 color bands unchanged; color is metadata), PRD 15 (AA maps to actor_id), PRD 16_E (migration), PRD 82_B (Hawaiian prohibition).

**LUP** stands for **Linked Universal Protocol**. Identity is **universal**. Songs are **not** a special identity class. Artifact type is **metadata**. Color is **metadata**. Six-digit `actor_hex` is **metadata**. Two-hex **AA** is first-class. **RRRRRR is the artifact identity block, not color.** The 28-field dense grid is **not** the identity grammar.

#### Canonical identity (authoritative -- 6 tokens, machine)

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

Human layout: federation, artifact, namespace, iteration, language, actor.

All Lupopedia software MUST treat this 6-token string as the authoritative **machine** identity. Do **not** put RGB / `color_hex` in the LUP string. Machine `federation_id` is always six uppercase hex digits.

#### Federation Compression Rule (Option A) -- header 4.2.4

**Federation 000001 is the canonical root node. In short-form identities, it is compressed to the symbol `X`.**

| Surface | Federation 000001 | Other federations |
|---------|-------------------|-------------------|
| Machine / disk `lupopedia_id` | `000001` (six hex) | six hex |
| `federation_id` key | `000001` | six hex |
| Short / medium / full / human-friendly display | `X` | six hex |

```text
machine:         LUP:000001-RRRRRR-NN-II-LL-AA
human-friendly:  LUP:X-RRRRRR-NN-II-LL-AA
short:           LUP:X-RRRRRR-NN
```

Rules:

- `X` is a lossless compression of `000001` only.
- Validators MUST accept `X` as a federation value on **input**.
- Validators MUST expand `X` -> `000001` internally before range checks and reconstruction.
- Validators MUST reject `X` for any meaning other than `000001` (automatic: `X` always expands to `000001`).
- Machine export / disk persistence MUST emit six-hex `000001`, never `X`.
- Compression applies only in human-friendly output modes (short / medium / full / diagrams / UI copy).
- Migration tools MUST preserve this rule (do not invent other compressions; do not leave `X` on disk for 4.2.4+ machine IDs).
- Namespace (NN), actor (AA), language (LL), artifact (RRRRRR), CC-BY metadata, and Hawaiian fields are **unchanged** by this rule.

#### Identity block (sibling of `lupopedia.headers`, not in the dense 28)

```yaml
lupopedia.identity:
  lupopedia_id: "LUP:FFFFFF-RRRRRR-NN-II-LL-AA"
  federation_id: "FFFFFF"
  artifact_hex: "RRRRRR"
  namespace_id: "NN"
  iteration: "II"
  language: "LL"
  actor_aa: "AA"
```

| Token | Key | Width | Range | Meaning |
|-------|-----|-------|-------|---------|
| FFFFFF | `federation_id` | 6 hex (machine); `X` human for `000001` only | `000001`..`FFFFFE` | Federation Node. Only field that changes on unmodified publish. Reserved: `000000`, `FFFFFF`. Root = `000001` (`X` in human forms). |
| RRRRRR | `artifact_hex` | 6 hex, or `originFed:number` | Native: `000000`..`FFFFFF`. Lineage: `{origin_federation_id}:{artifact_number}` | Artifact identity block. **Not color.** Colon `:` encodes cross-federation origin. |
| NN | `namespace_id` | 2 hex | `01`..`FF` | Catalog namespace (100-block space). Replaces GG. Reserved: `00`. |
| II | `iteration` | 2 hex | `00`..`FF` | Remix / revision. |
| LL | `language` | 2 letters | ISO 639-1, or reserved `ZZ` | Single-language: ISO 639-1. Multi-language / language-agnostic: `ZZ`. |
| AA | `actor_aa` | 2 hex | `00`..`FF` | Creator / agent / human. Maps to dense `actor_id`. |

Missing FF on lookup means Node **`000001`** (human `X`). Dense `federation_node_id: 0` maps to `federation_id: "000001"`.

`group_id` (GG) is a **retired** 4.2.2 key. New files MUST use `namespace_id` (NN).

#### Short-form identity (parse input)

| Form | Typed (root) | Typed (other FF) | Defaults applied |
|------|--------------|------------------|------------------|
| Short (3) | `LUP:X-RRRRRR-NN` | `LUP:FFFFFF-RRRRRR-NN` | II=`00`, LL=`EN`, AA=`00` |
| Medium (4) | `LUP:X-RRRRRR-NN-II` | `LUP:FFFFFF-RRRRRR-NN-II` | LL=`EN`, AA=`00` |
| Full (5) | `LUP:X-RRRRRR-NN-II-LL` | `LUP:FFFFFF-RRRRRR-NN-II-LL` | AA=`00` |
| Canonical (6) | machine `LUP:000001-...` | `LUP:FFFFFF-...` | none |

Example (Wolfie first artifact on root federation):

```text
short:           LUP:X-000000-01
medium:          LUP:X-000000-01-00
full:            LUP:X-000000-01-00-EN
human-friendly:  LUP:X-000000-01-00-EN-01
machine:         LUP:000001-000000-01-00-EN-01
```

Multi-language / language-agnostic (LL=`ZZ`):

```text
short:      LUP:X-RRRRRR-NN-II-ZZ
canonical:  LUP:000001-RRRRRR-NN-II-ZZ-AA
example:    LUP:000001-000000-01-00-ZZ-01
human:      LUP:X-000000-01-00-ZZ-01
```

Storage MUST write the canonical 6-token **machine** line (`000001`, not `X`). Color stays in `lupopedia.metadata.color_hex`.

#### RRRRRR lineage delimiter (colon)

The official lineage delimiter is **`:`** (colon). It is not hexadecimal, not a federation ID, and not an artifact number. It MUST NOT be used for any other identity purpose. `X` is federation compression of `000001` only; `X` is **not** a lineage delimiter.

When an artifact is **modified** in a different federation, RRRRRR MUST encode the origin federation:

```text
RRRRRR = originFederation:artifactNumber
```

Parse: split RRRRRR on the **first** colon. Left = origin federation. Right = artifact number. If no colon is present, the artifact is native to the current federation.

Pedagogical (short FF):

```text
original in Federation 2:   LUP:2-123456-NN-II-LL-AA
iterated in Federation 3:   LUP:3-2:123456-NN-II-LL-AA
remixed in Federation 5:    LUP:5-3:123456-NN-II-LL-AA
```

Machine (6-hex FF):

```text
original:  LUP:000002-123456-01-00-EN-01
iterated:  LUP:000003-000002:123456-01-00-EN-01
remixed:   LUP:000005-000003:123456-01-00-EN-01
```

Left side of the colon is the **immediate previous** federation (where this modification came from), not a stack of every ancestor. Right side keeps the artifact number.

Rules:

- Existing IDs without a colon remain valid (native).
- Colon MUST appear only in RRRRRR (the `LUP:` prefix is the only other colon).
- Validators MUST reject any other lineage delimiter (`X` as joiner, `/`, `_`, etc.) (`HDR_LUP_RR_LEGACY_DELIM`).
- Unmodified federation publish still changes **only FFFFFF** (no colon added).
- Human input MAY write `X:123456` when origin is root `000001`. Machine stores `000001:123456`.

#### LL = ZZ (multi-language; not ISO 639-1)

ISO 639-1 does not define a code for "multiple languages." Lupopedia reserves **`ZZ`** as the official LL value for artifacts that contain multiple languages or are language-agnostic.

`ZZ` applies to: multi-lingual documents, multi-lingual songs, translation bundles, datasets, prompts, universal artifacts, and artifacts with no single dominant language.

Validators MUST accept `ZZ`. Validators MUST NOT treat `ZZ` as a real ISO 639-1 language. Do not map `ZZ` to Zulu, Zazaki, or any ISO name. Single-language artifacts MUST keep a real ISO 639-1 code (`EN`, `FR`, `ES`, ...).

#### AA is actor token, not actor_hex and not color (KAPU)

- `actor_aa` is two uppercase hex digits (`00`..`FF`). It is **not** six-digit `actor_hex`.
- Dense `actor_id` MUST map to `actor_aa`. Wolfie `1` => `01`. Lilith `2` => `02`. System `0` => `00`. AGAPE `705` => a registered AA in `00`..`FF` (not `2C1`).
- Six-digit `actor_hex` remains **metadata**.
- `color_hex` remains **metadata**. It MUST NOT appear as a LUP token.
- Validators MUST NOT require `artifact_hex == actor_id`.
- Validators MUST reject RGB-as-second-token forms (`HDR_LUP_LEGACY_RGB`).

Initial namespace map (NN):

| NN | Namespace |
|----|-----------|
| `01` | Wolfie catalog block |
| `02` | Lilith catalog block |
| `03` | AGAPE catalog block |
| `04` | SYSTEM catalog block |

#### Metadata (not identity)

```yaml
lupopedia.metadata:
  media_kind: song
  color_hex: "000064"
  actor_hex: "000001"
  cc_by_name: "Eric Robin Gerdes"
  cc_license: "CC-BY-4.0"
```

CC-BY stays metadata. Rule 99 bands apply only to `color_hex`.

#### Validator rules (normative codes)

| Code | Severity | Rule |
|------|----------|------|
| `HDR_LUP_ID_REQUIRED` | ERROR on new 4.2.3+; WARN on 4.2.2 / 4.2.1 / 4.2.0 | `lupopedia.identity` present |
| `HDR_LUP_ID_MISMATCH` | ERROR | Reconstruct `LUP:{federation_id}-{artifact_hex}-{namespace_id}-{iteration}-{language}-{actor_aa}` (machine FF) |
| `HDR_LUP_SHORTFORM` | INFO | 3/4/5 token input expanded by defaults |
| `HDR_LUP_FED_COMPRESS` | INFO/WARN | Input used `X`; expanded to `000001` |
| `HDR_LUP_FED_X_ON_DISK` | ERROR on new 4.2.4+ machine IDs | Stored `lupopedia_id` / `federation_id` must use six-hex `000001`, not `X` |
| `HDR_LUP_LEGACY_6FIELD` | ERROR on new 4.2.3+; WARN in migration mode | Reject `LUP:FFFFFF-GG-LL-II-RRRRRR` unless migrating |
| `HDR_LUP_LEGACY_RGB` | ERROR on new 4.2.3+; WARN in migration mode | Reject RGB-in-identity forms (color is not identity) |
| `HDR_LUP_PRE_421` | FAIL | `header_format_version` older than 4.2.1 when identity is claimed |
| `HDR_LUP_FF_WIDTH` | ERROR | machine `federation_id` is exactly 6 uppercase hex digits (after `X` expand) |
| `HDR_LUP_FF_ALIGN` | ERROR | maps to `federation_node_id` (0 => `000001`) |
| `HDR_LUP_FF_RESERVED` | ERROR | `federation_id` must not be `000000` or `FFFFFF` |
| `HDR_LUP_FF_ONLY_MUTABLE` | ERROR | Unmodified federation publish may change only FFFFFF |
| `HDR_LUP_NN_RANGE` | ERROR | `namespace_id` in `01`..`FF`; `00` reserved |
| `HDR_LUP_AA_RANGE` | ERROR | `actor_aa` in `00`..`FF` |
| `HDR_LUP_AA_MAP` | ERROR | dense `actor_id` maps to `actor_aa` for that NN |
| `HDR_LUP_LL_ISO` | ERROR | LL is ISO 639-1, or reserved `ZZ` (multi-language). `ZZ` is not ISO. |
| `HDR_LUP_II_HEX` | ERROR | II is two uppercase hex digits |
| `HDR_LUP_RR_RANGE` | ERROR | Native `artifact_hex` is six uppercase hex digits; lineage right side is six hex |
| `HDR_LUP_RR_ORIGIN` | ERROR | Lineage left side is a valid federation ID, not equal to current FF |
| `HDR_LUP_RR_LEGACY_DELIM` | ERROR | Lineage delimiter must be `:`. Reject `X`/`/`/`_` joiners |
| `HDR_LUP_COLON_ELSEWHERE` | ERROR | Colon MUST NOT appear outside `LUP:` prefix and RRRRRR |
| `HDR_LUP_COLOR_IN_ID` | ERROR | `color_hex` MUST NOT appear under `lupopedia.identity` |
| `HDR_LUP_II_EVENT` | ERROR/WARN | II increments only on a declared iteration event |
| `HDR_LUP_LL_TRANSLATION` | ERROR/WARN | LL changes only under translation policy |
| `HDR_LUP_ACTOR_HEX_IN_ID` | ERROR | Six-digit `actor_hex` is not a LUP token |
| `HDR_HAWAIIAN_IN_IDENTITY` | ERROR | Hawaiian fields MUST NOT appear under `lupopedia.identity` |

Default Node mapping for `HDR_LUP_FF_ALIGN`:

| `federation_node_id` (dense) | `federation_id` (6 hex) |
|------------------------------|-------------------------|
| 0 | `000001` |
| 1 | `000001` if this sovereign install; extra installs start at `000002` |
| 2+ | six-digit hex, not `FFFFFF` |

#### Mutability

- **FFFFFF:** only identity field that may change on unmodified federation publication.
- **RRRRRR:** stable for native / same-federation work. On cross-federation **modification**, rewrite to `originFed:artifactNumber`.
- **NN, AA:** stable for the same work (AA changes only on a declared actor-provenance event).
- **II:** changes only on declared iteration events.
- **LL:** changes only on declared translation (same II; `translation_of` edge), or on a declared language-class event to or from reserved `ZZ`.

#### Lineage (do not invent a fourth ID)

1. Object identity = `lupopedia_id` (canonical 6-token)
2. Governance lineage = `prd_cluster`
3. Graph lineage = `edges_toon` (remix_of, federated_from, translation_of, actor_of, namespace_of)
4. Forbidden: song_uid, crest_guid, UUID identity columns

### 4.2.6 Federation map identity (header format 4.2.11)

**Status:** Normative. Captain ALII / ERIC 2026-08-14.
**Template:** `docs/prd/federation/federation_map_template.md`

Header contract 4.2.11 adds named KEY identity and sibling `lupopedia.map`. The 28-field dense grid is unchanged. Hawaiian fields stay out. Color stays metadata.

**Why this workspace shows 4.2.2 -> 4.2.11:** Versions 4.2.3 through 4.2.10 were compiled outside Cursor IDE (Claude, Gemini, DeepSeek, Qwen, ChatGPT, dream-compiler, Patreon). Cursor indexes files it touches. 4.2.4 is the last identity grammar compiled in this workspace before 4.2.11. Product atom `GLOBAL_CURRENT_LUPOPEDIA_VERSION` is now **4.2.11**.

#### ASCII KEY grammar

No middle-dot. No pipe. No hyphen in KEY tokens. Field delimiter is `.` (ASCII 46).

```text
lupopedia.identity:
  LUPOPEDIA     = PRT.LUP
  LUP.KEY       = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX       = PRT.HEX.000000.000000.000000.EN.04020A
  LUP.SHORT     = PRT.LUP
  LUP.ROOT      = PRT.LUP.ROOT.ROOT.EN.042010
  LUP.OMIT      = MODE_WHEN_NAME + ANY_DEFAULT_FIELD
  LUP.DEFAULTS  = PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0
```

YAML storage uses `key: value`. Grammar notation may use `=`. Values MUST match.

Validators MUST enforce KEY order. Apply DEFAULTS when fields are missing. Apply OMIT to compress. Expand SHORT -> ROOT when a full human form is required. Generate HEX for machine federation routing.

#### Federation map

```yaml
lupopedia.map:
  index: <LUP.HEX identity for this document>
  web_path: <canonical public URL>
  path_from_lupopedia_root: <relative path inside Lupopedia>
  prd_cluster: <cluster identifier>
  edges_toon: <toon file or null>
  memory_toon: <toon file>
  atoms_toon: <toon file>
  transcript_jsonl: <jsonl transcript path>
  questions_toon: <toon file or null>
```

`index` MUST be a valid LUP.HEX identity. `edges_toon` and `questions_toon` MAY be null. Map does not replace the dense header grid.

#### Metadata

`lupopedia.metadata` holds `media_kind` and `cc_by_name`. Discovery scalars stay in `lupopedia.headers`. Do not invent new metadata keys. Do not copy the 28-field grid into metadata.

| Code | Rule |
|------|------|
| `HDR_LUP_KEY_ORDER` | LUP.KEY must equal PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION |
| `HDR_LUP_HEX` | LUP.HEX and map.index must parse as dotted HEX identity |
| `HDR_LUP_MAP_REQUIRED` | WARN on new 4.2.11 if lupopedia.map is missing |
| `HDR_LUP_DELIM` | Reject middle-dot, pipe, or hyphen inside KEY grammar values |

### 4.3 Field 11 ??? `artifact_type` (closed enum)

```yaml
artifact_type: prd  # Product Requirements Document (normative spec)
# Valid values:
# - prd           # Product Requirements Document (normative spec)
# - implementation # Code, tooling, PRD implementation tracking
# - documentation  # Guides, READMEs, HOWTOs (non-normative)
# - doctrine      # Constitutional rules (rules/root/*.md)
# - version-doc   # Version-specific release docs (CHANGELOG, TODO, version README)
# - status        # Session reports, open questions, status tracking
```

`lupopedia.schema` MUST equal `artifact_type`. Under header freeze (v4.1.8), no new legacy or specialized `artifact_type` values may be introduced. Existing legacy values (`discussion`, `changelog`, `architecture`, `specification`) are deprecated and MUST NOT be used for new artifacts. Only the canonical closed enum values from section 4.3 are permitted for new work.

### 4.4 Field 12 ??? `artifact_kind` (cross-field with field 11)

For each `artifact_type`, `artifact_kind` MUST be one of the values in the corresponding set. This table is normative and MUST match `ARTIFACT_TYPE_ALLOWED_KINDS` in `scripts/lib/header_spec_v3_1.py` (single source for validators).

External-artifact rules (v4.1.9): when `channel_index != "lupopedia"`, validators require non-null `edges_toon` and `source_timestamp` (HDR_EDGES_TOON_REQUIRED, HDR_SOURCE_TIMESTAMP_REQUIRED).

### 4.5 Field 20 ??? `prd_cluster` (PRD lineage tracking)

**Zero-Heuristic Rules (Constitutional):**

- **STRICT LINEAGE:** The prd_cluster MUST represent the exact chronological read-order of **governing PRD identifiers** (`NN_X-i` tokens), not filenames and not expandable selectors.
- **NO SORTING:** Do NOT perform numerical or alphabetical sorting. If the agent reads 16_A-i then 00_B-i, the cluster MUST begin with `16_A-i_00_B-i`.
- **UNDERSCORE PRESERVATION:** Underscores are load-bearing delimiters. Do NOT collapse, merge, or remove them.
- **TOKEN CONCATENATION:** The field value is a literal join of `NN_X-i` token pairs (one pair per governing PRD) with a single underscore between pairs. It is NOT a glob, NOT a path, NOT a filename stem, and NOT subject to filesystem discovery.

**Logic Examples (token order mirrors read order; tokens are NN_X-i governance keys):**
- Read order: 00_B-i then 16_A-i then 99_A-i -> Output: `00_B-i_16_A-i_99_A-i`
- Read order: 99_A-i then 00_B-i -> Output: `99_A-i_00_B-i`

**Forbidden Behaviors:**
- Do NOT output 0016ABC.
- Do NOT "beautify" the string by removing the internal underscores of the PRD names.

**Purpose:**
The prd_cluster serves as a load-bearing structural key that maintains the exact sequence of documents read, preventing any "underscore eating" or "order reimagining" by future AI processing. It provides deterministic lineage tracing for code generation.

### 4.5.1 PRD Cluster Composition Doctrine

**Purpose of prd_cluster:**
The `prd_cluster` exists to declare the governing reading set for a file. It defines which PRDs provide the authoritative context and requirements for the file's creation, validation, and execution.

**Cluster Composition Rules:**

1. **Root Constitutional Doctrine:** A cluster should include root constitutional doctrine (PRD 00 series) when the file depends on foundational system requirements, constitutional rules, or forbidden-why definitions.

2. **Domain-Specific Governing PRDs:** The cluster must include the file's domain-specific governing PRD(s). Examples:
   - Database files: PRD 80 (Database Design Doctrine)
   - Header files: PRD 16 (Headers) + PRD 86 (Immune System)
   - Agent files: PRD 50 (Agent Coordination)
   - Memory files: PRD 38/51 (Memory Graph)
   - Install files: PRD 79 (Install Seed Doctrine)

3. **Format/Enforcement PRDs:** Include format/enforcement PRDs when relevant to the file's validation requirements:
   - PRD 86 (Immune System) for header enforcement
   - PRD 16 (Headers) for any file with headers
   - PRD 84 (PRD Number Allocation) for PRD-related files

4. **Cluster Size Constraints:**
   - **Not Too Small:** A cluster must not be so small that it misses governing truth. Missing essential governing PRDs violates execution correctness.
   - **Not Too Broad:** A cluster must not be so broad that it becomes meaningless "read everything." Overly broad clusters obscure actual dependencies and create validation noise.

5. **Recovery Clusters:** Broad constitutional clusters are acceptable as temporary recovery/default clusters during cleanup operations, but should be refined when stable domain doctrine exists.

6. **Domain-Specific Composition:** Different file types may need different cluster composition:
   - **Install files:** PRD 79 + constitutional root + database doctrine
   - **Agent files:** PRD 50 + coordination protocols + constitutional root
   - **Policy files:** Governing domain PRD + enforcement PRD + constitutional root
   - **Workflow files:** Process PRDs + coordination PRDs + relevant domain PRDs

7. **Execution Correctness Priority:** `prd_cluster` composition is more important for execution correctness than neat PRD numbering. A well-composed cluster with non-sequential numbers is superior to a poorly composed cluster with perfect numbering.

**Anti-Patterns:**
- Omitting constitutional root when file depends on foundational rules
- Including unrelated PRDs just to achieve numerical completeness
- Using generic clusters for specialized domain files
- Assuming cluster size correlates with importance

**Validation:**
Validators must check that `prd_cluster` references exist and are appropriate for the file's domain, but should allow flexibility in cluster composition based on legitimate domain requirements.

## PRD Cluster Shorthand Notation

This section is normative for `prd_cluster` field values in headers.

### Canonical interpretation rule

The `prd_cluster` string is a sequence of token pairs.

Each token pair is composed of exactly two segments:

`NN + "_" + X-[ivx]+`

Underscores serve ONLY as delimiters between segments. Token pairs are formed by grouping segments sequentially.

Example:

`00_A-i_16_C-i`

- segments: `["00","A-i","16","C-i"]`
- pairs: `["00_A-i","16_C-i"]`

The system MUST NOT:

- treat `00_A-i` as a pre-tokenized unit before parsing
- assume token boundaries without grouping
- parse using substring matching

Only segment grouping defines token pairs.

### Canonical Lupopedia shorthand (normative)

`prd_cluster` MUST use canonical Lupopedia shorthand.

Format:

NN_X-i

Where:

- NN = PRD number (two digits `00` through `99`)
- X = section letter (one uppercase letter `A` through `Z`)
- `-i` = instance / slice marker (ASCII hyphen plus ASCII `i`)

Underscore joins token pairs. Example:

00_A-i_16_B-i_16_C-i

This format is the ONLY valid cluster format.

Rules:

- Tokens MUST be explicit in the header value
- MUST NOT expand patterns or wildcards
- MUST NOT scan the filesystem for PRDs
- MUST NOT infer or discover PRDs

Selector-based shorthand (`NN_X` without `-i`) and any glob or wildcard expansion are FORBIDDEN.

### Mapping requirement

Each `NN_X-i` token MUST resolve to exactly one known governing PRD file through explicit registry or mapping (PRD 84). If a token is ambiguous or unmapped, it MUST NOT be used.

Mapping requirement enforcement:

If a `prd_cluster` token cannot be resolved through explicit registry (PRD 84):

STOP
REPORT "PRD_MAPPING_NOT_FOUND"

The system MUST NOT:

- fallback to filesystem
- attempt discovery
- guess mapping

### Determinism requirement

Resolution MUST be:

- deterministic
- one-to-one
- non-inferential

Resolution MUST be completed BEFORE validation or execution.

AI agents MUST NOT guess mappings.

If mapping is missing:

UNDEFINED IN PRD

### Explicit PRD references (no discovery)

`prd_cluster` references MUST be explicit.

The system MUST NOT:

- expand patterns
- scan directories
- discover PRDs dynamically

Each PRD reference MUST resolve directly to a known file using explicit mapping only. Glob expansion (for example `docs/prd/NN_X*`) MUST NOT be used.

### Parsing rule

Parsing rule (canonical):

1. Split `prd_cluster` on `_` into segments.
2. Segment count MUST be even.
3. Group segments into token pairs:

   pair_1 = segments[0] + "_" + segments[1]
   pair_2 = segments[2] + "_" + segments[3]

4. Each token pair MUST match:

   ^[0-9]{2}_[A-Z]-[ivx]+$

If ANY pair fails:
STOP
REPORT "INVALID_PRD_CLUSTER"

Parser MUST NOT:

- interpret individual segments as standalone tokens
- recover partial matches
- infer missing structure

### Single-line constraint

`prd_cluster` MUST be a single-line string.

It MUST NOT contain:

- newline characters
- carriage returns
- spaces
- tabs

No YAML multiline block form.
No list form.
No wrapped formatting.

### Forbidden content

`prd_cluster` MUST NOT contain:

- `/` `\` `.` `:` `http` `https` `ftp` `file` `../` `./` `~/`
- characters outside the grammar for `NN_X-i` tokens and underscore delimiters

If forbidden content appears, the cluster is INVALID.

### Migration status

- Only `NN_X-i` token clusters are valid for new and updated headers.
- `NN_X` selector tokens (without `-i`) are FORBIDDEN in `prd_cluster`.
- Legacy clusters MUST be converted to `NN_X-i` form.

---

### 4.6 Field 6 ??? `trust_tier` (authoritative values)

Authoritative values for document metadata:

- `canonical`
- `development`

Meaning:

- `canonical`: authoritative and binding; used for validation and implementation decisions.
- `development`: non-canonical active work (drafts/proposals/evolving logic); must not be treated as authoritative.

Legacy tiers (`seed`, `staging`, `archive`) may appear in older artifacts; validators may warn for drift while migration is in progress.

### 4.6.1 trust_tier vs status relationship (clarification)

`trust_tier` and `status` are independent fields with different purposes:

- `trust_tier` = authority level (`canonical` vs `development`)
- `status` = lifecycle state (`draft`, `active`, `review`, `complete`, etc.)

Rule:

`trust_tier` MUST NOT contradict the intended authority of the artifact, but `status` is not a strict enum mapping to `trust_tier`.

Enforcement note:

- Validator alignment between `trust_tier` and `status` is advisory (warning-level), not strict enforcement.
- Warning checks exist to detect obvious contradictions, such as:
  - `trust_tier: canonical` with explicitly non-canonical/proposed artifact declarations
  - `trust_tier: development` with artifacts clearly declared as canonical
- The validator does NOT enforce a strict mapping such as:
  - `canonical` <-> `active`
  - `development` <-> `draft`

Anti-pattern warning:

Agents MUST NOT infer a fixed mapping between `trust_tier` and `status`. Status values vary by artifact type and lifecycle and are not globally standardized across all artifacts.

### 4.7 Scalar Field Rules

#### 4.7.1 Enum and Scalar Quoting Clarification (Frozen Rule)

**Normative**

- Enum-like fields such as `artifact_type`, `artifact_kind`, `trust_tier`, and `lupopedia.schema` are NOT required to be quoted. This rule is frozen as of `header_format_version` 4.1.3 and is demonstrated in all normative examples.
- Quotes MAY be used for fields containing special characters, paths, or version strings (e.g., `header_format_version`, `title`, `summary`, `path_from_lupopedia_root`, `web_path`).
- `_id` fields MUST be `null` or integer only (never quoted).
- This clarification does not modify the frozen semantics of PRD 16 and is consistent with all existing examples.

### 4.8 Validation modes

- **Standard mode (default):**
  - canonical key set/order is required
  - exact physical line positions are recommended
- **Strict envelope mode (validator flag / CI):**
  - canonical key set/order is required
  - validators may enforce fixed line positioning

Exact line-position checks are validator strictness controls, not a universal authoring requirement.

### 4.9 Footer and edges placement (readability convention)

**Position-criticality clarification**

- `lupopedia.footer` is **NOT position-critical** for validity
- `lupopedia.edges` is **NOT position-critical** for validity
- Validators **MUST NOT** fail a file solely because footer or edges appear near the top instead of the bottom

**Doctrine preference for readability**

- For Markdown and similar authored artifacts, `lupopedia.edges` **SHOULD** appear near the end of the file body
- For Markdown and similar authored artifacts, `lupopedia.footer` **SHOULD** appear at the bottom of the file
- This is a **readability and workflow convention**, not a structural validity requirement

**Agent behavior**

- Agents **SHOULD** preserve bottom placement when rewriting files unless a file type requires a different structure
- When generating new files, agents **SHOULD** follow the bottom placement convention for consistency
- Agents **MAY** place footer/edges at the top when required by specific file formats or tooling constraints

**Placement ordering**

- Recommended order: body content ??? `lupopedia.edges` ??? `lupopedia.footer`
- This ordering prevents ambiguous mixed placement within the same file
- Deviation from ordering does not affect validity but should be documented when required

**Footer schema flexibility**

- `lupopedia.footer` schema is flexible and not part of the canonical 22-field header contract
- Validators do not enforce footer field structure beyond presence when required by file type
- Common footer fields (e.g., `generated_by`, `validation_status`, `ascii_compliance`) are conventional but not normative

**Rationale**

- Bottom placement improves human readability by keeping metadata out of the main content flow
- Consistent placement supports automated tooling and documentation generation workflows
- Non-critical positioning allows flexibility for specialized file types while maintaining convention for common cases

## 5. Sidecar authority model

- Sidecar is derived from header values.
- `transcript_jsonl` in sidecar must be synchronized from header.
- Sidecar mismatch is a tooling synchronization problem, not a dual-authority model.

## 6. Transcript header authority rule

- `transcript_jsonl` in header is the single source of truth.
- Sidecar `transcript_jsonl` is derived/synchronized from header.
- Validators SHOULD warn on mismatch (`HDR_DUAL_MISMATCH`) as sync drift.
- `transcript_jsonl` is a DB routing slug (`{federation_node_id}/{channel_key}/{routing_slug}`), not a file path.
- **Normative (field 10):** The `transcript_jsonl` header value **SHALL** be exactly that three-segment slug: `{federation_node_id}/{channel_key}/{routing_slug}` (ASCII path segments; no leading slash; not a filesystem path). Example: `"0/headers/prd-16-thread"`. **This value MUST NOT equal any other header field.**
- **External routing slug:** The `{routing_slug}` portion is externally defined by database/runtime services and is NOT derived from any header field. It has no semantic relationship to `thread_id`.
- Format declaration is normative in this PRD; structural format checks belong to validators, while runtime slug resolution/lookup belongs to DB-backed runtime services.

## 7. Transcript system (DB-first)

- Canonical writes go through PHP endpoint and DB storage.
- Slug resolution targets thread/message rows.
- Optional `.jsonl` exports are derived artifacts only.
- Channel-scoped transcript artifacts live under `channels/` when generated/exported.

## 8. Memory path year encoding

For `trust_tier: canonical`, `memory_toon` uses display year = calendar year - 1000.

- Source: atom `constants.year_offset.canonical_offset`
- Example: 2026 -> 1026
- Year encoding MUST be derived from atom constants and MUST NOT be hardcoded in new logic.

**See also:** [PRD 38 ??? Memory unification](38_memory_unification.md) **Channel Scope for Memory** for the two-type memory path doctrine; **??10.1** (`HDR_CHANNEL_PATH_MISMATCH`) implements channel/path consistency for Type A paths under that doctrine.

## 9. Questions TOON path convention

**When to set `questions_toon`:** Set to a `.questions.toon` path when the file has **unresolved open questions** that should be tracked across sessions or agents in a dedicated structured artifact. For files with no open questions, or where questions live only in the main body (e.g. inline `[Q:]` markers), keep `questions_toon: null`. The separate `.questions.toon` is for **structured, tracked** question sets, not ad-hoc inline questions.

When `questions_toon` is non-null:

- suffix must be `.questions.toon`
- year uses real calendar year (e.g. 2026), not 1026
- channel/slug align with `memory_toon`
- pattern: `memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/`

**Example:** `memory/headers/questions/2026/04/lupopedia_headers.questions.toon`

## 10. Validator rules (normative)

Validators must enforce:

- canonical 22-field order
- required key presence
- **`prd_cluster`:** `NN_X-i` token grammar, single-line constraints, and explicit file resolution only (see **PRD Cluster Shorthand Notation**; no `NN_X` selector tokens; no `docs/prd/NN_X*` glob expansion)
- `artifact_type` / `artifact_kind` / `lupopedia.schema` cross-field rules (see ??4.4; `HDR_ARTIFACT_TYPE`, `HDR_SCHEMA_ARTIFACT_MISMATCH`)
- type checks including:
  - `content_id` = null or integer
  - `content_parent_id` = null or integer
  - `default_collection_id` = null or integer (`HDR_DEFAULT_COLLECTION_INVALID`)
- **ID field null handling:** All `_id` fields (content_id, content_parent_id, default_collection_id, thread_id) MUST be either null or integer. Empty strings are prohibited.
- **ASCII enforcement (`HDR_ASCII_VIOLATION`):** Header fields MUST be ASCII-only. ASCII_ART_BLOCK MUST remain ASCII. Non-ASCII in those surfaces (including smart quotes, em dashes, Unicode arrows, and emoji) is forbidden. Document body SHOULD be ASCII-safe; MAY contain extended characters; MUST NOT break parsing or validation.
- **Empty string normalization:** For non-`_id` nullable fields (questions_toon, memory_toon, atoms_toon), empty strings are prohibited; use `null` instead. (`HDR_EMPTY_STRING`)
- **Migration enforcement:** Existing files with empty-string `_id` values MUST be corrected to `null` during validation; this is a mandatory auto-correction. (`HDR_MIGRATION_VIOLATION`)
- **No header field may end in `_slug`.** Slug patterns are handled by external routing systems only. (`HDR_SLUG_FIELD_VIOLATION`)
- **transcript slug collision:** `transcript_jsonl` routing slug MUST NOT equal any other header field value. (`HDR_TRANSCRIPT_SLUG_COLLISION`)
- **external slug prevention:** Header fields MUST NOT contain slug patterns. Detection criteria: Field value matches regex `^[a-z0-9]+(-[a-z0-9]+)*$` (kebab-case slug pattern) AND field is NOT `transcript_jsonl`. Exception: `transcript_jsonl` may contain slash-separated slug-like segments as part of its routing path. (`HDR_EXTERNAL_SLUG`)
- `trust_tier` = one of `canonical`, `development` (`HDR_TRUST_TIER_INVALID`; legacy tiers warn during transition)
- `transcript_jsonl` header authority semantics (slug shape per **??6**)
- legacy alias handling per migration policy (**??11**); **`HDR_PK_LEGACY_ALIAS`** when legacy `pk_*` / `parent_pk_id` keys are present ??? **WARN** for declared **`header_format_version`** **`4.1.0`???`4.1.2`** only; **ERROR** for **`4.1.3`+** (see **??11.1**)
- **channel/path consistency** (`HDR_CHANNEL_PATH_MISMATCH`): when `memory_toon` is non-null,
  the path MUST follow `memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/...` and the
  `channel_key` segment in path position 2 MUST equal declared `channel_key`. A mismatch
  is a validation ERROR. When `memory_toon` is null, the check is skipped.
- `memory_toon` path is derived from header-declared metadata and is validated against header fields; it is not authoritative over header values.

### 10.1 `HDR_CHANNEL_PATH_MISMATCH` (normative)

```python
# PRD 16 ??10.1 ??? channel/path consistency check
def check_channel_path_consistency(channel_key, memory_toon):
    if memory_toon is None:
        return None  # null memory_toon is always valid here
    # memory_toon form: memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/...
    parts = memory_toon.strip().split('/')
    if len(parts) < 6 or parts[0] != 'memory':
        return 'HDR_CHANNEL_PATH_MISMATCH'  # malformed path
    path_tier = parts[1]
    path_channel = parts[2]
    path_thread = parts[3]
    if not path_tier or not path_thread:
        return 'HDR_CHANNEL_PATH_MISMATCH'
    if path_channel != channel_key:
        return 'HDR_CHANNEL_PATH_MISMATCH'
    return None
```

**Error code:** `HDR_CHANNEL_PATH_MISMATCH`
**Severity:** ERROR (not warning ??? path/header disagreement is structural)
**Auto-correctable:** NO ??? the correct channel key cannot be inferred safely from path alone;
requires human resolution.

This rule enforces the two-type memory path doctrine defined in [PRD 38](38_memory_unification.md) **Channel Scope for Memory**: Type A `.toon` artifacts MUST have a path whose first segment after `memory/` matches `channel_key`.

**See also:** [PRD 38](38_memory_unification.md) **Channel Scope for Memory** (full two-type path doctrine).

### 11. Header freeze scope

Header schema frozen (22 fields), interpretation evolving (4.1.8). See Header freeze rule (4.1.8) above. This freeze includes:
- The canonical 22-field order and field names
- Field type constraints and validation rules
- The closed enum definitions for `artifact_type` and `artifact_kind`
- Validator rule semantics and behavior

Validator rules may be clarified for consistency but MUST NOT alter the semantic meaning of existing constraints. New validation rules that enforce existing semantics (e.g., ASCII compliance, empty string normalization) are permitted under the freeze as they clarify rather than change requirements.

### 11. Migration policy boundary

Migration compatibility is defined in `16_lupopedia_headers_migration.md`.

Normative summary for **`pk_*` ??? `content_*`** (same detail as migration guide ??2.1):

### 11.1 pk_* to content_* migration (HDR_PK_LEGACY_ALIAS)

| Legacy field | Canonical field | Accepted until | Rejected from |
|---|---|---|---|
| `pk_id` | `content_id` | 4.1.2 (warning only) | 4.1.3 (validation error) |
| `pk_slug` | ~~REMOVED~~ | 4.1.2 (warning only) | 4.1.3 (validation error) |
| `parent_pk_id` | `content_parent_id` | 4.1.2 (warning only) | 4.1.3 (validation error) |

Other hard policy (detail: `16_lupopedia_headers_migration.md`):

- 4.1.3: remove `dialog_transcript` alias support (same timeline family as `pk_*`; validator WARN vs ERROR by patch)
- 4.2.0: remove all remaining migration compatibility aliases

There is no Lupopedia-to-Lupopedia upgrade path prior to 4.2.0.

### 11.2 Header validation workflow

Headers are validated through the following workflow:
1. Initial creation with all required fields
2. Validation against schema rules
3. Auto-correction of common issues
4. Final approval and persistence

## 12. Key actors

Actor and agent IDs are authoritative from `memory/atoms/lupopedia_global_constants.atom.toon`:

| Actor | actor_id | agent_id | Role |
|-------|----------|----------|------|
| LILITH | 2 | 2 | Constitutional auditor ??? observes, reviews, reports, escalates |
| ANUBIS | 9 | 9 | Orphan processor ??? detects `content_id: null`, creates `lupo_contents` rows, writes headers |
| THOTH | 26 | 26 | Truth guardian ??? cross-references content against immutable atoms, raises `[ALERT]` on contradiction |

### 12.1 ANUBIS operational contract

ANUBIS (`actor_id: 9`, `agent_id: 9`) is an integrity component for orphan resolution:

**See also:** [PRD 19 ??? Garbage collection](19_garbage_collection_system.md) (unified GC tables, orphan-related lifecycle); [PRD 79 ??? Install seed doctrine](79_install_seed_doctrine.md) (initial `lupo_contents` and seed-time patterns).

- idempotent processing; no duplicate content rows
- deterministic orphan detection (`content_id: null` or missing header)
- retry-safe failure handling
- no partial success claims
- no duplicate row creation under concurrency
- all resolve/skip/failure actions logged

### 12.1.1 ANUBIS orphan resolution execution baseline (canonical)

ANUBIS orphan resolution is canonically **direct synchronous repair** unless and until a queue execution doctrine is explicitly ratified.

Canonical baseline behavior:

1. Detect orphan condition from PRD 16 linkage rules.
2. Execute deterministic repair in the same invoked execution context.
3. Prevent duplicate canonical rows before any create/insert operation.
4. Apply required header updates for resolved linkage.
5. Log resolve/skip/failure outcomes with enough detail for replay.

Determinism and idempotency requirements:

- Re-running the same repair on unchanged input MUST converge to the same result.
- Repeated scans MUST NOT create duplicate canonical rows.
- Retry paths MUST be safe and deterministic.

No-partial-success baseline:

- ANUBIS MUST NOT report completed success unless canonical linkage and required file/header state are both consistent.
- If DB linkage succeeds and file/header write-back fails, outcome is incomplete and MUST be retried safely without duplicate row creation.
- If file/header mutation succeeds but DB linkage fails, outcome is incomplete and MUST be reconciled to canonical DB-first consistency on retry.
- If execution is interrupted mid-repair, the next explicit run MUST resume idempotently.

Operational invocation baseline:

- Async execution is allowed only when explicitly invoked (for example cron, CLI, or manual operator trigger).
- There is no implied background daemon/worker in baseline canon.

Future Work:
A queue-based execution model may be introduced in a future doctrine.
See:
`docs/doctrine/runtime/ANUBIS_QUEUE_EXECUTION_PROPOSAL.md`

### 12.1.2 Canonical source resolution rule (identity authority)

Lupopedia uses a formal deterministic authority model for artifact identity resolution:

- `content_id` null or missing -> file-first discovery mode
- `content_id` non-null and valid in DB -> database-first reconciliation mode
- `content_id` non-null but invalid in DB -> repair state (not trusted authority)

File-first state:

- The filesystem artifact, header, and path are authoritative for discovery and initial identity creation.
- ANUBIS (or equivalent tooling) resolves or creates canonical DB linkage.
- Resolution is complete only when canonical linkage is written back and file plus DB linkage are consistent.

Database-first state:

- The DB row identified by `content_id` is the canonical identity anchor only after existence and linkage validity checks pass.
- Header regeneration and validation reconcile file metadata to DB truth.
- Mismatch is a validation/repair problem, not a discovery-state ambiguity.

Repair state (broken link):

- A non-null `content_id` is not automatically trusted if linkage is invalid.
- Invalid means, at minimum, DB row missing, row points to the wrong artifact, or row is unusable for canonical linkage.
- Repair flow must re-establish valid canonical linkage before returning to database-first state.

Deterministic evaluation order (mandatory):

1. If `content_id` is null or missing, classify file-first.
2. If `content_id` is present, validate DB existence and linkage correctness before trusting it.
3. If DB validation passes, classify database-first.
4. If DB validation fails, classify repair state.

Non-negotiable guardrails:

- Non-null `content_id` MUST NOT be treated as valid by presence alone.
- DB existence validation MUST NOT be skipped in resolution paths that claim state authority.
- State classification is deterministic and rule-based, not heuristic.
- Null `_id` values indicate file-first discovery state. Empty strings are invalid.

Transition rule:

- Artifacts begin file-first until valid canonical linkage exists.
- Once a valid non-null `content_id` is written and verified, the artifact transitions to database-first.

### 12.2 THOTH role

THOTH (`actor_id: 26`, `agent_id: 26`) is the truth guardian ??? a separate verification actor from ANUBIS:

- cross-references file content against immutable atoms (`atoms_toon`)
- reads all dialog messages in transcript context
- raises `[ALERT]` on contradiction between content and atoms

**See also:** [PRD 51 ??? Memory graph and thread context](51_memory_graph_as_source_of_truth.md) for memory-graph authority, header inference from graph/thread context, and atom-related validation expectations that align with THOTH???s role.

### 12.3 LILITH role

LILITH (`actor_id: 2`, `agent_id: 2`) is the constitutional auditor:

- observes, reviews, reports, escalates
- records constitutional compliance decisions
- approves header and schema changes (subject to **Header freeze rule (4.1.8)**; no normative header experiments during the freeze without orchestration exception)

## 13. File naming doctrine separation

- Documentation and memory artifacts: `lowercase_with_underscores`
- PHP runtime/class files: exempt from mass normalization during this phase
- Runtime naming must not be mass-normalized without loader/include-path validation

### 13.1 Content Ordering Constraints

The following order is **RECOMMENDED** and **validator-enforced in strict mode**:

1. Body content (Markdown after header YAML block)
2. `lupopedia.edges` (if present)
3. `lupopedia.footer` (if present)

**Prohibited:** Interleaving edges or footer blocks within body content.

Validators SHALL raise `HDR_CONTENT_ORDER_VIOLATION` (WARNING in standard mode, ERROR in strict mode) when:
- `lupopedia.edges` appears before the first body heading
- `lupopedia.footer` appears before `lupopedia.edges` (when both present)
- Either block appears inside a code fence or indented code block (those are not parsed as directives)

## 14. Companion documents

- Migration guide: `docs/prd/16_lupopedia_headers_migration.md`
- Examples: `docs/prd/16_lupopedia_headers_examples.md`

## 15. Version policy

**Current: 4.1.8**

The header format is currently unfrozen for critical fixes. Once foundation corrections are complete, it will be frozen again for the delivery phase.

### 15.1 Patch (4.1.x ??? 4.1.x+1) ??? all changes are patches

Any change is a patch. There is no Lupopedia ??? Lupopedia upgrade path before 4.2.0. Users either:
- Fresh install (no existing data to migrate)
- Upgrade from Crafty Syntax 3.7.5 (importer handles schema differences)

**Validator (unchanged mechanics):** declared `header_format_version` strings in the **`4.1.*`** family are evaluated per the timeline below. **When frozen**, treat the current frozen version as the **only** string to use in **normative** examples and new files; older trees may still declare **`4.1.0`???`4.1.2`** until migrated. Legacy YAML keys (notably **`pk_*`** aliases) are WARN-only for **`4.1.0`???`4.1.2`** and **rejected** for **`4.1.3`+** when still present. See ??15.4.

### 15.2 Minor (4.2.0) ??? upgrade gate

First version that supports Lupopedia ??? Lupopedia upgrades:
- Softaculous accepts the program
- Existing 4.1.x installs can upgrade to 4.2.0
- Migration scripts must exist for all breaking changes

### 15.3 Major (5.0.0)

Not planned. Would require complete rewrite + new database schema + new installer.

### 15.4 Validator behavior

```python
# PRD 16 ??15 ??? version acceptance policy (4.1.8 current; validator branch unchanged)
def validate_header(version, has_legacy_pk_fields):
    # Step 1: Version family acceptance
    if version.startswith("4.0."):
        reject("HDR_VERSION_FAMILY_DEPRECATED")  # 4.0.x frozen

    elif version.startswith("4.1."):
        # Step 2: Parse patch number
        version_parts = version.split(".")
        patch = int(version_parts[2]) if len(version_parts) >= 3 else 0

        # Step 3: Apply migration timeline
        if patch >= 3 and has_legacy_pk_fields:
            reject(
                "HDR_VERSION_MISMATCH",
                "pk_* fields removed in 4.1.3, but file claims %s" % (version,),
            )
        else:
            accept()  # 4.1.0-4.1.2 may have pk_* (warnings only)

    elif version == "4.2.0":
        accept()  # Upgrade gate
    else:
        reject("HDR_VERSION_FAMILY")
```

The universal validator implements the `4.1.x` branch via `check_version_compliance()` in `validate_lupopedia_headers_universal.py`. Declared `header_format_version` strings outside accepted families still fail `HDR_VERSION_FAMILY` (or deprecated-family rules for `4.0.x`) before the `pk_*` timeline runs.

## 16. Validator Enforcement & Auto-Correction

The universal header validator (`validate_lupopedia_headers_universal.py`) serves as the primary enforcement engine for the Lupopedia header contract. To maintain consistency across the codebase, the validator supports different levels of enforcement and automated remediation.

Responsibility split (clarification):

- Validators enforce structural/header-contract rules (shape, enums, ordering, path/header consistency).
- Runtime/DB-backed services enforce live linkage and operational reconciliation behavior.

### 16.1 Hard Failures (Non-Recoverable)
- **Cross-Field Contradiction:** `lupopedia.schema` not matching `artifact_type`.
- **Version Mismatch:** Declared `header_format_version` claiming a version (e.g., 4.1.3+) while still containing rejected legacy fields (`pk_*`).
- **ASCII Violations:** Presence of non-ASCII characters (`HDR_ASCII_VIOLATION`).
- **Empty String Violations:** Non-`_id` fields containing empty strings (`HDR_EMPTY_STRING`).
- **Migration Violations:** Empty-string `_id` values not corrected (`HDR_MIGRATION_VIOLATION`).
- **No header field may end in `_slug`.** Slug patterns are handled by external routing systems only. (`HDR_SLUG_FIELD_VIOLATION`)
- **Transcript Slug Collision:** `transcript_jsonl` routing slug equals any other header field value (`HDR_TRANSCRIPT_SLUG_COLLISION`).
- **External Slug Violations:** Header field value matches kebab-case slug pattern in non-transcript_jsonl fields (`HDR_EXTERNAL_SLUG`).
- **Web Path Consistency Violations:** `web_path` does not correspond to `path_from_lupopedia_root` with proper subdirectory mapping (`HDR_WEB_PATH_CONSISTENCY`).

### 16.2 Auto-Corrections (Warning-Led)

Validators MAY perform safe, non-destructive auto-corrections when invoked with the `--fix` flag. These changes trigger a `WARN_FIXED` status:

- **Canonical Reordering:** Moving existing valid keys into the canonical 22-field order defined in ??4.2.
- **Legacy Alias Migration:** Renaming `pk_id` to `content_id`, `pk_slug` to ~~REMOVED~~, etc., provided the version-timeline cutoff in ??11 has not been reached.
- **Whitespace Normalization:** Standardizing indentation to 2 spaces and removing trailing whitespace within the header block.
- **Missing Nulls:** Explicitly adding `null` to empty optional fields to satisfy the 22-field requirement. Empty strings are never inserted for `_id` fields.
- **ASCII Normalization:** Converting non-ASCII characters to ASCII equivalents where possible (e.g., smart quotes to straight quotes). Unconvertible characters trigger ERROR.
- **Empty String Correction:** Converting empty strings in non-`_id` nullable fields to `null`.
- **Migration Correction:** Converting empty-string `_id` values to `null` (mandatory correction).
- **Slug Field Correction:** Removing any header field ending in `_slug` (forbidden in 22-field structure).
- **External Slug Correction:** Removing filesystem helper slugs from header fields.

### 16.3 Generation Features (Interactive)

Complex state changes require explicit user confirmation or specialized flags:

- **Content ID Assignment:** When `content_id` is `null`, the validator (via ANUBIS integration) can propose a new ID generation.
- **Sidecar Synchronization:** Using `--generate-sidecars` to update `memory/` artifacts based on the current header state.

### 16.4 Validation Flags

| Flag | Description |
|---|---|
| `--strict` | Enforces exact line positions and prohibits any legacy aliases. |
| `--fix` | Automatically applies safe reordering and alias migrations. |
| `--generate-sidecars` | Triggers `generate_memory_from_header.py` for all validated files. |
| `--verify-channels` | Cross-references `channel_key` against active DB channels. |
| `--verify-edges` | Validates that `memory_toon` and `atoms_toon` paths exist and are reachable. |

### 16.5 Idempotency Requirement

All validator operations, including `--fix` and ID generation, MUST be idempotent. Running the validator multiple times on the same file without intermediate manual changes must result in a stable, unchanged file state after the initial correction.

### 16.6 Content Section Parsing

The validator includes a structural parser that extracts sections based on Markdown heading levels (e.g., `##`, `###`). This data is used to populate the `content_sections` JSON column in the `lupo_contents` table.

#### 16.6.1 JSON Schema for `content_sections`

The parsed output is an array of section objects:

```json
{
  "sections": [
    {
      "level": 2,
      "title": "Introduction",
      "section_slug": "introduction",
      "line_start": 45,
      "line_end": 88
    },
    {
      "level": 3,
      "title": "Sub-point A",
      "section_slug": "sub-point-a",
      "line_start": 89,
      "line_end": 112
    }
  ]
}

**`line_end` for the final section:** For the last section in the file, `line_end` **SHALL** be the line number of the **last non-empty** line of file content (body after the header envelope). If the file ends immediately after the last heading with no body lines, `line_end` **MAY** equal `line_start`. Validators **MUST NOT** treat `line_end` as ???exclusive next heading line??? for the final section when there is no following heading.

#### 16.6.2 Validator Flags

| Flag | Description |
|---|---|
| `--parse-sections` | Enables heading extraction and outputs the `content_sections` JSON to stdout or sidecar. |
| `--create-section-nodes` | Generates a memory node in the graph for every `##` and `###` level heading. |
| `--require-sections` | Fails validation if the file contains no structural headings (e.g., a raw text dump). |

#### 16.6.3 Use Cases

**See also:** [PRD 51](51_memory_graph_as_source_of_truth.md) **??3.1** (memory graph as a primary context source), **??4.4.5** (optional memory node materialization from content), and **??5** (header inference mapping) for graph and node rules that apply when section-level or file-level memory nodes are created or linked.

- **TOC Generation:** Automated Table of Contents generation for the Lupopedia web interface.
- **Section-Level Graph Edges:** Allows the memory graph to link specific questions or tasks to the precise section of a PRD where they are defined.
- **Structural Validation:** Ensures that PRDs follow a standardized hierarchical structure (e.g., requiring sections 1 through 5).

#### 16.6.4 Relationship to Database

The output of `--parse-sections` is the authoritative source for the `lupo_contents.content_sections` column. Importers (`import_content.py`, `import_content.php`) MUST be updated to call the validator's parsing logic and populate this column during content ingestion or refresh.

This output complies with Lupopedia Constitutional Root Rules.

---

## 17. File Types and Header Usage

### 17.1 Authored Files vs Machine-Readable Payloads

**Authored Files (Require Headers):**
- Human-authored documentation: `.md`, `.txt`
- Source code files: `.py`, `.php`, `.js`, `.html`, `.htm`
- Configuration files that are primarily human-maintained
- Any file where a human is the primary author and the file serves as a first-class artifact

**Machine-Readable Payloads (No Headers):**
- JSON data files: `.json`, `.jsonl`
- TOON memory files: `.toon`, `.atom.toon`
- Generated exports and automated outputs
- Lockfiles, vendor trees, binaries
- Any file that is primarily a data payload or machine-generated artifact

### 17.2 Recommended Pattern for Payload Files

For machine-readable artifacts that need to be tracked in the system:

1. **Create a companion .md file** with a full PRD 16 header
2. **Reference the payload file** in the companion's `memory_toon` or `atoms_toon` field
3. **Keep the payload file clean** without YAML headers

**Example:**
- `filename-normalization-session-2026-04-20.md` (with PRD 16 header)
- `filename-normalization-session-2026-04-20.toon` (clean JSON payload)

### 17.3 Rationale

- Headers are designed for **authored artifacts** where human identity and routing matter
- Machine payloads should remain **parseable without YAML envelope complications**
- Separation of concerns: metadata in .md, data in clean payload files
- Avoids header pollution of pure data structures

---

## 18. Footer Handling (Non-Validated)

The `lupopedia.footer` block (when present) is **not structurally validated** by header validators beyond:
- Presence of `pending_edges` array (if footer exists)
- Presence of `notes` array (if footer exists)

Contents of `pending_edges` and `notes` are informational only. Validators SHALL NOT enforce:
- Edge target existence
- Note formatting
- Field types within footer objects

Rationale: Footer is a human/agent notes area, not a normative contract.

---

## 19. PRD_CLUSTER REFERENCE VALIDATION

### NEW RULE: PRD_CLUSTER REFERENCE VALIDATION

For each PRD token referenced in `prd_cluster`:

* token MUST conform to `NN_X-i` grammar (see **PRD Cluster Shorthand Notation**)
* referenced PRD file MUST exist
* referenced PRD file MUST be resolved by explicit mapping or registry only (no glob expansion, no directory scanning)
* referenced PRD file `header_format_version` MUST be compatible with the current active header version policy

If either condition fails:

* ALLOW validator and tests to run
* BLOCK implementation and progression
* documentation correction takes priority

### RATIONALE

* prevents phantom doctrine
* prevents outdated doctrine from silently governing current implementation
* enforces PRD-first architecture

### VALIDATION REQUIREMENTS

1. RESOLVE referenced PRD file path
2. VALIDATE existence (HARD FAIL if missing)
3. VALIDATE header version compatibility against Section 15.4 policy (HARD FAIL on deprecated families or incompatible versions)

### FAILURE BEHAVIOR

On validation failure:

* STOP implementation and deployment actions only
* VALIDATION layers (validator + tests) MUST still execute to surface violations
* Validator/tests MUST report PRD_CLUSTER violations as HARD FAIL with explicit error codes:
  * `HDR_PRD_CLUSTER_MISSING` for non-existent PRD files
  * `HDR_PRD_CLUSTER_OUTDATED` for PRD files that use deprecated or incompatible header versions
* OUTPUT clear error message with required actions

### ENFORCEMENT MODE

This rule is enforced in strict mode (validator `--strict` flag). Non-strict mode may report warnings but does not block implementation.

### SCOPE

Applies to:

* validator
* PRD parsing logic
* any system resolving prd_cluster

---

## 20. PRD_CLUSTER-DRIVEN CODE VALIDATION

### PRINCIPLE

For authored implementation files such as `.php` and `.py`, correctness is evaluated against the directions declared in the file's `prd_cluster`. The `prd_cluster` is not decorative; it is the declared implementation contract.

### VALIDATION QUESTIONS

When evaluating implementation file correctness, validation should ask:

1. **Do the PRDs in the cluster exist?** (See Section 19 for existence validation)
2. **Are they current?** (For 4.1.x files, accepted versions are governed by Section 15.4; strict mode rejects deprecated families and legacy-field violations)
3. **Do the implementation choices in the file align with those PRDs?**

### IMPLEMENTATION ALIGNMENT CRITERIA

An implementation file is considered doctrinally aligned when:

* **Material Compliance:** Implementation does not materially contradict any directive in the cluster PRDs
* **Scope Consistency:** Implementation stays within the scope defined by the cluster PRDs
* **Constraint Adherence:** Implementation respects all constraints, prohibitions, and requirements in cluster PRDs

### FAILURE CONDITIONS

Implementation fails validation when:

* **Direct Contradiction:** Code implements behavior explicitly forbidden by cluster PRDs
* **Missing Requirements:** Code omits functionality explicitly required by cluster PRDs
* **Scope Violation:** Code implements functionality outside the scope defined by cluster PRDs

### VALIDATION SCOPE

**This does NOT mean:**
* Every file must restate every rule inline
* Implementation must quote PRD text verbatim

**This DOES mean:**
* The file's declared cluster is the implementation contract
* Validation is doctrine-first, not guess-first
* Material contradictions are validation failures

### EXAMPLE VALIDATION FLOW

```
Question: "Does all the directions in the prd_cluster match what is written in this PHP file?"

Validation Steps:
1. Parse prd_cluster from file headers
2. Validate all referenced PRDs exist and are current (Section 19)
3. Extract implementation directives from cluster PRDs
4. Compare implementation against extracted directives
5. Report pass/fail with specific contradictions if any
```

### ENFORCEMENT

* **Validator Mode:** Strict mode enforces this validation, non-strict mode may report warnings
* **Implementation Block:** Material contradictions block progression in strict mode
* **Documentation Priority:** When contradictions exist, documentation correction takes priority over implementation changes

---

## 21. IMPLEMENTATION IS NOT TRUTH (FALL-FORWARD SYSTEMS)

### PRINCIPLE

PRDs define system intent (truth). Code defines implementation strategies (replaceable). Code MUST NOT be treated as the canonical definition of system behavior.

### 1. INTENT VS IMPLEMENTATION

* **PRDs define system intent** (canonical truth)
* **Code defines implementation strategies** (disposable, replaceable)
* **Code MUST NOT be treated as the canonical definition of system behavior**

### 2. MULTIPLE IMPLEMENTATION PATHS

Systems MAY contain multiple execution paths for the same feature:

**Examples:**
* refresh-based polling
* XMLHttpRequest  
* AJAX
* future transport layers

These are NOT separate features. They are interchangeable implementations of the same intent.

### 3. CAPABILITY-BASED ROUTING

Runtime MAY select implementation based on:

* browser capability
* environment capability  
* performance conditions
* feature detection

**Therefore:**
* The existence of a fallback path does NOT define system behavior
* The most visible code path may be the lowest-level fallback

### 4. FALL-FORWARD / PROGRESSIVE EVOLUTION

Systems are expected to evolve:

* New implementations replace old ones
* Old implementations remain for compatibility
* System must continue working across generations

Implementation layers may accumulate over time. This is intentional, not technical debt.

### 5. VALIDATION RULE FOR AGENTS

**Agents MUST NOT:**
* Assume a single code path represents the full system
* Treat fallback implementations as primary behavior
* Infer intent from a single file or function

**Agents MUST:**
* Consult PRDs (intent)
* Consult prd_cluster
* Recognize multiple execution strategies
* Identify capability routing where present

### 6. CORE PRINCIPLE

"Implementations are disposable. Intent is not."

### 7. IMPACT

This doctrine affects:

* Code review
* Validation logic
* Agent reasoning
* Documentation interpretation

### 8. EXAMPLE CRAFTY SYNTAX CASE

**Incorrect Agent Analysis:**
* Observed: `chat_refresh` function
* Concluded: "System only refreshes the page"

**Correct System Behavior:**
* Uses JavaScript capability detection
* Dynamically switches execution paths:
  * `chat_refresh` (fallback)
  * `chat_xmlhttp`
  * `chat_ajax`
* Selects best available implementation at runtime

**Lesson:** The visible code path is NOT the full system.

---

## 22. Cross-references

- Related doctrine: `THREE_LAYER_METADATA_DOCTRINE.md` - Three-layer metadata architecture (headers/metadata/footers)
- Related doctrine: `METADATA_VALIDATION_SPECIFICATION.md` - Metadata validation rules and specifications
- Related doctrine: `CASTCADE_METADATA_HANDLING_CHECKLIST.md` - Castcade operational checklist for metadata handling
- See also: `00_root_constitutional_system_requirements.md` (Constitutional system requirements, subdirectory installation doctrine, web_path requirements)
- See also: `38_memory_unification.md` (memory unification and two-type memory path doctrine)
- See also: `51_memory_graph_as_source_of_truth.md` (memory graph authority and header inference)
- Related tables: `lupo_contents`, `lupo_metadata`, `lupo_memory_nodes`, `lupo_edges`
