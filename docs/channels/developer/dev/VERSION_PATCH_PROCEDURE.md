---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-14
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Version Patch Procedure documentation. This document provides step-by-step instructions for incrementing Lupopedia system version by one patch level, updating all version references, and maintaining documentation consistency."
  mood: "00FF00"
tags:
  categories: ["documentation", "development", "versioning"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Version Patch Procedure"
  description: "Step-by-step procedure for incrementing Lupopedia system version by one patch level. Non-destructive, documentation and metadata updates only."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
sections:
  - "Purpose"
  - "Version Bump"
  - "Update CHANGELOG.md"
  - "Update Documentation"
  - "Update Migration Notes"
  - "Update Architecture Map"
  - "Update UI Component Registry"
  - "Update Inline PHP Docblocks"
  - "Maintain Backward Compatibility"
  - "Header Metadata Consistency"
  - "Final Consistency Check"
  - "Strict Prohibitions"
---

# Version Patch Procedure

**Purpose:**  
Record all changes made in a development thread, update documentation, and bump the Lupopedia system version by one patch level.

This procedure is **non‑destructive**, **non‑refactoring**, and strictly focused on documentation and metadata updates.

---

## 1. Version Bump

Increment the Lupopedia system version by one patch level.

**Example:** `x.x.x` → `x.x.x+1` (e.g., `4.0.12` → `4.0.13`)

Update the version atom everywhere it appears:

### Required Files:

1. **`config/global_atoms.yaml`**
   - Update `versions.lupopedia` field
   - Update `versions.crafty_syntax` field (if applicable)
   - Update `versions.wolfie_headers` field (if applicable)
   - Update `versions.schema` field (if applicable)
   - Update `GLOBAL_CURRENT_LUPOPEDIA_VERSION` atom value
   - Update `version` metadata field
   - Update `last_updated` date field

2. **`lupo-includes/version.php`**
   - Update `@version` docblock tag
   - Update `LUPOPEDIA_VERSION` constant
   - Update `LUPOPEDIA_DB_VERSION` constant
   - Update `LUPOPEDIA_VERSION_NUM` constant (increment by 1)
   - Update `LUPOPEDIA_VERSION_DATE` constant (set to current date/time)

3. **Version Constants in Other Files**
   - Check for any hard-coded version strings in:
     - Module manifests
     - Agent configuration files
     - Component registries
     - API documentation

---

## 2. Update CHANGELOG.md

Add a new section for the new version at the top of the changelog (after the header):

```markdown
## [x.x.x+1] - YYYY-MM-DD

### Added
- [List new features, components, tables, etc.]

### Changed
- [List modifications to existing features]

### Fixed
- [List bug fixes]

### Documentation
- [List documentation updates]
```

**Rules:**
- Summarize **all changes** made in the thread
- Include:
  - Schema changes (if any)
  - New tables (if any)
  - New components (if any)
  - New doctrine rules (if any)
  - Renderer/UI updates (if any)
  - Any refactors explicitly discussed
- **Do not add unrelated changes**
- Update CHANGELOG.md header with new version and `file.last_modified_system_version`

---

## 3. Update Documentation

Update **only** the files affected by this thread or the version bump:

### Documentation Directories to Check:

- `/docs/architecture/*`
- `/docs/schema/*`
- `/docs/migrations/*`
- `/docs/api/*`
- `/docs/ui/*`
- `/docs/cursor-rules.md`
- `/docs/doctrine/*`
- `/docs/dev/*`

**Rules:**
- Update headers of modified documentation files
- Update version references in documentation content
- Update examples that reference version numbers
- **Do not reorganize or rewrite architecture**
- **Do not modify untouched documentation files**

---

## 4. Update Migration Notes

Create or update migration documentation:

**File:** `/docs/migrations/<version>.md`

**Document:**
- New tables (if any)
- New fields (if any)
- New constraints (if any)
- New doctrine rules (if any)
- Any structural changes introduced in this thread
- Migration steps
- Backward compatibility notes
- Rollback procedures (if applicable)

**Template:** Follow the format of existing migration documents (e.g., `docs/migrations/4.0.7.md`)

---

## 5. Update the Architecture Map

Reflect any new:

- Folders
- Components
- Renderers
- Pipelines

introduced in this thread.

**Rules:**
- **Do not reorganize unrelated areas**
- Only document what was actually added or changed
- Maintain existing structure for unchanged areas

---

## 6. Update UI Component Registry

If UI components were created or modified:

- Add new components created in this thread
- Update descriptions for modified components
- Update version references in component metadata

**Rules:**
- Only update components actually touched in this thread
- Preserve existing component documentation

---

## 7. Update Inline PHP Docblocks

Where applicable:

- Add `@version` tags
- Add notes about new behavior
- Reference new doctrine rules
- Update version references in comments

**Rules:**
- **Do not rewrite or simplify existing architecture**
- Only update docblocks for files actually modified
- Preserve existing documentation style

---

## 8. Maintain Backward Compatibility

**Critical Rules:**

- **Do not remove legacy files** (e.g., `/livehelp_js.php`)
- **Preserve all compatibility shims**
- Document any breaking changes (should be rare for patch releases)
- Ensure migration path is clear

---

## 9. Header Metadata Consistency

Ensure all modified files have:

- Updated `file.last_modified_system_version` to new version
- Preserved `wolfie.headers` signature (exact string, never changes)
- Valid Lupopedia header fields (dialog, authorship, sections, etc. where applicable)

**Rules:**
- **Do not modify headers of untouched files**
- Only update `file.last_modified_system_version` for files actually modified
- When creating new files, use current version in `file.last_modified_system_version`
- Preserve exact signature: `wolfie.headers: explicit architecture with structured clarity for every file.`

**Files That Must Have Updated Headers:**
- All files modified in this thread
- `CHANGELOG.md`
- `config/global_atoms.yaml`
- `lupo-includes/version.php`
- New documentation files created
- Migration documents created

---

## 10. Final Consistency Check

Verify:

- ✅ Version bump is consistent across all files
- ✅ Documentation matches the new version
- ✅ No unrelated files were changed
- ✅ No refactors or reorganizations occurred
- ✅ Headers follow new format (if format was updated)
- ✅ Migration document exists (if structural changes were made)
- ✅ CHANGELOG.md entry is complete and accurate

**Checklist:**
1. Search for old version string: `grep -R "x.x.x" .` (replace with actual old version)
2. Verify new version appears in all required locations
3. Verify no unintended changes were made
4. Verify headers are correct format
5. Verify migration document (if needed)

---

## Strict Prohibitions

**DO NOT:**

- ❌ Refactor unrelated code
- ❌ Reorganize files
- ❌ Simplify architecture
- ❌ Introduce new features (unless explicitly part of the thread)
- ❌ Modify untouched headers
- ❌ Remove legacy compatibility code
- ❌ Change file locations or directory structure
- ❌ Update version numbers in files not touched in this thread

**This procedure is documentation and metadata only.**

---

## Usage

To execute this procedure, reference this document:

> "Do the version patch procedure as described in `docs/dev/VERSION_PATCH_PROCEDURE.md`"

The procedure will:
1. Read the current version from `config/global_atoms.yaml`
2. Increment patch level by 1
3. Update all version references
4. Update CHANGELOG.md
5. Update documentation
6. Create migration notes (if needed)
7. Verify consistency

---

## Version Format

Lupopedia uses Semantic Versioning: `MAJOR.MINOR.PATCH`

- **MAJOR**: Breaking changes
- **MINOR**: New features (backward compatible)
- **PATCH**: Bug fixes, documentation, metadata updates

This procedure increments the **PATCH** level only.

---

## Related Documentation

- [Ecosystem Versioning Doctrine](docs/doctrine/VERSION_DOCTRINE.md)
- [WOLFIE Header Doctrine](docs/doctrine/WOLFIE_HEADER_DOCTRINE.md)
- [Universal Wolfie Header Specification](docs/doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md)
- [Lupopedia Header Profile](docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md)

---

**Last Updated:** 2026-01-14  
**Version:** 1.0  
**Status:** Active
