# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\FLIP_V2_DOCTRINE.md"
  file_hash: "a5744e432ab87d23ee67c3bb950a66f8523f1002ebe903e54ccb6a907b411ca7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLIP v2 DOCTRINE — VERSION 4.0.37"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip_v2_doctrinemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# FLIP v2 DOCTRINE — VERSION 4.0.37

**Status:** ACTIVE  
**Last Modified:** 20260224  
**Author:** Antigravity (1003)

## 1. OVERVIEW
FLIP (Forward-Looking Intelligence Protocol) v2 expands the semantic metadata layer of the Lupopedia OS. It introduces structured artifact types, collection grouping, and advanced relationship tracking.

## 2. ARTIFACT TYPES
The following artifact types are recognized and supported:

- `file`: Generic file-based artifact (default).
- `prompt`: An AI prompt template.
- `directive`: A required system or user instruction.
- `broadcast`: A multi-target semantic message.
- `status`: Current system or project state report.
- `doctrine`: Fundamental rules or procedures.
- `audit`: Compliance or security review results.
- `link`: A pointer to another local artifact (requires `link_target`).
- `http_link`: A pointer to an external resource (requires `url`).
- `collection`: An artifact that defines a group of other artifacts.

## 3. COLLECTION SYSTEM
Artifacts may belong to one or more collections for organizational and semantic grouping.

### 3.1 Definition
A `collection` artifact defines the metadata for a collection:
```yaml
wolfie.headers:
  artifact_type: "collection"
  collection_id: "v4.0.37_release"
  collection_title: "Version 4.0.37 Release Collection"
  collection_description: "All artifacts related to the 4.0.37 update."
```

### 3.2 Membership
Artifacts join a collection by adding it to their header:
```yaml
collections:
  - "v4.0.37_release"
```

## 4. HEADER/FOOTER EXAMPLES

### 4.1 Header Example (Directive)
```yaml
---
wolfie.headers:
  file_path_from_root: "docs/directives/example.md"
  system_version: "4.0.37"
  artifact_type: "directive"
  artifact_id: "DIR-42-01"
  last_modified: "20260224"
---
```

### 4.2 Footer Example (Semantic Relationships)
```yaml
---
flip.footer:
  semantic_relationships:
    - "implements:DOCTRINE-01"
    - "refined_by:CHAT-102"
  version_history:
    - "4.0.36: Initial draft"
    - "4.0.37: Expanded artifact types"
  version: "4.0.37"
  last_verified: "20260224"
---
```

## 5. VSX PARSING RULES
- Parsers MUST handle the nested `wolfie.headers` structure.
- Timestamps MUST follow YYYYMMDD format.
- `link` artifacts MUST provide `link_target`.
- `http_link` artifacts MUST provide `url`.

## 6. VSX UI EXPECTATIONS
- Display artifact type with appropriate icons.
- Provide clickable navigation for `link` and `http_link`.
- Support browsing collections via a dedicated panel.
- Show version history and semantic relationships in the artifact viewer.

## 7. BACKWARD COMPATIBILITY
FLIP v2.1 parsers SHOULD remain compatible with legacy v1 headers (un-nested keys) where possible, but v2.1 structure is preferred for all new files.
