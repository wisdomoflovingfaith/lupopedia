# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v2.9.md"
  file_hash: "937a9956d8833e3d25af4c544c0887bc89c22aea788dfc5931b4ab2cac6cab7f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "⧉ WOLFIE HEADER DOCTRINE v2.9"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "archive", "doctrine_revisions", "wolfie_header_doctrine_v29md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# ⧉ WOLFIE HEADER DOCTRINE v2.9
### Identity • Determinism • Navigation‑First • DB‑Aligned

## 1. PURPOSE
Wolfie Headers define the identity layer for every file in Lupopedia and Crafty Syntax.
They provide deterministic metadata, grep‑first navigation, machine‑verifiable structure,
relationship graph edges, optional creative context, and cross‑file documentation.

v2.9 refines the doctrine for:
- strict DB alignment
- explicit boundary enforcement (LEXA)
- clear separation of header vs. branch vs. database (Grok)

Headers remain branch‑agnostic. Branch lives only in the Doctrine Database.

## 2. HEADER FORMAT (v2.9)
The header format is identical to v2.8.

```
⧉ WOLFIE v2.9 ⧉
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

- pkg → maps to DB column pkg_name
- mod → maps to DB column mod_name
- asp → maps to DB column asp_name
- pur → maps to DB column pur_name (single, grep‑friendly line)

Rules:
- No creative language in NAV.
- No multi‑line purpose.
- No ambiguity.
- NAV is pure identity, not lore.

### 3.2 META (Timestamps + Tracking)
Required.

- cre: numeric timestamp (YYYYMMDDHHMMSS)
- mod: numeric timestamp (YYYYMMDDHHMMSS)
- upd: must follow pattern agent#N
- tax: must equal wolfie.header.taxonomy@2.3

Rules:
- No ISO timestamps.
- No T/Z suffixes.
- META reflects file‑level lifecycle, not branch state.

### 3.3 MYTH (Optional Creative Overlay)
Optional.

If present:
- epo: a valid epoch tag (e.g., wolfie-winter-2026)
- sig: a short agent signature

If omitted, the MYTH section is simply not included.
MYTH must not override or contradict NAV/META.

### 3.4 REL (Graph Edges)
Optional.

Semantics:
- → this file depends on target (supports)
- ← target depends on this file (supported_by)
- ↔ mutual dependency (bidirectional)

v2.9 invariant:
- If ↔ is present, @see MUST reference the same targets.

REL defines the semantic graph and is mirrored into the Doctrine DB
(doctrine_relationships / edges tables).

### 3.5 DOCS (Cross‑References + Notes)
Optional.

Allowed fields:
- @requires:
- @note:
- @see:

Rules:
- @see must list canonical file/table names.
- If ↔ exists, @see must include the same targets as the ↔ edges.
- No additional @tags are allowed in DOCS.
- DOCS content is mirrored into doctrine_docs / docs_* tables.

## 4. INFRASTRUCTURE HEADER TEMPLATE (v2.9)
Example for infrastructure tables:

```
/* ⧉ WOLFIE v2.9 ⧉
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

MYTH is intentionally omitted for infra.

## 5. VALIDATION RULES (v2.9)
A header is valid if:

1. NAV is complete and DB‑aligned:
   - pkg, mod, asp, pur all present and non‑empty.
2. META timestamps are numeric (14 digits).
3. upd matches agent#N.
4. tax equals wolfie.header.taxonomy@2.3.
5. MYTH, if present, has only epo and sig.
6. REL edges use only → ← ↔.
7. If ↔ exists, @see includes the same targets.
8. DOCS uses only @requires, @note, @see.
9. No extra fields exist in NAV, META, MYTH, REL, DOCS.
10. No creative drift in NAV/META fields.

These rules are enforced by LEXA‑style validators and Grok‑style DB importers.

## 6. MIGRATION RULES (v2.8 → v2.9)
For existing headers:

1. Update version tag from v2.8 to v2.9.
2. Confirm NAV fields map cleanly to DB schema (pkg_name, mod_name, asp_name, pur_name).
3. Confirm META timestamps are numeric and 14 digits.
4. Confirm upd follows agent#N.
5. Confirm tax is wolfie.header.taxonomy@2.3.
6. Confirm REL/@see invariant:
   - All ↔ edges have matching @see entries.
7. Remove any extra DOCS tags beyond @requires, @note, @see.

No structural changes to the header format are required between v2.8 and v2.9.
v2.9 is a boundary‑clarification and DB‑alignment release.

## 7. TAXONOMY REFERENCE
Wolfie Header v2.9 uses:

    wolfie.header.taxonomy@2.3

This taxonomy defines:
- directory → pkg/mod inference
- filename → asp inference
- fallback rules

Taxonomy is implemented in the Doctrine DB and in header generators/importers.

## 8. COMMENT SYNTAX MAP
Headers are embedded using language‑appropriate comment syntax:

- PHP / JS / TS / CSS / SQL → /* ... */
- HTML / Vue / MD → <!-- ... -->

The header content itself must remain identical across languages; only the comment wrapper changes.

## 9. DOCTRINE DATABASE ALIGNMENT (Grok Integration)
The Wolfie Header Doctrine is mirrored into a Doctrine Database.

Core concepts:

- Headers define identity and relationships.
- The Doctrine DB stores searchable, queryable representations of that identity and graph.

Key tables (conceptual):

- doctrine_files
  - path, filename, extension, hash
  - created_ymdhis, modified_ymdhis
  - is_infrastructure, is_toon
  - branch_name (environment context, NOT in header)
  - is_deleted, deleted_ymdhis

- doctrine_headers
  - file_id (soft reference)
  - pkg_name, mod_name, asp_name, pur_name
  - cre_ymdhis, mod_ymdhis
  - upd_name, tax_name
  - epo_name, sig_name
  - is_deleted, deleted_ymdhis

- doctrine_relationships / edges
  - file_id (soft reference)
  - target
  - rel_type (supports, supported_by, bidirectional)
  - is_deleted, deleted_ymdhis

- doctrine_docs / docs_requires / docs_see / docs_notes
  - file_id (soft reference)
  - doc_type / dependency / related / note
  - is_deleted, deleted_ymdhis

- doctrine_updates
  - file_id (soft reference)
  - agent_name
  - update_number
  - timestamp_ymdhis
  - branch_name (environment context)
  - is_deleted, deleted_ymdhis

Branch information is stored ONLY in the Doctrine DB (e.g., branch_name),
never in the header itself.

## 10. BRANCH BOUNDARY RULE (CRITICAL)
Branch is explicitly excluded from Wolfie Headers.

- Branch is a property of the repository state, not the file.
- Branch changes without file changes.
- Embedding branch in headers would break determinism, caching, and diffs.

v2.9 invariant:
- Headers are branch‑agnostic.
- Branch is tracked in the Doctrine DB (e.g., doctrine_files.branch_name, doctrine_updates.branch_name).
- Header generators and importers may read the current Git branch and store it in the DB, but must NOT write it into headers.

## 11. DOCTRINE INVARIANTS (LEXA ENFORCEMENT)
The following invariants are non‑negotiable:

- NAV must match DB schema (pkg/mod/asp/pur ↔ pkg_name/mod_name/asp_name/pur_name).
- META timestamps must be numeric (YYYYMMDDHHMMSS).
- upd must follow agent#N.
- tax must be wolfie.header.taxonomy@2.3.
- ↔ requires @see with matching targets.
- DOCS must use only @requires, @note, @see.
- No foreign keys in Doctrine DB (soft references only).
- Soft‑delete only (is_deleted, deleted_ymdhis).
- Headers are branch‑agnostic; branch lives only in DB.
- No creative drift in NAV/META fields.

## 12. VERSION HISTORY
- v2.9 (2026‑02‑02):
  DB‑aligned, branch‑aware (DB only), LEXA enforcement, Grok integration. Clarifies header vs. branch vs. DB boundaries.
- v2.8 (2026‑02‑02):
  Identity‑first rewrite. Numeric timestamps. MYTH optional. REL/@see invariant. DB alignment.
- v2.7 (2026‑02‑02):
  Infrastructure doctrine. @see support. Bidirectional edge rules.
- v2.6 (2026‑02‑01):
  Balanced context + efficiency.
- v2.5 and earlier:
  Legacy formats.
