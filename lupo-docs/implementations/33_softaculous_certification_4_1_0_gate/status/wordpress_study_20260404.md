---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404061932"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_study_20260404.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_study_20260404.md"
  last_modified_utc: "20260404061932"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-wordpress-study"
  actor_id: 102
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "status"
  artifact_kind: "research_report"
  purpose: "WordPress reference study for Softaculous packaging — files read, insights, Lupopedia delta"
  status: complete
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "PRD 33 Section 14"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_061540_QUESTION_wordpress_distribution_patterns_unresolved.md"
      type: references
      weight: 1.0
      reason: "Original question thread (resolved)"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
      type: references
      weight: 1.0
      reason: "LILITH resolutions"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_pattern_implementation_tasks_20260404.md"
      type: references
      weight: 1.0
      reason: "Code and documentation backlog"
lupopedia.footer:
  last_verified: "20260404061932"
  verified_by:
    identity_type: agent
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "cursor:root"
---

# file: Status — WordPress distribution study — PRD 33

# WordPress distribution pattern study — status report

**Session UTC:** `20260404061540`  
**Reference:** `lupo-archive/legacy/wordpress-reference/` (core **6.9.4** per `wp-includes/version.php`)

## Files analyzed (primary)

| Path under `lupo-archive/legacy/wordpress-reference/` | Patterns noted |
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

**`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** — new **Section 14**.

This file complies with Lupopedia Constitutional Root Rules.
