---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/16_D_LUPOPEDIA_HEADERS_EXAMPLES.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/16_D_LUPOPEDIA_HEADERS_EXAMPLES.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/headers/canonical/1026/04/lupopedia-headers.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
  artifact_type: prd
  artifact_kind: guide
  channel_key: headers
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_16_B_16_C_16_D
  title: "PRD 16: Lupopedia Headers (Examples and Reference)"
  summary: "Examples and reference implementations for Lupopedia header contract at version 4.1.4. Concrete examples, validation patterns, and usage samples."
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->

<!-- HUMAN_SEMANTIC -->
This file belongs to:
PRD Group 16 (Identity Layer: Headers, Atoms, Migration)
Cluster: 00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_16_D_EXAMPLES
Channel: headers
No default collection yet

See also:
docs/prd/00_A_FORBIDDEN_AND_WHY.md
docs/prd/00_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
docs/prd/16_C_LUPOPEDIA_HEADERS.md
Order of Operations: PRD Schema Examples Code
<!-- /HUMAN_SEMANTIC -->

# PRD 16: Lupopedia Headers (Examples and Reference)

## Current Canonical 22-Field Header (v4.1.4)

This is the current standard header structure with all 22 fields in the correct order:

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/prd/example.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/example.md"
  status: "active"
  when_updated: "20260421130000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/headers/canonical/1026/04/example.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/headers/00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_16_D_EXAMPLES"
  artifact_type: prd
  artifact_kind: guide
  channel_key: "headers"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Example Header"
  summary: "Current canonical 22-field header example."
---
```

## 1. Canonical Markdown header (v4.1.4)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/prd/16_D_LUPOPEDIA_HEADERS_EXAMPLES.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/16_D_LUPOPEDIA_HEADERS_EXAMPLES.md"
  status: "active"
  when_updated: "20260421130000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/headers/canonical/1026/04/example.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/headers/00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_16_D_EXAMPLES"
  artifact_type: prd
  artifact_kind: guide
  channel_key: "headers"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "00_A_16_B_16_C_16_D
  title: "Example Header"
  summary: "Example of canonical v4.1.4 ordered header."
---
```

## 2. Python header (v4.1.4, with shebang)

Python uses the `#` comment grid. Line 1 is the optional shebang; lines 2–26 are the 25-line block.

```python
#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.4"
#   file_path_from_root: "scripts/example.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/example.py"
#   status: "complete"
#   when_updated: "20260421130000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/example-py.toon"
#   atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/example-py"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   default_collection_id: null
#   lupopedia.schema: implementation
#   prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
#   title: "Example Python script"
#   summary: "Dense v4.1.4 header on Python using the # grid with shebang"
# ---------------------------------------------------------------------
```

## 3. PHP header (v4.1.4, # grid preferred)

PHP preferred: `#!/usr/bin/env php` (line 1), `<?php` (line 2), then the same 25-line `#` grid as Python.

```php
#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.4"
#   file_path_from_root: "includes/example.php"
#   web_path: "https://www.lupopedia.com/lupopedia/includes/example.php"
#   status: "active"
#   when_updated: "20260421130000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/example-php.toon"
#   atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/example-php"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   default_collection_id: null
#   lupopedia.schema: implementation
#   prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
#   title: "Example PHP CLI script"
#   summary: "Dense v4.1.4 header on PHP using the Python-style # grid"
# ---------------------------------------------------------------------
/**
 * Example file docblock (optional) begins on the first line after the # grid.
 */
```

## 4. Full sidecar JSON example

The `header_metadata` sidecar is derived from the YAML header. `transcript_jsonl` must match byte-for-byte. ANUBIS (`actor_id: 9`) creates it; THOTH (`actor_id: 26`) verifies it.

```json
{
  "type": "header_metadata",
  "file_path_from_root": "docs/prd/example.md",
  "channel_key": "headers",
  "trust_tier": "canonical",
  "purpose": "Header metadata sidecar derived from YAML header authority",
  "status": "active",
  "tags": ["tag-prd", "tag-headers"],
  "author": {
    "type": "actor",
    "id": 9,
    "name": "ANUBIS"
  },
  "delegation_chain": "anubis:orphan-resolution",
  "transcript_jsonl": "0/headers/example-thread",
  "edges": [],
  "footer": {
    "last_verified": "20260415120000",
    "verified_by": {
      "type": "actor",
      "id": 26,
      "name": "THOTH"
    }
  },
  "init": []
}
```

## 5. Edge specification example

Edges in the sidecar `edges` array follow the memory doctrine (PRD 38):

```json
{
  "edge_type": "supports",
  "edge_context": "doctrine",
  "edge_status": "supported",
  "edge_direction": "unidirectional",
  "to": "docs/prd/00_root_constitutional_system_requirements.md"
}
```

## 6. Transcript slug form

- canonical slug pattern: `{federation_node_id}/{channel_key}/{prd_cluster}`
- example: `0/headers/00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_16_D_EXAMPLES`
- this is a lookup slug, not an OS file path

## 7. Strict vs standard envelope

- standard mode: ordered canonical keys required
- strict mode: validator may also enforce fixed-position envelope checks

## 8. Captain's Log documentation header (v4.1.4)

Captain's Log entries use `artifact_type: documentation` and `artifact_kind: guide`. The canonical live example that established the Observer vs Active Actor Tab Doctrine (see PRD 02 "Observer vs Active Actor Tab Doctrine") uses this exact header pattern.

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "content/federation_node/0/captains_log/20260416_the_four_engine_render_ordeal.md"
  web_path: "https://www.lupopedia.com/lupopedia/content/federation_node/0/captains_log/20260416_the_four_engine_render_ordeal.md"
  status: "active"
  when_updated: "20260416130413"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/captains_log/canonical/1026/04/the-four-engine-render-ordeal.toon"
  atoms_toon: null
  transcript_jsonl: "0/captains_log/the-four-engine-render-ordeal"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "captains_log"
  federation_node_id: 0
  thread_id: "the-four-engine-render-ordeal"
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: documentation
  prd_cluster: "00_A_16_C"
  title: "Captain's Log -- The Four-Engine Render Ordeal"
  summary: "A distributed multi-engine debugging session becomes a doctrine: observer vs actor tab logic, four render attempts, and a human GIMP intervention."
---
```

**Key pattern notes for Captain's Log entries:**

| Field | Value | Rule |
|---|---|---|
| `artifact_type` | `documentation` | Not `prd`; Captain's Logs are non-normative guides |
| `artifact_kind` | `guide` | Correct kind for documentation type (see PRD 16 §4.2.2) |
| `channel_key` | `captains_log` | Dedicated channel for session narratives |
| `atoms_toon` | `null` | Logs do not require atom cross-reference |
| `lupopedia.schema` | `documentation` | Must equal `artifact_type` |
| `thread_id` | slug of the entry | Non-empty for Captain's Log artifacts; ties log to a specific discussion thread |
| `memory_toon` | `memory/captains_log/canonical/1026/04/...` | Year offset applied (2026 -> 1026) |

**Note:** The non-empty thread_id requirement applies specifically to Captain's Log artifacts. In the canonical header model, thread_id is optional (nullable) for other artifact types.

## 9. Canonical visual artifact: channel interface screenshot

`Gemini_Generated_Image_channel_interface.png` (project root) is the normative visual specification for the Observer vs Active Actor Tab Doctrine (PRD 02). It is a binary asset and therefore carries no Lupopedia YAML header block. However, it is referenced as a canonical artifact from:

- PRD 02 "Observer vs Active Actor Tab Doctrine" - "Canonical Visual Reference" subsection
- Captain's Log `20260416_the_four_engine_render_ordeal.md` - produced during the Four-Engine Render Ordeal session

**What the screenshot confirms visually:**

- Tab bar order: Cursor (yellow/active), Auggie (blue), Gemini (green), Cascade (purple), Rose (dark), Lilith (dark), Thoth (dark)
- Active Output Rule: last message row (`[GEMINI]`) renders in bright green matching the Gemini tab color
- Left panel layout: Actors (status dots), Files, Tasks - three collapsible sections
- One-column chronological feed with per-actor row background colors

**Why no header:** Binary image files are out of scope for YAML header doctrine (PRD 16 §2 "Out of scope: generated exports, binaries"). The screenshot is referenced by filename from PRD and Captain's Log artifacts.

## 10. Cross-references

- **PRD 02 "Observer vs Active Actor Tab Doctrine"** - defines the Observer/Active split. The header pattern in section 8 and screenshot in section 9 are the canonical artifacts for that doctrine's live proof-of-concept.
- **PRD 16 §4.2.2** - normative `artifact_type` / `artifact_kind` cross-field table; `documentation` + `guide` is a listed valid combination.
- **PRD 16 §2** - out-of-scope rule for binaries; explains why the screenshot carries no YAML header.

## 11. Machine-Readable Payload Pattern (JSON/TOON)

Machine-readable files (.json, .jsonl, .toon, .atom.toon) should NOT have YAML headers. Instead, use a companion .md file.

### Correct Pattern:

**Companion .md file (with header):**
```markdown
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "memory/doctrine/canonical/filename-normalization-session-2026-04-20.md"
  web_path: "https://www.lupopedia.com/lupopedia/memory/doctrine/canonical/filename-normalization-session-2026-04-20.md"
  status: "active"
  when_updated: "20260420060000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/doctrine/canonical/filename-normalization-session-2026-04-20.toon"
  atoms_toon: null
  transcript_jsonl: "0/doctrine/filename-normalization-session-2026-04-20"
  artifact_type: status
  artifact_kind: report
  channel_key: "doctrine"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: status
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Filename Normalization Session Report - 2026-04-20"
  summary: "Report of filename violations identified during current session with proposed corrections."
---
# Filename Normalization Session Report

See attached data file for detailed scan results.
```

**Payload .toon file (clean, no header):**
```json
{
  "scan_timestamp": "2026-04-20T06:00:00Z",
  "total_violations": 7,
  "violations": [
    {
      "file_path": "example/File-Name.md",
      "issue": "uppercase letters and hyphens",
      "suggested": "example/file_name.md"
    }
  ]
}
```

### Why This Pattern:
- Headers belong on **authored artifacts** where human identity matters
- Payload files remain **parseable without YAML complications**
- Clear separation: metadata in .md, data in clean payload
- Referenced via `memory_toon` field in the companion header

This output complies with Lupopedia Constitutional Root Rules.


---
lupopedia.footer:
  pending_edges:
    - to: docs/prd/16_C_LUPOPEDIA_HEADERS.md
      reason: "file created in session and must be linked to PRD"
  notes:
    - "When DB is online, this file's edges must be imported into polymorphic edge table."
---
