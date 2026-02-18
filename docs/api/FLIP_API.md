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

See **docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md** Part 1.4 (Universal Agent Flipping), Parts 6.1–6.3 (API spec, security, future auth).
