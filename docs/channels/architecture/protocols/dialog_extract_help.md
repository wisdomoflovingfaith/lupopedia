---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "0088FF"
  message: Created dialog_extract_help.md with complete help documentation for dialog:extract command including all flags, examples, and usage patterns.
tags:
  categories: ["documentation", "cli", "help"]
  collections: ["core-docs", "cli-docs"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Dialog Extract Command Help
  - Full Flag List
  - Usage Examples
  - Thread Rules
  - Database Rules
  - Output Rules
  - File Format Examples
file:
  title: "Dialog Extract Command Help"
  description: "Complete help documentation for the dialog:extract agent command"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# Dialog Extract Command Help

## Command

```
php agent dialog:extract
```

Extract Inline Dialog blocks from files or database threads and output to files or database.

---

## Full Flag List

### Help
- `-h, --help` - Display this help documentation and exit

### Input Sources
- `-f, --file [file]` - Extract dialog from a single file
- `-d, --dir [directory]` - Extract dialog from a directory
- `-r, --recursive` - Scan subdirectories recursively
- `--from-thread-id [id]` - Extract dialog from database thread by ID
- `--from-thread-name [name]` - Extract dialog from database thread by name

### Output Targets
- `-o, --output [file]` - Write output to specified file
- `-n, --name [name]` - Use name for output file (see naming conventions)
- `--md` - Output as Markdown (default)
- `--json` - Output as JSON
- `--yaml` - Output as YAML

### Database Integration
- `--to-db` - Store extracted dialog in database
- `--thread-id [id]` - Use existing thread ID
- `--thread-name [name]` - Use existing thread by name (create if missing)
- `--new-thread [name]` - Create new thread with specified name

### Filtering
- `--speaker [name]` - Filter by speaker
- `--target [name]` - Filter by target
- `--since [timestamp]` - Filter by timestamp (YYYYMMDDHHIISS format)
- `--limit [number]` - Limit number of results

### Sorting
- `--newest-first` - Sort newest first (default)
- `--oldest-first` - Sort oldest first

---

## Usage Examples

### Display Help
```bash
php agent dialog:extract --help
```
or
```bash
php agent dialog:extract -h
```

### Extract from Single File
```bash
php agent dialog:extract -f HISTORY.md -o dialog_history.md
```

### Extract from Directory
```bash
php agent dialog:extract -d docs/ -o changelog_dialog.md
```

### Extract Recursively
```bash
php agent dialog:extract -d . -r -o changelog_dialog.md
```

### Store to Database (New Thread)
```bash
php agent dialog:extract -d docs/ --to-db --new-thread "Documentation Updates"
```

### Store to Database (Existing Thread)
```bash
php agent dialog:extract -f CHANGELOG.md --to-db --thread-name "Version 4.0.0"
```

### Extract from Database Thread
```bash
php agent dialog:extract --from-thread-name "Version 4.0.0" -o dialog_thread_version_4.0.0.md
```

### Filter by Speaker
```bash
php agent dialog:extract -d . -r --speaker CURSOR -o cursor_dialogs.md
```

### Filter by Target
```bash
php agent dialog:extract -d . -r --target @everyone -o public_dialogs.md
```

### Filter Since Timestamp
```bash
php agent dialog:extract -d . -r --since 20260106000000 -o recent_dialogs.md
```

### Limit Results
```bash
php agent dialog:extract -d . -r --limit 10 -o latest_10_dialogs.md
```

### Output as JSON
```bash
php agent dialog:extract -d docs/ --json -o dialogs.json
```

### Output as YAML
```bash
php agent dialog:extract -d docs/ --yaml -o dialogs.yaml
```

### Sort Oldest First
```bash
php agent dialog:extract -d . -r --oldest-first -o dialog_chronological.md
```

---

## Thread Rules

### Creating Threads
- Use `--new-thread [name]` to create a new thread
- Thread name must be unique
- Thread is created in `dialog_threads` table

### Using Existing Threads
- Use `--thread-id [id]` to reference by ID
- Use `--thread-name [name]` to reference by name
- If `--thread-name` is used and thread doesn't exist, it will be created

### Thread Requirements
- `--to-db` requires one of: `--thread-id`, `--thread-name`, or `--new-thread`
- Cannot store messages without a thread

---

## Database Rules

### Storage Format
When storing to database, each dialog block is inserted into `dialog_messages` with:
- `dialog_thread_id` - From thread lookup/creation
- `speaker` - From dialog block
- `target` - From dialog block
- `message` - From dialog block (must be â‰¤ 272 chars)
- `mood_rgb` - From dialog block (6 hex digits, no #) or default '666666'
- `created_ymdhis` - From `date_ymdhis` in dialog block or current UTC timestamp
- `artifacts` - NULL

### Validation
- Messages must be â‰¤ 272 characters
- `mood_rgb` must be exactly 6 hex digits (no leading #)
- `date_ymdhis` must be valid UTC format (YYYYMMDDHHIISS)

---

## Output Rules

### File Formats
- **Markdown (default):** Human-readable format with dialog blocks
- **JSON:** Structured data format for programmatic access
- **YAML:** Structured data format for configuration

### Sorting
- **Newest First (default):** Most recent dialog at top
- **Oldest First:** Oldest dialog at top

### Content Preservation
- Dialog blocks are written exactly as extracted
- No rewriting, reformatting, or collapsing
- Original formatting is preserved

---

## Naming Conventions

### Global Dialog History
```
changelog_dialog.md
```
Used for consolidated dialog history across entire repository.

### Thread-Specific History
```
dialog_thread_[thread_name].md
```
Example: `dialog_thread_version_4.0.0.md`

### File-Specific History
```
dialog_file_[filename].md
```
Example: `dialog_file_HISTORY.md`

---

## File Format Examples

### Markdown Output
```markdown
# Dialog History (Auto-Generated)

## 2026-01-06

---
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00A0FF"
  message: Created dialog extraction system.
---
Source: CHANGELOG.md (lines 3-7)

---
```

### JSON Output
```json
{
  "dialogs": [
    {
      "speaker": "CURSOR",
      "target": "@everyone",
      "mood_RGB": "00A0FF",
      "message": "Created dialog extraction system.",
      "date_ymdhis": "20260106143000",
      "source": "CHANGELOG.md",
      "source_lines": "3-7"
    }
  ]
}
```

### YAML Output
```yaml
dialogs:
  - speaker: CURSOR
    target: "@everyone"
    mood_RGB: "00A0FF"
    message: "Created dialog extraction system."
    date_ymdhis: "20260106143000"
    source: "CHANGELOG.md"
    source_lines: "3-7"
```

---

## Error Handling

The command will:
- **Abort** if `--to-db` is used without a thread flag
- **Warn** and skip malformed dialog blocks
- **Warn** if file paths do not exist
- **Warn** if thread does not exist (unless `--thread-name` is used)
- **Never** silently fail

---

## Ignored Directories

The following directories are automatically ignored during recursive scans:
- `vendor/`
- `node_modules/` (npm packages directory, not related to federation tables)
- `.git/`
- `storage/`

---

## File Types Scanned

The following file types are scanned for Inline Dialog blocks:
- `.md` - Markdown files
- `.txt` - Text files
- `.php` - PHP files
- `.json` - JSON files

---

## Related Documentation

**Core Specifications:**
- **[Dialog Extraction Spec](DIALOG_EXTRACTION_SPEC.md)** - Complete technical specification for this extraction tool
- **[Inline Dialog Specification](../../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** - Format specification for dialog blocks being extracted

**Database Integration:**
- **[Dialog History Spec](../../dialogs/agents/DIALOG_HISTORY_SPEC.md)** - Database schema for dialog storage
- **[Database Schema](../../schema/DATABASE_SCHEMA.md)** - Complete documentation of dialog_messages and dialog_threads tables

**Development Context (LOW Priority):**
- **[Multi-IDE Workflow](../multi-ide-workflow.md)** - How dialog extraction fits into development workflow
- **[Architecture Sync](../ARCHITECTURE_SYNC.md)** - DialogManager system that processes extracted dialogs
- **[Agent Runtime](../../agents/AGENT_RUNTIME.md)** - Agent system that generates the dialogs being extracted

---

