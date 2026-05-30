---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md
  last_modified_utc: '20260402224949'
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "cursor"
  delegation_chain: cursor:root
  artifact_type: doctrine
  artifact_kind: root_rule
  purpose: Constitutional requirement — real system UTC for headers and filenames; forbid LLM-guessed dates
  tags:
    - utc
    - temporal_anchor
    - tick
    - root_rules
lupopedia.edges:
  outbound_edges:
    - to: lupo-bin/tick.py
      type: references
      weight: 1.0
    - to: lupo-bin/echo_anchor_utc.py
      type: references
      weight: 1.0
    - to: lupo-bin/temporal_anchor.json
      type: references
      weight: 1.0
    - to: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
      type: references
      weight: 1.0
    - to: lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md
      type: references
      weight: 1.0
    - to: lupo-docs/prd/00_root_constitutional_system_requirements.md
      type: references
      weight: 1.0
      reason: Section 3.5a constitutional binding
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

# UTC temporal anchor doctrine (root)

## Binding statement

**IDE agents, automation, and humans MUST NOT invent “the current UTC time” from language-model context.** Models do not have a reliable clock; any date they “know” is predictive text, not system time.

For **LUPOPEDIA header/footer BIGINT fields** and **new canonical filename UTC prefixes** (PRD 17), the only approved sources are:

1. **`python lupo-bin/tick.py`** — reads **real** OS clock in UTC, writes `lupo-bin/temporal_anchor.json` and root `CURRENT_UTC`.
2. **`python lupo-bin/echo_anchor_utc.py`** — prints `current_utc` from the anchor **without** updating it (same batch as a recent tick).

## Requirements

- Run **`tick.py`** at least once before writing or updating `last_modified_utc`, `when_updated`, `last_verified`, or thread/decision filename timestamps.
- Use the **14-digit** `current_utc` value in YAML (quoted string).
- **PHP runtime** timestamps for DB rows remain **`gmdate('YmdHis')`** in application code (PRD 00 §3.5); this doctrine adds **repo artifact** discipline for markdown and agents.

## Constitutional cross-reference

- **[lupo-docs/prd/00_root_constitutional_system_requirements.md](../../lupo-docs/prd/00_root_constitutional_system_requirements.md)** — §3.5a
- **[lupo-docs/doctrine/TICK_PY_DOCTRINE.md](../../lupo-docs/doctrine/TICK_PY_DOCTRINE.md)** — full workflow
- **[README.md](../../README.md)** — Temporal Anchor section

## Violations

Guessed or “approximately now” timestamps are **non-canonical** and must be corrected when found.

This output complies with Lupopedia Constitutional Root Rules.
