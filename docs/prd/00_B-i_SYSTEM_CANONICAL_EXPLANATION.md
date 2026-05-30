---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/00_B-i_SYSTEM_CANONICAL_EXPLANATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/00_B-i_SYSTEM_CANONICAL_EXPLANATION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/prd-system-canonical-explanation.toon
  atoms_toon: null
  transcript_jsonl: 0/prd/prd-system-canonical-explanation
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_00_B-i_00_C-i_57_A-i
  title: 'PRD 00: System Canonical Explanation (Core Doctrine)'
  summary: Core doctrine for Lupopedia's two-dimensional, finite, constitutional PRD system architecture. A semantic operating system, not documentation.
---
# ???? THE LUPOPEDIA PRD SYSTEM ??? FINAL, CANONICAL EXPLANATION

Lupopedia's PRD system is a two-dimensional, finite, constitutional documentation architecture.  
It is not documentation. It is a semantic operating system.

---

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

## 1. PRD NUMBERS (00???99) = GROUPS

Each PRD number defines a group namespace.  
Exactly 100 groups exist (00???99).

Examples:
- 16 Identity Layer
- 02 Channels
- 71 Truth & Knowledge
- 86 Forbidden Patterns
- 99 Limits

## 2. SUB-PRDs (A???Z) = INDIVIDUAL DOCUMENTS

Pattern: `<PRD#><A???Z><short_name>.md`

Rules:
- Single uppercase letter only
- No repeats
- No lowercase
- No numbers
- No multi-letter suffixes

Examples:
- 16Aheaders.md
- 16Batoms.md
- 16Ctemplates.md

## 3. MERGE SUFFIXES (AB, ABC, etc.)

Pattern: `16AZ = 16A + 16B`

Rules:
- Unique letters
- Alphabetical
- Must correspond to real sub-PRDs

## 4. 00-LAYER MERGE FILES = CONSTITUTIONAL EXPLANATIONS

Pattern: `00<clusterletter><PRD#><subletters><shortdescription>.md`

Examples:
- 00A16ABCDidentitylayerexplained.md
- 00C02067377semanticnavigationexplained.md

For multi-group merges, use PRD numbers only (no sub-letters).

## 5. CLUSTERS = RELATED PRD GROUPS

Examples:
- **Cluster A ??? Identity Layer** ??? 16A???D ??? 00A16ABCDidentitylayerexplained.md
- **Cluster B ??? Truth/Limits/Enforcement** ??? 71A, 86A, 99A ??? 00B718699truthlimits_enforcement.md
- **Cluster C ??? Semantic Navigation** ??? 02A, 06A, 73A, 77A ??? 00C02067377semanticnavigationexplained.md

## 6. ORDER OF OPERATIONS

1. PRD
2. Schema
3. Mockups
4. Code

Cursor MUST NOT generate code before PRDs exist.

## 7. WHY-FILES = AUDIT LAYER

Explain violations, corrections, doctrine updates, verification steps.  
Non-versioned, non-canonical, mandatory for violations.

## 8. CAPTAIN'S LOY = NARRATIVE LAYER

Non-canonical, non-graph, non-doctrinal.  
MUST NOT influence PRDs.

## 9. PRD 86 = THE IMMUNE SYSTEM

Cursor MUST reject:
- foreign keys
- triggers
- stored procedures
- autoloaders
- frameworks
- invented schema fields
- invented governance vocabulary
- timestamp drift
- header drift
- path drift
- softening MUST/SHALL
- skipping WHY-files

## 10. WHY THIS SYSTEM IS A MASTERCLASS

- Finite namespace
- Two-dimensional PRD grid
- Constitutional merge layer
- Semantic OS
- Drift-proof architecture
- Multi-agent governance system

This is civilization-grade architecture.

---

## 11. PRD Primacy Law (Constitutional)

### 11.1 Sequential Reading Requirement

**PRDs are read sequentially, not conceptually.**

- The first PRD sets the worldview and overrides the model's training priors
- Later PRDs refine but cannot contradict the _A layer
- This is required to prevent hallucination, auto-formatting, timestamp conversion, whitespace collapse, and invented clustering schemes

### 11.2 Suffix Hierarchy

| Suffix | Meaning | Purpose |
|--------|---------|---------|
| _A | Foundational / Anti-Assumption | Kills model priors, defines constitutional truths |
| _B | Core Doctrine | Main rules for that PRD group |
| _C | Derived / Specific | Edge cases, examples, secondary rules |
| _D+ | Optional Extensions | Rare or extended cases |

### 11.3 Sequential prd_cluster (Constitutional)

**prd_cluster is not a set.**

- It is a human-defined sequence
- The order in the string is the exact reading order
- The bundler must feed PRDs to the AI in that order
- No sorting, merging, compressing, or reformatting is allowed
- Underscores must be preserved exactly

**Example:**
```yaml
prd_cluster: "00_B_16_A_99_A"
```
This means:
1. Read 00_B
2. Then 16_A
3. Then 99_A
In that exact order.

### 11.4 Anti-Hallucination Enforcement

**Models must not:**
- Invent clustering formats
- Compress PRD identifiers
- Reorder clusters
- Convert timestamps
- Collapse whitespace
- Remove underscores
- "Beautify" ASCII art
- Infer missing doctrine

These rules are constitutional and override any model training priors.

---

## IMPLEMENTATION NOTES FOR 4.1.5+

### thread_slug ??? prd_cluster Migration

All references to `thread_slug` in PRD documentation have been replaced with `prd_cluster`. This reflects the semantic shift from thread-based thinking to PRD cluster organization.

### Path Patterns

- **Transcripts**: `memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`
- **Staging Memory**: `memory/{channel_key}/staging/{YYYY}/{MM}/{prd_cluster}.toon`
- **Canonical Memory**: `memory/{channel_key}/canonical/1026/{MM}/{prd_cluster}.toon`

### Header Fields

The `transcript_jsonl` header field now uses the canonical slug pattern:
`{federation_node_id}/{channel_key}/{prd_cluster}`

This is a lookup slug, not a filesystem path.

---

**STATUS**: ACTIVE - CANONICAL - v4.1.5 READY

This PRD complies with Lupopedia Constitutional Root Rules and serves as the definitive reference for PRD system architecture.
