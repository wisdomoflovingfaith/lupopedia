---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404061932"
  file_path_from_root: "docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_study_20260404.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_study_20260404.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: status
  artifact_kind: research_report
  thread_id: "33-softaculous-wordpress-study"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "complete"
  parent_pk_id: "33_softaculous_certification_4_1_0_gate"
  summary: ""
  module: null
  dialog_transcript: null
---
# file: Status — WordPress distribution study — PRD 33

# WordPress distribution pattern study — status report

**Session UTC:** `20260404061540`  
**Reference:** `archive/legacy/wordpress-reference/` (core **6.9.4** per `wp-includes/version.php`)

## Files analyzed (primary)

| Path under `archive/legacy/wordpress-reference/` | Patterns noted |
|-----------------------------------|----------------|
| `wp-admin/includes/misc.php` | **`insert_with_markers`**, **`save_mod_rewrite_rules`**, **`iis7_save_url_rewrite_rules`** |
| `wp-includes/load.php` | **`wp_check_php_mysql_versions`**, **`wp_fix_server_vars`**, bootstrap ordering |
| `wp-includes/version.php` | **`$required_php_version`** (7.2.24), **`$required_php_extensions`** (`json`, `hash`), **`$wp_version`** |
| `wp-includes/functions.php` | **`wp_mkdir_p`**, **`wp_upload_dir`** |
| `wp-admin/setup-config.php` | **wp-config-sample → wp-config.php**, **`is_writable(ABSPATH)`** gate |
| `wp-admin/install.php` | Installer shell; pulls **upgrade** + **translation** APIs |

**Not found in this checkout:** root **`.gitignore`** (empty or not vendored). **`.htaccess`** appears under a plugin (**Akismet**), not core root — consistent with “generate at runtime” for core.

## Insights (high level)

1. **Rewrite files are environment-specific.** WordPress writes **`.htaccess`** only when **mod_rewrite** path applies; **IIS** gets **`web.config`**. Lupopedia currently **documents** Nginx/IIS and **writes** Apache **`.htaccess`** on install when possible.

2. **Marker-based updates** reduce conflict with operator edits. Lupopedia today **replaces** known-good full content; upgrading to **BEGIN/END** blocks is a **future** improvement.

3. **PHP requirements are hard-fail early.** WordPress **500**s with clear text if PHP or extensions fail. Lupopedia installer uses **wizard preflight**; extension matrix can still expand (**GD**, etc.) per gate.

4. **Directory creation inherits permissions in WordPress.** Fixed **0755** in Lupopedia is simpler but may need tuning on locked-down shared hosting.

## Lupopedia delta (already landed vs open)

| Topic | State |
|-------|--------|
| No dotfiles in Softaculous zip | **Shipped** — **`build_softaculous_package.sh`** + docs |
| Install-time **`.htaccess`** | **Shipped** — **`InstallWizardHtaccessWriter`** |
| Runtime dirs without **`.gitkeep`** in zip | **Shipped** — **`ensureRuntimeDirectories`** |
| **`.gitkeep`** in git repo | **Resolved** — remove from tree; installer owns dirs (**LILITH Q6**); see **`wordpress_pattern_implementation_tasks_20260404.md`** |
| **web.config** / Nginx automation | **Resolved** — docs + optional example only (**LILITH Q3**) |
| Marker merge for **`.htaccess`** | **Resolved** — adopt markers (**LILITH Q1**); immediate write retained (**Q2**) |

## Questions filed (resolved)

**`questions/20260404_061540_QUESTION_wordpress_distribution_patterns_unresolved.md`** — answered by **`answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md`**.

## PRD update

**`docs/prd/33_softaculous_certification_4_1_0_gate.md`** — new **Section 14**.

This file complies with Lupopedia Constitutional Root Rules.
