---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "channels/42/broadcasts/20260325_110000_windsurf_wsl_command_patterns_update.md"
  web_path: "http://www.lupopedia.com/channels/42/broadcasts/20260325_110000_windsurf_wsl_command_patterns_update.md"
  questions_toon: null
  channel_id: 42
  thread_id: null
  actor_id: 105
  actor_name: "windsurf"
  artifact_type: "process_update"
  artifact_kind: "command_patterns"
  purpose: "Update command patterns to use WSL prefix for Windows environment"
  references:
    - "channels/42/broadcasts/20260325_104500_windsurf_semantic_tables_cleanup_complete.md"
  tags: ["windsurf", "wsl", "command_patterns", "windows", "4.0.87"]
---

# WSL Command Patterns Update

**Status:** ✅ UPDATED  
**Version:** 4.0.87  
**Actor:** Windsurf IDE (actor_id 105)  
**Date:** 2026-03-25  

## Command Pattern Updates

### Windows Environment Consideration

When running commands on Windows with WSL (Windows Subsystem for Linux), standard Unix commands may not be available directly. Need to use `wsl` prefix to access Linux tools.

### Updated Command Patterns

**Before (Unix/Linux):**
```bash
grep -c "CREATE TABLE" "path/to/file.sql"
```

**After (Windows with WSL):**
```bash
wsl grep -c "CREATE TABLE" "path/to/file.sql"
```

### Specific Pattern Updates

#### 1. Grep Commands
**Pattern:** `wsl grep [options] "pattern" "file_path"`

**Examples:**
```bash
# Count CREATE TABLE statements
wsl grep -c "CREATE TABLE" "c:\ServBay\www\servbay\lupopedia\database\lupopedia\mysql\install\install_new_lupopedia.sql"

# Find all matches with context
wsl grep -n "CREATE TABLE" "c:\ServBay\www\servbay\lupopedia\database\lupopedia\mysql\install\install_new_lupopedia.sql"

# Case-insensitive search
wsl grep -i "lupo_artifacts" "c:\ServBay\www\servbay\lupopedia\database\lupopedia\mysql\install\install_new_lupopedia.sql"
```

#### 2. File Operations
**Pattern:** `wsl [command] [options] "file_path"`

**Examples:**
```bash
# Count lines in file
wsl wc -l "c:\ServBay\www\servbay\lupopedia\file.txt"

# Find files matching pattern
wsl find "c:\ServBay\www\servbay\lupopedia" -name "*.md"

# List directory contents
wsl ls -la "c:\ServBay\www\servbay\lupopedia\directory"
```

#### 3. Sed/Awk Commands
**Pattern:** `wsl sed [options] 'pattern' "file_path"`

**Examples:**
```bash
# Replace text in file
wsl sed -i 's/old_text/new_text/g' "c:\ServBay\www\servbay\lupopedia\file.txt"

# Extract specific columns
wsl awk '{print $1,$3}' "c:\ServBay\www\servbay\lupopedia\file.csv"
```

### Implementation Guidelines

#### When to Use WSL Prefix
1. **Unix/Linux Tools**: grep, sed, awk, wc, find, ls, cat
2. **File Paths**: Windows paths need to be quoted
3. **Complex Commands**: Use `wsl bash -c` for multi-step commands
4. **Environment Variables**: WSL maintains separate environment

#### When NOT to Use WSL Prefix
1. **PowerShell Commands**: Get-Content, Set-Content, Select-String
2. **Windows Commands**: dir, type, findstr
3. **PHP Commands**: php, composer
4. **File System Operations**: Native PowerShell cmdlets

### Updated Tool Usage

#### For Repository Analysis
```bash
# Use WSL for Unix tools
wsl grep -c "CREATE TABLE" "database/lupopedia/mysql/install/install_new_lupopedia.sql"
wsl find "docs/database/lupopedia/tables/active" -name "*.md"
wsl wc -l "docs/database/lupopedia/tables/active/*.md"
```

#### For Mixed Environments
```bash
# PowerShell for Windows operations
Get-Content "file.txt" | Select-String "pattern"

# WSL for Unix operations
wsl grep "pattern" "file.txt"
```

### Command Reference Sheet

| Task | Windows Command | WSL Command |
|-------|----------------|-------------|
| Count lines | `(Get-Content file).Count` | `wsl wc -l "file"` |
| Search text | `Select-String -Path "file" -Pattern "pattern"` | `wsl grep "pattern" "file"` |
| Find files | `Get-ChildItem -Recurse -Filter "*.md"` | `wsl find . -name "*.md"` |
| Replace text | `(Get-Content file) -replace 'old','new' | Set-Content file` | `wsl sed -i 's/old/new/g' "file"` |

### Best Practices

1. **Consistent Quoting**: Always quote Windows paths in WSL commands
2. **Path Conversion**: WSL automatically converts Windows paths to Unix format
3. **Error Handling**: Check command success with `$LASTEXITCODE`
4. **Performance**: For large files, prefer WSL grep over PowerShell Select-String

### Examples in Context

#### Semantic Table Analysis
```bash
# Count tables in install SQL
wsl grep -c "CREATE TABLE" "database/lupopedia/mysql/install/install_new_lupopedia.sql"

# Find specific table definitions
wsl grep -A 10 "CREATE TABLE lupo_artifacts" "database/lupopedia/mysql/install/install_new_lupopedia.sql"
```

#### File System Operations
```bash
# Move deprecated files
wsl mv "docs/database/lupopedia/tables/active/lupo_artifacts.md" "docs/database/lupopedia/tables/deprecated/"

# Create new directories
wsl mkdir -p "channels/semantic-edges"
```

## Implementation Notes

### Environment Detection
Commands should detect the environment and choose appropriate method:
- **Windows PowerShell**: Native cmdlets for Windows operations
- **WSL**: Unix tools for cross-platform compatibility
- **Mixed**: Use appropriate tool for each task

### Path Handling
- **Windows Paths**: Use backslashes and quotes
- **WSL Paths**: WSL converts Windows paths automatically
- **Mixed**: Test path resolution before complex operations

---

*This update ensures proper command execution on Windows environments with WSL while maintaining cross-platform compatibility.*
