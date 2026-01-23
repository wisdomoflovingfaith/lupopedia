---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Added WOLFIE header to Legacy README. Enforced Dialog Thread Mapping Rule - linked to changelog_dialog.md as no dedicated dialog thread exists."
tags:
  categories: ["documentation", "legacy", "reference"]
  collections: ["core-docs"]
  channels: ["dev"]
in_this_file_we_have:
  - Legacy Code Reference Overview
  - Read-Only Reference Warning
  - Crafty Syntax 3.7.5 Codebase Documentation
file:
  title: "Legacy Crafty Syntax Code Reference"
  description: "Read-only reference documentation for legacy Crafty Syntax 3.7.5 codebase"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Legacy Crafty Syntax Code Reference

## ⚠️ CRITICAL: READ-ONLY REFERENCE CODE ⚠️

This directory contains the **EXACT** Crafty Syntax 3.7.5 codebase as distributed by:

- **Softaculous**
- **Installatron**
- **Netenberg** (as of January 2026)

---

## Purpose

This code is **NOT part of the active Lupopedia application**. It is kept here **EXCLUSIVELY** for reference while building the new Crafty Syntax 4.0.0 module for Lupopedia.

### Use Cases:
- Reference during development of the new Crafty Syntax module
- Understanding legacy patterns and functionality
- Migration reference for database structures
- Documentation of old behaviors
- Verifying auto-installer distribution contents

---

## ⚠️ DO NOT MODIFY ⚠️

**THIS CODE MUST REMAIN EXACTLY AS DISTRIBUTED BY AUTO-INSTALLERS**

- ✅ **READ ONLY** - Use for reference only
- ❌ **NO MODIFICATIONS** - Do not change any files
- ❌ **NO DELETIONS** - Do not delete any files
- ✅ **EXACT COPY** - Must remain exactly as auto-installers distribute it
- ✅ **REFERENCE ONLY** - This code does NOT run in Lupopedia

---

## Structure

The directory structure matches the exact distribution from auto-installers:

```
legacy/
├── README.md (this file)
├── DO_NOT_MODIFY.txt (protection notice)
├── .htaccess (web access prevention)
└── craftysyntax/          # Complete old Crafty Syntax 3.7.5 installation
    ├── config.php
    ├── livehelp.php
    ├── class/
    ├── admin/
    ├── images/
    ├── themes/
    ├── database/
    └── ... (all original files exactly as distributed)
```

---

## Where to Build New Code

**DO NOT build new code in `legacy/`**

The new Crafty Syntax 4.0.0 module will be built in:

- `lupo-admin/` - Admin interface and UI
- `lupo-includes/` - Core classes and functions
- `database/migrations/` - Database migration scripts

---

## Development Workflow

1. **Reference** code in `legacy/` to understand original behavior
2. **Implement** new code in `lupo-admin/` or `lupo-includes/`
3. **Do NOT modify** any files in `legacy/`
4. **Keep legacy/ untouched** - it's the baseline reference

---

## Security

- `.htaccess` file prevents web access to this directory
- This code is for development reference only
- Not included in production deployments
- Not executed by the web server

---

## Notes

- This code represents the **exact baseline** that auto-installers distribute
- It is the reference point for all migration and upgrade work
- Must remain unchanged to maintain reference integrity
- Used to verify compatibility and migration accuracy

---

*This directory is for development reference only and should not be included in production deployments.*

