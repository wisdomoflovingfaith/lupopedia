# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\VERSION_CONTROL_POLICY.md"
  file_hash: "2b318bc2ea8849bfde90c5b895a389554bac4f4927f5db7fff0419e2a11cafd8"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\VERSION_CONTROL_POLICY.md"
  file_hash: "78cc0905cd50b488f887af93abb394981bd77d2c5361196739d033b3e432697f"
  file_path_from_root: "docs\channels\doctrine\VERSION_CONTROL_POLICY.md"
  file_hash: "619d132a91e79ad2e8662b7852def7a98e4bc702459e3b57e1b4ebd8c0a4628b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for VERSION_CONTROL_POLICY.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "version_control_policymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added WOLFIE Header v3.0.0 for documentation consistency."
tags:
  categories: ["documentation", "policy", "version-control"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Version Control Policy"
  description: "Git/GitHub policy: no Git until version 3.1.0, unified versioning for Lupopedia and Crafty Syntax"
  version: "3.0.0"
  status: published
  author: "Captain Wolfie"
---

# Version Control Policy

## ⚠️ NO GIT UNTIL VERSION 3.1.0

Lupopedia does **NOT** use Git, GitHub, or any version control system until version 3.1.0.

---

## Policy Statement

### Current Status (Versions 1.0.0 - 3.0.0)

- ❌ **NO `.git` directories** anywhere in the project
- ❌ **NO Git initialization** 
- ❌ **NO GitHub repositories**
- ❌ **NO version control system integration**
- ✅ **FTP-based deployment workflow**
- ✅ **Local development only**
- ✅ **Clean file structure for FTP sync**

### Future Status (Version 3.1.0+)

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

**Lupopedia 3.0.0** and **Crafty Syntax 3.0.0** are version-locked and always released together.

### Version Locking Rules

- Both systems share the same version number
- Version increments happen simultaneously
- No independent versioning between systems
- Coordinated release cycles
- Unified changelog entries

This reflects the unified architecture where Crafty Syntax is a core module of Lupopedia, not a separate system.

### Module Versioning

While Lupopedia core doesn't use Git until 3.1.0, modules maintain their own changelogs:

### Crafty Syntax Module
- **Location:** `modules/craftysyntax/`
- **Current Version:** 3.0.0
- **Changelog:** `modules/craftysyntax/CHANGELOG.md`
- **Versioning:** Locked to Lupopedia core version
- **Status:** Integrated core module

### Dialog Module
- **Location:** `modules/dialog/`
- **Current Version:** 3.0.0
- **Changelog:** `modules/dialog/CHANGELOG.md`
- **Versioning:** Locked to Lupopedia core version
- **Status:** Integrated core module

---

## Current Versions

- **Lupopedia Core:** 3.0.0
- **Crafty Syntax Module:** 3.0.0
- **Dialog Module:** 3.0.0

---

## Workflow

### Development (Current)
1. Develop locally on localhost
2. Test changes locally
3. Upload changes via FileZilla/FTP
4. No Git/GitHub involved

### Future (Version 3.1.0+)
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

All developers and contributors should respect this policy until version 3.1.0.

---

*This policy ensures clean, FTP-compatible file structures during initial development and first public release.*