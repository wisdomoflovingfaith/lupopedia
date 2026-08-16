---
lupopedia.headers:
  header_format_version: "4.2.4"
  path_from_lupopedia_root: docs/prd/16_A-i_HEADER_TEMPLATE_22_FIELDS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/16_A-i_HEADER_TEMPLATE_22_FIELDS.md
  status: active
  when_updated: "20260815212117"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/headers/canonical/1026/04/header-template.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/header-template
  artifact_type: prd
  artifact_kind: template
  channel_key: headers
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_16_A-i_16_D-i
  title: 'PRD 16: Header Template (19 Fields)'
  summary: Header template plus 4.2.4 LUP identity sibling. Federation 000001 compresses to X in human forms. Dense grid unchanged.
lupopedia.identity:
  lupopedia_id: "LUP:000001-000011-01-00-EN-01"
  federation_id: "000001"
  artifact_hex: "000011"
  namespace_id: "01"
  iteration: "00"
  language: "EN"
  actor_aa: "01"
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _________________
. ./ \ ` ` `_\-\ . | A two-dimensional, finite, constitutional PRD documentation
. '/| \-''-/_ / . | architecture that lets docs build software. PRDs reference
. { . , . , . ,\ .| other PRDs, forming clusters that define behavior, truth,
. / . , . , . , \ | limits, and system identity. Each file carries a header that
./ , . "O. |"O. } | records the exact prd_cluster (reading order), the full
_| . , . , \ \ ;. | transcript_jsonl dialog, and atoms_toon for canonical truth,
. '\. . , . \ \' . | ensuring deterministic lineage and reproducibility.
.. '\_ . , . \__\ | https://www.lupopedia.com/
., , ''-_ , {\__/}|
. . , . / '-.____'| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
., , /___________________________________________________________________________________
.. , _'
___-'
<!-- /ASCII_ART_BLOCK -->

<!--HUMAN_SEMANTIC -->
This file belongs to:
- PRD Group 16 (Identity Layer ??? Headers, Atoms, Migration)
- Cluster 16ABCDE
- Channel: headers
- No default collection yet

See also:
- 00_A_FORBIDDEN_AND_WHY.md
- 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
- PRD 86 ?-? Immune system (no drift, no entropy)
- Order of Operations: PRD - Schema - Mockups -Code
<!-- /HUMAN_SEMANTIC -->

# PRD 16: Header Template (22 Fields)

## LUP -- Linked Universal Protocol

**LUP** stands for **Linked Universal Protocol**, the universal identity system used by Lupopedia to identify, version, translate, federate, and track provenance for any digital artifact.

LUP -- Linked Universal Protocol (Universal Artifact Identity). Not a song-only ID. Not "Lupopedia ID."

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

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

## Usage Instructions

Copy this template and replace the following placeholders:

- `FILENAME.md` ??? Your actual filename
- `YYYYMMDDHHIISS` ??? Current timestamp (run `python bin/tick.py` to generate)
- `CHANNEL` ??? Your channel (e.g., "prd", "development")
- `MM` ??? Current month (two digits)
- `slug` ??? Short identifier for your file
- `PRD_CLUSTER_HERE` ??? Your PRD cluster string
- `TITLE` ??? Your document title
- `SUMMARY` ??? Brief description


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

## Template Header (22 Fields)

This template reflects `header_format_version: "4.1.8"` semantics.

Earlier versions such as 4.1.5 and 4.1.6 used different interpretation rules and MUST NOT be assumed when creating new files from this template.

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: "docs/prd/FILENAME.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/FILENAME.md"
  status: "active"
  when_updated: "YYYYMMDDHHIISS"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/CHANNEL/canonical/1026/MM/slug.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/CHANNEL/slug"
  artifact_type: prd
  artifact_kind: template
  channel_key: "CHANNEL"
  federation_node_id: 0
  thread_key: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "PRD_CLUSTER_HERE"
  title: "TITLE"
  summary: "SUMMARY"
```

## Three-Part Preamble Example (4.1.8)

For PRD and canonical authored artifacts, this three-part preamble is REQUIRED.

For other authored artifacts, this three-part preamble is RECOMMENDED unless file type constraints prevent it.

1. YAML `lupopedia.headers` block
2. `ASCII_ART_BLOCK`
3. `HUMAN_SEMANTIC`

```md
---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: "docs/prd/FILENAME.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/FILENAME.md"
  status: "active"
  when_updated: "YYYYMMDDHHIISS"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/CHANNEL/canonical/1026/MM/slug.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/CHANNEL/slug"
  artifact_type: prd
  artifact_kind: template
  channel_key: "CHANNEL"
  federation_node_id: 0
  thread_key: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "PRD_CLUSTER_HERE"
  title: "TITLE"
  summary: "SUMMARY"
---
<!-- ASCII_ART_BLOCK -->
... ascii art ...
<!-- /ASCII_ART_BLOCK -->
<!--HUMAN_SEMANTIC -->
... human-oriented contextual notes ...
<!-- /HUMAN_SEMANTIC -->
```

### ASCII_ART_BLOCK Protection

`ASCII_ART_BLOCK` is immutable visual identity.

AI agents MUST NOT:

- reformat it
- normalize spacing
- modify characters
- regenerate it
- replace it

Only the Captain or an explicitly authorized identity-update task may modify this block.

`ASCII_ART_BLOCK` is visual only and MUST NOT override YAML header fields.

### HUMAN_SEMANTIC Guardrail

`HUMAN_SEMANTIC` is advisory human context only.

It MUST NOT:

- introduce new facts not present in the YAML header or governing PRD cluster
- contradict YAML header values
- redefine system behavior
- override `prd_cluster`
- override `memory_toon`
- override `atoms_toon`
- override `transcript_jsonl`
- override `questions_toon`

It may only summarize or explain existing doctrine and header context for human readers.

### Authority Stack Reminder (4.1.8)

Header interpretation follows:

```text
READ_ORDEX:
1. prd_cluster
2. atoms_toon
3. memory_toon
4. questions_toon (optional)
5. transcript_jsonl (append-only; read only if explicitly required by governing PRD)
```

The three-part preamble MUST NOT alter this order.

## LUP (Linked Universal Protocol) Identity Block (4.2.4 -- NOT in the dense grid)

Identity is a **sibling** of `lupopedia.headers`. It is **not** one of the 22/28 dense discovery fields.

New artifacts MUST set `header_format_version: "4.2.4"` and include **machine** identity (six-hex federation):

```yaml
lupopedia.identity:
  lupopedia_id: "LUP:000001-000000-01-00-EN-01"
  federation_id: "000001"
  artifact_hex: "000000"
  namespace_id: "01"
  iteration: "00"
  language: "EN"
  actor_aa: "01"
```

Dense 28-field grid is unchanged. Identity is a sibling block. Storage MUST be the canonical 6-token **machine** string (`000001`, not `X`). Color is metadata, not a LUP token.

## LUP.KEY identity (4.2.11 -- NOT in the dense grid)

New artifacts MUST set `header_format_version: "4.2.11"` and include:

```text
lupopedia.identity:
  LUPOPEDIA     = PRT.LUP
  LUP.KEY       = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX       = PRT.HEX.000000.000000.000000.ROOT.EN.04020B
  LUP.SHORT     = PRT.LUP
  LUP.ROOT      = PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT      = REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS  = PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

YAML storage uses `key: value`. No hyphen in KEY grammar. Add sibling `lupopedia.map` (see `docs/prd/federation/federation_map_template.md`). 4.2.4 hyphen identity remains dual-accept until the file is edited.

**Federation Compression Rule (Option A):** Federation `000001` is the canonical root node. In short-form / human-friendly identities, it is compressed to the symbol `X`. Validators expand `X` -> `000001`. Only `000001` compresses.

Short-form parse (input only):

| Typed | Expands to |
|-------|------------|
| `LUP:X-RRRRRR-NN` | FF=`000001` II=`00` LL=`EN` AA=`00` |
| `LUP:FFFFFF-RRRRRR-NN` | II=`00` LL=`EN` AA=`00` |
| `LUP:X-RRRRRR-NN-II` | FF=`000001` LL=`EN` AA=`00` |
| `LUP:FFFFFF-RRRRRR-NN-II` | LL=`EN` AA=`00` |
| `LUP:X-RRRRRR-NN-II-LL` | FF=`000001` AA=`00` |
| `LUP:FFFFFF-RRRRRR-NN-II-LL` | AA=`00` |
| `LUP:FFFFFF-RRRRRR-NN-II-LL-AA` | no defaults (machine) |

Cross-checks (PRD 16_C section 4.2.5):

- Reconstruct `LUP:{federation_id}-{artifact_hex}-{namespace_id}-{iteration}-{language}-{actor_aa}` (machine FF)
- `federation_id` 6 hex after expand; reserved `000000`, `FFFFFF`; human `X` = `000001` only
- `artifact_hex` is 6 hex (native) or `originFederation:artifactNumber` (cross-federation modification). Colon `:` is the only lineage delimiter. **Not color.**
- No colon means native to the current federation. Split RRRRRR on the first colon only.
- `namespace_id` `01`..`FF`. Replaces `group_id`
- `actor_aa` `00`..`FF` maps to dense `actor_id`
- `language` (LL) is ISO 639-1, **or** reserved `ZZ` for multi-language / language-agnostic artifacts
- `ZZ` is **not** an ISO 639-1 language. Do not interpret it as Zulu or any ISO name
- Hawaiian fields MUST NOT appear here (PRD 82_B)
- `color_hex` if present is song metadata only

## Optional Edges Example

`lupopedia.edges` is not part of the 22-field YAML header.

When present, it SHOULD appear after the body content and before `lupopedia.footer`, following PRD 16 placement doctrine.

```yaml
lupopedia.edges:
  outbound_edges: []
```

## Code File Interface Note

Top-of-file function map requirements for implementation files are governed by the code file interface doctrine, not this header template.

This PRD defines header and preamble structure only.

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260421133500"
