---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/required_flare_headers.md"
  last_modified_utc: "20260312"
  system_version: "4.0.71"
  channel_id: 42
  actor_name: "system"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Canonical list of required LUPOPEDIA HEADERS for validation (replaces FLARE)"
  tags: ["lupopedia_headers", "headers", "validation", "doctrine"]
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  next_action:
    - "Validate required headers in CLI whoami/context output"
    - "Add next_action to footer when this file is updated"
    - "Keep deprecation link to LUPOPEDIA_HEADERS current"
---
# file: Lupopedia Required LUPOPEDIA HEADERS — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain — web_path: http://www.lupopedia.com/docs/doctrine/required_flare_headers

# Required LUPOPEDIA HEADERS (replaces FLARE)

Minimum set of headers that must be present for a valid LUPOPEDIA HEADERS context (e.g. whoami/context CLI output, session binding, artifact resolution). **FLARE, FLIP, and FLP are deprecated** (4.0.71); LUPOPEDIA HEADERS is the canonical system. See [DEPRECATION_FLARE_FLIP_FLP.md](LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md) for the deprecation notice.

## Required Headers (minimum set)

| Header | Purpose |
|--------|--------|
| `lupopedia.version` | LUPOPEDIA HEADERS protocol version (e.g. 1.0) |
| `lupopedia.schema` | Schema type (e.g. documentation, doctrine) |
| `actor_name` | Canonical actor identity (whoami) |
| `channel_id` | Channel context (numeric) |
| `federation_node_id` | Federation node (numeric) |
| `system_version` | Lupopedia system version (e.g. 4.0.59) |
| `last_modified_utc` | Last modified timestamp (YYYYMMDDHHMMSS UTC) |

## Validation

Before emitting context (e.g. `lupo whoami`), the CLI validates that the resolved context includes these. Missing values produce a non-fatal warning:

```
WARNING: Missing required LUPOPEDIA HEADERS field: <header>
```

Validation does not crash the process; execution continues with the resolved context.
