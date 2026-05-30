---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/THREE_LAYER_METADATA_DOCTRINE.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/THREE_LAYER_METADATA_DOCTRINE.md"
  status: "active"
  when_updated: "20260420050000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/doctrine/canonical/1026/04/three-layer-metadata-doctrine.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/doctrine/three-layer-metadata"
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: "doctrine"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "three-layer-metadata-doctrine"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "THREE_LAYER_METADATA_DOCTRINE.md -- Headers, Metadata, and Footers"
  summary: "Canonical doctrine defining the three distinct metadata layers in Lupopedia: headers (identity), metadata (contextual), and footers (operational)."
---

# Three-Layer Metadata Doctrine

## Purpose

This doctrine establishes the canonical separation of metadata into three distinct layers in Lupopedia files. Each layer has a specific purpose, lifecycle, and set of rules that must be followed by all agents and tools.

## Core Principle

**If metadata describes identity, it belongs in headers; if it describes context, it belongs in metadata; if it describes tasks, it belongs in footers.**

---

## Layer 1: `lupopedia.headers` - Identity Metadata

### Purpose
- Defines the canonical identity of the file
- Contains stable, deterministic, machine-validated fields
- Required for every Lupopedia-participating file
- Treated as stable doctrine, not a scratchpad

### Characteristics
- **Required**: Must be present on every file
- **Deterministic**: Fixed field ordering (22 canonical fields)
- **Stable**: Changes only when file identity changes
- **Validated**: Machine-validated against PRD 16 rules
- **Global**: Applies to the entire file

### Valid Fields
Only the 22 canonical fields defined in PRD 16 §4.2:
1. header_format_version
2. file_path_from_root
3. web_path
4. status
5. when_updated
6. trust_tier
7. questions_toon
8. memory_toon
9. atoms_toon
10. transcript_jsonl
11. artifact_type
12. artifact_kind
13. channel_key
14. federation_node_id
15. thread_id
16. content_id
17. content_parent_id
18. content_slug
19. default_collection_id
20. lupopedia.schema
21. title
22. summary

### Forbidden in Headers
- Operational fields (orchestrator, next_action)
- Task lists
- Comments
- Workflow information
- Ephemeral data
- Agent-specific notes

---

## Layer 2: `lupopedia.metadata` - Contextual Annotations

### Purpose
- Provides section-specific semantic notes
- Contains inline, local, contextual annotations
- Describes the section where it appears, not the entire file
- Acts as semantic sticky notes embedded in the document

### Characteristics
- **Optional**: May appear anywhere in the file
- **Local**: Applies only to the surrounding content
- **Contextual**: Tied to specific paragraphs or sections
- **Flexible**: No fixed structure or validation
- **Semantic**: Carries meaning about the content

### Valid Uses
- Comments about a specific paragraph
- Dialog notes tied to a specific line
- Section-level observations
- Mood or tone notes for a specific block
- "This part needs review" annotations
- Section-specific references

### Format
```yaml
---
lupopedia.metadata:
  comments:
    - "This paragraph introduces the emotional pivot."
  dialog:
    - speaker: "Wolfie"
      line: "This is where the doctrine shifts."
  notes:
    - "Check lineage consistency for this section."
---
```

### Rules for Agents
- **MUST**: Preserve exactly where it appears
- **MUST**: Treat as local metadata, not global
- **MAY**: Add new blocks near relevant content
- **MUST NOT**: Move to headers or footers
- **MUST NOT**: Assume it applies to entire file

---

## Layer 3: `lupopedia.footer` / `lupopedia.footers` - Operational Workspace

### Purpose
- Provides workspace for agents and scripts
- Contains pending edges, next actions, and comments
- Tracks what still needs to be done
- Acts as agent scratchpad at bottom of file

### Characteristics
- **Optional**: Not required on every file
- **Operational**: Task-oriented and action-focused
- **Ephemeral**: Can be removed when resolved
- **Flexible**: No strict validation requirements
- **Workspace**: For agent-to-agent communication

### Valid Uses
- Pending edges to be added
- Next actions for maintainers
- Script detection results
- Unresolved references
- Missing metadata warnings
- Agent notes and comments

### Format Options

**Single Footer:**
```yaml
---
lupopedia.footer:
  pending_edges:
    - to: some/file.md
      reason: "Referenced in text but not linked"
  next_actions:
    - "Review this file for missing lineage"
    - "Confirm whether this belongs in PRD 07"
  comments:
    - "Script detected inconsistent naming"
    - "Needs manual review"
---
```

**Multiple Footers:**
```yaml
---
lupopedia.footers:
  - type: pending_edges
    items:
      - to: foo/bar.md
        reason: "Detected keyword match"
  - type: next_actions
    items:
      - "Add inbound edges"
  - type: comments
    items:
      - "Generated by enqueue_files.py"
---
```

### Rules for Agents
- **MAY**: Append new footer blocks
- **MAY**: Update existing footer blocks
- **MAY**: Remove footer blocks once resolved
- **MUST NOT**: Treat as canonical metadata
- **MUST NOT**: Validate footer fields
- **MUST NOT**: Enforce deterministic ordering

---

## Migration Rules

### From Headers to Proper Layers
- **orchestrator**: Move from headers to metadata (if contextual) or remove (if implicit)
- **next_action**: Move from headers to footer
- **comments**: Move from headers to metadata (if section-specific) or footer (if file-wide)

### Detection Patterns
- Headers containing operational fields should trigger migration
- Files with mixed metadata should be reorganized
- Validators should flag misplaced fields

---

## Enforcement

### Validation Rules
1. Headers must contain only the 22 canonical fields
2. Metadata blocks must not appear in headers
3. Footer content must not appear in headers
4. Each layer must be properly separated

### Agent Guidelines
1. Always identify which layer you're writing to
2. Never mix layer purposes
3. Respect existing layer boundaries
4. Use appropriate layer for your data type

---

## Examples

### Correct Structure
```markdown
---
lupopedia.headers:
  header_format_version: "4.1.3"
  # ... 21 other canonical fields
---

# Content here

---
lupopedia.metadata:
  comments:
    - "This section introduces key concepts"
---

More content

---
lupopedia.footer:
  next_actions:
    - "Add examples to this section"
---
```

### Incorrect Structure (Header Pollution)
```markdown
---
lupopedia.headers:
  header_format_version: "4.1.3"
  # ... canonical fields ...
  orchestrator: "wolfie:root"  # WRONG - operational field
  next_action:                 # WRONG - task field
    - "Fix this file"
---
```

---

## Summary

The three-layer metadata system ensures:
- **Clear separation** of concerns
- **Proper lifecycle** management for different data types
- **Agent-friendly** interfaces for different use cases
- **Maintainable** structure across 10,000+ files
- **Semantic clarity** for both humans and machines

By following this doctrine, agents and tools can work together without corrupting file identity or polluting operational workspaces.

---
lupopedia.footer:
  pending_edges:
    - to: docs/prd/16_lupopedia_headers.md
      reason: "file created in session and must be linked to PRD"
  notes:
    - "When DB is online, this file's edges must be imported into polymorphic edge table."
---
