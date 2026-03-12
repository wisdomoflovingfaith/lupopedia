# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/lupo-docs/status/report_on_flame_header_to_url_alias_and_database

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "Documentation for report_on_flame_header_to_url_alias_and_database.md"
    where:
      repo_paths: ["lupo-docs/status/report_on_flame_header_to_url_alias_and_database.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:56:13Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "status"
  file_path_from_root: "lupo-docs/status/report_on_flame_header_to_url_alias_and_database.md"
  file_hash: "f48935a614e25d7a1c75c2888f62b42fffb951fb55abaf2419cd7d3db88d694f"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for report_on_flame_header_to_url_alias_and_database.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-docs", "status", "report_on_flame_header_to_url_alias_and_databasemd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-docs/status/report_on_flame_header_to_url_alias_and_database.md", "http://www.lupopedia.com/lupo-docs/status/report_on_flame_header_to_url_alias_and_database"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

  updated_ymdhis: 20260304044500,
  message_type: "documentation",
  visibility: "public",
  priority: "high",
  artifact_type: "report",
  artifact_kind: "documentation"
}
lupopedia.see:
  mappings:
    - ["docs/status/report_on_flame_header_to_url_alias_and_database.md", "http://www.lupopedia.com/FLAME_MAPPING_REPORT"]
---

# Report on Flame Header to URL Alias and Database Mapping

## 1. Flame Header Overview

The FLARE protocol in v4.0.56 introduces the `flame` namespace for lifecycle hooks and URL discovery.

### Canonical Block Order
To ensure parser stability across multiple agents, headers MUST follow this order:
1. `lupopedia.init`: Prologue hook for requirements and pre-actions.
2. `lupopedia.conditional`: Guards (allow/deny) and 5W1H briefing.
3. `lupopedia.headers`: Standard file metadata.
4. `lupopedia.edges`: Semantic relationships.
5. `lupopedia.footer`: Verification metadata.
6. `lupopedia.see`: URL discovery mappings.
7. `lupopedia.close`: Epilogue hook for post-actions.

## 2. URL Alias Mapping (`lupopedia.see`)

The `lupopedia.see` block provides the link between the web-canonical URL and the repository-local file path.

### Schema
```yaml
lupopedia.see:
  mappings:
    - ["relative/path/to/file.md", "http://www.lupopedia.com/ALIAS"]
```

### Discovery Workflow
1. **Indexing**: The `lupo-tools/flare_see.py` script scans the repository for `lupopedia.see` blocks and builds a JSON index in `artifacts/index/flame_see_index.json`.
2. **Resolution**: The `lupo see <URL>` command queries the index to find the corresponding file path.

### Index Schema (`flame_see_index.json`)
```json
{
  "version": "4.0.56",
  "generated_utc": "20260304123000",
  "mappings": [
    {
      "path": "docs/doctrine/FLARE/FLARE_DOCTRINE.md",
      "url": "http://www.lupopedia.com/lupopedia/content/FLARE",
      "file_hash": "19033383ad2d953cc1db20c04d51c42ae3a87578bc0624d4ab36644d3397f423",
      "last_verified": "20260304"
    }
  ],
  "stats": {
    "total_mappings": 42,
    "unique_urls": 42,
    "unique_paths": 42
  }
}
```

### Collision Resolution
When multiple files claim the same URL:
1. First seen in index wins (by file_hash timestamp or system detection).
2. Conflict logged to `lupo_channel_logs` (Channel 42/0).
3. Manual review required (escalate to Channel 0).
4. All conflicting files flagged as **ERRORS** in validation.

## 3. Path Staleness Mitigation

If a file moves, the `file_path_from_root` and `lupopedia.see` mappings must stay in sync.

### Git Move Hook Implementation
```bash
#!/bin/bash
# git-move-hook.sh - Post-commit hook to update lupopedia.see mappings

# Get list of moved/renamed files in last commit
git diff --name-status HEAD~1 HEAD | grep '^R' | while read status old new; do
    echo "File moved: $old -> $new"
    
    # Update all files that reference the old path
    find docs/ prompts/ channels/ -name "*.md" -exec sed -i "s|$old|$new|g" {} \;
    
    # Rebuild index
    python lupo-tools/flare_see.py --rebuild
done
```

## 4. CLI Command Specification (`lupo see`)

The `lupo see <URL>` command supports multiple output modes:

### Mode 1: Resolution (Default)
```bash
lupo see http://www.lupopedia.com/FLARE
# Output: docs/doctrine/FLARE/FLARE_DOCTRINE.md
```

### Mode 2: Metadata (JSON)
```bash
lupo see http://www.lupopedia.com/FLARE --json
# Output: {"path": "docs/doctrine/FLARE/FLARE_DOCTRINE.md", "hash": "...", "verified": "20260304"}
```

### Mode 3: Open in Editor
```bash
lupo see http://www.lupopedia.com/FLARE --open
# Opens the resolved .md file in the default VS Code / IDE environment.
```

## 5. Database Integration

Flame actions tie into the Lupopedia database for audit logging and task orchestration.

### SQL Integration Examples
```sql
-- When lupo see is called (Audit Trail)
INSERT INTO lupo_audit_log (actor_id, action, target, created_ymdhis)
VALUES (1004, 'url_resolution', 'http://www.lupopedia.com/FLARE', 20260304123000);

-- When lupopedia.close registers completion (Faucet Interaction)
UPDATE lupo_agent_faucets 
SET last_used_ymdhis = 20260304123000, use_count = use_count + 1
WHERE faucet_name = 'url_resolution';

-- During ANUBIS ingestion (Content Primacy)
UPDATE lupo_contents 
SET canonical_url = 'http://www.lupopedia.com/FLARE'
WHERE file_hash = '19033383ad2d953cc1db20c04d51c42ae3a87578bc0624d4ab36644d3397f423';
```

## 6. Validation & Circularity Detection

### Validation Rules (`flare_validate.py`)
```python
def validate_flame_see(see_block):
    errors = []
    if not isinstance(see_block.get('mappings'), list):
        errors.append("lupopedia.see.mappings must be a list")
    
    for mapping in see_block['mappings']:
        if not isinstance(mapping, list) or len(mapping) != 2:
            errors.append(f"Invalid mapping format: {mapping}")
            continue
        
        path, url = mapping
        if not path.startswith(('docs/', 'channels/', 'prompts/')):
            errors.append(f"Path must be in docs/, channels/, or prompts/: {path}")
        
        if not url.startswith('http://www.lupopedia.com/'):
            errors.append(f"URL must be on lupopedia.com domain: {url}")
            
    return errors
```

### Circularity Detection
```python
def detect_circular_mappings(index):
    graph = {}
    for mapping in index['mappings']:
        path, url = mapping['path'], mapping['url']
        graph[url] = path # URL points to Path
    
    # Check for cycles (e.g., URL_A -> Path_B -> URL_B -> Path_A)
    # This requires building a full resolution map of aliases
    # ... detector logic here ...
    return None
```

## 7. Recommendations for Future Phases

### 7.1 Immediate (v4.0.57)
- [ ] **Add index schema documentation** to `FLARE_DOCTRINE.md`.
- [ ] **Implement collision resolution** with logging and manual review.
- [ ] **Add path staleness detection** via git hooks (script provided).
- [ ] **Document `lupo see` command** with --path, --json, --open modes.

### 7.2 Short-term (v4.0.58)
- [ ] **Integrate database logging** for all URL resolutions.
- [ ] **Add circularity detection** to validation scripts.
- [ ] **Create faucet automation** for `lupopedia.close` completions.
- [ ] **Update ANUBIS ingestion** to set `canonical_url` in `lupo_contents`.

### 7.3 Long-term (v4.1.0+)
- [ ] **Enforce `execution_mode: required`** for all new artifacts.
- [ ] **Auto-sync mappings** on file moves (git hooks).
- [ ] **Build web dashboard** for URL alias management.

---
**Timestamp**: 2026-03-04 04:45:00 UTC  
**Actor ID**: 1004 (Antigravity)
