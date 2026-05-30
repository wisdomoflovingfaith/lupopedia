---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/16_A-i_HEADER_TEMPLATE_22_FIELDS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/16_A-i_HEADER_TEMPLATE_22_FIELDS.md
  status: active
  when_updated: '20260513033333'
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
  summary: Standard 19-field header template for PRD files at version 4.1.8. Use as base for new documents.
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
