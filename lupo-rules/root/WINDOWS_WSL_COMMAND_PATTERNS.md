---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "lupo-rules/root/WINDOWS_WSL_COMMAND_PATTERNS.md"
  web_path: "http://www.lupopedia.com/lupo-rules/root/WINDOWS_WSL_COMMAND_PATTERNS.md"
  last_modified_utc: "20260325_111500"
  channel_id: 0
  thread_id: null
  actor_id: 105
  actor_name: "windsurf"
  artifact_type: "rule"
  artifact_kind: "command_pattern"
  purpose: "Rule for using WSL prefix on Windows environments for Unix command execution"
  references:
    - "lupo-channels/42/broadcasts/20260325_110000_windsurf_wsl_command_patterns_update.md"
  tags: ["windsurf", "wsl", "command_patterns", "windows", "rule", "4.0.87"]
---

# Rule: Windows WSL Command Patterns

**Rule ID:** WINDOWS_WSL_COMMAND_PATTERNS  
**Status:** ACTIVE  
**Version:** 4.0.87  
**Actor:** Windsurf IDE (actor_id 105)  
**Scope:** All agents working on Windows environments  

## Rule Statement

When executing Unix/Linux commands on Windows environments with WSL (Windows Subsystem for Linux), prepend `wsl` to the command to ensure proper execution.

## Applicability

### When to Apply This Rule
- **Environment**: Windows with WSL installed
- **Command Type**: Unix/Linux tools (grep, sed, awk, wc, find, etc.)
- **File Paths**: Windows file paths require proper quoting

### When NOT to Apply
- **PowerShell Commands**: Use native PowerShell cmdlets
- **Windows Commands**: Use native Windows commands (dir, type, findstr)
- **PHP Commands**: Use PHP directly without WSL wrapper
- **No WSL**: When WSL is not available

## Command Pattern

### Standard Form
```bash
wsl [unix_command] [options] "file_path"
```

### Examples

#### Grep Commands
```bash
# Count CREATE TABLE statements
wsl grep -c "CREATE TABLE" "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"

# Search with context lines
wsl grep -A 5 -B 2 "lupo_artifacts" "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"

# Case-insensitive search
wsl grep -i "rose" "lupo-includes/class-rose.php"
```

#### File Operations
```bash
# Count lines in file
wsl wc -l "lupo-docs/database/lupopedia/tables/active/lupo_edges.md"

# Find files by pattern
wsl find "lupo-docs/database/lupopedia/tables/active" -name "*edges*.md"

# Move/copy files
wsl mv "source_file.md" "destination_directory/"
wsl cp -r "source_directory/" "destination_directory/"
```

#### Text Processing
```bash
# Search and replace
wsl sed -i 's/old_pattern/new_pattern/g' "target_file.md"

# Extract columns
wsl awk '{print $1,$3}' "data_file.csv"

# Sort and unique
wsl sort "file.txt" | wsl uniq
```

## Path Handling Guidelines

### Windows Paths in WSL
- **Always quote paths** with double quotes
- **Use forward slashes** or escaped backslashes
- **WSL converts paths** automatically from Windows to Unix format

### Examples
```bash
# Correct - quoted path
wsl grep "pattern" "c:\ServBay\www\servbay\lupopedia\file.txt"

# Incorrect - unquoted path (may fail)
wsl grep "pattern" c:\ServBay\www\servbay\lupopedia\file.txt
```

## Environment Detection

### PowerShell Commands (Windows Native)
```powershell
# Use when no WSL available or for Windows-specific operations
Get-Content "file.txt" | Select-String "pattern"
Get-ChildItem -Recurse -Filter "*.md"
```

### Mixed Environment Approach
```bash
# Detect and use appropriate tool
if (command -v wsl >/dev/null 2>&1); then
    wsl grep "pattern" "file.txt"
else
    grep "pattern" "file.txt"  # Fallback to native if available
fi
```

## Implementation Requirements

### For All Agents
1. **Detect Environment**: Check for WSL availability before using WSL commands
2. **Use Proper Quoting**: Always quote Windows file paths
3. **Error Handling**: Check command success with appropriate error codes
4. **Documentation**: Document when WSL is required vs native commands

### For IDE Agents
1. **Consistent Patterns**: Use this rule for all similar command executions
2. **Clear Comments**: Explain why WSL prefix is used
3. **Fallback Options**: Provide alternatives when WSL is not available

## Compliance Notes

### Rule Enforcement
- **Automatic**: Apply when Windows environment detected
- **Documentation**: Comment usage when WSL prefix is used
- **Validation**: Ensure commands work in target environment

### Exceptions
- **PowerShell-specific**: Use native PowerShell cmdlets
- **Windows-specific**: Use native Windows commands
- **Cross-platform**: Use PHP/Python for platform independence

## Related Rules

- **LINUX_COMMAND_PATTERNS**: For Linux environments
- **POWERSHELL_COMMAND_PATTERNS**: For Windows PowerShell environments
- **CROSS_PLATFORM_COMMANDS**: For platform-independent operations

---

*This rule ensures consistent and reliable command execution across Windows environments with WSL while maintaining compatibility with other platforms.*
