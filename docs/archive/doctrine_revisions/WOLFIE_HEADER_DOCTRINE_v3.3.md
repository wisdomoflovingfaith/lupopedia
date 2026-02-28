# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v3.3.md"
  file_hash: "c54459fcb28d8ec8701a45ada42ef5947861f87f94a91457ac7b9c082d98a397"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "⧉ WOLFIE HEADER DOCTRINE v3.3"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "archive", "doctrine_revisions", "wolfie_header_doctrine_v33md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# ⧉ WOLFIE HEADER DOCTRINE v3.3
### Identity • Determinism • Boundary Enforcement • Master Doctrine Alignment • Channel & Thread Metadata

## 1. PURPOSE
Wolfie Headers define the identity layer for every file in Lupopedia and Crafty Syntax.
They provide deterministic metadata, grep-first navigation, machine-verifiable structure,
relationship graph edges, optional creative context, cross-file documentation, and optional channel/thread metadata.

v3.3 resolves structural contradictions in v3.2 by removing mixed-format definitions, eliminating duplicate sections, and separating ingestion architecture into its own doctrine.

**Key changes from v3.2:**
- Removed YAML-style header format (Section 2)
- Eliminated duplicate Section 9 (TAGS)
- Separated Python Import Pipeline Requirements into dedicated doctrine
- Renumbered all sections sequentially (17 sections total)
- Preserved all v3.2 improvements: channel/thread metadata, soft-reference rules, state machine, validation rules, Master Doctrine v1.0 compliance, mutability doctrine, relationship invariants

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

## 4. HEADER FORMAT (v3.3)
The canonical Wolfie header format is the traditional block beginning with:

```
⧉ WOLFIE v3.3 ⧉
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

## 10. INFRASTRUCTURE HEADER TEMPLATE (v3.3)
```
/* ⧉ WOLFIE v3.3 ⧉
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

## 11. VALIDATION RULES (v3.3)
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

## 12. MIGRATION RULES (v3.2 → v3.3)
- Update version tag to v3.3.
- Confirm NAV/META alignment.
- Confirm REL/@see invariants.
- Confirm mutability rules.
- Confirm agent signature validity.
- Confirm branch is NOT present in header.
- **Confirm Master Doctrine v1.0 compliance** (supreme authority).
- Add optional CHANNEL/THREAD sections as needed.
- Remove any YAML-style header references.

## 13. TAXONOMY REFERENCE
Wolfie Header v3.3 uses:

    wolfie.header.taxonomy@2.3

## 14. COMMENT SYNTAX MAP
- PHP / JS / TS / CSS / SQL → /* ... */
- HTML / Vue / MD → <!-- ... -->
- Python → """ ... """

## 15. DOCTRINE DATABASE ALIGNMENT (Grok Integration)
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

## 16. DOCTRINE INVARIANTS (LEXA Enforcement)
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

## 17. VERSION HISTORY
- v3.3 (2026-02-02): Resolved structural contradictions by removing YAML format, eliminating duplicate sections, separating ingestion architecture. Preserved all v3.2 improvements.
- v3.2 (2026-02-02): Added optional channel and thread metadata for conversational lineage. Added Python Import Pipeline Requirements with two-lane ingestion architecture. Maintained Master Doctrine compliance.
- v3.1 (2026-02-02): Aligned with Master Doctrine v1.0. HHIISS timestamp format. Master Doctrine compliance as supreme authority.
- v3.0 (2026-02-02): Full boundary enforcement, DB alignment, mutability doctrine, validation protocol, agent signature doctrine.
- v2.9 (2026-02-02): DB-aligned, branch-aware (DB only), LEXA enforcement, Grok integration.
- v2.8 (2026-02-02): Identity-first rewrite.
- v2.7 and earlier: Legacy formats.
