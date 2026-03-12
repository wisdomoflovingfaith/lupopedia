# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\FLARE_HEADERS_QUICK_REFERENCE.md"
  file_hash: "51b2733b644c2b8fab751a431d620aa52d0589498df1ad0ace0b921028c035ae"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
lupopedia.headers:
  file_path_from_root: "docs/FLARE_HEADERS_QUICK_REFERENCE.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "guide"
  purpose: "Fast reference for implementing FLARE headers in .md files"
  dialog_message: "Recommended next step: create actors/1007 profile and align any remaining docs/examples to the required FLARE prologue format."
  mood_rgb: "4B0082"
  traits: ["canonical", "comprehensive"]
  tags: ["flare_headers", "quick_reference", "implementation_guide"]
  lupo_agent: "codex-ide"
lupopedia.edges:
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.8 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "channels/0/broadcasts/20260224163100_0_10000_minimum_flare_header_requirements.md", type: "references", weight: 1.0 }
  semantic_tags: ["flare_headers", "quick_reference", "implementation_guide", "4.0.47"]

lupopedia.footer:
  last_verified: "20260226"
  last_verified_by: "windsurf"
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
- **actor_ip:** IP address or system identifier of the author
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
lupopedia.headers:
  file_path_from_root: "docs/guide/example.md"
  system_version: "4.1.0"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260226"
  delegation_chain: "1007:10000"
  artifact_type: "guide"

lupopedia.edges:
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }

lupopedia.footer:
  view_count: 150
  last_verified: "20260226"
---
```

### Doctrine File
```yaml
---
lupopedia.headers:
  file_path_from_root: "channels/0/broadcasts/example_doctrine.md"
  system_version: "4.1.0"
  channel_id: 0
  actor_id: 1007
  last_modified_utc: "20260226"
  delegation_chain: "1007:10000"
  artifact_type: "doctrine"

lupopedia.edges:
  semantic_tags: ["doctrine", "system"]

lupopedia.footer:
  view_count: 500
  like_count: 0
  share_count: 0
  last_verified: "20260226"
  last_verified_by: "windsurf"
---
```

## 🗺️ **lupopedia.edges Format**

```yaml
lupopedia.edges:
  outbound_edges:
    - { to: "docs/related.md", type: "references", weight: 1.0, reason: "Primary reference" }
    - { to: "docs/toons/lupo_table.toon.json", type: "schema_reference", weight: 1.0 }
  
  inbound_edges:
    - { from: "docs/other.md", type: "references", weight: 0.8, last_seen: "20260227" }
  
  semantic_tags: ["tag1", "tag2", "tag3"]
```

## 📊 **lupopedia.footer Format (Engagement Snapshot)**

```yaml
lupopedia.footer:
  view_count: 1234
  like_count: 56
  share_count: 12
  last_verified: "20260227"
  last_verified_by: "windsurf"
```

## 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

## 🔧 **Date Format

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
6. **Incorrect Header Format** - Must use `lupopedia.headers` not legacy keys
7. **Missing Required Fields** - delegation_chain and artifact_type are REQUIRED
8. **JSON-style Objects** - Use YAML format, not JSON with trailing commas

## 📚 **Reference Documents**

- **Core Doctrine:** `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- **Minimum Requirements:** `channels/0/broadcasts/20260224163100_0_10000_minimum_flare_header_requirements.md`
- **Validator Service:** `app/Services/FlareValidatorService.php`
- **Actor Registry:** `actors/registry.json`

---

**Quick Reference Complete** 🎯
