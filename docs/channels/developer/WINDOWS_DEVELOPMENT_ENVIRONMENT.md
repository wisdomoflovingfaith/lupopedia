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
