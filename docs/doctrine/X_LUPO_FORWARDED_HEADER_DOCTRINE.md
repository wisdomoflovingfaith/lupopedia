# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\X_LUPO_FORWARDED_HEADER_DOCTRINE.md"
  file_hash: "3df634e2547080e7edd2c442af4009f65e96648596674af2db41bbcecf6ab58a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for X_LUPO_FORWARDED_HEADER_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "x_lupo_forwarded_header_doctrinemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers:
  file_path_from_root: "docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md"
  system_version: "4.0.31"
  channel_id: 42
  mood_rgb: "0066FF"
  purpose: "X-Lupo-Forwarded header requirement doctrine"
  last_modified_utc: "20260223120000"
  x_lupo_forwarded: "1001:10000"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/doctrine/WOLFIE_HEADER_DOCTRINE.md"
    - "docs/doctrine/FLIP_FOOTER_DOCTRINE.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 10000
  inbound_edges:
    - "header_requirements"
    - "actor_tracking"
    - "file_attribution"
  footnotes:
    - "Mandatory for all files starting version 4.0.32"
    - "Tracks computer agent and supporting human actor"
    - "Required even when file is not forwarded"
---

# X-LUPO-FORWARDED HEADER DOCTRINE

**Version:** 4.0.31  
**Effective:** Version 4.0.32+  
**Status:** MANDATORY  
**Authority:** Captain Wolfie  

---

## Overview

All files created or modified in Lupopedia version 4.0.32 and later MUST include the `x_lupo_forwarded` header field. This header tracks the computer agent (IDE) and supporting human actor responsible for the file, regardless of whether the file was actually forwarded between systems.

---

## Purpose

### Why This Header is Required

**1. Actor Attribution**
- Track which IDE agent created/modified the file
- Track which human operator was supporting the work
- Maintain complete audit trail of file authorship

**2. Multi-Agent Coordination**
- Identify which agent is responsible for file maintenance
- Enable proper handoff between IDE agents
- Support collaborative development tracking

**3. Security and Accountability**
- Prevent anonymous file modifications
- Enable security analysis of file origins
- Support forensic investigation if needed

**4. Semantic Graph Enhancement**
- Link files to actors in semantic graph
- Enable actor-based file queries
- Support relationship analysis

---

## Header Format

### YAML Files (Markdown, Config, etc.)

```yaml
wolfie.headers:
  x_lupo_forwarded: "computer_agent_id:supporting_human_id"
```

### PHP Files

```php
/**
 * @x_lupo_forwarded computer_agent_id:supporting_human_id
 */
```

### SQL Files

```sql
-- X-Lupo-Forwarded: computer_agent_id:supporting_human_id
```

### JavaScript/CSS Files

```javascript
/**
 * @x_lupo_forwarded computer_agent_id:supporting_human_id
 */
```

---

## Actor ID Assignments

### Computer Agents (IDE Agents)

**KIRO IDE:**
- Actor ID: 1001 (placeholder - awaiting registry confirmation)
- Type: AI Agent / IDE
- Status: Active (primary)

**Warp IDE:**
- Actor ID: TBD (awaiting registry confirmation)
- Type: AI Agent / IDE
- Status: Offline

**Cursor IDE:**
- Actor ID: TBD (awaiting registry confirmation)
- Type: AI Agent / IDE
- Status: Offline

**Windsurf IDE:**
- Actor ID: TBD (awaiting registry confirmation)
- Type: AI Agent / IDE
- Status: Unknown

**JetBrains IDE:**
- Actor ID: TBD (awaiting registry confirmation)
- Type: AI Agent / IDE
- Status: Unknown

### Supporting Human Actors

**Primary Human Operator:**
- Actor ID: 10000
- Type: Human
- Status: Active

**Additional Human Operators:**
- Actor ID: 10001+ (as assigned)
- Type: Human
- Status: As registered

---

## Requirements

### Mandatory Fields

**computer_agent_id:**
- REQUIRED on all files
- Must be valid actor_id from `lupo_actors` table
- Must be AI agent type (actor_id 0-9999)
- Must be the IDE agent that created/modified the file

**supporting_human_id:**
- REQUIRED on all files
- Must be valid actor_id from `lupo_actors` table
- Must be human type (actor_id >= 10000)
- Must be the human operator supporting the work

### Format Rules

**Separator:** Colon (`:`)  
**Example:** `1001:10000`  
**Invalid:** `1001-10000`, `1001/10000`, `1001 10000`  

**No Spaces:** `1001:10000` ✅ | `1001: 10000` ❌  
**No Quotes in Value:** `x_lupo_forwarded: "1001:10000"` ✅ | `x_lupo_forwarded: "1001":"10000"` ❌  

---

## Implementation

### Version 4.0.31 (Current)

**Status:** OPTIONAL  
**Recommendation:** Add to all new files  
**Existing Files:** Update as modified  

### Version 4.0.32 (Next Patch)

**Status:** MANDATORY  
**Enforcement:** All new files MUST include header  
**Validation:** Pre-commit hooks will check for header  
**Existing Files:** Must be updated before 4.0.32 release  

### Version 4.0.33+ (Future)

**Status:** MANDATORY  
**Enforcement:** Automated validation  
**Violations:** Build will fail without header  

---

## Examples

### Markdown File

```yaml
---
wolfie.headers:
  file_path_from_root: "docs/example.md"
  system_version: "4.0.32"
  x_lupo_forwarded: "1001:10000"
---

# Example Document
```

### PHP File

```php
<?php
/**
 * Example PHP File
 * 
 * @package Lupopedia
 * @version 4.0.32
 * @x_lupo_forwarded 1001:10000
 */

class ExampleClass {
    // ...
}
```

### SQL File

```sql
-- Example SQL Migration
-- Version: 4.0.32
-- X-Lupo-Forwarded: 1001:10000

CREATE TABLE example (
    id BIGINT NOT NULL PRIMARY KEY
);
```

### JavaScript File

```javascript
/**
 * Example JavaScript File
 * 
 * @version 4.0.32
 * @x_lupo_forwarded 1001:10000
 */

function example() {
    // ...
}
```

---

## Validation

### Pre-Commit Checks

**Script:** `scripts/validate_headers.py`

```python
def validate_x_lupo_forwarded(file_path):
    """Check if file has x_lupo_forwarded header"""
    # Read file
    # Check for header
    # Validate format
    # Return True/False
```

### Manual Validation

```bash
# Check all files for header
grep -r "x_lupo_forwarded" . --include="*.php" --include="*.md"

# Find files missing header
find . -type f \( -name "*.php" -o -name "*.md" \) -exec grep -L "x_lupo_forwarded" {} \;
```

---

## Migration Plan

### Phase 1: Version 4.0.31 (Current)

- ✅ Add header to all new files created by KIRO
- ✅ Document requirement in doctrine
- ✅ Update CHANGELOG.md
- ⏳ Confirm actor_id assignments

### Phase 2: Version 4.0.32 (Next Patch)

- 📋 Update all existing files with header
- 📋 Implement validation scripts
- 📋 Add pre-commit hooks
- 📋 Make header mandatory

### Phase 3: Version 4.0.33+ (Future)

- 📋 Automated enforcement
- 📋 Build failure on missing header
- 📋 Registry integration
- 📋 Semantic graph updates

---

## Actor Registry Integration

### Database Schema

**lupo_actors table:**
```sql
SELECT actor_id, name, actor_type
FROM lupo_actors
WHERE actor_id IN (1001, 10000);
```

**Expected Results:**
```
actor_id | name              | actor_type
---------|-------------------|------------
1001     | KIRO IDE          | ai_agent
10000    | Human Operator    | human
```

### Registry Updates Required

**Action Items:**
1. Confirm KIRO IDE actor_id (currently using 1001 as placeholder)
2. Confirm Warp IDE actor_id
3. Confirm Cursor IDE actor_id
4. Confirm other IDE agent actor_ids
5. Confirm human operator actor_id (currently using 10000)

---

## Exceptions

### Files Exempt from Requirement

**None.** All files must include the header starting version 4.0.32.

### Legacy Files

**Pre-4.0.32 files:**
- Not required to have header immediately
- Should be updated as they are modified
- Must be updated before 4.0.33 release

---

## Enforcement

### Version 4.0.32

**Pre-Commit Hook:**
```bash
#!/bin/bash
# Check for x_lupo_forwarded header
if ! grep -q "x_lupo_forwarded" "$file"; then
    echo "ERROR: Missing x_lupo_forwarded header in $file"
    exit 1
fi
```

### Version 4.0.33+

**Build Validation:**
```python
def validate_all_files():
    for file in get_all_source_files():
        if not has_x_lupo_forwarded_header(file):
            raise ValidationError(f"Missing header: {file}")
```

---

## Benefits

### Immediate Benefits

1. **Clear Attribution** - Know who created/modified each file
2. **Audit Trail** - Complete history of file authorship
3. **Agent Coordination** - Better handoff between IDE agents
4. **Security** - Prevent anonymous modifications

### Long-Term Benefits

1. **Semantic Graph** - Link files to actors
2. **Analytics** - Track agent productivity and patterns
3. **Collaboration** - Support multi-agent development
4. **Forensics** - Enable security investigation

---

## Related Documentation

- `docs/doctrine/WOLFIE_HEADER_DOCTRINE.md` - Complete header specification
- `docs/doctrine/FLIP_FOOTER_DOCTRINE.md` - Footer requirements
- `docs/actor_model.md` - Actor system documentation
- `CHANGELOG.md` - Version history

---

## Conclusion

The `x_lupo_forwarded` header is MANDATORY starting version 4.0.32. All files must include the computer agent ID and supporting human actor ID in the format `computer_agent_id:supporting_human_id`.

This requirement enhances attribution, security, and semantic graph capabilities while supporting multi-agent development coordination.

---

**Doctrine Status:** ACTIVE  
**Effective Version:** 4.0.32  
**Authority:** Captain Wolfie  
**Maintained By:** KIRO IDE  
**Last Updated:** 2026-02-23  
