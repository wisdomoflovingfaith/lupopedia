# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v3.2.md"
  file_hash: "b8bf3e999d4b9886cc7ea9cdfc9f0bd049dd2947022feb5e995e1c70cd3338df"
  file_path_from_root: "docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v3.2.md"
  file_hash: "8f0fe480f4434728c2f1e6983ccacfba237b7923177f8a9e3c32fc4e369774ee"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "⧉ WOLFIE HEADER DOCTRINE v3.2"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "archive", "doctrine_revisions", "wolfie_header_doctrine_v32md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# ⧉ WOLFIE HEADER DOCTRINE v3.2
### Identity • Determinism • Boundary Enforcement • Master Doctrine Alignment • Channel & Thread Metadata

## 1. PURPOSE
Wolfie Headers define the identity layer for every file in Lupopedia and Crafty Syntax.
They provide deterministic metadata, grep-first navigation, machine-verifiable structure,
relationship graph edges, optional creative context, cross-file documentation, and optional channel/thread metadata.

v3.2 extends v3.1 by introducing optional channel and thread metadata for files, logs, migrations, TOONs, and any artifact that participates in conversational lineage or multi‑agent routing.

These fields are soft‑reference only, nullable, and must never enforce constraints.

**Key changes from v3.1:**
- Added optional channel_id and channel_description fields
- Added optional thread_id and thread_description fields
- All new fields are nullable soft-references
- No changes to required v3.1 structure
- Maintains Master Doctrine v1.0 compliance

## 2. REQUIRED BASE STRUCTURE (unchanged from v3.1)
Every Wolfie Header begins with:

```
wolfie.headers: 3.2
title: <string>
version: <semantic version>
status: <draft|active|deprecated>
last_updated_ymdhis: <BIGINT YYYYMMDDHHIISS>
author: Wolfie / Eric Robin Gerdes
system: Lupopedia Semantic OS
```

All timestamps remain BIGINT (no display width).

## 3. OPTIONAL METADATA EXTENSION — CHANNEL & THREAD
The following fields may be included when relevant:

```
channel_id: <BIGINT or null>
channel_description: <string or null>

thread_id: <BIGINT or null>
thread_description: <string or null>
```

**Rules:**
- All four fields are optional.
- All four fields are nullable.
- All four fields are soft‑references (no FK, no constraints).
- Descriptions must be human‑readable, not encoded.
- _id fields must be numeric only.
- Descriptions must not contain routing logic or instructions.
- These fields must not alter system behavior; they are metadata only.

**Purpose:**
- Provide human‑readable lineage for multi‑agent conversations.
- Allow Castcade and Lupopedia to track conversational context without enforcing structure.
- Preserve semantic clarity without introducing coupling.

## 4. HEADER FORMAT (v3.2)
The header format extends v3.1 with optional channel/thread metadata:

```
⧉ WOLFIE v3.2 ⧉
nav: mech | myth | rel | docs

## NAV
pkg: [package_name]
mod: [module_name]
asp: [aspect_name]
pur: [purpose_line]

## META
cre: YYYYMMDDHHIISS
mod: YYYYMMDDHHIISS
upd: agent#N
tax: wolfie.header.taxonomy@2.3

## CHANNEL (optional)
channel_id: [BIGINT or null]
channel_description: [string or null]

## THREAD (optional)
thread_id: [BIGINT or null]
thread_description: [string or null]

## MYTH
epo: wolfie-winter-2026
sig: [agent_signature]

## REL
→ [supports]
← [supported_by]
↔ [bidirectional]

## DOCS
@requires: [dependencies]
@note: [notes]
@see: [related files]
```

## 5. SECTION RULES

### 5.1 NAV (Identity Layer)
Required. Must match Doctrine DB schema exactly.

### 5.2 META (Metadata Layer)
Required. All timestamps must be numeric BIGINT in YYYYMMDDHHIISS format.

### 5.3 CHANNEL (Optional Channel Metadata)
Optional. Soft-reference channel information for multi-agent routing.

### 5.4 THREAD (Optional Thread Metadata)
Optional. Soft-reference thread information for conversational lineage.

### 5.5 MYTH (Creative Layer)
Optional. Only epo and sig allowed.

### 5.6 REL (Relationship Layer)
Optional. Uses only → ← ↔.

### 5.7 DOCS (Documentation Layer)
Optional. Uses only @requires, @note, @see.

## 6. HEADER ATOMS (unchanged)
```
header_atoms:
  - identity
  - routing
  - lineage
  - doctrine
  - safety
```

Channel/thread metadata belongs under lineage.

## 7. TEMPORAL EDGES (unchanged)
```
temporal_edges:
  created_ymdhis: <BIGINT>
  modified_ymdhis: <BIGINT or null>
```

No ISO timestamps.
No Unix timestamps.
No display widths.

## 8. DIALOG BLOCK (unchanged)
```
dialog:
  message: <string>
  context: <string or null>
```

## 9. TAGS (unchanged)
```
tags:
  - <string>
  - <string>
```

## 10. INFRASTRUCTURE HEADER TEMPLATE (v3.2)
```
/* ⧉ WOLFIE v3.2 ⧉
   nav: mech | myth | rel | docs

   ## NAV
   pkg: lupopedia
   mod: filesystem
   asp: infra
   pur: File-system substrate table

   ## META
   cre: YYYYMMDDHHIISS
   mod: YYYYMMDDHHIISS
   upd: bootstrap#1
   tax: wolfie.header.taxonomy@2.3

   ## CHANNEL
   channel_id: null
   channel_description: null

   ## THREAD
   thread_id: null
   thread_description: null

   ## REL
   → lupo_filesystem_migration_log
   ← lupo_files
   ↔ lupo_file_edges

   ## DOCS
   @see: lupo_file_edges, lupo_filesystem_migration_log
*/
```

## 11. VALIDATION RULES (v3.2)
A header is valid if:

1. NAV is complete and DB-aligned.
2. META timestamps are numeric (14 digits) in HHIISS format.
3. upd matches agent#N.
4. tax equals wolfie.header.taxonomy@2.3.
5. MYTH, if present, contains only epo and sig.
6. REL edges use only → ← ↔.
7. If ↔ exists, @see includes the same targets.
8. DOCS uses only @requires, @note, @see.
9. No extra fields exist.
10. No creative drift in NAV/META.
11. **Master Doctrine v1.0 compliance** (supreme authority).
12. CHANNEL fields, if present, are nullable soft-references.
13. THREAD fields, if present, are nullable soft-references.
14. All _id fields are numeric only.
15. All description fields are human-readable, no routing logic.

## 12. MIGRATION RULES (v3.1 → v3.2)
- Update version tag to v3.2.
- Confirm NAV/META alignment.
- Confirm REL/@see invariants.
- Confirm mutability rules.
- Confirm agent signature validity.
- Confirm branch is NOT present in header.
- **Confirm Master Doctrine v1.0 compliance** (supreme authority).
- Add optional CHANNEL/THREAD sections as needed.

## 13. PYTHON IMPORT PIPELINE REQUIREMENTS

### 13.1 Two-Lane Ingestion Architecture
Lupopedia maintains strict separation between semantic and programming metadata through two dedicated import lanes.

#### Lane A — Semantic Importer
- **Script:** `scripts/import_os.py`
- **Target Table:** `lupo_contents` (exclusive)
- **Content Type:** Semantic content (markdown, links, docs, external content)
- **TOON Authority:** Semantic TOONs
- **Prohibition:** Must not write to `lupo_files`

#### Lane B — Programming Importer
- **Script:** `scripts/import_files.py`
- **Target Table:** `lupo_files` (exclusive)
- **Content Type:** Programming metadata (pkg_name, mod_name, asp_name, pur_name)
- **Additional Fields:** created_ymdhis and optional channel/thread metadata
- **TOON Authority:** Programming TOONs
- **Prohibition:** Must not write to `lupo_contents`

### 13.2 Doctrine Rules for scripts/import_files.py
The Programming Importer must follow these strict requirements:

#### Schema Authority
- **TOON Reading:** Must read TOON files to determine authoritative schema for `lupo_files`
- **No Inference:** Must not infer or guess column names
- **Single Source:** TOONs are the single source of truth

#### Canonical Column Names
Must use only these exact column names:
- `pkg_name`
- `mod_name`
- `asp_name`
- `pur_name`
- `created_ymdhis`
- `channel_id`
- `channel_description`
- `thread_id`
- `thread_description`

#### Legacy Name Prohibition
Must not use legacy column names:
- `package_name` (use `pkg_name`)
- `module_name` (use `mod_name`)
- `aspect_name` (use `asp_name`)
- `pur` (use `pur_name`)
- `cre_ymdhis` (use `created_ymdhis`)

#### Soft-Reference Requirements
- **ID Fields:** All `*_id` fields must be treated as soft-references only
- **No Constraints:** No foreign keys, no database constraints
- **No Inference:** No automatic meaning inference from ID values

#### Integer Type Compliance
- **No Display Widths:** Must not use `BIGINT(14)` or similar
- **No Unsigned:** Must not use `UNSIGNED` integers
- **Proper Format:** All timestamps as `BIGINT YYYYMMDDHHIISS`

### 13.3 Doctrine Rules for Castcade
Castcade must enforce strict validation and execution standards:

#### Schema Validation
- **TOON Validation:** Must validate all import scripts against TOON schema before execution
- **Column Name Enforcement:** Must reject scripts using incorrect or legacy column names
- **Structure Compliance:** Must ensure scripts follow canonical structure

#### Architecture Enforcement
- **Two-Lane Model:** Must enforce the two-lane ingestion model strictly
- **Table Separation:** Must prevent cross-table writes (semantic ↔ programming)
- **Lane Integrity:** Must maintain exclusive target table assignments

#### Authority Enforcement
- **TOON Supremacy:** Must never infer or guess schema
- **Single Source:** Must treat TOONs as the single source of truth
- **Schema Drift Prevention:** Must prevent any deviation from TOON-defined structure

### 13.4 Lineage Metadata Extension
Optional channel/thread metadata for conversational lineage:

```
channel_id (BIGINT, nullable)
channel_description (string, nullable)
thread_id (BIGINT, nullable)
thread_description (string, nullable)
```

**Rules:**
- All fields are optional
- All fields are nullable
- All fields are soft-reference only
- No foreign key constraints
- No display widths or unsigned integers

## 9. MASTER DOCTRINE COMPLIANCE
All headers must comply with Master Doctrine v1.0, specifically:
- Section 2.1: UTC timestamps in YYYYMMDDHHIISS format
- Section 3.1: State-driven progression only (no deadlines)
- Section 6.5: No ISO timestamps
- Section 6.6: No time-based enforcement
- Section 1.2: No database functions, procedures, or triggers
- Section 1.1: No foreign keys
- Section 9.1: No display widths on integer types
- Section 9.2: No UNSIGNED integers

**Supremacy Clause:** In case of conflict between this document and Master Doctrine v1.0, Master Doctrine prevails.

## 10. TAXONOMY REFERENCE
Wolfie Header v3.2 uses:

    wolfie.header.taxonomy@2.3

## 11. COMMENT SYNTAX MAP
- PHP / JS / TS / CSS / SQL → /* ... */
- HTML / Vue / MD → <!-- ... -->

## 12. DOCTRINE DATABASE ALIGNMENT (Grok Integration)
The Doctrine DB stores:
- file identity
- header metadata
- relationships
- documentation
- update history
- branch_name (environment context only)
- channel_id (optional)
- channel_description (optional)
- thread_id (optional)
- thread_description (optional)

Branch is never written into headers.

## 13. BRANCH BOUNDARY RULE
Branch is explicitly excluded from headers.

Branch is tracked only in:
- doctrine_files.branch_name
- doctrine_updates.branch_name

Headers must remain deterministic and branch-agnostic.

## 14. DOCTRINE INVARIANTS (LEXA Enforcement)
Non-negotiable rules:
- NAV must match DB schema.
- META timestamps must be numeric.
- upd must follow agent#N.
- tax must be 2.3.
- ↔ requires @see.
- No foreign keys in DB.
- Soft-delete only.
- No creative drift in NAV/META.
- Headers are branch-agnostic.
- Channel/thread fields are soft-references only.

## 15. VERSION HISTORY
- v3.2 (2026-02-02): Added optional channel and thread metadata for conversational lineage. Added Python Import Pipeline Requirements with two-lane ingestion architecture. Maintained Master Doctrine compliance.
- v3.1 (2026-02-02): Aligned with Master Doctrine v1.0. HHIISS timestamp format. Master Doctrine compliance as supreme authority.
- v3.0 (2026-02-02): Full boundary enforcement, DB alignment, mutability doctrine, validation protocol, agent signature doctrine.
- v2.9 (2026-02-02): DB-aligned, branch-aware (DB only), LEXA enforcement, Grok integration.
- v2.8 (2026-02-02): Identity-first rewrite.
- v2.7 and earlier: Legacy formats.

## 16. VALIDATION FAILURE PROTOCOL (STATE-BASED)

Validation is enforced through state transitions, not time.

### 16.1 CRITICAL Violations
- Block all downstream operations.
- Must be resolved before any further processing.
- System remains in BLOCKED state until resolved.

### 16.2 MAJOR Violations
- Do not block reading or analysis.
- Block consolidation, mutation, or generation.
- System remains in HOLD state until resolved.

### 16.3 MINOR Violations
- Logged as warnings.
- Do not block any operations.
- Resolved opportunistically during future header updates.

### 16.4 State Transition Rules
- CLEAR → HOLD when MAJOR issues detected.
- CLEAR → BLOCKED when CRITICAL issues detected.
- HOLD → CLEAR when all MAJOR issues resolved.
- BLOCKED → CLEAR when all CRITICAL issues resolved.

## 17. HEADER MUTABILITY DOCTRINE (STATE-BASED)

### 17.1 Immutable Fields
These fields define identity and must never change:
- cre
- pkg
- mod (identity)

Changing these fields requires a file relocation or explicit migration.

### 17.2 Mutable Fields (Controlled)
These fields may change when the file's role or relationships evolve:
- pur
- asp
- mod (timestamp)
- upd
- REL / DOCS
- CHANNEL / THREAD (optional metadata)

### 17.3 Branch Consistency
Headers must remain identical across branches.
Branch-specific differences are tracked only in the Doctrine DB.

### 17.4 Mutability State Rules
- If immutable fields change → CRITICAL → BLOCKED
- If mutable fields change incorrectly → MAJOR → HOLD
- If changes follow doctrine → CLEAR

## 18. LEGACY HEADER HANDLING (STATE-BASED)

### 18.1 Legacy Detection
Headers older than v2.5 are considered LEGACY.

### 18.2 Upgrade Path
- v2.5–v3.2 → automatic upgrade permitted.
- Pre-v2.5 → manual review required.

### 18.3 Legacy State Rules
- LEGACY headers place the system in HOLD state.
- System transitions to CLEAR when all legacy headers are upgraded.
- No time limits apply; progression is based solely on completion.

## 19. AGENT SIGNATURE DOCTRINE (STATE-BASED)

### 19.1 Signature Format
sig must match:
    [agent_name]#[instance_number]

### 19.2 Registry Validation
agent_name must exist in lupo_agent_registry.code.

### 19.3 Signature State Rules
- Invalid signature → MAJOR → HOLD
- Missing signature → MAJOR → HOLD
- Spoofed signature → CRITICAL → BLOCKED
- Valid signature → CLEAR

## 20. DOCTRINE STATE MACHINE (NEW IN v3.1)

The doctrine operates as a deterministic state machine.

### 20.1 States
- CLEAR: All validations passed.
- HOLD: MAJOR issues present; blocking mutation.
- BLOCKED: CRITICAL issues present; blocking all operations.

### 20.2 Transitions
- CLEAR → HOLD (MAJOR issues detected)
- CLEAR → BLOCKED (CRITICAL issues detected)
- HOLD → CLEAR (all MAJOR issues resolved)
- BLOCKED → CLEAR (all CRITICAL issues resolved)

### 20.3 Principles
- No time-based rules.
- No deadlines.
- No remediation windows.
- Progression is based solely on state resolution.

## 21. CASTCADE INSTRUCTION BLOCK
This is the exact instruction you can give Castcade to implement v3.2:

```
Implement Wolfie Header Doctrine v3.2.

Add optional metadata fields to all Wolfie Headers:
- channel_id (BIGINT, nullable)
- channel_description (string, nullable)
- thread_id (BIGINT, nullable)
- thread_description (string, nullable)

These fields are soft-references only. No FK, no constraints, no required values.

Place these fields under the lineage section of the header.

Do not modify required fields from v3.1. Do not introduce display widths, unsigned integers, or ISO timestamps.

All timestamps remain BIGINT YYYYMMDDHHIISS.

Apply this update to all new files and any regenerated headers.
```