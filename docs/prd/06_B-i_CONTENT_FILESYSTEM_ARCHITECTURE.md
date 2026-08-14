---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/06_B-i_CONTENT_FILESYSTEM_ARCHITECTURE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/06_B-i_CONTENT_FILESYSTEM_ARCHITECTURE.md
  status: active
  when_updated: '20260801010640'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/content-filesystem-architecture.toon
  atoms_toon: null
  transcript_jsonl: 0/development/content-filesystem-architecture
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_06_B-i
  title: 'PRD 06_B: Content Filesystem Architecture'
  summary: Canonical filesystem structure for content storage, federation isolation, timestamp anchoring, and validation rules.
---

# PRD 06_B ??? Content Filesystem Architecture

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

Define the canonical filesystem structure for all content storage in Lupopedia.

This PRD enforces deterministic, auditable, and collision-free content placement.

---


## 2. Invariant Path Rule (Constitutional)

All content MUST exist under:

```
content/federation_node/{federation_node}/{channel_key}/{YYYY}/{MM}/
```

The literal directory segment `federation_node/` is mandatory. It identifies the following path segment as the federation node ID. Agents MUST NOT omit this segment.

This rule is absolute.

---

## 3. Federation Nodes (Canonical Constants)

Federation nodes define isolated content universes.

| ID | Name |
|----|------|
| 0  | Lupopedia |
| 2  | Wisdom of Loving Faith |

Rules:

1. Federation nodes SHALL NOT share content paths.
2. Cross-federation access MUST be explicit and reference-based.
3. Federation nodes prevent doctrinal contamination and namespace collision.

---

## 4. Channel Keys (Namespace Domains)

Channel keys define semantic domains within a federation.


Examples:

```
content/federation_node/0/lupopedia/2026/04/the_day_lilith_stopped_bleeding.md
content/federation_node/2/wisdom_of_loving_faith/2026/04/another_file.md
```

Rules:

1. Channel keys MUST be lowercase and filesystem-safe.
2. Channel keys SHALL map directly to database channel identifiers.
3. Channel keys MUST NOT be dynamically mutated.

---

## 5. Timestamp Anchoring (Level 4 Requirement)

All content SHALL be anchored to:

```
{YYYY}/{MM}
```

Rules:

1. Content MUST be written into its timestamp directory.
2. No content may exist outside a timestamp anchor.
3. Directory grouping beyond month (e.g., year-only) is forbidden.

Failure to comply is a **Level 4 structural violation**.

---

## 6. File Metadata Requirements

For file-backed content:

- `storage_type` MUST be `file`
- `file_path_from_root` MUST match actual filesystem location
- Path MUST conform to invariant structure

Mismatch between metadata and filesystem SHALL be treated as corruption.

---

## 7. Cross-Federation References

Content in one federation MAY reference another, but:

1. References MUST be explicit (no implicit path traversal)
2. No direct filesystem merging is allowed
3. References SHALL be resolved via metadata, not path assumptions

---

## 8. Deferred Edges (GOD Layer / Soul Location)

Some content relationships are not immediately resolved.

Rules:

1. Deferred edges SHALL NOT alter filesystem placement
2. They MAY exist as metadata-only constructs
3. Filesystem structure remains immutable regardless of edge resolution

---


## 9. Validation Rules (LILITH/AGAPE Enforcement)

LILITH/AGAPE validation MUST reject file-backed content paths that:

- omit `content/`
- omit literal `federation_node/`
- place the federation node ID directly under `content/`
- omit `{channel_key}`
- omit `{YYYY}/{MM}`
- use non-numeric federation node IDs
- use invalid month values

LILITH SHALL validate:

- Path matches invariant structure
- Federation node is valid
- Channel key is valid
- Timestamp directories exist and are correct
- Metadata path matches filesystem path

Violations SHALL trigger:

- WHY file generation
- Classification as structural failure

---

## 10. Source Teaching Artifact

Derived from:

```
content/0/lupopedia/2026/04/content_folder_structure_explained.md
```

This artifact explains the reasoning. This PRD enforces the rules.
