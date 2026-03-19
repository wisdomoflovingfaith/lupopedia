---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "rule"
  system_version: "4.0.82"
  file_path_from_root: "lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md"
  web_path: "http://www.lupopedia.com/lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1037
  task_id: "task_file_boundary_validation_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "rule"
  artifact_kind: "validation"
  purpose: "HEPHAESTUS validator rule - only files with Lupopedia headers may be modified"
  tags: ["wolfie", "validation", "file_boundary", "system_protection", "hephaestus"]
lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Implement in HEPHAESTUS validator suite"
    - "Deploy to all agent runtimes"
---

# 🔒 FILE BOUNDARY VALIDATION RULE

## 🎯 PURPOSE

Protect Lupopedia from unauthorized file modifications by enforcing strict boundary: **only files with Lupopedia headers may be modified**.

---

## 📋 VALIDATION CRITERIA

### File Safety Check
A file is **SAFE** to modify if and only if:

1. **Has Valid Lupopedia Header**
   ```
   ---
   lupopedia.headers:
   ...
   ---
   ```

2. **Is Within Controlled Directories**
   - `lupo-channels/` (channel artifacts)
   - `lupo-docs/doctrine/` (doctrine files)
   - `lupo-rules/root/` (rule files)
   - Task-created files with proper headers

3. **Is Not Legacy Protected**
   - Files without headers are OUT OF BOUNDS
   - Legacy Crafty files require explicit migration

4. **Path Authority Clarification**
   - File location is determined by **directory doctrine and `file_path_from_root`**
   - **Collections do not define filesystem paths** - they organize navigation only
   - **Namespace does not define filesystem paths** - it classifies for policy only
   - Any logic deriving file path from collection slug or namespace value is a boundary violation

### File Safety Failure
A file is **UNSAFE** to modify if:

1. **No Lupopedia Header**
   - Missing `---` header block
   - No `lupopedia.headers` section
   - Plain text or markdown without structure

2. **Outside Controlled Directories**
   - Random files in root directory
   - Configuration files without headers
   - External documentation not under Lupopedia control

3. **Legacy Protected Files**
   - Original Crafty Syntax files
   - Files marked for migration-only
   - Historical artifacts without proper headers

---

## 🔧 VALIDATION IMPLEMENTATION

### HEPHAESTUS Validator Requirements

#### A. Header Detection
```php
function hasValidLupopediaHeader($file_path) {
    $content = file_get_contents($file_path);
    return preg_match('/^---\s*\n.*lupopedia\.headers:/', $content);
}
```

#### B. Directory Validation
```php
function isInControlledDirectory($file_path) {
    $controlled = [
        'lupo-channels/',
        'lupo-docs/doctrine/',
        'lupo-rules/root/'
    ];
    
    foreach ($controlled as $dir) {
        if (strpos($file_path, $dir) === 0) {
            return true;
        }
    }
    return false;
}
```

#### C. Safety Enforcement
```php
function validateFileModification($file_path, $actor_id) {
    if (!hasValidLupopediaHeader($file_path)) {
        throw new Exception("FILE_BOUNDARY_VIOLATION: File lacks valid Lupopedia header");
    }
    
    if (!isInControlledDirectory($file_path)) {
        throw new Exception("FILE_BOUNDARY_VIOLATION: File outside controlled directories");
    }
    
    // Log all modification attempts
    logModification($file_path, $actor_id);
    
    return true; // Safe to proceed
}
```

---

## 🚨 ENFORCEMENT ACTIONS

### Immediate Block
1. **Reject modifications** to files without headers
2. **Block directory traversal** outside controlled paths
3. **Log all violations** for audit trail
4. **Notify system operators** of boundary violations

### Migration Path
1. **Identify legacy files** lacking headers
2. **Create migration tasks** to add proper headers
3. **Gradual upgrade** with explicit approval
4. **Preserve provenance** during migration

---

## 📋 VALIDATION TESTS

### Test Case 1: Valid File
```php
validateFileModification('lupo-channels/51/threads/1037/artifact.md', 1);
// Expected: PASS (has header, in controlled directory)
```

### Test Case 2: Invalid File (No Header)
```php
validateFileModification('random_file.txt', 1);
// Expected: EXCEPTION (no Lupopedia header)
```

### Test Case 3: Invalid File (Wrong Directory)
```php
validateFileModification('outside/file.md', 1);
// Expected: EXCEPTION (outside controlled directory)
```

### Test Case 4: Legacy File
```php
validateFileModification('legacy_crafty_file.php', 1);
// Expected: EXCEPTION (legacy protection)
```

---

## 🔒 SYSTEM PROTECTION

### Boundary Enforcement
- **All agents** must run validation before file modifications
- **HEPHAESTUS** monitors for boundary violations
- **Automated blocking** of unsafe modifications
- **Audit trail** of all modification attempts

### Legacy File Handling
- **No direct modifications** to legacy files
- **Migration required** before any changes
- **Explicit approval** needed for legacy file updates

### Directory Control
- **Strict path enforcement** for controlled directories
- **No directory traversal** outside approved paths
- **Header validation** mandatory for all modifications

---

## 🎯 SUCCESS CONDITIONS

1. **Header Detection**: 100% of files have valid headers
2. **Directory Compliance**: 0% modifications outside controlled directories
3. **Legacy Protection**: 0% unauthorized legacy file modifications
4. **Violation Detection**: 100% of boundary violations caught and logged
5. **System Integrity**: Lupopedia file ecosystem protected from corruption

---

## 📚 RELATED RULES

- **ACTOR_STATE_DOCTRINE.md**: Identity vs state separation
- **CONVERGENCE_DOCTRINE.md**: Single canonical system state
- **MULTI_AGENT_COORDINATION_DOCTRINE.md**: Agent coordination protocols
- **SYSTEM_LIMITS_DOCTRINE.md**: System boundary definitions

---

## 🔒 NON-NEGOTIABLE REQUIREMENT

**Only files with Lupopedia headers may be modified.**

This rule is **constitutional** and applies to:

- All agents (AI, human, external)
- All automated systems
- All migration operations
- All task executions

**No exceptions. No workarounds. No gradual adoption.**

---

*This rule protects Lupopedia's file system integrity while enabling safe evolution within controlled boundaries.*
