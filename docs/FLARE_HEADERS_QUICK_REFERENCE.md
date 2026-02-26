---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "docs/FLARE_HEADERS_QUICK_REFERENCE.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260226"
  delegation_chain: "1001:10000"
  artifact_type: "guide"
  purpose: "Fast reference for implementing FLARE headers in .md files"
  mood_rgb: "4B0082"
  traits: ["canonical", "comprehensive"]
  tags: ["flare_headers", "quick_reference", "implementation_guide"]
  lupo_agent: "windsurf"
flare.footer:
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.8 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "channels/0/broadcasts/20260224163100_0_10000_minimum_flare_header_requirements.md", type: "references", weight: 1.0 }
  semantic_tags: ["flare_headers", "quick_reference", "implementation_guide", "4.0.47"]
---

# FLARE Headers Quick Reference Guide

**Purpose:** Fast reference for implementing FLARE headers in .md files  
**Version:** 4.0.47  
**Also Known As:** FLIP, FLP, FLPH, WOLFIE, CROP  

## 📖 **What FLARE Stands For**

**FLARE** = **F**ile-**L**evel **A**ttribute and **R**elationship **E**xchange

FLARE is the formal rule set that governs how Lupopedia and its AI agents interpret files. When a file is "flared" to the system, agents must infer everything they need to know about that file entirely from the FLARE Header — without guessing, hallucinating, or requiring external context.

FLARE defines:
- **flare.headers** → File-Level Attributes (metadata)
- **flare.footer** → File-Level Relationships (graph edges)

Together they form the File-Level Attribute and Relationship Exchange layer of Lupopedia.

## 🚀 **Standard Header Format**

```yaml
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "path/from/root.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260226"
  delegation_chain: "1001:10000"
  artifact_type: "guide"
  purpose: "Brief description of file purpose"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "comprehensive"]
  tags: ["tag1", "tag2"]
  lupo_agent: "windsurf"
---
```

##  **Field Explanations**

### Required Fields
- **file_path_from_root:** Exact path from project root
- **system_version:** Current Lupopedia version (4.0.47)
- **channel_id:** Channel number (0=doctrine, 1=main, 42=development)
- **actor_id:** Author's actor ID (from actors/registry.json)
- **last_modified_utc:** Last update date (YYYYMMDD)
- **delegation_chain:** Actor delegation chain (e.g., "1001:10000")
- **artifact_type:** Type of artifact (doctrine|guide|directive|broadcast|status|profile)

### Optional Fields
- **purpose:** One-sentence description of file purpose
- **mood_rgb:** Color code for emotional state
- **artifact_kind:** Kind of artifact (table, file, component, etc.)
- **traits:** File characteristics
- **tags:** Descriptive tags for categorization
- **lupo_agent:** Agent name handling the file (can be inferred from actor_id)

## 🎯 **Channel ID Guide**

| Channel | Purpose | Example Use |
|---------|---------|-------------|
| 0 | System doctrines | channels/0/broadcasts/ |
| 1 | Main documentation | README.md, docs/guides/ |
| 42 | Development | channels/42/threads/ |
| 51 | Reserved | Future system use |
| 666 | Quarantine | channels/666/quarantine/ |
| 1000+ | User channels | Custom user spaces |

## 🔍 **Looking Up Actor IDs**

Always check the registry before using an actor ID:
- `actors/registry.json` - Master mapping
- `channels/*/actors/*/profile.md` - Actor profiles
- Command: `Lupopedia: Resolve Alias`

## 👥 **Actor ID Quick Reference**

| Actor | ID | Use When |
|--------|-----|----------|
| Captain Wolfie | 10000 | Official directives |
| KIRO | 1000 | Development tasks |
| Windsurf | 1001 | Coordination tasks |
| Cursor | 1002 | IDE tasks |
| Antigravity | 1003 | IDE tasks |
| Warp | 1004 | IDE tasks |
| Cascade | 1005 | IDE tasks |
| WOLFIE | 1 | Core AI tasks |
| LILITH | 2 | System tasks |
| ROSE | 3 | Dialog tasks |
| ERIS | 4 | Conflict analysis |
| METIS | 5 | Empathy intelligence |
| ANUBIS | 19 | System audit |
| VISHWAKARMA | 25 | System tasks |

### 👥 Additional Actors

For a complete list of all actors, including:
- Human users (10001+, 12150, etc.)
- Test agents (2000-2010)
- System agents (0, 19, 25, etc.)

See `actors/registry.json` - the master source of truth for all actor IDs.

## ⚡ **Common Examples**

### Documentation File
```yaml
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "docs/guide/example.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260226"
  delegation_chain: "1001:10000"
  artifact_type: "guide"
  purpose: "Example implementation guide"
  mood_rgb: "4B0082"
  traits: ["canonical", "comprehensive"]
  tags: ["guide", "example", "implementation"]
  lupo_agent: "windsurf"
---
```

### Status Report
```yaml
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "docs/status/example.md"
  system_version: "4.0.47"
  channel_id: 42
  actor_id: 1001
  last_modified_utc: "20260226"
  delegation_chain: "1001:10000"
  artifact_type: "status"
  purpose: "Task completion status"
  mood_rgb: "FF6B6B"
  traits: ["time-sensitive", "high-priority"]
  tags: ["status", "task", "completion"]
  lupo_agent: "windsurf"
---
```

### Doctrine File
```yaml
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "channels/0/broadcasts/example_doctrine.md"
  system_version: "4.0.47"
  channel_id: 0
  actor_id: 10000
  last_modified_utc: "20260226"
  delegation_chain: "10000:10000"
  artifact_type: "doctrine"
  purpose: "System doctrine definition"
  mood_rgb: "FFD700"
  traits: ["canonical", "system-critical"]
  tags: ["doctrine", "system", "definition"]
  lupo_agent: "wolfie"
---
```

## � **flare.footer Format**

### Required Footer Fields
```yaml
# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.footer:
  outbound_edges:
    - { to: "docs/related.md", type: "references", weight: 1.0, reason: "Primary reference" }
    - { to: "docs/toons/lupo_table.toon.json", type: "schema_reference", weight: 1.0 }
  
  inbound_edges:
    - { from: "docs/other.md", type: "references", weight: 0.8, last_seen: "20260227" }
  
  semantic_tags: ["tag1", "tag2", "tag3"]
  version: "4.0.47"
  last_verified: "20260227"
  last_verified_by: "windsurf"
```

**Important:** Always include the automation tip comment above `flare.footer` to promote tool adoption.

## �🔧 **Date Format

### Current Date Format
Format: `YYYYMMDD` UTC  
Example: `20260226` = Feb 26, 2026

### PHP
```php
$date = gmdate('Ymd'); // 20260226
```

## ⚠️ **Common Mistakes to Avoid**

1. **Wrong Actor ID** - Always check actors/registry.json
2. **Wrong Channel ID** - Match directory structure
3. **Old System Version** - Use current version (4.0.47)
4. **Missing Date** - Use YYYYMMDD UTC format
5. **Invalid YAML** - Validate syntax before commit
6. **Incorrect Header Format** - Must use `flare.headers` not legacy keys
7. **Missing Required Fields** - delegation_chain and artifact_type are REQUIRED
8. **JSON-style Objects** - Use YAML format, not JSON with trailing commas

## 📚 **Reference Documents**

- **Core Doctrine:** `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- **Minimum Requirements:** `channels/0/broadcasts/20260224163100_0_10000_minimum_flare_header_requirements.md`
- **Validator Service:** `app/Services/FlareValidatorService.php`
- **Actor Registry:** `actors/registry.json`

---

**Quick Reference Complete** 🎯
