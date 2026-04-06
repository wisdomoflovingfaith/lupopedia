---
lupopedia.init:
  file_identity: "TIMESTAMP_DOCTRINE.md"
  artifact_type: "lexa_rule"
  artifact_kind: "doctrine"
  namespace: "lexa"
  system_version: "4.0.76"
  orchestrator_actor: "lexa"
  delegation_chain: "lexa:captain"

lupopedia.headers:
  actor_id: 24
  actor_name: "lexa"
  delegation_chain: "lexa:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "lexa_rule"
  file_path_from_root: ".lexa/rules/TIMESTAMP_DOCTRINE.md"
  last_modified_utc: "20260406"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/TIMESTAMP_DOCTRINE.md"
  artifact_type: "rule"
  artifact_kind: "lexa_doctrine"
  purpose: "LEXA-specific rule derived from canonical root rule - Boundary Keeper enforcement"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "TIME001"
      rule_text: "Artifact timestamps and filename timestamps must be generated from real UTC system time only."
      scope: "all_agents"
      category: "timestamp"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260406"
    last_reviewed_by: "lexa"
    last_reviewed_date: "20260406"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260406"
  last_verified_by: "lexa"
  orchestrator: "lexa"
  next_action:
    - "Keep in sync with canonical root rules"
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

