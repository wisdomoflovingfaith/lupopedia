---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.93/CRITICAL_CONSTITUTIONAL_FIXES.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/CRITICAL_CONSTITUTIONAL_FIXES.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: constitutional_fix
  thread_id: "constitutional-fixes"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Critical Constitutional Fixes - 4.0.93

Generated: 2026-03-30 16:34:00

## 🔥 **CRITICAL ISSUES FIXED**

### 1. ✅ **Explicit Binding Rule for rules/root/**

**BEFORE**: PRD referenced directory but didn't state its binding nature
**AFTER**: Added explicit constitutional supremacy rule

```
### 11.1 Constitutional Supremacy
**All files in `rules/root/` are binding constitutional law and override all PRDs.**
```

**Impact**: Agents now understand root rules override all PRDs

---

### 2. ✅ **No Hardcoded Filesystem Paths**

**BEFORE**: Only forbade hardcoded web paths
**AFTER**: Added RULE 93.NO_HARDCODED_PATHS

```
## 9.1 Filesystem Path Restrictions (RULE 93.NO_HARDCODED_PATHS)

**No hardcoded filesystem paths allowed:**
- ❌ `/var/www/...`
- ❌ `/home/...`
- ❌ `/usr/local/...`
- ❌ Any absolute filesystem paths
```

**Impact**: Ensures portability across shared hosting

---

### 3. ✅ **Primary Key Requirements**

**BEFORE**: Mentioned IdGenerator but not explicit PK format
**AFTER**: Added RULE 93.PK_FORMAT

```
## 9.2 Primary Key Requirements (RULE 93.PK_FORMAT)

**All primary keys MUST be:**
- **BIGINT(18)** exactly
- **Generated via IdGenerator class**
- **YYYYMMDDHHIISS + 4-digit sequence format**

**Forbidden:**
- ❌ VARCHAR primary keys
- ❌ Composite primary keys
- ❌ AUTO_INCREMENT
```

**Impact**: Prevents schema drift

---

### 4. ✅ **Soft Delete Pattern**

**BEFORE**: Implied but not explicit
**AFTER**: Added RULE 93.SOFT_DELETE

```
## 9.3 Soft Delete Pattern (RULE 93.SOFT_DELETE)

**All soft deletes MUST use this exact pattern:**
```sql
is_deleted TINYINT NOT NULL DEFAULT 0
deleted_ymdhis BIGINT NULL
```
```

**Impact**: Prevents alternate soft-delete patterns

---

### 5. ✅ **Schema Inference Prohibition**

**BEFORE**: Not explicitly forbidden
**AFTER**: Added RULE 93.NO_SCHEMA_INFERENCE

```
## 9.4 Schema Inference Prohibition (RULE 93.NO_SCHEMA_INFERENCE)

**Agents must NOT infer schema from:**
- ❌ PHP arrays
- ❌ Model classes
- ❌ Comments
- ❌ Docblocks
- ❌ Any PHP code structure

**Schema comes ONLY from:**
- ✅ Database TOON files (read-only)
- ✅ Install SQL files
- ✅ Explicit PRD documentation
```

**Impact**: Major source of drift eliminated

---

### 6. ✅ Subdirectory .htaccess Clarification

**BEFORE**: Impplied no .htaccess allowed
**AFTER**: Added RULE 93.SUBDIRECTORY_HTACCESS

```
## 9.5 .htaccess Usage (RULE 93.SUBDIRECTORY_HTACCESS)

✅ **ALLOWED:**
- .htaccess inside `/lupopedia/` directory
- Rewrite rules scoped to Lupopedia subdirectory
- Fallback routing to index.php
- Clean URLs within Lupopedia

❌ **FORBIDDEN:**
- Modifying parent directory's .htaccess
- Requiring server-level rewrite rules
- Assuming mod_rewrite is enabled
- Breaking parent site's routing
```

**Impact**: Aligns with Crafty Syntax, phpBB, and Softaculous standards

---

### 7. ✅ ASCII Safety Rule (RULE 93.ASCII_SAFETY)

**BEFORE**: No explicit ASCII requirement
**AFTER**: Added ASCII-only filename requirement

```
## 9.10 ASCII Safety (RULE 93.ASCII_SAFETY)

**All filenames must be ASCII-only:**
- ❌ UTF-8 BOM in PHP files
- ❌ Unicode in class names
- ❌ Unicode in directory names
```

**Impact**: Prevents Unicode breaks on shared hosting

---

### 8. ✅ No Symlinks Rule (RULE 93.NO_SYMLINKS)

**BEFORE**: No symlinks policy
**AFTER**: Explicit symlinks prohibition

```
## 9.11 No Symlinks (RULE 93.NO_SYMLINKS)

**No symbolic links allowed anywhere in the codebase.**
```

**Impact**: Ensures compatibility with restricted shared hosting

---

### 9. ✅ Database Engine Neutrality (RULE 93.DB_ENGINE_NEUTRALITY)

**BEFORE**: Only forbid UNSIGNED
**AFTER**: Forbid all ENGINE/COLLATE clauses

```
## 9.12 Database Engine Neutrality (RULE 93.DB_ENGINE_NEUTRALITY)

**No ENGINE= or COLLATE= clauses allowed in SQL:**
- ❌ ENGINE=InnoDB
- ❌ COLLATE=utf8mb4_unicode_ci
```

**Impact**: Database engine left to host configuration

---

### 10. ✅ Installer Sandbox Rule (RULE 93.INSTALLER_SANDBOX) - LILITH-APPROVED

**BEFORE**: Absolute restriction (too restrictive)
**AFTER**: Controlled exception for config file

```
## 9.13 Installer Sandbox (RULE 93.INSTALLER_SANDBOX)

### 9.13.1 General Sandbox Restrictions
Installer may only write inside `/public_html/lupopedia/`
EXCEPT for secure configuration file.

### 9.13.2 Secure Configuration Exception (Allowed)
Installer may attempt to write `../lupopedia-config.php`
IF AND ONLY IF:
- Directory is writable
- Hosting permits it
- Safe write test performed first

### 9.13.3 Fallback Behavior (Mandatory)
If cannot write above web root:
- Write config inside Lupopedia directory
- Continue installation normally
- Warn user about public location

### 9.13.4 No Other Exceptions
Config file is ONLY exception.
```

**Impact**: Aligns with WordPress, phpBB, MediaWiki, Crafty Syntax standards

**Status**: ✅ LILITH-APPROVED FIX

---

### 11. ✅ Section Numbering Fixed

**BEFORE**: Jumped from 9.9 to 12
**AFTER**: Proper sequential numbering (9.1-9.13, 10, 11)

**Impact**: Constitutional documents are now clean

---

## 📋 **COMPLETE RULE SET NOW EXPLICIT**

1. **Database Doctrine** (existing)
2. **TOON File Protection** (existing)
3. **Agent File Protection** (added)
4. **Absolute-Root Pathing** (existing)
5. **Controlled Namespace Doctrine** (added)
6. **Installer Requirements** (existing)
7. **Subdirectory .htaccess Usage** (NEW - CLARIFIED)
8. **Filesystem Path Restrictions** (NEW)
9. **Primary Key Requirements** (NEW)
10. **Soft Delete Pattern** (NEW)
11. **Schema Inference Prohibition** (NEW)
12. **ASCII Safety** (NEW)
13. **No Symlinks** (NEW)
14. **Database Engine Neutrality** (NEW)
15. **Installer Sandbox** (NEW)
16. **Constitutional Supremacy** (NEW)

---

## 🎯 **COMPLIANCE STATUS**

- [x] All 5 critical issues addressed
- [x] .htaccess usage clarified (6th fix)
- [x] 5 additional refinements added (total 11 fixes)
- [x] Section numbering fixed (proper sequential)
- [x] Rules made explicit and binding
- [x] Constitutional supremacy established
- [x] Documentation created

---

## 🚨 **IMMEDIATE ACTION REQUIRED**

1. **Train all agents** on these explicit rules
2. **Update code reviews** to check for violations
3. **Audit existing code** for hardcoded paths
4. **Validate schema** follows PK format
5. **Check soft deletes** use exact pattern
6. **Review schema inference** in any PHP code
7. **Validate filenames** are ASCII-only
8. **Check for symlinks** in codebase
9. **Audit SQL** for ENGINE/COLLATE clauses
10. **Verify installer** respects sandbox boundaries
11. **Test .htaccess** in subdirectory only

---

**STATUS**: All critical constitutional fixes implemented
**PRIORITY**: Critical - prevents doctrine drift
**IMPACT**: System stability and portability ensured
