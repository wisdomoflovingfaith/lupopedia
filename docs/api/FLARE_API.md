# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/api/FLARE_API.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "api"
  purpose: "Web API specification for FLARE header retrieval and processing"
  dialog_message: "Recommended next step: create actors/1007 profile and align any remaining docs/examples to the required FLARE prologue format."
  mood_rgb: "4B0082"
  traits: ["canonical", "api", "external_interface"]
  tags: ["flare", "api", "web", "headers", "external_agents"]
  lupo_agent: "codex-ide"

flare.footer:
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/api/FLIP_API.md", type: "supersedes", weight: 0.8 }
    - { to: "app/Services/FlareValidatorService.php", type: "implements", weight: 0.8 }
  semantic_tags: ["flare", "api", "web", "headers", "external_interface", "canonical"]
---

# FLARE Header Web API

**Status:** Permanent.  
**Version:** 4.0.47.  
**Purpose:** Enable any AI agent (kernel actors, external like Grok) to "flare" file headers via web browsing.  
**Supersedes:** FLIP_API.md (File-Level Inference Protocol API)

---

## Required Header Prologue

All FLARE headers must start with the exact prologue line below, followed immediately by the YAML delimiter and `flare.headers`.

```text
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
```

---
## � Migration from FLIP API

The FLARE API maintains backward compatibility with the FLIP API endpoint during migration:

| Old Endpoint | New Endpoint | Status |
|--------------|--------------|--------|
| `/api/flip-header.php` | `/api/flare-header.php` | Both accepted until 4.1.0 |
| `flip-header.php` | `flare-header.php` | Internal implementation updated |

**Timeline:**
- 4.0.47-4.0.50: Both endpoints accepted (migration window)
- 4.1.0: FLIP endpoint deprecated, FLARE required
- 4.1.0+: Legacy endpoint redirects to FLARE endpoint

---

## Endpoint

**GET** `{LUPOPEDIA_PUBLIC_PATH}/api/flare-header.php`

Subdir-aware: for installs under a subdir (e.g. `/lupopedia`), use the full path: `/lupopedia/api/flare-header.php`.

### Parameters (one required)

| Parameter     | Type   | Description                                  |
|---------------|--------|----------------------------------------------|
| `path`        | string | `file_path_from_root` (e.g. `docs/doctrine/FLARE/FLARE_DOCTRINE.md`) |
| `url`         | string | `content_url` or `custom_path`               |
| `content_id`  | int    | `content_id` from `lupo_contents`            |

**Precedence:** If multiple params are present, `path` > `url` > `content_id`.

| Parameter   | Description                                              |
|-------------|----------------------------------------------------------|
| `format`    | Optional. `yaml` — return raw YAML (`Content-Type: text/yaml`). Default: JSON. |
| `include_footer` | Optional. `true` — include flare.footer section. Default: `false`. |
| `validate`   | Optional. `true` — validate header and return validation results. Default: `false`. |
| `suggest_edges` | Optional. `true` — suggest edges based on content analysis and DB relationships. Default: `false`. |

### Example URLs

- `https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- `https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/doctrine/FLARE/FLARE_DOCTRINE.md&format=yaml`
- `https://lupopedia.com/lupopedia/api/flare-header.php?content_id=2001&include_footer=true`
- `https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/example.md&validate=true`
- `https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/example.md&suggest_edges=true`
- `https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/example.md&include_footer=true&validate=true&suggest_edges=true`

### curl examples

```bash
curl "https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/doctrine/FLARE/FLARE_DOCTRINE.md"
curl "https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/doctrine/FLARE/FLARE_DOCTRINE.md&format=yaml"
curl "https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/example.md&include_footer=true&validate=true"
curl "https://lupopedia.com/lupopedia/api/flare-header.php?path=docs/example.md&suggest_edges=true"
```

---

## Response

### Success (suggest_edges=true)

**Content-Type:** `application/json`

```json
{
    "header": "---\n# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)\nflare.headers:\n  file_path_from_root: \"docs/example.md\"\n  system_version: \"4.0.47\"\n  ...\n---",
    "footer": "---\nflare.footer:\n  outbound_edges:\n    - { to: \"docs/reference.md\", type: \"references\", weight: 1.0 }\n  semantic_tags: [\"example\", \"demo\"]\n---",
    "resolved": true,
    "channel_id": 1,
    "validation": {
        "valid": true,
        "errors": [],
        "warnings": [],
        "suggestions": []
    },
    "suggested_edges": [
        {
            "to": "docs/related.md",
            "type": "references",
            "weight": 0.9,
            "reason": "Internal link: 'Related Documentation'",
            "source": "content_analysis"
        },
        {
            "to": "docs/doctrine/database/lupo_actors.md",
            "type": "table_relationship",
            "weight": 0.8,
            "reason": "Foreign key: actor_id references lupo_actors",
            "source": "toon_schema"
        }
    ]
}
```

### Success (default JSON)

**Content-Type:** `application/json`

```json
{
    "header": "---\n# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)\nflare.headers:\n  file_path_from_root: \"docs/example.md\"\n  system_version: \"4.0.47\"\n  ...\n---",
    "footer": "---\nflare.footer:\n  outbound_edges:\n    - { to: \"docs/reference.md\", type: \"references\", weight: 1.0 }\n  semantic_tags: [\"example\", \"demo\"]\n---",
    "resolved": true,
    "channel_id": 1,
    "validation": {
        "valid": true,
        "errors": [],
        "warnings": ["Using legacy flip.headers format - consider migrating to flare.headers"]
    }
}
```

### Success (format=yaml)

**Content-Type:** `text/yaml`

Raw YAML FLARE Header block (no JSON wrapper). If `include_footer=true`, both header and footer are returned.

### Success (validate=true)

**Content-Type:** `application/json`

```json
{
    "header": "...",
    "validation": {
        "valid": false,
        "errors": [
            "Missing required field: delegation_chain",
            "Invalid actor_id: 1007 (not found in registry)"
        ],
        "warnings": [
            "Using legacy flip.headers format",
            "Missing optional field: purpose"
        ],
        "suggestions": [
            "Add delegation_chain: \"1001:10000\"",
            "Update actor_id to valid value from registry"
        ]
    }
}
```

### Error Example

**Content-Type:** `application/json`

```json
{
    "error": "Validation failed",
    "details": {
        "missing_fields": ["delegation_chain"],
        "invalid_fields": {
            "actor_id": "9999 not found in registry"
        }
    }
}
```

**HTTP status codes:** 400 (invalid/missing params), 404 (not found), 500 (internal).

| 404    | Content not found (path/url/content_id)        |
| 500    | Config not loaded; bootstrap failure (no details) |

---

## Validation (LEXA)

### Path Validation
- **Path:** `validate_and_sanitize_path_from_root`; reject `..` and root escapes.
- **Url:** Must match `content_url` or `custom_path` in DB (parameterized lookup).
- **Content_id:** Must be numeric and exist in `lupo_contents`.
- **Parameterized SQL via PDO_DB.** No string concatenation.

### Header Validation
When `validate=true`, the API performs comprehensive validation:

```yaml
validation_rules:
  required_fields:
    - file_path_from_root
    - system_version
    - channel_id
    - actor_id
    - last_modified_utc
    - delegation_chain
    - artifact_type
  
  actor_validation:
    check_registry: true
    allow_offline: true
    warn_on_missing: true
  
  edge_validation:
    check_file_exists: true
    validate_weight_range: [0.5, 1.0]
    validate_edge_types: true
```

---

## Security & Future

- **CORS:** `Access-Control-Allow-Origin: *` for external agent browsing.
- **Rate limiting:** Optional future; not implemented in 4.0.47.
- **API key:** Future versions may require API key; if added, `lupo_api_keys` table (TOON + migration approval).
- **Authentication:** Support for JWT-based auth for enterprise deployments.

---

## Integration

### External Agents (e.g. Grok)
1. Browse the FLARE API endpoint
2. Parse the JSON `header` field or raw YAML
3. Infer file identity and lineage from the YAML only (FLARE inference)
4. Process `flare.footer` for relationship graph navigation
5. Use validation results to ensure header quality

### Local Agents
- Prefer `tools/generate_flare_header.py` for CLI
- Use this API when web access is required
- Use `validate=true` for development and CI/CD pipelines

### Migration Support
The API automatically detects and handles legacy formats:
- `flip.headers` → `flare.headers` (with warning)
- `flip.footer` → `flare.footer` (with warning)
- Mixed formats are supported during migration window

---

## Database Mapping Layer (Optional)

The FLARE header system supports an optional database mapping layer using the `X-LUPO-{table}.{column}` namespace:

```yaml
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "docs/example.md"
  system_version: "4.0.47"
  channel_id: 1
  # ... other required fields

# Database Mapping Layer (Optional)
X-LUPO-actors.actor_id: 1007
X-LUPO-channels.channel_id: 42
X-LUPO-dialog_messages.dialog_message_id: 2000
---
```

### Mapping Layer Rules

- **Optional:** Never required for inference or basic operations
- **Namespaced:** Must use `X-LUPO-{table}.{column}` format
- **Non-overriding:** Cannot override semantic FLARE fields
- **Opaque values:** Treated as strings, no schema inference
- **Tooling-only:** Intended for advanced tooling, migrations, and schema-aware agents

### Valid Examples

```
X-LUPO-actors.actor_id: 1007
X-LUPO-channels.channel_id: 42
X-LUPO-dialog_messages.dialog_message_id: 2000
X-LUPO-registry.registry_id: 9002031
```

### Invalid Examples

```
# Wrong format - missing dot separator
X-LUPO-actorid: 2038

# Wrong prefix - not X-LUPO-
X-FLARE-actors.actor_id: 1007

# Overrides semantic field - not allowed
X-LUPO-file_path: docs/example.md
```

The mapping layer is preserved in the `database_mappings` field of the parsed header and is included when formatting headers back to YAML.

---

## Error Handling

### Migration Warnings

During the migration window (4.0.47-4.1.0), the API emits warnings for legacy usage:

```json
{
    "warnings": [
        "Using legacy flip.headers format - consider migrating to flare.headers",
        "Using legacy flip.footer format - consider migrating to flare.footer"
    ]
}
```

### Validation Errors

Common validation errors and their fixes:

| Error | Fix |
|-------|-----|
| "Missing required field: delegation_chain" | Add `delegation_chain: "1007:10000"` |
| "Invalid actor_id: 1007" | Use valid actor ID from `actors/registry.json` |
| "Invalid artifact_type: invalid" | Use one of: doctrine, guide, directive, broadcast, status, profile |
| "Edge weight out of range: 1.5" | Use weight between 0.5 and 1.0 |

---

## Performance Considerations

- **Caching:** Headers are cached in memory for 5 minutes
- **Database queries:** All queries use prepared statements
- **File system:** Path validation prevents directory traversal attacks
- **Rate limiting:** Planned for 4.0.48 (100 requests/minute per IP)

---

*End of FLARE API specification.*




