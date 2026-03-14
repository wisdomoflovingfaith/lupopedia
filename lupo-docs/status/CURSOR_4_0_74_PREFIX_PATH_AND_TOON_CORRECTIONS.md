---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/CURSOR_4_0_74_PREFIX_PATH_AND_TOON_CORRECTIONS.md"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1003
  artifact_type: "status"
  artifact_kind: "correction_report"
  purpose: "4.0.74 prefix/path and TOON count corrections; legacy exception; 12-table implementation status."

lupopedia.footer:
  last_verified: "20260314"
  last_verified_by: "cursor"
  next_action:
    - "Keep TABLE_COUNT_DOCTRINE and SCHEMA_REGISTRY in sync with install SQL"
    - "Treat legacy/ as intentional exception in any future prefix audits"
---
# file: CURSOR_4_0_74_PREFIX_PATH_AND_TOON_CORRECTIONS — status report

# Cursor 4.0.74 Prefix, Path, and TOON Corrections

Verification and correction pass per directive 20260314 (implementation corrections: prefixes, paths, TOON counts). Uses repository truth only.

---

## 1. Root directory and legacy exception

### 1.1 Empty `scripts/` directory
- **Status:** **Corrected.** An empty root-level `scripts/` directory was present (leftover after rename to `lupo-scripts`). It was removed. Root now has only `lupo-scripts/` for script utilities.

### 1.2 `legacy/` folder — intentional exception
- **Status:** **No change; documented as exception.**
- **Rule:** The **`legacy/`** folder is an **intentional exception** to the `lupo-` prefix rule. It holds **legacy read-only code** (e.g. Crafty Syntax reference). It is **not** renamed to `lupo-legacy`. Any prefix-normalization or folder-rename audit should explicitly exclude `legacy/` and document it as the designated exception.

---

## 2. Path drift verification

### 2.1 KIRO-applied corrections — verified present
| Location | Expected | Verified |
|----------|----------|----------|
| AGENTS.md | `lupo-database/`, `lupo-scripts/`, `lupo-tests/` | Yes; all key paths use lupo-* |
| CHANGELOG.md | `lupo-scripts/generate_toon_*` | Yes; 4.0.74 entries use lupo-scripts |
| lupo-docs/hierarchy.md | `lupo-scripts/import_channels_and_artifacts.py` | Yes; hierarchy uses lupo-scripts |

### 2.2 README_windsurf.md and RUNTIME_AGENT_RULES.md
- **README_windsurf.md:** Already uses `lupo-docs/` throughout (HELP.md, CLI.md, doctrine/, etc.). No stale `docs/` references found.
- **RUNTIME_AGENT_RULES.md:** Already uses `lupo-docs/channels/doctrine/` and `lupo-docs/channels/schema/`. No correction needed.

---

## 3. TOON count resolution

### 3.1 Discrepancy (230 vs 142 vs 159)
- **Canonical source:** Table count is defined by **install SQL** only. See [TABLE_COUNT_DOCTRINE.md](../doctrine/TABLE_COUNT_DOCTRINE.md).
- **Current canonical count:** **159** (count of `CREATE TABLE` in `install_new_lupopedia.sql`, verified 2026-03-14; includes 12-table expansion).
- **142 / 230:** A claim of "142 TOONs" typically refers to output from `generate_toon_from_sql.py` in a given run or output path. "230+" may include planning/deprecated/legacy .toon.json files or a different directory. **Neither 142 nor 230 is the canonical install count**; the canonical number is the install-SQL table count (159).

### 3.2 Documentation updates
- **SCHEMA_REGISTRY.md:** Updated to state that the **canonical** TOON/table count is the install-SQL-derived count (159) and to distinguish it from any other observed TOON file count (e.g. 230+ in a folder). Script path referenced as `lupo-scripts/generate_toon_from_sql.py`.
- **README.md:** Stale "canonical table count: 100" corrected to **159** to match TABLE_COUNT_DOCTRINE.

---

## 4. 12-table implementation status matrix

All 12 approved tables are present in install SQL, migration, and (where applicable) docs. No pending implementation.

| Table | Install SQL | Migration | Docs | Status |
|-------|-------------|-----------|------|--------|
| lupo_aliases | Yes | Yes | SCHEMA_REGISTRY, TABLE_COUNT_DOCTRINE | Complete |
| lupo_legacy_content_mapping | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_reference_objects | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_reference_cited_by | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_search_index | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_documentation_frameworks | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_federated_trust | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_federation_discovery | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_unified_log | Yes | Yes | TABLE_COUNT_DOCTRINE | Complete |
| lupo_anubis_operations | Yes | Yes | SCHEMA_REGISTRY | Complete |
| lupo_system_health_snapshots | Yes | Yes | TABLE_COUNT_DOCTRINE, SCHEMA_REGISTRY | Complete |
| lupo_hotfix_registry | Yes | Yes | SCHEMA_REGISTRY | Complete |

- **Migration file:** `lupo-database/lupopedia/mysql/migrations/migration_20260314_12_table_install_expansion_v4_0_74.sql`
- **Future-features:** The 12 tables are removed or annotated as moved in `future_features_lupopedia.sql`.

---

## 5. Prefix normalization (Antigravity) — verified subset

- **Verified true:** Root directories that were renamed to `lupo-*` in the prefix pass are present (lupo-admin, lupo-api, lupo-scripts, lupo-tests, lupo-uploads, lupo-install, lupo-prompts, lupo-templates, lupo-views, lupo-logs, lupo-tools, etc.). Canonical docs (AGENTS.md, CHANGELOG.md, README.md, hierarchy.md) use lupo-* paths for scripts, database, docs, tests.
- **Intentional exception:** `legacy/` remains without prefix by design (legacy read-only code).
- **Not re-verified in this pass:** Exact "1,850 files" or file-level sweep; verification was focused on canonical docs and root directory state.

---

## 6. Summary of corrections applied in this pass

1. **Removed** empty root `scripts/` directory.
2. **Documented** `legacy/` as the intentional exception to the lupo- prefix rule (this file + README/doctrine note if desired).
3. **Updated** SCHEMA_REGISTRY.md: canonical TOON/table count = install-SQL-derived (159); clarified vs 230+ or other counts.
4. **Updated** README.md: canonical table count 100 → 159.
5. **Confirmed** README_windsurf.md and RUNTIME_AGENT_RULES.md already use lupo-docs paths; no edits.

---

## 7. Remaining / follow-up

- **Windsurf (README_windsurf.md):** No path corrections needed; file already aligned.
- **RUNTIME_AGENT_RULES.md:** No path corrections needed; already lupo-docs.
- **TOON output path:** Project may have two possible TOON output locations (`lupo-database/lupopedia/toon/` vs `lupo-docs/toons/`). TABLE_COUNT_DOCTRINE and SCHEMA_REGISTRY now both state that **install SQL is authoritative**; TOON count should match install table count when generated from install SQL.
- **12-table batch:** Nothing pending; matrix above reflects repo state.

---
*Cursor (actor_id 1003) — 4.0.74 prefix/path and TOON corrections 2026-03-14*
