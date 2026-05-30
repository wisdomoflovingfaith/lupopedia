---
lupopedia.headers:
  header_format_version: 4.1.8
  path_from_lupopedia_root: docs/prd/archive/16_lupopedia_headers.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/archive/16_lupopedia_headers.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/headers/canonical/1026/04/16_lupopedia_headers.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
  artifact_type: prd
  artifact_kind: specification
  channel_key: headers
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: prd
  prd_cluster: null
  title: 'PRD: Lupopedia File Headers and Verification'
  summary: 'Normative-only v4.1.1 header specification: canonical 22-field order, header-authoritative transcript slug, standard vs strict envelope validation modes, and ANUBIS integrity contract.'
---

# PRD 16: Lupopedia Headers (Normative Spec)

### Revision path

| Revision | Date (UTC) | Summary |
|---|---|---|
| v4.1.1 | 2026-04-15 | `content_*` alignment finalized, `default_collection_id` added, header authority clarified. |
| v4.1.0 | 2026-04-15 | `dialog_transcript` renamed to `transcript_jsonl`; canonical order reflowed. |
| v4.0.99 | 2026-04-10 | Dense canonical header family established. |

## 1. Purpose

This document defines the normative Lupopedia header contract for in-scope authored files:

- identity linkage (`content_id`, `file_path_from_root`, `content_slug`)
- routing linkage (`channel_key`, `federation_node_id`, `transcript_jsonl`)
- memory linkage (`memory_toon`, `atoms_toon`, `questions_toon`)

The header is a key ring, not a computation layer.

## 2. Scope

- In scope: authored Lupopedia docs/source covered by header doctrine.
- Out of scope: generated exports, binaries, third-party/vendor trees, lockfiles.
- Migration procedures and concrete examples are defined in companion docs:
  - `docs/prd/16_lupopedia_headers_migration.md`
  - `docs/prd/16_lupopedia_headers_examples.md`

## 3. Definitions

- **Header**: canonical 22-field ordered YAML contract.
- **Sidecar (`header_metadata`)**: derived JSON metadata companion.
- **Transcript slug**: `transcript_jsonl` logical DB lookup slug, not a filesystem path.
- **Memory pointer**: `memory_toon` path to `.toon` representation.
- **Orphan**: file with `content_id: null` (or missing header).

### 3.1 The three key systems

1. **Content system**: `content_id` -> `lupo_contents`
2. **Memory system**: `memory_toon` -> `.toon` memory export
3. **Transcript system**: `transcript_jsonl` -> thread lookup / message rows

### 3.2 Header responsibility boundaries

Header is responsible for:

- identity (`content_id`, `file_path_from_root`, `content_slug`)
- routing (`channel_key`, `federation_node_id`, `transcript_jsonl`)
- linkage (`memory_toon`, `atoms_toon`, `questions_toon`)

Header is NOT responsible for:

- business logic
- transformation logic
- computed state
- derived data

The header is a key ring, not a computation layer.

## 4. Header format

### 4.1 Canonical field count and order

Header field count and canonical order are authoritative from:

- `memory/atoms/lupopedia_global_constants.atom.toon`
- `constants.header_fields.count`
- `constants.header_fields.order`

Current canonical count: **22** fields.

### 4.2 Canonical field order (v4.1.1)

1. `header_format_version`
2. `file_path_from_root`
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
15. `thread_id`
16. `content_id`
17. `content_parent_id`
18. `content_slug`
19. `default_collection_id`
20. `lupopedia.schema`
21. `title`
22. `summary`

### 4.3 Validation modes

- **Standard mode (default)**:
  - canonical key set/order is required
  - exact physical line positions are recommended
- **Strict envelope mode (validator flag / CI)**:
  - canonical key set/order is required
  - validators may enforce fixed line positioning

Exact line-position checks are validator strictness controls, not a universal authoring requirement.

## 5. Sidecar (`header_metadata`)

- Sidecar is derived from header values.
- `transcript_jsonl` in sidecar must be synchronized from header.
- Sidecar mismatch is a tooling synchronization problem, not a dual-authority model.

## 6. Transcript header authority rule

- `transcript_jsonl` in header is the single source of truth.
- Sidecar `transcript_jsonl` is derived/synchronized from header.
- Validators SHOULD warn on mismatch (`HDR_DUAL_MISMATCH`) as sync drift.
- `transcript_jsonl` is a DB slug (`{federation_node_id}/{channel_key}/{prd_cluster}`), not a file path.

## 7. Transcript system (DB-first)

- Canonical writes go through PHP endpoint and DB storage.
- Slug resolution targets thread/message rows.
- Optional `.jsonl` exports are derived artifacts only.
- Channel-scoped transcript artifacts live under `channels/` when generated/exported.

## 8. Memory path year encoding

For `trust_tier: canonical`, `memory_toon` uses display year = calendar year - 1000.

- Source: atom `constants.year_offset.canonical_offset`
- Example: 2026 -> 1026

## 9. Questions TOON path convention

When `questions_toon` is non-null:

- suffix must be `.questions.toon`
- year uses real calendar year (e.g. 2026), not 1026
- channel/slug align with `memory_toon`
- pattern: `memory/{channel_key}/questions/{YYYY}/{MM}/{slug}.questions.toon`

## 10. Validator rules (normative)

Validators must enforce:

- canonical 22-field order
- required key presence
- type checks including:
  - `content_id` = null or integer
  - `content_parent_id` = null or integer
  - `default_collection_id` = null or integer (`HDR_DEFAULT_COLLECTION_INVALID`)
- `transcript_jsonl` header authority semantics
- legacy alias handling per migration policy

## 11. Migration policy boundary

Migration compatibility is defined in `16_lupopedia_headers_migration.md`.

Hard policy:

- 4.1.3: remove `pk_*` alias support
- 4.1.5: remove `dialog_transcript` alias support
- 4.2.0: remove all migration compatibility aliases

There is no Lupopedia-to-Lupopedia upgrade path prior to 4.2.0.

## 12. ANUBIS operational contract

ANUBIS (`actor_id: 9`) is an integrity component for orphan resolution:

- idempotent processing; no duplicate content rows
- deterministic orphan detection (`content_id: null` or missing header)
- retry-safe failure handling
- no partial success claims
- no duplicate row creation under concurrency
- all resolve/skip/failure actions logged

THOTH remains `actor_id: 26` and is a separate verification actor.

## 13. File naming doctrine separation

- Documentation and memory artifacts: `lowercase_with_underscores`
- PHP runtime/class files: exempt from mass normalization during this phase
- Runtime naming must not be mass-normalized without loader/include-path validation

## 14. Companion documents

- Migration guide: `docs/prd/16_lupopedia_headers_migration.md`
- Examples: `docs/prd/16_lupopedia_headers_examples.md`

This output complies with Lupopedia Constitutional Root Rules.
---
lupopedia.headers:
  header_format_version: "4.1.1"
  file_path_from_root: "docs/prd/16_lupopedia_headers.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/16_lupopedia_headers.md"
  status: "active"
  when_updated: "20260415075121"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/headers/canonical/1026/04/16_lupopedia_headers.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/headers/lupopedia-headers"
  artifact_type: prd
  artifact_kind: specification
  channel_key: "headers"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  title: "PRD: Lupopedia File Headers and Verification"
  summary: "Normative spec for the 22-field PRD 16 v4.1.1 header: sidecar derived from header authority, DB-first transcripts, trust-ladder year encoding, validator taxonomy, and DB-column alignment (content_id / content_parent_id / content_slug / default_collection_id)"
---
<!-- TRUST_LADDER_NOTE: Canonical memory_toon year segment 1026 means calendar 2026 minus 1000 (past-as-trust). Lower display year sorts before staging. Do not "fix" 1026 to 2026 for new verified canonical paths. See CHRONOLOGICAL_TRUST_LADDER.md and section 8.1. -->

# PRD: Lupopedia File Headers and Verification

**Self-check:** This file's YAML front matter follows **§4.2** canonical key order (**22** scalar keys under **`lupopedia.headers:`**, **§4.3**). **`transcript_jsonl`** is a **DB lookup slug** (`{federation_node_id}/{channel_key}/{prd_cluster}`), **not** a filesystem path (**§4.2** field **10**, **§6**). The slug's middle segment is the transcript channel (`development` here); the header **`channel_key`** is **`headers`** (this document's home channel). Both are valid and need not match. **§6** defines header authority; sidecar transcript values are derived from header values. **Freeze / CI:** ensure the file at **`memory_toon`** exists and is **tracked** in VCS before declaring the spec shipped (path is normative; repo state must follow).

### Change Log (document revision)

| Revision | Date (UTC) | Summary |
|----------|------------|---------|
| **v4.1.1** | 2026-04-15 | Added `default_collection_id` after `content_slug`, finalized 22-field canonical order, and aligned validator references for `content_*` plus collection routing. |
| **v4.1.0** | 2026-04-15 | Renamed `dialog_transcript` to `transcript_jsonl` and performed major canonical key-order reflow (including moving `file_path_from_root`, `status`, `channel_key`, `federation_node_id`, and `lupopedia.schema`). |
| **v4.0.99** | 2026-04-10 | Introduced the dense 22-key header model and migration baseline replacing prior legacy field layouts. |

## Overview

This PRD defines the canonical requirements, structure, and verification process for Lupopedia file headers on **in-scope authored** files. Those files **must** include a YAML-formatted `lupopedia.headers` block (or the comment-embedded equivalent for non-Markdown types), which encodes file identity, schema, and verification metadata. **Headers are not required on every path in the repository** - see **[Header applicability and scope](#header-applicability-and-scope)** (binaries, generated exports, third-party trees, and similar are out of scope). Verification may be attributed to **actors** or **agents**; verifier identity lives in the **memory sidecar** `footer` object (see **§5** and [Author vs Verifier](#author-vs-verifier-distinction)).

**Normative specification:** **[§1–§20 - PRD 16 v4.0.99](#1-purpose)** (RFC-style). Older supplementary sections below must stay aligned with **§4-§15** (see [Supplementary material](#supplementary-material-below)).

---

# PRD 16 v4.1.1 - Lupopedia Headers Specification (RFC) <!-- v4.1.1 update -->

*Ultra-clean, normative sections. **v4.1.1** sets the canonical grid to **22** scalars (**§4**) with direct DB alignment on `content_id` / `content_parent_id` / `content_slug` / `default_collection_id`. Legacy `pk_*` keys are accepted only as migration aliases.*

**Version labels (normative):** **PRD 16 v4.1.1** names the current **Lupopedia Headers** ruleset (this document). In files, set **`header_format_version: "4.1.x"`** (e.g. **`"4.1.1"`**), aligned with the current platform patch line. During migration, validators MAY accept older families as documented in **§4.3**.

## 1. Purpose

- Define the canonical header specification for **Lupopedia Headers v4.1.1** (header + sidecar + transcript + memory).
- Establish the **fixed header field set** (**22** scalar fields), with sidecar transcript values derived from header values.
- Define the **`header_metadata`** memory sidecar schema and file location.
- Define the **DB-first** transcript pipeline (PHP canonical writer; Python/IDE callers only).
- Define **validator** rules (reject v1/v2; enforce **§4** **v4.1.1** twenty-two-key canonical order and header-authoritative transcript semantics).
- **Normative ceilings** for header schema evolution (e.g. **22** keys in **4.1.1**, four trust tiers) are documented with other system limits in **`docs/prd/99_limits_for_everything_and_why.md`** (**PRD 99**).
- Define **versioning**, **backlog migration**, and **freeze** rules.
- Define **interactive legacy header migration** (**§20**) — no blind mass migration; per-file memory and edge alignment.
- Explain the **runtime purpose** of headers: each key field is a **primary database key** linking the file to its `lupo_contents` row (engagement hub), its `.toon` memory node (compressed knowledge graph), and its `lupo_dialog_messages` thread (the WHY behind every decision). **Without a complete header, the file is an orphan — invisible to the database, the memory graph, and the transcript system.** See **§3.1**.
- Document the **ZIP distribution model** (**§16**): Lupopedia ships as a downloadable ZIP (like WordPress or phpBB). Headers are **not stripped** from the distribution — they are **read at runtime by the PHP application**. CI validates headers before the ZIP is built: not for GitHub, but to ensure every user's installed copy works correctly.
- Document **ANUBIS** requirements (**§15**): the system actor responsible for detecting filesystem-created file orphans and completing their database linkage. ANUBIS is **not aspirational** — it is required for the two-path content model (**§15**) to function safely.

### memory_key → memory_toon (Migration Note)
The field previously named `memory_key` has been renamed to `memory_toon`.
Reason:
- The field does not represent a generic key
- It specifically stores a path to a `.toon` file
- The new name aligns with system terminology and reduces ambiguity
This is a documentation-first migration. Code and schema updates will follow in later phases. `memory_key` may still exist in legacy artifacts; migration will be handled in later phases.

### dialog_transcript → transcript_jsonl (Migration Note)
The field previously named `dialog_transcript` has been renamed to `transcript_jsonl` in **v4.1.0**.
Reason:
- The field name `dialog_transcript` implied a dialog-specific or filesystem-based transcript path
- `transcript_jsonl` better conveys the field's actual purpose: an append-only JSONL log identifier for agent messages and observer reads
- The new name is consistent with the JSONL format used for transcript storage
- Type: string or null. Description: **DB lookup slug**, not a filesystem path. Purpose: append-only log key for agent messages and observer reads.
Migration: Validators MUST accept `dialog_transcript` as a legacy alias for `transcript_jsonl` during migration and emit **`HDR_DIALOG_TRANSCRIPT_RENAMED`** (WARN) when the old name is encountered. New artifacts MUST use `transcript_jsonl`. Simultaneously, the canonical field order has been restructured — see §4.2 and the v4.1.0 changelog entry. Migration script: `scripts/rename_dialog_transcript_to_transcript_jsonl.py`.

## 2. Scope

- **In scope:** All Lupopedia-authored, in-scope files per [Header applicability and scope](#header-applicability-and-scope) (`.md`, `.php`, `.js`, `.py`, `.sql`, `.html`, `.htm`, hand `.txt`, pseudocode under `decisions/pseudocode/`, etc.).
- **Out of scope:** Binaries, vendor trees, generated exports (TOON JSON, minified bundles), lockfiles, and paths explicitly listed as N/A.
- **This PRD defines:** Header YAML + sidecar JSON + transcript rows + compaction outputs + validator expectations.

## 3. Definitions

| Term | Meaning |
|------|---------|
| **Header** | YAML `lupopedia.headers` identity block (fixed keys, single-line values). The header is not metadata — it is the **primary key set** linking the file to the database, memory graph, and dialog thread. |
| **Sidecar** | JSON file, schema **`header_metadata`**, sibling to semantic content; not embedded in the authored file body. |
| **Transcript** | DB-first audit log of agent/human actions (JSONL *semantics*, stored as rows in `lupo_dialog_messages`). |
| **Memory node** | Compacted semantic representation of a file's content stored as a `.toon` file at `memory_toon` and/or a DB row in `lupo_memory_nodes` per PRD 38. The `.toon` contains entities, decisions, relationships, and summary — everything an agent needs to understand the file without reading it in full. |
| **TOON file** | The `.toon` file at the path named by `memory_toon`. Contains a compressed, graph-structured representation of the file — its entities, decisions, relationships, and summary. **The `.toon` is the file's brain. `memory_toon` is the pointer to that brain.** |
| **Orphan** | A file with `content_id: null` — present on disk but having no `lupo_contents` row, no dialog thread linkage, and no memory node. An orphan is invisible to the engagement system, the memory graph, and the transcript history. ANUBIS resolves orphans. |
| **ANUBIS** | System orphan processor. **actor_id/agent_id are sourced from** `memory/atoms/lupopedia_global_constants.atom.toon` (`constants.actors.anubis`, `constants.agents.anubis`). ANUBIS creates `lupo_contents` rows, resolves/creates transcript thread bindings, and writes completed headers for filesystem-created files. |
| **Dialog Thread** | The conversation history associated with a file. Stored as rows in `lupo_dialog_messages`, indexed by `thread_id`. The `transcript_jsonl` header field is the slug that resolves to this thread. **The file says WHAT; the thread says WHY.** Both are required for complete understanding of any decision or specification. |
| **Channel** | Logical discussion context; identified by **`channel_key`** (no silent session default for writes). |
| **Federation node** | `federation_node_id` tier (0 core, 1 local install, 2+ external). |
| **Trust tier** | Global v3 header classification (**Option A**): **REQUIRED** on every in-scope header; drives **`memory_toon`** path segment (**§5.2**). Semantics are **documentation / export layout** (e.g. **`canonical`** = verified/normative copy). **Not** the same claim as Trust Ladder **packed row ID** bands on **`lupo_memory_nodes`** (PRD 38) - aligned vocabulary, different layer. |
| **ZIP distribution** | The packaging format for Lupopedia: a downloadable archive (like WordPress or phpBB) containing all PHP source, PRDs, `.toon` files, sidecar JSON, and headers. **Headers ship inside the ZIP and are read at runtime by the PHP application.** See **§16**. |
| **questions_toon** | The `.questions.toon` file associated with a Lupopedia file. Contains structured Q&A pairs: anticipated questions about the file's content + authoritative answers drawn from the file body and its dialog transcript. `null` until the Q&A system generates it. See **§19**. |
| **Q&A TOON** | Synonym for `questions_toon` file. A specialized `.toon` variant for the Q&A surface — distinct from the memory `.toon` at `memory_toon`, which contains graph-structured knowledge. The Q&A TOON contains question/answer pairs optimized for agent context injection and human FAQ display. |
| **atoms_toon** | The `.atoms.toon` file associated with a Lupopedia file. Contains **immutable truths** about the artifact's domain — canonical definitions, version invariants, and structural rules that must not silently drift. The header field `atoms_toon` is a nullable pointer to this file. **THOTH** (future) will use this pointer to detect drift: if the file's content contradicts its `.atoms.toon`, THOTH flags the contradiction. `null` until an atoms file is created. See **§4.2** field **9** and `docs/doctrine/lupopedia-headers/atoms_toon_schema.md`. |
| **THOTH** | Future truth guardian and constitutional checker. **actor_id/agent_id are sourced from** `memory/atoms/lupopedia_global_constants.atom.toon` (`constants.actors.thoth`, `constants.agents.thoth`). THOTH cross-references content against immutable atoms and raises `[ALERT]` on contradiction. |

### Single Source of Truth: Global Constants Atom

All global constants (actor IDs, agent IDs, trust tiers, year offsets, constitutional limits) are defined in:

**`memory/atoms/lupopedia_global_constants.atom.toon`**

This atom is the authoritative source. PRD text is descriptive; the atom is prescriptive. Seed SQL is derived from the atom. Validators MAY check that header values match the atom.

### THOTH

- **actor_id:** See `lupopedia_global_constants.atom.toon` -> `constants.actors.thoth`
- **agent_id:** See atom -> `constants.agents.thoth`
- **Role:** Truth guardian — reads all dialog messages, compares against constitutional truth, raises `[ALERT]` on discrepancy

### ANUBIS

- **actor_id:** See atom -> `constants.actors.anubis`
- **agent_id:** See atom -> `constants.agents.anubis`
- **Role:** Orphan processor — detects files with `content_id: null`, creates `lupo_contents` rows, writes headers

### LILITH

- **actor_id:** See atom -> `constants.actors.lilith`
- **agent_id:** See atom -> `constants.agents.lilith`
- **Role:** Constitutional auditor — observes, reviews, reports, escalates

### Full Actor Table (from Atom)

| Entity | actor_id | agent_id |
|--------|----------|----------|
| system | 0 | — |
| wolfie | 1 | — |
| lilith | 2 | 2 |
| rose | 3 | 3 |
| anubis | 9 | 9 |
| hermes | 15 | 15 |
| iris | 16 | 16 |
| thoth | 26 | 26 |
| vishwakarma | 28 | 28 |
| countermeasure | 111 | — |
| kairos | 115 | 115 |
| heimdall | 108 | 108 |

*For the authoritative list, see `memory/atoms/lupopedia_global_constants.atom.toon`.*

## 3.1 The Three Key Systems

**The header is not a comment. The header is not metadata. The header is the key ring that connects the file to three core database systems.**

Every in-scope file in Lupopedia is simultaneously a node in three systems. The header fields are the foreign keys that make those connections explicit and machine-resolvable at runtime.

### 3.1.1 System 1 — Content Database (`content_id` → `lupo_contents`)

**Every piece of content has a row in `lupo_contents`.** The header field `content_id` is the foreign key to that row.

```
File on disk:   docs/prd/16_lupopedia_headers.md
                      |
                      ▼  (header field)
                content_id: 12345
                      |
                      ▼  (SQL)
Database:       SELECT * FROM lupo_contents WHERE id = 12345
                      |
                      ▼
Returns:        content record — author, created_at, version, permissions
```

**What `content_id` enables:**
- **Engagement** (likes, comments, shares) targets `content_id` — not the file path
- **Version history** tracks `content_id` across file renames
- **Search indexing** is keyed by `content_id`
- **Permissions** are checked against `content_id`

**When `content_id` is `null`:** The file has no database record. It is an **orphan** — invisible to engagement, search, permissions, and version history. The file exists on disk; the system does not know it exists. ANUBIS resolves this by creating the `lupo_contents` row and writing the `content_id` back into the header.

#### 3.1.1.1 Exact `content_id` Creation Flow (normative)

1. **Web/editor path (preferred):**
   - Actor submits a new artifact through a write surface.
   - PHP inserts a `lupo_contents` row with `content_slug`, `content_parent_id`, `default_collection_id`, `artifact_type`, `created_by`, and UTC timestamps.
   - Database returns the new `id`.
   - Writer back-fills header `content_id` with that integer in the same write transaction.
2. **Filesystem/ANUBIS path (fallback):**
   - ANUBIS scans in-scope files for `content_id: null`.
   - ANUBIS derives/validates `content_slug`, `content_parent_id`, and `default_collection_id` from header + file context.
   - ANUBIS inserts into `lupo_contents` as `created_by = 9`.
   - ANUBIS rewrites only the header envelope with the new `content_id`.
   - ANUBIS logs the action in actor/audit records.

**Validation rules:**
- `content_id` MUST be `null` or a positive integer.
- `content_id: ''` is invalid.
- If `content_id` is non-null, `content_slug` SHOULD match the DB slug for that row (`HDR_CONTENT_SLUG_MISMATCH`).

### 3.1.2 System 2 — Memory Graph (`memory_toon` → `.toon` file)

**Every file has a memory node.** The memory node contains a compressed, graph-structured representation of the file's content — its key entities, decisions, relationships, and summary — stored in a `.toon` file (TOON = The Object Orientation Notation, Lupopedia's compact graph serialization). The header field `memory_toon` is the path to that `.toon` file.

```
File on disk:   docs/prd/16_lupopedia_headers.md
                      |
                      ▼  (header field)
                memory_toon: "memory/headers/canonical/1026/04/16_lupopedia_headers.toon"
                      |
                      ▼  (filesystem read)
.toon file:     {
                  "type": "prd_memory",
                  "summary": "22-field header spec with sidecar, dual-field, trust-ladder",
                  "entities": ["header_fields", "sidecar", "validator", "ANUBIS"],
                  "decisions": ["22 fields required", "25-line envelope", "dual-field rule"],
                  "edges": [{"to": "prd/00", "type": "constitutional_anchor"}, ...]
                }
```

**What `memory_toon` / the `.toon` enables:**
- The system loads a file's memory **without parsing the full Markdown** — the `.toon` alone is sufficient
- The **memory graph** traverses relationships between files using `.toon` edges
- **External agents** can be given just the `.toon` and understand everything important about the file
- **Compaction** (§10) reads transcript rows and writes updated `.toon` nodes — the graph stays current

**The `.toon` is the file's brain. `memory_toon` is the pointer to that brain.** An agent reading only the `.toon` at `memory_toon` knows what the file decided, who its related files are, and what its status is — without reading 1,500 lines of Markdown.

### 3.1.3 System 3 — Dialog Thread (`transcript_jsonl` → `lupo_dialog_messages`)

**Every file has a conversation thread.** When people or agents discuss a file — debating alternatives, recording decisions, explaining rationale — that dialog is stored in the database keyed by the thread resolved from `transcript_jsonl`.

```
File on disk:   docs/prd/16_lupopedia_headers.md
                      |
                      ▼  (header field)
                transcript_jsonl: "0/headers/lupopedia-headers"
                      |
                      ▼  (DB lookup)
                SELECT thread_id FROM lupo_thread_registry
                WHERE federation_node_id = 0
                  AND channel_key = 'headers'
                  AND prd_cluster = 'lupopedia-headers'
                      |
                      ▼  (resolved)
                thread_id: 5678
                      |
                      ▼  (messages)
                SELECT * FROM lupo_dialog_messages
                WHERE thread_id = 5678
                ORDER BY created_ymdhis
```

**What `transcript_jsonl` enables:**
- **The WHY is in the transcript.** The file says WHAT was decided. The transcript says WHY — the debates, alternatives considered, and reasoning behind each choice.
- When reading a PRD, loading its transcript gives complete context for every decision
- Agents fetch the transcript before acting on a file — they need the WHY to act correctly
- External agents with no database access can be given the transcript as part of their prompt context

**Without `transcript_jsonl`:** The file's reasoning is inaccessible. Agents acting on the file have only the decisions — not the context that produced them. This leads to re-litigated debates, contradictory changes, and compounding errors.

### 3.1.4 Summary: Header as Key Ring

| Header Field | System | Database Object | What It Unlocks |
|---|---|---|---|
| `content_id` | Content DB | `lupo_contents` row | Engagement, search, permissions, versioning |
| `memory_toon` | Memory graph | `.toon` file + `lupo_memory_nodes` row | Compressed knowledge, graph traversal, agent context |
| `transcript_jsonl` | Dialog thread | `lupo_dialog_messages` rows | The WHY — reasoning, debates, decisions |
| `channel_key` | Channel | `lupo_channels` row | Discussion context and routing |
| `file_path_from_root` | Identity | — | Path for agents with no filesystem; stable DB key |
| `trust_tier` | Memory layout | `memory_toon` path segment | Graph tier and year-offset encoding |

**A file without a complete header is an orphan.** It exists on disk, but it is disconnected from every system that makes it useful. The header is not documentation about the file — it is the file's connection to the platform.

## 3.2 Header Responsibility Boundaries

The header is a **key ring**, not a computation layer.

Header responsibilities:
- **Identity:** `content_id`, `file_path_from_root`
- **Routing:** `channel_key`, `transcript_jsonl`
- **Linkage:** `memory_toon`, `atoms_toon`, `default_collection_id`

Header non-responsibilities:
- Business logic
- Transformation logic
- Computed state
- Other derived data that belongs to sidecars, runtime services, or database projections

## 4. Header Format (v4.1.1) <!-- v4.1.1 update -->

### 4.1 Fixed Field Set

- **22** scalar fields are always present in the YAML mapping (use empty string `''` where a conditional string field does not apply).
- **Transcript authority model:** `transcript_jsonl` in the header is the single source of truth; sidecar `transcript_jsonl` is derived/synchronized from header values.
- **`trust_tier` (Option A):** **REQUIRED** for **all** in-scope headers - no conditional-by-`artifact_type` empty value. See **§4.2** field 6 and **§3** definition.
- **No** YAML arrays in the header. **No** multi-line or folded scalars. **No** keys other than those listed in **§4.2**.
- **`atoms_toon`:** MUST be YAML **`null`** when no atom file exists; empty string **`''`** is **forbidden** (**`HDR_ATOMS_TOON_SUFFIX`**). When non-null, MUST be a string ending in **`.atoms.toon`** or **`.atom.toon`**. File existence is NOT enforced by the validator. **Replaces deprecated `module` field** (see changelog).
- **`module` (deprecated):** Validators emit **`HDR_MODULE_DEPRECATED`** (WARN) when `module` is found. Validators MUST accept `module` as a legacy alias for `atoms_toon` during migration. New artifacts MUST use `atoms_toon`.
- **`dialog_transcript` (deprecated):** Validators emit **`HDR_DIALOG_TRANSCRIPT_RENAMED`** (WARN) when `dialog_transcript` is found. Validators MUST accept `dialog_transcript` as a legacy alias for `transcript_jsonl` during migration. New artifacts MUST use `transcript_jsonl`.

### 4.2 Field List (22 fields) - canonical order <!-- v4.1.1 update -->

**Header field count:** See `memory/atoms/lupopedia_global_constants.atom.toon` -> `constants.header_fields.count`

**Key order:** See atom -> `constants.header_fields.order`

**Key presence (normative).** Every in-scope artifact MUST list **all 22 keys** below in this exact order. **No key may be omitted** - omission is invalid (**HDR_MISSING_KEY**, **HDR_LINE_COUNT**, **`HDR_MISSING_CLOSE`**). In strict-envelope validator mode, this is typically enforced as **22** contiguous single-line rows on lines **3–24** with no blank or whitespace-only lines between keys. In standard authoring mode, validators SHOULD prioritize parseable ordered keys over exact physical line numbers. When a value is unused, writers MUST still emit the key line and use a **sentinel**: YAML **`null`** for **`content_id`**, **`content_parent_id`**, **`default_collection_id`**, and **`atoms_toon`** where **§4.2** allows; empty string **`''`** for string fields that **§4.2** / cross-field rules allow to be empty (**`thread_id`**, **`title`**, **`status`**, and **`summary`** in the allowed cases). The words **optional field** MUST NOT be read as permission to **omit the key**. **Legacy transition:** validators MAY accept deprecated key names **`pk_id`**, **`pk_slug`**, **`parent_pk_id`** (and older `prd_*` aliases) as migration aliases mapping to **`content_id`**, **`content_slug`**, and **`content_parent_id`**; emit **`HDR_PK_LEGACY_ALIAS`** warning.

Keys MUST appear in this order for machine validation:

1. `header_format_version` - string in the **`4.1.x`** family (example: **`"4.1.1"`**). Validators MUST accept any `4.1.*` patch; also accept `4.0.*` and legacy `3` for older artifacts during migration. **For new artifacts,** SHOULD equal **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`** from **`config/global_atoms.yaml`**.
2. `file_path_from_root` - REQUIRED for every artifact; repo-relative; for DB-only content use a **synthetic** stable slug path reserved for imports (still a single line).
3. `web_path` - full public URL including `/lupopedia/` when applicable. **MUST use HTTPS** in production (e.g. `https://www.lupopedia.com/lupopedia/...`): when the value is an absolute URL (any `http://` or `https://` scheme), it **MUST** use **`https://`**. Validators **MUST** reject **`http://`** unless run with **`--development`** (**§12.4**). Repo-relative or scheme-less values are unchanged by this rule.
4. `status` - required where cross-field rules demand; otherwise `''`.
5. `when_updated` - quoted UTC `YYYYMMDDHHIISS`.
6. `trust_tier` - non-empty string; MUST be one of **`seed`**, **`canonical`**, **`staging`**, **`archive`** (same closed set as [LUPOPEDIA_HEADERS_FORMAT.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)). **`seed`** - install-time / immutable anchor exports; **`canonical`** - verified or normative "source" documentation (including PRDs); **`staging`** - work-in-progress or unverified exports; **`archive`** - deprecated or superseded artifacts kept for lineage. Validators **MUST** reject any other value. New values require a **PRD revision** and **validator bump**.
7. `questions_toon` - path to the `.questions.toon` file for this artifact, or YAML **`null`** if the Q&A file has not yet been generated. When non-null, MUST end with **`.questions.toon`** (validator: **`HDR_QUESTIONS_TOON_SUFFIX`**). See **§19** for the planned Q&A system. **Replaces the deprecated `last_modified_utc` field** (renamed in v4.0.99: timestamp was redundant with `when_updated`; field now carries unique semantic purpose).
8. `memory_toon` - string path to the `.toon` file representing the memory node for this artifact (layout §5.2, JSON↔TOON pairing §5.2.2); connects the artifact to its canonical memory representation in the Lupopedia memory graph. MUST align §8.1 year segment with the memory node's graph/epistemic status, not necessarily with header status alone (§8.1 clarification). The path MUST end with the `.toon` extension (no .json / bare stem in this field). This field is REQUIRED. The .toon file at this path contains memory graph metadata; if header parsing is non-deterministic, memory_toon extraction can fail and graph loading breaks.
9. **`atoms_toon`** - YAML **`null`** or string ending in **`.atoms.toon`** (general atoms sidecars) or **`.atom.toon`** (global constants atom); nullable pointer to immutable atom truth files for this artifact. Atom files contain truths that must not silently drift — canonical definitions, version invariants, structural rules, and global constants. **`null`** when no atom file exists (always valid). Empty string **`''`** is **forbidden** — use **`null`** instead (validator: **`HDR_ATOMS_TOON_SUFFIX`**). File existence is NOT enforced by the validator. **THOTH** (future) will use this pointer for corpus-wide integrity validation. **Replaces deprecated `module` field** (legacy subsystem label); validators accept `module` as alias during migration (emit **`HDR_MODULE_DEPRECATED`** WARN). Schema: `docs/doctrine/lupopedia-headers/atoms_toon_schema.md`.
10. **`transcript_jsonl`** - REQUIRED non-empty **logical transcript identifier** for the PHP transcript endpoint (**§9**). Type: string or null. Description: **DB lookup slug** (not a filesystem path). Purpose: append-only log key for agent messages and observer reads. **Standard form (recommended):** database lookup slug **`{federation_node_id}/{channel_key}/{prd_cluster}`** - slashes are part of the slug namespace, not OS path separators. The PHP endpoint resolves the slug to a DB thread; **no file I/O** is performed based on this value alone. The slug MAY contain `.jsonl` in the string for historical naming; that suffix **does not** imply a file exists and **must not** trigger filesystem access. Validators MUST NOT reject values merely because they contain `/` or `.jsonl`. Sidecar `transcript_jsonl` MUST be derived from this header value (**§6**). **Per-file semantic `version` is not a header field** - use **`header_format_version`** for the YAML schema revision, **git** / **`CHANGELOG.md`** / document title (e.g. PRD 16 v4.1.1) for content lineage, and sidecar **`footer.last_verified`** for freshness. **Renamed from `dialog_transcript` in v4.1.0** (see migration note in §1 and changelog).
11. `artifact_type` - taxonomy (see [Artifact Type Taxonomy](#artifact-type-taxonomy)).
12. `artifact_kind` - taxonomy (see [Artifact Kind Taxonomy](#artifact-kind-taxonomy)).
13. `channel_key` - non-empty string; writers MUST NOT infer from session without user/actor confirmation.
14. `federation_node_id` - integer `0`, `1`, or `2+`.
15. `thread_id` - rules by **`artifact_type`** in **§4.2.1** (normative table). When a thread is **explicitly bound** to a non-`discussion` artifact, non-empty is allowed; otherwise default to **`''`**.
16. `content_id` - **integer or NULL**. In YAML use **lowercase** `null` only: `content_id: null`. **`NULL`** means not yet imported into the database (no **`lupo_contents`** row linked to this artifact). If non-NULL, it MUST reference a valid row in **`lupo_contents`**. Validators MUST accept `null` and reject empty string.
17. `content_parent_id` - **integer or NULL**. Parent content row in `lupo_contents` when hierarchical linkage exists; otherwise `null`. Empty string is invalid.
18. `content_slug` - string or **`''`**. Canonical slug for `lupo_contents` row alignment. For slug-bearing artifacts this SHOULD be non-empty and match DB slug.
19. `default_collection_id` - **integer or NULL**. Default collection-tabs container for this content (see Collections PRD). Use `null` when no default collection mapping exists.
20. `lupopedia.schema` - **closed enum**; MUST be exactly one of: **`prd`**, **`doctrine`**, **`documentation`**, **`implementation`**, **`discussion`**, **`changelog`**, **`architecture`**, **`specification`**. Validators **MUST** reject any other value.
21. `title` - required for `prd` (and as needed by cross-field rules); otherwise `''`.
22. **`summary`** - string; single-line human-readable summary or short description of the artifact. Use **`''`** when no summary is provided. **Replaces** the previously proposed **`note`** field (do not add **`note`** to headers).

### 4.2.1 `thread_id` rules (normative)

| `artifact_type` | `thread_id` rule |
|-----------------|------------------|
| `discussion` | **REQUIRED**, non-empty |
| `prd` | MUST be **`''`** |
| all others | **`''`** unless a thread is **explicitly bound** to this artifact (documented binding; validators MAY require a sidecar edge or manifest pointer when non-empty) |

**`prd` and threading:** For **`artifact_type: prd`**, **`thread_id`** MUST remain empty. **Do not** carry thread linkage in **`thread_id`** for PRDs—use **`transcript_jsonl`** (**§4.2** field **10**) as the **only** transcript/thread routing slug in the header. Non-empty **`thread_id`** on a PRD is invalid.

**`transcript_jsonl` vs header `channel_key`:** The slug **`{federation_node_id}/{channel_key}/{prd_cluster}`** uses the **transcript** channel in the middle segment (e.g. **`development`**). The header's **`channel_key`** names the **document's** home channel (e.g. **`headers`** for this PRD). Those segments **need not** match; both may be valid at once (**§6** still requires header and **`header_metadata`** **`transcript_jsonl`** to match **byte-for-byte**).

### 4.3 Header Rules <!-- v4.1.1 update -->

**Validation modes (normative):**
- **Standard mode (default authoring mode):** canonical key set and canonical key order are REQUIRED; exact physical line positions are RECOMMENDED and SHOULD NOT be treated as a hard requirement.
- **Strict envelope mode (CI / strict validator mode):** canonical key set + canonical key order remain REQUIRED; exact envelope line positioning MAY be enforced as a validator flag.

1. **`header_format_version` MUST be string `4.1.x`** in YAML for new artifacts (example: `"4.1.1"`). Validators MUST also accept `4.0.*` and legacy **`3`** for backward compatibility during migration.
2. **`file_path_from_root` is REQUIRED** for all files (synthetic allowed for DB-only).
3. **No arrays** in header YAML. **No** `tags` list in header (tags live in sidecar only).
4. **No multi-line values** - each `key: value` occupies **one physical line** in the front matter block.
5. **No extra keys** - validators MUST fail on any key not listed in **§4.2** (after **legacy alias** normalization if implemented).
6. **Fixed key order** as in **§4.2**.
7. **Header position baseline:** in standard mode, header MUST be at file start and structurally parseable; in strict envelope mode, validators SHOULD enforce absolute envelope positioning (for Markdown: opening `---` line 1, `lupopedia.headers:` line 2, canonical rows contiguous).
8. **Markdown strict envelope profile (strict mode only):** The **entire** header occupies **lines 1–25**. Line **1** = opening `---`; line **2** = `lupopedia.headers:`; lines **3–24** = **22** contiguous single-line `key: value` rows (**§4.2** order; **no** blank or whitespace-only lines between keys); line **25** = closing `---`; line **26** = first body line (**`HDR_EMPTY_BODY`** if missing/blank). Validators enforce this profile when strict mode is enabled.
9. **Comment-embedded strict envelope profile (strict mode only):** Opening/closing fences and optional shebang consume lines outside the YAML grid. The inner grid remains `lupopedia.headers:` + **22** contiguous single-line scalar rows in §4.2 order. Strict validators enforce the format-specific budgets below.

   | Format | Header block (1-based line numbers) | First body line (non-whitespace; **`HDR_EMPTY_BODY`**) |
   |--------|-------------------------------------|--------------------------------------------------------|
   | **Markdown** | **Lines 1–25** (see rule **8**) | Line **26** |
   | **Python** + shebang | **Line 1** = `#!/usr/bin/env python3`; **lines 2–26** = **25-line** `#` block (open fence, `# lupopedia.headers:`, **22** key lines, close fence) | Line **27** |
   | **Python** without shebang | **Lines 1–25** = same **25-line** `#` block | Line **26** |
   | **PHP** + shebang (preferred for CLI) | **Line 1** = ``#!/usr/bin/env php``; **line 2** = ``<?php``; **lines 3–27** = same **25-line** ``#`` block as Python (open ``# ---``, ``# lupopedia.headers:``, **22** ``#   key:`` rows, close ``# ---``) | Line **28** |
   | **PHP** without shebang (preferred ``#`` grid) | **Line 1** = ``<?php``; **lines 2–26** = **25-line** ``#`` block (same inner layout as Python) | Line **27** |
   | **PHP** star-docblock (legacy) | **Line 1** = ``<?php``; **legacy envelope accepted during migration** | First line after ``*/`` |
   | **JavaScript** | **Lines 1–25** = **`/*`**, **` * lupopedia.headers:`**, **22** key lines **` *   key: value`**, **` */`** | Line **26** |
   | **HTML / SQL / Shell** | **Lines 1–25** = language comment wrapper, **`lupopedia.headers:`** line, **22** key lines, closing comment — **25** lines for the block | Line **26** |

   **Invariant (all formats):** The first physical line after the header block SHOULD contain non-whitespace body content in standard mode and MUST in strict envelope mode. (Exception: **`--development`** may downgrade **`HDR_EMPTY_BODY`** to warning where documented in **§12.4**.) Validators emit **`HDR_PYTHON_*`**, **`HDR_PHP_*`**, and **`HDR_JS_HEADER`** as implemented.
10. **Single header only:** Each file MUST contain exactly **one** `lupopedia.headers` YAML front matter block at the document start. Validators MUST reject files with multiple consecutive `lupopedia.headers` blocks (including a second block prefixed by a stray BOM). Validator error code: **`HDR_MULTIPLE_HEADERS`**.
11. **[WOLF] `when_updated` rule:** Do **not** change `when_updated` when only header maintenance is performed (normalization, key reorder, key rename migration, or envelope formatting cleanup). Change `when_updated` **only** when body content changes in a meaningful way. Header-only maintenance MUST preserve the original `when_updated`. If migration timing needs recording, write it to changelog, footer metadata, or rollover notes instead of mutating `when_updated`.

### 4.4 Forbidden (non-exhaustive)

```yaml
# FORBIDDEN: folded / multi-line scalar
purpose: "line one
  line two"

# FORBIDDEN: tags in header
tags:
 - tag-doctrine

# FORBIDDEN: legacy or rejected top-level keys under lupopedia.headers
note: "Use summary field instead"
namespace: "Use atoms_toon field instead (module is also deprecated)"
module: "deprecated — use atoms_toon instead"

# FORBIDDEN: atoms_toon empty string (use null)
atoms_toon: ''

# FORBIDDEN: module field on new artifacts (deprecated; use atoms_toon)
# module: null  ← replace with atoms_toon: null

# FORBIDDEN: removed - per-file semantic version (use header_format_version, git, CHANGELOG, title)
version: "1.0.0"
```

## 5. Sidecar Format (`header_metadata`)

### 5.1 Required Fields

| Field | Type | Rule |
|-------|------|------|
| `id` | string | Stable sidecar id (often derived from file stem + channel). |
| `type` | string | MUST be **`header_metadata`**. |
| `file_path_from_root` | string | MUST equal header. |
| `channel_key` | string | MUST equal header. |
| `trust_tier` | string | MUST equal header. |
| `purpose` | string | Human-readable purpose (ASCII). |
| `status` | string | Sidecar/doc status (e.g. `active`, `needs_review`). |
| `tags` | array of string | Machine tags (`tag-*` namespace preferred). |
| `author` | object | `{ "type", "id", "name" }`. See **type rules** below. |
| `delegation_chain` | string | e.g. `cursor:root`. |
| `edges` | array | Outbound edges; each edge obeys **§11**. |
| `footer` | object | Verification / `last_verified` mirror; includes `verified_by` with same **type** rules as `author`. |
| `init` | array | Required-reading pointers (may be empty `[]`). |
| **`transcript_jsonl`** | string | MUST equal header `transcript_jsonl` (dual-field). |

**`author.type` and `verified_by.type` (normative):** Allowed values: **`actor`** (registry actor - includes IDE facets and primary coordination personas; **autonomous "agents" in prose use `actor` with their `actor_id`**), **`user`** (human / `lupo_auth_users`), **`system`** (kernel). There is **no** separate `author.type` value **`agent`**; do not use it in sidecar JSON. Validators SHOULD reject unknown types. Validators SHOULD check that the numeric **`id`** is plausible for the declared type (e.g. resolves against `lupo_actors` or `lupo_auth_users` per implementation).

**Unknown author:** When the creating identity cannot be determined (imports, automation, or first promotion), use **`type: system`**, **`id: 0`**, **`name: ''`** unless the implementation assigns a documented non-zero system sentinel in **PRD 38** / registry; validators SHOULD accept `system` / `0` as a declared unknown.

### 5.2 Sidecar Location and Atomic Write

Two on-disk layouts are normative; they use **different second-segment semantics** after `memory/...` - do not conflate **`artifact_type`** with **`trust_tier`**.

| Layout | Purpose | Path pattern | Segment after fixed prefix |
|--------|---------|--------------|----------------------------|
| **`header_metadata` JSON** | Sidecar **`header_metadata`** file (taxonomy index) | `memory/headers/{artifact_type}/{YYYY}/{MM}/{stem}.metadata.json` | **`artifact_type`** (`prd`, `doctrine`, ...) - **not** `trust_tier` |
| **`memory_toon` `.toon`** | Compacted memory export / graph mirror | `memory/{channel_key}/{trust_tier}/{memory_year}/{MM}/{slug}.toon` | **`trust_tier`** - **not** `artifact_type`; **`memory_year`** from **§8.1** (`YYYY` or `YYYY - 1000`) |

**Disambiguation:** The directory name **`headers`** in the metadata path is a **fixed repository convention** for the metadata index tree. It is **not** required to equal **`channel_key`**. When **`channel_key`** happens to be `headers`, a `.toon` may still live under `memory/headers/{trust_tier}/...`; the **third** path segment is then **`trust_tier`**, while the metadata JSON for the same document uses **`memory/headers/{artifact_type}/...`** where the third segment is **`artifact_type`**. Those segments are **never interchangeable**.

```text
Header declares  ->  memory_toon  ->  .../{trust_tier}/{memory_year}/{MM}/{slug}.toon
Tooling derives   ->  header_metadata  ->  memory/headers/{artifact_type}/{YYYY}/{MM}/{stem}.metadata.json
```

- **Atomic rule:** write **`header_metadata`** file **first**, then content header/body; if content write fails, **delete** the new metadata file (rollback).

**Rollback procedure (normative):**

1. **Content write fails after metadata write:** Delete the newly created **`*.metadata.json`** (or restore from transaction log if the implementation uses one). Log the path and error. **Do not** leave an orphaned sidecar that claims a file that was never written.
2. **Metadata write fails:** Do **not** write the authored file body/header update. Return failure; no sidecar cleanup required.
3. Implementations MAY use write-to-temp + rename for the body file; the **observable** rule is: no durable header/body state that contradicts a missing rollback of the sidecar.

#### 5.2.1 Deriving `header_metadata` path (normative)

Tooling derives the **`header_metadata`** path **deterministically** from the header (do **not** infer it from `memory_toon` / `.toon`):

1. Let `stem` = basename of `file_path_from_root` **without extension** (e.g. `16_lupopedia_headers` from `docs/prd/16_lupopedia_headers.md`).
2. Let `YYYY` / `MM` = UTC calendar year and month from `when_updated`, each zero-padded (`2026`, `04`).
3. **Path:** `memory/headers/{artifact_type}/{YYYY}/{MM}/{stem}.metadata.json`
 - Example: `artifact_type: prd`, `stem` as above -> `memory/headers/prd/2026/04/16_lupopedia_headers.metadata.json`
 - **This PRD file** (`file_path_from_root: docs/prd/16_lupopedia_headers.md`), with `when_updated` in April 2026 and `artifact_type: prd`, derives to: **`memory/headers/prd/2026/04/16_lupopedia_headers.metadata.json`** (YYYY/MM from `when_updated`).

**`memory_toon`** is **independent**: it MUST follow **`memory/{channel_key}/{trust_tier}/{memory_year}/{MM}/{slug}.toon`** (or equivalent slug) per **§8.1** and **PRD 38**. Importers MUST treat **`header_metadata`** as authoritative for `purpose`, `tags`, `edges`, `footer`, `init`, and dual `transcript_jsonl` alignment.

Pairing of **JSON master** and **`.toon`** export for **`seed`** / **`canonical`** is normative in **§5.2.2**.

Validators SHOULD check that the derived `*.metadata.json` exists when enforcement mode is strict (optional until tooling rollout).

#### 5.2.2 Memory file pairing (JSON master → TOON derived)

For **`seed`** and **`canonical`** **`trust_tier`** values, memory files use a **paired representation** when the pipeline uses JSON authoring:

1. **JSON master** (`<basename>.json`) — authoring format, human-editable, **same directory** as the export.
2. **TOON derived** (`<basename>.toon`) — generated from JSON for deterministic parsing.

The header’s **`memory_toon`** **MUST** point to the **`.toon`** file (never the `.json` in this field). For a shipped tree, the **JSON master SHOULD exist** beside the **`.toon`** when both are part of the workflow.

**Generation command:**

```bash
python scripts/json_to_toon.py --json "<base>.json" --toon "<base>.toon"
```

**Validation:** When the declared **`.toon`** path **exists on disk**, the shipped universal validator **SHOULD** warn if the sibling **`.json`** is missing (**`HDR_MEMORY_JSON_MASTER`**, documented alias **`SIDECAR_JSON_MASTER_MISSING`**); **`--strict-memory-pair`** upgrades that to **MUST** fail. **`--development`** skips this pairing check (**§12.4**). Deep sync: **`python scripts/validate_memory_json_toon_pair.py`**.

#### 5.2.3 TOON ordering requirements

All **`memory_toon`** targets that reference **`.toon`** files **MUST** point to TOON documents that conform to the **Canonical TOON Ordering Specification (v1.0.0)** in [`docs/doctrine/TOON_ORDERING_SPEC.md`](../doctrine/TOON_ORDERING_SPEC.md). Header writers **MUST NOT** treat unordered JSON or YAML object maps as the canonical on-disk shape for memory-bearing artifacts when the product declares a **`.toon`** export: primary logical structures in those files **MUST** follow integer-indexed ordered arrays per that spec (see also **PRD 38** and **PRD 51**). The full ordering rules, writer obligations, and enforcement roles are defined only in **`TOON_ORDERING_SPEC.md`**—do not duplicate them here.

### 5.3 Sidecar Rules

- **ASCII-safe** machine strings only (no emoji in machine fields).
- **Identity alignment:** `file_path_from_root`, `channel_key`, `trust_tier`, `transcript_jsonl` MUST match the header byte-for-byte.
- **Year segment in `.toon` / `memory_toon` paths:** Use **§8.1** (`memory_year`). The **`header_metadata`** JSON path uses **calendar** `YYYY` / `MM` from the header timestamps (**§5.2** table) - **not** the `YYYY - 1000` encoding.

### 5.4 File format

- Sidecar file is **JSON** (UTF-8). Extension **`.metadata.json`** or **`.toon`** per export pipeline - pick one per repo convention; validators MUST accept the chosen extension documented in [VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md). Standard JSON **does not** allow comments; sidecar files MUST NOT contain `//` or `/* */` commentary—document prose belongs in **`.md`** files or sidecar string fields.

## 6. Transcript Header Authority Rule

- **`transcript_jsonl`** is the **only** field present in both header and sidecar.
- **Header is authoritative.** Sidecar is derived/synchronized from header values.
- Sidecar generation tooling MUST copy `transcript_jsonl` from header; user workflows MUST NOT treat sidecar mismatch as an editable second truth.
- Validators SHOULD flag mismatch as synchronization/tooling drift (`HDR_DUAL_MISMATCH`) rather than a dual-authority content conflict.
- The `transcript_jsonl` string is a logical DB identifier for transcript rows (**§9**), not a filesystem path.

## 7. Channel Rules

- **`channel_key` is REQUIRED** on every transcript-related write.
- **No fallback** from session.json or IDE state without an explicit user/actor answer to: *"Which channel do you want to work in?"* (terminal/IDE) or *"Which channel are you working in?"* (web).
- PHP transcript endpoint MUST reject requests missing **`channel_key`**.

## 8. Federation Rules

| `federation_node_id` | Meaning |
|----------------------|---------|
| `0` | Core / repository documentation default. |
| `1` | Local install / this node. |
| `2+` | External / research / peer references. |

### 8.1 Memory path year encoding

### §8.1 Memory Key Year Segment (Canonical Tier)

**Rule:** For `trust_tier: canonical`, the memory_toon path MUST use display year = calendar year - 1000.

**Offset value:** See `memory/atoms/lupopedia_global_constants.atom.toon` -> `constants.year_offset.canonical_offset`

**Example:**
- Calendar year: 2026
- Display year: 2026 - 1000 = 1026
- Path: `memory/{channel_key}/canonical/1026/{mm}/{slug}.toon`

**Validation:** `validate_lupopedia_headers_universal.py` enforces this with error code HDR_MEMORY_YEAR_OFFSET.

**Staging Tier:** Uses real calendar year (e.g., 2026). No offset applied.

**KAIROS consolidation:** Staging records (real calendar year in PK and paths) are consolidated to canonical masters (display year **calendar − 1000**) via normative **`lupo_memory_edges`** types such as **`promoted_to`** and **`consolidated_into`** (exact strings per **PRD 37** / install SQL — **`MemoryPromotionService`** uses **`promoted_to`**).

**Rationale:** Lower numeric PK bands encode higher trust for sorting and display. This is a display optimization, not a security feature. Explicit `trust_tier` column remains the source of truth for trust semantics.

## 9. Transcript System (DB-First)

### 9.1 Write Path

- **Canonical:** `python bin/transcript.py` (or browser/IDE client) -> **HTTP POST** -> PHP endpoint -> **`lupo_dialog_messages`** (or dedicated transcript table if split - same logical row).
- **Authentication:** session cookie **or** `X-Lupo-Api-Token` (or `X-API-Token`) header for automation; PHP validates **one** policy.
- **Channel transcript artifact:** Channel-scoped transcript files are written under `channels/{federation_node_id}/{channel_key}/{prd_cluster}/transcript.jsonl` for filesystem visibility and replay.
- **Offline / DB-down (exceptional):** Python MAY append to a **single local queue file** only when PHP is unreachable; MUST reconcile into DB when online (no long-term second writer of truth).

**Thread resolution (normative):** The client supplies context via **`transcript_jsonl`** (**§4.2** field **10**), **`channel_key`**, and **`federation_node_id`**. For a **well-formed** slug triple that passes channel / federation policy checks, if **no** DB thread row exists yet, the PHP endpoint **MUST** create the thread **deterministically** (idempotent insert keyed by that triple), **MUST** append the message, and **MUST** return success - **same behavior on every channel**. **`thread_not_found`** (HTTP 400) is **only** for **malformed** slugs, unknown **`channel_key`**, or **out-of-policy** federation scope - **not** for "row missing" on an otherwise valid triple. An install MAY define **`thread_create_denied`** (HTTP 403) when global config **explicitly** disables auto-create (**PRD 38** only); default is **auto-create enabled**.

#### 9.1.1 Offline transcript queue (normative minimum)

| Item | Rule |
|------|------|
| **Queue file path** | `config/offline_transcript_queue.jsonl` (repo root-relative; one JSON object per line). |
| **Line shape** | Same logical fields as **§9.2** (`channel_key`, `from_actor_id`, `created_ymdhis`, `message`, optional `task` / `context`), plus `"queued_ymdhis"` when helpful. |
| **Writer** | Only `bin/transcript.py` (or a single named script) may append; no scatter of queue files. |
| **Reconciliation** | Operator or agent runs `python bin/transcript.py --flush-offline-queue` (or equivalent) which POSTs each line to **§9.1** endpoint in order; on success, **truncate or rotate** the queue file. |
| **Security** | Queue file MUST NOT be web-served; treat as sensitive as session config. |

**Normative endpoint shape (implementation target):**

```http
POST /lupopedia/index.php?route=api/transcript/append
Content-Type: application/json
X-Lupo-Api-Token: {token}   # optional if session cookie present

{"channel_key":"development","message":"hello","from_actor_id":102,"task":"PRD-16","context":{}}
```

**Response:**

```json
{"dialog_message_id":"20260410045706000001","status":"ok"}
```

(Exact ID format MUST match `lupo_dialog_messages` PK strategy - BIGINT per doctrine.)

### 9.2 Required Fields in DB Row (logical)

| Field | Required |
|-------|----------|
| `channel_key` | Yes |
| `from_actor_id` | Yes in **request JSON** / queue lines (**§9.1**). DB column names MAY differ (`actor_id` in table DDL); loaders map to this logical field. |
| `created_ymdhis` | Yes (packed UTC BIGINT or 14-digit string per table doctrine) |
| `message` | Yes (body text; do not use a separate `action` key in new writes) |
| `task` / `context` | Optional |

### 9.3 Transcript Semantics

- **JSONL semantics preserved:** each logical line maps to one DB insert.
- **Channel storage location:** transcript artifacts for channel work live under `channels/{federation_node_id}/{channel_key}/{prd_cluster}/transcript.jsonl`.
- **`transcript_jsonl` values** MUST be interpreted as **DB slugs** only (**§4.2** field **10**). A slug string that **looks** like a former filesystem path is **still** a slug until migrated; tooling MUST NOT open files based on that string without an explicit, separate export contract.

#### 9.3.1 Slug Construction and Resolution (normative)

- Canonical slug form: **`{federation_node_id}/{channel_key}/{prd_cluster}`**
- Example: `0/headers/lupopedia-headers`
  - federation node: `0`
  - channel key: `headers`
  - thread slug: `lupopedia-headers`

Resolution flow:
1. Parse the three segments from `transcript_jsonl`.
2. Validate federation + channel policy.
3. Resolve existing thread row by that triple.
4. If missing and policy allows, create thread deterministically.
5. Insert transcript row(s) into `lupo_dialog_messages`.

**Important:** `transcript_jsonl` is a slug, not a literal path string. File placement under `channels/` is derived from that slug using the canonical `{federation_node_id}/{channel_key}/{prd_cluster}` mapping, not by treating header text as a raw filesystem path.

## 10. Transcript -> Memory Compaction

### 10.1 Input

- **DB transcript rows** for a `channel_key` + thread/context (not filesystem JSONL).

### 10.2 Output

- **Primary:** Memory nodes persisted in the database (**`lupo_memory_nodes`**, and related tables per **PRD 38**). The database is the **source of truth**.
- **Optional:** `.toon` (or JSON export) files on disk for IDE/offline read - exports only; they MUST NOT be treated as authoritative over DB rows.
- **Edges** with **§11** dimensions (stored per PRD 38; may be mirrored into sidecar `edges`).
- Updated **`header_metadata`** sidecar (`edges`, `footer`, `status`) when the sidecar pipeline is in use.

### 10.3 Rules

- Compaction MUST NOT read `transcript.jsonl` as primary input after freeze.
- Compaction MUST update **`memory_toon`** paths per **§8.1**.
- Every generated edge MUST include **`edge_type`**, **`edge_context`**, **`edge_status`**, **`edge_direction`**, and **`review_reason`** when status is **`needs_review`**.

### 10.4 Detailed TOON Generation Pipeline (normative)

1. **Input collection**
   - Load DB transcript rows for the thread resolved from `transcript_jsonl`.
   - Load current file body (`file_path_from_root`).
2. **Compaction step**
   - Run the compaction generator for that slug + file.
   - Extract entities, decisions, and relationships per PRD 38.
3. **Master JSON build**
   - Write ordered JSON master structure following `TOON_ORDERING_SPEC.md`.
4. **TOON export**
   - Convert JSON master to `.toon` and write to `memory_toon` path.
5. **Validation**
   - Validate JSON/.toon pairing (seed/canonical tiers unless development mode).
6. **Side effects**
   - Update memory edges and `header_metadata` footer/edges as configured.

Example command flow:

```bash
python scripts/compaction/generate_memory_toon.py --transcript-slug "0/headers/lupopedia-headers" --file-path "docs/prd/16_lupopedia_headers.md"
python scripts/json_to_toon.py --json "memory/headers/prd/2026/04/16_lupopedia_headers.json" --toon "memory/headers/canonical/1026/04/16_lupopedia_headers.toon"
python scripts/validate_memory_json_toon_pair.py --path "memory/headers/"
```

## 11. Edge Specification

### 11.1 Required Dimensions

| Dimension | JSON key (example) | Description |
|-----------|-------------------|-------------|
| Type | `edge_type` | Relationship name (`supports`, `references`, ...). |
| Context | `edge_context` | Structural class (`doctrine`, `summary`, ...). |
| Status | `edge_status` | `unsupported` \| `supported` \| `needs_review`. |
| Direction | `edge_direction` | **`lupo_memory_edges` (install / PRD 38):** **`unidirectional`** \| **`bidirectional`** (default **`unidirectional`**). Sidecar/header JSON may use **`outbound`** \| **`inbound`** \| **`bidirectional`** as labels - writers MUST persist using the DB vocabulary (e.g. treat **`outbound`** as **`unidirectional`** for a single stored row from -> to). |

### 11.2 Status Values

- `unsupported` - provisional.
- `supported` - validated.
- `needs_review` - human/agent queue.

### 11.3 `needs_review` and `review_reason`

When **`edge_status`** is **`needs_review`**, the field **`review_reason`** is **REQUIRED**.

**Allowed values (closed enum):** `orphaned_edge`, `contradiction`, `new_doctrine`, `schema_drift`, `consolidation_candidate`, `integrity_unknown`, `human_escalation`.

Validators **MUST** reject any other value. Adding a new value requires a **PRD revision** and **validator bump** (no silent extension).

## 12. Validator Rules

### 12.1 Header Validator

- For **`header_format_version` in `4.0.x`** (and legacy `3`), reject the legacy **`version`** key under **`lupopedia.headers`** (**§4.4**, **`HDR_VERSION_FIELD_REMOVED`**).
- Reject **v1** and **v2** headers outright.
- The document MUST contain a top-level YAML mapping key **`lupopedia.headers:`** (Markdown front matter) or the equivalent embedded header root for non-Markdown files. **All** header fields MUST be **children** of that mapping. **Flat** headers (the canonical keys placed immediately under the opening `---` without a `lupopedia.headers:` parent) MUST be **rejected**.
- Enforce **exact** key set and **§4.2** order.
- Reject **extra** keys.
- Reject **multi-line** values and YAML arrays in header.
- Require **`file_path_from_root`** and **`channel_key`**.
- Validate **`lupopedia.schema`** against the **closed enum** in **§4.2** field 20.
- Validate **`thread_id`** per **§4.2.1** (including **`prd`** -> **`''`**).
- Validate **`memory_toon`** pattern and consistency with **§8.1** when status known.
- Validate **`trust_tier`** against the **closed enum** in **§4.2** field 9 (**`HDR_TRUST_TIER_INVALID`**).
- Validate **`default_collection_id`** is `null` or integer (**`HDR_DEFAULT_COLLECTION_INVALID`**).
- **Markdown envelope positioning:** enforce exact line-position checks only in **strict envelope mode** (see **§4.3**). In standard mode, validators SHOULD verify parseable ordered header + non-empty body without hard line-number dependency.
- **Absolute `web_path`:** MUST use **`https://`**; reject **`http://`** unless **`--development`** (**`HDR_WEB_PATH_HTTP`**).
- For **`trust_tier`** **`seed`** or **`canonical`**, when the **`.toon`** at **`memory_toon`** exists on disk, expect a sibling **`.json`** master (**§5.2.2**; **`HDR_MEMORY_JSON_MASTER`** / **`SIDECAR_JSON_MASTER_MISSING`**); **`--development`** skips this check.

### 12.2 Sidecar Validator

- Enforce **`type: header_metadata`** and required keys in **§5.1**.
- Enforce **ASCII** for machine fields.
- Enforce **identity alignment** with header.
- Enforce **edge schema** per **§11**.
- **`author.type`** and **`footer.verified_by.type`** MUST each be one of **`actor`**, **`user`**, **`system`** (**§5.1**); reject unknown types (**§19.3** `SIDECAR_AUTHOR_TYPE` / `SIDECAR_VERIFIER_TYPE`).

### 12.3 Cross-Field Validator

- Enforce **`artifact_type` <-> `artifact_kind`** (see [Cross-Field Validation Rules](#cross-field-validation-rules)).
- Enforce **conditional** header fields per artifact type (PRD, implementation, discussion).

### 12.4 Development mode (`--development`)

The shipped **`validate_lupopedia_headers_universal.py`** SHOULD support **`--development`** for local and transitional runs. When set:

- **`web_path`:** **`http://`** is **allowed** (production still **MUST** use **`https://`** per **§4.2** field 5).
- **JSON master pairing:** checks in **§5.2.2** / **§12.2** are **skipped** (no **`HDR_MEMORY_JSON_MASTER`** / **`SIDECAR_JSON_MASTER_MISSING`** from the universal validator).
- **`HDR_EMPTY_BODY`:** **Markdown** line 26 and **Python** “no body after header block” are reported as **`[WARN]`** only; validation **MAY** still exit **`0`** if no other errors occur.

Without **`--development`**, production rules apply strictly.

## 13. Versioning Rules <!-- v4.0.99 update -->

| Artifact | Version | Meaning |
|----------|---------|---------|
| **This PRD** | **v4.1.1** | Normative header field grid (**22** scalars + dual-field); supersedes **v4.1.0** and earlier grids. |
| **Legacy PRD 16** | **v4.0.0** | Historical RFC milestone (twenty-key envelope with lines 23–24 blank); cite for migration context only. |
| **Lupopedia platform** | **4.1.x** | Current patch family; **`header_format_version`** in files tracks platform family. |
| **Lupopedia Headers product** | **4.1.1+** | Headers/sidecar/validator release line. |
| **Patch line** | **4.0.x / 4.1.x** | Headers/schema evolution until platform **4.2.0** stable gate. |

### 13.1 Canonical Key Order Transition Map (v4.0.99 -> v4.1.1)

| v4.0.99 order | v4.1.1 order |
|---|---|
| 1. `header_format_version` | 1. `header_format_version` |
| 2. `lupopedia.schema` | 2. `file_path_from_root` |
| 3. `when_updated` | 3. `web_path` |
| 4. `file_path_from_root` | 4. `status` |
| 5. `web_path` | 5. `when_updated` |
| 6. `last_modified_utc` (removed) | 6. `trust_tier` |
| 7. `federation_node_id` | 7. `questions_toon` |
| 8. `channel_key` | 8. `memory_toon` |
| 9. `trust_tier` | 9. `atoms_toon` |
| 10. `memory_key` (renamed) | 10. `transcript_jsonl` |
| 11. `artifact_type` | 11. `artifact_type` |
| 12. `artifact_kind` | 12. `artifact_kind` |
| 13. `thread_id` | 13. `channel_key` |
| 14. `content_id` | 14. `federation_node_id` |
| 15. `pk_id` (removed) | 15. `thread_id` |
| 16. `pk_slug` (removed) | 16. `content_id` |
| 17. `title` | 17. `content_parent_id` |
| 18. `status` | 18. `content_slug` |
| 19. `parent_pk_id` (removed) | 19. `default_collection_id` |
| 20. `summary` | 20. `lupopedia.schema` |
| 21. `module` (removed) | 21. `title` |
| 22. `dialog_transcript` (renamed) | 22. `summary` |

Transition notes:
- `memory_key` was renamed to `memory_toon`.
- `dialog_transcript` was renamed to `transcript_jsonl`.
- `pk_id`, `pk_slug`, and `parent_pk_id` were replaced by `content_id`, `content_parent_id`, and `content_slug`.
- `default_collection_id` was introduced in v4.1.1 as canonical field 19.

## 14. Backlog Migration Rules (4.0.97 -> 4.0.98)

1. Create `docs/versions/4.0.98/` with same subtree pattern as `4.0.97/`.
2. **Copy** (not delete) active backlog from `4.0.97` -> `4.0.98`.
3. In `4.0.97/README.md`: `status: archived`, `archived_date`, `superseded_by: 4.0.98`.
4. In `4.0.98/README.md`: `status: active`, `based_on: 4.0.97 (archived)`.
5. Update **THREAD_INDEX.md** files under `4.0.98/`.
6. Grep repo: point **active** links to `4.0.98`; keep `4.0.97` links where historical permalink is intended.
7. **Tooling:** consolidate `scripts/lib/header_spec_v3_1.py` (new) + `validate_lupopedia_headers_universal.py` + `lib/header_validation.py` into **one** ruleset matching **§12** (implementation task, not a separate spec).
8. **Strip legacy `version`:** Grep in-scope v3 headers for `version:` under `lupopedia.headers` and remove the line (breaking cleanup; **`version`** is no longer a **§4.2** field - **`HDR_VERSION_FIELD_REMOVED`**).
9. **Duplicate consecutive YAML blocks (`HDR_MULTIPLE_HEADERS`):** Run `python scripts/fix_double_headers.py` (use `--dry-run` first; optional `--backup`, `--verbose`) on affected Markdown. The tool merges multiple leading `lupopedia.headers` blocks using the newest `when_updated` as the tie-break, then rebuilds the **§4.3** dense envelope.
10. **Missing `content_id` / inner key line count:** Run `python scripts/normalize_lupopedia_md_header_25.py` (`--dry-run` or `--check` first; optional `--backup`, `--verbose`, `--recursive`) so the inner block matches **§4.3** rule 8: **22** contiguous key lines (**3–24**) with **no** internal blanks, then closing delimiter on line **25**.
11. **Box-drawing / mojibake before header repair:** Run `python scripts/fix_unicode_box_drawing_ascii.py` (`--dry-run` / `--check` first) so **`HDR_UNICODE_BOX`** does not mask envelope parsing (see [VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md)).

### 14.1 Legacy v1/v2 header deprecation (policy)

Adopt **state-based** enforcement (not calendar quarters): **(1)** Validators **MAY** warn on legacy **v1/v2** shapes. **(2)** CI **MAY** treat those warnings as failure once the universal validator is wired. **(3)** Optional pre-commit hooks **MAY** hard-reject legacy shapes after maintainers enable strict mode. Each step is **gated on tooling adoption**, not on wall-clock dates.

## 15. E2E Test Requirements

1. Web -> PHP -> **DB transcript row** (with `channel_key`).
2. Terminal -> PHP -> **same** DB row shape.
3. IDE / automation -> PHP (API token) -> **same**.
4. Run compaction -> memory nodes + **edges** + sidecar update.
5. Validate **header + sidecar** with unified validator.
6. Load **memory graph** UI with nodes from DB/export.
7. Confirm transcript writes land in the **channel-keyed path** derived from slug triple (`federation_node_id` / `channel_key` / `prd_cluster`).
8. **Manual (LILITH):** browser exercise - create transcript, view graph, confirm expected append under `channels/` for the active channel/thread.
9. **Gate (transcript.jsonl):** From a clean baseline, run the test window, then search **only**  
   `channels/**/*.jsonl`  
   (recursive under `channels/`, files ending in `.jsonl`). The expected channel/thread transcript file MUST be created or appended during the window, and writes MUST stay within the matching slug-derived directory.
10. **Gate (offline transcript queue):** During normal web-only E2E, **`config/offline_transcript_queue.jsonl`** MUST show **zero new lines** (fallback queue must remain idle unless DB/PHP path is unavailable).
11. **`header_metadata` atomic write (**§5.2**):** Exercise a flow that writes the derived **`*.metadata.json`** sidecar **before** the authored file body; inject a controlled failure on the body write and assert the sidecar is **rolled back** (deleted or not left orphaned) per **§5.2** atomic rule.

### 15.1 Automation and CI (informative)

Shipped validators use **process exit code `0`** = success, **`1`** = validation failure (no separate “warnings-only” exit code in **`validate_lupopedia_headers_universal.py`** today). Typical wiring from repo root:

```bash
python scripts/batch_validate_prd_headers.py
# or all Markdown under docs:
python scripts/batch_validate_prd_headers.py --all-md
# single file:
python scripts/validate_lupopedia_headers_universal.py path/to/file.md
# transitional / local (http web_path, relaxed empty-body + JSON pairing warnings):
python scripts/validate_lupopedia_headers_universal.py path/to/file.md --development
# optional repair pass before validate (Markdown numbered PRDs):
python scripts/normalize_lupopedia_md_header_25.py --check --path "docs/prd/[0-9][0-9]_*.md"
```

Optional: install **`scripts/git-hooks/pre-commit-lupopedia-headers.sample`** as a **substring** guard; strict checks SHOULD invoke the universal validator (see [VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md)). **Do not** assume **`validate_memory_file_exists.py`** exists—it is **not** shipped; memory pairing uses **`validate_memory_json_toon_pair.py`** where applicable.

**Shipped sidecar for this PRD:** `memory/headers/prd/2026/04/16_lupopedia_headers.metadata.json` (derived path per **§5.2.1**; **`transcript_jsonl`** MUST equal the live header).

## 16. Migration Guide (Reference)

Migration policy, legacy alias lifecycle, and interactive migration procedures are maintained in:

- `docs/prd/16_lupopedia_headers_migration.md`

## 17. Examples (Reference)

All concrete examples (Markdown, sidecar JSON, Python/PHP/shell comment-grid headers, transcript rows) are maintained in:

- `docs/prd/16_lupopedia_headers_examples.md`

## 18. Compliance

- Agents and validators MUST follow this normative specification.
- `transcript_jsonl` is header-authoritative; sidecar transcript values are derived/synchronized from header values.
- Exact physical line-position checks are strict-validator behavior, not a universal authoring requirement.

## 19. Change Control

- Any schema/key-order change requires coordinated updates to this PRD, validators, and related docs.
- Migration-specific compatibility windows are defined in `16_lupopedia_headers_migration.md`.

---

## Supplementary material (below)

**§1–§20** is the **single normative** specification for headers, sidecars, transcripts, validators, E2E, and **legacy header migration policy**. Sections below provide **non-normative** context: applicability by file type, constitutional notes, taxonomies, cross-field tables, audit history, and tooling links. Editors SHOULD remove duplicated normative bullets when touching those sections; any future conflict with **§4-§15** or **§20** is an **editorial bug** to fix in the supplementary section, not a second spec.

### File format rule (sidecar)

All sidecars are JSON and must contain **ASCII-safe** machine data (no emoji in machine fields per constitutional UI/string discipline).

## Header applicability and scope

**Principle:** LUPOPEDIA HEADERS track **Lupopedia-owned authorship and traceability** for documentation and source that agents, importers, and validators are expected to treat as canonical. **Generated data, vendor code, and binary assets** are not required to carry headers; their lineage comes from generators, upstream packages, or build output - not hand-maintained YAML in every byte stream.

### Required (must have `lupopedia.headers` when the file is project-authored and in a tracked code/docs path)

| Extension | Role | Typical placement |
|-----------|------|-------------------|
| **`.md`** | Documentation, PRDs, doctrines, threads | YAML front matter; line 1 must be `---` (see [LUPOPEDIA_HEADERS_FORMAT.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)) |
| **`.php`** | Application and include PHP | Block comment immediately after `<?php` |
| **`.js`** | Shipped / maintained JavaScript (non-minified source) | Block comment at top of file |
| **`.py`** | Scripts under `scripts/` and other maintained Python | Comment lines at top (`#` YAML lines) |
| **`.sql`** | Install, seed, import SQL | Block comment at top of file |
| **`.html`**, **`.htm`** | Templates and static HTML | Block comment at top of file |
| **`.txt`** | Hand-authored text (prefer **`.md`** for new narrative docs) | Leading `#` comment lines or equivalent convention for the file |
| **`*.pseudo.md`**, **`*.pseudo.php`**, **`*.pseudo.txt`** under **`**/decisions/pseudocode/`** | Design / handoff artifacts (PRD 17) | Same as sibling type: **Markdown** -> YAML front matter line 1 `---`; **PHP** -> block comment immediately after `<?php` ([PRD 17 pseudocode](17_decisions_format.md#pseudocode-directory-decisionspseudocode)) |

**IDE / agent rule:** When creating or editing any **in-scope** file above, **add or preserve** headers with correct **`file_path_from_root`** (repo-relative, no leading `/`). If a file **should** have headers but lacks them, that is a **specification gap** to fix when the file is touched - not proof the file is "invisible"; tooling may still locate it by path.

**External AI / paste handoff:** Pseudocode under **`decisions/pseudocode/`** is often copied to **external** agents. **`file_path_from_root`** (and ideally **`web_path`**) **must** be present so the recipient can anchor the file in the repo without guessing.

### Optional (may include headers; validators typically do not enforce)

| Extension | Notes |
|-----------|--------|
| **`.json`**, **`.yaml`**, **`.yml`**, **`.xml`** | Often generated or machine-owned; add headers only when the file is **hand-authored** and you want traceability |
| **`.css`** | Recommended for maintained stylesheets; not universally enforced |
| **`.sh`** | Shell scripts - headers allowed; enforcement optional |

### Not applicable (do **not** require LUPOPEDIA HEADERS)

- **Binary assets:** images (e.g. `.png`, `.jpg`, `.gif`, `.ico`), fonts (e.g. `.woff`, `.woff2`, `.ttf`), other non-text media
- **Generated or compiled artifacts:** e.g. **`.toon.json`**, **`.map`**, **`.min.js`**, **`.min.css`**, **`.pyc`**, build/cache outputs, **`.log`**
- **Data exports / dumps:** e.g. **`.csv`**, JSON exports from DB or tooling where the row/file is output, not hand-authored spec
- **Third-party / vendor trees:** e.g. **`node_modules/`**, bundled libraries not maintained as Lupopedia source
- **Lock files:** e.g. **`package-lock.json`**, **`composer.lock`**

### Validator scope (normative intent)

Validators **should** enforce headers on **in-scope** paths for: **`.md`**, **`.php`**, **`.js`**, **`.py`**, **`.sql`**, **`.html`**, **`.htm`**, hand-authored **`.txt`**, and **`**/decisions/pseudocode/*.pseudo.md`**, **`*.pseudo.php`**, **`*.pseudo.txt`** (exact directory globs are implementation-defined in each script - align them with this table over time). Validators **should not** fail the tree solely because a **`.png`** or **`.toon.json`** lacks YAML.

Full format and comment-embedding patterns: **[LUPOPEDIA_HEADERS_FORMAT.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)**, **[VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md)**.

## Constitutional Compliance

All header metadata and verification processes must comply with Lupopedia constitutional rules:

- **Verification authority**: Both actors and agents may perform verification
- **Primary authority**: THOTH (actor_id 26) is canonical for stale artifacts (`last_verified < 20260301000000`)
- **Identity tracking**: **`verified_by.type`** is **`actor`**, **`user`**, or **`system`** (**§5.1**); autonomous personas still use **`actor`** with their registry **`actor_id`** (no separate `agent` type in sidecar JSON)
- Header blocks must be present on all **in-scope** authored files (see [Header applicability and scope](#header-applicability-and-scope))
- Header fields must match format requirements in LUPOPEDIA_HEADERS_DOCTRINE
- All verification actions are logged and auditable via `lupo_actor_actions`
- Timestamps in **header YAML** and in **sidecar** `footer.last_verified` use **14-digit** UTC strings `YYYYMMDDHHIISS` (quoted in YAML; JSON string in sidecar). Legacy **8-digit** `YYYYMMDD` MAY appear only where an older tool explicitly documents it; new artifacts MUST use 14-digit for verifier freshness comparisons (e.g. THOTH stale threshold **§ Verification Authority**).
## Verification Authority

### Primary Authority: THOTH (actor_id 26)

- **THOTH** is the canonical authority for semantic truth verification of stale artifacts (`last_verified < 20260301000000`).
- Verification includes:
 - Comparing artifact content against current repository sources (TOONs, JSON exports, root rules)
 - Validating table references, edge types, and rule references
 - Confirming statements match repository reality

**Note:** THOTH actor_id 26 and PRD 26 share the same number but are different namespaces (actor registry vs document IDs). No functional conflict, but be aware when searching/grepping.

### Self-Verification Exception

- Self-verification allowed if:
 - The verifying actor (or agent) created or last updated the artifact
 - No semantic changes have occurred since last update
 - The artifact is not stale (`last_verified >= 20260301000000`)

### Verification Evidence

- All footer refreshes must include justification:
 - Commit message: `revalidated: [reason]`
 - Example: `revalidated: table docs match TOON; edge types confirmed`

### Actors and Agents

- Verification may be performed by **any actor** (including IDE facets and primary personas) or **`user`** or **`system`** - encoded only via **`verified_by.type`** values in **§5.1**
- In registry terms, "agents" are still **actors**; sidecar JSON does **not** use a separate `agent` type
- Legacy **`verified_by.identity_type`** (if encountered) MUST be mapped to **`type: actor`** for autonomous personas or **`type: user`** for humans in new sidecars

## Author vs Verifier Distinction

Lupopedia distinguishes between **who created content** (author) and **who validated it** (verifier).

| Role | Field Location | Purpose | Required |
|------|----------------|---------|----------|
| **Author** | Memory sidecar JSON (`author`) | Attribution of content creation | Yes |
| **Verifier** | Sidecar `footer` object (`header_metadata`) | Attribution of content validation | Yes (see **§5.1**; legacy YAML `lupopedia.footer` removed from header) |

### Author Types (aligns with **§5.1**)

| Type | Meaning | Example |
|------|---------|---------|
| `actor` | Any **`lupo_actors` row** - IDE facet **or** primary coordination persona | `actor_id` 102 (Cursor), 1 (WOLFIE), 2 (LILITH) |
| `user` | Human **`lupo_auth_users`** identity | Orchestrator / operator login |
| `system` | Kernel / unknown / automation sentinel | **`id: 0`** when unknown (**§5.1**) |

**Not valid in sidecar JSON:** `author.type: agent` - use **`actor`** with the agent's **`actor_id`**.

### Author Field Structure (Memory sidecar)

```json
{
  "author": {
    "type": "actor",
    "id": 102,
    "name": "CURSOR"
  }
}
```

### Migration

- **v3 headers:** do not include author identity fields in header YAML.
- **Memory sidecar:** must include structured `author` object.
- **Validators:** header validators enforce the fixed header set; memory validators enforce sidecar author fields.

**Trust Weights (non-normative heuristic):** Validators MAY apply different strictness by `author.type`: `system` minimal checks; `actor` standard; `user` stricter (humans err more often).

## Header Structure (supplementary)

**Naming:** **`header_format_version: "4.1.x"`** is the YAML value family stored in files (**§4.3**). **"v3.1"** in older prose meant *fixed key grid + sidecar + dual field*—the **current** grid is **22** keys (**v4.1.1**). Patch may drift inside `4.1.x`; major/minor must stay in family lockstep with platform validators. **PRD 16 document revision** **v4.1.1** is the active RFC (**§13**); **v4.1.0**, **v4.0.99**, and **v4.0.0** are legacy milestones. When in doubt, cite **§4.2** for field order and types.

The **authoritative field table** is **§4.2** (not duplicated here, to avoid drift).

### Fixed header layout (v4.1.1)

Line counting and delimiter rules are **§4.3** rules **7–10** (Markdown: rule **8**; comment-embedded: rule **9** table + invariant; duplicate headers: rule **10**). Markdown: `---`, **`lupopedia.headers:`**, **22** dense key lines on **3–24**, closing `---` on line **25**, body from line **26**. **`transcript_jsonl`** is field **10** in the **§4.2** order. **Legacy (v4.0.0):** twenty keys + blank lines **23–24**. Do not duplicate **§4.3** here—edit **§4** only.

### Removed from header (moved to memory sidecar)

- `actor_id`, `actor_name`
- `delegation_chain`
- `purpose`
- `tags`
- `lupopedia.edges`
- `lupopedia.footer`
- `lupopedia.init`
- `context_id`
- `channel_id` (replaced by `channel_key`)

### New in v4.1.1 (header YAML)

- **`content_id`**, **`content_parent_id`**, **`content_slug`**, **`default_collection_id`** — canonical DB-aligned content identity and default-collection routing fields replacing legacy `pk_*`.
- **`summary`**, **`atoms_toon`** — short description and atoms sidecar pointer; **`atoms_toon`** uses **`null`** when no `.atoms.toon` file exists. (**`module`** was renamed to **`atoms_toon`** in this revision.)

### New in v4.1.0 (header YAML)

- **`transcript_jsonl`** — renamed from **`dialog_transcript`**; remains the **required** dual-field (header + sidecar); pointer to transcript context (**§6**, **§9**); now field **10** in the new canonical order (see §1 migration note and changelog).

**Rule 17 Clarification:** Rule 17 (from PRD 26) applies to `slug` fields and display names, not physical file paths. File paths MAY retain numeric prefixes for sorting purposes.

**Explicit ID Naming:** All ID fields in the fixed header set use explicit prefixes to avoid ambiguity:
- `content_id`: primary content row key in `lupo_contents`
- `content_parent_id`: parent content row key (nullable)
- `content_slug`: URL-friendly content slug counterpart
- `default_collection_id`: default collection-tabs container ID for this content
- `dialog_message_id`: message identifier (in **`lupo_dialog_messages`** and related thread tables)
- Generic `id` is deprecated to prevent confusion

### Header example (v4.1.1)

Canonical nested example: **[§16.1 Example Header](#161-example-header-v411-prd-artifact)**. Legacy flat front matter (keys at root under `---`) is **not** valid after freeze.

## Transcript System Integration (non-normative summary)

**Single normative source:** **[§9 Transcript System (DB-First)](#9-transcript-system-db-first)**, **[§10](#10-transcript--memory-compaction)**, **`§15` gates**. If this section disagrees with **§9**, treat this section as wrong.

### Legacy filesystem paths (read-only / historical)

Some trees still contain **`channels/{federation_node_id}/{channel_key}/{slug}/transcript.jsonl`**. That layout is **not** a normative write target after freeze (**§15** gate). **`transcript_jsonl`** remains a **DB slug** (**§4.2** field **10**); do not treat those paths as implied by the slug.

### JSONL shape (when illustrating line-oriented semantics)

**Offline queue** and **logical export** lines MUST use the same keys as **§9.1** / **§9.2** (e.g. `channel_key`, `from_actor_id`, `created_ymdhis`, `message`, optional `task` / `context`) - not legacy `ts` / `action` / `actor_id` tuples. Example (one line):

```json
{"channel_key":"development","from_actor_id":116,"created_ymdhis":20260409001808,"message":"Started session on PRD-44","task":"PRD-44"}
```

### How callers relate to PHP

| Surface | Role |
|---------|------|
| **`python bin/transcript.py`** | **Client** - builds JSON and **POST**s to **§9.1** when online; **§9.1.1** queue when offline |
| **Web / IDE** | Same - HTTP POST to PHP; **no** direct normative append to `transcript.jsonl` after freeze |
| **`pending.py`**, other CLIs | SHOULD route transcript lines through the same POST (or shared library), not a parallel writer |

### Compaction and agent startup (pointers)

- Compaction: **§10** (DB primary; `.toon` export optional).
- Token discipline: **`memory.php load-context`** loads **graph / compacted** context - see **CLAUDE.md** / operator docs; not a substitute for **§9** row shape.

### Why this section exists

| Problem | Where specified |
|---------|-----------------|
| Audit trail | **§9** DB rows |
| Link doc <-> discussion | **`transcript_jsonl`** (**§6**) |
| Large history | **§10** compaction + **PRD 38** |

## Header format versioning

| Version | Meaning | Status |
|--------|---------|--------|
| **1** | Pre-v3 legacy headers | **Rejected** by validators after freeze (**§12.1**) |
| **2** | Transitional structured headers | **Rejected** by validators after freeze (**§12.1**) |
| **3** | Fixed v3/v3.1 field set + sidecar + dual `dialog_transcript` (legacy name) | **Accepted with WARN** (`HDR_DIALOG_TRANSCRIPT_RENAMED`) during migration |
| **4.0.x** | Pre-v4.1.1 dense header families | **Legacy alias accepted** during migration |
| **PRD 16 doc** | **v4.1.1** RFC block (**§1–§20**) | Active spec (**22**-key dense header; `transcript_jsonl`; DB-aligned `content_*` + `default_collection_id` order); prior revisions are legacy |

Validators MUST enforce **§12**; v1/v2 MUST NOT pass CI after freeze.

## Header linkage model (v3)

- Header YAML is a stable locator and identity block.
- Rich metadata is loaded from sidecar JSON via `memory_toon`.
- Graph relationships use sidecar `edges`; header-level edge/context fields are removed.

## LILITH audit record

### Path and ID hygiene (resolved)

| Finding | Resolution |
|--------|------------|
| Leading `/` on **`file_path_from_root`** | **Fixed:** repo-relative only |
| Header bloat and mixed identity/metadata | **Fixed:** v3 limits header to fixed field set and moves rich metadata to sidecar |
| **`web_path`** host | **Normative:** absolute URLs use **`https://www.lupopedia.com/lupopedia/...`**; subdirectory is mandatory |
| **`lupopedia.edges.to`** leading slashes | **Fixed:** repo-relative targets |
| **`author.type`** for LILITH | **`author.type: actor`** (**`actor_id` 2** in registry) |
| Missing **`header_format_version`** narrative | **Added:** versioning table above |
| Header graph metadata overload | **Fixed:** edges are sidecar-owned in v3 |

### Final review (2026-04-03 UTC)

| Field | Value |
|-------|--------|
| **Verdict** | **APPROVED** - ready for operational use; **`lupopedia.headers.status`** set to **`active`** |
| **Accuracy (reported)** | 99/100 |
| **Constitutional violations** | None reported |
| **Remaining debt** | Complete migration of all in-scope files to **v4.1.1** fixed-position headers (**22** keys, dense **3–24**; see **§4.3**) |

This PRD is the canonical specification for Lupopedia headers; follow **`next_action`** in the header footer for maintenance tasks.

## Artifact Type Taxonomy

The `artifact_type` field categorizes the artifact by its primary purpose in Lupopedia's documentation architecture (see PRD 26: Five-Layer Documentation Architecture).

| `artifact_type` | Description | Examples |
|-----------------|-------------|----------|
| `prd` | Product Requirements Document - defines WHAT to build | `16_lupopedia_headers.md` |
| `implementation` | Implementation documentation - defines HOW it was built | `README.md` in implementation folders |
| `doctrine` | Constitutional or doctrinal document - defines rules | `root_constitutional_system_requirements.md` |
| `discussion` | Discussion thread or message - captures WHY decisions were made | Thread messages in `discussions/` |
| `changelog` | Version-specific change log | `CHANGELOG.md` |
| `documentation` | General documentation (table docs, guides, etc.) | Table schema docs |
| `architecture` | System architecture specification | PRD 26 itself |
| `specification` | Technical specification | API specifications |

## Artifact Kind Taxonomy

The `artifact_kind` field provides finer-grained classification within an `artifact_type`.

### For `artifact_type: prd` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `requirements` | Standard PRD with requirements | `content_id`, `content_slug`, `default_collection_id`, `title`, `status` |
| `architecture` | Architecture PRD | `content_id`, `content_slug`, `default_collection_id`, `title`, `status` |
| `specification` | Technical specification PRD | `content_id`, `content_slug`, `default_collection_id`, `title`, `status` |

### For `artifact_type: implementation` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `README` | Implementation overview | `content_parent_id`, `status` |
| `documentation` | Detailed implementation docs | `content_parent_id`, `status` |
| `authors` | Author attribution | `content_parent_id`, `status` |
| `edges` | System mapping | `content_parent_id`, `status` |
| `tool` | CLI / one-off script (e.g. `scripts/*.py`) | `content_parent_id`, `status` |
| `library` | Shared library module consumed by tools | `content_parent_id`, `status` |

### For `artifact_type: doctrine` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `constitutional` | Root constitutional rules | None (minimal) |
| `reference` | Reference doctrine | None |
| `decisions` | Decision records | None |

### For `artifact_type: discussion` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `thread` | Discussion thread index | `channel_key`, `thread_id` |
| `message` | Individual message in thread | `channel_key`, `thread_id` |

### For `artifact_type: changelog` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `version_specific` | Version-specific changelog | None |

### For `artifact_type: documentation` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `table_schema` | Database table documentation | None |
| `guide` | User or developer guide | None |

### For `artifact_type: architecture`

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `system` | System / platform architecture | None (cross-field with **`lupopedia.schema: architecture`** as needed) |
| `data_model` | Data / storage architecture | None |

### For `artifact_type: specification`

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `technical` | General technical specification | None |
| `api` | API contract or surface | None |
| `protocol` | Wire / exchange protocol | None |

## Cross-Field Validation Rules

Validators MUST enforce the following combinations:

| `artifact_type` | Allowed `artifact_kind` values |
|-----------------|-------------------------------|
| `prd` | `requirements`, `architecture`, `specification` |
| `implementation` | `README`, `documentation`, `authors`, `edges`, `tool`, `library` |
| `doctrine` | `constitutional`, `reference`, `decisions` |
| `discussion` | `thread`, `message` |
| `changelog` | `version_specific` |
| `documentation` | `table_schema`, `guide` |
| `architecture` | `system`, `data_model` |
| `specification` | `technical`, `api`, `protocol` |

**Validation Rule:** If `artifact_type` is not in the allowed list, the validator MUST reject with error.
**Validation Rule:** If `artifact_kind` is not allowed for the given `artifact_type`, the validator MUST reject with error.

## Conditional Required Fields by Type/Kind

| `artifact_type` | `artifact_kind` | Additional Required Fields |
|-----------------|-----------------|---------------------------|
| `prd` | any | `content_id`, `content_slug`, `default_collection_id`, `title`, `status` |
| `implementation` | `README` | `content_parent_id`, `status` |
| `implementation` | `documentation` | `content_parent_id`, `status` |
| `discussion` | `thread` | `channel_key`, `thread_id` |
| `discussion` | `message` | `channel_key`, `thread_id` |
| All others | any | No additional required fields (but **`content_id`** MAY be **`NULL`** until linked; unused fields follow **§4.2** sentinels) |

### Deprecated Fields

The following fields are deprecated and must not be used in new artifacts:
- `version_when_written` - use `when_updated`
- `system_version` - no replacement
- `lupopedia.version` - no replacement
- `version` (per-file semantic, under **`lupopedia.headers`**) - **removed**; use **`header_format_version`**, git / **CHANGELOG.md**, document title
- `id` - use explicit identifiers (`content_id`, `thread_id`, `content_parent_id`, etc.)
- `slug` - use `content_slug` for content-linked artifacts, explicit prefixes for other types
- `channel_id` - use `channel_key`
- `content_id` - REQUIRED in **v4.0.99** header set (`NULL` until imported; integer when linked)
- `context_id` - removed from v3 header set (use sidecar edges)
- `actor_id` / `actor_name` - sidecar author identity only


## Verification Process

- Verification may be performed by **actors or agents**
- Verifier identity lives in the **sidecar** `footer` object (**§5.1**). Legacy YAML `lupopedia.footer` in header is **out of scope** for **v4.0.99** headers.
- The `verified_by` field structure uses `type` and `id` fields.
- All verification actions are logged in the system audit trail
- Verification includes header format, field presence, and cross-references
- Stale artifacts (`last_verified < 20260301000000`) must be verified by THOTH

### Verifier Field Structure (Preferred - sidecar JSON only)

**This is not header YAML.** The following is a **JSON fragment** inside the `header_metadata` sidecar file. Do **not** place `lupopedia.footer` (or any nested block) in Markdown front matter under **§4**.

Store under **`header_metadata.footer`**:

```json
{
  "footer": {
    "last_verified": "20260410054023",
    "verified_by": {
      "type": "actor",
      "id": 2,
      "name": "LILITH"
    }
  }
}
```

Legacy YAML `lupopedia.footer` blocks are **not** part of the **v4.0.99** header (**§4**). Legacy `identity_type` / `actor_id` verifier shapes are rejected for new artifacts.

## 15. Two Paths to Content

This section is normative. All agents and implementors must understand both paths. Neither path is wrong; both end with a valid header.

### 15.1 Path 1: Web UI (Privileged)

The actor uses the web interface to create content. The system has full context at write time.

1. Actor selects channel, thread, and/or collection in the UI
2. System knows the parent at creation time
3. Complete header is generated immediately
4. File is written to disk with header already present
5. Database row is created in the same transaction
6. ANUBIS is not involved

**Result**: Header is complete at write time and at rest.

### 15.2 Path 2: Filesystem (Unprivileged)

An agent (e.g. Claude Code, Crafty, external tool) writes a file directly to the filesystem without the web UI. The agent has no UI context and does not know the correct parent.

1. Agent writes file to disk — no header, no database row
2. File sits on disk as an **orphan**
3. ANUBIS detects the orphan file via its filesystem watcher
4. ANUBIS analyzes the file content to infer the correct parent (channel, thread, collection)
5. ANUBIS generates the complete header
6. ANUBIS writes the header to the file in place
7. ANUBIS creates the database row
8. ANUBIS builds the memory graph edges

**Result**: Header is absent at write time, complete at rest (after ANUBIS processing).

### 15.3 Comparison

| | Path 1 (Web UI) | Path 2 (Filesystem) |
|---|---|---|
| Actor context | Human — knows parent | Agent — no context |
| Header at write time | Complete | Absent (orphan) |
| Header at rest | Complete | Complete (after ANUBIS) |
| Database row | Created immediately | Created by ANUBIS |
| ANUBIS involvement | None | Required |

The header always exists by the time the file is in the database. The only difference is **when** it gets added.

### 15.4 Why This Matters for Agents

Agents operating on the filesystem must not block on "I don't know the parent." That is not their problem to solve. ANUBIS exists precisely to resolve this.

| Agent Expectation | Reality |
|---|---|
| "I must know everything before writing" | Web UI knows; filesystem agents do not need to |
| "Files without headers are errors" | Files without headers are unprocessed — not broken |
| "There is one path to create content" | Two paths, both valid, both end with a header |

**Rule**: An agent writing a file without a header is not violating the constitution. A file remaining in the database without a header is a constitutional violation. ANUBIS bridges the gap.

### 15.5 Cross-References

- **PRD 07 §ANUBIS** — ANUBIS agent role definition and actor ID
- **PRD 06 §Two-Path Content Intake Doctrine** — content lifecycle and storage rules
- **PRD 19** — garbage collection; orphans that ANUBIS cannot resolve are escalated here
- **PRD 32** — actor authority; only ANUBIS may write headers on behalf of filesystem-path files

### 15.6 ANUBIS Implementation Requirements (Normative)

**ANUBIS is not aspirational.** The two-path content model is only safe when ANUBIS is operational. A Lupopedia installation without a working ANUBIS process cannot safely allow agents to write files via the filesystem path.

#### 15.6.1 Trigger

ANUBIS MUST be triggered by one of the following mechanisms (implementation chooses one):

| Mechanism | Description |
|-----------|-------------|
| **Filesystem watcher** | Watches in-scope directories; triggers on file creation/modification |
| **Periodic scan** | Runs on a schedule (e.g. every 5 minutes); scans for orphans |
| **CI hook** | Runs on every commit; processes new/modified files |

Minimum viable: a periodic scan script. Recommended: filesystem watcher (real-time).

#### 15.6.2 Orphan Detection

ANUBIS identifies an orphan by checking the header field `content_id`:

```
IF file is in-scope (per §2) AND header.content_id IS NULL:
    file is an orphan → process it
IF file has no header:
    file is an orphan → generate header first, then process
```

#### 15.6.3 Required Actions (per orphan)

ANUBIS MUST perform all of the following, in order, for each orphan it processes:

1. **Parse the file's header** — extract `channel_key`, `transcript_jsonl`, `memory_toon`, `artifact_type`, `content_slug`, `default_collection_id`, `trust_tier`
2. **Create the `lupo_contents` row** — INSERT into `lupo_contents` (including `default_collection_id` when present); capture the new `id`
3. **Write `content_id` back to the header** — update the file's `content_id` field with the new integer; re-validate the header
4. **Resolve or create the dialog thread** — use `transcript_jsonl` slug to find or create the `lupo_threads` row; the PHP transcript endpoint auto-creates threads for well-formed slugs (§9)
5. **Generate the `.toon` memory node** — run the compaction pipeline in §10.4 (or equivalent) to write the `.toon` file at `memory_toon`
6. **Write edges** — register the file's relationships in `lupo_memory_edges` (or sidecar `edges`) per §11
7. **Update sidecar** — write or update `header_metadata` JSON at the derived sidecar path (§5)
8. **Log the action** — append an entry to the ANUBIS processing log identifying the file, the new `content_id`, and the UTC timestamp

#### 15.6.4 Failure Handling

- If any step fails, ANUBIS MUST NOT partially commit — leave the file as an orphan and log the failure
- ANUBIS MUST NOT modify the file body — it may only modify the header block (lines 1–24 for Markdown)
- Files that ANUBIS cannot process (e.g. malformed header, unknown `artifact_type`) MUST be escalated to PRD 19 (garbage collection) after N retries (implementation-defined, recommended: 3)

#### 15.6.5 Actor Identity

ANUBIS MUST act as **actor_id 9** in all database writes. The `lupo_contents` row MUST record `actor_id = 9` as the `created_by` for any orphan-resolved row. Headers written by ANUBIS MUST have the sidecar `author.type: actor` and `author.id: 9`. THOTH remains **actor_id 26** and is a separate verification actor.

### 15.7 ANUBIS Operational Contract

ANUBIS is an integrity component. The following behavior is normative:

1. **Idempotency**
   - Re-processing the same file MUST NOT create duplicate `lupo_contents` rows.
   - Resolution MUST key on deterministic identity (`file_path_from_root` + canonical slug context).
2. **Retry behavior**
   - Failed orphan completion MUST be retried safely.
   - Retries MUST preserve idempotency guarantees.
3. **Failure modes**
   - DB failure: MUST NOT write partial header state claiming success.
   - Header write failure after DB insert: implementation MUST rollback insert where safe or mark deterministic retry state for reconciliation.
4. **Orphan handling**
   - Detection MUST be deterministic: `content_id: null` (or missing header case per §15.6.2).
   - Every resolve/skip/failure action MUST be logged.
5. **Concurrency**
   - Concurrent ANUBIS workers MUST NOT create duplicate rows for one file.
   - Implementations MUST use locking, unique constraints, or equivalent single-writer guards.

## Cross-References

- See [LUPOPEDIA_HEADERS_FORMAT.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) for fixed **v4.1.1** fields and fixed-position header layout
- See [LUPOPEDIA_HEADERS_DOCTRINE.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md) for canonical header requirements
- See [VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) for validation tools
- See [VERSIONING_MODEL.md](../doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md) for versioning rules
- See [PRD 26: Five-Layer Documentation Architecture](26_five_layer_documentation_architecture.md) for actor identifier types and documentation standards
- See [PRD 51: Memory graph and thread context as header authority](51_memory_graph_as_source_of_truth.md) for inferring header fields from **`lupo_memory_*`** and **`lupo_dialog_*`** before path heuristics
- **§20** (normative RFC above) — interactive migration path for legacy headers (per-file; no mass blind migrate); **§20.10** — optional **breakthrough registry** ([`BREAKTHROUGH_REGISTRY.md`](../versions/4.0.99/BREAKTHROUGH_REGISTRY.md))
- **`scripts/validate_lupopedia_headers_universal.py`** - enforcement of **§4** / **§12** (including **22**-key **v4.1.1** envelope; legacy alias handling during transition)
- **`scripts/import_content.py`** - content import flow (independent from fixed header field set)
- **`scripts/lib/header_db_sync.py`** - **`lupo_metadata`** + **`lupo_edges`** sync

## 19. The Questions TOON System (Planned)

### 19.1 Purpose and Motivation

Field 7 (`questions_toon`) exists because every field in the canonical dense header must carry **unique semantic value**. The previous occupant — `last_modified_utc` — was a second timestamp that was always equal to `when_updated`. It carried zero additional information and created a maintenance burden (two values that must be kept identical). It has been removed.

In its place, `questions_toon` points to a **planned Q&A surface** for every Lupopedia file. The Q&A TOON is a specialized `.toon` file (distinct from the memory `.toon` at `memory_toon`) that stores structured question/answer pairs about the file's content. It answers the question: "What would someone ask about this file, and what are the authoritative answers?"

**The Q&A system is not yet built.** `questions_toon: null` is correct and expected for all files until the writer, validator, and UI are implemented. This section specifies the planned shape so implementations are consistent.

### 19.2 What `.questions.toon` Will Contain

A `.questions.toon` file is a JSON document with the following planned structure:

```json
{
  "schema": "questions_toon/1.0",
  "source_file": "docs/prd/16_lupopedia_headers.md",
  "when_generated": "20260415040000",
  "generator": "actor_id:26",
  "questions": [
    {
      "q": "What is the purpose of the Lupopedia header?",
      "a": "The header is the key ring connecting a file to three database systems: lupo_contents (what it is), the .toon memory node (compressed knowledge), and the lupo_dialog_messages thread (why decisions were made).",
      "source": "§3.1",
      "confidence": 1.0
    },
    {
      "q": "Why was last_modified_utc removed?",
      "a": "It was always identical to when_updated, providing no additional information. Its position in the header was repurposed for questions_toon, which carries unique semantic value.",
      "source": "§19.1",
      "confidence": 1.0
    }
  ],
  "faq_topics": ["header-format", "agent-discovery", "memory-system"],
  "edge_to_memory_toon": "memory/headers/canonical/1026/04/16_lupopedia_headers.toon"
}
```

### 19.3 Path Convention

When `questions_toon` is non-null, the path MUST:
1. End with **`.questions.toon`** (validator: `HDR_QUESTIONS_TOON_SUFFIX`)
2. Use the **real calendar year** (for example, `2026`), not the canonical offset (`1026`) because questions are staging content
3. Use the **same `channel_key` and slug** as `memory_toon`
4. Replace the `{trust_tier}` segment with `questions/`

**Pattern:**
`memory/{channel_key}/questions/{YYYY}/{MM}/{slug}.questions.toon`

**Example for this PRD:**
```
memory_toon: "memory/headers/canonical/1026/04/16_lupopedia_headers.toon"
questions_toon: "memory/headers/questions/2026/04/16_lupopedia_headers.questions.toon"
```

**Why questions use real year (2026) and not offset (1026):**
- Questions are staging content: open, unresolved, or in planning
- They are not canonical truth
- They should sort after canonical memory (`1026 < 2026`)
- When a question is resolved, its answer may be promoted to the memory `.toon` (canonical tier), but the `.questions.toon` remains as historical record

**Lifecycle:**
1. Open question is written to `questions_toon` (staging, 2026)
2. Discussion happens in the transcript (`transcript_jsonl` thread)
3. Resolution is reached, then answer content is added to memory `.toon` (canonical, 1026)
4. Original `.questions.toon` is retained as historical record (no migration required)

### 19.4 Validator Rules (When Non-Null)

| Rule | Error Code | Severity |
|---|---|---|
| Value must end in `.questions.toon` | `HDR_QUESTIONS_TOON_SUFFIX` | ERROR |
| `null` is always valid | — | — |
| File at path need not exist at validation time | — | — (WARN only with `--strict-questions-toon`) |
| Path must not equal `memory_toon` | `HDR_QUESTIONS_TOON_COLLISION` | ERROR |

### 19.5 Migration Plan: `last_modified_utc` → `questions_toon`

#### Phase 1 — Rename (Current, v4.0.99)
- **All new files**: use `questions_toon: null` at field 6. Do NOT use `last_modified_utc`.
- **Existing files**: Migrate at next edit (per §20 interactive migration policy — no blind mass migration).
- **Validator**: emits `HDR_LAST_MODIFIED_RENAMED` (WARN) when `last_modified_utc` still present. File still validates (for backward compatibility with legacy corpus) but the warning is prominent.
- **Tooling**: Run `python scripts/normalize_lupopedia_md_header_25.py` on each file at migration time — the script will replace `last_modified_utc: "timestamp"` with `questions_toon: null`.
- **Batch migration command**: `python scripts/rename_last_modified_to_questions_toon.py` — renames the field in all Python script own-headers in one pass.

#### Phase 2 — Q&A Writer (Future)
- Implement `generate_questions_toon.py` — reads file body + dialog transcript, generates Q&A pairs, writes `.questions.toon` at the Phase 3 path.
- Update `questions_toon` field from `null` to the generated path.

#### Phase 3 — UI Surface (Future)
- Web UI renders the `.questions.toon` Q&A pairs as an expandable FAQ panel below each file.
- Agents receive `.questions.toon` content as part of their file context (alongside the memory `.toon`).

#### Phase 3.1 — `last_modified_utc` Deprecation Completion Checklist

Before removing all backward-compatibility code (the `# REMOVE after Phase 3` blocks), ALL of the following must be confirmed:

- [ ] Full corpus validator run returns `0` `HDR_LAST_MODIFIED_RENAMED` warnings for **2 consecutive sessions**
- [ ] No federation node file stores carry `last_modified_utc` in header position 6
- [ ] All `# REMOVE after Phase 3` comments removed from the codebase (~15 Python scripts; see CHANGELOG 2026-04-14)
- [ ] Validator updated: `last_modified_utc` emits **error** only (remove the WARN backward-compat path)
- [ ] `LEGACY_KEYS_V4` entry `"last_modified_utc": "questions_toon"` removed from `scripts/lib/header_spec_v3_1.py`
- [ ] `HDR_LAST_MODIFIED_RENAMED` removed from validator error-code taxonomy (§19.3)
- [ ] `scripts/remove_phase3_legacy_support.py` run to completion and output reviewed

**Trigger**: WOLFIE or Actor 116 confirms all items, then runs `python scripts/remove_phase3_legacy_support.py`.
**Closes**: OQ-38 in `docs/versions/4.0.99/status/open_questions.md`.

---

## 18. Header as Agent Discovery Mechanism

### 18.1 The head-25 Contract (Strict Envelope Mode)

In **strict envelope mode**, the 25-line fixed-position envelope is not an arbitrary constraint. It is a machine contract: any agent or tool can read the complete identity metadata of any Lupopedia file by consuming exactly the first 25 lines — no YAML parser required, no full file load, no database query.

```bash
# Read all metadata for any Lupopedia file in one shot
head -25 docs/prd/16_lupopedia_headers.md
```

This gives the agent: what the file is (`artifact_type`, `lupopedia.schema`), where it lives (`file_path_from_root`), how to find its database row (`content_id`), how to load its compressed knowledge (`memory_toon`), and how to fetch the full reasoning history (`transcript_jsonl`) — all in 25 lines, from a cold filesystem, with no network call and no database access.

**In strict envelope mode, the 25-line limit is enforced because violating it breaks this contract.** A header that runs past line 25 because someone added extra keys can no longer be consumed by `head -25`. This is not pedantry — it is a protocol guarantee.

### 18.2 Directory Scanning: O(n) Metadata, Not O(n) Files

An agent tasked with understanding a directory of Lupopedia files can scan all headers without reading any file bodies:

```bash
# Get artifact_type, content_id, and memory_toon for every PRD
grep -h "artifact_type:\|content_id:\|memory_toon:" docs/prd/*.md | head -3
```

Or more precisely — read only the header envelope of each file:

```python
# Read all PRD headers without loading file bodies
import subprocess
for path in sorted(Path("docs/prd").glob("*.md")):
    header_lines = path.read_text().splitlines()[:25]
    # parse fields from header_lines[2:24]
```

**The performance gain is substantial.** Reading 66 PRD file headers (25 lines each) costs 1,650 lines of I/O. Reading 66 full PRD files costs tens of thousands of lines. For an LLM agent with a context window, the difference is the ability to survey the entire PRD corpus vs. reading two or three files in full.

### 18.3 Orphan Detection Without the Database

Because `content_id: null` in the header means "this file has no database row," an agent can find every orphan in the repo with a single grep:

```bash
# Find all files not yet linked to lupo_contents
grep -rl "content_id: null" docs/ scripts/ includes/
```

This is the command ANUBIS uses in its scanning pass (§15.6.2). It requires no database connection, no PHP endpoint, no network access. The header's `content_id: null` is a filesystem-readable signal that the file needs ANUBIS processing.

Similarly, to find all staging files not yet promoted to canonical:

```bash
grep -rl 'trust_tier: "staging"' docs/prd/
```

And to find files at the old header format version needing migration:

```bash
grep -rl 'header_format_version: "4.0.98"' docs/
```

### 18.4 Three Depths of Access

The header system provides three distinct tiers of access, each progressively richer:

| Depth | Source | Read cost | What you get |
|---|---|---|---|
| **1 — Header** | `head -25 file.md` | 25 lines | Identity: what it is, where it is, its DB key, its memory pointer, its WHY slug |
| **2 — TOON file** | `cat {memory_toon}` | ~1–5 KB | Compressed knowledge: entities, decisions, relationships, summary — structured for agent consumption |
| **3 — Full file** | Full file read | 1 KB – 200 KB | Complete specification/code — every nuance, every example, every footnote |

**An agent working on a refactor reads at Depth 1 for all files, Depth 2 for candidates, Depth 3 only for the files it will actually change.** This is the protocol that makes large-context tasks tractable.

### 18.5 External Agents and `file_path_from_root`

An external agent — one with no access to the Lupopedia filesystem at all (operating via API, receiving only text context) — can still resolve any file's identity because `file_path_from_root` is the canonical file path embedded in the header itself.

When an agent receives a file as pasted text (no filesystem access), it reads `file_path_from_root` from the header to know:
- Where this file would be in the repo
- What its sibling files are (same directory)
- What its `.toon` memory file path would be (`memory_toon`)
- Whether it is a draft (`content_id: null`, `trust_tier: staging`) or a canonical spec

**`file_path_from_root` is the anchor that lets a headerless agent context act as if it has filesystem awareness.** This is why it is required even for files that will never move.

### 18.6 Token Efficiency

For LLM agents, the header system provides substantial token savings:

| Operation | Without headers | With headers |
|---|---|---|
| "What PRDs exist?" | Read all 66 files | `rg -n "content_id:|content_slug:|title:" docs/prd/*.md` — ~200 lines |
| "What orphans need ANUBIS?" | Query database | `grep -rl "content_id: null"` — no DB needed |
| "What does PRD 38 say?" | Load full 1,200-line file | Load 25-line header → decide if full read needed |
| "What files reference memory unification?" | Full-text search all files | Grep `memory_toon` paths for `memory-unification` slug |
| "What is the reasoning behind PRD 16?" | Unknown — lost in history | `transcript_jsonl: "0/headers/lupopedia-headers"` → fetch thread |

**The header transforms O(n-files × avg-file-size) operations into O(n-files × 25-lines) operations for discovery tasks.** For a corpus of 200+ files, this is the difference between fitting a complete survey in one context window vs. requiring dozens of round trips.

---

## 16. ZIP Distribution and Runtime Architecture

### 16.1 How Lupopedia Ships

Lupopedia is not a SaaS product. It is not a GitHub repository users clone. It is a **self-contained downloadable package** — like WordPress, phpBB, or Joomla.

```
Developer workstation:
  [Lupopedia source repo]
        |
        ▼ (CI validates all headers pass)
  [Build step: ZIP packaging]
        |
        ▼ (user downloads)
  lupopedia.zip
        |
        ▼ (user extracts on their server)
  /var/www/lupopedia/
        +-- index.php
        +-- docs/prd/16_lupopedia_headers.md   ← HEADER IS IN THE ZIP
        +-- memory/.../16_lupopedia_headers.toon
        +-- memory/headers/prd/.../16_lupopedia_headers.metadata.json
        +-- includes/...
```

**The headers ship inside the ZIP.** Every `.md` file, every `.php` file, every `.toon` file, every sidecar `.json` — they all go into the ZIP as-is. The user's installation contains the complete header system.

### 16.2 What the PHP Runtime Does with Headers

At runtime, the PHP application reads header fields to resolve database linkages. This happens in the same way a WordPress plugin reads its own configuration — automatically, at load time, without user awareness.

| Runtime Operation | Header Field Used | Purpose |
|---|---|---|
| Resolve content row | `content_id` | Load engagement data for this file |
| Load memory node | `memory_toon` | Serve compressed knowledge to agents/UI |
| Resolve dialog thread | `transcript_jsonl` | Fetch conversation history (the WHY) |
| Route to channel | `channel_key` | Apply channel-level permissions and config |
| Verify file identity | `file_path_from_root` | Confirm file is where the DB thinks it is |
| Apply trust rules | `trust_tier` | Determine which memory tier to load |

**The user never sees the header.** The header is to the PHP runtime what a database schema migration is to the application — invisible to end users, essential to correct operation.

### 16.3 Why CI Validates Headers

CI runs `validate_lupopedia_headers_universal.py` on every file before the ZIP is built. This is not a development convenience. This is a **quality gate** — the same reason WordPress validates its plugin manifest before publishing to the WordPress.org directory.

**A header error in the shipped ZIP = a broken installation for every user who downloads it.**

| Header failure | Runtime consequence for every installed user |
|---|---|
| Wrong `content_id` | Engagement data loads for the wrong content |
| Missing `memory_toon` or broken `.toon` | Memory graph is missing a node; agent context is wrong |
| Wrong `transcript_jsonl` | File's reasoning thread is inaccessible |
| `content_id: null` in shipped ZIP | File is permanently orphaned — ANUBIS cannot run on user machines |

### 16.4 ANUBIS and ZIP Distribution

ANUBIS runs **on the developer's build machine**, not on user machines. The user's installation receives a ZIP where every file already has a complete header, a valid `content_id`, a generated `.toon`, and a linked dialog thread.

**Delivery contract:**
- Files with `content_id: null` must NOT ship in the production ZIP
- All `.toon` files at `memory_toon` paths must be present in the ZIP
- All `header_metadata` sidecars must match their header's `transcript_jsonl`
- CI MUST fail if any in-scope file has `content_id: null` in production mode

**During development,** `content_id: null` is acceptable — ANUBIS processes new files on the build machine before the ZIP is cut.

---

## 17. The Transcript as WHY

### 17.1 The File/Thread Duality

Every Lupopedia file exists in two dimensions simultaneously:

| Dimension | Location | Contains |
|---|---|---|
| **WHAT** | The file itself (`.md`, `.php`, etc.) | Decisions, requirements, specifications, code |
| **WHY** | `lupo_dialog_messages` (via `transcript_jsonl`) | Debates, alternatives, rationale, context |

**Neither dimension alone is sufficient.** A file without its transcript is a decision without reasoning. A transcript without its file is reasoning without a conclusion. The header's `transcript_jsonl` field is the bridge between them.

### 17.2 Why Agents Need the Thread

An agent asked to modify a PRD must understand not just what the PRD says, but why it says it. Without the thread:

- The agent may re-litigate decisions that were explicitly settled
- The agent cannot distinguish a deliberate constraint from an oversight
- The agent may propose alternatives that were considered and rejected for documented reasons

With the thread, the agent has full context:

```
Agent prompt:
  "Here is PRD 16. Here is its transcript (last 20 messages).
   Update the validator section to add HDR_DUAL_MISMATCH support."

Agent can now see:
  - The spec author's intent for HDR_DUAL_MISMATCH (from the thread)
  - The decision to defer implementation (from the thread)
  - The agreed format for the sidecar check (from the thread)
  - Prior attempts and why they were rejected (from the thread)
```

### 17.3 Normative Requirements for `transcript_jsonl`

1. `transcript_jsonl` MUST be non-empty for every in-scope file (**§4.2** field **10**)
2. The thread identified by `transcript_jsonl` MUST exist in the database before the file is marked `trust_tier: canonical`
3. The thread MUST contain at minimum one message explaining the file's creation context
4. The `transcript_jsonl` value MUST match byte-for-byte in both the YAML header and the `header_metadata` sidecar (**§6** dual-field rule)
5. Agents reading a file for modification purposes SHOULD fetch the thread via the `transcript_jsonl` slug before proposing changes

### 17.4 The Transcript is the Reasoning

When a file is archived, deprecated, or superseded, its `transcript_jsonl` thread remains accessible. Future agents and human readers can always answer: **why was this decided this way, and why was it eventually changed?**

The thread is a permanent record of reasoning. The header is the key to that record. This is why `transcript_jsonl` is the only field that appears in both the YAML header and the sidecar JSON — its integrity is so important that it is enforced by two independent checks.

---

**Status**: ACTIVE - **PRD 16 v4.1.1** (**§1–§20**); `lupopedia.headers.status: active`.

**Constitutional adherence**: FULL (pending validator alignment with **22**-key dense envelope + PHP transcript endpoint implementation for **§9**).

**Next review**: When **§12** validator behavior or **§4** field grid changes.

### Appendix: Full compliant examples (v4.1.1) <!-- v4.1.1 update -->

**1) Markdown (`.md`)** — line numbers are comments for review only; do not paste them into the file.

```text
 1 | ---
 2 | lupopedia.headers:
 3 |   header_format_version: "4.1.1"
 4 |   file_path_from_root: "docs/example.md"
 5 |   web_path: "https://www.lupopedia.com/lupopedia/docs/example.md"
 6 |   status: "active"
 7 |   when_updated: "20260410120000"
 8 |   trust_tier: "canonical"
 9 |   questions_toon: null
10 |   memory_toon: "memory/development/canonical/1026/04/example.toon"
11 |   atoms_toon: null
12 |   transcript_jsonl: "0/development/example_thread"
13 |   artifact_type: documentation
14 |   artifact_kind: guide
15 |   channel_key: "development"
16 |   federation_node_id: 0
17 |   thread_id: ""
18 |   content_id: null
19 |   content_parent_id: null
20 |   content_slug: "example-document"
21 |   default_collection_id: null
22 |   lupopedia.schema: documentation
23 |   title: "Example document"
24 |   summary: "Updated header format to v4.1.1 with content_* + default_collection_id alignment and canonical key order"
25 | ---
26 | # Body starts here (non-empty line required)
```

**2) PHP (`.php`) — preferred (CLI / `scripts/`)** — Optional **line 1** ``#!/usr/bin/env php``; **next** line ``<?php``; **then** the **same 25-line** ``#`` comment grid as Python (``# ---`` … ``# lupopedia.headers:`` … **22** ``#   key:`` rows … ``# ---``). **Do not** embed YAML directly under ``/**`` without `` * `` line leaders (that pattern is rejected as **`HDR_PHP_LEGACY_INLINE_V3`**). **Legacy** star envelopes remain documented for existing includes.

```php
#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.1"
#   file_path_from_root: "includes/example.php"
#   web_path: "https://www.lupopedia.com/lupopedia/includes/example.php"
#   status: "active"
#   when_updated: "20260410120000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/example-php.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/example-php"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 16
#   content_slug: "example-php"
#   default_collection_id: 42
#   lupopedia.schema: implementation
#   title: "Example PHP CLI script"
#   summary: "Dense v4.1.1 header on PHP using the Python-style # grid with default_collection_id"
# ---------------------------------------------------------------------
/**
 * Example file docblock (optional) begins on the first line after the # grid.
 */
```

---

## Memory graph doctrine (reference)

The full **context-typed, status-aware, directional** memory doctrine (edge dimensions, `review_reason` queues, traversal rules) is maintained in **[PRD 38: Memory Unification](38_memory_unification.md)** (**`file_path_from_root`:** `docs/prd/38_memory_unification.md`). **Normative edge keys** for headers/sidecars in this PRD are **§11** above. Do not duplicate the long doctrine appendix here—link to PRD 38 when editing graph semantics.

---

**End of supplementary material** (normative **§1–§20** is the RFC block above **## Supplementary material**; everything from **## Supplementary material (below)** onward is non-normative unless a subsection explicitly states otherwise).
