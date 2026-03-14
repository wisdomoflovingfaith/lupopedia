# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\index\README.md"
  file_hash: "2acc275e32bd72a4f5e8a063833fdca561eb19ab602a109ff2f4775ea41af26b"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\index\README.md"
  file_hash: "baeffa11b28fa25f2bf39f4ec5130d05e315b3f8b7937454039b64a67364333a"
  file_path_from_root: "lupo-docs\index\README.md"
  file_hash: "8b67b9fc9ec1f89cb7573802ef9d4e960b69a1446cd27e30fc4a4e5c648e8639"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "index", "readmemd"]
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
wolfie.headers:
  file_path_from_root: "lupo-docs/index/README.md"
  system_version: "4.0.34"
  channel_id: 42
  mood_rgb: "0088FF"
  purpose: "README for FLIP header/footer index system"
  last_modified: "20260223"
  x_lupo_forwarded: "1001:10000"
  actor_id: 1001
  lupo_agent: "kiro"

flip.footer:
  referenced_by_files:
    - "lupo-docs/status/header_lookup_build_report_20260223.md"
    - "HEADER_LOOKUP_INDEX_COMPLETE_4_0_34.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 10000
  inbound_edges:
    - "header_lookup"
    - "index_documentation"
  footnotes:
    - "Index directory README"
    - "Usage guide for FLIP index system"
  version: "4.0.34"
  last_verified: "20260223"
  last_verified_by: "kiro"
---

# FLIP Header/Footer Index System

File-based lookup system for FLIP metadata (wolfie.headers + flip.footer).

**Generated:** 20260223  
**Version:** 4.0.34  
**Agent:** KIRO IDE (actor_id 1001)  

---

## Overview

This directory contains queryable JSON indices of all FLIP headers and footers across the Lupopedia repository. The index is deterministic, regeneratable, and requires no database access.

---

## Index Files

### Main Index
- **flip_index.json** - Complete index of all entries (110 entries)

### By Actor
- **by_actor/1001.json** - KIRO IDE (45 files)
- **by_actor/1003.json** - Antigravity IDE (6 files)
- **by_actor/1000.json** - System (1 file)
- **by_actor/10000.json** - Captain Wolfie (60 files)

### By Channel
- **by_channel/42.json** - Channel 42 (60 files)
- **by_channel/420.json** - Channel 420 (3 files)

### By X_LUPO_FORWARDED
- **by_forward/1001_10000.json** - KIRO:Wolfie (60 files)
- **by_forward/1003_10000.json** - Antigravity:Wolfie (6 files)
- **by_forward/1002_10000.json** - Windsurf:Wolfie (9 files)
- **by_forward/1000_10000.json** - System:Wolfie (1 file)
- **by_forward/10000_1001.json** - Wolfie:KIRO (1 file)

### Orphans
- **orphans.json** - Files with missing headers/footers (35 files)

---

## Quick Start

### Query by Actor

```bash
# View all files by actor 1001
cat lupo-docs/index/by_actor/1001.json | jq '.entries[] | .file_path_from_root'

# Count files per actor
cat lupo-docs/index/flip_index.json | jq '.entries | group_by(.actor_id) | map({actor_id: .[0].actor_id, count: length})'
```

### Query by Channel

```bash
# View all files in channel 42
cat lupo-docs/index/by_channel/42.json | jq '.entries[] | .file_path_from_root'

# List all channels
cat lupo-docs/index/flip_index.json | jq '[.entries[].channel_id] | unique'
```

### Query by X_LUPO_FORWARDED

```bash
# View all files with x_lupo_forwarded = "1001:10000"
cat lupo-docs/index/by_forward/1001_10000.json | jq '.entries[] | .file_path_from_root'

# List all forwarding pairs
ls lupo-docs/index/by_forward/ | sed 's/_/:/' | sed 's/.json//'
```

### Find Orphans

```bash
# View all files missing footers
cat lupo-docs/index/orphans.json | jq '.orphans[] | select(.issue == "missing_footer") | .file_path'

# View all files missing headers
cat lupo-docs/index/orphans.json | jq '.orphans[] | select(.issue == "missing_header") | .file_path'
```

### Latest Activity

```bash
# Find latest activity for each actor
cat lupo-docs/index/flip_index.json | jq '.entries | group_by(.actor_id) | map({actor_id: .[0].actor_id, latest: (map(.last_modified) | max)})'

# Find most active actor
cat lupo-docs/index/flip_index.json | jq '.entries | group_by(.actor_id) | map({actor_id: .[0].actor_id, count: length}) | sort_by(.count) | reverse | .[0]'
```

---

## Regeneration

To regenerate the index:

```bash
python lupo-scripts/generate_flip_index.py
```

**Time:** ~5 seconds for 2,245 files  
**Output:** All index files + build report  
**Safety:** Read-only operation, no side effects  

---

## Index Schema

Each entry contains:

### Core Fields
- `file_path_from_root` - Evidence path
- `header_present` - Boolean
- `footer_present` - Boolean

### Header Fields
- `actor_id` - Actor identity
- `lupo_agent` - Agent key
- `x_lupo_forwarded` - Forwarding chain
- `channel_id` - Channel assignment
- `system_version` - Version tag
- `purpose` - File purpose
- `mood_rgb` - Mood color
- `last_modified` - UTC YYYYMMDD

### Footer Fields
- `referenced_by_files` - File references
- `referenced_by_channels` - Channel references
- `referenced_by_actors` - Actor references
- `inbound_edges` - Semantic edges
- `footnotes` - Additional notes
- `version` - Footer version
- `last_verified` - UTC YYYYMMDD
- `last_verified_by` - Verifier agent

---

## Statistics

**Files Scanned:** 2,245  
**Headers Found:** 75  
**Footers Found:** 110  
**Orphans Found:** 35  
**Total Entries:** 110  

**Indices:**
- 1 main index
- 4 actor indices
- 2 channel indices
- 5 x_lupo_forwarded indices
- 1 orphans index

---

## Documentation

- **Build Report:** `lupo-docs/status/header_lookup_build_report_20260223.md`
- **Summary:** `HEADER_LOOKUP_INDEX_COMPLETE_4_0_34.md`
- **Broadcast:** `lupo-channels/42/broadcasts/20260223_header_lookup_index_complete.md`
- **Script:** `lupo-scripts/generate_flip_index.py`

---

## Requirements

- Python 3.6+
- PyYAML library (`pip install pyyaml`)
- jq (optional, for query examples)

---

## Features

- ✅ No database dependency
- ✅ Deterministic and regeneratable
- ✅ Evidence-based (stores file paths)
- ✅ UTC YYYYMMDD canonical format
- ✅ jq-queryable JSON output
- ✅ Orphan detection
- ✅ Error handling and reporting

---

## Known Issues

**Encoding Errors (8 files):**
- Legacy files with non-UTF-8 characters
- Files skipped during scan
- Does not affect index integrity

**Affected Files:**
- lupo-docs/channels/doctrine/EMOTIONAL_GEOMETRY_THREE_AXIS_MODEL_2026.md
- lupo-docs/channels/agents/agent-1/README.md
- lupo-docs/channels/agents/agent-1/workflows/channel_initialization.workflow.md
- dialogs/monday_wolfie_changelog.md
- dialogs/session_2026_01_16_version_3_0_46.md

---

## Future Enhancements

- Add TOON file support (*.toon.json)
- Add PHP query interface
- Add web UI for index browsing
- Add automatic regeneration on file changes
- Add incremental index updates
- Add full-text search capability
- Add graph visualization of relationships

---

**INDEX SYSTEM READY**

For questions or issues, contact KIRO IDE (actor_id 1001) via Channel 42.

**END OF README**
