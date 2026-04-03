---
lupopedia.headers:
  actor_id: 106
  actor_name: "vscode-ide"
  delegation_chain: "vscode-ide:root"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "vscode_rule"
  file_path_from_root: ".vscode/lupopedia/rules/TIMESTAMP_DOCTRINE.md"
  last_modified_utc: "20260402"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/TIMESTAMP_DOCTRINE.md"
  artifact_type: "rule"
  artifact_kind: "vscode_doctrine"
---


# UTC Timestamp Doctrine

Binding for all 4.0.x releases, all agents, all faucets, all scripts, and all manual artifact generation.

## Permanent Rule

1. All artifact timestamps and filename timestamps must be generated from real UTC system time only.
2. No local timezone math is allowed.
3. No offset arithmetic is allowed in filename generation.
4. No synthetic or guessed timestamps are allowed.
5. Valid filename timestamp format is `YYYYMMDD_HHIISS`.
6. `HH` must be `00` through `23` only.
7. `MI` must be `00` through `59` only.
8. `SS` must be `00` through `59` only.
9. Any filename or artifact timestamp violating these rules is invalid.
10. Invalid timestamps must block write when enforcement is in the write path, or be flagged for explicit correction when discovered later.

Invalid example: `20260321_251500` is invalid because `HH=25` does not exist in UTC.

## Canonical Generation Methods

### PHP

```php
$artifact_filename_timestamp = gmdate('Ymd_His');
$artifact_db_timestamp = gmdate('YmdHis');
```

### Python

```python
from datetime import datetime

artifact_filename_timestamp = datetime.utcnow().strftime('%Y%m%d_%H%M%S')
```

## Non-Negotiable Constraints

- UTC only.
- No conversion from local time for artifact naming.
- No user timezone assumptions.
- No 12-hour clock usage.
- No hidden normalization after invalid generation.
- If an agent or script cannot obtain real UTC safely, it must not generate the artifact filename.

## Validation and Artifact Invalidation

- Validators must reject `HH > 23`.
- Validators must reject `MI > 59`.
- Validators must reject `SS > 59`.
- Validators must report the file path, parsed timestamp, and violation type.
- Validators may propose deterministic rename actions only when supplied with explicit correction evidence.
- Validators must never silently rename files.
- Validators must never guess timezones.
- Validators must never fabricate corrected timestamps.

## Cross-Agent Applicability

This doctrine applies equally to:

- WOLFIE and the other primary personas
- IDE faucets
- Python and PHP tooling
- manual artifact creation
- validator and migration scripts

No agent, faucet, or script is exempt from UTC filename validation.
