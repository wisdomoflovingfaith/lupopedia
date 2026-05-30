# FLARE Header Deduplication Script

## Purpose

Removes duplicate FLARE headers from markdown files, keeping only the first header block per FLARE doctrine requirements.

## Usage

```bash
# Dry run (scan only)
php scripts/dedupe_flare_headers.php . --dry-run

# Actual deduplication
php scripts/dedupe_flare_headers.php .
```

## Features

- **Recursive Processing**: Scans all `.md` files in repository
- **Header Detection**: Identifies FLARE header blocks using regex patterns
- **Deduplication Logic**: Keeps first header, removes all subsequent headers
- **Dry Run Mode**: Preview changes without modifying files
- **Statistics**: Reports files processed and headers removed
- **Error Handling**: Graceful handling of file read/write errors

## FLARE Doctrine Compliance

- **One Header Per File**: Each artifact gets exactly one FLARE header block
- **No Duplicates**: Multiple headers in same file violate doctrine
- **Version Control**: Prevents duplicate entries in Git history
- **Clean Repository**: Maintains repository hygiene and compliance

## Implementation

The script uses PHP to:
1. Scan directory recursively for `.md` files
2. Find all FLARE header blocks using regex pattern matching
3. Keep only the first header block found
4. Remove all additional header blocks
5. Provide detailed statistics on processing

## Output Example

```
=== FLARE Header Deduplication Summary ===
Files processed: 15
Headers removed: 3
Dry run: NO

⚠️  Duplicate headers found and removed!
```

## Integration

Can be integrated into:
- **Pre-commit hooks**: Automatic deduplication before commits
- **CI/CD pipelines**: Repository hygiene checks
- **Manual cleanup**: Periodic repository maintenance
- **Git hooks**: Prevent duplicate header commits

---

**Script Created**: 20260301  
**Author**: Windsurf (1002)  
**Version**: 4.0.52  
**Purpose**: Repository hygiene and FLARE compliance
