---

lupopedia.headers:
  lupopedia.schema: rule
  file_path_from_root: lupo-rules/root/FOOTER_VERSION_MANAGEMENT_RULE.md
  web_path: http://www.lupopedia.com/lupo-rules/root/FOOTER_VERSION_MANAGEMENT_RULE.md
  last_modified_utc: 20260327220000
  system_version: 4.0.89
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: rule
  artifact_kind: version_management
  purpose: Guidelines for managing version information in footer to avoid unnecessary maintenance overhead while maintaining traceability
  tags:
  - footer
  - version-management
  - maintenance
  - metadata
lupopedia.edges:
  outbound_edges:
    - to: lupo-rules/root/README.md
      type: documents
      weight: 1.0
      reason: Footer management rules indexed in root rules
lupopedia.footer:
  when_created: 20260327220000
  last_modified: 20260327220000
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
    - Add header validation to include footer management checks
    - Update validation scripts to check footer compliance
    - Train agents on proper footer usage patterns
    - Enforce footer rules in code review process
---

# Footer Version Management Rule

## Purpose

Establish guidelines for managing version information in `lupopedia.footer` to avoid unnecessary maintenance overhead while maintaining traceability.

## Problem Statement

**CURRENT ISSUE:** The `lupopedia.footer` requires manual updates every time a file is modified, creating maintenance churn.

**IMPACT:** 
- Version must be updated in multiple places for every change
- Easy to forget updates, leading to inconsistent metadata
- Unnecessary overhead for simple documentation changes

## Solution

### Use `when_created` Instead of `version`

**Primary Rule:** Use `when_created` field in `lupopedia.footer` for file creation timestamp, NOT `version`.

**Rationale:**
- File creation time doesn't change
- No need to update footer for simple content changes
- Only update when significant version changes occur

### When to Use `version`

**Use `version` field ONLY for:**
1. **Major version changes** - When doctrine or architecture significantly changes
2. **Release artifacts** - When creating official release documentation
3. **Schema changes** - When database structure is modified
4. **API changes** - When interfaces are updated
5. **Migration scripts** - When upgrade procedures change

### Footer Field Guidelines

#### Standard Footer (Most Files)
```yaml
lupopedia.footer:
  when_created: YYYYMMDDHHIISS    # File creation timestamp (DOES NOT CHANGE)
  last_modified: YYYYMMDDHHIISS   # Last significant modification
  verified_by:                       # Verification information
    identity_type: actor
    actor_id: [number]
    agent_name_identity: [name]
  verified_via:
    type: [faucet|cli|api]
    faucet_slug: [slug]              # Only if type=faucet
  orchestrator: [name]
  next_action: [array]                # Required
```

#### Release Artifact Footer
```yaml
lupopedia.footer:
  when_created: YYYYMMDDHHIISS
  version: [version]                 # For release artifacts
  approved_for_release: [version]  # For release artifacts
  approval_status: [status]             # For release artifacts
  approval_target_version: [version]     # For release artifacts
  approval_status_utc: YYYYMMDDHHIISS # For release artifacts
  approval_status_by: [name]           # For release artifacts
  approved_by_actor_id: [number]        # For release artifacts
  approved_utc: YYYYMMDDHHIISS       # For release artifacts
  # ... other standard fields
```

## Implementation Guidelines

### 1. Set `when_created` on File Creation
When creating new files, set the creation timestamp:
```yaml
lupopedia.footer:
  when_created: 20260327220000   # File creation time
  # ... other fields
```

### 2. Update `last_modified` Only When Necessary
Only update `last_modified` when:
- Content significantly changes (not just typo fixes)
- Structure or purpose changes
- Version bump required
- Major milestone reached

### 3. Use `version` for Release Management
For release-related artifacts (changelog, plan, version docs):
```yaml
lupopedia.footer:
  version: 4.0.89               # Release version
  approved_for_release: 4.0.89    # What this release approves
  # ... other fields
```

### 4. Automated Footer Management

#### Script Suggestion
```bash
#!/bin/bash
# Update footer with current timestamp (only when needed)
update_footer() {
    local file="$1"
    if [ -n "$1" ]; then
        echo "Usage: update_footer.sh [--force] [file]"
        return 1
    fi
    
    # Only update if significant change
    if [ "$2" != "--force" ]; then
        echo "Checking if significant change..."
        # Add logic to detect significant changes here
        # return 0 if not significant
    fi
    
    # Update footer
    sed -i "s/last_modified_utc: .*/last_modified_utc: $(date +%Y%m%d%H%M%S)/" "$file"
    
    echo "Footer updated: $file"
}
```

## Validation Rules

### Check Footer Compliance
```bash
# Check for proper footer usage
python lupo-scripts/validate_footers.py [directory]

# Validate specific file
python lupo-scripts/validate_footers.py [file]
```

### Automated Enforcement

Files violating footer management rules should be flagged:
1. Using `version` for non-release artifacts
2. Missing `when_created` field
3. Unnecessary `last_modified` updates
4. Version field without release context

## Examples

### ✅ Correct Usage
```yaml
# Doctrine file - created once, rarely modified
lupopedia.footer:
  when_created: 20260327220000
  last_modified: 20260327220000
  verified_by: { identity_type: actor, actor_id: 1, agent_name_identity: WOLFIE }
  # No version field needed
```

### ❌ Incorrect Usage
```yaml
# Documentation file - updated frequently, version churn
lupopedia.footer:
  version: 4.0.89              # Changes every time!
  last_modified: 20260327220000
  verified_by: { identity_type: actor, actor_id: 1, agent_name_identity: WOLFIE }
  # Unnecessary maintenance overhead
```

## Migration Path

### Phase 1: Education
1. Document this rule in LUPOPEDIA_HEADERS_REQUIREMENT_RULE.md
2. Update validation scripts to check footer compliance
3. Train agents on proper footer usage

### Phase 2: Implementation
1. Update existing files to use `when_created`
2. Remove unnecessary `version` fields from non-release artifacts
3. Add footer management to validation scripts

### Phase 3: Enforcement
1. Reject files with incorrect footer usage
2. Automated validation in CI/CD pipeline
3. Manual review during code review

## Benefits

1. **Reduced Maintenance** - No need to update version for simple changes
2. **Improved Traceability** - `when_created` shows actual file creation time
3. **Clear Versioning** - `version` only used for actual releases
4. **Less Errors** - Fewer opportunities for version inconsistencies

---

**lupo_schema:** rule  
**tags:** footer, version-management, maintenance, metadata
