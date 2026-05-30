# LUPOPEDIA HEADERS
---
lupopedia.headers:
  lupopedia.version: "4.0.99"
  lupopedia.schema: "doctrine"
  file_path_from_root: "docs/channels/doctrine/file_and_folder_naming_doctrine.md"
  when_updated: "20260414173000"
  system_version: "4.0.99"
  channel_id: 1
  actor_id: 116
  artifact_type: "doctrine"
  artifact_kind: "naming_standards"
  purpose: "Consolidated doctrine for file and folder naming in Lupopedia"
  mood_vector: "4169E1"
  traits: ["canonical", "enforced"]
  tags: ["documentation", "standards", "file_naming", "folder_naming"]
---

# File and Folder Naming Doctrine

**⚠️ MANDATORY (NON-NEGOTIABLE)**  
**Version 4.0.99**  
**Effective Date: 2026-04-14**

## Overview

All files and folders in Lupopedia MUST follow strict naming rules to ensure cross-platform compatibility, clarity, and consistency. This doctrine applies to **ALL** project artifacts, documentation, and memory nodes.

## 1. Folder Naming Rules

**ALL folder names MUST be lowercase with hyphens.**

- ✅ **Allowed:** `docs/`, `core/`, `doctrine/`, `agents/`, `database/`
- ❌ **FORBIDDEN:** `ARCHITECTURE/`, `Core/`, `Doctrine/`, `Lupo-Agents/`, `DataBase/`
- ⚠️ **Avoid:** `lupo_agents/` (use hyphens for folders)

### 1.1 Allowed Characters (Folders)
- **Lowercase letters:** `a-z`
- **Digits:** `0-9`
- **Hyphen:** `-` (dash, preferred separator)
- **Underscore:** `_` (allowed but discouraged)

### 1.2 Forbidden (Folders)
- ❌ **No Uppercase**
- ❌ **No Dots** (except for `.git` or `.env` if explicitly required by legacy, but Lupopedia constitution §9.10 forbids them)
- ❌ **No Spaces**
- ❌ **No Hidden Folders** (starting with `.`)

---

## 2. File Naming Rules

**ALL filenames MUST be lowercase with underscores.**

- ✅ **Allowed:** `core_agents_prd.md`, `folder_naming_doctrine.md`, `lupo_sessions.json`
- ❌ **FORBIDDEN:** `CORE_AGENTS_PRD.md`, `CoreAgentsPrd.md`, `core-agents-prd.md`

### 2.1 Allowed Characters (Files)
- **Lowercase letters:** `a-z`
- **Digits:** `0-9`
- **Underscore:** `_` (canonical separator for files)
- **Dot:** `.` (only for extension)

### 2.2 Forbidden (Files)
- ❌ **No Uppercase**
- ❌ **No Spaces**
- ❌ **No Hyphens** (reserved for folders)
- ❌ **No Special Characters** (ASCII-safe only)

---

## 3. Rationale

### 3.1 Cross-Platform Compatibility
Windows is case-insensitive, while Linux and macOS are case-sensitive. Standardizing on lowercase prevents collisions and "file not found" errors when moving the project across environments.

### 3.2 URL and Path Safety
Lowercase names with clear separators (hyphens for folders, underscores for files) are safer for web URLs and shell scripts, requiring no quoting or special encoding.

### 3.3 Deterministic Behavior
Eliminating naming variations ensures that AI agents and automated tools can predict and locate files reliably without ad-hoc case checks.

---

## 4. Enforcement

### 4.1 For AI Assistants
**BEFORE creating or renaming any file or folder:**
1. ✅ Convert all uppercase letters to lowercase.
2. ✅ Replace spaces/dots/hyphens in files with underscores.
3. ✅ Replace spaces/dots/underscores in folders with hyphens.
4. ✅ Verify name matches the character set: `[a-z0-9_]+` for files, `[a-z0-9-]+` for folders.

### 4.2 Migration Protocol
Existing files that violate this doctrine must be renamed. Update all documentation references when renaming to prevent broken links.

---

## Document History

- **2025-01-06**: Created folder naming doctrine (v3.0.2)
- **2026-04-14**: Expanded to include File Naming Doctrine (v4.0.99) and mandated `lowercase_with_underscores` for all files.

---

**END OF DOCTRINE DOCUMENT**
