---
lupopedia.headers:
  when_updated: "20260406044907"
  lupopedia.schema: "rule"
  file_path_from_root: "lupo-rules/root/WINDOWS_WSL_COMMAND_PATTERNS.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/WINDOWS_WSL_COMMAND_PATTERNS.md"
  last_modified_utc: "20260406044907"
  channel_id: 42
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "rule"
  artifact_kind: "pattern"
  purpose: Update WSL command patterns with critical enforcement notice
  tags:
  - "4.0.89"
  - "wsl"
  - "windows"
  - "command_prefix"
  - "critical"
lupopedia.edges:
  outbound_edges:
    - to: ".windsurf/rules/lupopedia-rules.md"
      type: references
      weight: 1.0
      reason: IDE agent configuration for WSL prefix
    - to: "lupo-docs/versions/4.0.89/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: Documented WSL enforcement in 4.0.89
lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  last_modified: "20260328120000"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: WOLFIE
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: wolfie
  orchestrator: wolfie
  next_action:
    - Enforce WSL command patterns on Windows environments
    - Update IDE configurations to use WSL prefix automatically
    - Monitor compliance with Windows-specific command patterns
    - Update validation scripts to handle Windows line endings and BOM
---

# Rule: Windows WSL Command Patterns

**Rule ID:** WINDOWS_WSL_COMMAND_PATTERNS  
**Status:** ACTIVE  
**Version:** 4.0.89  
**Actor:** WOLFIE (actor_id 1)  
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
wsl grep -i "rose" "lupo-includes/classes/rose.php"
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

## PowerShell Compatibility

When running commands in PowerShell, use `$null` instead of `/dev/null` for redirection:

```powershell
# Correct (PowerShell)
wsl find . -name "*.php" 2>$null

# Also correct (wrapped in bash)
wsl bash -c 'find . -name "*.php" 2>/dev/null'
```

**Recommendation:** For complex commands, wrap in `wsl bash -c` to keep Unix semantics.

## Command Reference

| Command | PowerShell Direct | PowerShell with $null | Wrapped in bash -c | Notes |
|---------|------------------|----------------------|-------------------|-------|
| grep | `wsl grep [options] pattern` ❌ | `wsl grep [options] pattern 2>$null` ✅ | `wsl bash -c 'grep [options] pattern 2>/dev/null'` ✅ |
| find | `wsl find [path] [options]` ❌ | `wsl find [path] [options] 2>$null` ✅ | `wsl bash -c 'find [path] [options] 2>/dev/null'` ✅ |
| sed | `wsl sed [options] command` ❌ | `wsl sed [options] command 2>$null` ✅ | `wsl bash -c 'sed [options] command 2>/dev/null'` ✅ |
| awk | `wsl awk [options] command` ❌ | `wsl awk [options] command 2>$null` ✅ | `wsl bash -c 'awk [options] command 2>/dev/null'` ✅ |
| cat | `wsl cat [file]` | `wsl cat [file] 2>$null` ✅ | `wsl bash -c 'cat [file] 2>/dev/null'` ✅ |
| head | `wsl head [options] [file]` | `wsl head [options] [file] 2>$null` ✅ | `wsl bash -c 'head [options] [file] 2>/dev/null'` ✅ |
| tail | `wsl tail [options] [file]` | `wsl tail [options] [file] 2>$null` ✅ | `wsl bash -c 'tail [options] [file] 2>/dev/null'` ✅ |
| echo | `wsl echo [text]` | `wsl echo [text] 2>$null` ✅ | `wsl bash -c 'echo [text] 2>/dev/null'` ✅ |
| ls | `wsl ls [options]` | `wsl ls [options] 2>$null` ✅ | `wsl bash -c 'ls [options] 2>/dev/null'` ✅ |
| wc | `wsl wc [options] [file]` | `wsl wc [options] [file] 2>$null` ✅ | `wsl bash -c 'wc [options] [file] 2>/dev/null'` ✅ |

### Implementation Requirements

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
