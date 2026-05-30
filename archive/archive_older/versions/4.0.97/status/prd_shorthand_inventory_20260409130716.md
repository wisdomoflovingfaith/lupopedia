# PRD Shorthand Inventory and Conversion

## Inventory Report

Shorthand `.toon` files currently present:

- `memory/2026/04/00_constitution_shorthand.toon`
- `docs/implementations/16_lupopedia_headers/decisions/pseudocode/16_shorthand.toon`
- `docs/implementations/17_decisions_format/decisions/pseudocode/17_shorthand.toon`
- `docs/implementations/38_memory_unification/decisions/pseudocode/38_shorthand.toon`
- `docs/implementations/43_parent_child_trust_ladder/decisions/pseudocode/43_shorthand.toon`
- `docs/implementations/44_session_config_and_transcript/decisions/pseudocode/44_shorthand.toon`

## Missing Shorthand List

Priority PRDs with shorthand now present:

- PRD 38: present
- PRD 44: present
- PRD 17: present
- PRD 16: present

Priority PRD with canonical source missing:

- PRD 43: no canonical `docs/prd/43_*.md` found; provisional shorthand added with `status=missing_source_prd`.

High-priority follow-up PRDs still needing shorthand `.toon`:

- PRD 40, PRD 41, PRD 42
- Remaining PRDs in `docs/prd/*.md` that do not yet have `*_shorthand.toon` peers.

## Example Shorthand Files Produced

- `docs/implementations/38_memory_unification/decisions/pseudocode/38_shorthand.toon`
- `docs/implementations/44_session_config_and_transcript/decisions/pseudocode/44_shorthand.toon`
- `docs/implementations/43_parent_child_trust_ladder/decisions/pseudocode/43_shorthand.toon`
- `docs/implementations/17_decisions_format/decisions/pseudocode/17_shorthand.toon`
- `docs/implementations/16_lupopedia_headers/decisions/pseudocode/16_shorthand.toon`

## Root Shorthand Update

Updated:

- `memory/2026/04/00_constitution_shorthand.toon`

Changes:

- Added constitutional rule: `No emoji in machine-readable data`.
- Added `shorthand_index` entries for PRDs 16, 17, 38, 43, 44.

## Conversion Plan (Batch Priority)

1. Batch A: PRDs 40, 41, 42 (versioning/seed/content path).
2. Batch B: Remaining active operational PRDs used in current development threads.
3. Batch C: Backfill all remaining numeric PRDs in ascending order.
4. Batch D: Add validator to assert each numeric PRD has shorthand coverage or explicit waiver marker.
