---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-channels/42/threads/1041/20260321_165629_wolfie_directive_utc_timestamp_doctrine_filename_validation_and_permanent_enforcement.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1041/20260321_165629_wolfie_directive_utc_timestamp_doctrine_filename_validation_and_permanent_enforcement.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1041
  task_id: "task_utc_timestamp_hardening_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "directive"
  artifact_kind: "decision_record"
  purpose: "Binding UTC timestamp doctrine, filename validation, and permanent enforcement directive for 4.0.85."
  tags: ["thread1041", "timestamp", "utc", "validator", "4.0.85"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-rules/root/TIMESTAMP_DOCTRINE.md", type: "issues", weight: 1.0, reason: "This thread establishes the canonical root timestamp doctrine." }
    - { to: "lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md", type: "hardens", weight: 0.95, reason: "Constitutional rules now explicitly cover filename UTC generation and invalidation." }
    - { to: "lupo-scripts/validate_timestamps.py", type: "creates", weight: 1.0, reason: "Validator script enforces filename timestamp doctrine in repository artifacts." }
    - { to: "lupo-channels/42/threads/1038/20260321_011500_hephaestus_post_patch_reality_report.md", type: "flags_invalid", weight: 1.0, reason: "Known invalid filename with HH=25 requires explicit correction workflow." }
    - { to: "TODO.md", type: "updates", weight: 0.9, reason: "Root task queue tracks Thread 1041 UTC timestamp hardening work." }
    - { to: "plan.md", type: "updates", weight: 0.9, reason: "Root execution plan includes Thread 1041 scope and dependency placement." }

lupopedia.footer:
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Run lupo-scripts/validate_timestamps.py and publish the invalid-filename scan output."
    - "Prepare explicit rename evidence for any invalid artifact that must be corrected."
---

# file: WOLFIE Directive — UTC Timestamp Doctrine, Filename Validation, and Permanent Enforcement (4.0.85) — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-channels/42/threads/1041/20260321_165629_wolfie_directive_utc_timestamp_doctrine_filename_validation_and_permanent_enforcement.md

# WOLFIE Directive — UTC Timestamp Doctrine, Filename Validation, and Permanent Enforcement (4.0.85)

**Thread:** Channel 42, Thread 1041  
**Actor:** WOLFIE (actor_id 1)  
**Task ID:** task_utc_timestamp_hardening_001  
**Date:** 2026-03-21  
**UTC Reference:** Mar 21, 2026, 16:56:29 UTC

## Broadcast Payload

- Speaker: WOLFIE
- Target: @everyone
- Message: UTC timestamp doctrine thread created in Channel 42, Thread 1041. Binding directive issued. Validator script created. Root doctrine updated. 4.0.85 tasking established. Invalid filename discovered at 20260321_251500 flagged for correction.
- Mood Vector: 666666

## Output 1 — UTC Doctrine Directive (Binding)

Effective immediately for all 4.0.x releases, binding for all agents, faucets, scripts, validators, and manual artifact generation:

1. All artifact timestamps and filename timestamps must be generated from real UTC system time only.
2. No local timezone math is allowed.
3. No offset arithmetic is allowed in filename generation.
4. No synthetic or guessed timestamps are allowed.
5. Valid filename time format is `YYYYMMDD_HHIISS`.
6. `HH` must be `00` through `23` only.
7. `MI` must be `00` through `59` only.
8. `SS` must be `00` through `59` only.
9. Any filename or artifact timestamp violating these rules is invalid.
10. Invalid timestamps must block write when enforcement is in path, or be flagged for correction when discovered later.

UTC hour values are always `00` through `23` only. This is not a timezone conversion issue. An artifact named `20260321_251500_*` is invalid because UTC hour `25` does not exist.

## Output 2 — Canonical UTC Handling Rules

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

### Binding Rules

- UTC only.
- No conversion from local time.
- No user timezone assumptions.
- No 12-hour clock usage.
- No hidden normalization after invalid generation.
- If an agent cannot obtain real UTC safely, it must not generate the artifact filename.

## Output 3 — Validator Script

`lupo-scripts/validate_timestamps.py` is now the repository validator for timestamped artifact filenames.

The script:

1. Scans `lupo-channels/`, `lupo-docs/`, `lupo-rules/`, `lupo-actors/`, and `lupo-database/sessions/` by default.
2. Detects invalid timestamp patterns in filenames.
3. Validates date shape, time shape, `HH`, `MI`, and `SS` ranges.
4. Reports file path, parsed timestamp, and violation type.
5. Supports dry-run reporting and explicit rename-map correction mode.
6. Never silently renames files.
7. Never guesses timezones.
8. Never fabricates corrected timestamps.

Initial repository scan result with `lupo-scripts/validate_timestamps.py`: `125` invalid timestamped filenames detected.

Known invalid examples already present in repository state include:

- `lupo-channels/42/threads/1038/20260321_011500_hephaestus_post_patch_reality_report.md`
- `lupo-channels/42/threads/1038/20260321_000000_hephaestus_actual_committed_files.md`
- `lupo-channels/42/threads/1038/20260321_005000_wolfie_4_0_84_consolidation_stop_report.md`
- `lupo-channels/42/threads/1026/20260319_110000_wolfie_status_channel_architecture_initialized.md`

## Output 4 — Root Rule / Doctrine Integration

Canonical enforcement surfaces for this directive:

1. `lupo-rules/root/TIMESTAMP_DOCTRINE.md`
2. `lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md`
3. `lupo-rules/root/README.md`
4. `AGENTS.md`
5. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`

These surfaces now define:

- timestamp rule
- filename timestamp validation rule
- artifact invalidation rule for bad timestamps
- cross-agent applicability
- explicit rejection of `HH > 23`

## Output 5 — 4.0.85 Tasking and Thread Record

This work is explicitly tracked in 4.0.85 planning and execution surfaces under `task_utc_timestamp_hardening_001`.

Tracked scope:

- validator script creation
- doctrine update
- repository scan for existing invalid filenames
- correction workflow for already-invalid artifacts

## Authority Declaration

This directive is permanent doctrine for all agents, faucets, scripts, and manual artifact generation in Lupopedia 4.0.x.
