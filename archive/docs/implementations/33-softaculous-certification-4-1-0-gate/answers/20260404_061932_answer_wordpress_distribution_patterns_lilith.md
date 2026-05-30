---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404061932"
  file_path_from_root: "docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: answer
  artifact_kind: review_resolution
  thread_id: "33-softaculous-answers"
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
# file: ANSWER — WordPress distribution patterns — LILITH — PRD 33

# ANSWER: WordPress distribution patterns (LILITH)

**Answers UTC:** `20260404061932`  
**Auditor:** LILITH (**actor_id 2**), non-interfering reviewer per **LIL001**.  
**Question artifact:** `questions/20260404_061540_QUESTION_wordpress_distribution_patterns_unresolved.md`

## Audit summary (reported)

```yaml
findings:
  accuracy_score: 100/100
  constitutional_violations: None
  security_concerns: None
  bias_detected: No
  recommendations:
    - "QUESTIONS ARE VALID - All 6 questions need answers before finalizing Softaculous package"
    - "Q1 (marker-based .htaccess) - RECOMMEND YES (preserves manual rules)"
    - "Q2 (lazy vs immediate .htaccess write) - RECOMMEND immediate at install (chat routes required)"
    - "Q3 (IIS/web.config) - RECOMMEND hosting doc guidance (not shipping config)"
    - "Q4 (config sample file) - RECOMMEND YES (mirror WordPress pattern)"
    - "Q5 (permission inheritance) - RECOMMEND detect and warn, not auto-fix"
    - "Q6 (.gitkeep removal) - RECOMMEND YES (installer creates directories)"
  verdict: "Questions are well-formed. Answers needed before PRD 33 implementation proceeds."
```

## Q1: Marker-based `.htaccess` merges?

**Recommendation:** **YES** — use **`# BEGIN LUPOPEDIA` … `# END LUPOPEDIA`** (WordPress-style); **only** replace content between markers; preserve rules outside the block.

**Reasoning:** Hosters and operators add custom rules (security, caching, redirects). Full-file replace would wipe those. Marker blocks allow deterministic updates without destroying unrelated rules.

**Implementation notes:** Implement **`insert_with_markers()`**-equivalent behavior in **`InstallWizardHtaccessWriter`** (or a dedicated helper). **Constitutional fit:** no assumptions about environment beyond preserving existing customizations.

## Q2: Lazy vs immediate `.htaccess` write?

**Recommendation:** **IMMEDIATE** at install (wizard completes or first successful config write path).

**Reasoning:** Chat API routes (e.g. `api/channels/.../messages`) depend on rewrite rules. Without `.htaccess`, APIs **404** and chat fails silently from a user perspective. WordPress can defer pretty permalinks; Lupopedia rewrites are **not** optional for core chat behavior.

**Implementation notes:** Keep install-time write; add optional **canary** check (HTTP to a known API path) when feasible; if write fails, **warn** and show manual snippet. **Constitutional fit:** deterministic post-install behavior.

## Q3: IIS / `web.config` handling?

**Recommendation:** **Documentation only** in the shipped product — **do not** auto-install **`web.config`**. Optional **`web.config.example`** (reference only, not written by wizard unless explicitly product-approved later).

**Reasoning:** IIS share is smaller than Apache on typical shared hosting; wrong **`web.config`** is worse than none; WordPress core behavior is environment-specific.

**Implementation notes:** IIS section in install/hosting docs; wizard may **detect** IIS and show manual instructions. **Constitutional fit:** no Apache-host shipment of IIS-only artifacts as active config.

## Q4: Config sample file?

**Recommendation:** **YES** — add **`config/lupopedia-config-sample.php`** (WordPress **`wp-config-sample.php`** pattern).

**Reasoning:** Hosts may forbid web-writable config; manual copy → edit → rename is battle-tested.

**Implementation notes:** Sample lists required constants (DB, paths, salts); wizard branch when docroot not writable: point at sample + rename instructions. **Constitutional fit:** supports manual install paths without assuming writability.

## Q5: Permission inheritance?

**Recommendation:** **Detect and warn** — **do not** auto-**`chmod`** or silently “fix” permissions.

**Reasoning:** Fixed **0755** can fail under **0750** parents; inheriting parent mode can be too permissive; auto-fix is a security footgun.

**Implementation notes:** **`mkdir(..., 0755, true)`**; on failure, inspect parent perms and emit a **specific** message (path + observed mode + suggested operator action). Document recommended permissions in install guide. **Constitutional fit:** no assumption that the installer may change host policy.

## Q6: `.gitkeep` removal?

**Recommendation:** **YES** — remove **`.gitkeep`** from the **git** tree; rely on **installer-created** runtime directories (**`cache/`**, **`logs/`**, **`uploads/`**, **`tmp/`** — canonical name **`tmp`**, not `temp`).

**Reasoning:** Softaculous zip already strips **`.gitkeep`**; dotfiles confuse FTP users; empty dirs need not be tracked in git.

**Implementation notes:** Bulk delete repo **`.gitkeep`**; stop generating new ones in maint scripts (**`scripts/ensure_actor_apps_structure.ps1`**, **`archive/scripts/init_actor_dirs.php`**, docs under **`docs/actors.md`**); ensure **`ensureRuntimeDirectories`** covers all required writable roots.

## Answers summary

| Q# | Topic | Recommendation | Priority |
|----|--------|----------------|----------|
| 1 | Marker-based `.htaccess` | **YES** — `# BEGIN LUPOPEDIA` / `# END LUPOPEDIA` | High |
| 2 | Lazy vs immediate `.htaccess` | **IMMEDIATE** at install | High |
| 3 | IIS / `web.config` | **Docs + optional example only** | Medium |
| 4 | Config sample file | **YES** — `lupopedia-config-sample.php` | High |
| 5 | Directory permissions | **Detect and warn** (no auto-chmod) | Medium |
| 6 | `.gitkeep` | **YES** — remove; installer owns dirs | High |

## Verdict

All six questions are **answered**. Recommendations are **actionable** for implementers. **Next:** apply **§14.4** in PRD 33 and execute the backlog in **`status/wordpress_pattern_implementation_tasks_20260404.md`**.

```yaml
verdict: "All 6 questions answered. Recommendations provided. Ready to update PRD 33."
next_action: "PRD 33 Section 14.4 + implementation status backlog; mark question artifact resolved."
final_truth: "WordPress patterns adapted to Lupopedia: markers, immediate .htaccess, sample config, no .gitkeep."
```

This file complies with Lupopedia Constitutional Root Rules.
