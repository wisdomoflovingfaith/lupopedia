---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
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
â”œâ”€â”€ lupopedia/                  # Main application directory
â”‚   â”œâ”€â”€ api/                    # API endpoints and external interfaces
â”‚   â”œâ”€â”€ lupo-admin/             # Administrative interface
â”‚   â”œâ”€â”€ lupo-content/           # User uploads and media (writable)
â”‚   â”œâ”€â”€ lupo-includes/          # Core classes, functions, and includes
â”‚   â”œâ”€â”€ lupo-agents/            # AI agent configuration and files
â”‚   â”œâ”€â”€ database/               # Database schemas, migrations, and data
â”‚   â”œâ”€â”€ docs/                   # Documentation system
â”‚   â”œâ”€â”€ modules/                # Modular components and extensions
â”‚   â”œâ”€â”€ legacy/                 # Legacy code reference (development only)
â”‚   â”œâ”€â”€ dialogs/                # Dialog files (MANDATORY location)
â”‚   â”œâ”€â”€ config/                 # Configuration files and atoms
â”‚   â”œâ”€â”€ images/                 # Static images and assets
â”‚   â”œâ”€â”€ index.php               # Front controller
â”‚   â””â”€â”€ lupopedia-load.php      # Bootstrap loader
â”œâ”€â”€ remote-index.php            # Portable entry point (optional)
â”œâ”€â”€ license.txt                 # License file
â””â”€â”€ .htaccess                   # Apache configuration

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
â”œâ”€â”€ classes/                    # Core PHP classes
â”œâ”€â”€ functions/                  # Utility functions
â”œâ”€â”€ modules/                    # Module-specific includes
â”œâ”€â”€ ui/                         # UI components and templates
â”œâ”€â”€ version.php                 # Version constants
â””â”€â”€ bootstrap.php               # Application bootstrap
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
â”œâ”€â”€ pages/                      # Admin page controllers
â”œâ”€â”€ templates/                  # Admin UI templates
â”œâ”€â”€ assets/                     # Admin-specific CSS/JS
â””â”€â”€ index.php                   # Admin front controller
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
â”œâ”€â”€ uploads/                    # User file uploads
â”œâ”€â”€ generated/                  # System-generated files
â”œâ”€â”€ cache/                      # Temporary cache files
â””â”€â”€ exports/                    # Data export files
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
â”œâ”€â”€ 0/                          # System Agent (Agent 0)
â”‚   â”œâ”€â”€ agent.php               # Agent configuration
â”‚   â”œâ”€â”€ classification.json     # Agent classification metadata
â”‚   â”œâ”€â”€ prompts/                # System prompts
â”‚   â””â”€â”€ tools/                  # Agent-specific tools
â”œâ”€â”€ 1/                          # Captain Wolfie (Agent 1)
â”œâ”€â”€ 2/                          # Agent 2
â”œâ”€â”€ ...                         # Additional agents
â”œâ”€â”€ 127/                        # Agent 127 (max agent ID)
â””â”€â”€ shared/                     # Shared agent resources
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
â”œâ”€â”€ agent.php                   # Agent configuration and metadata
â”œâ”€â”€ classification.json         # Agent classification (REQUIRED)
â”œâ”€â”€ prompts/                    # System prompts and instructions
â”‚   â”œâ”€â”€ system.txt              # Base system prompt
â”‚   â”œâ”€â”€ persona.txt             # Personality definition
â”‚   â””â”€â”€ rules.txt               # Behavioral rules
â”œâ”€â”€ tools/                      # Agent-specific tools and functions
â”œâ”€â”€ memory/                     # Agent memory files (if applicable)
â””â”€â”€ README.md                   # Agent documentation
```

---

## 5. Documentation Organization

### 5.1 docs/
**Purpose:** Complete documentation system  
**Structure:**
```
docs/
â”œâ”€â”€ README.md                   # Documentation index
â”œâ”€â”€ core/                       # Core system documentation
â”œâ”€â”€ doctrine/                   # Architectural doctrines (MANDATORY)
â”œâ”€â”€ agents/                     # Agent system documentation
â”œâ”€â”€ modules/                    # Module documentation
â”œâ”€â”€ schema/                     # Database schema documentation
â”œâ”€â”€ protocols/                  # Communication protocols
â”œâ”€â”€ architecture/               # System architecture
â”œâ”€â”€ appendix/                   # Additional documentation
â”œâ”€â”€ migrations/                 # Migration documentation
â””â”€â”€ dev/                        # Developer documentation
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
â”œâ”€â”€ install/                    # Installation SQL files
â”‚   â”œâ”€â”€ lupopedia_mysql.sql     # Main schema
â”‚   â””â”€â”€ seed_*.sql              # Seed data files
â”œâ”€â”€ migrations/                 # Database migrations
â”‚   â”œâ”€â”€ 4.0.10.sql              # Version-specific migrations
â”‚   â””â”€â”€ migration_notes.md      # Migration documentation
â”œâ”€â”€ csv_data/                   # CSV data files (optional)
â”œâ”€â”€ toon_data/                  # TOON format data files
â”œâ”€â”€ refactors/                  # Database refactor files
â””â”€â”€ generate_toon_files.py      # TOON generation script
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
â”œâ”€â”€ dialog/                     # Dialog module (v4.0.0)
â”‚   â”œâ”€â”€ README.md               # Module documentation
â”‚   â”œâ”€â”€ controllers/            # Module controllers
â”‚   â”œâ”€â”€ models/                 # Module data models
â”‚   â””â”€â”€ templates/              # Module templates
â”œâ”€â”€ craftysyntax/               # Crafty Syntax module (v4.0.0)
â””â”€â”€ [module_name]/              # Additional modules
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
â”œâ”€â”€ craftysyntax/               # Original Crafty Syntax code
â”œâ”€â”€ migration_notes/            # Migration documentation
â””â”€â”€ deprecated/                 # Deprecated code for reference
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
â”œâ”€â”€ changelog_dialog.md         # Changelog dialog (SINGLE SOURCE OF TRUTH)
â”œâ”€â”€ changelog_readme.md         # README dialog
â”œâ”€â”€ changelog_todo.md           # TODO dialog
â””â”€â”€ [threadname]_dialog.md      # Additional dialog threads
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
â”œâ”€â”€ icons/                      # System icons
â”œâ”€â”€ logos/                      # Branding assets
â”œâ”€â”€ ui/                         # UI graphics
â””â”€â”€ content/                    # Content-related images
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
- Classes: `class-[classname].php` (lowercase with hyphens)
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
- `.git/` - Version control (not present until v4.1.0)

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
- **[Versioning Doctrine](../VERSIONING_DOCTRINE.md)** - Version management and release procedures

---

**This directory structure is MANDATORY and NON-NEGOTIABLE.**

All AI agents and developers must follow this structure exactly. Any deviations must be explicitly approved and documented.

> **Directory structure reflects architectural boundaries.**  
> **Security zones are enforced through file placement.**  
> **Portability requires consistent organization.**

This is architectural doctrine.

---