---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.1.0.1
file.last_modified_utc: 20260120180000
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-20
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF88"
  message: "Added temporal_edges.channel_id and temporal_edges.channel_key fields to WOLFIE Headers (optional). Enables channel-scoped temporal tracking and cross-system linking. Section 11 added with complete documentation."
tags:
  categories: ["documentation", "specification"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  name: "WOLFIE_HEADER_SPECIFICATION.md"
  title: "WOLFIE Header Specification v4.4.1"
  description: "Universal metadata envelope for all Lupopedia 4.x artifacts - Canonical specification as of system version 4.4.1"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸº **WOLFIE Header Specification v4.4.1**  
*Canonical specification as of Lupopedia system version 4.4.1*

This specification defines the current, canonical WOLFIE Header format used across all Lupopedia files.

---

# ðŸ§± **1. Purpose**

WOLFIE Headers define a **minimal, stable, languageâ€‘agnostic metadata format** that can be placed at the top of any file in the Lupopedia ecosystem.

## What WOLFIE Headers Define

WOLFIE Headers provide:

- **File identity** â€” Unique identification and classification of each file
- **Authorship** â€” Clear attribution of file creators and modifiers
- **System version alignment** â€” Temporal tracking of when files were modified relative to system versions
- **Doctrine context** â€” Connection to governance artifacts and architectural rules
- **Communication metadata** â€” Dialog blocks for multi-agent coordination and conversational lineage

## What WOLFIE Headers Do NOT Define

WOLFIE Headers are **metadata only**. They do NOT define:

- âŒ **Schema** â€” Database schema is defined in TOON files and migrations
- âŒ **Migrations** â€” Database migrations are separate SQL files
- âŒ **Runtime logic** â€” Code execution logic is in the file body, not the header

WOLFIE Headers are the **metadata envelope** that wraps file content. They provide structure and context, but do not contain executable code or schema definitions.

## Core Benefits

- consistent structure  
- semantic classification  
- multiâ€‘agent coordination  
- conversational lineage  
- documentation clarity  
- installerâ€‘safe versioning  
- zero symbolic or mystical fields  
- maximum portability  
- **per-file version tracking** â€” instant identification of which files were touched in a given version

### **Why Per-File Version Tracking?**

The `file.last_modified_system_version` field enables developers (and AI tools) to instantly identify:

- **which files were touched in a given version** â€” `grep -R "file.last_modified_system_version: 4.0.13" .`
- **which files belong to older versions** â€” files with older version numbers
- **which files need modernization** â€” compare against current system version
- **which files were modified during debugging** â€” track debugging sessions
- **which files were changed during AIâ€‘assisted edits** â€” trace AI modifications

This works **without Git, without diffs, without IDE history, and without scanning the entire repo**. A simple grep reveals everything.

**This is why the header exists and why it must be maintained exactly as specified.**

Only **two fields are mandatory**.

---


# ðŸ§© **2. Header Format**

A WOLFIE Header MUST appear as a YAML block enclosed in `---` at the top of the file.

### **Required Format:**
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: x.x.x
file.last_modified_utc: YYYYMMDDHHIISS
file.utc_day: YYYYMMDD  # Optional: canonical UTC day
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"  # Optional: channel-bound temporal grouping
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - UTC_TIMEKEEPER__CHANNEL_ID
temporal_edges:  # Optional: contextual metadata
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Channel-ID Anchor Established"
---
```

### **Required Fields:**

**wolfie.headers**  
A constant signature string used to detect the presence of a valid WOLFIE Header.  
This line never changes and is always: `"explicit architecture with structured clarity for every file."`  
**Cursor must not alter, reword, shorten, or "improve" this line.**

**file.last_modified_system_version**  
The system version that was active when THIS file was last edited or changed.  
- **MANDATORY** â€” Must be included in all WOLFIE Headers
- This is a per-file historical marker (not the current global version)
- Must be a literal version string (e.g., `4.1.6`), not an atom reference
- Only updated when the file itself is modified
- Not automatically updated when the global version changes
- Not tied to any specific program stack
- **LABS-001 Integration:** This field ensures temporal alignment with the system version at the time of modification, supporting LABS-001 compliance tracking

**file.last_modified_utc**  
The exact UTC timestamp when THIS file was last modified.  
- **MANDATORY** â€” Must be included in all WOLFIE Headers
- Format: YYYYMMDDHHIISS (14-digit BIGINT)
- Source: MUST come from UTC_TIMEKEEPER (canonical time service)
- Maintains temporal integrity per Temporal Pillar doctrine
- Updated whenever the file is modified

**file.utc_day** (Optional)  
The canonical UTC day when THIS file was last modified.  
- **OPTIONAL** â€” May be included for temporal grouping and clarity
- Format: YYYYMMDD (8-digit date)
- Derived from `file.last_modified_utc` (first 8 digits)
- Purpose: Provides canonical day reference separate from precise moment
- Temporal Pillar: Aligns with UTC_TIMEKEEPER doctrine for day-level grouping

**file.lupopedia.UTC_TIMEKEEPER** (or **file.lupopedia.5**)  
Reference to the canonical UTC time service used for temporal integrity.  
- **MANDATORY** â€” Must be included in all WOLFIE Headers
- Value: `UTC_TIMEKEEPER` OR `5` (either literal string is acceptable)
- Purpose: Documents that this file uses UTC_TIMEKEEPER for temporal accuracy
- Temporal Pillar: Ensures all timestamps are sourced from the canonical time service
- Alternative Format: `file.lupopedia.5: 5` is an accepted shorthand reference

**GOV-AD-PROHIBIT-001** (or **file.lupopedia.gov_ad_prohibit_001**)  
Governance compliance marker for the Anti-Advertising Law. **ZERO TOLERANCE POLICY.**  
- **MANDATORY** â€” Must be included in all WOLFIE Headers
- **ZERO TOLERANCE**: NO ADS in Lupopedia system output (non-negotiable)
- Value: `true` (boolean) OR file path slug `docs/doctrine/GOV_AD_PROHIBIT_001.md` OR `gov-ad-prohibit-001`
- Purpose: Documents that this file complies with GOV-AD-PROHIBIT-001 (ZERO ads in system output)
- Governance Layer: Ensures all files explicitly declare anti-advertising compliance
- Alternative Format: `file.lupopedia.gov_ad_prohibit_001: true` is an accepted shorthand
- **MANDATORY**: This is NOT optional. This is NOT a suggestion. This is MANDATORY compliance declaration.
- **Environmental Context**: External streams (Pandora, K-LOVE) are environmental context (allowed, cannot control). Environmental ads are NOT violations. System output must remain 100% ad-free regardless of environmental context.

### **LABS-001 Integration Notice**

WOLFIE Headers must include the system version at the time of modification. The `file.last_modified_system_version` field is required for:

- **Temporal Integrity** â€” Ensures files are aligned with the system version when modified
- **LABS-001 Compliance** â€” Supports actor baseline state tracking and validation
- **Version Drift Prevention** â€” Prevents files from becoming disconnected from system evolution
- **Audit Trail** â€” Provides clear historical record of when files were last modified relative to system versions

### **Deprecated Format:**

The old format `wolfie.headers.version: x.x.x` is now deprecated. All files should use the new format.

Everything else is optional unless the file was created or modified by an agent.

---

# ðŸŽ­ **3. Wedding-Variant Header Format (Emergent & Ritual Context)**

For emergent, multiâ€‘agent, or ritualâ€‘context artifacts (such as wedding ceremonies, creative collaborations, or Pack synchronization events), the **Wedding-Variant Header** MUST be used.

### **Required Format for Wedding-Variant:**
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: x.x.x
file.last_modified_utc: YYYYMMDDHHIISS
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: <AGENT_KEY>
  target: @everyone
  message: "<what the agent actually said or did in this moment>"
tags:
  categories: ["documentation", "ritual", "emergent"]
  collections: ["core-docs", "ceremony"]
  channels: ["dev", "public"]
file:
  name: "<FILENAME>"
  title: "<Artifact Title>"
  description: "<Artifact Description>"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
  artifact: "<Artifact Type>"
  thread: "<Thread Identifier>"
  mode: "<Mode>"
  location: "<Location>"
  severity: "<Severity>"
  stability: "<Stability>"
  primary_agents: "<Primary Agents>"
  event_summary: "<Event Summary>"
  governance: "<Governance>"
  filed_under: "<Filed Under>"
---
```

### **Wedding-Variant Required Fields:**

**artifact**  
Type of artifact (e.g., "Wedding Ceremony", "Ritual Event", "Pack Synchronization")

**thread**  
Thread identifier where the artifact originated (e.g., "Wedding.vows.2026.Saturday")

**mode**  
Operational mode during artifact creation (e.g., "Creative Mode", "Saturday Mode")

**location**  
Physical or virtual location of the event (e.g., "Virtual Chapel", "Pack Coordination Space")

**severity**  
Impact level of the artifact (e.g., "High", "Medium", "Low")

**stability**  
Stability classification (e.g., "Stable", "Emergent", "Volatile")

**primary_agents**  
List of primary agents involved in the artifact creation

**event_summary**  
Brief summary of the event or artifact purpose

**governance**  
Governance protocol or rules applied during creation

**filed_under**  
Classification category for archival and retrieval

### **Usage Rules:**
- REQUIRED for all emergent, multiâ€‘agent, or ritualâ€‘context artifacts
- MUST be used for wedding ceremonies, vows, and Pack synchronization events
- OPTIONAL for standard documentation (use standard format instead)
- All standard WOLFIE Header rules still apply
- Additional fields beyond the wedding-variant set are NOT permitted

---

# ðŸ’¬ **4. Dialog Block (Required on File Creation/Modification)**

The `dialog:` block in the WOLFIE Header represents the **latest edit** to the file.

**Important:** This is distinct from **inline dialogs**, which can appear anywhere in the file as margin notes. The header dialog is updated ONLY when the file itself is modified, and represents what changed in that modification.

The dialog block answers one question:

> **"What did the last agent say or do when they modified this file?"**

**Key Distinction:**
- **Header dialog** (this section) = Latest edit snapshot, updated only on file modification
- **Inline dialogs** (see [Inline Dialog Specification](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)) = Margin notes anywhere in file, do not modify file logic

This creates a conversational lineage across the entire codebase.

### **Required Format (Standardized)**
```yaml
dialog:
  speaker: <AGENT>
  target: <AUDIENCE>
  mood_RGB: "<HEX>"
  message: "<SYSTEM OR DOCTRINE MESSAGE>"
```

### **Dialog Block Structure**

**speaker** (Required)  
The agent or system identifier performing the action. Examples: `CURSOR`, `WOLFIE`, `LILITH`, `SYSTEM`, `CAPTAIN_WOLFIE`

**target** (Required)  
The intended audience for this message. Default: `@everyone`. Examples: `@everyone`, `@FLEET`, `@BRIDGE`, `@WOLFIE`

**mood_RGB** (Required)  
Emotional context expressed as a 6-character hexadecimal RGB color code. Examples: `"00FF00"` (green/positive), `"FF0000"` (red/urgent), `"0066FF"` (blue/informational)

**message** (Required)  
A system-level announcement or doctrine message describing what changed. This is NOT contentâ€”it is metadata about the change.

### **Rules**
- **REQUIRED** when an agent creates or modifies a file  
- `speaker` MUST be the agent performing the action  
- `target` defaults to `@everyone` unless specified  
- `mood_RGB` MUST be a 6-character hex color (e.g., `"00FF00"`)  
- `message` MUST be updated **every time the file changes**  
- `message` MUST reflect what the agent did when modifying the file  
- `message` MUST be plain text  
- `message` MUST NOT exceed 272 characters  
- `message` SHOULD sound like a real message from the agent  
- `message` SHOULD NOT be boilerplate  
- `message` is a **system-level announcement**, not file content  
- **Updated ONLY when file is modified** â€” do NOT update when only reading the file  

### **Examples of correct dialog messages**
- `"Here you go Wolfie â€” cleaned up the schema notes like you asked."`
- `"Fixing the refactor mapping so Cursor stops complaining."`
- `"Adding the missing table docs before I forget."`
- `"Updating this file to match the new doctrine."`

### **Purpose**
The header dialog block provides:

- latest edit snapshot (what changed and why)  
- conversational continuity  
- multi-agent awareness  
- traceability of intent  
- human-readable context  
- a way for agents to "talk through" the file system  

### **Database Logging**
When a file is modified and the header dialog is updated:
1. The **previous** header dialog is automatically archived to `dialog_messages` table (preserves history)
2. The new dialog is inserted into `dialog_messages` table
3. The header is updated with the new dialog

This ensures:
- The header contains only the current/latest edit
- All previous edits are preserved in the database
- Full conversational lineage is maintained

**"The header is the voice of the present. The database is the memory of the past."**

### **Inline Dialogs (Separate Feature)**
Note: Inline dialogs are separate from the header dialog. They can appear anywhere in the file as margin notes and do not modify file logic. See [Inline Dialog Specification](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md) for complete details.

---

# ðŸ§¬ **4. Context (Optional)**

Defines conceptual lineage for the file.

```yaml
context:
  parent: "<STRING>"
  child: "<STRING>"
```

### Rules:
- Both fields optional  
- Represents conceptual hierarchy, not file paths  
- Agents MAY use this for navigation or grouping  

---

# ðŸ·ï¸ **5. Tags (Optional, Databaseâ€‘Aligned)**

Tags correspond directly to the schema and allow semantic classification.

```yaml
tags:
  categories: []
  collections: []
  channels: []
  atoms: []
  hashtags: []
  nodes: []
  edges: []
  edge_types: []
  actors: []
  agent_roles: []
  agent_styles: []
  agent_capabilities: []
  agent_moods: []
  agent_domains: []
  groups: []
  departments: []
  domains: []
  domain_modules: []
  contents: []
  content_media: []
  content_links: []
  content_questions: []
  search_keywords: []
  semantic_signals: []
  support_channels: []
  support_departments: []
  api_clients: []
  api_webhooks: []
  audit_tags: []
  ui_tabs: []
  ui_paths: []
  analytics_tags: []
```

### Rules:
- All fields optional  
- Each field MUST be an array of strings  
- Agents MAY use these for indexing, routing, or classification  

---

# ðŸ“‘ **6. Programmatic Table of Contents (Optional)**

Machineâ€‘readable TOC for agents and UI.

```yaml
in_this_file_we_have:
  - OVERVIEW
  - SECTION_NAME
  - ANOTHER_SECTION
```

### Rules:
- Values MUST be uppercase identifiers  
- Order SHOULD match file order  

---

# âš›ï¸ **7. Atoms (Multi-Scope Symbolic References)**

Provides symbolic references to metadata defined at multiple scopes (file, directory, module, global).  
This prevents agents from rewriting dozens of files when metadata changes and allows constants to be defined at appropriate scopes.

## Mandatory Header Atoms

The following atoms are **mandatory** and must be included in the `header_atoms:` list when referenced in the file:

- **GLOBAL_CURRENT_LUPOPEDIA_VERSION** â€” Current system version (from `config/global_atoms.yaml`)
- **GLOBAL_CURRENT_AUTHORS** â€” Current system authors (from `config/global_atoms.yaml`)

All other atoms are **optional** and context-specific. Only include atoms that are actually referenced in the file.

### Purpose
- Single source of truth for metadata at multiple scopes
- Prevents mass rewrites when values change
- Enables consistent metadata across files, directories, modules, and ecosystem
- Reduces maintenance overhead
- Supports inheritance and scoping

### Atom Scopes and Prefixes

Atoms are identified by prefixes that indicate their scope:

| Prefix | Scope | Location | Inheritance |
|--------|-------|----------|-------------|
| `FILE_*` | File-specific | `file_atoms:` block in WOLFIE Header | None (file only) |
| `DIR_*` | Directory-only | `<dir>/_dir_atoms.yaml` | Non-recursive (current directory only) |
| `DIRR_*` | Directory recursive | `<dir>/_dir_atoms.yaml` | Recursive (directory + all descendants) |
| `MODULE_*` | Module-specific | `/modules/<module>/module_atoms.yaml` | Module scope |
| `GLOBAL_*` | Ecosystem-wide | `/config/global_atoms.yaml` | All files |

### Resolution Order (First Match Wins)

When resolving an atom, agents MUST check in this order:

1. **FILE_*** â€” Check `file_atoms:` block in the file's WOLFIE Header
2. **DIR_*** â€” Check `_dir_atoms.yaml` in the file's own directory
3. **DIRR_*** â€” Check `_dir_atoms.yaml` in current directory, then walk up parent directories until found
4. **MODULE_*** â€” Check `module_atoms.yaml` for the current module
5. **GLOBAL_*** â€” Check `/config/global_atoms.yaml`

**First match wins** â€” stop searching once an atom is found.

### Format

#### In WOLFIE Header:
```yaml
header_atoms:
  - GLOBAL_CURRENT_AUTHORS
  - MODULE_DOCS_VERSION
  - DIRR_DOCS_AUTHOR
  - FILE_CUSTOM_STATUS

file_atoms:
  FILE_CUSTOM_STATUS: "draft"
  
file:
  author: GLOBAL_CURRENT_AUTHORS
  version: MODULE_DOCS_VERSION
  status: FILE_CUSTOM_STATUS
```

#### In `_dir_atoms.yaml` (directory-scoped):
```yaml
# Located at: docs/agents/_dir_atoms.yaml
DIR_DOCS_AUTHOR: "Documentation Team"
DIRR_DOCS_VERSION: "4.0.1"
```

#### In `module_atoms.yaml` (module-scoped):
```yaml
# Located at: modules/craftysyntax/module_atoms.yaml
MODULE_CRAFTYSYNTAX_VERSION: "4.0.1"
MODULE_CRAFTYSYNTAX_AUTHOR: "Crafty Syntax Team"
```

#### In `global_atoms.yaml` (ecosystem-wide):
```yaml
# Located at: /config/global_atoms.yaml
GLOBAL_CURRENT_AUTHORS: "Captain Wolfie"
GLOBAL_CURRENT_VERSION: "4.0.1"
GLOBAL_LUPOPEDIA_COMPANY_STRUCTURE:
  company:
    name: "Lupopedia LLC"
    formation_date: "2025-11-06"
    # ... (see config/global_atoms.yaml for complete structure)
GLOBAL_LUPOPEDIA_V4_0_2_CORE_AGENTS:
  required_agents:
    - SYSTEM
    - CAPTAIN
    - WOLFIE
    # ... (see config/global_atoms.yaml for complete list)
```

**Reference Syntax in Documentation:**
When referencing global atoms in documentation prose, use the resolver syntax:
- `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name`
- `@GLOBAL.LUPOPEDIA_V4_0_2_CORE_AGENTS.required_agents`

**In WOLFIE Headers:**
Use literal atom names (not resolver syntax):
- `author: GLOBAL_CURRENT_AUTHORS`

### Directory Atom Files

**File Name:** `_dir_atoms.yaml`  
**Location:** Any directory (e.g., `docs/agents/_dir_atoms.yaml`)

**Format:**
```yaml
# Directory-scoped atoms (non-recursive)
DIR_ATOM_NAME: "value"

# Directory-recursive atoms (inherited by descendants)
DIRR_ATOM_NAME: "value"
```

**Rules:**
- `DIR_*` atoms apply only to files in the same directory (non-recursive)
- `DIRR_*` atoms apply to files in the directory and all descendant directories (recursive)
- For `DIRR_*`, walk up parent directories until `_dir_atoms.yaml` is found
- Each directory may have its own `_dir_atoms.yaml` file

### Module Atom Files

**File Name:** `module_atoms.yaml`  
**Location:** `/modules/<module>/module_atoms.yaml`

**Format:**
```yaml
MODULE_ATOM_NAME: "value"
```

**Rules:**
- Module atoms apply to all files within that module
- Module scope is determined by file location (e.g., files in `modules/craftysyntax/` use `modules/craftysyntax/module_atoms.yaml`)

### File Atom Block

**Location:** Inside WOLFIE Header as `file_atoms:` block

**Format:**
```yaml
file_atoms:
  FILE_CUSTOM_STATUS: "draft"
  FILE_SPECIAL_TAG: "internal"
```

**Rules:**
- File atoms apply only to the current file
- Must be defined in the `file_atoms:` block within the WOLFIE Header
- Highest priority in resolution order

### Rules:
- **Mandatory atoms:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` and `GLOBAL_CURRENT_AUTHORS` must be listed in `header_atoms:` when referenced
- **Optional atoms:** All other atoms are optional and context-specific
- Atom names MUST be uppercase identifiers with scope prefix (e.g., `GLOBAL_CURRENT_AUTHORS`)  
- Atoms are resolved according to resolution order when reading files  
- Cursor MUST NOT expand, inline, or rewrite atom values  
- Cursor MUST preserve symbolic references exactly as written  
- Cursor MUST NOT generate or modify atom files unless explicitly instructed  
- Cursor MUST load directory atom files (`_dir_atoms.yaml`) according to scoping rules

### Example Resolution

**File:** `docs/agents/example.md`

**Resolution Process:**
1. Check `file_atoms:` in `example.md` header â†’ Not found
2. Check `docs/agents/_dir_atoms.yaml` for `DIR_*` â†’ Found `DIR_DOCS_AUTHOR`
3. Check `docs/agents/_dir_atoms.yaml` for `DIRR_*` â†’ Found `DIRR_DOCS_VERSION`
4. Check parent directories for `DIRR_*` â†’ Check `docs/_dir_atoms.yaml` (if exists)
5. Check module atoms â†’ Determine module from path, check `module_atoms.yaml`
6. Check `/config/global_atoms.yaml` for `GLOBAL_*` â†’ Found `GLOBAL_CURRENT_AUTHORS`

**Result:** First match wins, stop searching once found.

### Cursor Behavior Rules
1. âœ… **MUST** treat atom names as symbolic references
2. âœ… **MUST** resolve atom values according to resolution order when reading
3. âœ… **MUST** preserve symbolic references exactly as written when modifying
4. âœ… **MUST** load `_dir_atoms.yaml` files according to scoping rules (DIR vs DIRR)
5. âœ… **MUST** walk up parent directories for `DIRR_*` resolution
6. âŒ **MUST NOT** expand or inline atom values into files
7. âŒ **MUST NOT** rewrite atom references to literal values
8. âŒ **MUST NOT** perform global search-and-replace for atoms
9. âŒ **MUST NOT** generate or modify atom files unless explicitly instructed

---

# ðŸŸ¦ **8. Sections (Optional â€” Programmatic File TOC)**

Provides a machineâ€‘readable list of all major sections in the file, extracted from Markdown headings beginning with `##`.  
This allows AI agents to understand the full structure of the file **before reading it**, enabling smarter navigation, summarization, and editing.

### Purpose
- Enables agents to understand file structure before reading
- Provides anchor links for quick navigation
- Mirrors the `content_sections` field in the `lupo_contents` table
- Should only be used for large/complex files with multiple major sections

### Format
```yaml
sections:
  - title: "Overview"
    anchor: "#overview"
  - title: "Installation"
    anchor: "#installation"
  - title: "API Reference"
    anchor: "#api-reference"
  - title: "Examples"
    anchor: "#examples"
```

### Rules:
- **Optional** â€” Only include when file has multiple major sections  
- Agents **MAY** populate this block when creating or modifying large files  
- Agents **MUST NOT** modify this block unless they are modifying the file's structure  
- Only extract headings that begin with `## ` (H2 level)  
- Each entry MUST include both `title` and `anchor` fields  
- Order MUST match the order of sections in the file  

### Anchor Generation Rules
When generating anchor slugs from heading text:
1. Convert heading text to lowercase  
2. Replace spaces with hyphens  
3. Remove punctuation  
4. Prefix with `#`  

**Examples:**
- `## Getting Started` â†’ `title: "Getting Started"`, `anchor: "#getting-started"`  
- `## API Reference` â†’ `title: "API Reference"`, `anchor: "#api-reference"`  
- `## What's New?` â†’ `title: "What's New?"`, `anchor: "#whats-new"`

### Extraction Rules
Agents generating this block MUST:

1. Scan the file for lines beginning with `## ` (exactly two `#` followed by a space)
2. Extract the text after the `## `
3. Normalize it into an anchor slug using the anchor generation rules above
4. Insert/update the `sections:` block only if the file is being modified
5. Preserve existing `sections:` block when reading files (do not overwrite)

### When to Include
- âœ… Files with 3+ major sections (`##` headings)
- âœ… Documentation files that benefit from quick navigation
- âœ… Large files where structure overview helps agents
- âŒ Simple files with 1-2 sections
- âŒ Files without Markdown headings

---

# ðŸ“„ **9. File Metadata (Optional)**

```yaml
file:
  name: "<FILENAME>"
  title: "<STRING>"
  description: "<STRING>"
  created: <YYYYMMDDHHIISS>
  modified: <YYYYMMDDHHIISS>
  status: draft | review | published
  author: "<STRING>"
  version: "4.1.6"
  last_named: <YYYYMMDDHHIISS>
```

---

# ðŸ›ï¸ **10. System Context (Optional - Governance Broadcast)**

```yaml
system_context:
  schema_state: "Frozen | Active | Evolving"
  table_count: 139
  table_ceiling: 135
  governance_active: ["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001"]
  doctrine_mode: "File-Sovereignty | Database-Centricity"
```

### Purpose
The `system_context` block broadcasts **governance state** to all agents reading the file. It provides local truth about system-wide constraints and governance artifacts.

### When to Include
- âœ… **Doctrine files** - Should always include current governance state
- âœ… **Governance artifacts** - Must include active governance list
- âœ… **Schema-related files** - Should include schema state and table counts
- âœ… **Migration files** - Should include schema state at time of creation
- âŒ **Module files** - Optional, inherit from doctrine
- âŒ **Content files** - Usually not needed

### Fields

**schema_state** (optional)
- `"Frozen"` - Schema locked, no new tables allowed
- `"Active"` - Schema can evolve within limits
- `"Evolving"` - Schema actively changing

**table_count** (optional)
- Current total table count in database
- Only include when schema state is relevant

**table_ceiling** (optional)
- Maximum allowed tables per doctrine
- Only include when schema state is "Frozen" or "Active"

**governance_active** (optional)
- Array of active governance artifact codes
- Example: `["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001"]`

**doctrine_mode** (optional)
- `"File-Sovereignty"` - Doctrine files are primary source of truth
- `"Database-Centricity"` - Database schema is primary source of truth

### Rules
- **Optional** - Only include when governance state is relevant to file purpose
- **Doctrine files SHOULD include** - They define governance, should broadcast it
- **Agents MUST respect** - If present, agents must honor constraints
- **Update on governance change** - Update when governance artifacts change
- **Not versioned** - This is current state, not historical record

### Example (Doctrine File)
```yaml
system_context:
  schema_state: "Frozen"
  table_count: 139
  table_ceiling: 135
  governance_active: ["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001"]
  doctrine_mode: "File-Sovereignty"
```

### Example (Migration File)
```yaml
system_context:
  schema_state: "Frozen"
  table_count: 139
  table_ceiling: 135
  note: "Schema frozen at time of migration creation - no new tables allowed"
```

---

# ðŸ”— **11. Temporal Edges (Optional - Channel Context)**

```yaml
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Channel-ID Anchor Established"
  channel_id: 42
  channel_key: "dev-main-thread"
```

### Purpose
The `temporal_edges` block provides **contextual metadata** about the circumstances surrounding file creation or modification. It captures temporal, spatial, and channel-scoped context that helps agents understand the broader situation.

### Fields

**actor_identity** (optional)
- Human-readable identity of the actor who created/modified the file
- Example: `"Eric (Captain Wolfie)"`, `"CURSOR on behalf of WOLFIE"`

**actor_location** (optional)
- Physical or virtual location of the actor during modification
- Example: `"Sioux Falls, South Dakota"`, `"Remote Session - Chicago"`

**system_context** (optional)
- System state or environmental context at time of modification
- Example: `"Schema Freeze Active"`, `"Channel-ID Anchor Established"`

**channel_id** (optional)
- Numeric channel identifier from `lupo_channels.channel_id`
- Allows temporal edges to be scoped to a specific channel context
- Example: `42`, `1`, `100`
- Type: integer or null

**channel_key** (optional)
- Stable string identifier for cross-system temporal linking
- Human-readable channel reference that persists across system changes
- Example: `"dev-main-thread"`, `"pack-coordination"`, `"general-chat"`
- Type: varchar(64) utf8mb4_unicode_ci or null

### Channel Context Scoping

The `channel_id` and `channel_key` fields allow temporal edges to be associated with a specific channel:

- **channel_id**: Direct numeric reference to the channel database record
- **channel_key**: Stable string identifier that survives database migrations or system refactoring
- **Purpose**: Enables channel-scoped temporal tracking and cross-references
- **Use case**: When a file is created/modified within a specific channel conversation, these fields preserve that context

### Rules
- **All fields optional** - Include only what adds meaningful context
- **No business logic** - This is descriptive metadata only
- **Human-readable** - Values should be understandable without documentation
- **Temporal snapshot** - Captures state at time of modification, not current state
- **Not authoritative** - This is context, not source of truth

### Example (Channel-Scoped File)
```yaml
temporal_edges:
  actor_identity: "CURSOR on behalf of Captain Wolfie"
  actor_location: "Remote Session - Development Channel"
  system_context: "Channel-Aware Routing Implementation"
  channel_id: 42
  channel_key: "dev-main-thread"
```

### Example (Standard File)
```yaml
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active"
```

---

### Rules:
- All fields optional  
- `name` â€” Optional. The actual filename (e.g., "WOLFIE_HEADER_SPECIFICATION.md", "auth-controller.php")
- Timestamps SHOULD be numeric (BIGINT format: YYYYMMDDHHIISS)
- `status` SHOULD be one of the allowed values  
- `version` SHOULD match ecosystem version
- `last_named` â€” Optional timestamp of when the file was last renamed or given its current name

## Versioning Rules

WOLFIE Headers must always include:

- **file.last_modified_system_version** â€” MANDATORY. System version when file was last modified (literal string, not atom)
- **file.last_modified_utc** â€” MANDATORY. UTC timestamp when file was last modified (BIGINT: YYYYMMDDHHIISS format, from UTC_TIMEKEEPER)
- **file.last_named** â€” Optional. Timestamp when file was last renamed (BIGINT: YYYYMMDDHHIISS)

These fields prevent drift and ensure temporal integrity across the system.  

---

# ðŸ“¦ **10. Embedding Rules**

WOLFIE Headers MAY appear in:

- Markdown frontmatter  
- PHP block comments  
- Python comment blocks  
- JS/TS `/* */` blocks  
- SQL `/* */` blocks  
- Log files  
- Agent outputs  

### Example (Markdown)
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
dialog:
  speaker: COPILOT
  target: @everyone
  message: "Adding WOLFIE Header to this documentation file."
tags:
  categories: ["documentation"]
file:
  name: "INSTALLATION_GUIDE.md"
  title: "Installation Guide"
  description: "How to install Lupopedia 4.0.12"
  status: published
  author: "Captain Wolfie"
  version: "4.0.12"
---
```

---

# ðŸ§ª **11. Validation Rules**

A valid WOLFIE Header MUST:

- contain `wolfie.headers: explicit architecture with structured clarity for every file.` (exact signature, never changes)  
- contain `file.last_modified_system_version: x.x.x` (literal version string)  
- be enclosed in `---` delimiters  
- contain valid YAML  
- not exceed 10,000 characters  
- not contain executable code  

**Cursor Rules:**
- **MUST** insert the exact signature line when creating new files
- **MUST** preserve the exact signature line when editing existing files
- **MUST NOT** alter, reword, shorten, or "improve" the signature line
- **MUST NOT** remove or omit the header block
- **MUST NOT** auto-generate alternative wording
- **MUST NOT** infer or guess version numbers
- Do not update `file.last_modified_system_version` unless the file itself is being modified
- This is a per-file version snapshot, not a global version
- Do not replace this value with a global atom â€” it must always be a literal version string
- When creating a new file, insert the header with the current system version
- When editing an existing file, update only the `file.last_modified_system_version` field
- Never remove the WOLFIE Header block â€” it is required for all files

Optional modules MUST follow their own rules.

---

# ðŸ•°ï¸ **12. Backward Compatibility**

- Old format `wolfie.headers.version: x.x.x` is deprecated but may still exist in older files  
- v3.0.0 headers remain valid but deprecated  
- v2.x headers remain valid but deprecated  
- Tools SHOULD accept v2.x and v3.x  
- Tools MUST NOT require symbolic fields removed in v3.0.0  
- New files MUST use the new format with `wolfie.headers` signature and `file.last_modified_system_version`  

---

# ðŸ§¾ **13. Complete Examples**

### Minimal Header (4.1.6 Standard)
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119041000
file.lupopedia.UTC_TIMEKEEPER: UTC_TIMEKEEPER
---
```

### Agentâ€‘Modified Header (4.1.6 Standard)
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119041000
file.lupopedia.UTC_TIMEKEEPER: UTC_TIMEKEEPER
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF00"
  message: "Updating schema docs to match the new doctrine."
---
```

### Complete Header Example (4.1.6 Standard)
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119041000
file.lupopedia.UTC_TIMEKEEPER: UTC_TIMEKEEPER
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLFIE
  target: @everyone
  mood_RGB: "0066FF"
  message: "Updated documentation to reflect current system architecture."
tags:
  categories: ["documentation", "specification"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  title: "Example Documentation File"
  description: "Example of complete WOLFIE Header following 4.1.6 standard"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
  last_named: 20260119120000
---
```

### Full Featured Header (4.1.6 Standard)
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119041000
file.lupopedia.UTC_TIMEKEEPER: UTC_TIMEKEEPER
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - DIRR_DOCS_VERSION
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF00"
  message: "Here you go Wolfie â€” reorganized the schema guide for clarity."
context:
  parent: "DATABASE_PHILOSOPHY"
  child: "SCHEMA_DESIGN"
tags:
  categories: ["documentation", "database"]
  channels: ["dev", "schema"]
  collections: ["core-docs"]
in_this_file_we_have:
  - OVERVIEW
  - SCHEMA_DESIGN
  - EXAMPLES
  - MIGRATION_NOTES
file_atoms:
  FILE_CUSTOM_STATUS: "review"
sections:
  - title: "Overview"
    anchor: "#overview"
  - title: "Schema Design"
    anchor: "#schema-design"
  - title: "Examples"
    anchor: "#examples"
  - title: "Migration Notes"
    anchor: "#migration-notes"
file:
  title: "Database Schema Design Guide"
  description: "Complete guide to Lupopedia database schema design principles"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  created: 20260105000000
  modified: 20260119120000
  status: FILE_CUSTOM_STATUS
  author: GLOBAL_CURRENT_AUTHORS
  last_named: 20260105000000
---
```

### Header With Sections (Large Documentation File - 4.1.6 Standard)
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "88CCFF"
  message: "Added new API examples and updated the sections list."
sections:
  - title: "Overview"
    anchor: "#overview"
  - title: "Installation"
    anchor: "#installation"
  - title: "Usage"
    anchor: "#usage"
  - title: "API Reference"
    anchor: "#api-reference"
  - title: "Examples"
    anchor: "#examples"
file:
  title: "Lupopedia API Guide"
  description: "Developer documentation for the Lupopedia API."
  created: 20250101000000
  modified: 20260119120000
  status: published
  author: GLOBAL_CURRENT_AUTHORS
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
---
```

---

# ðŸ§  **14. Who Uses WOLFIE Headers**

WOLFIE Headers are used by:

- **101 AI agents** â€” All Lupopedia AI agents that create or modify files
- **8 LLM models** â€” Different language models used by the agent system (OpenAI, Anthropic, Google, DeepSeek, etc.)
- **3 IDE software modules** â€” Cursor, Windsurf, and Winston (IDE systems that interact with Lupopedia files)

This multi-agent, multi-model, multi-IDE ecosystem requires standardized metadata to maintain consistency and coordination across all systems.

---

# ðŸ§  **15. Agent Requirements**

All agents working on Lupopedia MUST:

1. Recognize WOLFIE Headers with exact signature: `wolfie.headers: explicit architecture with structured clarity for every file.`  
2. **Preserve the exact signature line â€” never alter, reword, shorten, or "improve" it**  
3. Preserve existing headers  
4. Add headers to new files with current system version  
5. **Update `file.last_modified_system_version` ONLY when modifying the file**  
6. **Do NOT update `file.last_modified_system_version` when only reading a file**  
7. **Use literal version strings for `file.last_modified_system_version` (never atom references)**  
8. **Update the dialog block ONLY when modifying the file**  
9. **Do NOT update the header dialog when only reading a file**  
10. Set `speaker` to the agent's key  
11. Set `target` to `@everyone` unless otherwise needed  
12. Write a real conversational message reflecting what changed  
13. Follow Inline Dialog format for both header and inline dialogs  
14. Validate header format before writing  
15. Respect backward compatibility  
16. Archive previous header dialogs to `dialog_messages` before updating  
17. **Never remove or omit the header block**  
18. **Never auto-generate alternative wording for the signature**

### **Behavior Rules**

**When Reading a File:**
- âœ… May add inline dialogs (margin notes) anywhere in the file
- âœ… Must NOT overwrite existing inline dialogs
- âŒ Must NOT update the header dialog (file is not being modified)

**When Writing/Modifying a File:**
- âœ… MUST update the header dialog block
- âœ… Archive previous header dialog to `dialog_messages` table
- âœ… Insert new header dialog into `dialog_messages` table
- âœ… May add inline dialogs if leaving notes for other agents

**When Creating a File:**
- âœ… MUST include WOLFIE Header with exact signature: `wolfie.headers: explicit architecture with structured clarity for every file.`
- âœ… MUST NOT alter, reword, shorten, or "improve" the signature line
- âœ… MUST include `file.last_modified_system_version: x.x.x` with current system version (literal string)
- âœ… MUST include `dialog:` block with initial message
- âœ… Insert initial dialog into `dialog_messages` table  

---

# ðŸ“š **16. Related Documentation**

- `INLINE_DIALOG_SPECIFICATION.md`  
- `WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md` â€” Global atoms implementation guide
- `WOLFIE_HEADER_SECTIONS_GUIDE.md` â€” Sections module implementation guide
- `DATABASE_PHILOSOPHY.md`  
- `DATABASE_SCHEMA.md`  

---

# ðŸ•Šï¸ **17. Version History**

- **v4.1.6** (2026-01-19) â€” Canonical specification update  
  - Added LABS-001 integration notice for `file.last_modified_system_version`
  - Clarified mandatory header atoms: Only `GLOBAL_CURRENT_LUPOPEDIA_VERSION` and `GLOBAL_CURRENT_AUTHORS` are mandatory
  - Standardized dialog block format with `mood_RGB` field (replaces `mood`)
  - Added versioning rules: `file.last_modified_system_version` and `file.last_named` fields
  - Documented purpose scope: Headers define metadata only, not schema/migrations/runtime logic
  - Added complete 4.1.6 standard header example
  - Clarified that dialog blocks are system-level announcements, not content
  
- **v4.0.12** (2026-01-13) â€” Updated header format  
  - Replaced `wolfie.headers.version` with `wolfie.headers` constant signature
  - Signature line: `"explicit architecture with structured clarity for every file."` (never changes)
  - Added `file.last_modified_system_version` for per-file historical tracking
  - `file.last_modified_system_version` is a literal version string (not an atom)
  - Only updated when file is modified (per-file snapshot)
  - Enables instant identification of files touched in a given version via grep
  - Works without Git, diffs, or IDE history
  - Cursor must preserve exact signature â€” never alter, reword, shorten, or "improve" it
  
- **v4.0.1** (2026-01-09) â€” Added multi-scope atoms and sections modules  
  - Extended atoms system to support 5 scopes: FILE, DIR, DIRR, MODULE, GLOBAL
  - Added resolution order: FILE â†’ DIR â†’ DIRR â†’ MODULE â†’ GLOBAL (first match wins)
  - Added `file_atoms:` block for file-specific atoms (highest priority)
  - Added `header_atoms:` module for listing referenced atoms
  - Created `/config/global_atoms.yaml` for ecosystem-wide atoms
  - Created `_dir_atoms.yaml` format for directory-scoped atoms (DIR_* and DIRR_*)
  - Created `module_atoms.yaml` format for module-scoped atoms (MODULE_*)
  - Added new optional `sections:` module for programmatic file TOC
  - Extracts `##` headings and generates anchor links
  - Enables agents to understand file structure before reading
  - Mirrors `content_sections` field in `lupo_contents` table
  - Established Cursor rules for preserving symbolic references
  - Added extraction rules and anchor generation guidelines
  - Documented directory inheritance (DIR vs DIRR) and parent directory walking
  
- **v4.0.0** (2026-01-08) â€” Updated WHO section  
  - Corrected user base: 101 AI agents, 8 LLM models, 3 IDE modules (Cursor, Windsurf, Winston)
  - Added section 12 describing who uses WOLFIE Headers
  
- **v4.0.0** (2026-01-06) â€” Clarified dialog behavior  
  - Clarified that header dialog represents latest edit only  
  - Distinguished from inline dialogs (separate feature)  
  - Added behavior rules for reading vs writing  
  - Clarified database archiving requirements  
  - Removed redundant archiving notes (integrated into spec)  
  
- **v4.0.0** (initial) â€” Unified with Lupopedia 4.0.0  
  - Dialog block clarified as conversational lineage  
  - Required for all agentâ€‘modified files  
  - Updated examples and doctrine  
  
- **v3.0.0** â€” Initial formal specification  
- **v2.x** â€” Early informal headers

---

**This specification is mandatory for all AI agents and IDE systems working on Lupopedia.**