# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\developer\WINDOWS_DEVELOPMENT_ENVIRONMENT.md"
  file_hash: "8bc2f3e22c77d8f39120dcc592c120993d7ea942128b74fca3ec8fc299ef6f56"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\developer\WINDOWS_DEVELOPMENT_ENVIRONMENT.md"
  file_hash: "957acd8052c50625a204ebe1bf9572c2d4ed41b619b35a7140df19da4075501b"
  file_path_from_root: "docs\channels\developer\WINDOWS_DEVELOPMENT_ENVIRONMENT.md"
  file_hash: "014a90a7fdae46d36a7ea4bf458feb621bca4b4812b5ad9877f92e9a534fa5dc"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Windows Development Environment Guidelines"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "developer", "windows_development_environmentmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Windows Development Environment Guidelines

## Overview

This project is developed on Windows 11 using PowerShell. All filesystem operations in the development environment must use Windows-native PowerShell commands.

## Command Requirements

### Allowed PowerShell Commands

When interacting with the filesystem in development, use these Windows-native commands:

- `Get-ChildItem` - List directory contents
- `Select-Object` - Filter and select object properties
- `Get-Content` - Read file contents
- `Set-Content` - Write file contents
- `Add-Content` - Append to files
- `Copy-Item` - Copy files and directories
- `Move-Item` - Move files and directories
- `Remove-Item` - Delete files and directories
- `Test-Path` - Test path existence
- `Resolve-Path` - Resolve path to absolute form

### Usage Examples

```powershell
# List TOON files
Get-ChildItem -Filter "*.toon"

# Get first 10 items
Get-ChildItem | Select-Object -First 10

# Read first 20 lines
Get-Content file.txt -TotalCount 20

# Read last 20 lines
Get-Content file.txt -Tail 20

# Copy files
Copy-Item -Path "source.txt" -Destination "backup.txt"

# Check if path exists
if (Test-Path "somefile.txt") {
    Write-Host "File exists"
}
```

### Forbidden Commands

The following Linux/Unix utilities are **NOT** allowed in the development environment:

- `ls` (allowed only as PowerShell alias, but do NOT pipe to Linux tools)
- `head`
- `tail`
- `grep`
- `sed`
- `awk`
- `cat` (allowed alias, but do NOT assume Linux behavior)
- Any Linux/Unix pipeline utilities

## Platform Considerations

### Development Environment
- **OS**: Windows 11
- **Shell**: PowerShell
- **Filesystem**: NTFS
- **Path Separator**: Backslash (`\`)
- **Case Sensitivity**: Case-insensitive

### Server Environment
- **OS**: Production and shared servers may be Linux
- **Code**: Write platform-neutral logic for server execution
- **Paths**: Use forward slashes (`/`) for cross-platform compatibility

## Best Practices

### For Development
1. Always use PowerShell native commands
2. Use full cmdlet names when possible for clarity
3. Test paths before operations
4. Handle Windows path separators correctly

### For Server Code
1. Write platform-neutral logic
2. Use `os.path` functions in Python for path operations
3. Avoid hardcoding path separators
4. Test on both Windows and Linux when possible

## Failure Mode

If a Linux command is attempted in the development environment, the system will respond with:

```
Operation blocked. Development environment is Windows. Use PowerShell commands only.
```

## Migration Notes

When converting from Linux-style commands:

| Linux Command | PowerShell Equivalent |
|---------------|----------------------|
| `ls -la` | `Get-ChildItem -Force` |
| `head -n 10` | `Get-Content file.txt -TotalCount 10` |
| `tail -n 10` | `Get-Content file.txt -Tail 10` |
| `grep "pattern"` | `Select-String "pattern"` |
| `cat file` | `Get-Content file` |
| `cp src dst` | `Copy-Item src dst` |
| `mv src dst` | `Move-Item src dst` |
| `rm file` | `Remove-Item file` |

## Compliance

All development tools, scripts, and automated systems must comply with these Windows PowerShell requirements. This ensures consistent behavior across the development environment and prevents platform-specific issues.