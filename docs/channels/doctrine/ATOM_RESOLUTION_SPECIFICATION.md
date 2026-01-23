---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created ATOM_RESOLUTION_SPECIFICATION.md: Complete specification for atom resolution engine. FILE_→DIR_→DIRR_→MODULE_→GLOBAL_. First match wins. Deterministic and idempotent."
tags:
  categories: ["documentation", "doctrine", "specification"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
header_atoms:
  - GLOBAL_CURRENT_AUTHORS
in_this_file_we_have:
  - Atom resolution algorithm
  - Scope hierarchy
  - Resolution order
  - Resolver implementation rules
  - Error handling
  - Performance requirements
file:
  title: "Atom Resolution Specification"
  description: "Complete specification for the atom resolution engine. Defines scopes, resolution order, and deterministic resolution behavior."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🟦 **Atom Resolution Specification**

## **Purpose**

This specification defines the **atom resolution engine** that resolves symbolic atom references to literal values across multiple scopes.

Atoms are **variables with scopes**.  
Resolution is **deterministic and idempotent**.

---

## **Atom Reference Syntax**

### **In Documentation Prose**
```
@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name
@GLOBAL.LUPOPEDIA_V4_0_2_CORE_AGENTS.required_agents
@MODULE.CRAFTYSYNTAX.version
@DIRR.DOCS.author
@FILE.CUSTOM_STATUS
```

### **In WOLFIE Headers**
```yaml
file:
  author: GLOBAL_CURRENT_AUTHORS
  version: MODULE_DOCS_VERSION
  status: FILE_CUSTOM_STATUS
```

**Note:** WOLFIE Headers use literal atom names (no `@` prefix). The resolver adds the `@` prefix internally during resolution.

---

## **Atom Scopes**

### **FILE_*** (Highest Priority)
- **Scope:** Current file only
- **Location:** `file_atoms:` block in WOLFIE Header
- **Inheritance:** None
- **Use Case:** File-specific overrides, temporary values

### **DIR_*** (Directory, Non-Recursive)
- **Scope:** Current directory only
- **Location:** `<directory>/_dir_atoms.yaml`
- **Inheritance:** None
- **Use Case:** Directory-specific defaults

### **DIRR_*** (Directory, Recursive)
- **Scope:** Current directory + all descendant directories
- **Location:** `<directory>/_dir_atoms.yaml` (walk up parent directories)
- **Inheritance:** Parent directory resolution
- **Use Case:** Inherited defaults, recursive configuration

### **MODULE_*** (Module Scope)
- **Scope:** Entire module
- **Location:** `modules/<module>/module_atoms.yaml`
- **Inheritance:** None
- **Use Case:** Module-specific versions, authors, metadata

### **GLOBAL_*** (Final Fallback)
- **Scope:** Ecosystem-wide
- **Location:** `config/global_atoms.yaml`
- **Inheritance:** None
- **Use Case:** System-wide constants, versions, company info

---

## **Resolution Order**

Resolution follows this order (first match wins):

1. **FILE_*** — Check `file_atoms:` block in current file's WOLFIE Header
2. **DIR_*** — Check `_dir_atoms.yaml` in current file's directory
3. **DIRR_*** — Walk up parent directories, check each `_dir_atoms.yaml` until found
4. **MODULE_*** — Check `module_atoms.yaml` for current module (if file is in a module)
5. **GLOBAL_*** — Check `config/global_atoms.yaml`

**First match wins. Stop searching once atom is found.**

---

## **Resolution Algorithm**

### **Pseudocode**

```
function resolveAtom(atomName, filePath, projectRoot):
    // Extract scope prefix
    scope = extractScope(atomName)  // FILE_, DIR_, DIRR_, MODULE_, GLOBAL_
    baseName = removeScopePrefix(atomName)
    
    // Resolution order
    if scope == "FILE_":
        value = resolveFileAtom(atomName, filePath)
        if value != null: return value
    
    if scope == "DIR_" or scope == "DIRR_":
        value = resolveDirectoryAtom(atomName, filePath, scope == "DIRR_")
        if value != null: return value
    
    if scope == "MODULE_":
        value = resolveModuleAtom(atomName, filePath, projectRoot)
        if value != null: return value
    
    if scope == "GLOBAL_":
        value = resolveGlobalAtom(atomName, projectRoot)
        if value != null: return value
    
    // Atom not found
    return null
```

### **Helper Functions**

#### **resolveFileAtom(atomName, filePath)**
1. Read WOLFIE Header from file at `filePath`
2. Extract `file_atoms:` block
3. Look up `atomName` in `file_atoms:`
4. Return value if found, else `null`

#### **resolveDirectoryAtom(atomName, filePath, recursive)**
1. Get directory of `filePath`
2. Check `<directory>/_dir_atoms.yaml`
3. If recursive and not found, walk up to parent directory
4. Repeat until found or project root reached
5. Return value if found, else `null`

#### **resolveModuleAtom(atomName, filePath, projectRoot)**
1. Determine if `filePath` is within a module directory (`modules/<module>/`)
2. If yes, load `modules/<module>/module_atoms.yaml`
3. Look up `atomName` in module atoms
4. Return value if found, else `null`

#### **resolveGlobalAtom(atomName, projectRoot)**
1. Load `config/global_atoms.yaml` from `projectRoot`
2. Look up `atomName` in global atoms
3. Return value if found, else `null`

---

## **Nested Atom Resolution**

Atoms can contain nested structures:

```yaml
# config/global_atoms.yaml
GLOBAL_LUPOPEDIA_COMPANY_STRUCTURE:
  company:
    name: "Lupopedia LLC"
    formation_date: "2025-11-06"
  teams:
    alpha:
      shift_utc: "09:00-15:00"
```

Resolution uses dot notation:

```
@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name
→ "Lupopedia LLC"

@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.teams.alpha.shift_utc
→ "09:00-15:00"
```

**Implementation:**
1. Resolve base atom (`GLOBAL_LUPOPEDIA_COMPANY_STRUCTURE`)
2. Navigate nested structure using dot notation path
3. Return final value

---

## **Error Handling**

### **Atom Not Found**
- **Behavior:** Preserve atom reference as-is
- **Logging:** Log warning (do not fail)
- **Resolution:** Continue processing with atom name as literal text
- **Documentation:** Document missing atom in `header_atoms:` block

### **Atom File Not Found**
- **Behavior:** Skip that scope, continue to next scope
- **Logging:** Log warning for missing file
- **Resolution:** Continue resolution at next scope level
- **Result:** Atom remains as symbolic reference

### **Invalid Atom Reference Syntax**
- **Behavior:** Preserve as-is, do not attempt resolution
- **Logging:** Log error
- **Resolution:** Skip resolution for that reference
- **Example Invalid:** `@GLOBAL.` (incomplete), `@@GLOBAL.ATOM` (double prefix)

### **Circular Reference**
- **Behavior:** Detect and break cycle
- **Logging:** Log error
- **Resolution:** Use last resolved value before cycle
- **Prevention:** Do not create atoms that reference themselves

### **Type Mismatch**
- **Behavior:** Coerce to string for display
- **Logging:** Log warning
- **Resolution:** Convert value to string representation
- **Example:** Number `27` → String `"27"` for display

---

## **Performance Requirements**

### **Caching**
- Cache loaded atom files in memory
- Invalidate cache when atom files change (file modification time)
- Cache resolution results per file (file-level cache)

### **Lazy Loading**
- Load atom files only when needed
- Do not load all atom files at startup
- Load on first resolution request

### **Incremental Resolution**
- Only resolve atoms when file is read/modified
- Do not resolve entire documentation tree on every operation
- Batch resolution when possible

---

## **Deterministic Requirements**

Resolution must be **deterministic**:

- Same input → Same output  
- No random behavior  
- No time-based values  
- No environment-dependent resolution  
- Resolution order is fixed (FILE → DIR → DIRR → MODULE → GLOBAL)  

Resolution must be **idempotent**:

- Resolving twice produces same result  
- Resolution does not modify atom files  
- Resolution does not modify source files  
- Resolution is a pure function  

---

## **Implementation Notes**

### **File Format Support**
- **YAML:** Primary format for atom files
- **TOON:** Future support (when TOON parser exists)
- **JSON:** Fallback format (if YAML parsing fails)

### **Character Encoding**
- **UTF-8:** Required for all atom files
- **Line Endings:** Normalize to LF for consistency

### **Case Sensitivity**
- Atom names are **case-sensitive**
- `GLOBAL_CURRENT_AUTHORS` ≠ `GLOBAL_current_authors`
- Maintain exact case matching

### **Whitespace**
- Trim leading/trailing whitespace from atom values
- Preserve internal whitespace
- Normalize multiple spaces to single space (optional, configurable)

---

## **Testing Requirements**

### **Unit Tests**
- Test each scope resolution independently
- Test resolution order (first match wins)
- Test nested atom resolution
- Test error cases (atom not found, invalid syntax)

### **Integration Tests**
- Test complete resolution across all scopes
- Test recursive directory resolution
- Test module resolution
- Test global atom resolution

### **Performance Tests**
- Test resolution time for single atom
- Test resolution time for file with 100+ atom references
- Test cache invalidation performance
- Test lazy loading performance

---

## **Related Documentation**

- **[DOCUMENTATION_DOCTRINE.md](DOCUMENTATION_DOCTRINE.md)** — Documentation-as-code doctrine
- **[WOLFIE_HEADER_SPECIFICATION.md](../agents/WOLFIE_HEADER_SPECIFICATION.md)** — WOLFIE Header format
- **[WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md](../agents/WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md)** — Atom usage guide
- **[DOCUMENTATION_AS_CODE_MANIFESTO.md](DOCUMENTATION_AS_CODE_MANIFESTO.md)** — Documentation-as-code manifesto

---

**This specification is authoritative.  
All implementations must follow this specification exactly.**
