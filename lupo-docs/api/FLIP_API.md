# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\api\FLIP_API.md"
  file_hash: "238838a7031cb45cbdc7389cdf9e267f185aaa4aa253194aab29d566294c4f2e"
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\api\FLIP_API.md"
  file_hash: "413875c301578262000b7e782627ce91ca0bc165eb783cd9b9f24eb1c6a7d9ad"
  file_path_from_root: "docs\api\FLIP_API.md"
  file_hash: "5ea711d12f338904243d4d6ec0eb16ea832033b9dad59cf5465a1efff41b8626"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIP_API.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "api", "flip_apimd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/api/FLIP_API.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/api/FLIP_API.md
---

# FLIP Header Web API

**Status:** Permanent.  
**Version:** 4.0.16.  
**Purpose:** Enable any AI agent (kernel actors, external like Grok) to "flip" file headers via web browsing.

---

## Endpoint

**GET** `{LUPOPEDIA_PUBLIC_PATH}/api/flip-header.php`

Subdir-aware: for installs under a subdir (e.g. `/lupopedia`), use the full path: `/lupopedia/api/flip-header.php`.

### Parameters (one required)

| Parameter     | Type   | Description                                  |
|---------------|--------|----------------------------------------------|
| `path`        | string | `file_path_from_root` (e.g. `docs/doctrine/FLIP/FLIP_DOCTRINE.md`) |
| `url`         | string | `content_url` or `custom_path`               |
| `content_id`  | int    | `content_id` from `lupo_contents`            |

**Precedence:** If multiple params are present, `path` > `url` > `content_id`.

| Parameter   | Description                                              |
|-------------|----------------------------------------------------------|
| `format`    | Optional. `yaml` — return raw YAML (`Content-Type: text/yaml`). Default: JSON. |

### Example URLs

- `https://lupopedia.com/lupopedia/api/flip-header.php?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md`
- `https://lupopedia.com/lupopedia/api/flip-header.php?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md&format=yaml`
- `https://lupopedia.com/lupopedia/api/flip-header.php?content_id=2001`

### curl examples

```bash
curl "https://lupopedia.com/lupopedia/api/flip-header.php?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md"
curl "https://lupopedia.com/lupopedia/api/flip-header.php?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md&format=yaml"
```

---

## Response

### Success (default JSON)

**Content-Type:** `application/json`

```json
{
    "header": "---\n# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)\n...\n---",
    "resolved": true,
    "channel_id": 42
}
```

### Success (format=yaml)

**Content-Type:** `text/yaml`

Raw YAML FLIP Header block (no JSON wrapper).

### Error

**Content-Type:** `application/json`  
**HTTP status codes:** 400 (invalid/missing params), 404 (not found), 500 (internal).

```json
{
    "error": "Invalid path"
}
```

| Status | Condition                                      |
|--------|------------------------------------------------|
| 400    | Missing all params; invalid `path` (escape/traversal) |
| 404    | Content not found (path/url/content_id)        |
| 500    | Config not loaded; bootstrap failure (no details) |

---

## Validation (LEXA)

- **Path:** `validate_and_sanitize_path_from_root`; reject `..` and root escapes.
- **Url:** Must match `content_url` or `custom_path` in DB (parameterized lookup).
- **Content_id:** Must be numeric and exist in `lupo_contents`.
- **Parameterized SQL via PDO_DB.** No string concatenation.

---

## Security & Future

- **CORS:** `Access-Control-Allow-Origin: *` for external agent browsing.
- **Rate limiting:** Optional future; not implemented in 4.0.16.
- **API key:** Future versions may require API key; if added, `lupo_api_keys` table (TOON + migration approval).

---

## Integration

- **External agents (e.g. Grok):** Browse the URL, parse the JSON `header` field or raw YAML, infer file identity and lineage from the YAML only (FLIP inference).
- **Local agents:** Prefer `tools/generate_flip_header.py` for CLI; use this API when web access is required.

---

## Database Mapping Layer (Optional)

The FLIP header system supports an optional database mapping layer using the `X-LUPO-{table}.{column}` namespace:

```yaml
---
# FLIP Header (alias: Wolfie Header, CROP Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/example.md
file.last_modified_system_version: "4.0.28"
file.last_modified_utc: "20260222140000"
channel_id: 42

# Database Mapping Layer (Optional)
X-LUPO-actors.actor_id: 2038
X-LUPO-channels.channel_id: 42
X-LUPO-dialog_messages.dialog_message_id: 2000
---
```

### Mapping Layer Rules

- **Optional:** Never required for inference or basic operations
- **Namespaced:** Must use `X-LUPO-{table}.{column}` format
- **Non-overriding:** Cannot override semantic FLIP fields
- **Opaque values:** Treated as strings, no schema inference
- **Tooling-only:** Intended for advanced tooling, migrations, and schema-aware agents

### Valid Examples

```
X-LUPO-actors.actor_id: 2038
X-LUPO-channels.channel_id: 42
X-LUPO-dialog_messages.dialog_message_id: 2000
X-LUPO-registry.registry_id: 9002031
```

### Invalid Examples

```
# Wrong format - missing dot separator
X-LUPO-actorid: 2038

# Wrong prefix - not X-LUPO-
X-FLIP-actors.actor_id: 2038

# Overrides semantic field - not allowed
X-LUPO-file_path: docs/example.md
```

The mapping layer is preserved in the `database_mappings` field of the parsed header and is included when formatting headers back to YAML.

See **docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md** Part 1.4 (Universal Agent Flipping), Parts 6.1–6.3 (API spec, security, future auth).