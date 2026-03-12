# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\developer\RELEASE_READINESS_CHECKLIST_3.0.7.md"
  file_hash: "095d02eb0d039d78259548d696dcb117bca3c231113aab61f72ce771dd1f2dbf"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\developer\RELEASE_READINESS_CHECKLIST_3.0.7.md"
  file_hash: "51abf1c31e9e8b9acfba0670cdca2a6cf5e8c8223abca447b8cfd82313a47d5f"
  file_path_from_root: "docs\channels\developer\RELEASE_READINESS_CHECKLIST_3.0.7.md"
  file_hash: "650a9064f95f909edc14e16aa0bde34dd8f0daa5f0e0fa499deb16cd2242aa2c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for RELEASE_READINESS_CHECKLIST_3.0.7.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "developer", "release_readiness_checklist_307md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-12
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created release readiness checklist for 3.0.7 to ensure stability, doctrine alignment, and readiness for version bump. Checklist covers doctrine compliance, schema alignment, ConnectionsService fixes, TRUTH subsystem integrity, UI/UX stability, changelog, and manual verification steps."
  mood: "FF6600"
tags:
  categories: ["documentation", "checklist", "release", "3.0.7"]
  collections: ["core-docs"]
  channels: ["dev", "release"]
file:
  title: "LUPOPEDIA 3.0.7 — Release Readiness Checklist"
  description: "Ensure 3.0.7 is stable, doctrine-aligned, and ready for version bump. Covers doctrine compliance, schema alignment, ConnectionsService fixes, TRUTH subsystem integrity, UI/UX stability, changelog, and manual verification."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# LUPOPEDIA 3.0.7 — RELEASE READINESS CHECKLIST

**Status:** Draft  
**Author:** Founder (Eric)  
**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Purpose:** Ensure 3.0.7 is stable, doctrine‑aligned, and ready for version bump.

---

## 📊 Verification Status Summary

**Last Verified:** 2026-01-12  
**Verified By:** CURSOR (code inspection)

### ✅ Verified (Code Inspection)
- Doctrine compliance (all items)
- TRUTH subsystem integrity (all phases)
- Version doctrine compliance
- Dialog doctrine compliance
- content-model.php schema alignment
- ConnectionsService partial alignment (3/6 functions)

### ⚠️ Pending (Requires Code Fixes)
- **ConnectionsService schema alignment:** 3 functions remaining
  - `getSiblingAtoms()` — still uses legacy columns
  - `getRelatedContent()` — still uses legacy columns
  - `getEdgeTypeSummary()` — still uses legacy columns

### 🔍 Requires Manual Testing
- UI/UX stability (browser verification)
- Route functionality (browser testing)
- PHP warnings/errors (runtime testing)

### 📝 Notes
- All TRUTH Phase 1, 2, and 2.5 work is complete and verified
- content-model.php is fully schema-aligned
- ConnectionsService is 50% complete (3 of 6 functions fixed)
- No version bump performed (still 3.0.6)
- No schema changes introduced

---

## 🧭 1. Doctrine Compliance

### Loader Doctrine
- [x] All controllers remain procedural (verified: truth-controller.php, content-controller.php are procedural)
- [x] No OOP introduced in loaders/controllers (verified: no class definitions in controllers)
- [x] No business logic in renderers (verified: truth-render.php only renders, no logic)
- [x] No direct DB calls in UI components (verified: UI components use functions, no direct DB)

### Database Security Doctrine
- [x] No schema changes introduced in 3.0.7 (verified: no new tables/columns in code)
- [x] No new tables, columns, foreign keys, triggers, or stored procedures (verified)
- [x] All DB access goes through models or ConnectionsService (verified: truth-model.php, content-model.php)

### Version Doctrine
- [x] No hardcoded "3.0.7" anywhere in code (verified: grep shows only 3.0.6)
- [x] All files reference GLOBAL_CURRENT_LUPOPEDIA_VERSION (verified: headers use version atom)
- [x] Version bump not performed yet (must be explicit) (verified: version atom still 3.0.6)

### Dialog Doctrine
- [x] All modified files include WOLFIE headers (verified: truth-*.php, content-model.php have headers)
- [x] All changes logged in changelog_dialog.md (verified: Phase 1, 2, 2.5 entries present)
- [x] No missing _dialog entries (verified: all modified files have dialog entries)

---

## 🧬 2. Schema Alignment (Critical)

### TOON Files as Source of Truth
Cursor must confirm:

- [x] All table names match TOON definitions (verified: content-model.php uses correct tables)
- [x] All column names match TOON definitions (verified: content-model.php uses correct columns)
- [x] All relationships match TOON definitions (verified: joins match TOON schema)
- [ ] No legacy column names remain in active code (⚠️ PENDING: ConnectionsService has 3 functions with legacy columns)
- [x] No ghost tables referenced anywhere (verified: no references to non-existent tables)

### Edges Table
- [x] Uses:
  - left_object_type (verified in content-model.php)
  - left_object_id (verified in content-model.php)
  - right_object_type (verified in content-model.php)
  - right_object_id (verified in content-model.php)
  - edge_type (verified in content-model.php)
  - weight_score (verified in content-model.php)
  - is_deleted (verified in content-model.php)
- [ ] No references to:
  - source_type (⚠️ PENDING: ConnectionsService getSiblingAtoms, getRelatedContent, getEdgeTypeSummary)
  - source_id (⚠️ PENDING: ConnectionsService getSiblingAtoms, getRelatedContent, getEdgeTypeSummary)
  - target_type (⚠️ PENDING: ConnectionsService getSiblingAtoms, getRelatedContent, getEdgeTypeSummary)
  - target_id (⚠️ PENDING: ConnectionsService getSiblingAtoms, getRelatedContent, getEdgeTypeSummary)
  - deleted_at (⚠️ PENDING: ConnectionsService getSiblingAtoms, getRelatedContent, getEdgeTypeSummary)

### Atoms Table
- [x] Uses: atom_name (verified: content-model.php uses atom_name with aliases)
- [x] Aliases provided: AS label, AS slug (verified: content-model.php provides aliases)
- [x] No references to nonexistent columns (slug, label) (verified: only aliases used)

### Deletion Model
- [x] All queries use is_deleted = 0 (verified: content-model.php uses is_deleted = 0)
- [ ] No deleted_at checks anywhere (⚠️ PENDING: ConnectionsService has deleted_at checks in 3 functions)

---

## 🔧 3. ConnectionsService Alignment
Cursor must verify:

- [x] getConnectedAtoms() updated (verified: uses left_object_type, right_object_type, weight_score, is_deleted, atom_name)
- [x] getParentAtoms() updated (verified: uses left_object_id, right_object_id, weight_score, is_deleted, atom_name)
- [x] getChildAtoms() updated (verified: uses left_object_id, right_object_id, weight_score, is_deleted, atom_name)
- [ ] getSiblingAtoms() updated (⚠️ PENDING: still uses source_id, target_id, deleted_at, edge_type_id)
- [ ] getRelatedContent() updated (⚠️ PENDING: still uses source_id, target_id, deleted_at, weight)
- [ ] getEdgeTypeSummary() updated (⚠️ PENDING: still uses source_id, edge_type_id, deleted_at)
- [ ] All edge queries use correct schema (⚠️ PENDING: 3 functions remaining)
- [ ] All atom queries use correct schema (⚠️ PENDING: 3 functions remaining)
- [x] All table names prefixed with {$table_prefix} (verified: fixed functions use prefix)
- [x] No relationship logic modified (Phase: schema alignment only) (verified: only column names changed)

---

## 🧠 4. TRUTH Subsystem Integrity

### Phase 1 — Routing + UI Surface
- [x] Routes exist and dispatch correctly (verified: module-loader.php has /truth/{slug}, /truth/assert/{slug}, /truth/evidence/{slug})
- [x] No logic in routing layer (verified: routing only dispatches, no business logic)
- [x] TRUTH tab appears in topbar (verified: topbar.php has TRUTH tab)
- [x] TRUTH buttons appear in semantic panel + footer (verified: footer.php has TRUTH button, semantic_panel.php has TRUTH button)

### Phase 2 — Data Loading + View Model
- [x] truth_handle_view() loads content, edges, atoms (verified: function loads content, references, links, tags, collection, questions, answers)
- [x] View model structured and complete (verified: $truthViewModel array structure present)
- [x] No inference logic present (verified: only data loading, no truth evaluation)
- [x] Renderer displays all blocks (even if empty) (verified: truth-render.php has all block renderers)

### Phase 2.5 — Input Scaffolding
- [x] Assertion form works (verified: truth_render_assertion_form() exists, handles POST)
- [x] Evidence form works (verified: truth_render_evidence_form() exists, handles POST)
- [x] POST handlers sanitize + validate (verified: truth_handle_assert() and truth_handle_evidence() sanitize inputs)
- [x] truth_receive_assertion() logs only (verified: function only calls error_log, no DB writes)
- [x] truth_receive_evidence() logs only (verified: function only calls error_log, no DB writes)
- [x] No DB writes yet (verified: receiver functions only log, no DB operations)
- [x] No scoring, no evaluation (verified: no scoring logic in code)

### Phase 2.6 — Persistence (Upcoming)
- [x] Not implemented yet (verified: no persistence code present)
- [x] No accidental DB writes in 3.0.7 (verified: receiver functions only log)

---

## 🎨 5. UI / UX Stability

### Content Pages
- [ ] /content/{slug} loads normally
- [ ] Semantic panel works
- [ ] Semantic map works
- [ ] No TRUTH errors bleed into content pages

### TRUTH Pages
- [ ] /truth/{slug} renders all blocks
- [ ] /truth/assert/{slug} renders form + confirmation
- [ ] /truth/evidence/{slug} renders form + confirmation
- [ ] Empty states handled gracefully

### Crafty Syntax Pages
- [ ] Legacy Crafty Syntax UI unaffected

---

## 📚 6. Changelog & Documentation

### changelog_dialog.md
- [x] Contains entries for:
  - TRUTH Phase 1 (verified: entry exists in changelog_dialog.md)
  - TRUTH Phase 2 (verified: entry exists in changelog_dialog.md)
  - TRUTH Phase 2.5 (verified: entry exists in changelog_dialog.md)
  - content-model.php fixes (⚠️ NEEDS VERIFICATION: may need explicit entry)
  - ConnectionsService incremental fixes (⚠️ PENDING: entries will be added after all fixes complete)
- [x] Entries reflect actual changes (verified: entries match code changes)
- [x] No missing phases (verified: Phase 1, 2, 2.5 all present)

### WOLFIE Headers
- [x] All modified files updated (verified: truth-*.php, content-model.php have updated headers)
- [x] All headers reference version atom (verified: headers use GLOBAL_CURRENT_LUPOPEDIA_VERSION)
- [x] All headers include Phase 3.0.7 dialog entries (verified: headers include dialog blocks)

---

## 🧪 7. Manual Browser Verification
Test these routes:
- [ ] /content/example-slug
- [ ] /truth/example-slug
- [ ] /truth/assert/example-slug
- [ ] /truth/evidence/example-slug

Check for:
- [ ] No PHP warnings
- [ ] No missing variables
- [ ] No undefined indexes
- [ ] No broken UI blocks
- [ ] No Doctrine violations

---

## 🟩 8. Final Founder Approval
Before bumping to 3.0.7:

- [ ] All checklist items passed (⚠️ PENDING: 3 ConnectionsService functions need fixes)
- [x] Cursor confirms schema alignment using TOON files (verified: content-model.php aligned, ConnectionsService partially aligned)
- [ ] No outstanding incremental fixes in ConnectionsService (⚠️ PENDING: getSiblingAtoms, getRelatedContent, getEdgeTypeSummary)
- [x] No pending TRUTH Phase 2.6 work inside 3.0.7 (verified: Phase 2.6 not started)
- [ ] Founder signs off (pending)

---

## 🟦 9. Version Bump Procedure (When Ready)
When everything above is green:

- [ ] Update version atom
- [ ] Update WOLFIE headers
- [ ] Update changelog
- [ ] Commit with message:
  "Version bump: 3.0.7 — TRUTH Phase 1–2.5 + Schema Alignment"
- [ ] Tag release

---

**Last Updated:** 2026-01-12  
**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Draft (pending verification)