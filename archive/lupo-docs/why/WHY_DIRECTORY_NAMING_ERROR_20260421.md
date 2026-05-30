# WHY VIOLATION REPORT
**Generated:** 20260421234000
**Failure ID:** WHY_DIRECTORY_NAMING_ERROR_20260421

## INCIDENT
Created alias configuration file in wrong directory:
- **Incorrect location:** `lupo-registry/agent_aliases.json`
- **Correct location:** `lupo-config/naming_aliases.json`

## ROOT CAUSE
1. **Misread system architecture** - Assumed "registry" was for all configuration
2. **Failed to consult PRD 16** - Header doctrine specifies config vs registry separation
3. **Mental model error** - Treated all JSON files as registry data rather than runtime behavior configuration
4. **Missing validation** - No check against canonical directory structure before file creation

## VIOLATED DOCTRINE
- **PRD 16 Section 4.2** - Header contract field definitions and file placement rules
- **PRD 98_A Section 2** - WHY files must be in `lupo-docs/why/` (applies to all file placement)
- **System Architecture** - `lupo-registry/` is for system truth, `lupo-config/` is for runtime behavior
- **PRD 00_C** - Forbidden patterns: incorrect filesystem organization

## IMPACT
1. **Configuration split** - Runtime behavior config mixed with system truth
2. **Discovery confusion** - Future agents won't know where to find alias configuration
3. **Validation failures** - Scripts expecting config in `lupo-config/` will fail
4. **System inconsistency** - Breaks established pattern of config vs registry separation

## REQUIRED CORRECTIONS

### Files to Move:
- **FROM:** `lupo-registry/agent_aliases.json`
- **TO:** `lupo-config/naming_aliases.json` (COMPLETED)

### Scripts to Update:
- **`lupo-scripts/agent_alias_resolver.py`** - Line 24: Update default path from `lupo-registry/agent_aliases.json` to `lupo-config/naming_aliases.json` (COMPLETED)

### Documentation to Update:
- **`lupo-docs/versions/4.1.4/status/agent_alias_normalization_report.md`** - Update all references from `lupo-registry/agent_aliases.json` to `lupo-config/naming_aliases.json` (COMPLETED)

## PRD FILES TO UPDATE
- `lupo-scripts/agent_alias_resolver.py` - Path reference updated (DONE)
- `lupo-docs/versions/4.1.4/status/agent_alias_normalization_report.md` - Documentation updated (DONE)

## PREVENTION RULE
**Deterministic Rule:** All runtime behavior configuration MUST be placed in `lupo-config/`. System truth and registry data MUST be placed in `lupo-registry/`. Before creating any file, verify its purpose:
- Runtime behavior → `lupo-config/`
- System truth/registry → `lupo-registry/`
- Documentation → `lupo-docs/`
- WHY files → `lupo-docs/why/`

## SYSTEM LEARNING NOTE
Future agents MUST understand:
1. **Directory structure is NOT flexible** - Each directory has specific purpose
2. **Config vs Registry separation** - Runtime behavior ≠ system truth
3. **File placement requires verification** - Check existing patterns before creating
4. **PRD 16 is authoritative** - Header doctrine defines placement rules

## Status
- **File moved** to correct location
- **Script updated** with correct path
- **Documentation updated** with correct references
- **WHY file created** as permanent system memory
- **Prevention rule established** for future file creation
