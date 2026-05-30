---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/TOON_ORDERING_SPEC.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/TOON_ORDERING_SPEC.md"
  status: "active"
  when_updated: "20260411184008"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/toon-ordering-spec.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/toon-ordering-spec"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: "TOON Ordering Specification (Canonical v1.0.0)"
  summary: "Normative integer-indexed ordered-array rules for TOON memory and registry exports; referenced from PRD 16, 38, 51."
---
# file: TOON Ordering Specification — delegation: cursor:root — web_path: https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/TOON_ORDERING_SPEC.md

# TOON Ordering Specification (Canonical v1.0.0)

## 0. Repository hygiene (do not remove)

This file is **hand-authored** and **in scope** for **LUPOPEDIA HEADERS** (**PRD 16**). New or moved doctrine specs **MUST** include the full **`lupopedia.headers`** envelope, a non-empty **`dialog_transcript`**, and a tracked **`memory_key`** pair (**`.json`** master + **`.toon`** derived via **`python lupo-scripts/json_to_toon.py`**) under **`lupo-memory/`**. Bootstrap with **`python lupo-scripts/add_lupopedia_header_to_file.py path/to/file.md [--create]`**; validate with **`python lupo-scripts/validate_lupopedia_headers_universal.py path/to/file.md`**. **IDE agents:** do not paste body-only spec text without headers—workspace rules require the envelope (**`.cursor/rules/lupopedia-headers-mandatory.mdc`**, **`lupopedia-headers-file-order.mdc`**).

## 1. Purpose

TOON files MUST be deterministic, stable, and diff-friendly.  
All TOON structures MUST use ordered lists indexed by integer keys.  
No unordered maps, no floating keys, no implicit ordering.

## 2. Global Rules

1. All TOON files MUST begin with:

   - `toon.version: "1.0.0"`
   - `toon.kind: "<memory|schema|actor|table|registry|other>"`

2. All top-level logical structures MUST be arrays, not free-form objects.
3. Every array MUST be ordered by its first element (the index key).
4. Index keys MUST be:
   - integers
   - unique within their array
   - sequential (0..N) unless explicitly reserved for future use
5. TOON files MUST NOT contain:
   - unordered maps for primary structures
   - key-order-dependent semantics
   - schema drift between writer and reader

## 3. Standard TOON Patterns

### 3.1 Column Definition

```yaml
columns: [
  [0, "id", "int", "primary"],
  [1, "name", "varchar(255)", "required"],
  [2, "created_at", "datetime", "auto"],
  [3, "updated_at", "datetime", "auto"]
]
```

### 3.2 Memory Node

```yaml
memory: [
  [0, "key", "lupo-memory/captains_log/canonical/1026/04/20260411_share_we_play_a_game.toon"],
  [1, "summary", "A comedic account of herding AI agents for header migration."],
  [2, "tags", ["captains_log", "agents", "migration"]],
  [3, "trust_tier", "canonical"]
]
```

### 3.3 Actor Definition

```yaml
actors: [
  [0, "id", 1],
  [1, "name", "THOTH"],
  [2, "role", "graph_philosopher"],
  [3, "channel_key", "system_steward"]
]
```

### 3.4 Registry Entry

```yaml
patterns: [
  [0, "pattern_id", 10],
  [1, "name", "Registry Memory Node Self-Seeding"],
  [2, "severity", "high"],
  [3, "invariants", ["self_referential", "graph_root", "deterministic"]],
  [4, "applies_to", ["headers", "memory", "registry"]]
]
```

## 4. Ordering Rules

1. Arrays MUST be sorted by the integer index key.
2. Duplicate index keys render the TOON invalid.
3. Writers MUST preserve index keys and ordering when modifying entries.
4. Writers MUST NOT reorder arrays unless:
   - schema version changes, or
   - a deliberate reindexing migration is executed.

## 5. Writer Requirements

Any system writing TOON files MUST:

1. Load the existing TOON file (if present).
2. Parse arrays in order, preserving index keys.
3. Modify only the affected index entries.
4. Re-serialize without reordering unaffected entries.
5. Avoid full-file rewrites unless:
   - `toon.version` changes, or
   - the index layout itself changes.

## 6. Enforcement and Drift

- THOTH detects graph/ordering drift.
- ANUBIS detects orphaned or mismatched TOON nodes.
- KAIROS flags edge-case violations of ordering or schema.

Pattern #7 (Memory-Graph-Header Reconciliation) MUST treat TOON ordering as canonical truth.

This output complies with Lupopedia Constitutional Root Rules.
