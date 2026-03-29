---
lupopedia.headers:
  when_updated: "20260328120000"
  lupopedia.schema: meta_rule
  file_path_from_root: lupo-rules/root/RULE_FILES_HEADER_REQUIREMENT.md
  web_path: http://www.lupopedia.com/lupo-rules/root/RULE_FILES_HEADER_REQUIREMENT.md
  last_modified_utc: "20260328120000"
  system_version: 4.0.89
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: meta_rule
  artifact_kind: header_requirement
  purpose: Establish absolute requirement that ALL rule files in lupo-rules/root/ MUST have complete LUPOPEDIA headers
  tags:
  - headers
  - validation
  - requirements
  - enforcement
  - rule_files
  - enforce timestamp quoting
lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
      type: references
      weight: 1.0
      reason: Header format documentation for timestamp quoting
    - to: lupo-rules/root/README.md
      type: references
      weight: 0.95
      reason: Root rules index with header requirements
lupopedia.footer:
  when_created: "20260328120000"
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
    - Update all files with mixed timestamp quoting to use quoted strings
    - Update validation scripts to enforce timestamp quoting
    - Monitor for new files with unquoted timestamps
    - Enforce WSL command patterns on Windows environments
    - Update IDE configurations to use WSL prefix automatically
    - Ensure all rule files have complete LUPOPEDIA headers
---

# RULE FILES HEADER REQUIREMENT

## Purpose

Establishes absolute requirement that ALL rule files in `lupo-rules/root/` MUST have complete LUPOPEDIA headers. This is a meta-rule that enforces header requirement across all rule files.

## Rule Statement

**ALL FILES IN `lupo-rules/root/` DIRECTORY MUST HAVE COMPLETE LUPOPEDIA HEADERS**

No exceptions. No rule files may be created without:
- Complete `lupopedia.headers` block
- Complete `lupopedia.footer` block
- Complete `lupopedia.edges` block (if applicable)

## Enforcement Mechanism

### 1. Creation Validation
Any rule file created without complete headers MUST be rejected immediately:
- Files missing `lupopedia.headers` block → REJECT
- Files missing required fields in headers → REJECT
- Files with incomplete `lupopedia.footer` → REJECT

### 2. Automated Validation
All rule files must pass automated validation:
```bash
# Validate all rule files have headers
python lupo-scripts/validate_headers.py lupo-rules/root/ --require-headers

# Check specific rule file
python lupo-scripts/validate_headers.py lupo-rules/root/FILENAME.md
```

### 3. Manual Review
- **SESHAT:** Content review and validation
- **THOTH:** Database analysis and wisdom
- **MAAT:** Truth and justice verification

### 4. CI/CD Pipeline Enforcement
```yaml
# CI pipeline step
- name: validate_rule_headers
  run: python lupo-scripts/validate_headers.py lupo-rules/root/ --require-headers
  fail_on_error: true
```

## Required Header Fields for Rule Files

### Minimum Required (All Rule Files)
```yaml
lupopedia.headers:
  lupopedia.schema: rule                    # Required
  file_path_from_root: lupo-rules/root/[FILENAME].md  # Required
  web_path: http://www.lupopedia.com/lupo-rules/root/[FILENAME].md  # Required
  last_modified_utc: YYYYMMDDHHIISS           # Required
  system_version: 4.0.89                       # Required
  channel_id: 42                              # Required
  actor_id: 1                                  # Required
  actor_name: wolfie                            # Required
  delegation_chain: wolfie:root                   # Required
  artifact_type: rule                            # Required
  artifact_kind: [specific_kind]                   # Required
  purpose: [description]                        # Required
  tags:
    - [relevant_tags]                           # Optional but recommended
lupopedia.footer:
  version: 4.0.89                             # Required
  last_verified: YYYYMMDDHHIISS                   # Required
  verified_by:                                   # Required
    identity_type: actor                           # Required
    actor_id: 1                                   # Required
    agent_name_identity: WOLFIE                    # Required
  verified_via:                                   # Required
    type: faucet                                  # Required
    faucet_slug: wolfie                           # Required if type=faucet
  orchestrator: wolfie                            # Required
  next_action:                                   # Required
lupopedia.edges:
  outbound_edges:                                  # Required for files with references
    - to: [related_file]                          # Optional but recommended
      type: references                              # Required
      weight: 1.0                                 # Required
      reason: [description]                        # Optional but recommended
```

## Template for Rule Files

### Standard Rule File Template
```yaml
---
lupopedia.headers:
  lupopedia.schema: rule
  file_path_from_root: lupo-rules/root/[RULE_NAME].md
  web_path: http://www.lupopedia.com/lupo-rules/root/[RULE_NAME].md
  last_modified_utc: YYYYMMDDHHIISS
  system_version: 4.0.89
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: rule
  artifact_kind: [specific_category]
  purpose: [Clear description of rule purpose]
  tags:
    - [relevant_tags]
lupopedia.edges:
  outbound_edges:
    - to: [related_rule_or_file]
      type: references
      weight: 1.0
      reason: [Relationship description]
lupopedia.footer:
  version: 4.0.89
  last_verified: "20260328120000"
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
    - Update all files with mixed timestamp quoting to use quoted strings
    - Update validation scripts to enforce timestamp quoting
    - Monitor for new files with unquoted timestamps
    - Enforce WSL command patterns on Windows environments
    - Update IDE configurations to use WSL prefix automatically
---
```

## Validation Script Enhancement

### Enhanced validate_headers.py
The validation script must be enhanced to enforce header requirements on rule files:
```bash
# Validate all rule files have headers
python lupo-scripts/validate_headers.py lupo-rules/root/ --require-headers
```

### Windows-Specific Command Patterns

When working on Windows environments:
- **Use WSL prefix** for Unix commands: `wsl grep`, `wsl find`, `wsl sed`
- **Use native Windows commands** for Windows operations: `powershell`, `cmd`, `type`
- **PowerShell for file operations**: `Get-Content`, `Set-Content`, `Test-Path`
- **Quote file paths properly**: Use double quotes for paths with spaces

### Cross-Platform Compatibility

Prefer Python scripts over shell scripts for cross-platform compatibility:
- Python works on Windows, Linux, and macOS without modification
- Shell scripts require WSL prefix on Windows

### IDE Integration

Configure Windsurf IDE to:
1. **Use WSL prefix** automatically when executing shell commands
2. **Prefer Python scripts** for validation and file operations
3. **Use native PowerShell** for Windows-specific file operations
4. **Follow WINDOWS_WSL_COMMAND_PATTERNS.md** for all command patterns

### Testing

Test WSL availability:
```bash
wsl grep --version
```

If WSL is not available, fall back to native Windows commands or PowerShell.

## Consequences of Non-Compliance

### Immediate Rejection
Files created without proper headers:
1. **REJECT** the file immediately
2. **DO NOT COMMIT** files without headers
3. **REQUIRE** immediate correction before proceeding

### Review Process
1. **SESHAT Review** - Flag all header violations
2. **THOTH Analysis** - Determine impact on system integrity
3. **MAAT Judgment** - Assess compliance with truth requirements

## Relationship to Other Rules

This rule works with:
- **LUPOPEDIA_HEADERS_REQUIREMENT_RULE.md** - General header requirement
- **FOOTER_VERSION_MANAGEMENT_RULE.md** - Footer version management
- **FILE_BOUNDARY_VALIDATION_RULE.md** - File validation
- **LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md** - Version baseline

## Implementation Priority

**PRIORITY 1 - CRITICAL**
- All rule files MUST have complete headers
- Automated validation must enforce header requirements
- CI/CD pipeline must reject non-compliant files

**PRIORITY 2 - HIGH**
- Template compliance for all new rule files
- Training for agents on header requirements

---

**lupo_schema:** meta_rule  
**tags:** headers, validation, requirements, enforcement, rule-files
