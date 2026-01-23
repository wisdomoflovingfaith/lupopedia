---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - GLOBAL_DOCTRINE_NO_FOREIGN_KEYS
  - GLOBAL_DOCTRINE_NO_TRIGGERS
  - GLOBAL_DOCTRINE_NO_STORED_PROCEDURES
  - GLOBAL_DATABASE_STORAGE_NOT_COMPUTATION
  - GLOBAL_COLLECTION_CORE_DOCS
  - GLOBAL_COLLECTION_DOCTRINE
  - GLOBAL_CHANNEL_DEV
  - GLOBAL_CHANNEL_PUBLIC
updated: 2026-01-15
author: GLOBAL_CURRENT_AUTHORS
architect: Captain Wolfie
dialog:
  speaker: KIRO
  target: @everyone
  message: "Updated ATOMIZATION_DOCTRINE.md with Atom Exception List v1.0. Added comprehensive rules for values that must NEVER be atomized, including WOLFIE header fields, disambiguation notices, canonical terminology, boilerplate phrases, ritualized repetition, doctrine file names, code keywords, and semantic categories. Exception list takes precedence over atomization rules to preserve semantic meaning and structural clarity."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "architecture", "mandatory"]
  collections: [GLOBAL_COLLECTION_CORE_DOCS, GLOBAL_COLLECTION_DOCTRINE]
  channels: [GLOBAL_CHANNEL_DEV, GLOBAL_CHANNEL_PUBLIC]
in_this_file_we_have:
  - Atomization Doctrine (MANDATORY)
  - Rule 1: Automatic Identification of Repeated Values
  - Rule 2: Never Inline Atom Values
  - Rule 3: Never Rewrite Files When Atoms Change
  - Rule 4: Preserve Atom Names Exactly
  - Rule 5: Convert Repeated Patterns into Atoms
  - Atom Categories (Versions, Authors, Doctrine Phrases, Structural Values, Header Fields)
  - Enforcement Rules for IDEs
  - Examples of Atomization
  - ATOM EXCEPTION LIST (DO NOT ATOMIZE)
  - Canonical Atom Exception List v1.0
  - Exception List Enforcement
  - Integration with Atomization Rules
  - Summary Doctrine
  - Integration with Other Doctrines
  - Enforcement Checklist
file:
  title: "Atomization Doctrine (MANDATORY)"
  description: "Any value repeated across multiple documentation files MUST become a global atom, EXCEPT for values on the exception list. Includes comprehensive Atom Exception List v1.0 defining values that must never be atomized to preserve semantic meaning and structural clarity."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🎯 **ATOMIZATION DOCTRINE (MANDATORY)**  
### *Stop Repeating Values in Documentation*  
### *Single Source of Truth for All Repeated Metadata*
### *This Doctrine Is Absolute and Non-Negotiable*

---

## ⚠️ **MANDATORY RULE: ATOMIZE ALL REPEATED VALUES**

**ALL IDEs AND AI AGENTS MUST FOLLOW THIS DOCTRINE ABSOLUTELY.**

Lupopedia **NEVER** hardcodes values that appear in multiple files.  
All repeated values **MUST** become global atoms.  
This is not optional.  
This is not a suggestion.  
This is a **mandatory architectural requirement** to prevent token burn and ensure consistency.

---

## **1. Any Value Repeated Across Multiple Files MUST Become a Global Atom**

### **Cursor Must Automatically Identify Repeated Values**

Cursor must scan documentation files and automatically identify repeated literal values such as:

#### **Version Information**
- Version numbers: `"4.0.2"`, `"4.0.1"`, `"3.7.5"`
- Semantic OS version numbers
- Schema version numbers
- WOLFIE Headers version numbers
- Crafty Syntax version numbers
- Agent registry version numbers

#### **Author Information**
- Author names: `"Captain Wolfie"`, `"Eric Robin Gerdes"`
- Legal names
- Email addresses
- Copyright strings: `"© 2025-2026 Eric Robin Gerdes. All rights reserved."`

#### **Project Information**
- Project names: `"Lupopedia"`, `"Lupopedia Semantic Operating System"`
- Project descriptions
- License text: `"Proprietary. All rights reserved."`
- Repository URLs
- Website URLs

#### **Company Information**
- Company names: `"Lupopedia LLC"`, `"Crafty Syntax"`
- Company formation dates: `"2025-11-06"`
- DBA names
- Company status: `"active"`
- State registrations: `"South Dakota"`

#### **Agent Information**
- Agent counts: `"27 core agents"`, `"101 agents"`, `"101 total defined"`
- Agent registry totals
- Core agent lists
- Agent classification names

#### **Team Information**
- Team names: `"Alpha"`, `"Bravo"`, `"Charlie"`, `"Delta"`
- Shift hours: `"09:00-15:00"`, `"12:00-18:00"`, `"15:00-21:00"`
- Team rules and combinations
- Work rhythm patterns: `"Focused Build"`, `"Recreational Programming Zone"`

#### **Status and Metadata Values**
- Status values: `"published"`, `"draft"`, `"archived"`
- Collection names: `"core-docs"`, `"doctrine"`, `"agents"`
- Channel names: `"dev"`, `"public"`, `"internal"`
- Category names

#### **Doctrine Constants**
- Doctrine phrases: GLOBAL_DOCTRINE_NO_FOREIGN_KEYS, GLOBAL_DOCTRINE_NO_TRIGGERS, GLOBAL_DOCTRINE_NO_STORED_PROCEDURES
- Architecture principles: `"Explicit UTC BIGINT timestamps"`, GLOBAL_DATABASE_STORAGE_NOT_COMPUTATION
- Design philosophy: `"Repairability over constraints"`, `"Federation-safe schema"`

#### **Technical Constants**
- Timestamp formats: `"YYYYMMDDHHIISS"`, `"UTC"`
- Node defaults: `node_id: 1`, `node_name: "local"`
- Schema constants
- Database formats

### **When Cursor Detects a Repeated Value**

If Cursor sees the same literal value in **more than one file**, it must:

1. **✔ Replace it with a symbolic atom reference**
   - Example: `version: "4.0.2"` → `version: GLOBAL_CURRENT_LUPOPEDIA_VERSION`

2. **✔ Add the atom to `global_atoms.yaml`** (ONLY when explicitly instructed)
   - Do NOT automatically add atoms without user approval
   - Do NOT modify `global_atoms.yaml` unless explicitly instructed
   - Suggest the atom addition to the user first

3. **✔ Preserve the symbolic reference forever**
   - Never revert to hardcoded values
   - Never expand the atom to its value
   - Keep the symbolic reference as the canonical form

### **Detection Threshold**

**Threshold: 2+ occurrences = MUST atomize**

- If a value appears in **2 or more documentation files**, it **MUST** be atomized
- If a value appears **only once**, atomization is optional (but recommended if it might be reused)
- If a value appears in **code files only** (not documentation), atomization is optional

---

## **2. Cursor MUST NOT Inline Atom Values**

### **Forbidden: Expanding Atom References**

If a file contains:

```yaml
version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
```

Cursor must **NEVER** rewrite it to:

```yaml
version: "4.0.2"
```

This is **FORBIDDEN**.

### **Why This Is Forbidden**

- **Token waste:** Expanding atoms burns tokens and makes files longer
- **Maintenance burden:** Inlined values must be updated manually across all files
- **Consistency risk:** Different files might have different values after manual updates
- **Single source of truth:** Atom references maintain one source of truth
- **Automatic updates:** Changing the atom value updates all references automatically

### **Correct Behavior**

Cursor must:
- **Preserve atom references** exactly as written
- **Resolve atoms only when reading** (for processing, not for storage)
- **Keep symbolic references** in the file content
- **Never substitute** atom values into file content

---

## **3. Cursor MUST NOT Rewrite Files When Atom Values Change**

### **Forbidden: Mass File Updates on Atom Changes**

When you bump:

```yaml
GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.0.2" → "4.0.3"
```

Cursor must:

1. **✔ Update only the atoms file** (`config/global_atoms.yaml`)
2. **✔ Update nothing else**
3. **✔ Preserve all symbolic references exactly**

Cursor must **NEVER**:
- ❌ Search and replace version numbers across all files
- ❌ Rewrite documentation files with new values
- ❌ Update hardcoded references (they shouldn't exist)
- ❌ Regenerate files with expanded atom values

### **Why This Is Critical**

**This prevents 10,000-line token burn.**

When you update a global atom:
- **One file changes:** `config/global_atoms.yaml` (single line)
- **Zero files need rewriting:** All documentation already uses atom references
- **Automatic resolution:** All tools resolve atoms when reading, getting the new value
- **Zero token waste:** No mass file edits, no search/replace, no regeneration

### **Correct Workflow**

1. **Update atom value** in `config/global_atoms.yaml`
2. **That's it.**
3. All files using the atom automatically reference the new value when resolved

---

## **4. Cursor MUST Treat Atom Names as Symbolic Constants**

### **Forbidden: Modifying Atom Names**

Atom names must be **preserved exactly**:

- ❌ **No renaming:** `GLOBAL_CURRENT_YEAR` → `GLOBAL_YEAR` (forbidden)
- ❌ **No reformatting:** `GLOBAL_CURRENT_YEAR` → `global_current_year` (forbidden)
- ❌ **No expansion:** `GLOBAL_CURRENT_YEAR` → `"2026"` (forbidden)
- ❌ **No substitution:** `GLOBAL_CURRENT_YEAR` → `@GLOBAL.CURRENT_YEAR` (if using atom format, not resolver format)
- ❌ **No "helpful" rewriting:** Never "improve" atom names

### **Example: Exact Preservation Required**

```yaml
GLOBAL_CURRENT_YEAR
```

must remain exactly that in:
- WOLFIE Headers
- Documentation files
- Configuration files
- Code comments (if atom is referenced)

### **Allowed: Adding Atoms to `header_atoms:` Block**

When using an atom, Cursor must add it to the `header_atoms:` block:

```yaml
---
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - GLOBAL_CURRENT_YEAR
file:
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  author: GLOBAL_CURRENT_AUTHORS
---
```

This is **required** to declare which atoms are used in the file.

---

## **5. Cursor MUST Convert Repeated Documentation Patterns into Atoms**

### **Atom Categories: What Must Be Atomized**

Cursor must atomize all repeated patterns across these categories:

#### **A. Repeated Metadata**

- `"Lupopedia Semantic Operating System"`
- `"Captain Wolfie"`
- `"© 2025–2026 Eric Robin Gerdes. All rights reserved."`
- `"published"`
- `"core-docs"`
- `"dev"`
- `"Lupopedia LLC"`
- `"Crafty Syntax"`
- `"4.0.2"`
- `"101 agents"`
- `"27 core agents"`

#### **B. Repeated Doctrine Phrases**

- GLOBAL_DOCTRINE_NO_FOREIGN_KEYS
- GLOBAL_DOCTRINE_NO_TRIGGERS
- GLOBAL_DOCTRINE_NO_STORED_PROCEDURES
- `"Explicit UTC BIGINT timestamps"`
- `"Database is storage, not computation"`
- `"Repairability over constraints"`
- `"Federation-safe schema"`
- `"Application logic first, database logic second"`
- `"Single source of truth"`
- `"MANDATORY and non-negotiable"`

#### **C. Repeated Structural Values**

- **Shift hours:** `"09:00-15:00"`, `"12:00-18:00"`, `"15:00-21:00"`
- **Team names:** `"Alpha"`, `"Bravo"`, `"Charlie"`, `"Delta"`
- **Team rules:** Allowed/forbidden combinations
- **Work rhythm:** Days, descriptions, pay status
- **Timestamp formats:** `"YYYYMMDDHHIISS"`, `"UTC"`
- **Node defaults:** `node_id: 1`, `node_name: "local"`
- **Agent registry totals:** `27`, `101`, `total_defined_in_registry`
- **Team coverage hours:** `"09:00-21:00 UTC"`, `double_coverage_hours: 6`

#### **D. Repeated Header Fields**

All standard WOLFIE Header fields that commonly repeat:

- `version` → `GLOBAL_CURRENT_LUPOPEDIA_VERSION`
- `author` → `GLOBAL_CURRENT_AUTHORS`
- `copyright` → `GLOBAL_CURRENT_COPYRIGHT`
- `year` → `GLOBAL_CURRENT_YEAR`
- `project_name` → `GLOBAL_PROJECT_NAME`
- `status` → `GLOBAL_DEFAULT_STATUS`
- `collection` → `GLOBAL_DEFAULT_COLLECTION` (if repeated)
- `channel` → `GLOBAL_DEFAULT_CHANNEL` (if repeated)

### **Pattern Recognition Rules**

Cursor must identify:

1. **Exact string matches** across files
2. **Similar patterns** (e.g., version numbers with different formats)
3. **Repeated phrases** (even if worded slightly differently)
4. **Repeated structural data** (team info, shift hours, etc.)

When in doubt: **atomize it.**

Better to have too many atoms than to repeat values across files.

---

## **6. Enforcement Rules for IDEs and AI Agents**

### **Cursor MUST:**

- ✅ **Scan for repeated values** before writing documentation
- ✅ **Suggest atomization** when detecting repeated values
- ✅ **Preserve atom references** exactly as written
- ✅ **Resolve atoms only when reading** (not when writing)
- ✅ **Add atoms to `header_atoms:`** when using them
- ✅ **Never inline atom values** into file content
- ✅ **Never rewrite files** when atom values change
- ✅ **Never modify atom names** (no renaming, reformatting, or "improvements")

### **Cursor MUST NOT:**

- ❌ **Hardcode repeated values** in documentation
- ❌ **Inline atom values** into file content
- ❌ **Rewrite files** when atom values change
- ❌ **Modify atom names** in any way
- ❌ **Automatically add atoms** to `global_atoms.yaml` without approval
- ❌ **Expand atoms** when reading files
- ❌ **Search and replace** values across files during version bumps

### **When Cursor Creates New Documentation**

Cursor must:

1. **Check `config/global_atoms.yaml`** for existing atoms
2. **Use existing atoms** instead of hardcoding values
3. **Suggest new atoms** if a value is repeated but no atom exists
4. **List all atoms used** in the `header_atoms:` block
5. **Never hardcode** values that should be atoms

---

## **7. Examples of Atomization**

### **Example 1: Version Bump**

**Before (WRONG - Hardcoded):**
```yaml
# File 1: README.md
file:
  version: "4.0.2"
  author: "Captain Wolfie"

# File 2: CHANGELOG.md
file:
  version: "4.0.2"
  author: "Captain Wolfie"

# File 3: HISTORY.md
file:
  version: "4.0.2"
  author: "Captain Wolfie"
```

**After (CORRECT - Atomized):**
```yaml
# File 1: README.md
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
file:
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  author: GLOBAL_CURRENT_AUTHORS

# File 2: CHANGELOG.md
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
file:
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  author: GLOBAL_CURRENT_AUTHORS

# File 3: HISTORY.md
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
file:
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  author: GLOBAL_CURRENT_AUTHORS

# config/global_atoms.yaml
GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.0.2"
GLOBAL_CURRENT_AUTHORS: "Captain Wolfie"
```

**Result:** Bump version → Update ONE line in `global_atoms.yaml` → All files automatically use new version

### **Example 2: Doctrine Phrase**

**Before (WRONG - Repeated Phrase):**
```markdown
# File 1: NO_TRIGGERS_DOCTRINE.md
"No triggers. Database is storage, not computation."

# File 2: NO_STORED_PROCEDURES_DOCTRINE.md
"No stored procedures. Database is storage, not computation."

# File 3: DATABASE_PHILOSOPHY.md
"Database is storage, not computation."
```

**After (CORRECT - Atomized):**
```markdown
# File 1: NO_TRIGGERS_DOCTRINE.md
GLOBAL_DOCTRINE_NO_TRIGGERS: "No triggers."
GLOBAL_DATABASE_PHILOSOPHY: GLOBAL_DATABASE_STORAGE_NOT_COMPUTATION

# File 2: NO_STORED_PROCEDURES_DOCTRINE.md
GLOBAL_DOCTRINE_NO_STORED_PROCEDURES: "No stored procedures."
GLOBAL_DATABASE_PHILOSOPHY: GLOBAL_DATABASE_STORAGE_NOT_COMPUTATION

# File 3: DATABASE_PHILOSOPHY.md
GLOBAL_DATABASE_PHILOSOPHY: GLOBAL_DATABASE_STORAGE_NOT_COMPUTATION

# config/global_atoms.yaml
GLOBAL_DATABASE_STORAGE_NOT_COMPUTATION: "Database is storage, not computation."
```

**Result:** Update doctrine phrase → Change ONE atom → All references automatically updated

### **Example 3: Team Information**

**Before (WRONG - Hardcoded Team Data):**
```markdown
# File 1: COMPANY_STRUCTURE.md
Alpha shift: 09:00-15:00 UTC

# File 2: TEAM_ROLES.md
Alpha team works 09:00-15:00 UTC

# File 3: WORK_RHYTHM.md
Alpha shift hours are 09:00-15:00 UTC
```

**After (CORRECT - Atomized):**
```markdown
# File 1: COMPANY_STRUCTURE.md
GLOBAL_TEAM_ALPHA_SHIFT: GLOBAL_TEAM_ALPHA_SHIFT_HOURS

# File 2: TEAM_ROLES.md
GLOBAL_TEAM_ALPHA_SHIFT: GLOBAL_TEAM_ALPHA_SHIFT_HOURS

# File 3: WORK_RHYTHM.md
GLOBAL_TEAM_ALPHA_SHIFT: GLOBAL_TEAM_ALPHA_SHIFT_HOURS

# config/global_atoms.yaml (referenced from GLOBAL_LUPOPEDIA_COMPANY_STRUCTURE)
GLOBAL_TEAM_ALPHA_SHIFT_HOURS: "09:00-15:00"
```

**Result:** Change shift hours → Update atom → All references automatically updated

---

## **ATOM EXCEPTION LIST (DO NOT ATOMIZE)**

### **⚠️ CRITICAL: Some Values Must NEVER Be Atomized**

While the atomization doctrine requires repeated values to become atoms, **certain values are intentionally repeated** and must remain inline. These values convey semantic meaning, structural importance, or doctrinal clarity that would be lost through atomization.

### **Exception Rules**

1. **Any value on the exception list must remain inline**
2. **Atomization must skip these values even if they appear 3+ times**
3. **Exception list entries may be:**
   - Exact strings
   - Regex patterns  
   - Semantic categories (e.g., WOLFIE header fields)
4. **Exception list must be versioned and documented**

### **Why These Exceptions Exist**

These values are **intentionally repeated** because:
- They are part of the doctrine structure
- They convey meaning rather than configuration
- They are ritualized repetition that reinforces understanding
- Atomizing them would reduce clarity and break semantic meaning
- They serve as structural anchors in the documentation mesh

---

## **Canonical Atom Exception List v1.0**

**These values must NEVER be atomized, even if repeated hundreds of times.**

### **1. WOLFIE Header Fields (All Structural Metadata)**

These are structural metadata that define the documentation format:

```yaml
title
description
version
status
author
tags
dialog
in_this_file_we_have
related_documentation
canonical_binding
last_updated
doctrine_version
category
collections
channels
file
speaker
target
message
mood
```

**Reason:** These fields define the doctrine structure. Atomizing them would break the documentation format and make headers unreadable.

### **2. Disambiguation Notices (Critical Warnings)**

These appear in routing doctrines and must remain verbatim:

```
⚠️ DISAMBIGUATION NOTE:
This file documents AGENT ROUTING (HERMES/CADUCEUS)
This file documents HTTP URL ROUTING
For AGENT ROUTING, see AGENT_ROUTING_DOCTRINE.md
For HTTP URL ROUTING, see URL_ROUTING_DOCTRINE.md
```

**Reason:** These are critical warnings that prevent routing confusion. They must be immediately visible and recognizable.

### **3. Canonical Routing Terminology (Doctrinal Primitives)**

These terms are foundational and must remain inline:

```
slug
URL path
filesystem path
reference entry
semantic node
domain installation
namespace
collection tab
atom
content lookup
collection tab lookup
atom lookup
lookup priority
first match wins
```

**Reason:** These are doctrinal primitives. Atomizing them would obscure meaning and make the text harder to understand.

### **4. Required Boilerplate Phrases (Invariant Architectural Rules)**

These appear across multiple doctrines and must remain intact:

```
Lupopedia must not interfere with the host website
Treat /lupopedia/ as the root of the Lupopedia application
Do not assume anything about directories above /lupopedia/
HTTP → slug → DB → render
semantic OS
reference layer
Crafty Syntax migration
```

**Reason:** These are invariant architectural rules that must be immediately recognizable and consistent.

### **5. Ritualized Repetition (Structural Anchors)**

These appear in many files and must remain repeated:

```
See also:
Related Documentation:
Canonical Binding:
Correct vs Incorrect Patterns
Example:
SQL Query:
Rendering Flow:
```

**Reason:** These are structural anchors in the documentation mesh that provide consistent navigation and organization.

### **6. Doctrine File Names (Canonical Identifiers)**

These must never be atomized or replaced:

```
URL_ROUTING_DOCTRINE.md
AGENT_ROUTING_DOCTRINE.md
SUBDIRECTORY_INSTALLATION_DOCTRINE.md
Lupopedia-Reference-Layer-Doctrine.md
CSLH-URL-Semantics.md
CSLH-Historical-Context.md
WHAT_LUPOPEDIA_IS.md
WHY_LUPOPEDIA_NEEDS_CRAFTY_SYNTAX.md
SAMPLE_REFERENCE_ENTRY.md
GLOSSARY.md
```

**Reason:** These are canonical identifiers that must remain literal for cross-referencing and linking.

### **7. Code Keywords (Developer Comprehension)**

These must remain literal in examples:

```
define
rtrim
str_replace
$_SERVER['DOCUMENT_ROOT']
__DIR__
basename
SELECT
FROM
WHERE
LIMIT 1
```

**Reason:** Atomizing code keywords breaks examples and developer comprehension. Code must remain literal.

### **8. Pattern-Based Exceptions (Regex Patterns)**

These patterns should also be excluded:

```regex
^/lupopedia/.*$
^atom:.*$
^collection/[0-9]+/.*$
^GLOBAL_.*$
^FILE_.*$
^DIR_.*$
^MODULE_.*$
```

**Reason:** These are structural patterns that define the system architecture and must remain recognizable.

### **9. Semantic Categories (Broad Classifications)**

These categories are always excluded:

- **WOLFIE header field names** (all of them)
- **Doctrine metadata fields** (all of them)
- **Routing disambiguation notices** (all of them)
- **Canonical routing terminology** (all foundational terms)
- **Required boilerplate phrases** (all architectural rules)
- **Ritualized repetition markers** (all structural anchors)
- **File extension patterns** (`.md`, `.php`, `.yaml`, etc.)
- **URL path patterns** (anything starting with `/lupopedia/`)

**Reason:** These are semantic categories that define the system structure and must remain intact.

---

## **Exception List Enforcement**

### **Before Atomizing Any Value, Check:**

1. **Is it on the exact string exception list?** → Do not atomize
2. **Does it match a regex pattern exception?** → Do not atomize  
3. **Is it in a semantic category exception?** → Do not atomize
4. **Is it marked as "ritualized" or "required repetition"?** → Do not atomize
5. **Would atomizing it reduce clarity or break meaning?** → Do not atomize

### **When in Doubt:**

**Do NOT atomize.** It's better to have repeated values that preserve meaning than to atomize structural elements that break comprehension.

### **Exception List Versioning**

- **Current Version:** v1.0 (January 15, 2026)
- **Next Review:** When new structural patterns emerge
- **Update Process:** Add to exception list, document reason, update version

---

## **Integration with Atomization Rules**

### **Modified Atomization Workflow:**

1. **Scan for repeated values** (threshold: 2+ occurrences)
2. **Check exception list** (exact strings, patterns, categories)
3. **Skip atomization** if value is on exception list
4. **Proceed with atomization** only if value passes exception check
5. **Document reason** if adding new exceptions

### **Exception Precedence:**

**Exception list takes precedence over atomization rules.**

If a value appears 100 times but is on the exception list, it must remain inline.

---

## **8. Summary Doctrine**

---

## **9. Summary Doctrine**

### **The Atomization Doctrine in One Sentence**

**Any value repeated across multiple documentation files MUST become a global atom, EXCEPT for values on the exception list, and Cursor must preserve atom references exactly, never inline values, and never rewrite files when atoms change.**

### **Core Principles**

1. **Single Source of Truth:** Every repeated value has ONE canonical definition in `global_atoms.yaml`
2. **Exception List Precedence:** Values on the exception list must remain inline regardless of repetition
3. **Symbolic References:** Documentation files contain atom names, not values (except for exceptions)
4. **Automatic Resolution:** Tools resolve atoms when reading, not when writing
5. **Zero Token Burn:** Version bumps update ONE file, not dozens
6. **Absolute Preservation:** Atom names are immutable constants, never modified
7. **Semantic Meaning:** Structural and doctrinal elements remain inline to preserve clarity

### **Benefits**

- **Consistency:** All files always reference the same canonical value
- **Maintainability:** Update once, use everywhere
- **Token Efficiency:** No mass file edits, no search/replace operations
- **Error Prevention:** Impossible for files to have different values for the same concept
- **Automation:** Tools can resolve atoms automatically during processing
- **Clarity Preservation:** Important structural elements remain visible and meaningful

### **Violations Are Forbidden**

- ❌ Hardcoding repeated values (unless on exception list)
- ❌ Inlining atom values
- ❌ Rewriting files when atoms change
- ❌ Modifying atom names
- ❌ Expanding atoms in file content
- ❌ Atomizing values on the exception list

**These violations cause token burn, inconsistency, maintenance burden, and loss of semantic meaning.**

---

## **10. Integration with Other Doctrines**

This doctrine complements:

- **[WOLFIE_HEADER_SPECIFICATION.md](../agents/WOLFIE_HEADER_SPECIFICATION.md)** — Defines atom resolution order and `header_atoms:` block
- **[NO_TRIGGERS_DOCTRINE.md](NO_TRIGGERS_DOCTRINE.md)** — Uses atoms for doctrine constants
- **[NO_STORED_PROCEDURES_DOCTRINE.md](NO_STORED_PROCEDURES_DOCTRINE.md)** — Uses atoms for doctrine constants
- **[DOCUMENTATION_DOCTRINE.md](DOCUMENTATION_DOCTRINE.md)** — Documentation must use atoms, not hardcoded values
- **[global_atoms.yaml](../../config/global_atoms.yaml)** — Single source of truth for all global atoms

---

## **11. Enforcement Checklist**

Before Cursor writes or modifies documentation, it must:

- [ ] Scan for repeated values (threshold: 2+ occurrences)
- [ ] **Check exception list** (exact strings, patterns, categories)
- [ ] **Skip atomization** if value is on exception list
- [ ] Check `config/global_atoms.yaml` for existing atoms
- [ ] Use existing atoms instead of hardcoding (for non-exception values)
- [ ] Suggest new atoms if value is repeated but no atom exists (and not on exception list)
- [ ] Add all used atoms to `header_atoms:` block
- [ ] Preserve atom references exactly (no renaming, reformatting, expansion)
- [ ] Never inline atom values into file content
- [ ] Never rewrite files when atom values change (only update `global_atoms.yaml`)
- [ ] **Never atomize values on the exception list**

---

**This doctrine is MANDATORY and non-negotiable.**  
**The exception list preserves semantic meaning and structural clarity.**  
**Violations waste tokens, create inconsistency, burden maintenance, and break comprehension.**  
**Follow this doctrine absolutely.**
