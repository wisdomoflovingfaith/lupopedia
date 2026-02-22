# FLIP Header Integration — VSX Extension & Python Audit Tool

**Version**: 4.0.27  
**Date**: 2026-02-22  
**Purpose**: Comprehensive FLIP header parsing, navigation, and offline collaboration for Lupopedia multi-agent platform

---

## Overview

This implementation provides **both** VSX extension offline capabilities and Python-based FLIP header auditing to support the Lupopedia 4.0.27 upgrade during web interface outages.

### Components

1. **VSX Extension** (`src/providers/flipTreeProvider.ts`, `src/commands/flipCommands.ts`)
   - Hierarchical FLIP file navigation
   - Search and filtering by status/channel/thread
   - Thread simulation for offline collaboration
   - Automatic FLIP header timestamp updates
   - Offline audit logging to `lupo_anubis_log.json`

2. **Python Audit Tool** (`scripts/flip_header_audit.py`)
   - FLIP header validation across all docs/
   - Metadata extraction and reporting
   - Offline navigation JSON generation
   - Comprehensive validation reports

---

## Features

### VSX Extension Features

#### 1. FLIP TreeView Navigation
- **Grouping Modes**: Status, Channel, Flat list
- **Visual Indicators**: Icons and colors based on FLIP status
  - Active: Green checkmark
  - Proposed: Light bulb
  - Deprecated: Orange warning
- **Tooltips**: Rich metadata display on hover
- **Quick Open**: Click to open file

#### 2. Search & Filter Commands
- `lupopedia.searchFlip` — Search by keyword in path or metadata
- `lupopedia.filterByStatus` — Filter by Active/Proposed/Deprecated
- `lupopedia.filterByChannel` — Filter by channel ID
- `lupopedia.filterByThread` — Filter by thread ID

#### 3. Thread Simulation
- `lupopedia.showThreadSimulation` — Show all files linked to current thread
- Webview panel with file cards showing metadata
- Simulates discussion/thread view when server is offline

#### 4. Offline Collaboration
- `lupopedia.updateFlipTimestamp` — Auto-update FLIP header UTC timestamp
- `lupopedia.logAction` — Log agent actions to audit file
- Audit log: `~/.config/Code/User/globalStorage/lupopedia/lupo_anubis_log.json`
- Last 1000 entries retained automatically

#### 5. Utility Commands
- `lupopedia.refreshDoctrine` — Refresh FLIP tree view
- `lupopedia.toggleFlipGroupBy` — Switch grouping mode
- `lupopedia.openFlipFile` — Open file from tree

### Python Audit Tool Features

#### 1. FLIP Header Scanning
- Scans `docs/doctrine/`, `docs/api/`, `docs/specs/`
- Validates required fields: `file.last_modified_system_version`, `file.last_modified_utc`
- Detects missing headers
- Validates UTC timestamp format (14 digits)

#### 2. Metadata Extraction
Extracts and validates:
- `version` — System version (e.g. "4.0.27")
- `modified_utc` — UTC timestamp (YYYYMMDDHHMMSS)
- `channel_id` — Channel association
- `status` — Active/Proposed/Deprecated
- `thread_id` — Thread linking
- `actor_id` — Creator/modifier
- `tags` — Classification tags
- `mood_rgb` — Visual theming

#### 3. Output Generation
- **Offline Navigation**: `exports/flip_navigation.json`
  - Grouped by status, channel, thread
  - Used by VSX extension in offline mode
- **Validation Report**: `exports/flip_validation_report.md`
  - Summary statistics
  - Files with issues
  - Statistics by status and channel

#### 4. Interactive Features
- Optional FLIP header addition to missing files
- User confirmation before modifying files
- Detailed progress reporting

---

## Installation & Usage

### VSX Extension

#### Requirements
- VS Code 1.80.0 or newer
- Node.js & npm for building
- Lupopedia workspace open

#### Build & Install
```bash
cd tools/vsx-extension
npm install
npm run compile
# Install .vsix in VS Code
```

#### Configuration
`File > Preferences > Settings > Lupopedia`

```json
{
  "lupopedia.baseUrl": "https://lupopedia.com/lupopedia",
  "lupopedia.communicationMode": "auto",
  "lupopedia.defaultChannelId": 42
}
```

#### Activation
Extension activates automatically on VS Code startup when workspace contains `docs/` directory.

#### TreeView Location
- **Activity Bar** → Lupopedia icon
- **View** → "Lupopedia Doctrine / Docs"

### Python Audit Tool

#### Requirements
- Python 3.8+
- No external dependencies (uses stdlib only)

#### Usage
```bash
# From repo root
python scripts/flip_header_audit.py
```

#### Sample Output
```
🔍 Scanning FLIP headers...

============================================================
Total .md files scanned: 127
With valid FLIP header: 102
Missing FLIP header: 25
============================================================

📋 Files missing FLIP header:
  - docs/specs/API_ENDPOINTS_NEW.md
  - docs/doctrine/SCHEMA_UPDATES.md
  ...

⚙️ Add FLIP headers to missing files? (y/N): y
  ✓ Added FLIP header: docs/specs/API_ENDPOINTS_NEW.md
  ...

📊 Generating reports...

✓ Offline navigation file generated: exports/flip_navigation.json
✓ Validation report generated: exports/flip_validation_report.md

============================================================
✅ FLIP Header Audit Complete
============================================================

📁 Outputs:
  - Navigation: exports/flip_navigation.json
  - Report: exports/flip_validation_report.md

💡 Next steps:
  1. Review validation report for issues
  2. VSX extension will use flip_navigation.json for offline mode
  3. Commit updated FLIP headers to git
```

---

## Architecture

### FLIP Header Format

```yaml
---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/EXAMPLE.md
file.last_modified_system_version: "4.0.28"
file.last_modified_utc: "20260222150000"
channel_id: 42
status: Active
thread_id: 1001
actor_id: 2039
tags: ["doctrine", "schema", "upgrade"]
mood_rgb: "569CD6"

# Database Mapping Layer (Optional)
X-LUPO-actors.actor_id: 2038
X-LUPO-channels.channel_id: 42
X-LUPO-dialog_messages.dialog_message_id: 2000
---
```

#### Database Mapping Layer (New in 4.0.28)

The FLIP header system now supports an optional database mapping layer:

- **Format**: `X-LUPO-{table}.{column}: <value>`
- **Purpose**: Explicit mapping between header fields and database schema
- **Usage**: Advanced tooling, migrations, and schema-aware agents
- **Rules**: 
  - Never overrides semantic FLIP fields
  - Treated as opaque strings (no inference)
  - Optional for all operations
  - Must use exact `{table}.{column}` format

**VSX Extension Behavior**:
- When offline, includes mapping layer only if present in file
- Does NOT auto-generate mapping layer unless explicitly requested
- Does NOT infer table or column names
- Treats mapping layer as metadata only

### Data Flow

```
┌─────────────────┐
│ .md Files       │
│ (docs/)         │
└────────┬────────┘
         │
         ├──> VSX Extension ──> FlipTreeDataProvider ──> TreeView
         │                         │
         │                         └──> Search/Filter Commands
         │                         │
         │                         └──> Thread Simulation
         │
         └──> Python Audit   ──> flip_header_audit.py
                                     │
                                     ├──> flip_navigation.json
                                     └──> flip_validation_report.md
```

### Offline Audit Log Format

`lupo_anubis_log.json`:
```json
[
  {
    "event": "flip_header_updated",
    "file": "/path/to/file.md",
    "utc_timestamp": "20260222150500",
    "timestamp": "20260222150500"
  },
  {
    "event": "agent_action",
    "action": "Updated schema mismatch documentation",
    "timestamp": "20260222150730"
  }
]
```

---

## Command Reference

### VSX Extension Commands

| Command | Description | Shortcut |
|---------|-------------|----------|
| `lupopedia.searchFlip` | Search FLIP files by keyword | — |
| `lupopedia.filterByStatus` | Filter by status (Active/Proposed/Deprecated) | — |
| `lupopedia.filterByChannel` | Filter by channel ID | — |
| `lupopedia.filterByThread` | Filter by thread ID | — |
| `lupopedia.showThreadSimulation` | Show thread-linked files | — |
| `lupopedia.updateFlipTimestamp` | Update FLIP header timestamp | — |
| `lupopedia.toggleFlipGroupBy` | Change grouping mode | — |
| `lupopedia.refreshDoctrine` | Refresh tree view | — |
| `lupopedia.logAction` | Log agent action to audit file | — |
| `lupopedia.validateFlipHeader` | Validate current file's FLIP header | — |

---

## Workflow Examples

### Example 1: Finding Related Doctrine Files

1. Open Command Palette (`Ctrl+Shift+P`)
2. Run `Lupopedia: Search FLIP`
3. Enter keyword: `"schema"`
4. Select file from results
5. Run `Lupopedia: Show Thread Simulation` to see related files

### Example 2: Offline Collaboration Audit

1. Make changes to doctrine file
2. Run `Lupopedia: Update FLIP Header Timestamp`
3. Run `Lupopedia: Log Agent Action`
4. Describe action (e.g. "Fixed schema mismatch in registry table")
5. Audit log entry created in `lupo_anubis_log.json`

### Example 3: Generate Reports for Windsurf IDE

1. From repo root: `python scripts/flip_header_audit.py`
2. Review validation report in `exports/flip_validation_report.md`
3. Share `exports/flip_navigation.json` with Windsurf IDE
4. Windsurf uses navigation JSON for offline FLIP browsing

---

## Integration with Multi-IDE Workflow

### Actor IDs
- **Warp IDE**: 2039 (current IDE)
- **Windsurf IDE**: 2040 (awaiting handoff)
- **Copilot**: 2036
- **LEXA**: 2037
- **LILITH**: 2038

### Channel Assignments
- **Channel 42**: Crafty Development (main coordination)
- **Channel 51**: AI Development
- **Channel 420**: Lupopedia Development
- **Channel 666**: Protocol Development

### Offline Mode Benefits
- Continue work during server outages
- All actions auditable via `lupo_anubis_log.json`
- FLIP headers provide file-level provenance
- Thread links maintain conversation context

---

## Troubleshooting

### TreeView Not Showing
- Ensure workspace contains `docs/` directory
- Run `Lupopedia: Refresh Doctrine View`
- Check VS Code developer console for errors

### FLIP Headers Not Validating
- Ensure `---` delimiters present at start/end
- Verify UTC timestamp is exactly 14 digits
- Check for typos in field names (case-sensitive)

### Audit Log Not Created
- Check VS Code global storage path
- Verify write permissions
- Check developer console for errors

### Python Script Errors
- Run from repo root: `python scripts/flip_header_audit.py`
- Ensure Python 3.8+ installed
- Check file encoding (UTF-8 required)

---

## Future Enhancements

1. **Real-time Sync**: When server online, sync audit log to database
2. **FLIP Diff View**: Compare FLIP headers across versions
3. **Thread Message Integration**: Merge local FLIP threads with server messages
4. **Auto-FLIP Generation**: Generate headers from file content
5. **Multi-Workspace Support**: Scan multiple Lupopedia instances

---

## Credits

**Implementation**: Warp IDE (actor 2039)  
**Coordination**: Channel 42 multi-IDE collaboration  
**Version**: Lupopedia 4.0.27  
**Date**: 2026-02-22  

**References**:
- `MINIMAL_SEED_4.0.26_READY.md` — Testing guide
- `CRITICAL_SCHEMA_FIX_4.0.26.sql` — Schema documentation
- `messages/GLOBAL_AGENT_SYNC_4.0.27.md` — Multi-IDE coordination

---

**Status**: ✅ FLIP Integration Complete — Ready for Phase 2 Testing
