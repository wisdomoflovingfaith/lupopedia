# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/archive/doctrine_revisions/WOLFIE_HEADER_DOCTRINE_v3.1.md"
  file_hash: "5eb638cf5f47f5150de813223922ce4fed0e32557d7337937bf8a85e11077317"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v3.1.md"
  file_hash: "3fe910af8c19f5a0985361d390916a017ead6396dab00d9f33d06bd7afd5adde"
  file_path_from_root: "lupo-docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v3.1.md"
  file_hash: "e1c74f5245bc8de9a8d64c98a405047b1b385cd5341fd17e38263dac19fd7ef0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "⧉ WOLFIE HEADER DOCTRINE v3.1"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "archive", "doctrine_revisions", "wolfie_header_doctrine_v31md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# ⧉ WOLFIE HEADER DOCTRINE v3.1
### Identity • Determinism • Boundary Enforcement • Master Doctrine Alignment

## 1. PURPOSE
Wolfie Headers define the identity layer for every file in Lupopedia and Crafty Syntax.
They provide deterministic metadata, grep-first navigation, machine-verifiable structure,
relationship graph edges, optional creative context, and cross-file documentation.

v3.1 consolidates all prior doctrine versions and incorporates:
- LEXA boundary enforcement (Sections 13–16)
- Grok database alignment and branch-boundary rules
- Mutability doctrine
- Validation failure protocol
- Legacy header handling
- Agent signature doctrine
- Clarified invariants for the semantic OS
- State-based enforcement (no time dependencies)
- **Master Doctrine v1.0 compliance** (supreme authority)

**Key changes from v3.0:**
- Timestamp format: HHIISS (not HHMMSS) per Master Doctrine Section 2.1
- No time-based enforcement (state-driven only) per Master Doctrine Section 3.1
- Supreme authority: Master Doctrine v1.0 overrides this document in case of conflict

Headers remain branch-agnostic. Branch is tracked only in the Doctrine Database.

## 2. HEADER FORMAT (v3.1)
The header format remains identical to v2.9.

```
⧉ WOLFIE v3.1 ⧉
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

## 3. SECTION RULES

### 3.1 NAV (Identity Layer)
Required. Must match Doctrine DB schema exactly.

- pkg → pkg_name
- mod → mod_name
- asp → asp_name
- pur → pur_name (single, grep-friendly line)

NAV is pure identity. No creative language. No ambiguity.

### 3.2 META (Timestamps + Tracking)
Required.

- cre: numeric timestamp (YYYYMMDDHHMMSS)
- mod: numeric timestamp (YYYYMMDDHHMMSS)
- upd: agent#N
- tax: wolfie.header.taxonomy@2.3

No ISO timestamps. No T/Z suffixes.

### 3.3 MYTH (Optional Creative Overlay)
Optional. If present:
- epo: valid epoch tag
- sig: valid agent signature

### 3.4 REL (Graph Edges)
Optional.

- → supports
- ← supported_by
- ↔ bidirectional

Invariant: If ↔ exists, @see must reference the same targets.

### 3.5 DOCS (Cross-References + Notes)
Optional.

Allowed fields: @requires, @note, @see.

@see must list canonical file/table names.

## 4. INFRASTRUCTURE HEADER TEMPLATE (v3.1)
```
/* ⧉ WOLFIE v3.1 ⧉
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

   ## REL
   → lupo_filesystem_migration_log
   ← lupo_files
   ↔ lupo_file_edges

   ## DOCS
   @see: lupo_file_edges, lupo_filesystem_migration_log
*/
```

## 5. VALIDATION RULES (v3.1)
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

## 6. MIGRATION RULES (v2.9 → v3.1)
- Update version tag to v3.1.
- Confirm NAV/META alignment.
- Confirm REL/@see invariants.
- Confirm mutability rules.
- Confirm agent signature validity.
- Confirm branch is NOT present in header.
- **Confirm Master Doctrine v1.0 compliance** (supreme authority).

## 7. MASTER DOCTRINE COMPLIANCE
All headers must comply with Master Doctrine v1.0, specifically:
- Section 2.1: UTC timestamps in YYYYMMDDHHIISS format
- Section 3.1: State-driven progression only (no deadlines)
- Section 6.5: No ISO timestamps
- Section 6.6: No time-based enforcement
- Section 1.2: No database functions, procedures, or triggers
- Section 1.1: No foreign keys

**Supremacy Clause:** In case of conflict between this document and Master Doctrine v1.0, Master Doctrine prevails.

## 8. TAXONOMY REFERENCE
Wolfie Header v3.1 uses:

    wolfie.header.taxonomy@2.3

## 9. COMMENT SYNTAX MAP
- PHP / JS / TS / CSS / SQL → /* ... */
- HTML / Vue / MD → <!-- ... -->

## 10. DOCTRINE DATABASE ALIGNMENT (Grok Integration)
The Doctrine DB stores:
- file identity
- header metadata
- relationships
- documentation
- update history
- branch_name (environment context only)

Branch is never written into headers.

## 11. BRANCH BOUNDARY RULE
Branch is explicitly excluded from headers.

Branch is tracked only in:
- doctrine_files.branch_name
- doctrine_updates.branch_name

Headers must remain deterministic and branch-agnostic.

## 12. DOCTRINE INVARIANTS (LEXA Enforcement)
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

## 13. VERSION HISTORY
- v3.1 (2026-02-02): Aligned with Master Doctrine v1.0. HHIISS timestamp format. Master Doctrine compliance as supreme authority.
- v3.0 (2026-02-02): Full boundary enforcement, DB alignment, mutability doctrine, validation protocol, agent signature doctrine.
- v2.9 (2026-02-02): DB-aligned, branch-aware (DB only), LEXA enforcement, Grok integration.
- v2.8 (2026-02-02): Identity-first rewrite.
- v2.7 and earlier: Legacy formats.

## 14. VALIDATION FAILURE PROTOCOL (STATE-BASED)

Validation is enforced through state transitions, not time.

### 14.1 CRITICAL Violations
- Block all downstream operations.
- Must be resolved before any further processing.
- System remains in BLOCKED state until resolved.

### 14.2 MAJOR Violations
- Do not block reading or analysis.
- Block consolidation, mutation, or generation.
- System remains in HOLD state until resolved.

### 14.3 MINOR Violations
- Logged as warnings.
- Do not block any operations.
- Resolved opportunistically during future header updates.

### 14.4 State Transition Rules
- CLEAR → HOLD when MAJOR issues detected.
- CLEAR → BLOCKED when CRITICAL issues detected.
- HOLD → CLEAR when all MAJOR issues resolved.
- BLOCKED → CLEAR when all CRITICAL issues resolved.

## 15. HEADER MUTABILITY DOCTRINE (STATE-BASED)

### 15.1 Immutable Fields
These fields define identity and must never change:
- cre
- pkg
- mod (identity)

Changing these fields requires a file relocation or explicit migration.

### 15.2 Mutable Fields (Controlled)
These fields may change when the file's role or relationships evolve:
- pur
- asp
- mod (timestamp)
- upd
- REL / DOCS

### 15.3 Branch Consistency
Headers must remain identical across branches.
Branch-specific differences are tracked only in the Doctrine DB.

### 15.4 Mutability State Rules
- If immutable fields change → CRITICAL → BLOCKED
- If mutable fields change incorrectly → MAJOR → HOLD
- If changes follow doctrine → CLEAR

## 16. LEGACY HEADER HANDLING (STATE-BASED)

### 16.1 Legacy Detection
Headers older than v2.5 are considered LEGACY.

### 16.2 Upgrade Path
- v2.5–v2.9 → automatic upgrade permitted.
- Pre-v2.5 → manual review required.

### 16.3 Legacy State Rules
- LEGACY headers place the system in HOLD state.
- System transitions to CLEAR when all legacy headers are upgraded.
- No time limits apply; progression is based solely on completion.

## 17. AGENT SIGNATURE DOCTRINE (STATE-BASED)

### 17.1 Signature Format
sig must match:
    [agent_name]#[instance_number]

### 17.2 Registry Validation
agent_name must exist in lupo_agent_registry.code.

### 17.3 Signature State Rules
- Invalid signature → MAJOR → HOLD
- Missing signature → MAJOR → HOLD
- Spoofed signature → CRITICAL → BLOCKED
- Valid signature → CLEAR

## 18. DOCTRINE STATE MACHINE (NEW IN v3.1)

The doctrine operates as a deterministic state machine.

### 18.1 States
- CLEAR: All validations passed.
- HOLD: MAJOR issues present; blocking mutation.
- BLOCKED: CRITICAL issues present; blocking all operations.

### 18.2 Transitions
- CLEAR → HOLD (MAJOR issues detected)
- CLEAR → BLOCKED (CRITICAL issues detected)
- HOLD → CLEAR (all MAJOR issues resolved)
- BLOCKED → CLEAR (all CRITICAL issues resolved)

### 18.3 Principles
- No time-based rules.
- No deadlines.
- No remediation windows.
- Progression is based solely on state resolution.
