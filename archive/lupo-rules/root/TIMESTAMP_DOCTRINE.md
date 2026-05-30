---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-rules/root/TIMESTAMP_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupo-rules/root/TIMESTAMP_DOCTRINE.md"
  last_modified_utc: "20260406044907"
  channel_id: 42
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "root_rule"
  purpose: "Canonical UTC timestamp generation, filename validation, and artifact invalidation doctrine for all agents and tooling."
  tags: ["timestamp", "utc", "filename_validation", "artifact_naming", "4.0.85"]

lupopedia.rules:
  declares:
    - rule_id: "TIME001"
      rule_text: "Artifact timestamps and filename timestamps must be generated from real UTC system time only."
      scope: "all_agents"
      category: "timestamp"
    - rule_id: "TIME002"
      rule_text: "Filename timestamps must use YYYYMMDD_HHIISS with HH 00-23, MI 00-59, SS 00-59."
      scope: "all_agents"
      category: "filename_validation"
    - rule_id: "TIME003"
      rule_text: "Invalid timestamped artifacts must be blocked from write or flagged for correction and may not be silently normalized."
      scope: "all_agents"
      category: "artifact_integrity"

lupopedia.footer:
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Run lupo-scripts/validate_timestamps.py against artifact-bearing directories."
    - "Use explicit rename maps only when deterministic correction evidence exists."
---

# file: Timestamp Doctrine — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-rules/root/TIMESTAMP_DOCTRINE.md

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
