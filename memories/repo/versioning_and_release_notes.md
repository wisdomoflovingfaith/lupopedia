---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: memories/repo/versioning_and_release_notes.md
  web_path: https://www.lupopedia.com/lupopedia/memories/repo/versioning_and_release_notes.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: repo-versioning-memory
  lupopedia.schema: documentation
  prd_cluster: null
  title: Versioning and release notes (repo memory)
  summary: Index of versioning files and header-driven bump rule; points to VERSIONING_DOCTRINE.md.
---
# Versioning and release notes (repo memory)

**Canonical doctrine:** [VERSIONING_DOCTRINE.md](../../lupo-docs/doctrine/VERSIONING_DOCTRINE.md)

**Short rule:** On **4.0.x**, bump **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`** (patch) **only** when **LUPOPEDIA HEADERS** change (envelope, keys, parse/validate/store). No **Lupopedia→Lupopedia** upgrade path until auto-installer gate (**4.1.0**).

**Version-folder changelog:** **`lupo-docs/versions/<version>/CHANGELOG.md`** — **oldest at top, newest at bottom**; **always append** new entries at the **bottom**; titles **`[YYYY-MM-DD HH:MM UTC]`**; use **`tac`** to read newest first. See **VERSIONING_DOCTRINE.md §10.1**.

**Where version strings and process live:**

| Artifact | Path |
|----------|------|
| Changelog (4.0.85+) | `lupo-docs/versions/<version>/CHANGELOG.md` |
| Root changelog index | `CHANGELOG.md`, `CHANGELOG_ARCHIVE.md` |
| Atoms | `lupo-config/global_atoms.yaml` |
| Runtime | `lupo-includes/version.php`, `install.php`, `lupo-includes/functions/load_atoms.php` |
| Bump automation | **Deprecated:** `lupo-bin/bump-version.php` (exit **3**); edit **`lupo-config/global_atoms.yaml`** manually per **VERSIONING_DOCTRINE.md** |
| Packaging | `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md` |
| PRD | `lupo-docs/prd/40_versioning_doctrine.md` |
| Schema / TOON | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, `lupo-database/lupopedia/toon/` |
| Tree / verify scripts | `lupo-scripts/generate_directory_tree.py`, `lupo-scripts/verify_db_against_toons.py` |

Plain-text mirrors (if present at repo root): `version.txt`, `CURRENT_LUPOPEDIA_VERSION.txt`.

**Enforcement:** Before a bump, apply **§3** in **VERSIONING_DOCTRINE.md**.
