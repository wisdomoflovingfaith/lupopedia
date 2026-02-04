# ⧉ WOLFIE HEADER DOCTRINE v2.8
### Identity • Determinism • Navigation‑First

## 1. PURPOSE
Wolfie Headers define the identity layer for every file in Lupopedia and Crafty Syntax.
They provide deterministic metadata, grep‑first navigation, machine‑verifiable structure,
relationship graph edges, optional creative context, and cross‑file documentation.
v2.8 refines the doctrine for clarity, consistency, and DB alignment.

## 2. HEADER FORMAT (v2.8)
```
⧉ WOLFIE v2.8 ⧉
nav: mech | myth | rel | docs

## NAV
pkg: [package_name]
mod: [module_name]
asp: [aspect_name]
pur: [purpose_line]

## META
cre: YYYYMMDDHHMMSS
mod: YYYYMMDDHHMMSS
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
pkg → pkg_name
mod → mod_name
asp → asp_name
pur → single, grep‑friendly purpose line
No creative language. No multi‑line purpose. No ambiguity.

### 3.2 META (Timestamps + Tracking)
Required.
cre and mod must be numeric timestamps (YYYYMMDDHHMMSS).
upd must follow agent#N.
tax must equal wolfie.header.taxonomy@2.3.
No ISO timestamps. No T/Z suffixes.

### 3.3 MYTH (Optional Creative Overlay)
Optional. If present:
epo must be a valid epoch tag.
sig must be a short agent signature.
If omitted, the section is not included.

### 3.4 REL (Graph Edges)
Optional.
→ this file depends on target
← target depends on this file
↔ mutual dependency
Invariant: If ↔ is present, @see MUST reference the same targets.

### 3.5 DOCS (Cross‑References + Notes)
Optional.
Allowed fields: @requires, @note, @see.
@see must list canonical file/table names.
If ↔ exists, @see must include the same targets.
No additional @tags allowed.

## 4. INFRASTRUCTURE HEADER TEMPLATE (v2.8)
```
/* ⧉ WOLFIE v2.8 ⧉
   nav: mech | myth | rel | docs

   ## NAV
   pkg: lupopedia
   mod: filesystem
   asp: infra
   pur: File-system substrate table

   ## META
   cre: YYYYMMDDHHMMSS
   mod: YYYYMMDDHHMMSS
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

## 5. VALIDATION RULES (v2.8)
A header is valid if:
NAV is complete and DB‑aligned.
META timestamps are numeric.
upd matches agent#N.
tax is correct.
MYTH is optional.
REL edges use only → ← ↔.
If ↔ exists → @see must match.
No extra fields exist.
No creative drift in required fields.

## 6. MIGRATION RULES (v2.7 → v2.8)
Convert timestamps to numeric format.
Remove creative language from NAV.
Remove unused DOCS fields.
Enforce REL/@see invariant.
Make MYTH optional.
Update version tag to v2.8.

## 7. TAXONOMY REFERENCE
wolfie.header.taxonomy@2.3 defines directory → pkg/mod inference,
filename → asp inference, and fallback rules.

## 8. COMMENT SYNTAX MAP
PHP / JS / TS / CSS → /* */
HTML / Vue / MD → <!-- -->

## 9. DOCTRINE INVARIANTS
NAV must match DB schema.
META timestamps must be numeric.
upd must be agent#N.
↔ requires @see.
tax must be 2.3.
No foreign keys in DB.
Soft‑delete only.
No creative drift in required fields.

## 10. VERSION HISTORY
v2.8 (2026‑02‑02): Identity‑first rewrite. Numeric timestamps. MYTH optional. REL/@see invariant. DB alignment.
v2.7 (2026‑02‑02): Infrastructure doctrine. @see support. Bidirectional edge rules.
v2.6 (2026‑02‑01): Balanced context + efficiency.
v2.5 and earlier: Legacy formats.
