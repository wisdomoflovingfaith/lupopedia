---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created guide for using global atoms to avoid version number search/replace across all files."
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
file:
  title: "WOLFIE Header Global Atoms Usage Guide"
  description: "How to use global atoms to avoid search/replace across all files when version numbers change"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
tags:
  categories: ["documentation", "development", "guide"]
  collections: ["core-docs", "dev"]
  channels: ["dev"]
in_this_file_we_have:
  - What Are Global Atoms
  - Why Use Global Atoms
  - Available Global Atoms
  - How to Use Global Atoms in WOLFIE Headers
  - How to Update Version Numbers
  - Examples
file_atoms:
  FILE_GUIDE_TYPE: "usage-guide"
---

# ðŸ“‹ **WOLFIE Header Global Atoms Usage Guide**

## **What Are Global Atoms?**

Global atoms are **symbolic references** defined in `/config/global_atoms.yaml` that can be used in any WOLFIE header instead of hardcoding values like version numbers, author names, or copyright strings.

**Purpose:** Prevent the need to search/replace version numbers across dozens of files when Lupopedia is upgraded.

---

## **Why Use Global Atoms?**

When Lupopedia upgrades from `4.0.1` to `4.0.2`, instead of:

âŒ **Manually searching and replacing `"4.0.1"` in dozens of files**

You can:

âœ… **Update ONE file** (`config/global_atoms.yaml`)  
âœ… **All WOLFIE headers automatically use the new version**  
âœ… **No file-by-file search/replace needed**

---

## **Available Global Atoms**

All global atoms are defined in `/config/global_atoms.yaml`. Current available atoms:

### **Version Atoms**
- `GLOBAL_CURRENT_LUPOPEDIA_VERSION` â€” Current Lupopedia version (currently `"4.0.1"`)

### **Author Atoms**
- `GLOBAL_CURRENT_AUTHORS` â€” Primary author name (currently `"Captain Wolfie"`)

### **Copyright Atoms**
- `GLOBAL_CURRENT_COPYRIGHT` â€” Copyright string (currently `"Â© 2025-2026 Eric Robin Gerdes. All rights reserved."`)

### **Year Atoms**
- `GLOBAL_CURRENT_YEAR` â€” Current year (currently `"2026"`)

### **Project Atoms**
- `GLOBAL_PROJECT_NAME` â€” Project name (currently `"Lupopedia"`)

### **Status Atoms**
- `GLOBAL_DEFAULT_STATUS` â€” Default file status (currently `"published"`)

### **Complex Atoms** (referenced with dot notation in documentation)
- `GLOBAL_LUPOPEDIA_COMPANY_STRUCTURE` â€” Company structure and team information
- `GLOBAL_LUPOPEDIA_V4_0_2_CORE_AGENTS` â€” Required agents list for v4.0.2

---

## **How to Use Global Atoms in WOLFIE Headers**

### **Step 1: Reference the Atom in `header_atoms:` Block**

List all global atoms you're using in the `header_atoms:` block:

```yaml
---
wolfie.headers.version: 4.0.1
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
---
```

### **Step 2: Use the Atom Name (Not the Value) in `file:` Block**

Use the literal atom name (not resolver syntax) in your file metadata:

```yaml
file:
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  author: GLOBAL_CURRENT_AUTHORS
  status: GLOBAL_DEFAULT_STATUS
```

**Important:** Use the atom name exactly as written (e.g., `GLOBAL_CURRENT_LUPOPEDIA_VERSION`), not the value (not `"4.0.1"`).

---

## **Complete Example**

### **âœ… CORRECT: Using Global Atoms**

```yaml
---
wolfie.headers.version: 4.0.1
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Updated documentation with new agent information."
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
file:
  title: "Agent Guide"
  description: "Guide to Lupopedia AI agents"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
tags:
  categories: ["documentation", "agents"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
---
```

### **âŒ WRONG: Hardcoding Values**

```yaml
---
wolfie.headers.version: 4.0.1
file:
  version: "4.0.1"  # âŒ Hardcoded - will need search/replace on upgrade
  author: "Captain Wolfie"  # âŒ Hardcoded - will need search/replace if author changes
---
```

---

## **How to Update Version Numbers**

When Lupopedia upgrades from `4.0.1` to `4.0.2`:

### **Step 1: Update `/config/global_atoms.yaml`**

```yaml
# Change this line:
GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.0.1"

# To this:
GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.0.2"
```

### **Step 2: That's It!**

All WOLFIE headers that reference `GLOBAL_CURRENT_LUPOPEDIA_VERSION` will now automatically use `"4.0.2"` when resolved.

**No search/replace needed across any files.**

---

## **Resolution Process**

When a tool or agent reads a WOLFIE header:

1. **Detects atom reference** â€” Sees `version: GLOBAL_CURRENT_LUPOPEDIA_VERSION`
2. **Resolves atom** â€” Looks up `GLOBAL_CURRENT_LUPOPEDIA_VERSION` in `/config/global_atoms.yaml`
3. **Gets value** â€” Retrieves `"4.0.1"` (or whatever the current value is)
4. **Uses resolved value** â€” Treats `version` as `"4.0.1"` for processing

**The file itself still contains the symbolic reference.**  
**Only during resolution does the actual value get substituted.**

---

## **Cursor Behavior Rules**

Cursor (and all IDEs) must:

- âœ… **Preserve symbolic references** â€” Keep `GLOBAL_CURRENT_LUPOPEDIA_VERSION` exactly as written
- âœ… **Resolve values when reading** â€” Look up actual value from `global_atoms.yaml` when processing
- âœ… **List atoms in `header_atoms:`** â€” Declare all global atoms used in the file
- âŒ **NEVER expand atoms** â€” Don't replace `GLOBAL_CURRENT_LUPOPEDIA_VERSION` with `"4.0.1"` in the file
- âŒ **NEVER inline values** â€” Don't substitute the actual value when writing headers
- âŒ **NEVER modify `global_atoms.yaml`** â€” Unless explicitly instructed to update version

---

## **Benefits**

1. **Single Source of Truth** â€” Version number defined once in `global_atoms.yaml`
2. **No Search/Replace** â€” Update one file, all headers automatically use new version
3. **Consistency** â€” All files guaranteed to reference the same version
4. **Maintainability** â€” Easy to update version across entire codebase
5. **Symbolic Clarity** â€” Clear that this is a reference, not a hardcoded value

---

## **Migration Strategy**

When migrating existing files to use global atoms:

1. **Add `header_atoms:` block** â€” List all global atoms you'll use
2. **Replace hardcoded values** â€” Change `version: "4.0.1"` to `version: GLOBAL_CURRENT_LUPOPEDIA_VERSION`
3. **Test resolution** â€” Verify tools can resolve the atoms correctly
4. **Update documentation** â€” Document that files now use atoms

**Example Migration:**

**Before:**
```yaml
file:
  version: "4.0.1"
  author: "Captain Wolfie"
```

**After:**
```yaml
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
file:
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  author: GLOBAL_CURRENT_AUTHORS
```

---

## **Reference Documentation**

- **WOLFIE Header Specification:** [WOLFIE_HEADER_SPECIFICATION.md](../../agents/WOLFIE_HEADER_SPECIFICATION.md)
- **Global Atoms File:** `/config/global_atoms.yaml`
- **Atom Resolution Order:** See WOLFIE_HEADER_SPECIFICATION.md section 7

---

**Remember:** The power of global atoms is that you **update once, use everywhere**. No more hunting through dozens of files to update version numbers.
