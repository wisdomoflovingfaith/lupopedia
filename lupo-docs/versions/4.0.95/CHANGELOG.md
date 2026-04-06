---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: changelog
  when_updated: "20260406062838"
  file_path_from_root: "lupo-docs/versions/4.0.95/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/CHANGELOG.md"
  last_modified_utc: "20260406062838"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.95-changelog"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "changelog"
  artifact_kind: "version"
  purpose: "Changelog for Lupopedia 4.0.95 (scaffold)"
  tags: ["changelog", "version", "4.0.95", "cursor"]
lupopedia.footer:
  last_verified: "20260406062838"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/CHANGELOG.md — delegation: cursor:root

# Changelog - Lupopedia 4.0.95

## [4.0.95] - 2026-04-06

### Version bump (runtime + docs)

- **`lupo-config/global_atoms.yaml`** — `version`, **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`**: **4.0.95**
- **`lupo-includes/version.php`**, **`version.txt`**, **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`** (canonical current version §1), root **`README.md`** / **`CHANGELOG.md`** pointers — aligned to **4.0.95**
- **`lupo-rules/root/php-7-4-compatibility.md`** — rule stamp **4.0.95**

### Prior work carried into this line (from late 4.0.94 development)

- Install wizard: **mysqli**-backed **`InstallWizardMysqliLink`** for installer DB (WordPress-style buffering; avoids PDO MySQL **2014**), buffered PDO only for **`PDO_DB`** activation paths
- **`lupopedia-config.php` generator** — fixed missing **`}`** after **`LUPOPEDIA_PATH`** define (parse error on first load after install)

Add thread-verified entries below as work completes.

This output complies with Lupopedia Constitutional Root Rules.
