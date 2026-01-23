---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added WOLFIE Header v4.0.0 for documentation consistency."
tags:
  categories: ["documentation", "policy", "version-control"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Version Control Policy"
  description: "Git/GitHub policy: no Git until version 4.1.0, unified versioning for Lupopedia and Crafty Syntax"
  version: "4.0.0"
  status: published
  author: "Captain Wolfie"
---

# Version Control Policy

## ⚠️ NO GIT UNTIL VERSION 4.1.0

Lupopedia does **NOT** use Git, GitHub, or any version control system until version 4.1.0.

---

## Policy Statement

### Current Status (Versions 1.0.0 - 4.0.0)

- ❌ **NO `.git` directories** anywhere in the project
- ❌ **NO Git initialization** 
- ❌ **NO GitHub repositories**
- ❌ **NO version control system integration**
- ✅ **FTP-based deployment workflow**
- ✅ **Local development only**
- ✅ **Clean file structure for FTP sync**

### Future Status (Version 4.1.0+)

- ✅ Git integration will be added
- ✅ GitHub repository will be created
- ✅ Version control workflows will be established
- ✅ After first public release

---

## Rationale

This policy exists for several important reasons:

### 1. FTP Deployment Compatibility
- `.git` folders interfere with FTP sync workflows
- Many hosting environments use FTP/FileZilla for deployment
- Clean directory structures simplify file transfers

### 2. Development Workflow
- Solo developer workflow doesn't require Git yet
- Local development environment is sufficient
- No team collaboration needs until public release

### 3. Release Strategy
- First public release should be polished and complete
- Git/GitHub integration happens after initial release
- Clean transition to public development model

### 4. File Structure Control
- Full control over directory structure
- No hidden Git artifacts
- Predictable file organization

---

## Unified Versioning Policy

**Lupopedia 4.0.0** and **Crafty Syntax 4.0.0** are version-locked and always released together.

### Version Locking Rules

- Both systems share the same version number
- Version increments happen simultaneously
- No independent versioning between systems
- Coordinated release cycles
- Unified changelog entries

This reflects the unified architecture where Crafty Syntax is a core module of Lupopedia, not a separate system.

### Module Versioning

While Lupopedia core doesn't use Git until 4.1.0, modules maintain their own changelogs:

### Crafty Syntax Module
- **Location:** `modules/craftysyntax/`
- **Current Version:** 4.0.0
- **Changelog:** `modules/craftysyntax/CHANGELOG.md`
- **Versioning:** Locked to Lupopedia core version
- **Status:** Integrated core module

### Dialog Module
- **Location:** `modules/dialog/`
- **Current Version:** 4.0.0
- **Changelog:** `modules/dialog/CHANGELOG.md`
- **Versioning:** Locked to Lupopedia core version
- **Status:** Integrated core module

---

## Current Versions

- **Lupopedia Core:** 4.0.0
- **Crafty Syntax Module:** 4.0.0
- **Dialog Module:** 4.0.0

---

## Workflow

### Development (Current)
1. Develop locally on localhost
2. Test changes locally
3. Upload changes via FileZilla/FTP
4. No Git/GitHub involved

### Future (Version 4.1.0+)
1. Git repository initialization
2. GitHub repository creation
3. Standard Git workflows
4. Public development model

---

## File Structure Impact

This policy means:

- Clean directory structures (no `.git` folders)
- All files are "tracked" (no .gitignore needed yet)
- Simple file organization
- FTP-friendly structure

---

## Enforcement

This policy is documented in:

- **README.md** - Main project documentation
- **CHANGELOG.md** - Version history
- **This file** - Detailed policy documentation

All developers and contributors should respect this policy until version 4.1.0.

---

*This policy ensures clean, FTP-compatible file structures during initial development and first public release.*

