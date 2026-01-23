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
  message: Created DIALOG_EXTRACTION_SPEC.md with strict implementation instructions for dialog:extract agent command. No creativity allowed - exact implementation only.
tags:
  categories: ["documentation", "specification", "agents", "cli"]
  collections: ["core-docs", "specs"]
  channels: ["dev"]
in_this_file_we_have:
  - Dialog Extraction System Specification
  - Files to Create or Update
  - Command Implementation Requirements
  - Supported Flags (Mandatory)
  - Inline Dialog Parsing Rules
  - Database Storage Rules
  - File Output Rules
  - Naming Conventions
  - Directory Scanning Rules
  - Thread Extraction Rules
  - Error Handling Requirements
  - No Creativity Rule
file:
  title: "Dialog Extraction System Specification"
  description: "Strict, deterministic instructions for implementing the dialog:extract agent command - no creativity, no deviation, exact implementation only"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# ðŸº DIALOG EXTRACTION â€” CURSOR IMPLEMENTATION DIRECTIONS

**Strict, deterministic instructions for Cursor to implement the Dialog Extraction System**

These instructions define exactly how Cursor must implement the `dialog:extract` agent command, including file scanning, Inline Dialog parsing, database integration, thread handling, and dialog history file generation.

**Cursor must follow these instructions literally with no creativity, no renaming, no restructuring, and no deviation.**

---

## **1. FILES TO CREATE OR UPDATE**

Cursor must create or update the following files:

- `/agents/dialog_extract.php`
- `/docs/specs/DIALOG_EXTRACTION_SPEC.md` (this file)
- `/docs/cli/dialog_extract_help.md`

Cursor must not create additional files unless explicitly listed.

---

## **2. COMMAND TO IMPLEMENT**

Cursor must implement the following CLI command:

```
php agent dialog:extract
```

This command must be registered in the same way as all other Lupopedia agent commands.

---

## **3. SUPPORTED FLAGS (MANDATORY)**

Cursor must implement the following flags exactly:

### **Input Sources**
- `-f, --file [file]` - Extract dialog from a single file
- `-d, --dir [directory]` - Extract dialog from a directory
- `-r, --recursive` - Scan subdirectories recursively
- `--from-thread-id [id]` - Extract dialog from database thread by ID
- `--from-thread-name [name]` - Extract dialog from database thread by name

### **Output Targets**
- `-o, --output [file]` - Write output to specified file
- `-n, --name [name]` - Use name for output file (see naming conventions)
- `--md` - Output as Markdown (default)
- `--json` - Output as JSON
- `--yaml` - Output as YAML

### **Database Integration**
- `--to-db` - Store extracted dialog in database
- `--thread-id [id]` - Use existing thread ID
- `--thread-name [name]` - Use existing thread by name (create if missing)
- `--new-thread [name]` - Create new thread with specified name

### **Filtering**
- `--speaker [name]` - Filter by speaker
- `--target [name]` - Filter by target
- `--since [timestamp]` - Filter by timestamp (YYYYMMDDHHIISS format)
- `--limit [number]` - Limit number of results

### **Sorting**
- `--newest-first` - Sort newest first (default)
- `--oldest-first` - Sort oldest first

### **Help**
- `-h, --help` - Display help documentation and exit

Cursor must not add additional flags.

---

## **4. INLINE DIALOG PARSING RULES**

Cursor must implement a parser that:

1. **Detects Inline Dialog blocks** beginning with `---` and containing a `dialog:` key.

2. **Extracts the following fields:**
   - `speaker` (required)
   - `target` (required)
   - `message` (required)
   - `mood_RGB` (optional)
   - `date_ymdhis` (optional)

3. **Validates:**
   - `message` â‰¤ 272 chars
   - `mood_RGB` is exactly 6 hex digits (no leading `#`)
   - `date_ymdhis` is valid UTC format (YYYYMMDDHHIISS) if present

4. **Preserves the block exactly as written** for output.

Cursor must not modify dialog content.

---

## **5. DATABASE STORAGE RULES**

When `--to-db` is used:

1. Cursor must require one of:
   - `--thread-id`
   - `--thread-name`
   - `--new-thread`

2. If `--thread-name` is used:
   - Cursor must look up the thread by name.
   - If missing, Cursor must create a new row in `dialog_threads`.

3. If `--new-thread` is used:
   - Cursor must always create a new row in `dialog_threads`.

4. Cursor must insert each dialog block into `dialog_messages` with:
   - `dialog_thread_id` (from thread lookup/creation)
   - `speaker` (from dialog block)
   - `target` (from dialog block)
   - `message` (from dialog block)
   - `mood_rgb` (from dialog block, or default '666666')
   - `timestamp` (from `date_ymdhis` or NOW_UTC)
   - `artifacts` = NULL

Cursor must not insert messages without a thread.

---

## **6. FILE OUTPUT RULES**

When `--output` or `--name` is used:

1. Cursor must generate a markdown file unless `--json` or `--yaml` is specified.

2. Cursor must sort dialog blocks:
   - `newest-first` (default)
   - `oldest-first` (if flag provided)

3. Cursor must write each dialog block exactly as extracted.

4. Cursor must not rewrite, reformat, or collapse blocks.

---

## **7. NAMING CONVENTIONS**

Cursor must follow these naming rules:

- **Global dialog history:** `changelog_dialog.md`
- **Thread-specific history:** `dialog_thread_[thread_name].md`
- **File-specific history:** `dialog_file_[filename].md`

Cursor must not invent new naming patterns.

---

## **8. DIRECTORY SCANNING RULES**

When scanning directories:

1. Cursor must include all files ending in:
   - `.md`
   - `.txt`
   - `.php`
   - `.json`

2. If `--recursive` is used:
   - Cursor must scan all subdirectories.

3. Cursor must ignore:
   - `vendor/`
   - `node_modules/` (npm packages directory, not related to federation tables)
   - `.git/`
   - `storage/`

---

## **9. THREAD EXTRACTION RULES (DB â†’ FILE)**

When using:
- `--from-thread-id [id]`
- `--from-thread-name [name]`

Cursor must:

1. Query `dialog_messages` for that thread.
2. Sort according to flags.
3. Output to file or stdout.

---

## **10. ERROR HANDLING**

Cursor must:

1. Abort if `--to-db` is used without a thread flag.
2. Warn and skip malformed dialog blocks.
3. Warn if file paths do not exist.
4. Warn if thread does not exist (unless `--thread-name` is used).
5. Never silently fail.

---

## **11. HELP DOCUMENTATION**

Cursor must generate:

`docs/cli/dialog_extract_help.md`

Containing:
- full flag list
- examples
- usage patterns
- thread rules
- database rules
- output rules

---

## **12. NO CREATIVITY RULE**

Cursor must:

- not rename files
- not restructure directories
- not invent new flags
- not modify doctrine
- not change naming conventions
- not add new features

Cursor must implement exactly what is written here.

---

## **IMPLEMENTATION CHECKLIST**

Cursor must verify:

- [ ] Command `php agent dialog:extract` is registered
- [ ] All mandatory flags are implemented exactly as specified
- [ ] Inline Dialog parser extracts all required fields
- [ ] Validation rules are enforced (272 chars, 6 hex digits, UTC format)
- [ ] Database storage requires thread flag
- [ ] Thread lookup/creation works correctly
- [ ] File output preserves dialog blocks exactly
- [ ] Naming conventions are followed
- [ ] Directory scanning includes only specified file types
- [ ] Ignored directories are excluded
- [ ] Error handling aborts/warns appropriately
- [ ] Help documentation is generated
- [ ] No additional features or flags are added

---

## Related Documentation

- **[Inline Dialog Specification](../../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** - Complete specification for dialog format that this extraction tool processes
- **[Dialog History Spec](../../dialogs/agents/DIALOG_HISTORY_SPEC.md)** - Database schema and storage format for extracted dialogs
- **[Database Schema](../../schema/DATABASE_SCHEMA.md)** - Complete documentation of dialog_messages table structure
- **[Architecture Sync](../ARCHITECTURE_SYNC.md)** - DialogManager system that processes extracted dialogs

---

