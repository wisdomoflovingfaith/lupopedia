---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Cursor-safe implementation guide for global atoms module in WOLFIE Headers."
tags:
  categories: ["documentation", "specification", "implementation-guide"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "WOLFIE Header Global Atoms — Cursor Implementation Guide"
  description: "Practical guide for Cursor on when and how to use global atoms in WOLFIE Headers"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ⚛️ WOLFIE Header Atoms — Cursor Implementation Guide

## Purpose

This guide provides **Cursor-safe, doctrine-aligned** instructions for using the `header_atoms:` and `file_atoms:` modules in WOLFIE Headers. This system enables symbolic references to metadata at multiple scopes (file, directory, module, global), preventing mass rewrites when values change.

---

## What Are Atoms?

Atoms are **symbolic constants** defined at multiple scopes that represent metadata such as:
- Author names
- Version numbers
- Copyright information
- Project names
- Default values
- Module-specific constants
- Directory-specific constants

**Key Principle:** Atoms are **references**, not literal values. Cursor must preserve the symbolic reference, not expand it to the literal value.

---

## Atom Scopes

Atoms are identified by prefixes that indicate their scope:

| Prefix | Scope | Location | Inheritance |
|--------|-------|----------|-------------|
| `FILE_*` | File-specific | `file_atoms:` block in WOLFIE Header | None (file only) |
| `DIR_*` | Directory-only | `<dir>/_dir_atoms.yaml` | Non-recursive (current directory only) |
| `DIRR_*` | Directory recursive | `<dir>/_dir_atoms.yaml` | Recursive (directory + all descendants) |
| `MODULE_*` | Module-specific | `/modules/<module>/module_atoms.yaml` | Module scope |
| `GLOBAL_*` | Ecosystem-wide | `/config/global_atoms.yaml` | All files |

### Resolution Order (First Match Wins)

When resolving an atom, Cursor MUST check in this order:

1. **FILE_*** — Check `file_atoms:` block in the file's WOLFIE Header
2. **DIR_*** — Check `_dir_atoms.yaml` in the file's own directory
3. **DIRR_*** — Check `_dir_atoms.yaml` in current directory, then walk up parent directories until found
4. **MODULE_*** — Check `module_atoms.yaml` for the current module
5. **GLOBAL_*** — Check `/config/global_atoms.yaml`

**First match wins** — stop searching once an atom is found.

---

## Cursor Rules (MANDATORY)

### ✅ **MUST DO:**

1. **Treat atom names as symbolic references**
   - `CURRENT_AUTHORS` is a reference, not a string to expand
   - Preserve exactly as written: `CURRENT_AUTHORS`

2. **Resolve atom values when reading files**
   - Load `/config/global_atoms.yaml`
   - Resolve atom values for display/processing
   - **Do NOT write resolved values back to file**

3. **Preserve symbolic references when modifying files**
   - If file has `author: CURRENT_AUTHORS`, keep it as `CURRENT_AUTHORS`
   - Do NOT rewrite to `author: "Captain Wolfie"`

4. **Include `header_atoms:` block when atoms are referenced**
   - If file uses `CURRENT_AUTHORS`, include it in `header_atoms:` array

### ❌ **MUST NOT DO:**

1. **Expand atom values into files**
   - ❌ Do NOT rewrite `CURRENT_AUTHORS` → `"Captain Wolfie"`
   - ❌ Do NOT inline atom values

2. **Rewrite atom references to literal values**
   - ❌ Do NOT change `version: CURRENT_VERSION` to `version: "4.0.1"`

3. **Generate or modify `global_atoms.yaml`**
   - ❌ Do NOT create or edit `/config/global_atoms.yaml` unless explicitly instructed
   - This file is managed by Captain Wolfie, not Cursor

4. **Assume atom values**
   - ❌ Do NOT hardcode values that should be atoms
   - Always check if an atom exists before using it

---

## Atom File Locations

### Global Atoms
**File:** `/config/global_atoms.yaml`  
**Path:** `{PROJECT_ROOT}/config/global_atoms.yaml`  
**Scope:** Ecosystem-wide (all files)

### Module Atoms
**File:** `/modules/<module>/module_atoms.yaml`  
**Example:** `modules/craftysyntax/module_atoms.yaml`  
**Scope:** All files within that module

### Directory Atoms
**File:** `<dir>/_dir_atoms.yaml`  
**Example:** `docs/agents/_dir_atoms.yaml`  
**Scope:** 
- `DIR_*` atoms: Current directory only (non-recursive)
- `DIRR_*` atoms: Current directory + all descendant directories (recursive)

### File Atoms
**Location:** `file_atoms:` block inside WOLFIE Header  
**Scope:** Current file only

---

## Format

### In WOLFIE Header:
```yaml
header_atoms:
  - GLOBAL_CURRENT_AUTHORS
  - MODULE_DOCS_VERSION
  - DIRR_DOCS_AUTHOR
  - FILE_CUSTOM_STATUS

file_atoms:
  FILE_CUSTOM_STATUS: "draft"
  
file:
  author: GLOBAL_CURRENT_AUTHORS
  version: MODULE_DOCS_VERSION
  status: FILE_CUSTOM_STATUS
```

### In `_dir_atoms.yaml` (directory-scoped):
```yaml
# Located at: docs/agents/_dir_atoms.yaml
DIR_DOCS_AUTHOR: "Documentation Team"
DIRR_DOCS_VERSION: "4.0.1"
```

### In `module_atoms.yaml` (module-scoped):
```yaml
# Located at: modules/craftysyntax/module_atoms.yaml
MODULE_CRAFTYSYNTAX_VERSION: "4.0.1"
MODULE_CRAFTYSYNTAX_AUTHOR: "Crafty Syntax Team"
```

### In `global_atoms.yaml` (ecosystem-wide):
```yaml
# Located at: /config/global_atoms.yaml
GLOBAL_CURRENT_AUTHORS: "Captain Wolfie"
GLOBAL_CURRENT_VERSION: "4.0.1"
```

### Resolved (for display only, NOT written to file):
```yaml
file:
  author: "Captain Wolfie"  # Resolved from GLOBAL_CURRENT_AUTHORS
  version: "4.0.1"          # Resolved from MODULE_DOCS_VERSION
  status: "draft"           # Resolved from FILE_CUSTOM_STATUS
```

---

## When to Use Atoms (By Scope)

### ✅ **Use FILE_* Atoms For:**
- File-specific overrides
- One-off values unique to a single file
- Temporary or experimental values

### ✅ **Use DIR_* Atoms For:**
- Values specific to files in one directory only
- Directory-level defaults that don't inherit

### ✅ **Use DIRR_* Atoms For:**
- Values that apply to a directory and all its subdirectories
- Inherited defaults (walk up parent directories)

### ✅ **Use MODULE_* Atoms For:**
- Module-specific versions, authors, or metadata
- Values shared across all files in a module

### ✅ **Use GLOBAL_* Atoms For:**
- Ecosystem-wide metadata (authors, versions, copyright)
- Project names and default values
- Any metadata that appears in 3+ files across modules

### ❌ **DON'T Use Atoms For:**
- File-specific content (title, description)
- Values that are truly unique per file

---

## Implementation Example

### Scenario: Updating Author Name

**Before (without atoms):**
- 50 files have `author: "Captain Wolfie"`
- Need to change to `author: "Eric Robin Gerdes"`
- Must edit 50 files manually

**After (with atoms):**
- 50 files have `author: CURRENT_AUTHORS`
- Update `/config/global_atoms.yaml` once:
  ```yaml
  authors:
    primary: "Eric Robin Gerdes"
  ```
- All 50 files automatically resolve to new value
- **No file edits needed**

---

## Reading Files with Atoms

When Cursor reads a file with atoms:

1. **Parse the header**
   ```yaml
   header_atoms:
     - GLOBAL_CURRENT_AUTHORS
     - MODULE_DOCS_VERSION
   file_atoms:
     FILE_CUSTOM_STATUS: "draft"
   file:
     author: GLOBAL_CURRENT_AUTHORS
     version: MODULE_DOCS_VERSION
     status: FILE_CUSTOM_STATUS
   ```

2. **Resolve atoms in order (first match wins):**
   - Check `file_atoms:` block → Found `FILE_CUSTOM_STATUS: "draft"`
   - Check `_dir_atoms.yaml` in file's directory → Check for `DIR_*` and `DIRR_*`
   - Walk up parent directories for `DIRR_*` → Check each `_dir_atoms.yaml` until found
   - Check `module_atoms.yaml` for current module → Found `MODULE_DOCS_VERSION: "4.0.1"`
   - Check `/config/global_atoms.yaml` → Found `GLOBAL_CURRENT_AUTHORS: "Captain Wolfie"`

3. **Resolve atoms (for display/processing)**
   - `GLOBAL_CURRENT_AUTHORS` → `"Captain Wolfie"` (from global)
   - `MODULE_DOCS_VERSION` → `"4.0.1"` (from module)
   - `FILE_CUSTOM_STATUS` → `"draft"` (from file_atoms)
   - Use resolved values for understanding context

4. **Preserve symbolic references (when writing)**
   - Keep `author: GLOBAL_CURRENT_AUTHORS` in file
   - Keep `version: MODULE_DOCS_VERSION` in file
   - Keep `status: FILE_CUSTOM_STATUS` in file
   - Do NOT write literal values

---

## Modifying Files with Atoms

When Cursor modifies a file that uses atoms:

1. **Check if file already uses atoms**
   - Look for `header_atoms:` block
   - Look for atom references in `file:` block

2. **If atoms are used:**
   - ✅ Preserve existing atom references
   - ✅ Keep `header_atoms:` block
   - ❌ Do NOT expand to literal values

3. **If atoms are NOT used but should be:**
   - ✅ Add `header_atoms:` block with referenced atoms
   - ✅ Replace literal values with atom references
   - Example: `author: "Captain Wolfie"` → `author: CURRENT_AUTHORS`

---

## Common Atom Names

### Global Atoms (GLOBAL_*)
Based on `/config/global_atoms.yaml`:
- `GLOBAL_CURRENT_AUTHORS` → Primary author name
- `GLOBAL_CURRENT_VERSION` → Current Lupopedia version
- `GLOBAL_CURRENT_COPYRIGHT` → Copyright string
- `GLOBAL_PROJECT_NAME` → Project name
- `GLOBAL_DEFAULT_STATUS` → Default file status
- `GLOBAL_LUPOPEDIA_COMPANY_STRUCTURE` → Company structure, teams, work rhythm
- `GLOBAL_LUPOPEDIA_V4_0_2_CORE_AGENTS` → v4.0.2 required core agent list

**Reference Syntax in Documentation:**
When referencing global atoms in documentation prose, use the resolver syntax:
- `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name`
- `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.teams.alpha.shift_utc`
- `@GLOBAL.LUPOPEDIA_V4_0_2_CORE_AGENTS.required_agents`

**In WOLFIE Headers:**
Use literal atom names (not resolver syntax):
- `author: GLOBAL_CURRENT_AUTHORS`
- `version: GLOBAL_CURRENT_VERSION`

### Module Atoms (MODULE_*)
Examples:
- `MODULE_CRAFTYSYNTAX_VERSION` → Crafty Syntax module version
- `MODULE_DOCS_AUTHOR` → Documentation module author

### Directory Atoms (DIR_* / DIRR_*)
Examples:
- `DIR_DOCS_AUTHOR` → Directory-specific author (non-recursive)
- `DIRR_DOCS_VERSION` → Directory-recursive version (inherited by descendants)

### File Atoms (FILE_*)
Examples:
- `FILE_CUSTOM_STATUS` → File-specific status override
- `FILE_SPECIAL_TAG` → File-specific tag

**Note:** Check atom files for complete lists of available atoms at each scope.

---

## Error Handling

### Atom Not Found
If an atom is referenced but not found in any scope:
- ✅ Preserve the atom reference in the file
- ✅ Log a warning (do not fail)
- ✅ Continue processing with atom name as-is
- ✅ Check all scopes (FILE → DIR → DIRR → MODULE → GLOBAL) before reporting not found

### Atom File Not Found
If an atom file does not exist:
- ✅ Preserve atom references in files
- ✅ Log a warning for missing file
- ✅ Continue processing (atoms remain as symbolic references)
- ✅ Skip that scope and continue to next scope in resolution order

### Resolution Order Violation
If multiple scopes define the same atom:
- ✅ First match wins (follow resolution order)
- ✅ Do NOT merge or combine values
- ✅ Use the value from the first scope where atom is found

---

## Testing Checklist

Before committing a file with `header_atoms:`:

- [ ] Atom names are uppercase identifiers
- [ ] `header_atoms:` block lists all referenced atoms
- [ ] Atom references preserved (not expanded to literal values)
- [ ] File still valid YAML after modification
- [ ] No hardcoded values that should be atoms

---

## Directory Scoping Examples

### DIR_* (Non-Recursive)
**File:** `docs/agents/_dir_atoms.yaml`
```yaml
DIR_DOCS_AUTHOR: "Documentation Team"
```
- Applies to: Files in `docs/agents/` only
- Does NOT apply to: Files in `docs/agents/subdir/`

### DIRR_* (Recursive)
**File:** `docs/_dir_atoms.yaml`
```yaml
DIRR_DOCS_VERSION: "4.0.1"
```
- Applies to: Files in `docs/` AND all subdirectories
- Resolution: Walk up from file's directory until `_dir_atoms.yaml` is found
- Example: File at `docs/agents/examples/test.md` checks:
  1. `docs/agents/examples/_dir_atoms.yaml` (not found)
  2. `docs/agents/_dir_atoms.yaml` (not found)
  3. `docs/_dir_atoms.yaml` (found `DIRR_DOCS_VERSION`)

## Related Documentation

- **[WOLFIE_HEADER_SPECIFICATION.md](WOLFIE_HEADER_SPECIFICATION.md)** — Complete WOLFIE Header specification
- **[wolfie_headers.yaml](../../wolfie_headers.yaml)** — Machine-readable YAML schema
- **[global_atoms.yaml](../../config/global_atoms.yaml)** — Global atoms definitions
- **[_dir_atoms.yaml.example](_dir_atoms.yaml.example)** — Example directory atoms file
- **[module_atoms.yaml.example](../../modules/craftysyntax/module_atoms.yaml.example)** — Example module atoms file

---

**This guide is Cursor-safe and doctrine-aligned. Follow these rules when implementing the global atoms module.**
