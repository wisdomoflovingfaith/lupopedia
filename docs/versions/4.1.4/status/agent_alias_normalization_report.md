---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.4/status/agent_alias_normalization_report.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.4/status/agent_alias_normalization_report.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/agent-alias-normalization-report.toon
  atoms_toon: null
  transcript_jsonl: 0/development/agent-alias-normalization-report
  artifact_type: documentation
  artifact_kind: report
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_01_A_CORE_IDENTITY_02_A_CHANNELS_DB_DESIGN_16_C_LUPOPEDIA_HEADERS
  title: Agent Alias Normalization System Report
  summary: Implementation of agent alias normalization to handle naming drift and contextual overlays in multi-agent orchestration.
---

# Agent Alias Normalization System Report

**Date:** 2026-04-21  
**Task:** Implement alias normalization for multi-agent orchestration  
**Status:** COMPLETED

## PROBLEM STATEMENT

The system operates under real multi-agent orchestration with fast human input, leading to naming drift:

- **Core agent typos:** `gemni` → `gemini`, `vish` → `vishwakarma`
- **IDE naming variants:** `cascade`, `castcade`, `windsurf` (same agent)
- **Role-based naming:** `rose` ↔ `dialog`, `thoth` ↔ `truth`, `lilith` ↔ `audit`, `ara` ↔ `grok`

These are NOT mistakes—they are contextual naming overlays that the system must handle gracefully.

## SOLUTION IMPLEMENTED

### 1. Alias Registry File
**Location:** `config/naming_aliases.json`

Structure:
```json
{
  "core_agents": {
    "gemini": {"canonical": "gemini", "variants": ["gemni"]},
    "vishwakarma": {"canonical": "vishwakarma", "variants": ["vish"]}
  },
  "cascade_agent": {
    "cascade": {"canonical": "cascade", "variants": ["castcade", "windsurf"]}
  },
  "role_agent_drift": {
    "rose": {"canonical": "rose", "variants": ["dialog"]},
    "thoth": {"canonical": "thoth", "variants": ["truth"]},
    "lilith": {"canonical": "lilith", "variants": ["audit"]},
    "ara": {"canonical": "ara", "variants": ["grok"]}
  }
}
```

### 2. Python Resolver Utility
**Location:** `scripts/agent_alias_resolver.py`

Features:
- **Exact matching only** - NO prefix matching for deterministic behavior
- **Case-insensitive matching** for human typing convenience
- **Collision detection** warns on duplicate mappings
- **Alias-domain canonical enforcement** - lowercase canonical names within alias domain ONLY
- **Reverse lookup map** for fast resolution
- **Fallback handling** if config file missing
- **CLI interface** for testing and validation
- **Debug logging** option for visibility
- **Domain-scoped rules** - Does NOT apply to PRD filenames or other naming domains

### 3. Normalization Rules
- **Exact match only:** No prefix matching - deterministic behavior required
- **Case insensitive:** All matching ignores case
- **Alias-domain lowercase:** Canonical names normalized to lowercase WITHIN ALIAS DOMAIN ONLY
- **Collision detection:** Duplicate mappings trigger warnings
- **Graceful fallback:** Unknown names pass through unchanged
- **Domain scoped:** Lowercase rule applies ONLY to alias registry, NOT to PRD filenames or other domains

## VERIFICATION RESULTS

### Test Suite Results
```
✓ gemni           → gemini
✓ vish            → vishwakarma  
✓ castcade        → cascade
✓ windsurf        → cascade
✓ dialog          → rose
✓ truth           → thoth
✓ audit           → lilith
✓ grok            → ara
✓ ca              → ca (no prefix matching - deterministic)
```

### Complete Mappings
- **gemini:** gemini, gemni
- **vishwakarma:** vishwakarma, vish
- **cascade:** cascade, castcade, windsurf
- **rose:** rose, dialog
- **thoth:** thoth, truth
- **lilith:** lilith, audit
- **ara:** ara, grok

## INTEGRATION POINTS

### Usage in Python
```python
from lupo_scripts.agent_alias_resolver import AgentAliasResolver

resolver = AgentAliasResolver()
canonical_name = resolver.resolve("gemni")  # Returns "gemini"
canonical_name = resolver.resolve("gemni", debug=True)  # Shows [ALIAS] debug output
```

### CLI Usage
```bash
# Test resolution
python scripts/agent_alias_resolver.py --test

# List all mappings
python scripts/agent_alias_resolver.py --list

# Resolve specific name with debug
python scripts/agent_alias_resolver.py gemni --debug

# Verify no prefix matching
python scripts/agent_alias_resolver.py ca  # Returns "ca" unchanged
```

## SYSTEM RESILIENCE

✅ **Handles typos gracefully** - Human typing errors normalized automatically  
✅ **IDE naming differences** - Multiple IDE names resolve to same agent  
✅ **Role-based context** - Functional names map to correct agents  
✅ **No filesystem changes** - Pure normalization layer  
✅ **Backward compatible** - Existing names continue to work  
✅ **Extensible** - New aliases easily added to JSON config  
✅ **Deterministic behavior** - Exact matching only, no guessing  
✅ **Collision detection** - Warns on configuration conflicts  
✅ **Debug visibility** - Optional logging when aliases are used  

## FUTURE CONSIDERATIONS

1. **Dynamic updates:** Consider watching config file for changes
2. **Fuzzy matching:** Explicitly FORBIDDEN - Alias resolution must remain exact-match, registry-driven, and deterministic
3. **Context-aware resolution:** Context MAY influence agent selection but MUST NOT change canonical alias resolution (global and deterministic)
4. **Alias analytics:** Track which aliases are used most frequently
5. **Auto-suggestion:** Suggest canonical names for unknown inputs

## FILES CREATED/MODIFIED

- **NEW:** `config/naming_aliases.json` - Alias configuration
- **NEW:** `scripts/agent_alias_resolver.py` - Resolver utility
- **NEW:** `docs/versions/4.1.5/status/agent_alias_normalization_report.md` - This report
- **REMOVED:** `registry/agent_aliases.json` - Moved to config

## CRITICAL FIXES APPLIED

1. **Removed prefix matching** - Eliminates non-deterministic behavior
2. **Domain-scoped canonical enforcement** - Lowercase normalization applies to alias domain ONLY
3. **Added collision detection** - Warns on duplicate alias mappings
4. **Moved config to config** - Proper separation of concerns
5. **Added debug logging** - Optional visibility into alias resolution

## NAMING DOMAIN CLARIFICATION

### IMPORTANT: Domain-Specific Naming Rules

**PRD Naming Domain:**
- Format: `UPPERCASE_AND_UNDERSCORED`
- Example: `50_B_CRAFTY_SYNTAX_MODIFIED_FEATURES.md`
- Rule: NEVER apply lowercase normalization to PRD filenames

**Agent Identity Domain (Alias Registry):**
- Format: lowercase canonical names
- Example: `gemini`, `cascade`, `lilith`
- Rule: Apply lowercase normalization within alias domain only

**Code File Naming Domain:**
- Current status: Inconsistent
- Future: Requires explicit doctrine development
- Rule: Do not apply alias domain rules to code files

**WARNING:** Never cross-apply naming rules between domains. Each domain has its own canonical conventions.

---

**Resolution:** The system now handles agent naming drift gracefully through a configurable alias normalization layer that maintains filesystem authority while providing flexible name resolution.
