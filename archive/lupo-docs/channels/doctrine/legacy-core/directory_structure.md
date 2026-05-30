# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/legacy-core/DIRECTORY_STRUCTURE.md"
  file_hash: "1b740588a289d25d80ce4239ac2e4d8f51977ea212ccadf52594700a213784c9"
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
  file_path_from_root: "docs\channels\doctrine\legacy-core\DIRECTORY_STRUCTURE.md"
  file_hash: "10686bbdf34e8bebc5d343eebf089403d1b23c8b60f9686e966d0faa41cb4eeb"
  file_path_from_root: "docs\channels\doctrine\legacy-core\DIRECTORY_STRUCTURE.md"
  file_hash: "4d2642cfb9e6ec8fb6a3d73f5700c9a4309f9dc109da43457cda67beb240a4ec"
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
  tags: ["docs", "channels", "doctrine", "legacy-core", "directory_structuremd"]
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
  mood_RGB: "0066FF"
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

# ðŸ“ Directory Structure Doctrine

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
|   +-- lupo-admin/             # Administrative interface
|   +-- lupo-content/           # User uploads and media (writable)
|   +-- lupo-includes/          # Core classes, functions, and includes
|   +-- lupo-agents/            # AI agent configuration and files
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
- `lupo-content/` - User uploads, generated files
- `database/csv_data/` - CSV data files (if enabled)
- `database/toon_data/` - TOON data files

**Security:** Write permissions required, proper upload validation

---

## 3. Core Application Directories

### 3.1 lupo-includes/
**Purpose:** Core application logic  
**Structure:**
```
lupo-includes/
+-- classes/                    # Core PHP classes
+-- functions/                  # Utility functions
+-- modules/                    # Module-specific includes
+-- ui/                         # UI components and templates
+-- version.php                 # Version constants
+-- bootstrap.php               # Application bootstrap
```

**Rules:**
- All core classes live here
- No user-modifiable files
- Follow PSR-4 autoloading where applicable
- Use explicit includes for performance

### 3.2 lupo-admin/
**Purpose:** Administrative interface  
**Structure:**
```
lupo-admin/
+-- pages/                      # Admin page controllers
+-- templates/                  # Admin UI templates
+-- assets/                     # Admin-specific CSS/JS
+-- index.php                   # Admin front controller
```

**Rules:**
- Separate from public interface
- Authentication required for all access
- Admin-specific styling and behavior

### 3.3 lupo-content/
**Purpose:** User-generated and uploaded content  
**Structure:**
```
lupo-content/
+-- uploads/                    # User file uploads
+-- generated/                  # System-generated files
+-- cache/                      # Temporary cache files
+-- exports/                    # Data export files
```

**Rules:**
- Must be writable by web server
- Proper file validation and sanitization
- Regular cleanup of temporary files

---

## 4. Agent Directory Structure

### 4.1 lupo-agents/
**Purpose:** AI agent configuration and files  
**Structure:**
```
lupo-agents/
+-- 0/                          # System Agent (Agent 0)
|   +-- agent.php               # Agent configuration
|   +-- classification.json     # Agent classification metadata
|   +-- prompts/                # System prompts
|   +-- tools/                  # Agent-specific tools
+-- 1/                          # Captain Wolfie (Agent 1)
+-- 2/                          # Agent 2
+-- ...                         # Additional agents
+-- 127/                        # Agent 127 (max agent ID)
+-- shared/                     # Shared agent resources
```

**Rules:**
- Each agent has its own numbered directory
- Agent ID matches directory name
- Standard file structure within each agent directory
- `classification.json` required for all agents

### 4.2 Agent Directory Contents
**Standard Structure:**
```
[agent_id]/
+-- agent.php                   # Agent configuration and metadata
+-- classification.json         # Agent classification (REQUIRED)
+-- prompts/                    # System prompts and instructions
|   +-- system.txt              # Base system prompt
|   +-- persona.txt             # Personality definition
|   +-- rules.txt               # Behavioral rules
+-- tools/                      # Agent-specific tools and functions
+-- memory/                     # Agent memory files (if applicable)
+-- README.md                   # Agent documentation
```

---

## 5. Documentation Organization

### 5.1 docs/
**Purpose:** Complete documentation system  
**Structure:**
```
docs/
+-- README.md                   # Documentation index
+-- core/                       # Core system documentation
+-- doctrine/                   # Architectural doctrines (MANDATORY)
+-- agents/                     # Agent system documentation
+-- modules/                    # Module documentation
+-- schema/                     # Database schema documentation
+-- protocols/                  # Communication protocols
+-- architecture/               # System architecture
+-- appendix/                   # Additional documentation
+-- migrations/                 # Migration documentation
+-- dev/                        # Developer documentation
```

**Rules:**
- All documentation uses WOLFIE headers
- Atom references instead of hardcoded values
- Machine-readable structure
- Cross-reference mesh maintained

### 5.2 Critical Documentation Directories

**docs/doctrine/** - MANDATORY architectural rules  
**docs/core/** - Fundamental system documentation  
**docs/agents/** - Agent system specifications  
**docs/schema/** - Database documentation

---

## 6. Database Organization

### 6.1 database/
**Purpose:** Database schemas, migrations, and data  
**Structure:**
```
database/
+-- install/                    # Installation SQL files
|   +-- lupopedia_mysql.sql     # Main schema
|   +-- seed_*.sql              # Seed data files
+-- migrations/                 # Database migrations
|   +-- 3.0.10.sql              # Version-specific migrations
|   +-- migration_notes.md      # Migration documentation
+-- csv_data/                   # CSV data files (optional)
+-- toon_data/                  # TOON format data files
+-- refactors/                  # Database refactor files
+-- generate_toon_files.py      # TOON generation script
```

**Rules:**
- All SQL files use Lupopedia doctrine (no foreign keys, triggers, etc.)
- Migration files named by version
- TOON files for structured data exchange

---

## 7. Module Organization

### 7.1 modules/
**Purpose:** Modular components and extensions  
**Structure:**
```
modules/
+-- dialog/                     # Dialog module (v3.0.0)
|   +-- README.md               # Module documentation
|   +-- controllers/            # Module controllers
|   +-- models/                 # Module data models
|   +-- templates/              # Module templates
+-- craftysyntax/               # Crafty Syntax module (v3.0.0)
+-- [module_name]/              # Additional modules
```

**Rules:**
- Each module is self-contained
- Standard directory structure within modules
- Module-specific documentation required

---

## 8. Legacy Code Organization

### 8.1 legacy/
**Purpose:** Legacy code reference (development only)  
**Structure:**
```
legacy/
+-- craftysyntax/               # Original Crafty Syntax code
+-- migration_notes/            # Migration documentation
+-- deprecated/                 # Deprecated code for reference
```

**Rules:**
- Development reference only
- Not included in production deployments
- Preserved for migration and reference purposes

---

## 9. Dialog File Organization

### 9.1 dialogs/
**Purpose:** All dialog files (MANDATORY location)

**The /dialogs/ directory stores channel-level dialog logs using the naming pattern dialogs/<channel_name>_dialog.md. These are not thread logs. Threads exist only in the database.**

**Structure:**
```
dialogs/
+-- changelog_dialog.md         # Changelog dialog (SINGLE SOURCE OF TRUTH)
+-- changelog_readme.md         # README dialog
+-- changelog_todo.md           # TODO dialog
+-- [threadname]_dialog.md      # Additional dialog threads
```

**Rules:**
- ALL dialog files MUST live in `/dialogs/` directory
- No dialog files in any other location
- Newest entries at top of each file
- WOLFIE headers reflect latest entry

**See [Dialog Doctrine](../DIALOG_DOCTRINE.md) for complete rules.**

---

## 10. Configuration File Placement

### 10.1 Main Configuration
**File:** `lupopedia-config.php`  
**Location:** Parent directory of web root  
**Purpose:** Database credentials, API keys, sensitive settings  
**Security:** Outside web root, not accessible via HTTP

### 10.2 Application Configuration
**Directory:** `config/`  
**Files:**
- `global_atoms.yaml` - Global atom definitions
- Application-specific configuration files

**Rules:**
- Non-sensitive configuration can be in web root
- Sensitive configuration must be outside web root

---

## 11. Static Assets Organization

### 11.1 images/
**Purpose:** Static images and visual assets  
**Structure:**
```
images/
+-- icons/                      # System icons
+-- logos/                      # Branding assets
+-- ui/                         # UI graphics
+-- content/                    # Content-related images
```

### 11.2 CSS and JavaScript
**Location:** Within `lupo-includes/ui/` or module-specific directories  
**Rules:**
- Organized by component or module
- Minimize external dependencies
- Use LUPOPEDIA_PUBLIC_PATH for all asset URLs

---

## 12. File Naming Conventions

### 12.1 PHP Files
- Classes: `classes/[classname].php` (lowercase with hyphens)
- Functions: `[module]-functions.php`
- Controllers: `[name]-controller.php`
- Configuration: `[name]-config.php`

### 12.2 Documentation Files
- All caps for doctrine files: `DOCTRINE_NAME.md`
- Title case for guides: `Installation_Guide.md`
- Lowercase for technical specs: `database_schema.md`

### 12.3 Directory Names
- Lowercase with hyphens: `lupo-includes`
- No spaces or special characters
- Descriptive and consistent

---

## 13. Security Considerations

### 13.1 File Permissions
- **Directories:** 755 (rwxr-xr-x)
- **PHP Files:** 644 (rw-r--r--)
- **Config Files:** 600 (rw-------)
- **Writable Directories:** 755 with proper ownership

### 13.2 Access Control
- `.htaccess` files for Apache configuration
- Proper file validation for uploads
- No executable files in upload directories
- Configuration files outside web root

---

## 14. Portability Requirements

### 14.1 Path Independence
- Use `LUPOPEDIA_PUBLIC_PATH` for all URLs
- Relative paths for internal includes
- No hardcoded absolute paths
- Works in any subdirectory

### 14.2 Server Compatibility
- Standard PHP file structure
- No server-specific dependencies
- Compatible with shared hosting
- Works with various web servers (Apache, Nginx)

---

## 15. Development vs Production

### 15.1 Development-Only Directories
- `legacy/` - Reference code, not deployed
- `docs/dev/` - Developer-specific documentation
- `.git/` - Version control (not present until v3.1.0)

### 15.2 Production Deployment
- Exclude development-only directories
- Ensure proper file permissions
- Validate configuration file placement
- Test all directory access permissions

---

## 16. Enforcement Rules

### 16.1 For AI Agents
- **MUST** follow this directory structure exactly
- **MUST** place files in correct directories
- **MUST** use proper file naming conventions
- **MUST NOT** create files outside defined structure
- **MUST NOT** move core files without explicit instruction

### 16.2 For Developers
- Follow established patterns
- Document any structural changes
- Maintain security boundaries
- Test portability across environments

---

## 17. Related Documentation

- **[Dialog Doctrine](../DIALOG_DOCTRINE.md)** - MANDATORY rules for dialog file placement
- **[Subdirectory Installation Doctrine](../SUBDIRECTORY_INSTALLATION_DOCTRINE.md)** - Path handling requirements
- **[Module Doctrine](../MODULE_DOCTRINE.md)** - Module organization principles
- **[Agent Runtime](../../agents/AGENT_RUNTIME.md)** - Agent directory structure requirements
- **[Database Schema](../../schema/DATABASE_SCHEMA.md)** - Database file organization
- **[Metadata Governance](METADATA_GOVERNANCE.md)** - Metadata management and governance rules
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Development workflow governance
- **[Architecture Sync](../../architecture/ARCHITECTURE_SYNC.md)** - System architecture and component integration
- **[Single Task Patch Doctrine](../SINGLE_TASK_PATCH_DOCTRINE.md)** - One-task-per-patch workflow requirements
- **[Versioning Doctrine](../../../doctrine/VERSIONING_DOCTRINE.md)** - Version management and release procedures

---

**This directory structure is MANDATORY and NON-NEGOTIABLE.**

All AI agents and developers must follow this structure exactly. Any deviations must be explicitly approved and documented.

> **Directory structure reflects architectural boundaries.**  
> **Security zones are enforced through file placement.**  
> **Portability requires consistent organization.**

This is architectural doctrine.

---
