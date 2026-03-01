# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v2.7.md"
  file_hash: "e522a3d88a9e3255588e14813dc82a069c474727c5a354eae6c9c71a0e15f173"
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
  file_path_from_root: "docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v2.7.md"
  file_hash: "d076865f068b516a158442805d46c410b0117c5290ce1f3d5ac61f592f0602da"
  file_path_from_root: "docs\archive\doctrine_revisions\WOLFIE_HEADER_DOCTRINE_v2.7.md"
  file_hash: "eb622b99586631bfc6a5b8eb6fce93d5a3e15f5e2994dddff6da8d32c2cd0fea"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Wolfie Header Doctrine v2.7 — Navigation-First with Context"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "archive", "doctrine_revisions", "wolfie_header_doctrine_v27md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Wolfie Header Doctrine v2.7 — Navigation-First with Context

## Core Principle
**Efficient navigation with essential context preservation**

Balances v2.5 speed with v2.4 metadata for Lilith's hybrid workflow.

## Header Format — v2.7 Balanced

```
⧉ WOLFIE v2.7 ⧉
nav: mech | myth | rel | docs

## NAV (P1 - grep-first)
pkg: [package]
mod: [module]  
asp: [aspect]
pur: [purpose]

## META (P2 - essential context)
cre: YYYY-MM-DDTHH:MM:SSZ
mod: YYYY-MM-DDTHH:MM:SSZ
upd: agent#N
tax: wolfie.header.taxonomy@2.3

## MYTH (P2 - creative context)
epo: wolfie-winter-2026
sig: [agent|mood]

## REL (P3 - optional)
→ [supports]
← [supported_by]
↔ [conflicts_with]

## DOCS (P4 - optional, extracted)
@requires: [deps]
@return: [returns]
@note: [notes]
@see: [related files or tables]
```

## Field Prioritization

| Priority | Section | Fields | Purpose |
|----------|---------|--------|---------|
| P1 | NAV | pkg: mod: asp: pur: | Primary grep navigation |
| P2 | META | cre: mod: upd: tax: | Essential context & tracking |
| P2 | MYTH | epo: sig: | Creative context |
| P3 | REL | → ← ↔ | Optional relationships |
| P4 | DOCS | @ fields | Extracted documentation |

## Optimized Search Patterns

```bash
# Find ALL files in package (minimal)
grep "pkg: lupopedia" **/*

# Find controllers in kernel
grep -E "pkg: lupopedia" **/* | grep "asp: controller"

# Find files modified today
grep "mod: $(date +%Y-%m-%d)" **/*

# Find files updated by cascade
grep "upd: cascade" **/*

# Find files with specific update count
grep "upd:.*#2" **/*

# Find files with authentication purpose
grep "pur:.*auth" **/*

# Find files with dependencies
grep "@requires:" **/*
```

## Comment Syntax Examples

### PHP/JS/TS/CSS:
```php
/* ⧉ WOLFIE v2.7 ⧉
   nav: mech | myth | rel | docs
   
   ## NAV
   pkg: lupopedia
   mod: kernel
   asp: controller
   pur: Main application router
   
   ## META
   cre: 2026-01-15T22:14:00Z
   mod: 2026-02-01T15:48:00Z
   upd: cascade#1
   tax: wolfie.header.taxonomy@2.3
   
   ## MYTH
   epo: wolfie-winter-2026
   sig: focused-routing
   */
```

### HTML/Vue/MD:
```html
<!-- ⧉ WOLFIE v2.7 ⧉
     nav: mech | myth | rel | docs
     
     ## NAV
     pkg: lupopedia
     mod: ui
     asp: view
     pur: Main application layout
     
     ## META
     cre: 2026-01-15T22:14:00Z
     mod: 2026-02-01T15:48:00Z
     upd: cascade#1
     tax: wolfie.header.taxonomy@2.3
     
     ## MYTH
     epo: wolfie-winter-2026
     sig: winter-ui-focus
     -->
```

## Infrastructure Header Template (Filesystem Tables)

```php
/* ⧉ WOLFIE v2.7 ⧉
   nav: mech | myth | rel | docs

   ## NAV
   pkg: lupopedia
   mod: filesystem
   asp: infra
   pur: File-system substrate table

   ## META
   cre: YYYY-MM-DDTHH:MM:SSZ
   mod: YYYY-MM-DDTHH:MM:SSZ
   upd: cascade#1
   tax: wolfie.header.taxonomy@2.3

   ## MYTH
   epo: wolfie-winter-2026
   sig: infra-guardian

   ## REL
   → lupo_filesystem_migration_log
   ← lupo_files
   ↔ lupo_file_edges

   ## DOCS
   @requires: migration subsystem
   @note: infrastructure table — migration-safe edits only
   @see: lupo_file_edges, lupo_filesystem_migration_log
*/
```

## Field Generation Rules

### NAV Fields (Required)
- **pkg**: Infer from directory patterns using v2.3 taxonomy
- **mod**: Infer from file patterns using v2.3 taxonomy  
- **asp**: Infer from filename and content
- **pur**: One-line purpose description

### META Fields (Required)
- **cre**: File creation timestamp (full UTC)
- **mod**: File modification timestamp (full UTC)
- **upd**: `agent#N` where N increments on AI updates
- **tax**: `wolfie.header.taxonomy@2.3` (fixed reference)

### MYTH Fields (Required)
- **epo**: Current creative season (wolfie-winter-2026)
- **sig**: Agent ID or creative state

### REL Fields (Optional)
- Only include if relationships exist
- Use short arrow notation for quick scanning

### DOCS Fields (Optional)
- Extract from existing PHPDoc/JSDoc only
- Preserve @requires, @return, @note, @throws
- **@see:** Used for cross-file references. When a bidirectional edge is
  detected between two files, both headers must include a matching @see:
  entry pointing to the counterpart file.

## Infrastructure Table Rules (File-System Class)

Infrastructure tables represent the physical file graph and must follow
stricter header conventions to ensure safe migrations and agent-driven updates.

### Affected Tables
- lupo_files — canonical file registry
- lupo_file_edges — file dependency graph
- lupo_filesystem_migration_log — migration history and replay ledger

### NAV Rules (Required)
pkg: lupopedia
mod: filesystem
asp: infra
pur: File-system substrate table

### META Rules (Required)
- cre: file creation timestamp (UTC)
- mod: last migration timestamp (UTC)
- upd: agent update count
- tax: wolfie.header.taxonomy@2.3

Special rule: mod must reflect the last migration affecting the table.

### MYTH Rules (Required)
epo: wolfie-winter-2026
sig: infra-guardian

### REL Rules (Optional but Recommended)
→ lupo_filesystem_migration_log
← lupo_files
↔ lupo_file_edges

### DOCS Rules (Optional)
@requires: migration subsystem
@note: infrastructure table — migration-safe edits only
@see: related infrastructure tables or migration scripts

## Bidirectional Edge Rule
When a ↔ relationship is present between two files or tables:
- Both files must include a REL ↔ entry referencing each other.
- Both files must include a DOCS @see: entry referencing each other.
- @see: must be grep-friendly and list only canonical names.

## Update Count Logic

### Initialization
- New files: `upd: cascade#1` (or appropriate agent)
- Existing files: `upd: cascade#1` on first v2.7 conversion

### Incrementation
```javascript
// Pseudo-code for update count logic
if (updated_by === 'ai' && existing_header) {
    current_count = extract_count(existing_upd_field);
    new_count = current_count + 1;
    new_upd_field = `${agent}#${new_count}`;
} else if (new_file) {
    new_upd_field = `${agent}#1`;
}
```

## Migration Path v2.4/v2.5/v2.6 → v2.7

### Phase 1: Field Mapping
| v2.4/v2.5/v2.6 Field | v2.7 Field | Conversion |
|---------------------|------------|------------|
| w3_package: | pkg: | Direct mapping |
| w3_module: | mod: | Direct mapping |
| w3_aspect: | asp: | Direct mapping |
| w3_purpose: | pur: | Direct mapping |
| w3_created_day_utc: | cre: | Direct mapping |
| w3_modified_day_utc: | mod: | Direct mapping |
| w3_updated_by: | upd: | Convert to `agent#1` |
| w3_taxonomy_version: | tax: | Convert to `wolfie.header.taxonomy@2.3` |
| w3_epoch: | epo: | Direct mapping |
| w3_signature: | sig: | Direct mapping |

### Phase 2: Conversion Script
```bash
# Convert field names
sed -i 's/w3_package:/pkg:/g' **/*.php
sed -i 's/w3_module:/mod:/g' **/*.php
sed -i 's/w3_aspect:/asp:/g' **/*.php
sed -i 's/w3_purpose:/pur:/g' **/*.php
sed -i 's/w3_created_day_utc:/cre:/g' **/*.php
sed -i 's/w3_modified_day_utc:/mod:/g' **/*.php
sed -i 's/w3_epoch:/epo:/g' **/*.php
sed -i 's/w3_signature:/sig:/g' **/*.php

# Convert updated_by to upd: agent#1
sed -i 's/w3_updated_by: cascade/upd: cascade#1/g' **/*.php

# Convert taxonomy version
sed -i 's/w3_taxonomy_version: 2\.[0-9]/tax: wolfie.header.taxonomy@2.3/g' **/*.php

# Update header version
sed -i 's/WOLFIE v2\.[0-9]/WOLFIE v2.7/g' **/*.php
```

### Phase 3: Section Reorganization
- Group fields into ## NAV, ## META, ## MYTH sections
- Preserve existing REL and DOCS sections
- Update nav: line to `nav: mech | myth | rel | docs`

## Taxonomy Integration

Use v2.3 taxonomy with pattern matching:

```json
{
  "directory_patterns": {
    "app/": {"package": "lupopedia", "subpackage": "app"},
    "kernel/": {"package": "lupopedia", "subpackage": "kernel"},
    "api/": {"package": "lupopedia", "subpackage": "kernel", "module": "routing"}
  },
  "file_patterns": {
    "*.controller.php": {"aspect": "controller"},
    "*.service.php": {"aspect": "service"},
    "index.*": {"module": "routing", "aspect": "controller"}
  }
}
```

## Success Metrics

- **Search efficiency**: 60% improvement over v2.4 (short keys)
- **Context preservation**: 80% of v2.4 metadata retained
- **Header height**: ~18 lines (balanced between v2.5 ~15 and v2.4 ~25)
- **Update tracking**: AI update counts preserved
- **Migration complexity**: Moderate (field mapping + section reorg)

## Version History

- **v2.7** (2026-02-02): Added filesystem/infrastructure doctrine, 
  infrastructure header template, @see:
- **v2.6** (2026-02-01): Balanced efficiency/context per Lilith review
- **v2.5** (2026-02-01): Navigation-first minimal headers
- **v2.4** (2026-02-01): Hybrid navigation + docs system
- **v2.2** (2026-01-15): Canonical taxonomy integration
- **v2.1** (2025-12-01): Initial mechanical fields

---

**Navigation first. Context preserved. Efficiency balanced.**