# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\DIALOG_FILE_ORDERING_DOCTRINE.md"
  file_hash: "9d28de7967f28153f61b76c8e248e124a7d5aff6895999fef00409154a56f28f"
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
  file_path_from_root: "docs\channels\doctrine\DIALOG_FILE_ORDERING_DOCTRINE.md"
  file_hash: "783e3273555e6d77f7c984bb29a51b7588ca7216204d15819b1783f040f48a1c"
  file_path_from_root: "docs\channels\doctrine\DIALOG_FILE_ORDERING_DOCTRINE.md"
  file_hash: "12cde1d95fa7bde38d9f9b5e43d05a9905110afd4d7bca471ee86ab2021b28bc"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for DIALOG_FILE_ORDERING_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "dialog_file_ordering_doctrinemd"]
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
file.last_modified_system_version: 3.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CAPTAIN_WOLFIE
  target: @everyone @FLEET
  mood_RGB: "336699"
  message: "Updated DIALOG_FILE_ORDERING_DOCTRINE.md to version 3.1.6. Header synchronized with current system version."
tags:
  categories: ["doctrine", "dialog", "file-structure"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
file:
  title: "Dialog File Ordering Doctrine"
  description: "Rules for _dialog file structure: newest entries at top, historical preservation, deterministic parsing"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Dialog File Ordering Doctrine

## Purpose

This doctrine defines the mandatory structure and ordering rules for all `_dialog` files in Lupopedia. These rules ensure deterministic parsing for AI agents while preserving complete historical context.

## Core Principle

**Newest dialog entries must always be added at the top of the file, immediately after the `# Dialog begin` marker.**

This ensures:
- AI agents can immediately access the latest dialog state
- Historical context is preserved without rewriting or reordering
- Deterministic parsing behavior across all IDE agents
- Consistency with WOLFIE Header "living signature" behavior

## File Structure Requirements

### Mandatory Structure

Every `_dialog` file must follow this exact structure:

```markdown
---
# WOLFIE HEADER
wolfie.headers.version: lupopedia_current_version
# ... other header metadata ...
dialog:
  speaker: ...
  target: ...
  message: ...
  mood: ...
---

# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE

# Dialog begin

## YYYY-MM-DD — Newest Entry Title
...dialog block...

## YYYY-MM-DD — Older Entry Title
...dialog block...

## YYYY-MM-DD — Even Older Entry Title
...dialog block...
```

### Required Components

1. **WOLFIE Header** (at top of file)
   - Must include standard WOLFIE header fields
   - Must include `dialog:` block with latest file-level dialog
   - Must use `lupopedia_current_version` atom

2. **Dialog Insertion Marker** (immediately after header)
   - Must be exactly: `# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE`
   - Must appear on its own line
   - Must be the first content after the closing `---` of the header
   - Provides deterministic insertion point for IDE agents

3. **Dialog Begin Marker** (after insertion marker)
   - Must be exactly: `# Dialog begin`
   - Must appear on its own line
   - Must appear after the insertion marker

4. **Dialog Entries** (after dialog begin marker)
   - Newest entries inserted directly after `# Dialog begin`
   - Older entries remain below in chronological order
   - Each entry must have a date header: `## YYYY-MM-DD — Entry Title`

## Cursor Implementation Requirements

### Mandatory Rules

1. **Never append dialog entries to the bottom of the file.**
   - Violation: Adding new entries after the oldest entry
   - Correct: Inserting new entries directly after `# Dialog begin`

2. **Always insert new dialog blocks directly after `# Dialog begin`.**
   - New entries must be the first content after the marker
   - Preserve all existing entries below
   - Use the `# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE` marker to locate insertion point

3. **Never reorder or rewrite older dialog entries.**
   - Historical entries are immutable once written
   - Only add new entries, never modify existing ones

4. **Preserve the entire historical conversation exactly as-is.**
   - Maintain all formatting, spacing, and structure
   - Do not consolidate, merge, or summarize entries

5. **Maintain WOLFIE Header integrity and formatting.**
   - Update header `dialog:` block when file-level dialog changes
   - Preserve all header metadata fields

6. **Preserve the dialog insertion marker.**
   - Never remove or modify the `# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE` marker
   - This marker ensures deterministic insertion behavior across all IDE agents

### Reading Direction

- **AI Agents**: Read top-down, starting from `# Dialog begin` to get latest state
- **Humans**: Read bottom-up to follow conversation in chronological order

## File Naming Convention

All dialog files must follow the pattern: `*_dialog.md` or `changelog_dialog.md`

Examples:
- `changelog_dialog.md` - Consolidated dialog history
- `module_dialog.md` - Module-specific dialog history
- `agent_dialog.md` - Agent-specific dialog history

## Validation Checklist

When creating or updating a `_dialog` file, verify:

- [ ] WOLFIE Header is present at top of file
- [ ] Header includes `dialog:` block with latest file-level dialog
- [ ] `# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE` marker appears immediately after header
- [ ] `# Dialog begin` marker appears after insertion marker
- [ ] Newest dialog entry is directly after `# Dialog begin`
- [ ] All entries have date headers (`## YYYY-MM-DD — Title`)
- [ ] Older entries remain below in chronological order
- [ ] No entries were reordered or rewritten
- [ ] Historical context is preserved exactly as-is
- [ ] File follows naming convention (`*_dialog.md`)

## Examples

### Correct Structure

```markdown
---
wolfie.headers.version: lupopedia_current_version
dialog:
  speaker: Captain Wolfie
  target: @everyone
  message: "Latest file-level dialog message"
  mood: "336699"
---

# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE

# Dialog begin

## 2026-01-12 — TRUTH Module Implementation
**File:** `lupo-includes/modules/truth/truth-model.php`  
**Speaker:** Wolfie  
**Message:** "Created TRUTH model layer..."

## 2026-01-12 — Navigation Components
**File:** `lupo-includes/ui/components/collection_selector.php`  
**Speaker:** Wolfie  
**Message:** "Created collection selector component..."

## 2026-01-11 — Content Rendering Pipeline
**File:** `lupo-includes/modules/content/renderers/render-markdown.php`  
**Speaker:** Wolfie  
**Message:** "Implemented Markdown renderer..."
```

### Incorrect Structure (Violations)

```markdown
# ❌ WRONG: New entry appended to bottom
## 2026-01-11 — Older Entry
...
## 2026-01-12 — New Entry (should be at top!)
```

```markdown
# ❌ WRONG: Missing insertion marker
---
header...
---

# Dialog begin (missing insertion marker!)

## 2026-01-12 — Entry
```

```markdown
# ❌ WRONG: Missing "Dialog begin" marker
---
header...
---

# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE

## 2026-01-12 — Entry (no dialog begin marker!)
```

```markdown
# ❌ WRONG: Entries reordered
## 2026-01-12 — New Entry
## 2026-01-11 — Older Entry (should be below!)
```

## Integration with WOLFIE Headers

This doctrine aligns with the WOLFIE Header "living signature" behavior:

- **File-level dialog** (in WOLFIE Header) = Latest dialog state for the file itself
- **Entry-level dialog** (in `_dialog` file) = Historical conversation entries

Both systems use the same "newest at top" principle for deterministic parsing.

## Enforcement

- **Cursor**: Must follow all mandatory rules when creating/updating `_dialog` files
- **Other IDE Agents**: Must follow same rules for consistency
- **Validation**: Use checklist before committing `_dialog` file changes

## Related Documentation

- `docs/doctrine/WOLFIE_HEADER_DOCTRINE.md` - WOLFIE Header specification (canonical doctrine file)
- `docs/DIALOG_HISTORY_SPEC.md` - Dialog history file specification
- `dialog.yaml` - Inline dialog format specification

## Summary

Dialog files use a "newest at top" structure with a mandatory `# Dialog begin` marker. This ensures AI agents can immediately access the latest dialog state while preserving complete historical context. Cursor and all IDE agents must follow these rules deterministically.
