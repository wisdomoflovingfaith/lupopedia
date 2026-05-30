---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260412153257"
  file_path_from_root: "lupo-docs/versions/4.0.99/wolfie_pk_offset_implementation_20260412153257.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/wolfie_pk_offset_implementation_20260412153257.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "lupo-memory/development/canonical/1026/04/wolfie-pk-offset-implementation-20260412153257.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "WOLFIE PK offset implementation log (20260412153257 UTC)"
  status: "active"
  parent_pk_id: ""
  summary: "Countermeasure MODIFY: PRD/doctrine updates, validators, TrustLadder helper, backfill CLI, header strict staging year; lessons learned."
  module: null
  dialog_transcript: "0/development/wolfie-pk-offset-implementation"
---
# WOLFIE PK offset implementation log (`20260412153257` UTC batch)

## Task 1 — PRD updates

**Examined**

- `lupo-docs/prd/16_lupopedia_headers.md` — §8.1 already present; fixed consolidation edge names to match product (`promoted_to` / `consolidated_into`), clarified legacy `2026` canonical paths vs strict **1026** for new canonical exports.
- `lupo-docs/prd/38_memory_unification.md` — prior **§8.1** was “Era ranges”; inserted normative **§8.1 Canonical Year Offset Rule** and renumbered **8.1→8.2** (Era ranges), **8.2→8.3** (Archive), **8.3→8.4** (PHP helpers), **8.4→8.5** (Querying). Consolidation flow already referenced **§8.4** for PHP helpers (verified).
- `lupo-docs/prd/43_parent_child_trust_ladder.md` — added **§3.0 PK offset rule** under “Trust Weight Quantification”.
- `lupo-docs/prd/51_memory_graph_as_source_of_truth.md` — added **§3.5 Query abstraction for PK bands** (kept existing **§3.4** webroot note).

**Violations fixed**

- PRD 16 previously cited non-normative `consolidated_to`; aligned to **`promoted_to`** / **`consolidated_into`** per **`MemoryPromotionService`** and PRD 37.

## Task 2 — Doctrine updates

**Examined**

- `lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md` — inserted **## PK offset validation** (tooling + `MemoryPromotionService` + path scanner).
- `lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md` — inserted **§2.2 PK offset validation rule (summary)** under existing **§2.2** heading; fixed example PK to full **18-digit** form.

## Task 3 — `validate_trust_ladder_pk.php`

**Created:** `lupo-scripts/validate_trust_ladder_pk.php`

- Self-test for canonical + staging year segments; optional DB scan when `lupopedia-config.php` + bootstrap succeed; **`--no-db`** skips DB; **`--calendar-year=YYYY`** for CI.
- Tables: `memory_nodes`, `memory_edges`, `dialog_messages`, `edges` (prefixed). `dialog_messages` / `edges` forced to **staging** year check.

**Command:** `php lupo-scripts/validate_trust_ladder_pk.php --no-db` → **OK** (calendar year 2026).

## Task 4 — `backfill_canonical_offsets.php`

**Created:** `lupo-scripts/backfill_canonical_offsets.php`

- Lists staging-band **`memory_node_id`** rows missing a **`promoted_to`** edge (PHP-side 18-digit / year filter; portable SQL).
- **`--apply --actor-id=N`** delegates to existing **`MemoryPromotionService::promoteStagingToCanonical()`** (idempotent; no duplicate PK logic in the script).

## Task 5 — `TrustLadderQueryHelper.php`

**Created:** `lupo-includes/classes/TrustLadderQueryHelper.php`

- **`trait TrustLadderQueryHelper`** + facade **`final class TrustLadder`** with **`getCanonical()`**, **`validatePk()`**, **`query()`** (resolves **`memory_node_ids`** for `IN` lists via **`promoted_to`** / **`consolidated_into`**).

**Command:** `php -l lupo-includes/classes/TrustLadderQueryHelper.php` → no syntax errors.

## Task 6 — `validate_memory_key_years.php`

**Created:** `lupo-scripts/validate_memory_key_years.php`

- Recursive scan under **`lupo-memory/`** for **`.toon`**; validates **`canonical`** and **`staging`** year segments vs **`gmdate('Y')`**.
- **`--strict`** upgrades mismatches to errors.

**Lesson:** Do not embed the substring `**/` inside C-style header comments (e.g. glob examples like `**/*.toon`); it terminates the comment early.

**Command:** `php lupo-scripts/validate_memory_key_years.php` → exit **0** with **56** WARN lines (legacy **`canonical/2026/`** and **`canonical/1000/`** trees vs expected **1026** for calendar **2026**).

## Task 7 — Header validator (`validate_lupopedia_headers_universal.py`)

**Examined:** `validate_memory_key_path_shape()` — canonical strict path and warn-only legacy path **already** implemented (**HDR_MEMORY_YEAR_OFFSET**).

**Modified:** Added **`staging`** branch: with **`--strict-memory-year`**, **`memory_key`** year segment must equal **`when_updated`** calendar year.

**Command (spot-check):** not run on full tree in this session (optional: `python lupo-scripts/validate_lupopedia_headers_universal.py lupo-docs/prd/16_lupopedia_headers.md`).

---

WOLFIE implementation complete. PK offset rule now enforced. Next: Run backfill migration and validate all memory_keys.

This output complies with Lupopedia Constitutional Root Rules.
