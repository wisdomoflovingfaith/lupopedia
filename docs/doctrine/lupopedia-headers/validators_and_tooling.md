---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/lupopedia-headers/validators_and_tooling.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/validators_and_tooling.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/validators-and-tooling.toon
  atoms_toon: null
  transcript_jsonl: 0/development/validators-and-tooling
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: headers-validators-v3
  lupopedia.schema: doctrine
  prd_cluster: null
  title: Validators and Tooling for Lupopedia Headers
  summary: Validator entrypoints; PRD 16 freeze at 4.1.3; legacy version branches unchanged in code
---
# Validators and Tooling (PRD 16 / `header_format_version` 4.1.3)

**Freeze:** **[PRD 16](../../prd/16_lupopedia_headers.md) — Header freeze rule (4.1.3)**. Validator mechanics for legacy **`4.0.*`**, **`4.1.0`–`4.1.2`**, and integer **`3`** remain as implemented; **normative new edits** declare **`"4.1.3"`**. Header-format evolution resumes **4.1.5+** after the Crafty Syntax human live-help baseline is complete.

## Canonical references

- **[PRD 16](../../prd/16_lupopedia_headers.md)** — normative rules, codes, and scope.
- **[LUPOPEDIA_HEADERS_FORMAT.md](LUPOPEDIA_HEADERS_FORMAT.md)** — fixed-position Markdown and Python envelopes.
- **`scripts/validate_lupopedia_headers_universal.py`** — shipped implementation (source of truth for checks and `HDR_*` messages).

## Markdown vs Python envelopes

**Same 22-key dense block under `lupopedia.headers:`; different file wrappers.**

| Surface | Wrapper | Mechanical envelope (summary) |
|--------|---------|--------------------------------|
| **Markdown** (`.md`) | YAML front matter between `---` fences | Line 1 `---`; line 2 `lupopedia.headers:`; lines 3–24 **22 keys** (no blank lines between them); line 25 closing `---`; line 26+ body (**line 26 MUST be non-whitespace**, `HDR_EMPTY_BODY`). |
| **Python** (`.py`) | `#` comment lines; optional shebang | Optional line 1 `#!/usr/bin/env python3`; opening `# -----` fence; `# lupopedia.headers:` + **22** `#   key:` lines; closing `# -----`; then code. Line counts depend on shebang (validator emits `HDR_PYTHON_LINE_COUNT` / `HDR_PYTHON_HEADER` with exact expectations). |

**Generators:** `scripts/add_lupopedia_header_to_file.py` emits both shapes. Batch coverage: `scripts/add_lupopedia_headers_everywhere.py` reuses the same builders.

## Why the line count is strict (authoring)

The **25-line** Markdown envelope (and the parallel Python comment block) is **intentional** for PRD 16: scalar-only values, fixed key order, no YAML comments inside the block, and deterministic parse positions.

That strictness applies to **authoring validation** (`validate_lupopedia_headers_universal.py`). **Repair/dedupe** tools may use a more forgiving peel: `scripts/lib/lupopedia_markdown_header_peel.py` implements a **25-line fast path** plus a **line-based fallback** to find a closing `---` when merging duplicates—see that module’s docstring. Peeling does not relax the universal validator for normal commits.

**Line endings:** the validator normalises `CRLF` to `LF` for Markdown line indexing. “Blank” lines 23–24 must be **empty** (no spaces); otherwise expect `HDR_MISSING_BLANK_LINE` or related `HDR_LINE_COUNT` failures. Prefer `\n` in the repository; use `normalize_lupopedia_md_header_25.py` when the inner YAML is correct but envelope spacing drifted.

## `file_path_from_root` and `web_path`

- **`file_path_from_root`** — Repository-relative path using **forward slashes** (POSIX), matching this file’s location in the tree. It is the canonical file identity in headers and docs. The universal validator enforces **ASCII-safe basename** rules on this field. With **`--check-db`**, a **warning** may be printed if the header’s `file_path_from_root` disagrees with `lupo_contents.file_path_from_root` for the same `content_id`.
- **`web_path`** — Must be present and non-empty; the validator combines it with **`federation_node_id`** (internal vs external URL expectations). Absolute URLs **MUST** use **`https://`**; **`http://`** fails with **`HDR_WEB_PATH_HTTP`** unless **`--development`** is passed. There is no separate stable code name for every URL failure mode—read the emitted `[ERROR]` line.

## Required validator behavior (summary)

This list tracks **`validate_lupopedia_headers_universal.py`**; see the script for the full set.

- **`header_format_version`:** legacy **`3`** or a **`4.0.x`** string patch family (`HDR_VERSION_FAMILY` on mismatch). Do not use obsolete top-level `version` under `lupopedia.headers` (`HDR_VERSION_FIELD_REMOVED`).
- **`channel_key`** required (slug). Numeric **`channel_id`** is legacy in prose only—not a substitute for `channel_key` in new headers.
- **`trust_tier`:** one of `seed`, `canonical`, `staging`, `archive`.
- **`memory_key`:** required; must end with **`.toon`** (`HDR_MEMORY_KEY`, `HDR_MEMORY_KEY_SUFFIX`). **On-disk existence of that path is not asserted** by the universal validator. For **`trust_tier`** **`seed`** \| **`canonical`**, when the declared **`.toon`** **does** exist on disk, a missing sibling **`.json`** master emits **`HDR_MEMORY_JSON_MASTER`** (**WARN** by default; **`ERROR`** with **`--strict-memory-pair`**). Use **`validate_memory_json_toon_pair.py`** for full sync checks. Treat “memory file payload” richness as part of the memory artifact pipeline, not a duplicate of the 20 YAML keys.
- **`content_id`:** key must exist; value **`null`** or numeric BIGINT (`HDR_CONTENT_ID_MISSING`, `HDR_CONTENT_ID_INVALID`).
- **Top of file:** no bytes before the header (`HDR_NOT_AT_TOP`).
- **Markdown:** exact outer envelope (`HDR_LINE_COUNT`, `HDR_MISSING_BLANK_LINE`, `HDR_MISSING_CLOSE`, `HDR_CONTENT_TOO_EARLY`, **`HDR_EMPTY_BODY`**, …).
- **Python:** comment envelope and counts (`HDR_PYTHON_*`).
- **Keys:** exactly the **22** PRD 16 §4.2 keys in order (`HDR_MISSING_KEY`, `HDR_EXTRA_KEY`, `HDR_KEY_ORDER`); scalar values only (`HDR_ARRAY`, `HDR_MULTILINE`). Legacy names `prd_id`, `prd_slug`, `parent_prd` generate `HDR_LEGACY_FIELD_NAME` (WARN). Deprecated `module` generates `HDR_MODULE_DEPRECATED` (WARN) — rename to `atoms_toon`.
- **`atoms_toon` (field 21):** Must be YAML `null` or a path string ending in `.atoms.toon` (`HDR_ATOMS_TOON_SUFFIX`). `null` is always valid. File existence is NOT checked. `module` is accepted as a legacy alias for `atoms_toon` during migration (emits `HDR_MODULE_DEPRECATED` WARN). Schema: `docs/doctrine/lupopedia-headers/atoms_toon_schema.md`.
- **Timestamps:** `when_updated` is the sole 14-digit UTC timestamp field; it must match `YMDHIS_PATTERN`. The former `last_modified_utc` field was renamed to `questions_toon` in PRD 16 v4.0.99 §4.2 field 6.
- **`questions_toon` (field 6):** Must be YAML `null` or a path string ending in `.questions.toon` (`HDR_QUESTIONS_TOON_SUFFIX`). `null` is always valid. When non-null, must not equal `memory_key` (`HDR_QUESTIONS_TOON_COLLISION`). Validator emits `HDR_LAST_MODIFIED_RENAMED` (WARN) when old `last_modified_utc` field is found.
- **Duplicates:** more than one leading `lupopedia.headers` block → `HDR_MULTIPLE_HEADERS`; repair with **`fix_double_headers.py`**.

## Version acceptance matrix

| Label | `header_format_version` | Validator action |
|-------|-------------------------|------------------|
| Legacy numeric | `3` | Accepted per script rules; new files should use `4.0.x`. |
| Current string | `4.0.96`, `4.0.97`, `4.0.98`, … | Expected for new authoring; patch should match project version / `global_atoms` policy. |
| Obsolete prose “v1” / “v2” | — | Reject or warn per PRD 16; **no** `migrate_v2_to_v3.py` (or similar) ships in this repository—repair manually with the tools below. |

## Error recovery (common codes)

| Situation | Typical codes | First-line recovery |
|-----------|---------------|---------------------|
| Two YAML headers at top | `HDR_MULTIPLE_HEADERS` | `python scripts/fix_double_headers.py` (`--dry-run` first), then re-validate |
| Envelope / blank lines / close `---` | `HDR_LINE_COUNT`, `HDR_MISSING_BLANK_LINE`, `HDR_MISSING_CLOSE` | Fix missing keys/order first; then `python scripts/normalize_lupopedia_md_header_25.py` where applicable |
| Box-drawing / mojibake in header region | `HDR_UNICODE_BOX` | `python scripts/fix_unicode_box_drawing_ascii.py` (`--dry-run` / `--check` first); default `--path` `docs/prd/**/*.md` |
| Missing or out-of-order keys | `HDR_MISSING_KEY`, `HDR_KEY_ORDER` | Regenerate with `add_lupopedia_header_to_file.py` or hand-edit to **[FORMAT](LUPOPEDIA_HEADERS_FORMAT.md)** |
| Batch sweep (PRDs or all `docs` md) | — | `python scripts/batch_validate_prd_headers.py` (optional `--all-md`, `--verbose`, `--timeout`) |

## Exit codes (CI / automation)

- **`validate_lupopedia_headers_universal.py`:** **`0`** pass, **`1`** failure. There is **no** separate exit code for “warnings only” today.
- **`batch_validate_prd_headers.py`:** **`0`** if every file passed (or nothing matched), **`1`** if any subprocess failed or timed out.

When batch-spawning the universal validator, pass **`--quiet`** to keep logs readable.

## Tooling inventory (shipped)

| Script | Role |
|--------|------|
| `add_lupopedia_header_to_file.py` | Prepend / create header (`tick.py` + `echo_anchor_utc.py`); optional `--validate`, `--backup`. |
| `add_lupopedia_headers_everywhere.py` | Batch idempotent coverage (`--dry-run`, roots, `--all-repo`). |
| `validate_lupopedia_headers_universal.py` | Single-file canonical validation; `--check-links`, `--check-db`, `--development` (allow `http://` `web_path`; skip JSON↔TOON pairing; **`HDR_EMPTY_BODY`** as warn-only for Markdown line 26 / Python body), `--strict-memory-pair`, `--type`, `--quiet`. |
| `batch_validate_prd_headers.py` | Many Markdown paths; wraps the universal validator per file. |
| `fix_double_headers.py` | Merge duplicate leading YAML blocks (newest `when_updated` wins; `last_modified_utc` no longer used as tie-break since v4.0.99). |
| `normalize_lupopedia_md_header_25.py` | Rebuild 25-line envelope from inner YAML (numbered PRDs / docs). |
| `fix_unicode_box_drawing_ascii.py` | Recursive glob (`--path`); box glyphs + UTF-8/Latin-1 mojibake repair; `--dry-run`, `--check`, `--backup`, `--verbose`; extension filter or `--include-all-extensions`; skips probable binary files. |
| `fix_prd16_chars.py` | Targeted charset / punctuation hygiene (see script). |
| `fix_memory_key_paths.py` | Batch-correct `memory_key` path segments (trust_tier, channel_key, year-offset) across all PRD headers; `--dry-run` available. |
| `fix_web_path_https.py` | Batch-replace `http://` with `https://` in `web_path` header field; supports `.md`, `.py`, `.php`; `--dry-run` available. |
| `json_to_toon.py` | Emit `.toon` from JSON memory master. |
| `validate_memory_json_toon_pair.py` | Validate JSON/TOON basename pairing. |
| `lib/lupopedia_markdown_header_peel.py` | Peel leading `lupopedia.headers` blocks (dedupe, diagnostics). |

**Deferred (not in tree):** `validate_memory_file_exists.py` — if added later, it would assert on-disk `.toon` (and optional JSON master) paths without creating a circular dependency with header authoring; until then, rely on pairing scripts and manual layout discipline.

## Tooling updates (policy)

- **New file / missing header:** `python scripts/add_lupopedia_header_to_file.py <path.py|path.md> [--create] [--title "..."]` — emits the **4.0.x** envelope. See **[TEMPLATES_NEW_FILE.md](TEMPLATES_NEW_FILE.md)**.
- **Git hook (sample):** `scripts/git-hooks/pre-commit-lupopedia-headers.sample` — substring guard; for strict checks, call the universal validator from CI or a wrapper.
- Generators must emit **`channel_key`**, **`trust_tier`**, and tier-shaped **`memory_key`**; map legacy **`channel_id`** via **`channels/registry.json`**.
- Rich memory graph data belongs in **memory `.toon` / JSON** sidecars where appropriate—not by stuffing extra keys into the 20-key YAML block.

## Memory pair workflow (JSON master + TOON derived)

For seed/canonical memory artifacts that use a paired representation:

- JSON is the authoring master (`<basename>.json`).
- TOON is deterministic output (`<basename>.toon`) generated from JSON.
- Both files should live in the same directory and share basename.
- The universal validator warns (**`HDR_MEMORY_JSON_MASTER`**, alias **`SIDECAR_JSON_MASTER_MISSING`**) when the **`.toon`** exists but **`.json`** does not; **`--strict-memory-pair`** turns that into a hard failure. **`--development`** skips this pairing check (**PRD 16** §12.4).

Recommended commands:

- Generate TOON from JSON:
  - `python scripts/json_to_toon.py --json "<base>.json" --toon "<base>.toon"`
- Validate JSON/TOON sync:
  - `python scripts/validate_memory_json_toon_pair.py --base "<base>"`

Example:

- `python scripts/json_to_toon.py --json "memory/constitutional/seed/prd-00-constitutional.json" --toon "memory/constitutional/seed/prd-00-constitutional.toon"`
- `python scripts/validate_memory_json_toon_pair.py --base "memory/constitutional/seed/prd-00-constitutional"`
