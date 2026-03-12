# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\VERSION_DOCTRINE_APPLICATION_REPORT.md"
  file_hash: "2b9bd3019c450e4f33d76a49e3f5125fbbfe3cee2f666b5cec9aa5c9c5072f68"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\VERSION_DOCTRINE_APPLICATION_REPORT.md"
  file_hash: "3964b8c30e3d063864a493678cbfea2c3f5514e02414b7da6b513d6d7a688a1d"
  file_path_from_root: "docs\VERSION_DOCTRINE_APPLICATION_REPORT.md"
  file_hash: "f8c841ccada5338f9e0104ee98359b4b9f1c34752eb638b5cc8b9428c79d5281"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Version Doctrine Application Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "version_doctrine_application_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Version Doctrine Application Report

**Date:** 2026-02-10  
**Scope:** Apply authoritative Version Doctrine across code, install/seed SQL, migrations, and key docs. Only **3.0.0** (release target) and **4.1.0** (next planned release) may be referenced; **4.0.1–3.0.8** and any version beyond 4.1.0 must not appear in code, SQL, UI, or metadata.

---

## 1. Files updated

| File | Change |
|------|--------|
| `lupo-includes/functions/load_atoms.php` | @version 4.2.3 → 3.0.0; fallback '3.0.35' → '3.0.0'; docblock example 3.0.35 → 3.0.0 |
| `app/Support/AtomLoader.php` | Fallback '3.0.35' → '3.0.0' in getLupopediaVersion() |
| `app/Support/VersionUtils.php` | Docblock example "3.0.35" → "3.0.0" |
| `lupo-includes/version.php` | Docblock return example "3.0.35" → "3.0.0" |
| `lupo-includes/functions/identity-helpers.php` | wolfie.header.version 4.2.0 → 3.0.0 |
| `lupo-includes/functions/limits_logger.php` | @version 3.0.106 → 3.0.0 |
| `lupo-includes/functions/redirect-helpers.php` | wolfie.header.version 3.0.9 → 3.0.0 |
| `lupo-includes/functions/collection-tabs-loader.php` | wolfie.headers.version 3.0.18 → 3.0.0 |
| `lupo-includes/modules/help/help-model.php` | wolfie.header.version 4.4.1 → 3.0.0 |
| `database/migrations/install_new_lupopedia.sql` | All DEFAULT '3.0.72' and '3.0.75' → '3.0.0' (protocol_version, doctrine_alignment_version, impact_version, awareness_version, analytics_version, propagation_version, audit_version, refinement_version, calibration_version, sync_version) |
| `database/migrations/seed_lupopedia.sql` | awareness_version '3.0.72' → '3.0.0' |
| `database/migrations/dev_20260204_fix_schema_alignment.sql` | All DEFAULT '3.0.72' and '3.0.75' → '3.0.0' |
| `database/migrations/dev_20260204_fix_schema_alignment_summary.txt` | All 3.0.72 and 3.0.75 → 3.0.0 |
| `docs/HELPER_TO_CLASS_MAPPING_ANALYSIS.md` | Fallback version example '3.0.35' → '3.0.0' |
| `docs/REMAINING_HELPERS_REFACTOR_REPORT.md` | Fallback example '3.0.35' → '3.0.0' |
| `docs/REQUIRED_TABLES_4.1.0.md` | Purpose and heading/version 4.1.0 → 3.0.0 |
| `.cursorrules` | Added Version doctrine bullet: 4.0.1–3.0.8 forbidden, upgrade path 3.7.5 → 3.0.0, 4.1.0 only future; reference VERSION_DOCTRINE.md |
| `docs/doctrine/VERSION_DOCTRINE.md` | **New.** Authoritative Version Doctrine (3.0.0 only release, 4.0.1–3.0.8 not releases, upgrade path 3.7.5 → 3.0.0, 4.1.0 only future, code rules, cascade rules, single source of truth). |

---

## 2. Version references removed

- **4.2.3** — load_atoms.php @version (replaced with 3.0.0).
- **4.2.0** — identity-helpers.php wolfie.header.version (replaced with 3.0.0).
- **4.4.1** — help-model.php wolfie.header.version (replaced with 3.0.0).
- **3.0.106** — limits_logger.php @version (replaced with 3.0.0).
- **3.0.9** — redirect-helpers.php wolfie.header.version (replaced with 3.0.0).
- **3.0.18** — collection-tabs-loader.php wolfie.headers.version (replaced with 3.0.0).
- **3.0.72** — install_new_lupopedia.sql, seed_lupopedia.sql, dev_20260204_fix_schema_alignment.sql, dev_20260204_fix_schema_alignment_summary.txt (all replaced with 3.0.0).
- **3.0.75** — install_new_lupopedia.sql, dev_20260204_fix_schema_alignment.sql, dev_20260204_fix_schema_alignment_summary.txt (all replaced with 3.0.0).
- **3.0.35** — load_atoms.php, AtomLoader.php, VersionUtils.php docblock, version.php docblock, HELPER_TO_CLASS_MAPPING_ANALYSIS.md, REMAINING_HELPERS_REFACTOR_REPORT.md (fallback/example replaced with 3.0.0).
- **4.1.0** — docs/REQUIRED_TABLES_4.1.0.md purpose and heading/version (replaced with 3.0.0).

---

## 3. Version references corrected (to 3.0.0 or 4.1.0)

- All fallbacks and examples in code/docs now use **3.0.0**.
- Install and seed SQL column defaults now use **3.0.0**.
- File headers and @version in touched files now use **3.0.0**.
- REQUIRED_TABLES doc now states **3.0.0** as the release version.
- **4.1.0** is referenced only in VERSION_DOCTRINE.md and .cursorrules as the only future version (no 4.1.1, 4.2.0, etc.).

---

## 4. Confirmations

- **Only 3.0.0 and 4.1.0 remain (where versions are stated):** Code, install/seed SQL, and updated docs now use 3.0.0; 4.1.0 appears only as “next planned release” in doctrine and .cursorrules.
- **No 4.0.1–3.0.8 references remain in code, SQL, or updated docs:** Removed or replaced with 3.0.0; no new references to 4.0.1–3.0.8 were introduced.
- **Upgrade path is strictly 3.7.5 → 3.0.0:** Documented in docs/doctrine/VERSION_DOCTRINE.md and .cursorrules; no upgrade paths to 4.0.1+.
- **No future versions beyond 4.1.0 are referenced:** Doctrine and .cursorrules state 4.1.0 as the only future version; 4.1.1, 4.2.0, 4.3.0, etc. are not referenced in the updated set.

**Note:** Internal/historical docs (e.g. CHANGELOG, docs/channels/overview, migration filenames like 4.2.0_*) were not rewritten; the doctrine allows 4.0.1–3.0.8 and similar to appear in internal docs as development notes. This report and the Version Doctrine apply to code, install/upgrade SQL, version checks, UI version displays, and documentation that defines the install/upgrade version.