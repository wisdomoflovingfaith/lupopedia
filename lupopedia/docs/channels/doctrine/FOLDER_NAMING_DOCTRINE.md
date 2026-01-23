---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLFIE
  target: @everyone
  message: "Mandatory folder naming doctrine. All folders MUST use lowercase only (a-z, 0-9, hyphen, underscore). No uppercase, no dots, no special characters, no hidden folders."
  mood: "FF0000"
tags:
  categories: ["doctrine", "standards", "file-system"]
  collections: ["core-docs", "standards"]
  channels: ["public", "dev", "internal"]
file:
  title: "Folder Naming Doctrine"
  description: "Mandatory rules for folder naming in Lupopedia. All folders must be lowercase with restricted character set."
  version: "4.0.2"
  status: published
  author: "WOLFIE"
---

# Folder Naming Doctrine

**⚠️ MANDATORY (NON-NEGOTIABLE)**  
**Version 4.0.2**  
**Effective Date: 2025-01-06**

## Overview

All folder names in Lupopedia MUST follow strict naming rules to ensure cross-platform compatibility, clarity, and consistency. This doctrine applies to **ALL** folders in the Lupopedia codebase.

## Mandatory Rules

### 1. Lowercase Only

**ALL folder names MUST be lowercase.**

- ✅ **Allowed:** `docs/`, `core/`, `doctrine/`, `lupo-agents/`, `database/`
- ❌ **FORBIDDEN:** `ARCHITECTURE/`, `Core/`, `Doctrine/`, `Lupo-Agents/`, `DataBase/`

**Rationale:** Case-sensitivity differences between operating systems (Windows is case-insensitive, Linux/Mac are case-sensitive) cause collisions and portability issues. Lowercase ensures consistency everywhere.

### 2. Allowed Characters

**Folder names may ONLY contain:**
- **Lowercase letters:** `a-z`
- **Digits:** `0-9`
- **Hyphen:** `-` (dash, minus sign)
- **Underscore:** `_` (underscore)

**Character Set:** `[a-z0-9-_]+`

**Examples:**
- ✅ `lupo-agents/`
- ✅ `database/`
- ✅ `docs/doctrine/`
- ✅ `agent_registry/`
- ✅ `lupo-123/`

**Rationale:** Restricted character set ensures maximum cross-platform compatibility and avoids shell interpretation issues.

### 3. Forbidden Characters

**Folder names MUST NOT contain:**
- ❌ **Uppercase letters:** `A-Z` (FORBIDDEN)
- ❌ **Dots/periods:** `.` (FORBIDDEN - no `.env`, `.git`, `.cursor`, etc.)
- ❌ **Ampersands:** `&` (FORBIDDEN)
- ❌ **Spaces:** ` ` (FORBIDDEN)
- ❌ **Special characters:** `!@#$%^*()+={}[]|;:'"<>?/` (FORBIDDEN)
- ❌ **Leading/trailing hyphens or underscores:** `-folder/`, `_folder/`, `folder-/` (FORBIDDEN)

**Examples of FORBIDDEN folder names:**
- ❌ `ARCHITECTURE/` (uppercase)
- ❌ `.git/` (leading dot - hidden folder)
- ❌ `.env/` (leading dot - hidden folder)
- ❌ `Lupo-Agents/` (uppercase)
- ❌ `lupo agents/` (space)
- ❌ `lupo&agents/` (ampersand)
- ❌ `lupo.agents/` (dot)
- ❌ `lupo_agents/` (acceptable, but prefer hyphen)
- ❌ `-lupo-agents/` (leading hyphen)
- ❌ `lupo-agents-/` (trailing hyphen)

### 4. No Hidden Folders

**Hidden folders (starting with `.`) are FORBIDDEN.**

- ❌ `.git/` (FORBIDDEN)
- ❌ `.env/` (FORBIDDEN)
- ❌ `.cursor/` (FORBIDDEN)
- ❌ `.vscode/` (FORBIDDEN)
- ❌ `.idea/` (FORBIDDEN)

**Rationale:** Hidden folders cause issues with:
- Cross-platform compatibility
- Version control exclusions
- Deployment systems
- File synchronization
- Documentation visibility

**If you need IDE-specific configuration, use:**
- `ide-config/` (with subfolders for each IDE)
- `config/cursor/`
- `config/vscode/`
- Explicit non-hidden folders that are clear and visible

### 5. No Dots in Folder Names

**Folder names MUST NOT contain dots (periods).**

- ❌ `module.v2/` (FORBIDDEN)
- ❌ `lupo.agents/` (FORBIDDEN)
- ❌ `test.123/` (FORBIDDEN)

**Rationale:** Dots have special meaning in file systems and can cause confusion with file extensions. Use hyphens instead.

**Alternative:**
- ✅ `module-v2/`
- ✅ `lupo-agents/`
- ✅ `test-123/`

### 6. Preferred Separator: Hyphen

**Prefer hyphens (`-`) over underscores (`_`) for word separation.**

- ✅ **Preferred:** `lupo-agents/`, `database-migrations/`, `docs-core/`
- ⚠️ **Acceptable but not preferred:** `lupo_agents/`, `database_migrations/`, `docs_core/`

**Rationale:** Hyphens are more readable in URLs and documentation links. Underscores can be hidden by underlines in rendered text.

### 7. No Spaces, Ever

**Spaces in folder names are ABSOLUTELY FORBIDDEN.**

- ❌ `lupo agents/` (FORBIDDEN)
- ❌ `database migrations/` (FORBIDDEN)
- ❌ `my folder/` (FORBIDDEN)

**Rationale:** Spaces require quoting in shell commands and cause issues in URLs, scripts, and many tools.

**Alternative:**
- ✅ `lupo-agents/`
- ✅ `database-migrations/`
- ✅ `my-folder/`

---

## Current Folder Structure (Corrected)

### Root Directories
- ✅ `docs/` (lowercase)
- ✅ `core/` (if it exists at root, should be lowercase)
- ✅ `database/` (lowercase)
- ✅ `lupo-agents/` (lowercase with hyphen)
- ✅ `lupo-includes/` (lowercase with hyphen)
- ✅ `lupo-content/` (lowercase with hyphen)
- ✅ `legacy/` (lowercase)
- ✅ `modules/` (lowercase)
- ✅ `api/` (lowercase)
- ✅ `config/` (lowercase)
- ✅ `dialog/` (lowercase)

### Documentation Directories
- ✅ `docs/agents/` (lowercase)
- ✅ `docs/core/` (lowercase)
- ✅ `docs/doctrine/` (lowercase)
- ✅ `docs/schema/` (lowercase)
- ✅ `docs/dev/` (lowercase)
- ✅ `docs/modules/` (lowercase)
- ✅ `docs/protocols/` (lowercase)
- ✅ `docs/history/` (lowercase)
- ✅ `docs/appendix/` (lowercase)
- ✅ `docs/tools/` (lowercase)
- ❌ `docs/ARCHITECTURE/` (MUST BE MOVED TO `docs/architecture/`)

---

## Enforcement Rules

### For AI Assistants (Cursor, Claude, etc.)

**BEFORE creating or renaming any folder:**
1. ✅ Convert all uppercase letters to lowercase
2. ✅ Replace any dots with hyphens (except file extensions)
3. ✅ Remove any leading/trailing hyphens or underscores
4. ✅ Remove any spaces (replace with hyphens)
5. ✅ Remove any forbidden special characters
6. ✅ Verify folder name matches pattern: `[a-z0-9-_]+`

**NEVER:**
- ❌ Create folders with uppercase letters
- ❌ Create hidden folders (starting with `.`)
- ❌ Use dots in folder names
- ❌ Use spaces in folder names
- ❌ Use ampersands or other special characters
- ❌ Skip validation before folder creation

**ALWAYS:**
- ✅ Use lowercase only
- ✅ Use hyphens for word separation
- ✅ Validate folder name before creation
- ✅ Update documentation references when renaming folders
- ✅ Check for existing uppercase folders that need correction

### Validation Pattern

**Regex Pattern:** `^[a-z][a-z0-9-_]*[a-z0-9]$|^[a-z0-9]$`

**Explanation:**
- Starts with lowercase letter or digit
- Contains only lowercase letters, digits, hyphens, underscores
- Ends with lowercase letter or digit (not hyphen/underscore)
- Single-character folders must be lowercase letter or digit

**Examples:**
- ✅ `docs` - matches
- ✅ `lupo-agents` - matches
- ✅ `database-migrations` - matches
- ✅ `agent-123` - matches
- ✅ `a` - matches (single char)
- ❌ `ARCHITECTURE` - no match (uppercase)
- ❌ `.git` - no match (leading dot)
- ❌ `lupo.agents` - no match (dot)
- ❌ `lupo agents` - no match (space)
- ❌ `-lupo` - no match (leading hyphen)

---

## Migration Required

### Folders That Must Be Moved (Uppercase → Lowercase)

**Current uppercase folders:**
1. `docs/ARCHITECTURE/` → `docs/architecture/`

**Action Required:**
- Move the folder physically
- Update all documentation references
- Update all code references
- Verify no broken links

### Validation Script

**Check for uppercase folders:**
```powershell
# Check for uppercase folders in docs/
Get-ChildItem -Path "docs" -Directory | Where-Object { $_.Name -cmatch '[A-Z]' }
```

**Check for forbidden characters:**
```powershell
# Check for dots, spaces, special chars
Get-ChildItem -Path "." -Directory -Recurse | Where-Object { 
    $_.Name -match '[^a-z0-9-_]' -or $_.Name -match '^[-_]' -or $_.Name -match '[-_]$'
}
```

---

## Examples

### ✅ Good Folder Names

```
docs/
docs/core/
docs/doctrine/
docs/agents/
docs/schema/
lupo-agents/
database/
database/migrations/
database/toon-data/
lupo-includes/
modules/
api/
config/
```

### ❌ Bad Folder Names (FORBIDDEN)

```
ARCHITECTURE/          ❌ Uppercase
Architecture/          ❌ Mixed case
Core/                  ❌ Uppercase
Doctrine/              ❌ Uppercase
Lupo-Agents/           ❌ Uppercase
lupo.agents/           ❌ Dot in name
lupo agents/           ❌ Space in name
lupo&agents/           ❌ Ampersand
.git/                  ❌ Hidden folder (leading dot)
.env/                  ❌ Hidden folder (leading dot)
-lupo-agents/          ❌ Leading hyphen
lupo-agents-/          ❌ Trailing hyphen
```

---

## Rationale

### Why Lowercase Only?

1. **Cross-Platform Compatibility**
   - Windows is case-insensitive: `ARCHITECTURE/` and `architecture/` refer to the same folder
   - Linux/Mac are case-sensitive: `ARCHITECTURE/` and `architecture/` are different folders
   - Lowercase ensures identical behavior everywhere

2. **URL Compatibility**
   - Web URLs are case-sensitive in practice
   - Lowercase folders work consistently in URLs
   - Avoids broken links from case mismatches

3. **Shell Script Compatibility**
   - Shell scripts behave differently with case-sensitive vs case-insensitive filesystems
   - Lowercase avoids script failures
   - Quoting requirements reduced

4. **Clarity & Consistency**
   - Single naming convention reduces cognitive load
   - No ambiguity about correct case
   - Easier to remember and type

### Why No Dots?

1. **File Extension Confusion**
   - Dots indicate file extensions: `.php`, `.md`, `.json`
   - Folder names with dots look like files
   - Creates ambiguity in commands and documentation

2. **Hidden File Confusion**
   - Leading dots create hidden files/folders
   - Hidden folders are hard to discover and document
   - Version control issues with hidden folders

3. **Shell Interpretation**
   - Dots have special meaning in regex patterns
   - Requires escaping in some contexts
   - Hyphens are unambiguous

### Why No Spaces?

1. **Shell Quoting Required**
   - Spaces require quotes: `cd "lupo agents/"` vs `cd lupo-agents/`
   - Easy to forget quotes, causing errors
   - Breaks many scripts and tools

2. **URL Encoding**
   - Spaces become `%20` in URLs
   - Creates ugly, unreadable URLs
   - Hyphens are cleaner: `lupo-agents/` not `lupo%20agents/`

3. **Cross-Platform Issues**
   - Some tools handle spaces inconsistently
   - Path parsing can break with spaces
   - Hyphens avoid all these issues

### Why Hyphens Over Underscores?

1. **Readability**
   - Hyphens are more visible than underscores
   - Underscores can be hidden by underlines in rendered text
   - Hyphens clearly separate words

2. **URL Best Practice**
   - Search engines prefer hyphens in URLs
   - Hyphens are treated as word separators
   - Better for SEO and readability

3. **Consistency**
   - Most modern projects use hyphens
   - Aligns with web standards
   - More familiar to new developers

---

## Implementation

### Immediate Actions Required

1. **Move uppercase folders:**
   - `docs/ARCHITECTURE/` → `docs/architecture/`

2. **Update documentation references:**
   - Search all `.md` files for uppercase folder references
   - Replace with lowercase equivalents
   - Update `docs/README.md` with corrected paths

3. **Update code references:**
   - Search all PHP files for uppercase folder references
   - Replace with lowercase equivalents
   - Update include/require paths

4. **Create validation scripts:**
   - PowerShell script to check for uppercase folders
   - Validation in deployment process
   - Pre-commit checks (if version control is used)

### Long-Term Enforcement

1. **Documentation Updates:**
   - All new documentation must use lowercase folder references
   - Code examples must use lowercase folders
   - Architecture diagrams must use lowercase labels

2. **Automated Validation:**
   - Script to validate all folders on creation
   - CI/CD checks (if implemented)
   - Pre-deployment validation

3. **Onboarding:**
   - New developers must read this doctrine
   - Folder naming rules part of contributor training
   - Regular audits of folder structure

---

## For AI Assistants

### Before Creating Any Folder

**Checklist:**
1. ✅ Is the name all lowercase? (`a-z` only)
2. ✅ Does it contain only allowed characters? (`a-z`, `0-9`, `-`, `_`)
3. ✅ Does it start with a letter or digit? (not `-` or `_`)
4. ✅ Does it end with a letter or digit? (not `-` or `_`)
5. ✅ Does it avoid dots, spaces, and special characters?
6. ✅ Is it not a hidden folder? (doesn't start with `.`)

**If any answer is NO, fix it before creating the folder.**

### When Renaming Folders

1. **Identify uppercase folders:**
   - Check current folder name
   - Convert to lowercase
   - Replace dots with hyphens
   - Remove spaces

2. **Update references:**
   - Search codebase for old folder name
   - Update all file paths
   - Update all documentation links
   - Update all code references

3. **Verify:**
   - Run validation script
   - Check for broken links
   - Test file access

---

## Document History

- **2025-01-06**: Created folder naming doctrine (v4.0.2)
- **2025-01-06**: Documented migration from uppercase `ARCHITECTURE/` to lowercase `architecture/`

---

**END OF DOCTRINE DOCUMENT**
