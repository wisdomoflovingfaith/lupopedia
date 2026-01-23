---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.82
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - WHEELER_MODE
dialog:
  speaker: KIRO
  target: @architecture_team @developers
  mood_RGB: "0066FF"
  message: "Created standard WOLFIE Header Template with persona dialog examples and Wheeler Mode support."
tags:
  categories: ["template", "headers", "architecture", "metadata"]
  collections: ["core-templates", "wolfie-headers"]
  channels: ["dev", "architecture"]
file:
  title: "WOLFIE Header Template"
  description: "Standard template for WOLFIE headers with all metadata blocks and examples"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: template
  author: GLOBAL_CURRENT_AUTHORS
---

# WOLFIE HEADER TEMPLATE

This template provides the standard structure for WOLFIE headers across all Lupopedia files.

## Basic WOLFIE Header Structure

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.82
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: [AGENT_NAME]
  target: [TARGET_AUDIENCE]
  mood_RGB: "[HEX_COLOR]"  # Emotional tensor: strife/harmony/memory axes encoded as hex. NOT actual RGB colors.
  message: "[DESCRIPTION_OF_CHANGE]"
tags:
  categories: ["[CATEGORY1]", "[CATEGORY2]"]
  collections: ["[COLLECTION1]"]
  channels: ["[CHANNEL1]", "[CHANNEL2]"]
file:
  title: "[FILE_TITLE]"
  description: "[FILE_DESCRIPTION]"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: [draft|review|published|archived]
  author: GLOBAL_CURRENT_AUTHORS
---
```

## Dialog Block Examples

### Standard Dialog Block
```yaml
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Updated file with new functionality and documentation improvements."
```

### Persona Dialog Block Template
```yaml
dialog:
  speaker: <persona>
  target: <reader or agent>
  mood: "<emotional metadata>"
  message: |
    <literal dialog describing file state, warnings, or context>
```

### Multi-line Dialog Example
```yaml
dialog:
  speaker: LILITH
  target: @architecture_team
  mood_RGB: "9900FF"  # Emotional tensor: strife (99=high), harmony (00=low), memory (FF=high). Hex encoding only.
  message: |
    Structural analysis complete. File contains architectural patterns
    that require careful review. Implementation follows doctrine
    specifications with enhanced metadata support.
```

## Optional Metadata Blocks

### Wheeler Mode Block
For files created through emergent architecture:
```yaml
wheeler_mode:
  active: true
  reason: "File created during emergent architecture or reverse-20 workflow"
  notes:
    - "Structure emerged through iterative questioning"
    - "Architecture not predetermined at creation time"
    - "Truth collapsed by designated observer"
```

### Quantum State Block
For files with uncertainty or superposition:
```yaml
schrodingers_state:
  active: true
  possible_versions: ["[VERSION1]", "[VERSION2]"]
  truth_pending: true
  collapse_required_by: "[OBSERVER_NAME]"
  uncertainty_type: "[TYPE]"
  notes:
    - "[UNCERTAINTY_DESCRIPTION]"
  collapse_paths:
    - "If [CONDITION] → [ACTION]"
  observer_effect:
    - "State collapses when [OBSERVER] [ACTION]"
```

### Next Actions Block
For files requiring follow-up work:
```yaml
next_actions:
  - "[ACTION_ITEM_1]"
  - "[ACTION_ITEM_2]"
  - "[ACTION_ITEM_3]"
```

## Header Atoms Reference

### Standard Atoms
- `GLOBAL_CURRENT_LUPOPEDIA_VERSION` - Current system version
- `GLOBAL_CURRENT_AUTHORS` - System authors
- `WHEELER_MODE` - Wheeler mode workflow indicator

### Usage Guidelines
- Include atoms that are referenced in the file content
- Remove atoms that are not used in the file
- Ensure all atoms resolve to valid values

## Tag Categories

### Common Categories
- `["documentation", "doctrine"]` - Doctrine files
- `["template", "headers"]` - Template files
- `["architecture", "quantum"]` - Quantum architecture files
- `["migration", "version"]` - Migration documentation
- `["agent", "runtime"]` - Agent-related files

### Collections
- `["core-docs"]` - Core documentation
- `["doctrine"]` - Doctrine files
- `["templates"]` - Template files
- `["quantum-docs"]` - Quantum-related documentation

### Channels
- `["dev"]` - Development-focused
- `["architecture"]` - Architecture team
- `["quantum"]` - Quantum state management
- `["public"]` - Public documentation

## File Status Values

- `draft` - Work in progress
- `review` - Ready for review
- `published` - Finalized and active
- `archived` - Historical reference
- `template` - Template file
- `quantum` - In quantum superposition

## Best Practices

### Dialog Block Guidelines
- Use appropriate persona for the speaker
- Target the intended audience clearly
- Choose mood tensor values that reflect the emotional context (hex encoding; not literal colors)
- Keep messages concise but descriptive
- Use multi-line format for complex descriptions

### Metadata Consistency
- Update `file.last_modified_system_version` when modifying files
- Ensure dialog block reflects the actual change made
- Keep tags current and relevant
- Maintain consistent formatting and indentation

### Optional Block Usage
- Only include optional blocks when relevant
- Wheeler Mode for emergent architecture files
- Quantum State for files with uncertainty
- Next Actions for files requiring follow-up

---

**This template ensures consistent WOLFIE header structure across all Lupopedia files.**