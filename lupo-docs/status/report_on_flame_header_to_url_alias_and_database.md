  updated_ymdhis: 20260304044500,
  message_type: "documentation",
  visibility: "public",
  priority: "high",
  artifact_type: "report",
  artifact_kind: "documentation"
}
flame.see:
  mappings:
    - ["docs/status/report_on_flame_header_to_url_alias_and_database.md", "http://www.lupopedia.com/FLAME_MAPPING_REPORT"]
---

# Report on Flame Header to URL Alias and Database Mapping

## 1. Flame Header Overview

The FLARE protocol in v4.0.56 introduces the `flame` namespace for lifecycle hooks and URL discovery.

### Canonical Block Order
To ensure parser stability across multiple agents, headers MUST follow this order:
1. `flame.init`: Prologue hook for requirements and pre-actions.
2. `flare.conditional`: Guards (allow/deny) and 5W1H briefing.
3. `flare.headers`: Standard file metadata.
4. `flare.edges`: Semantic relationships.
5. `flare.footer`: Verification metadata.
6. `flame.see`: URL discovery mappings.
7. `flame.close`: Epilogue hook for post-actions.

## 2. URL Alias Mapping (`flame.see`)

The `flame.see` block provides the link between the web-canonical URL and the repository-local file path.

### Schema
```yaml
flame.see:
  mappings:
    - ["relative/path/to/file.md", "http://www.lupopedia.com/ALIAS"]
```

### Discovery Workflow
1. **Indexing**: The `lupo-tools/flare_see.py` script scans the repository for `flame.see` blocks and builds a JSON index in `artifacts/index/flame_see_index.json`.
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

If a file moves, the `file_path_from_root` and `flame.see` mappings must stay in sync.

### Git Move Hook Implementation
```bash
#!/bin/bash
# git-move-hook.sh - Post-commit hook to update flame.see mappings

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

-- When flame.close registers completion (Faucet Interaction)
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
        errors.append("flame.see.mappings must be a list")
    
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
- [ ] **Create faucet automation** for `flame.close` completions.
- [ ] **Update ANUBIS ingestion** to set `canonical_url` in `lupo_contents`.

### 7.3 Long-term (v4.1.0+)
- [ ] **Enforce `execution_mode: required`** for all new artifacts.
- [ ] **Auto-sync mappings** on file moves (git hooks).
- [ ] **Build web dashboard** for URL alias management.

---
**Timestamp**: 2026-03-04 04:45:00 UTC  
**Actor ID**: 1004 (Antigravity)
