---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/lupo-bin/TICK_PY.md
  last_modified_utc: '20260330'
  purpose: Documentation for lupo-bin/tick.py temporal anchor updater
  traits:
    - tick
    - temporal_anchor
    - doctrine
    - 4.0.93
    - utc
---
# lupo-bin/tick.py — Temporal Anchor Updater

## Purpose

Ensures all Lupopedia header timestamps are synchronized to real UTC by updating `lupo-bin/temporal_anchor.json` with the current UTC time in `YYYYMMDDHHMMSS` format. This prevents future-dating and guarantees auditability.

## Usage

Run after every session or major write:

```sh
python3 lupo-bin/tick.py
```

- Updates `lupo-bin/temporal_anchor.json` with the current UTC.
- No arguments required. Always uses UTC (never local time, never a timezone).
- The IDE and all header writers must reference this anchor for `last_modified_utc`.

## Output

Example `lupo-bin/temporal_anchor.json`:

```json
{
  "current_utc": "20260330090000",
  "last_session_end": "20260330084500",
  "system_year": "2026",
  "format_standard": "YYYYMMDDHHMMSS"
}
```

## Policy
- All timestamps must be in UTC, never local time or with timezone offsets.
- If the anchor file is missing, the IDE must request a tick before writing headers.
- This is a required step for all compliant deployments.
