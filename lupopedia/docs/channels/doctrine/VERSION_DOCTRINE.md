---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone @FLEET
  mood_RGB: "00FF00"
  message: "Updated VERSION_DOCTRINE.md to version 4.1.6. Header synchronized with current system version."
tags:
  categories: ["doctrine", "versioning"]
  collections: ["core-docs"]
  channels: ["dev", "doctrine"]
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
file:
  title: "Ecosystem Versioning Doctrine"
  description: "Comprehensive doctrine for version number management across Crafty Syntax, Lupopedia, and WOLFIE Headers"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Ecosystem Versioning Doctrine

## 1. Overview

The Lupopedia ecosystem is composed of three tightly interdependent subsystems:

1. **Crafty Syntax** — the legacy foundation and module 1 of Lupopedia
2. **Lupopedia Core** — the semantic OS, the orchestrator, the living system
3. **WOLFIE Headers** — the per‑file identity, metadata, and historical record

These three systems evolve together, but they do not version the same way.  
This doctrine explains why.

---

## 2. Mental Model

Before diving into the details, here's a quick conceptual anchor:

**The ecosystem version atom is like the OS kernel version.**

- It tells you what the system is **now**
- It is unified, global, and authoritative
- It changes only when the entire ecosystem releases

**The WOLFIE Header version is like a Git commit timestamp baked into each file.**

- It tells you what **era** this file belongs to
- It is per-file, historical, and literal
- It changes only when that specific file is modified

**The ecosystem version tells you what the system is now.**  
**The header version tells you what era this file belongs to.**

This mental model helps you understand why the two systems coexist and why they serve different purposes.

---

## 3. The Ecosystem Version (The Atom)

### What it is

The ecosystem version is the single, unified version number that represents:

- Crafty Syntax
- Lupopedia
- WOLFIE ecosystem
- All modules
- All agents
- All installers
- All migrations

This version is stored in one place:

**Atom:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION`  
**Location:** `config/global_atoms.yaml`

### Why it exists

Because the ecosystem is one organism, not three products.

- Crafty Syntax cannot evolve independently.
- Lupopedia cannot evolve independently.
- WOLFIE cannot evolve independently.

They are version‑locked because:

- They share schema
- They share doctrine
- They share UI
- They share agents
- They share upgrade paths
- They share compatibility guarantees

### Why it is an atom

Because:

- It must never drift
- It must never be duplicated
- It must never be hard‑coded
- It must never be updated in multiple places
- It must be the single source of truth

This is the living version of the ecosystem.

---

## 4. The WOLFIE Header Version (The Fossil Record)

### What it is

Every file in the ecosystem contains a WOLFIE Header with:

```yaml
wolfie.headers.version: 4.0.3
```

This is **not** the system version.  
This is **not** an atom.  
This is **not** updated globally.

It is a historical snapshot of:

> "What version was the ecosystem when this file was last touched?"

### Why it exists

Because you are building a semantic OS, not a code dump.

You need to know:

- Which files were written under old doctrine
- Which files predate breaking changes
- Which files are fossils
- Which files need modernization
- Which files are untouched since 4.0.0
- Which files were updated during 4.1.x
- Which files belong to which era of the system

This is digital archaeology.

### Why it is NOT an atom

Because:

- Atoms represent the current state
- WOLFIE Headers represent the historical state

If WOLFIE Headers used atoms, you would lose:

- drift detection
- file lineage
- compatibility tracing
- debugging context
- upgrade mapping
- doctrine evolution history

The header version must remain a literal string.

---

## 5. Why Only Lupopedia & Crafty Syntax Use Atoms

### Reason 1 — They define the ecosystem

These two systems are the ecosystem.  
They are the "kernel" and "module 1."

Their version must always be:

- unified
- atomic
- authoritative

### Reason 2 — They must always match

You will never have:

- Crafty Syntax 4.0.4
- Lupopedia 4.0.3

That would break:

- installers
- migrations
- module loading
- UI
- agents
- doctrine

### Reason 3 — WOLFIE Headers are not part of the runtime

They are:

- metadata
- identity
- archaeology
- debugging tools

They do not define the ecosystem version.  
They record the file's personal history.

---

## 6. Why This System Is Better Than Traditional Versioning

Most systems:

- rewrite every file on every release
- lose historical context
- hide drift
- hide fossils
- hide outdated code
- hide doctrine evolution

Your system:

- preserves history
- preserves drift
- preserves lineage
- preserves doctrine
- preserves compatibility
- preserves meaning

You can ask questions like:

> "Show me all files untouched since 4.0.0."

And instantly know:

- what's stale
- what's fossilized
- what needs modernization
- what might break under new doctrine

This is impossible in traditional versioning systems.

---

## 7. What Happens When You Move to JetBrains + GitHub (4.1.0)

### ✔ Git will love your system

Git tracks diffs, not meaning.  
Your headers will drift naturally and Git will preserve that history.

### ✔ JetBrains will not auto-update anything

Your doctrine remains intact.

### ✔ Version bumps become tiny commits

Only the atom changes.

### ✔ Header drift becomes visible archaeology

You'll see exactly which files belong to which era.

### ✔ You gain the ability to query the codebase by version

This is a superpower.

---

## 8. What NOT to Do

This doctrine is violated when people treat the systems incorrectly. Here are the forbidden patterns:

### ❌ Never Do This

**❌ Update all WOLFIE Header versions during a version bump**

- Header versions are historical records, not current versions
- Updating them globally destroys the fossil record
- Only update headers when files are actually modified

**❌ Replace literal header versions with atoms**

- `wolfie.headers.version` must remain a literal string
- Using atoms in headers would destroy historical traceability
- Atoms are for runtime code, not file metadata

**❌ Inline the atom value into code**

- Never expand `GLOBAL_CURRENT_LUPOPEDIA_VERSION` into hard-coded strings
- Always reference the atom symbolically
- Inlining breaks the single source of truth

**❌ Use global search‑and‑replace on version numbers**

- This destroys historical records
- This breaks the atom doctrine
- This prevents digital archaeology

**❌ Treat header versions as authoritative system versions**

- Header versions are fossils, not current state
- Always use `GLOBAL_CURRENT_LUPOPEDIA_VERSION` atom for system version checks
- Never read header versions to determine compatibility

### Why These Rules Exist

These violations break the core principles:

- **Breaking the fossil record** → Lose ability to query by era
- **Inlining atoms** → Lose single source of truth
- **Global replacements** → Destroy historical context
- **Using headers as system versions** → Get wrong answers about current state

Follow the doctrine. Preserve the archaeology.

---

## 9. How to Query the Codebase by Version

This is one of the coolest benefits of your system — practical tools for digital archaeology.

### Finding Files Written Before a Specific Version

```bash
# Find all files untouched since 4.0.0
grep -R "wolfie.headers.version: 4.0.0" .

# Find all files from version 4.0.x era
grep -R "wolfie.headers.version: 4.0\." .
```

### Finding Files Written Under a Specific Doctrine

```bash
# Find all files from 4.0.3 era (specific doctrine version)
grep -R "wolfie.headers.version: 4.0.3" .

# Find all files from 4.0.1 era
grep -R "wolfie.headers.version: 4.0.1" .
```

### Finding Fossils (Untouched Since Early Versions)

```bash
# Find files untouched since 4.0.0 (fossils)
grep -R "wolfie.headers.version: 4.0.0" .

# Find files from 4.0.1 era (early but not original)
grep -R "wolfie.headers.version: 4.0.1" .
```

### Advanced Queries

```bash
# Find files updated in recent versions (4.0.3+)
grep -R "wolfie.headers.version: 4.0.3" .

# Combine with other criteria (example: find old PHP files)
grep -R "wolfie.headers.version: 4.0.0" . --include="*.php"

# Find documentation files from specific era
grep -R "wolfie.headers.version: 4.0.1" docs/
```

### Practical Use Cases

**Before a major refactor:**
```bash
# Find all files untouched since 4.0.0 that might need modernization
grep -R "wolfie.headers.version: 4.0.0" .
```

**After a doctrine change:**
```bash
# Find all files from pre-doctrine-change era that might need updates
grep -R "wolfie.headers.version: 4.0.1" .
```

**For compatibility testing:**
```bash
# Identify files that predate breaking changes
grep -R "wolfie.headers.version: 4.0.0" .
```

This turns your doctrine into a practical tool for codebase archaeology and maintenance planning.

---

## 10. Future Versioning (4.1.x and Beyond)

As the ecosystem grows, the versioning system must scale gracefully. Here's how it handles long-term evolution:

### How Major/Minor/Patch Bumps Interact with Header Versions

**Major Bumps (4.0.x → 5.0.0):**
- Atom changes: `GLOBAL_CURRENT_LUPOPEDIA_VERSION` → `"5.0.0"`
- Headers remain unchanged until files are modified
- Files edited after bump get new version in their headers
- Files untouched remain at old version (fossils preserved)

**Minor Bumps (4.0.x → 4.1.0):**
- Atom changes: `"4.0.3"` → `"4.1.0"`
- Headers remain unchanged until files are modified
- New files get current version
- Existing files get updated version only when edited

**Patch Bumps (4.0.3 → 4.0.4):**
- Atom changes: `"4.0.3"` → `"4.0.4"`
- Headers remain unchanged until files are modified
- Most files remain at 4.0.3 (preserving archaeology)
- Only modified files get 4.0.4

### How Doctrine Changes Affect Header Updates

When doctrine changes:

1. **Update the atom** to new version
2. **Update CHANGELOG.md** with doctrine changes
3. **DO NOT update headers globally**
4. **Files edited after doctrine change** get new version
5. **Files untouched** remain at old version (showing they predate the change)

This creates natural documentation of which files are affected by doctrine changes.

### How to Handle Breaking Changes

When introducing breaking changes:

1. **Bump the version** (minor or major, depending on severity)
2. **Document breaking changes** in CHANGELOG.md
3. **Query the codebase** to find files that might be affected:
   ```bash
   # Find files from pre-breaking-change era
   grep -R "wolfie.headers.version: 4.0.2" .
   ```
4. **Update files as needed** (they get new version in headers automatically)
5. **Preserve fossils** (untouched files remain at old version)

### How to Detect Files That Need Migration

```bash
# Find all files from 4.0.0 era (might need migration)
grep -R "wolfie.headers.version: 4.0.0" .

# Find files from specific pre-change version
grep -R "wolfie.headers.version: 4.0.1" .

# Combine with file type filtering
grep -R "wolfie.headers.version: 4.0.0" . --include="*.php"
```

This system scales to any version number and preserves archaeology across major ecosystem changes.

---

## 11. Why This Matters for Semantic OS Design

A semantic OS is not just code — it is a living knowledge organism.  
Versioning must reflect:

- **evolution** — How the system grows over time
- **lineage** — Where components came from
- **meaning** — What each era represents
- **context** — When changes happened
- **history** — What the system once was

### Traditional Versioning Erases History

Most systems:

- Rewrite every file on every release
- Lose historical context
- Hide evolution patterns
- Make archaeology impossible
- Treat code as disposable

### Your System Preserves History

Your versioning system:

- Preserves the fossil record
- Enables digital archaeology
- Shows evolution patterns
- Makes history queryable
- Treats code as a living organism

### The Deeper Purpose

A semantic OS manages knowledge, not just data.  
Knowledge has:

- **Lineage** — Where ideas came from
- **Evolution** — How concepts develop
- **Context** — When understanding changed
- **Meaning** — What each era represents
- **History** — What was believed before

Your versioning system reflects this reality.  
It treats the codebase as a knowledge organism with memory, not a code dump with amnesia.

This is why the system matters.  
This is why the doctrine exists.  
This is how a semantic OS should behave.

---

## 12. Summary

Your ecosystem versioning system is built on two pillars:

### 1. The Atom (the living version)

- Unified
- Global
- Authoritative
- Updated on release
- Defines the ecosystem

### 2. The WOLFIE Header Version (the fossil record)

- Per-file
- Historical
- Literal
- Updated only when the file changes
- Defines the file's lineage

Together, they give you:

- stability
- clarity
- archaeology
- compatibility
- future-proofing
- developer sanity
- upgrade safety

This is not weird.  
This is elegant.  
This is intentional.  
This is how a semantic OS should behave.

---

## 13. Version Bump Workflow (MANDATORY CHECKLIST)

When bumping the ecosystem version, you **MUST** complete all steps in order. Missing any step causes version drift and breaks the single source of truth principle.

### Manual Version Bump Checklist

**Before starting:** Ensure all work for the current version is complete and committed (if using version control).

#### Step 1: Update the Atom (Single Source of Truth)
- [ ] Open `config/global_atoms.yaml`
- [ ] Update `version:` field to new version (e.g., `"4.0.36"`)
- [ ] Update `versions.lupopedia:` to match
- [ ] Update `versions.crafty_syntax:` to match
- [ ] Update `versions.wolfie_headers:` to match
- [ ] Update `versions.schema:` to match
- [ ] Update `last_updated:` to current date (YYYYMMDD format)

#### Step 2: Update Version Constants
- [ ] Open `lupo-includes/version.php`
- [ ] Update `LUPOPEDIA_VERSION` constant to new version
- [ ] Update `LUPOPEDIA_DB_VERSION` constant to match
- [ ] Update `LUPOPEDIA_VERSION_NUM` constant (calculate: MAJOR*10000 + MINOR*100 + PATCH)
- [ ] Update `LUPOPEDIA_VERSION_DATE` constant (YYYYMMDDHHMMSS format)
- [ ] Update `@version` docblock to new version

**Note:** After Phase 2 implementation, `version.php` will load from atom automatically. Until then, manual updates are required.

#### Step 3: Add CHANGELOG Entry
- [ ] Open `CHANGELOG.md`
- [ ] Add new version section at the top (after line 92, before existing version entries)
- [ ] Use format: `## [X.Y.Z] - YYYY-MM-DD`
- [ ] Document all changes in appropriate categories:
  - `### Added` - New features
  - `### Changed` - Changes to existing functionality
  - `### Deprecated` - Soon-to-be removed features
  - `### Removed` - Removed features
  - `### Fixed` - Bug fixes
  - `### Security` - Security fixes
- [ ] Include `### Files Changed` section listing modified files
- [ ] Include `### Next Steps` section for future work

#### Step 4: Update Consolidated Summary
- [ ] Open `CHANGELOG.md`
- [ ] Update `## 📊 Consolidated Summary:` section (around line 50)
- [ ] Update version range in title (e.g., `Version 4.0.19 → 4.0.36`)
- [ ] Update version count in overview text
- [ ] Update `### System State at X.Y.Z` section with new achievements
- [ ] Update `**Next Steps (X.Y.Z+):**` section with current priorities

#### Step 5: Update Dialog File
- [ ] Open `dialogs/changelog_dialog.md`
- [ ] Add new dialog entry for the version bump
- [ ] Include date, version number, and brief description

#### Step 6: Update WOLFIE Headers (Only Modified Files)
- [ ] For each file you modified in this version:
  - [ ] Update `file.last_modified_system_version:` to new version
  - [ ] Update `wolfie.headers.version:` to new version (literal string, not atom)
  - [ ] Update `updated:` date if present
- [ ] **DO NOT** update headers for files you did not modify
- [ ] **DO NOT** perform global search-and-replace on version numbers

#### Step 7: Validation
- [ ] Run validation script: `php scripts/validate_version_consistency.php` (if exists)
- [ ] Verify `config/global_atoms.yaml` version matches `lupo-includes/version.php` constants
- [ ] Verify CHANGELOG.md has entry for new version
- [ ] Verify consolidated summary is updated
- [ ] Verify no version drift between files

### Automated Version Bump (Phase 3)

Once `bin/bump-version.php` is implemented, use:

```bash
php bin/bump-version.php 4.0.36
```

This script will:
1. Validate version format
2. Update atom in `global_atoms.yaml`
3. Update `version.php` (via atom loader)
4. Create CHANGELOG entry template
5. Prompt for summary update
6. Update dialog file
7. Validate all files are in sync

### Common Mistakes to Avoid

❌ **Updating only the atom, forgetting version.php**  
✅ Update both atom and version.php (until Phase 2 complete)

❌ **Updating CHANGELOG but forgetting summary**  
✅ Always update both CHANGELOG entry AND consolidated summary

❌ **Global search-replace on version numbers**  
✅ Only update files you actually modified

❌ **Updating WOLFIE Headers for unmodified files**  
✅ Headers are historical records - only update when file changes

❌ **Bumping version before work is complete**  
✅ Complete all work, then bump version as final step

### Version Bump Timing

**When to bump:**
- After completing a feature or set of changes
- After fixing bugs that warrant a patch release
- After making breaking changes (minor/major bump)
- Before tagging a release

**When NOT to bump:**
- During active development (wait until feature complete)
- For every single commit (batch related changes)
- For experimental work (use dev/pre-release versions)

---

## Related Documentation

- `config/global_atoms.yaml` - Atom definitions
- `docs/doctrine/WOLFIE_HEADER_DOCTRINE.md` - WOLFIE Header specification (canonical doctrine file)
- `CHANGELOG.md` - Version history
- `wolfie_headers.yaml` - WOLFIE Header specification
- `lupo-includes/functions/load_atoms.php` - Atom loader function (Phase 2)
- `bin/bump-version.php` - Automated version bump script (Phase 3)

---

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Last Updated:** 2026-01-15  
**Status:** Published  
**Author:** Captain Wolfie
