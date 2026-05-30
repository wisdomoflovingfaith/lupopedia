> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/DIRECTORY_STRUCTURE.md"
  file_hash: "b1e497fcb15a665f15d47d0360988b28748ff52c7ba4edc391b3fa2f04f9abb8"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\doctrine\DIRECTORY_STRUCTURE.md"
  file_hash: "e5b04bb9213f79261838a46c23cffdeb46fa78f9735634940a16e37683726b53"
  file_path_from_root: "docs\channels\doctrine\DIRECTORY_STRUCTURE.md"
  file_hash: "b19956337a37890640513f4a1aaf1c33365927b687ff1f7f8fc93c64b0a79970"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for DIRECTORY_STRUCTURE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "directory_structuremd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_vector: "0066FF"
  message: "Created DIRECTORY_STRUCTURE.md as core documentation for Phase 2. Defines canonical directory layout, file organization principles, and structural doctrine for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)."
tags:
  categories: ["documentation", "core", "structure"]
  collections: ["core-docs", "architecture"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Canonical Directory Structure
  - File Organization Principles
  - Security Boundaries
  - Module Organization
  - Agent Directory Structure
  - Documentation Organization
  - Database Organization
  - Legacy Code Organization
  - Configuration File Placement
  - Public vs Private Directory Rules
file:
  title: "Directory Structure Doctrine"
  description: "Canonical directory layout and file organization principles for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 📁 Directory Structure Doctrine

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-14

## Overview

This document defines the canonical directory structure for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). The directory layout is designed for security, maintainability, portability, and clear separation of concerns.

**Critical Principle:** Directory structure reflects architectural boundaries and security zones.

---

## 1. Root Directory Structure

```
[web-root]/                     # Public web directory (public/, servbay/, htdocs/, etc.)
+-- lupopedia/                  # Main application directory
|   +-- api/                    # API endpoints and external interfaces
|   +-- admin/             # Administrative interface
|   +-- content/           # User uploads and media (writable)
|   +-- includes/          # Core classes, functions, and includes
|   +-- agents/            # AI agent configuration and files
|   +-- database/               # Database schemas, migrations, and data
|   +-- docs/                   # Documentation system
|   +-- modules/                # Modular components and extensions
|   +-- legacy/                 # Legacy code reference (development only)
|   +-- dialogs/                # Dialog files (MANDATORY location)
|   +-- config/                 # Configuration files and atoms
|   +-- images/                 # Static images and assets
|   +-- index.php               # Front controller
|   +-- lupopedia-load.php      # Bootstrap loader
+-- remote-index.php            # Portable entry point (optional)
+-- license.txt                 # License file
+-- .htaccess                   # Apache configuration

lupopedia-config.php            # Main configuration (OUTSIDE web root)
```

---

## 2. Security Boundaries

### 2.1 Public Web Root
**Location:** `[web-root]/lupopedia/`  
**Access:** Public HTTP access  
**Contains:** Application files, static assets, front controller  
**Security:** All files must be safe for public access

### 2.2 Configuration Directory
**Location:** `lupopedia-config.php` (parent directory of web root)  
**Access:** Private (outside web root)  
**Contains:** Database credentials, API keys, sensitive configuration  
**Security:** Never accessible via HTTP

### 2.3 Writable Directories
**Locations:**
- `content/` - User uploads, generated files
- `database/lupopedia/csv/` - CSV data files (if enabled)
- `database/lupopedia/toon/` - TOON data files

**Security:** Write permissions required, proper upload validation

---

## 3. Core Application Directories

### 3.1 includes/
**Purpose:** Core application logic  
**Structure:** See legacy-core/DIRECTORY_STRUCTURE.md for full tree.

**Rules:**
- All core classes live here
- No user-modifiable files
- Follow PSR-4 autoloading where applicable
- Use explicit includes for performance

### 3.2 admin/
**Purpose:** Administrative interface  

**Rules:**
- Separate from public interface
- Authentication required for all access
- Admin-specific styling and behavior

### 3.3 content/
**Purpose:** User-generated and uploaded content  

**Rules:**
- Must be writable by web server
- Proper file validation and sanitization
- Regular cleanup of temporary files

---

## 4. Agent Directory Structure

See legacy-core/DIRECTORY_STRUCTURE.md for full agent directory layout.

---

## 5. Documentation Organization

**docs/doctrine/** - MANDATORY architectural rules  
**docs/core/** - Fundamental system documentation  
**docs/agents/** - Agent system specifications  
**docs/schema/** - Database documentation

---

## 6. Database Organization

**database/install/** - Installation SQL files  
**database/migrations/** - Version-specific migrations  
**database/migrations/legacy/** - Legacy migration scripts  

**Rules:**
- All SQL files use Lupopedia doctrine (no foreign keys, triggers, etc.)
- Migration files named by version
- TOON files for structured data exchange

---

## 7. Module Organization

Each module is self-contained with standard directory structure and module-specific documentation.

---

## 8. Legacy Code Organization

**legacy/** - Development reference only; not included in production.

---

## 9. Dialog File Organization

**dialogs/** - All dialog files (MANDATORY location).  
See [Dialog Doctrine](DIALOG_DOCTRINE.md) for complete rules.

---

## 10. Configuration File Placement

**lupopedia-config.php** - Outside web root.  
**config/** - Non-sensitive application configuration.

---

## 11. Related Documentation

- **[Dialog Doctrine](DIALOG_DOCTRINE.md)** - MANDATORY rules for dialog file placement
- **[Versioning Doctrine](../../doctrine/VERSIONING_DOCTRINE.md)** - Version management and release procedures
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Development workflow governance
- **[legacy-core/DIRECTORY_STRUCTURE.md](legacy-core/DIRECTORY_STRUCTURE.md)** - Full directory tree and detailed sections

---

**This directory structure is MANDATORY and NON-NEGOTIABLE.**

All AI agents and developers must follow this structure exactly. Any deviations must be explicitly approved and documented.

This is architectural doctrine.

---
