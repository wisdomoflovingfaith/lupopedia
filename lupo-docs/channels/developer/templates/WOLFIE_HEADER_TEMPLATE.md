# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\developer\templates\WOLFIE_HEADER_TEMPLATE.md"
  file_hash: "84b1da6b6dfa692339c0b462f815c5a00031d3882150c20296e754f825ce43e0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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
  file_path_from_root: "docs\channels\developer\templates\WOLFIE_HEADER_TEMPLATE.md"
  file_hash: "0d44623875944f27d6c47610b096b1e9fe1a70b1ea76f3ca652d0c040647ca90"
  file_path_from_root: "docs\channels\developer\templates\WOLFIE_HEADER_TEMPLATE.md"
  file_hash: "05edf43f8701a9a26916cb044c719881f7d3839e118bc728d46112f322dee2c0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for WOLFIE_HEADER_TEMPLATE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "developer", "templates", "wolfie_header_templatemd"]
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
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.82
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @architecture_team @developers
  mood_RGB: "0066FF"
  message: "Created standard WOLFIE Header Template with persona dialog examples and metadata support."
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
file.last_modified_system_version: 3.0.82
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
### Usage Guidelines
- Include atoms that are referenced in the file content
- Remove atoms that are not used in the file
- Ensure all atoms resolve to valid values

## Tag Categories

### Common Categories
- `["documentation", "doctrine"]` - Doctrine files
- `["template", "headers"]` - Template files
- `["architecture", "metadata"]` - Architecture and metadata files
- `["migration", "version"]` - Migration documentation
- `["agent", "runtime"]` - Agent-related files

### Collections
- `["core-docs"]` - Core documentation
- `["doctrine"]` - Doctrine files
- `["templates"]` - Template files
- `["metadata-docs"]` - Metadata and discovery-context documentation

### Channels
- `["dev"]` - Development-focused
- `["architecture"]` - Architecture team
- `["public"]` - Public documentation

## File Status Values

- `draft` - Work in progress
- `review` - Ready for review
- `published` - Finalized and active
- `archived` - Historical reference
- `template` - Template file

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
- Optional discovery-context notes for emergent architecture files
- Quantum State for files with uncertainty
- Next Actions for files requiring follow-up

---

**This template ensures consistent WOLFIE header structure across all Lupopedia files.**
