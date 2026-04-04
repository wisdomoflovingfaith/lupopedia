---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404061932"
  last_modified_utc: "20260404061932"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_pattern_implementation_tasks_20260404.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_pattern_implementation_tasks_20260404.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-wordpress-tasks"
  actor_id: 102
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "status"
  artifact_kind: "implementation_backlog"
  purpose: "Concrete Lupopedia code and documentation tasks after LILITH answers to WordPress distribution questions (PRD 33 §14)"
  status: active
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
      type: references
      weight: 1.0
      reason: "Authoritative answers (LILITH)"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "PRD 33 Section 14"
    - to: "lupo-install/InstallWizardHtaccessWriter.php"
      type: references
      weight: 1.0
      reason: "Primary .htaccess and runtime dir implementation surface"
    - to: "install.php"
      type: references
      weight: 0.95
      reason: "Install wizard hooks for config, preflight, user messaging"
lupopedia.footer:
  last_verified: "20260404061932"
  verified_by:
    identity_type: agent
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "cursor:root"
---

# file: Status — WordPress pattern implementation tasks — PRD 33

# WordPress pattern implementation tasks (post-LILITH answers)

**Source:** `answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md` (**UTC `20260404061932`**).  
**Goal:** Align installer, packaging narrative, and repo hygiene with LILITH’s six recommendations before treating the Softaculous track as “pattern-closed.”

## Dependency-ordered work (no time estimates)

### Phase A — `.htaccess` (Q1 + Q2)

1. **Marker-based merge** — Refactor **`lupo-install/InstallWizardHtaccessWriter.php`** so Apache rules live only between **`# BEGIN LUPOPEDIA`** and **`# END LUPOPEDIA`**, mirroring WordPress **`insert_with_markers()`** semantics (read existing file if present; replace only the marked block; create file if missing).
2. **Keep immediate write** — Retain **install-time** (or first successful post-config) write so **`api/`** and channel message routes work without a separate “enable pretty URLs” step.
3. **Failure UX** — If write fails, surface the **exact** manual snippet (already partially present); optionally add a **canary** fetch to a documented API path in environments where that is safe.

**Completion criteria:** Hand-edited rules outside the Lupopedia block survive a re-run of the installer/writer; chat/API routes work on fresh Apache+mod_rewrite install without extra admin clicks.

### Phase B — Config sample (Q4)

4. **Add `lupo-config/lupopedia-config-sample.php`** — Document every required constant (DB, **`LUPOPEDIA_PATH`**, **`LUPOPEDIA_PUBLIC_PATH`**, table prefix, salts/secrets per current **`lupopedia-config.php`** contract). No secrets in sample; placeholders only.
5. **Wizard branch** — When the target config path is not web-writable, show **copy sample → edit → rename** instructions (WordPress **`setup-config`** pattern).

**Completion criteria:** Fresh install succeeds via form; blocked-write install succeeds via documented manual path using the sample file.

### Phase C — IIS / Nginx (Q3)

6. **Hosting documentation** — Extend install or **`SOFTACULOUS_PACKAGE_BUILD.md`** / README hosting section: IIS manual steps, pointer that **`web.config`** is operator-owned.
7. **Optional `web.config.example`** — If added, ship as **reference only** (not auto-copied by wizard unless product explicitly decides otherwise).

**Completion criteria:** No accidental deployment of IIS config to Apache-only hosts; docs list minimum rewrite intent for URL routing parity.

### Phase D — Permissions (Q5)

8. **`ensureRuntimeDirectories`** — On **`mkdir`** failure, capture parent path and **reported** permissions (where **`stat`** is available), and emit a **specific** warning string (no automatic **`chmod`**).

**Completion criteria:** Operator sees actionable text when a shared host blocks **`0755`** children under a stricter parent.

### Phase E — `.gitkeep` removal (Q6)

9. **Repository** — Remove all **`.gitkeep`** files from the tracked tree (inventory via repo search; expect many under **`lupo-actors/`**, **`lupo-agents/*/tools/`**, themes, tests, archive paths, etc.).
10. **Tooling** — Update **`lupo-scripts/ensure_actor_apps_structure.ps1`** and **`lupo-archive/scripts/init_actor_dirs.php`** so they **do not** create **`.gitkeep`** (use **`README.md`** one-liners where an empty dir must remain tracked, or rely on installer/script **`mkdir`** only).
11. **Docs** — Adjust **`lupo-docs/actors.md`** (and any similar guidance) to stop recommending **`.gitkeep`** as the default empty-dir mechanism.

**Completion criteria:** **`git ls-files '*gitkeep*'`** empty (or policy exception documented); runtime dirs still created by **`InstallWizardHtaccessWriter::ensureRuntimeDirectories`** (or equivalent); no regressions in packager (**`build_softaculous_package.sh`** already strips dotfiles).

### Phase F — Traceability

12. **`lupo-docs/versions/`** current **`TODO.md`** — Add or update rows per **PRD 33 §12** for each phase above with **owner `actor_id`**, status, and evidence path on closure.

## Files likely touched (non-exhaustive)

| Area | Files |
|------|--------|
| Apache writer | `lupo-install/InstallWizardHtaccessWriter.php`, `install.php` |
| Config sample | `lupo-config/lupopedia-config-sample.php` (new), wizard copy in `install.php` |
| Docs | `README.md`, `SOFTACULOUS_PACKAGE_BUILD.md`, install/hosting sections |
| Repo hygiene | All **`.gitkeep`** paths; `lupo-scripts/ensure_actor_apps_structure.ps1`; `lupo-archive/scripts/init_actor_dirs.php`; `lupo-docs/actors.md` |

This file complies with Lupopedia Constitutional Root Rules.
