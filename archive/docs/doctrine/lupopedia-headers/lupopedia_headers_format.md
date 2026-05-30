---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/lupopedia-headers/lupopedia_headers_format.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/lupopedia_headers_format.md"
  status: "active"
  when_updated: "20260418192619"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/lupopedia-headers-format.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/headers/headers-format-v4"
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: ""
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "LUPOPEDIA HEADERS Format Specification (v4)"
  summary: "Format companion to PRD 16 at 4.1.3 (frozen): 22-field dense envelope, fixed positions, field names, language-specific grids, memory pairing, agent discovery pattern"
---
# LUPOPEDIA HEADERS Format Specification (v4)

## Header freeze (4.1.3)

**Normative:** **[PRD 16](../../prd/16_lupopedia_headers.md) — Header freeze rule (4.1.3)** is binding. The declared **`header_format_version`** string for **new and edited** normative envelopes is **`"4.1.3"`** until the Crafty Syntax human live-help baseline is complete. **No** normative doctrine, templates, or agent examples should treat **`4.1.4`**, **`4.2.0`**, or generic **`4.1.x`** placeholders as the active contract during the freeze. **Header-format evolution resumes in 4.1.4+** after that baseline (not abandoned; paused).

**Version alignment:** This document is the **format companion** to **[PRD 16](../../prd/16_lupopedia_headers.md)**. **PRD 16** is authoritative for the **22-field** dense envelope, key order, and semantics. During the freeze, set **`header_format_version: "4.1.3"`** on new and updated files. Legacy trees may still declare **`4.1.0`–`4.1.2`** until migrated; validators branch on declared version per PRD 16 **§15.4**. The **(v4)** in this title is the **logical** headers generation label, not a second semver inside `header_format_version`.

## Core change

**Important:**
`lupopedia.edges` must never appear in the header block. It is always a separate block at the very bottom of the file, after the main content. The header is for metadata only.

Header format v3/v4 makes file headers minimal pointers. Rich metadata (`edges`, `footer`, tags, purpose, author metadata) now belongs in the associated memory file referenced by `memory_key`.

## Required header fields (v4 envelope, PRD 16 §4.2 key set)

**Key order (mandatory):** All **22** fields MUST appear in the **exact order** below (lines 3–24 under `lupopedia.headers:` in Markdown — the dense grid with **no blank lines** between fields). Validators emit **`HDR_KEY_ORDER`** on mismatch. Full code list: **[PRD 16](../../prd/16_lupopedia_headers.md)** (appendix §19.3).

**Presence:** All 22 rows are **required keys** — each MUST appear in the header YAML. **None may be omitted.** **Required** in the **Notes** column refers to **non-empty value** where applicable; if a field allows **`''`** or **`null`**, the key line must still be present (see **PRD 16 §4.2** key-presence rule).

**YAML `null` vs empty string:** Use lowercase **`null`** for nullable scalars (`content_id`, `pk_id`, `atoms_toon`). Use **`''`** for empty strings (`thread_id`, `pk_slug` when not a PRD). **`atoms_toon: ''` is forbidden** — use `atoms_toon: null` (**`HDR_ATOMS_TOON_SUFFIX`**). **`module` is deprecated** — rename to `atoms_toon` (validators emit **`HDR_MODULE_DEPRECATED`** WARN). **`dialog_transcript` is deprecated** — rename to `transcript_jsonl` (validators emit **`HDR_DIALOG_TRANSCRIPT_RENAMED`** WARN).

**Legacy field names:** `prd_id`, `prd_slug`, `parent_prd`, and **`pk_*`** aliases are **deprecated**. **New artifacts (4.1.3 freeze)** MUST use **`content_parent_id`**, **`content_slug`**, **`default_collection_id`** per **PRD 16** §4.2. Validators emit **`HDR_LEGACY_FIELD_NAME`** / **`HDR_PK_LEGACY_ALIAS`** (and related codes) per declared `header_format_version` (**PRD 16** §15.4).

| # | Field | Type | Notes |
|---|---|---|---|
| 1 | `header_format_version` | string | **`"4.1.3"`** (frozen normative string for new edits; see PRD 16 Header freeze rule). Validators still accept older **`4.1.0`–`4.1.2`** and **`4.0.*`** / integer **`3`** where migration policy applies. |
| 2 | `file_path_from_root` | string | Repo-relative path (POSIX forward slashes). **The agent anchor** — identifies this file even when no filesystem is present. |
| 3 | `web_path` | string | Canonical URL. MUST use **`https://`** (validator rejects `http://` unless `--development`). Derivable: `https://www.lupopedia.com/lupopedia/` + `file_path_from_root`. |
| 4 | `status` | string | Required for `prd` and `implementation` types; `''` otherwise |
| 5 | `when_updated` | string | UTC `YYYYMMDDHHIISS` (14 digits) |
| 6 | `trust_tier` | string | `seed` \| `canonical` \| `staging` \| `archive`. Drives `memory_key` path segment and year-offset encoding (§8.1). |
| 7 | `questions_toon` | null or string | Path to the `.questions.toon` Q&A file for this artifact, or YAML `null` (default; Q&A system not yet built). When non-null, MUST end in `.questions.toon` (validator: `HDR_QUESTIONS_TOON_SUFFIX`). Replaces deprecated `last_modified_utc` (was redundant with `when_updated`). See PRD 16 §19. |
| 8 | `memory_toon` | string | Path to `.toon` memory file (MUST end in `.toon`). **The brain pointer** — resolves to the file's compressed knowledge graph. Legacy prose may say `memory_key`; the canonical YAML key is **`memory_toon`** (**PRD 16** §4.2). |
| 9 | `atoms_toon` | null or string | YAML `null` (default) or path ending in `.atoms.toon`. Points to the immutable `.atoms.toon` sidecar for this artifact. `null` when no atoms file exists. `''` is **forbidden** (use `null`). **Replaces deprecated `module` field** (WARN: `HDR_MODULE_DEPRECATED`). See [atoms_toon_schema.md](atoms_toon_schema.md). |
| 10 | `transcript_jsonl` | string | **DB lookup slug** — `{federation_node_id}/{channel_key}/{thread_slug}` (exactly 3 segments normative; 4+ segments generate WARN). Type: string or null. Description: path to JSONL transcript file. Purpose: append-only log for agent messages and observer reads. **The WHY pointer** — resolves to the conversation thread explaining every decision in this file. Must match `header_metadata` sidecar byte-for-byte (**PRD 16 §6** dual-field rule). **Renamed from `dialog_transcript` in v4.1.0** (WARN: `HDR_DIALOG_TRANSCRIPT_RENAMED`). |
| 11 | `artifact_type` | string | From the Artifact Type Taxonomy (see TAXONOMY_REFERENCE.md) |
| 12 | `artifact_kind` | string | From the Artifact Kind Taxonomy; cross-validates with `artifact_type` |
| 13 | `channel_key` | string | Discussion channel slug. Required, never silently inferred. |
| 14 | `federation_node_id` | integer | `0` = core repo, `1` = local install, `2+` = external |
| 15 | `thread_id` | string | `''` for `prd` type; REQUIRED non-empty for `discussion` type; `''` unless explicitly bound |
| 16 | `content_id` | integer or null | **DB key to `lupo_contents`** — `null` until ANUBIS creates the row; integer once linked. `null` = orphan. |
| 17 | `content_parent_id` | integer or null | Parent content linkage; `null` when none. **`pk_id`** is a deprecated alias (WARN for 4.1.0–4.1.2; rejected under 4.1.3+ when still present). |
| 18 | `content_slug` | string | URL-safe slug when bound; `''` when unused. **`pk_slug`** is a deprecated alias (same WARN/reject timeline). |
| 19 | `default_collection_id` | integer or null | Collection binding; `null` when none. **`parent_pk_id`** is a deprecated alias (same WARN/reject timeline). |
| 20 | `lupopedia.schema` | string | Closed enum: `prd`, `doctrine`, `documentation`, `implementation`, `discussion`, `changelog`, `architecture`, `specification` |
| 21 | `title` | string | Required for `prd` type (duplicates Markdown H1 intentionally for head-25 access); `''` otherwise |
| 22 | `summary` | string | Single-line description or `''`. Never multi-line. |

## Removed from YAML header in v3

These fields/blocks are no longer written in `lupopedia.headers` YAML (they belong in **`header_metadata`** / memory graph, not in the 20-key block):

- `lupopedia.edges`
- `lupopedia.footer`
- `tags`
- `purpose`
- `author` block
- `delegation_chain`
- `next_action`
- `version` (per-artifact semantic version string — forbidden in v3; use `header_format_version`, git history, `CHANGELOG.md`, document title, and sidecar freshness)

**Not removed:** **`content_id`** (and the other **§4.2** scalars) remain **required** in the header YAML for v4. **`content_id: null`** means not yet imported into `lupo_contents`.

Content for `lupopedia.edges`, footer metadata, and author/delegation blocks belongs in the memory file pointed to by **`memory_toon`**. The legacy `version` key must be omitted entirely — it is not written to the header or the sidecar (**PRD 16** §4.4).

## New minimal example (4.1.3 dense envelope)

The **file** must use the 25-line dense envelope: **22 key lines** at lines 3–24 (**no blank lines** between them), closing `---` at line 25, first body line at line 26 (**MUST** be non-whitespace, **`HDR_EMPTY_BODY`**).

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/lupopedia-headers/lupopedia_headers_format.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/lupopedia_headers_format.md"
  status: "active"
  when_updated: "20260418192619"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/lupopedia-headers-format.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/lupopedia-headers"
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: ""
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "LUPOPEDIA HEADERS Format Specification (v4)"
  summary: "Format companion to PRD 16 at 4.1.3 (frozen): 22-field dense envelope"
---
```

## Memory file association (v4)

- **`memory_toon`** must resolve to a `.toon` file.
- That `.toon` file carries rich metadata and verification state.
- Memory schema definition is in **[MEMORY_FILE_SCHEMA.md](MEMORY_FILE_SCHEMA.md)**.

### Memory file pairing (JSON master → TOON derived)

For **`seed`** and **`canonical`** **`trust_tier`** artifacts that use a paired representation:

1. **JSON master** (`<basename>.json`) — human-editable authoring format in the same directory as the export.
2. **TOON derived** (`<basename>.toon`) — generated from JSON via **`python scripts/json_to_toon.py`**.

The header’s **`memory_toon`** **MUST** point at the **`.toon`** file only (never the `.json` in this field). When the **`.toon`** file **exists on disk**, the universal validator **SHOULD** warn if the sibling **`.json`** is missing (**`HDR_MEMORY_JSON_MASTER`**, alias **`SIDECAR_JSON_MASTER_MISSING`**); use **`--strict-memory-pair`** to fail the build instead. **`--development`** skips this check (**PRD 16** §5.2.2, §12.4).

**Generation command:**

```bash
python scripts/json_to_toon.py --json "<base>.json" --toon "<base>.toon"
```

Deep pairing checks: **`python scripts/validate_memory_json_toon_pair.py`** (see [VALIDATORS_AND_TOOLING.md](VALIDATORS_AND_TOOLING.md)).

### Trust-tier path pattern

**`memory_toon`** follows trust-tiered pathing (after the `memory/` prefix, the next segment is always **`{channel_key}`**, then **`{trust_tier}`**):

- **`seed`:** `memory/{channel_key}/seed/{slug}.toon`  
  Example: `memory/constitutional/seed/prd-00-constitutional.toon`
- **`canonical`:** `memory/{channel_key}/canonical/{display_year}/{month}/{slug}.toon`  
  **`display_year = actual_year - 1000`** (e.g. calendar **2026** → **1026**) so verified exports sort **before** staging directories that use calendar years in listings (**PRD 16** §8.1).
- **`staging`:** `memory/{channel_key}/staging/{actual_year}/{month}/{slug}.toon` (real calendar year in the path segment)
- **`archive`:** `memory/{channel_key}/archive/{actual_year}/{month}/{slug}.toon` — **same calendar-year shape as `staging`** (not the 1000-offset); the **`archive`** segment marks deprecated / superseded lineage.

**Compact legacy paths** (no `{year}/{month}` after `trust_tier`) may still appear in the repo (e.g. `memory/{channel_key}/seed/{slug}.toon` only); validators accept them when segments match header **`channel_key`** and **`trust_tier`** (**PRD 16** §8.1, **VALIDATORS_AND_TOOLING**).

## Compatibility policy

- **v1 / v2:** rejected (**PRD 16** §12.1).
- **v3** (integer **`header_format_version: 3`**) and **v4.0.x** (string **`"4.0.x"`**): validators may still accept these for **legacy** trees per **PRD 16** migration policy. **New and edited** artifacts during the **Header freeze (4.1.3)** MUST declare **`header_format_version: "4.1.3"`** and the **§4.2** key set per **PRD 16**.

## Fixed Position Headers (v4.0.99 — Dense Envelope)

The **universal** rule (**PRD 16** section 4.3): the header occupies exactly **lines 1–25**; **line 26** is the first line of Markdown body and **MUST** be non-whitespace (**`HDR_EMPTY_BODY`**). Same grid for all in-scope file types; only delimiters differ by language. **`--development`** may downgrade that check to a warning per **PRD 16** §12.4.

**v4.0.99 change from v4.0.0:** The field count increased from 20 to **22** (added `summary` and `module`). The two blank lines formerly at lines 23–24 are **gone** — all 22 key lines run contiguously from line 3 to line 24 with **no internal blank lines**. The closing `---` is always **line 25**. **Field 21 `module` was subsequently renamed to `atoms_toon`** (immutable sidecar pointer); `module` is accepted as legacy alias during migration (emit `HDR_MODULE_DEPRECATED` WARN).

| Lines | Markdown (`.md`) |
|-------|------------------|
| 1 | Opening `---` |
| 2 | `lupopedia.headers:` |
| 3–24 | **22** one-line `key: value` rows (**PRD 16 §4.2** order; **no blank lines** inside this block) |
| 25 | Closing `---` |
| 26+ | Body (line 26 MUST be non-whitespace) |

**Why 25 lines exactly?** The 25-line contract is the **agent discovery protocol** (PRD 16 §18): any agent or tool can read complete file metadata with `head -25 file.md`. See §18 for the full rationale.

Example (line numbers):

```text
1:  ---
2:  lupopedia.headers:
3:    header_format_version: "4.1.0"
4:    file_path_from_root: "path/to/file.md"
...
24:   summary: "..."
25: ---
26: # Content starts here
```

**Validators MUST enforce:** no body text before line 26; **no blank lines inside lines 3–24**; closing delimiter on line 25; exactly 22 key lines. Error codes: **`HDR_LINE_COUNT`**, **`HDR_HEADER_INTERNAL_BLANK`**, **`HDR_MISSING_CLOSE`**, **`HDR_LEGACY_ENVELOPE`** (old 20-key format with blank lines 23–24).

**Also:**

- Exactly **one** leading `lupopedia.headers` block; duplicate consecutive blocks are invalid (`HDR_MULTIPLE_HEADERS`).
- Legacy envelopes (e.g. closing `---` immediately after the last key with fewer than two blank lines) are rejected once migrated; tooling may still parse older files during transition.

## Error codes (summary)

Normative list: **[PRD 16 — Validator error codes (§19.3)](../../prd/16_lupopedia_headers.md)**.

| Code | Typical cause |
|------|----------------|
| `HDR_LINE_COUNT` | Envelope not exactly 25 lines or inner key block not 21 lines |
| `HDR_MISSING_BLANK_LINE` | Lines 23–24 not blank (Markdown) or Python grid equivalent |
| `HDR_MISSING_CLOSE` | Line 25 not closing `---` (Markdown) |
| `HDR_MISSING_KEY` / `HDR_EXTRA_KEY` | Wrong §4.2 key set |
| `HDR_KEY_ORDER` | Keys out of §4.2 order |
| `HDR_MULTIPLE_HEADERS` | More than one leading header block |
| `HDR_CONTENT_ID_MISSING` / `HDR_CONTENT_ID_INVALID` | `content_id` absent or wrong type |
| `HDR_PYTHON_*` | Python comment-embedded grid (shebang / line count / structure) |

## Other file types (same 25-line grid)

Delimiters differ; mechanical line budget is the same (**PRD 16** §4.3). Full tooling notes: **[VALIDATORS_AND_TOOLING.md](VALIDATORS_AND_TOOLING.md)**.

### Python (`.py`)

**Line 1:** optional `#!/usr/bin/env python3`. **Lines 2–26** (with shebang) or **1–25** (without): opening `#` separator, `# lupopedia.headers:`, **20** lines `#   key: value`, **two** blank `#` lines, closing `#` separator. **First code line** starts immediately after (line **27** with shebang, **26** without).

```python
#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.98"
#   lupopedia.schema: implementation
#   when_updated: "20260410194629"
#   file_path_from_root: "scripts/example.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/example.py"
#   questions_toon: null
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "memory/development/canonical/1026/04/example.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   prd_id: null
#   prd_slug: ""
#   title: "Example"
#   status: "complete"
#   parent_prd: "16"
#   transcript_jsonl: "0/development/prd_files/example"
#
#
# ---------------------------------------------------------------------
def main():
    pass
```

### PHP (`.php`)

Block comment immediately after `<?php` (or opening tag policy your file uses). Same **20** keys and **two** blank comment lines before the closing separator; total **25** comment lines in the grid per validator rules.

```php
<?php
/*
 * ---------------------------------------------------------------------
 * lupopedia.headers:
 *   header_format_version: "4.0.98"
 *   lupopedia.schema: implementation
 *   when_updated: "20260410194629"
 *   file_path_from_root: "path/to/example.php"
 *   web_path: "https://www.lupopedia.com/lupopedia/path/to/example.php"
 *   questions_toon: null
 *   federation_node_id: 0
 *   channel_key: "development"
 *   trust_tier: "canonical"
 *   memory_key: "memory/development/canonical/1026/04/example.toon"
 *   artifact_type: implementation
 *   artifact_kind: library
 *   thread_id: ""
 *   content_id: null
 *   prd_id: null
 *   prd_slug: ""
 *   title: "Example PHP"
 *   status: "complete"
 *   parent_prd: "16"
 *   transcript_jsonl: "0/development/prd_files/example"
 *
 *
 * ---------------------------------------------------------------------
 */
```

## Migration sketch (v2-style → v4 envelope)

v2 shapes (flat keys, `channel_id`, `actor_id` / `actor_name`, `tags` in header) are **not** valid after migration. Target shape:

**Before (illustrative v2 — invalid after freeze)**

```yaml
---
lupopedia.headers:
  version: "2"
  when_updated: "20260401000000"
  channel_id: 5
  actor_id: 102
  actor_name: "CURSOR"
  tags: [tag-example]
---
```

**After (v4 — excerpt; add full 20 keys + two blank lines + closing `---`)**

```yaml
---
lupopedia.headers:
  header_format_version: "4.0.98"
  lupopedia.schema: documentation
  when_updated: "20260410194629"
  file_path_from_root: "path/to/file.md"
  web_path: "https://www.lupopedia.com/lupopedia/path/to/file.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/example.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  prd_id: null
  prd_slug: ""
  title: "Example Document"
  status: "active"
  parent_prd: ""
  transcript_jsonl: "0/development/prd_files/example-thread"


---
```

**Changes:** `header_format_version: "4.1.0"`; all **§4.2** keys in order; `channel_key` replaces `channel_id`; **`content_id: null`**; **`transcript_jsonl`** (formerly `dialog_transcript`); actor/tags/footer/edges → **`header_metadata`** / memory graph.

Use **`python scripts/add_lupopedia_header_to_file.py`**, **`fix_double_headers.py`**, **`normalize_lupopedia_md_header_25.py`**, then **`validate_lupopedia_headers_universal.py`** (see **VALIDATORS_AND_TOOLING**).

## Agent Discovery Protocol (head-25 contract)

The 25-line fixed envelope is a **machine-readable contract** for agent-efficient discovery. See **PRD 16 §18** for the complete specification. Summary:

| Use case | Command | Cost |
|---|---|---|
| Read complete file metadata | `head -25 file.md` | 25 lines |
| Survey all PRD metadata | `grep -h "pk_id:\|title:\|status:" docs/prd/*.md` | ~3 lines/file |
| Find all orphans (no DB row) | `grep -rl "content_id: null" docs/` | O(n headers) |
| Find staging files | `grep -rl 'trust_tier: "staging"' docs/` | O(n headers) |
| Load compressed knowledge | `cat {memory_key}` | ~1–5 KB |

**Three depths of access:**
1. **Header** (`head -25`) — identity, DB key, memory pointer, WHY slug
2. **TOON file** (`cat {memory_key}`) — compressed entities, decisions, edges
3. **Full file** — complete specification (only read when modifying)

## Related files

- [TAXONOMY_REFERENCE.md](TAXONOMY_REFERENCE.md)
- [OPTIONAL_BLOCKS.md](OPTIONAL_BLOCKS.md)
- [VALIDATORS_AND_TOOLING.md](VALIDATORS_AND_TOOLING.md)
- [VERIFICATION_GUIDE.md](VERIFICATION_GUIDE.md)
- [MEMORY_FILE_SCHEMA.md](MEMORY_FILE_SCHEMA.md) — schema for `.toon` files referenced by **`memory_key`**
