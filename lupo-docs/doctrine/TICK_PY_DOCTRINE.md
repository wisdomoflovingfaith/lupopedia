---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/TICK_PY_DOCTRINE.md
  last_modified_utc: '20260402224949'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: doctrine
  artifact_kind: temporal
  purpose: Mandatory tick.py and temporal_anchor.json workflow; forbid guessed header timestamps
  tags:
    - tick
    - temporal_anchor
    - utc
    - headers
lupopedia.edges:
  outbound_edges:
    - to: lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Root binding; PRD 00 section 3.5a
    - to: lupo-bin/tick.py
      type: references
      weight: 1.0
    - to: lupo-bin/echo_anchor_utc.py
      type: references
      weight: 1.0
    - to: lupo-bin/temporal_anchor.json
      type: references
      weight: 1.0
    - to: lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md
      type: references
      weight: 1.0
    - to: README.md
      type: references
      weight: 0.95
      reason: Temporal anchor policy summary
lupopedia.footer:
  last_verified: '20260402224949'
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: cursor:root
---

# TICK_PY and temporal anchor (mandatory)

## Purpose

All **`last_modified_utc`**, **`when_updated`**, **`last_verified`**, and **UTC-based filename prefixes** for canonical artifacts must come from **real system UTC**, not from model guesses, “nice” round times, or copied dates from other files.

The **only** supported way to refresh the canonical clock for the repo is:

```bash
python lupo-bin/tick.py
```

That writes:

- `lupo-bin/temporal_anchor.json` — JSON with **`current_utc`** (`YYYYMMDDHHMMSS`, 14 digits, UTC)
- `CURRENT_UTC` (repository root) — same value as a single line

## After tick: reuse without re-clocking

For multiple files in one editing batch, reuse the same **`current_utc`**:

```bash
python lupo-bin/echo_anchor_utc.py
```

Prints **`current_utc`** from the anchor **without** updating it. Exit code **1** if the anchor is missing (run `tick.py` first).

## What agents must not do

- Do **not** type timestamps from training-data “current date” or chat context.
- Do **not** reuse another file’s `last_modified_utc` unless it matches the **current** anchor after a **fresh** `tick.py` in this session (stale reuse is wrong).
- Do **not** use local wall-clock strings or offsets in YAML header BIGINT fields.

## Filename timestamps (PRD 17)

From `current_utc` = `YYYYMMDDHHMMSS`:

- Preferred thread prefix: `YYYYMMDD_HHIISS_` → e.g. `20260402_224629_` (underscore between date and time).

## If Python is unavailable

Do not fabricate timestamps. Complete work without new timestamped canonical filenames, or run `tick.py` from another shell on the same machine and commit the updated anchor with the artifacts.

## References

- [TIMESTAMP_DOCTRINE.md](TIMESTAMP_DOCTRINE.md)
- [README.md](../../README.md) — Temporal Anchor section
- [LUPOPEDIA_HEADERS/README.md](LUPOPEDIA_HEADERS/README.md)

This output complies with Lupopedia Constitutional Root Rules.
