---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/16_C_LUPOPEDIA_HEADERS.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/16_C_LUPOPEDIA_HEADERS.md"
  status: active
  when_updated: "20260422204838"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/headers/canonical/1026/04/lupopedia-headers.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
  artifact_type: prd
  artifact_kind: specification
  channel_key: headers
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_16_B_16_C_57_A
  title: "PRD 16: Lupopedia Headers (Implementation Details)"
  summary: "Implementation details for Lupopedia header contract at version 4.1.4. Field definitions, validation rules, and examples."
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->

<HUMAN_SEMANTIC>
This file belongs to:
??? PRD Group 16 (Identity Layer ??? Headers, Atoms, Migration)
??? Cluster 16ABCD
??? Channel: headers
??? No default collection yet

See also:
??? 00_A_SYSTEM_CANONICAL_EXPLANATION.md
??? 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
??? PRD 86 ??? Immune system (no drift, no entropy)
??? Order of Operations: PRD ??? Schema ??? Mockups ??? Code
</HUMAN_SEMANTIC>

# PRD 16: Lupopedia Headers (Normative Spec)

## Revision path

| Revision | Date (UTC) | Summary |
|---|---|---|
| doc pass | 2026-04-18 | ??4.2 label v4.1.3; **transcript_jsonl** slug SHALL in ??6; **questions_toon** example ??9; **HDR_PK_LEGACY_ALIAS** in ??10; PRD 38 / 51 / 19 / 79 cross-refs; ??16.6.1 **line_end** for final section; ??4.3 table spacing. |
| v4.1.3 | 2026-04-15 | LILITH audit: ??15.4 version/pk_* alignment; `artifact_type` adds `version-doc` and `status`; ??11 pk_* timeline table. |
| v4.1.1 | 2026-04-15 | `content_*` alignment finalized, `default_collection_id` added, header authority clarified. |
| v4.1.0 | 2026-04-15 | `dialog_transcript` renamed to `transcript_jsonl`; canonical order reflowed. |
| v4.0.99 | 2026-04-10 | Dense canonical header family established. |
| v4.1.4 | 2026-04-21 | Unfreeze 4.1.3 for critical fixes; remove content_slug references; fix section numbering; update freeze language to 4.1.4. |

## Header freeze rule (4.1.4)

**Normative**

- The Lupopedia **header contract** is currently at **`header_format_version: "4.1.4"`**.
- The freeze was temporarily lifted from 4.1.3 to address critical structural issues (prd_cluster and atoms corrections).
- Once foundation fixes are complete, the header format will be frozen again for the delivery phase.
- During freeze: **no new header fields**, **no field removals**, **no reordering experiments**, and **no semantic redefinitions** of existing fields.
- **Header redesign, expansion, or cleanup** proposals are deferred until after the Crafty Syntax baseline is complete.
- Agents **MUST** implement and validate new and edited envelopes against the current frozen version.

**Rationale:** Stability while live-help parity ships; less doctrine churn; fewer IDE/agent misreads during routing, membership, and visitor-facing work.

**Forward note:** Header-format evolution may continue after the baseline is complete. The freeze is a stability measure, not abandonment of improvement roadmap.

## 1. Purpose

This document defines the normative Lupopedia header contract for in-scope authored files:

- identity linkage (`content_id`, `file_path_from_root`)
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

- identity (`content_id`, `file_path_from_root`)
- routing (`channel_key`, `federation_node_id`, `transcript_jsonl`)
- linkage (`memory_toon`, `atoms_toon`, `questions_toon`)

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

- `memory/atoms/lupopedia_global_constants.atom.toon`
- `constants.header_fields.count`
- `constants.header_fields.order`

Current canonical count: **22** fields.

### 4.2 Canonical field order (v4.1.4)

The canonical 22-field order (authoritative from `memory/atoms/lupopedia_global_constants.atom.toon`):

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
18. `default_collection_id`
19. `lupopedia.schema`
20. `prd_cluster`
21. `title`
22. `summary`

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

`lupopedia.schema` MUST equal `artifact_type` (field 20). Under header freeze (v4.1.4), no new legacy or specialized `artifact_type` values may be introduced. Existing legacy values (`discussion`, `changelog`, `architecture`, `specification`) are deprecated and MUST NOT be used for new artifacts. Only the canonical closed enum values from ??4.3 are permitted for new work.

### 4.4 Field 12 ??? `artifact_kind` (cross-field with field 11)

For each `artifact_type`, `artifact_kind` MUST be one of the values in the corresponding set. This table is normative and MUST match `ARTIFACT_TYPE_ALLOWED_KINDS` in `scripts/lib/header_spec_v3_1.py` (single source for validators).

`default_collection_id` (field 19) is always nullable (`null` or integer) regardless of `artifact_type`.

### 4.5 Field 20 ??? `prd_cluster` (PRD lineage tracking)

**Zero-Heuristic Rules (Constitutional):**

- **STRICT LINEAGE:** The prd_cluster MUST represent the exact chronological read-order of the source documents.
- **NO SORTING:** Do NOT perform numerical or alphabetical sorting. If the agent reads 16_A then 00_B, the cluster MUST begin with 16_A_00_B.
- **UNDERSCORE PRESERVATION:** Underscores are load-bearing delimiters. Do NOT collapse, merge, or remove them.
- **LITERAL CONCATENATION:** The cluster is a literal join of the PRD filenames (minus the extension) using a single underscore as the connector.

**Logic Examples:**
- Input: 00_B.md, 16_A.md, 99_A.md ??? Output: 00_B_16_A_99_A
- Input: 99_A.md, 00_B.md ??? Output: 99_A_00_B

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

### Purpose

`prd_cluster` strings may become long and difficult to read when multiple PRDs are included in the execution sequence. This section defines an optional shorthand notation to improve human readability without altering canonical meaning.

---

### 1. Canonical Rule (Unchanged)

The full PRD identifier (e.g., `00_A_FORBIDDEN_AND_WHY`) remains the canonical representation of a PRD.

- Full identifiers are the source of truth
- Validators, lineage, and execution MUST operate on canonical identifiers

Shorthand notation MUST NOT replace canonical identifiers in validation or execution.

---

### 2. Shorthand Notation (Optional)

A shortened form MAY be used when the PRD is unambiguous:

- `00_A` 
- `16_B` 
- `16_C` 
- `33_A` 

Shorthand represents:

[PRD number]_[section letter]

---

### 3. Mapping Requirement

All shorthand identifiers MUST resolve to a single canonical PRD.

The canonical mapping MUST be defined in:

- PRD 84 (PRD Number Allocation Doctrine)

Example mapping:

00_A → 00_A_FORBIDDEN_AND_WHY  
16_B → 16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS  
16_C → 16_C_LUPOPEDIA_HEADERS  
33_A → 33_A_SOFTACULOUS_CERTIFICATION_4_1_0_GATE  

If a shorthand is ambiguous, it MUST NOT be used.

---

### 4. Usage Rules

A. Full → Shorthand  
- First occurrence MAY use full identifier  
- Subsequent occurrences MAY use shorthand  

B. All Shorthand  
- Entire cluster MAY use shorthand if all entries are unambiguous  

Example:

prd_cluster: 00_A_16_B_16_C_33_A

---

### 5. Compatibility Rule (Critical)

- Existing clusters using full identifiers remain valid  
- No migration is required  
- Shorthand is additive and optional  

---

### 6. Determinism Requirement

Shorthand resolution MUST be:

- deterministic  
- one-to-one  
- non-inferential  

Shorthand MUST be expanded to canonical identifiers BEFORE validation or execution.

AI agents MUST NOT guess mappings.

If mapping is missing:

UNDEFINED IN PRD

---

### PRD Cluster Format (Shorthand Only)

#### Format Requirement

`prd_cluster` MUST use shorthand selector tokens only.

Format:
* `NN_X` repeated with underscores
* Example: `00_A_55_A_16_C` 

Where:
* `NN` = exactly two digits (00–99)
* `X` = exactly one uppercase letter (A–Z)

#### No Other Content Allowed

`prd_cluster` MUST NOT contain:
* descriptive text
* extra words
* mixed formats

Examples:
* VALID: `00_A_55_A` 
* INVALID: `00_A_FORBIDDEN_AND_WHY_55_A` 

If anything other than selector tokens exists → REJECT

#### Single-Line Constraint

`prd_cluster` MUST be a single-line string.

It MUST NOT contain:
* newline characters
* carriage returns
* spaces
* tabs

No YAML multiline block form.
No list form.
No wrapped formatting.

#### Parsing Rule

Parser MUST:
1. Split on `_` 
2. Ensure token count is even
3. Even positions = two digits
4. Odd positions = one uppercase letter
5. Each pair = selector token

If ANY rule fails → cluster is INVALID

#### No Guessing / No Extraction

Parser MUST NOT:
* use regex to extract tokens
* ignore descriptive text
* attempt recovery

Invalid input = reject immediately

#### Selector Expansion

Each selector token expands to all files matching `docs/prd/NN_X*`

Example:
* `00_A_55_A` means:
  1. expand `00_A` 
  2. expand `55_A` 
  3. concatenate results in order

#### Deterministic Expansion

Expansion MUST be deterministic.
Use stable lexicographic filename order for all matches.
If selector expansion matches no files, the selector is INVALID.

#### Forbidden Content

`prd_cluster` MUST NOT contain:
* `/` 
* `\` 
* `.` 
* `:` 
* `http` 
* `https` 
* `ftp` 
* `file` 
* `../` 
* `./` 
* `~/` 
* any character outside `[0-9A-Z_]` 

If forbidden content appears, the cluster is INVALID.

#### Migration Status

* Only shorthand clusters are valid
* No legacy support remains
* All existing clusters converted to shorthand

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
- Quotes MAY be used for fields containing special characters, paths, or version strings (e.g., `header_format_version`, `title`, `summary`, `file_path_from_root`, `web_path`).
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
- pattern: `memory/{channel_key}/questions/{YYYY}/{MM}/{slug}.questions.toon`

**Example:** `memory/headers/questions/2026/04/lupopedia_headers.questions.toon`

## 10. Validator rules (normative)

Validators must enforce:

- canonical 22-field order
- required key presence
- `artifact_type` / `artifact_kind` / `lupopedia.schema` cross-field rules (see ??4.4; `HDR_ARTIFACT_TYPE`, `HDR_SCHEMA_ARTIFACT_MISMATCH`)
- type checks including:
  - `content_id` = null or integer
  - `content_parent_id` = null or integer
  - `default_collection_id` = null or integer (`HDR_DEFAULT_COLLECTION_INVALID`)
- **ID field null handling:** All `_id` fields (content_id, content_parent_id, default_collection_id, thread_id) MUST be either null or integer. Empty strings are prohibited.
- **ASCII-only compliance:** All header values and the entire file MUST be ASCII-only. Non-ASCII characters (including smart quotes, em dashes, Unicode arrows, and emoji) are forbidden. (`HDR_ASCII_VIOLATION`)
- **Empty string normalization:** For non-`_id` nullable fields (questions_toon, memory_toon, atoms_toon), empty strings are prohibited; use `null` instead. (`HDR_EMPTY_STRING`)
- **Migration enforcement:** Existing files with empty-string `_id` values MUST be corrected to `null` during validation; this is a mandatory auto-correction. (`HDR_MIGRATION_VIOLATION`)
- **No header field may end in `_slug`.** Slug patterns are handled by external routing systems only. (`HDR_SLUG_FIELD_VIOLATION`)
- **transcript slug collision:** `transcript_jsonl` routing slug MUST NOT equal any other header field value. (`HDR_TRANSCRIPT_SLUG_COLLISION`)
- **external slug prevention:** Header fields MUST NOT contain slug patterns. Detection criteria: Field value matches regex `^[a-z0-9]+(-[a-z0-9]+)*$` (kebab-case slug pattern) AND field is NOT `transcript_jsonl`. Exception: `transcript_jsonl` may contain slash-separated slug-like segments as part of its routing path. (`HDR_EXTERNAL_SLUG`)
- `trust_tier` = one of `canonical`, `development` (`HDR_TRUST_TIER_INVALID`; legacy tiers warn during transition)
- `transcript_jsonl` header authority semantics (slug shape per **??6**)
- legacy alias handling per migration policy (**??11**); **`HDR_PK_LEGACY_ALIAS`** when legacy `pk_*` / `parent_pk_id` keys are present ??? **WARN** for declared **`header_format_version`** **`4.1.0`???`4.1.2`** only; **ERROR** for **`4.1.3`+** (see **??11.1**)
- **channel/path consistency** (`HDR_CHANNEL_PATH_MISMATCH`): when `memory_toon` is non-null,
  the first path segment after `memory/` MUST equal the declared `channel_key`. A mismatch
  is a validation ERROR. When `memory_toon` is null, the check is skipped.
- `memory_toon` path is derived from header-declared metadata and is validated against header fields; it is not authoritative over header values.

### 10.1 `HDR_CHANNEL_PATH_MISMATCH` (normative)

```python
# PRD 16 ??10.1 ??? channel/path consistency check
def check_channel_path_consistency(channel_key, memory_toon):
    if memory_toon is None:
        return None  # null memory_toon is always valid here
    # memory_toon form: memory/{channel_key}/{trust_tier}/...
    parts = memory_toon.strip().split('/')
    if len(parts) < 2 or parts[0] != 'memory':
        return 'HDR_CHANNEL_PATH_MISMATCH'  # malformed path
    path_channel = parts[1]
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

The header format was frozen at version 4.1.3, then temporarily unfrozen for critical fixes. Current version is 4.1.4. See Header freeze rule (4.1.4) above. This freeze includes:
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
- approves header and schema changes (subject to **Header freeze rule (4.1.4)**; no normative header experiments during the freeze without orchestration exception)

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

**Current: 4.1.4**

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
# PRD 16 ??15 ??? version acceptance policy (4.1.4 current; validator branch unchanged)
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
- **Web Path Consistency Violations:** `web_path` does not correspond to `file_path_from_root` with proper subdirectory mapping (`HDR_WEB_PATH_CONSISTENCY`).

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

* referenced PRD file MUST exist
* referenced PRD file MUST have `header_format_version = "4.1.4"` (exact match, no backward compatibility)

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
3. VALIDATE header version (HARD FAIL if != "4.1.4")

### FAILURE BEHAVIOR

On validation failure:

* STOP implementation and deployment actions only
* VALIDATION layers (validator + tests) MUST still execute to surface violations
* Validator/tests MUST report PRD_CLUSTER violations as HARD FAIL with explicit error codes:
  * `HDR_PRD_CLUSTER_MISSING` for non-existent PRD files
  * `HDR_PRD_CLUSTER_OUTDATED` for PRD files with header_version != "4.1.4"
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
2. **Are they current?** (All must have header_format_version = "4.1.4")
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
