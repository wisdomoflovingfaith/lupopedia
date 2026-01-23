---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: "CASCADE"
  target: "@everyone"
  mood_RGB: "00FF00"
  message: "Created comprehensive Lupopedia Header Profile (LHP) v1.0 - the official Lupopedia-specific extension of WHS with complete metadata specification for semantic OS integration"
tags:
  categories: ["documentation", "specification", "standards"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev", "standards"]
file:
  title: "Lupopedia Header Profile (LHP) v1.0"
  description: "The official Lupopedia-specific extension of WHS with expressive metadata for multi-agent clarity and semantic graph integration"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🟦 **Lupopedia Header Profile (LHP) v1.0**  
*The official Lupopedia-specific extension of WHS with expressive, doctrine-driven metadata for multi-agent clarity.*

---

## 🟩 1. Purpose

The **Lupopedia Header Profile (LHP)** defines how Lupopedia extends the **Universal Wolfie Header Specification (WHS)** with additional metadata required for:

- **doctrine** - Enforcing Lupopedia's mandatory rules and principles  
- **content** - Structuring semantic content and navigation  
- **semantic graph generation** - Building the knowledge graph relationships  
- **multi-agent collaboration** - Coordinating between 101+ AI agents  
- **rewrite safety** - Preventing drift during automated modifications  
- **federation** - Supporting distributed node synchronization  
- **archival integrity** - Maintaining long-term survivability  

Where WHS is universal and minimal, LHP is **expressive, structured, and doctrine‑driven**.

LHP applies to:
- Lupopedia core files  
- Crafty Syntax imports  
- Wolfette Engine documentation  
- AGI OS documentation  

**Canonical Definition:** https://lupopedia.com/what/LHP

### Architecture Layers

- **WHS** = Universal minimal core (2 required fields)  
- **LHP** = Lupopedia's official extension layer (additional required + optional fields)  

LHP **does not modify** WHS required fields. LHP **adds** structured metadata for Lupopedia's semantic operating system.  

---

## 🔗 2. Relationship to WHS

### Inheritance Model

LHP **inherits** all WHS required fields:
- `wolfie.headers` (immutable signature)  
- `file.last_modified_system_version` (version snapshot)  

### Extension Model

LHP **adds** additional required and optional fields while maintaining WHS compatibility:

```yaml
# WHS Core (inherited)
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13

# LHP Extensions
dialog: {...}           # Required in LHP
authorship: {...}       # Required in LHP
sections: [...]          # Required in LHP
file: {...}            # Optional but common
tags: {...}            # Optional but common
placement: {...}       # Optional
lineage: {...}         # Optional
federation: {...}      # Optional
header_atoms: [...]     # Optional
```

### Namespace Separation

LHP fields are clearly separated to avoid layer bleed:
- WHS fields come first (universal core)  
- LHP fields follow (Lupopedia-specific)  
- No field name conflicts between layers  

### Ecosystem Specificity

LHP is **not universal** — it is Lupopedia‑specific:
- Other ecosystems may create their own profiles  
- All profiles must respect WHS core immutability  
- Profiles may define different extension fields  

---

## 📋 3. Required LHP Fields

### 3.1 `dialog` (Required in LHP)

LHP **requires** a dialog block for multi-agent coordination.

#### Format

```yaml
dialog:
  speaker: "<YOU — the AI agent or actor writing this>"
  target: "@<WHO THIS ADDRESSES>"
  mood_RGB: "<OPTIONAL RGB VALUE>"
  message: "<MESSAGE>"
```

#### Field Rules

**`speaker`** (Required)
- The agent or human modifying the file
- Examples: "CURSOR", "CASCADE", "Wolfie", "Developer Name"

**`target`** (Required)  
- The intended recipient of the dialog
- Examples: "@Wolfie", "@Cursor", "@DIALOG", "@everyone"

**`mood_RGB`** (Optional)
- RGB color value calculated from dialog content
- Format: "RRGGBB" (e.g., "00FF00" for green)
- May be omitted if not needed

**`message`** (Required)
- Short, single-line summary of the action or intent
- Should be concise but informative

#### Dialog History Rules

- **Only the latest dialog appears in the header**
- **Older dialog entries go into `<filename>_dialog.md`**
- **Newest entries go at the TOP** of the dialog history file
- Each entry includes timestamp, speaker, target, mood, and message

### 3.2 `authorship`

Defines the creation and modification lineage.

#### Format

```yaml
authorship:
  author: "Wolfie (Eric Robin Gerdes)"
  agent: "<agent_name_or_null>"
```

#### Field Rules

**`author`** (Required)
- Always "Wolfie (Eric Robin Gerdes)" for Lupopedia files
- Immutable once set

**`agent`** (Optional)
- Name of AI agent involved in creation/modification
- `null` if no AI agent involvement
- Examples: "CURSOR", "CASCADE", "WOLFIE"

### 3.3 `sections`

A list of top-level sections in the file.

#### Format

```yaml
sections:
  - "Purpose"
  - "Relationship to WHS"
  - "Required LHP Fields"
  - "Optional LHP Fields"
```

#### Rules

- Must reflect actual file structure
- Must update when sections change
- Must use exact section names (case-sensitive)
- Order should match document organization

---

## 🔧 4. Optional LHP Fields

### 4.1 `file` block

File metadata and identification.

#### Format

```yaml
file:
  name: "<filename>"
  title: "<human-readable title>"
  description: "<description>"
  version: "<semantic_version_or_atom>"
  status: draft|published
```

#### Field Rules

**`name`** (Optional)
- The filename without extension
- Used for automated file identification

**`title`** (Optional)
- Human-readable title for display
- May differ from filename

**`description`** (Optional)
- Brief description of file purpose
- Used in search and indexing

**`version`** (Optional)
- Semantic version or atom reference
- Examples: "1.0.0", "GLOBAL_CURRENT_LUPOPEDIA_VERSION"

**`status`** (Optional)
- `draft` - Work in progress
- `published` - Final and stable

### 4.2 `tags`

Categorization and routing metadata.

#### Format

```yaml
tags:
  categories: ["documentation", "specification"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev", "standards"]
```

#### Field Rules

**`categories`** (Optional)
- Content type classification
- Examples: "documentation", "code", "configuration"

**`collections`** (Optional)
- Document collection membership
- Examples: "core-docs", "api-reference"

**`channels`** (Optional)
- Distribution channels
- Examples: "public", "dev", "internal"

### 4.3 `placement`

Content placement and navigation.

#### Format

```yaml
placement:
  collection: "<collection_name>"
  navigation_tabs: ["tab1", "tab2"]
```

#### Field Rules

**`collection`** (Optional)
- Primary collection for this content
- Must match existing collection names

**`navigation_tabs`** (Optional)
- Navigation tabs where this content appears
- Must match defined navigation structure

### 4.4 `lineage`

Source and semantic radius information.

#### Format

```yaml
lineage:
  source: local|crafty_syntax|external
  radius: 0|1|2
```

#### Field Rules

**`source`** (Optional)
- `local` - Created within Lupopedia
- `crafty_syntax` - Imported from Crafty Syntax
- `external` - Imported from external source

**`radius`** (Optional)
- `0` - Core content
- `1` - First-degree semantic expansion
- `2` - Second-degree semantic expansion

### 4.5 `federation`

Federation and canonical URL information.

#### Format

```yaml
federation:
  canonical_url: "<absolute_url>"
```

#### Field Rules

**`canonical_url`** (Optional)
- Absolute URL for canonical reference
- Used for cross-node synchronization
- Must be reachable from federated nodes

### 4.6 `header_atoms`

Global atom references for header standardization.

#### Format

```yaml
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - GLOBAL_WHS_VERSION_1_0
```

#### Field Rules

- Must reference existing global atoms
- Used for automated header validation
- Enables consistent metadata across files

### 4.7 `meta_governance`

Governance and heterodox management metadata.

#### Format

```yaml
meta_governance:
  heterodox_eligible: true|false
  proposal_rights: [...]
  council_role: ...
  last_proposal_id: ...
```

#### Field Rules

**`heterodox_eligible`** (Optional)
- Indicates if this file may be subject to heterodox proposals
- `true` for experimental or policy files
- `false` for core doctrine files

**`proposal_rights`** (Optional)
- List of agents or roles authorized to propose changes
- Examples: ["CURSOR", "CASCADE", "Wolfie"]
- Empty array indicates no restrictions

**`council_role`** (Optional)
- Role in governance council for this file type
- Examples: "member", "observer", "moderator"

**`last_proposal_id`** (Optional)
- Identifier of most recent governance proposal
- Used for tracking proposal history
- Format: "PROP-YYYY-MM-DD-NNN"

---

## 🕸️ 5. Semantic Graph Integration

### Graph Edge Generation

LHP metadata generates semantic graph edges through:

**Content Relationships**
- `placement.collection` → collection membership edges
- `lineage.source` → source attribution edges  
- `federation.canonical_url` → canonical reference edges

**Agent Collaboration**
- `dialog.speaker` → agent contribution edges
- `authorship.agent` → agent creation edges

**Document Structure**
- `sections` → structural organization edges
- `tags.categories` → categorization edges

### Explicit Structure Encoding

LHP **does not infer** relationships — it **encodes explicit structure**:

- Every edge has a clear source in LHP metadata
- No implicit relationships from content analysis
- Graph structure mirrors header structure
- Changes to metadata immediately reflect in graph

### WHS as Identity Layer

WHS remains the core identity layer:
- `wolfie.headers` provides universal identification
- `file.last_modified_system_version` provides temporal identity
- LHP builds semantic identity on top of WHS foundation

---

## 🛡️ 6. Rewrite Safety Doctrine

### Header Preservation Rules

**Allowed Modifications:**
- Updating `file.last_modified_system_version` when content changes
- Updating `dialog` block with latest modification
- Adding new optional fields
- Updating `sections` when structure changes

**Forbidden Modifications:**
- Removing required WHS fields
- Modifying `wolfie.headers` signature
- Changing `authorship.author` from Wolfie
- Reordering header keys (maintain defined order)
- Removing lineage or canonical URLs
- Modifying historical dialog entries

### Dialog Update Rules

- Only the latest dialog appears in header
- Each modification must update the dialog block
- Previous dialog entries move to `<filename>_dialog.md`
- Dialog history is append-only (no deletions)

### Authorship Update Rules

- `authorship.author` is immutable once set to Wolfie
- `authorship.agent` may be updated to reflect current agent
- Authorship changes must be documented in dialog

### Section Update Rules

- `sections` must reflect actual document structure
- Section name changes require header update
- Section order changes require header update
- Missing sections must be added to header file.

---

## 📝 8. Minimal LHP Example

The smallest valid LHP header (WHS core + LHP required fields):

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
dialog:
  speaker: "CASCADE"
  target: "@everyone"
  message: "Created minimal LHP example"
authorship:
  author: "Wolfie (Eric Robin Gerdes)"
  agent: "CASCADE"
sections:
  - "Introduction"
  - "Content"
  - "meta_governance"
---
```

---

## 🎯 9. Extended LHP Example

Full example with all common LHP fields:

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
dialog:
  speaker: "CURSOR"
  target: "@everyone"
  mood_RGB: "00FF00"
  message: "Updated LHP specification with comprehensive metadata examples"
authorship:
  author: "Wolfie (Eric Robin Gerdes)"
  agent: "CURSOR"
sections:
  - "Purpose"
  - "Relationship to WHS"
  - "Required LHP Fields"
  - "Optional LHP Fields"
  - "Semantic Graph Integration"
  - "Rewrite Safety Doctrine"
  - "Drift Prevention Doctrine"
  - "Examples"
  - "Scope and Versioning"
  - "meta_governance"
file:
  name: "LUPOPEDIA_HEADER_PROFILE"
  title: "Lupopedia Header Profile (LHP) v1.0"
  description: "The official Lupopedia-specific extension of WHS with expressive metadata for multi-agent clarity and semantic graph integration"
  version: "1.0.0"
  status: "published"
  author: "Captain Wolfie"
tags:
  categories: ["documentation", "specification", "standards"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev", "standards"]
placement:
  collection: "core-docs"
  navigation_tabs: ["doctrine", "standards"]
lineage:
  source: "local"
  radius: 0
federation:
  canonical_url: "https://lupopedia.com/docs/doctrine/LUPOPEDIA_HEADER_PROFILE"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - GLOBAL_WHS_VERSION_1_0
  - GLOBAL_LHP_VERSION_1_0
meta_governance:
  heterodox_eligible: false
  proposal_rights: ["CURSOR", "CASCADE", "Wolfie"]
  council_role: "member"
  last_proposal_id: "PROP-2026-01-13-001"
---
```

---

## 🌍 10. Scope and Versioning

### Version Information

**LHP v1.0** is the January 2026 standard and establishes:
- The complete LHP field specification
- Rewrite safety and drift prevention doctrines
- Semantic graph integration patterns
- Relationship to WHS v1.0

### Extension Philosophy

LHP **extends WHS but does not modify WHS**:
- WHS required fields remain immutable
- LHP adds expressive metadata for Lupopedia's needs
- Other ecosystems may create different profiles

### Evolution Path

LHP fields may evolve over time:
- New optional fields may be added
- Existing field rules may be clarified
- Validation requirements may be enhanced
- WHS core immutability is preserved

### Official Status

LHP is the **official Lupopedia profile**:
- Required for all Lupopedia files
- Enforced by automated validation
- Supported by all Lupopedia tools and agents
- Documented as part of core doctrine

### Future Profiles

Other profiles may exist in the future:
- Ecosystem-specific extensions
- Specialized use cases
- Experimental features
- Third-party integrations

All future profiles must respect WHS core immutability.

---

## 🔗 11. Implementation Resources

- **Canonical Reference**: https://lupopedia.com/what/LHP
- **Universal Wolfie Header Specification**: `docs/doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md`
- **Mood System Doctrine**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`
- **Mood Axis Registry**: `docs/registries/MOOD_AXIS_REGISTRY.md`
- **RGB Mapping Protocol**: `docs/doctrines/COLOR_DOCTRINE.md`
- **Mood Calculation Protocol**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`
- **Thread Aggregation Protocol**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`
- **Dialog History Specification**: `docs/agents/DIALOG_HISTORY_SPEC.md`
- **Thread-Level Dialog Specification**: `docs/agents/THREAD_LEVEL_DIALOG_SPEC.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

## 🎭 12. Mood System Extensions (January 2026)

LHP v1.0 integrates with the new Mood System extensions:

### Core Components

- **Mood System Doctrine** - Vectorized, nested mood blocks with multi-axis emotional geometry
- **Mood Axis Registry** - Governed list of emotional axes preventing drift and synonyms
- **RGB Mapping Protocol** - Defines how emotional scores map to RGB colors
- **Mood Calculation Protocol** - Deterministic, governed mood computation from dialog text
- **Thread Aggregation Protocol** - Mood accumulation across dialogs with thread_summary blocks

### Integration Points

The mood system extends LHP's `dialog` block with:

- **Multi-axis emotional vectors** instead of scalar mood values
- **Nested mood structure** with primary, secondary, meta, and thread_summary blocks
- **Deterministic calculation** governed by the Mood Calculation Protocol
- **RGB color mapping** through the Color Doctrine
- **Thread-level aggregation** for conversation-wide emotional tracking

### Implementation Requirements

All LHP-compliant files must:
- **Use canonical axis names** from the Mood Axis Registry
- **Calculate mood deterministically** using the Mood Calculation Protocol
- **Map emotions to RGB** using the Color Doctrine
- **Support thread aggregation** when working with thread-level dialog
- **Validate all mood values** against axis registry definitions

These extensions provide rich emotional context while maintaining LHP's core principles of structured, doctrine-driven metadata.

---

## 🏛️ 13. Meta-Governance Extension (January 2026)

The Lupopedia Header Profile now supports an optional `meta_governance` field for files participating in heterodox proposal workflows.

### Field Structure

```yaml
meta_governance:
  heterodox_eligible: true|false
  proposal_rights: [taxonomy_mutation, doctrine_amendment]
  council_role: shadow_delegate|none
  last_proposal_id: null
```

### Purpose

This field marks files that may participate in the Heterodox Engine, including doctrinal mutation proposals, sanctioned instability cycles, and meta-governance operations.

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/LHP*
