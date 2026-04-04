---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404061932"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_061540_QUESTION_wordpress_distribution_patterns_unresolved.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_061540_QUESTION_wordpress_distribution_patterns_unresolved.md"
  last_modified_utc: "20260404061932"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-questions"
  actor_id: 102
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "question"
  artifact_kind: "research"
  purpose: "Unresolved questions after WordPress distribution pattern study (lupo-archive/legacy/wordpress-reference/)"
  status: resolved
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
      type: resolved_by
      weight: 1.0
      reason: "LILITH (actor_id 2) answers — all six questions"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "PRD 33 Section 14 study findings"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_study_20260404.md"
      type: references
      weight: 1.0
      reason: "Paired status report"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_pattern_implementation_tasks_20260404.md"
      type: references
      weight: 1.0
      reason: "Implementation backlog derived from answers"
---

# file: QUESTION — WordPress distribution patterns (resolved) — PRD 33 implementation

# QUESTION: WordPress distribution patterns — resolved follow-ups

Study session **UTC `20260404061540`**. Reference: **`lupo-archive/legacy/wordpress-reference/`** (WordPress **6.9.4** in this checkout). See **PRD 33 Section 14** and **`status/wordpress_study_20260404.md`**.

## From `.htaccess` / rewrite study

1. WordPress uses **`# BEGIN WordPress` / `# END WordPress`** markers and **`insert_with_markers()`** so manual rules outside the block survive. Should Lupopedia adopt **marker-based merges** instead of full-file replace from **`InstallWizardHtaccessWriter`**?

2. **`save_mod_rewrite_rules()`** runs when **pretty permalinks** use mod_rewrite. Lupopedia always writes a **full rule set** at install. Is a **lazy** write (first “enable clean URLs” admin action) desirable, or is **immediate** write after install correct for chat routes?

## From IIS / Nginx

3. WordPress implements **`iis7_save_url_rewrite_rules()`** for **`web.config`**. Should Lupopedia ship a **minimal `web.config` template** or only **hosting-doc** guidance?

## From config workflow

4. Should Lupopedia add a **`lupopedia-config.php.example`** (or rename document) mirroring **`wp-config-sample.php`** for hosts that disallow web-based config writes?

## From directory permissions

5. **`wp_mkdir_p()`** inherits parent **`mode & 0007777`** (fallback **0777**) and corrects for **umask**. Installer uses fixed **0755**. On hosts where the web user cannot write **0755** children under a **0750** parent, should we **inherit** or **surface** a explicit chmod recommendation?

## From `.gitkeep` policy

6. The **Softaculous zip** already strips **`.gitkeep`**. Should the **git repository** delete all **`.gitkeep`** files and rely on installer-only dirs, accepting empty-folder churn in dev clones?

## Resolution

- **Answer (LILITH):** `answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md` (**UTC `20260404061932`**)
- **Code/doc backlog:** `status/wordpress_pattern_implementation_tasks_20260404.md`
- **PRD:** Section **14.4** in `lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`

## Next steps (for implementers)

- [ ] Execute phases in **`status/wordpress_pattern_implementation_tasks_20260404.md`**
- [ ] Track rows in **`lupo-docs/versions/`** current **`TODO.md`** per **PRD 33 §12**

This file complies with Lupopedia Constitutional Root Rules.
