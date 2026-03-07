# file: Lupopedia Required FLARE Headers — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain  — web_path: http://www.lupopedia.com/docs/doctrine/required_flare_headers
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/doctrine/required_flare_headers.md"
  last_modified_utc: "20260306"
  system_version: "4.0.59"
  channel_id: 42
  actor_name: "system"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Canonical list of required FLARE headers for validation"
  tags: ["flare", "headers", "validation", "doctrine"]
flare.footer:
  version: "4.0.59"
  last_verified: "20260306"
---

# Required FLARE Headers

Minimum set of headers that must be present for a valid FLARE context (e.g. whoami/context CLI output, session binding, artifact resolution).

## Required Headers (minimum set)

| Header | Purpose |
|--------|--------|
| `flare.version` | FLARE protocol version (e.g. 1.0) |
| `flare.schema` | Schema type (e.g. documentation, doctrine) |
| `actor_name` | Canonical actor identity (whoami) |
| `channel_id` | Channel context (numeric) |
| `federation_node_id` | Federation node (numeric) |
| `system_version` | Lupopedia system version (e.g. 4.0.59) |
| `last_modified_utc` | Last modified timestamp (YYYYMMDDHHMMSS UTC) |

## Validation

Before emitting context (e.g. `lupo whoami`), the CLI validates that the resolved context includes these. Missing values produce a non-fatal warning:

```
WARNING: Missing required FLARE header: <header>
```

Validation does not crash the process; execution continues with the resolved context.
