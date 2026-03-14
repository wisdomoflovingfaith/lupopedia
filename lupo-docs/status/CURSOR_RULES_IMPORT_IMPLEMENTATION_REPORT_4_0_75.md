---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/CURSOR_RULES_IMPORT_IMPLEMENTATION_REPORT_4_0_75.md"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  artifact_type: "report"
  artifact_kind: "implementation"
  purpose: "Report of Cursor canonical rules import and .cursor propagation for 4.0.75"
  tags: ["cursor", "rules", "propagation", "4.0.75", "implementation"]
---

# Cursor Rules Import — Implementation Report (4.0.75)

**Date:** 2026-03-14  
**Actor:** Cursor IDE (actor_id 102)  
**Directive:** Canonical Lupopedia Rules Import for `.cursor` with cross-agent review and documentation updates.

---

## Executive summary

Cursor researched the canonical root rules, Kiro specs, CHANGELOG, and the existing propagation pipeline; hardened the Cursor target output (added `source_path` and `slug` to `.cursor/lupopedia_rules.json`); added `.cursor/README.md` and `lupo-tests/unit/cursor_rules_enforcement.php`; ran propagation successfully (15 rules); and updated CHANGELOG.md, plan.md, and TODO.md. No new competing source of truth was introduced; all Cursor artifacts remain derived from `lupo-rules/root/`.

---

## Files researched

| Item | Purpose |
|------|--------|
| `lupo-rules/root/*.md` | Canonical rule set (15 files; README excluded from propagation). Confirmed each has `lupopedia.rules` with `declares` and rule_id/rule_text/scope/category. |
| `lupo-scripts/propagate_agent_rules.php` | Propagation pipeline. Already supported `--target=cursor`; `build_rules_from_root()` parses frontmatter and `lupopedia.rules`; `write_cursor_outputs()` wrote JSON and `.cursor/rules/<slug>.mdc`. |
| `.kiro/specs/kiro-rules-import/design.md` | Kiro design: extend script with target dispatch, shared rule struct, `.kiro/rules/<slug>.md` with LUPOPEDIA HEADERS, `.kiro/lupopedia_rules.json`, enforcement test, README. |
| `.kiro/specs/kiro-rules-import/requirements.md` | Kiro requirements: root rule review, Kiro-only propagation, JSON structure, rule file validation, enforcement test, `.kiro/README.md`, no modification of root rules. |
| `.kiro/lupopedia_rules.json` | Kiro rule index (15 rules); no `source_path`/`slug` in Kiro JSON; Kiro design expected `.kiro/rules/*.md` but current script only writes Kiro JSON. |
| `.cursor/` | Existing `.cursor/lupopedia_rules.json` (12 rules before run) and `.cursor/rules/*.mdc` (Cursor frontmatter: description, alwaysApply). No `.cursor/README.md` before this work. |
| `CHANGELOG.md` | Antigravity canonical rules system; JetBrains (Codex) hardening; Kiro/Windsurf references. Confirmed 15 root rules and propagation targets. |
| `plan.md`, `TODO.md` | Current backlog and version; updated after implementation. |
| Root rule sample (`database-logic-prohibition-doctrine.md`, `ide-agent-identity-actor-pairing-doctrine.md`) | Verified `lupopedia.rules` block shape and that `extract_yaml_scalar()` matches `rule_id`/`rule_text` inside `declares`. |

---

## Kiro and cross-agent compatibility findings

- **Shared:** Root parsing (`build_rules_from_root`), in-memory rule struct (`id`, `text`, `enforcement`, `scope`, `slug`, `source_path`), and warning behavior (missing `lupopedia.rules` or `rule_id`) are shared. Kiro design and requirements describe the same canonical source and target-isolation behavior.
- **Kiro-specific:** Kiro design specifies `.kiro/rules/<slug>.md` with full LUPOPEDIA HEADERS. The current script’s `write_kiro_outputs()` only writes `.kiro/lupopedia_rules.json`; it does not write individual `.kiro/rules/*.md` files. That is Kiro’s scope; Cursor did not change Kiro output.
- **Cursor-specific:** Cursor outputs `.cursor/rules/<slug>.mdc` with Cursor-native frontmatter (description, alwaysApply) and `.cursor/lupopedia_rules.json`. Cursor JSON now includes `source_path` and `slug` for provenance and for the enforcement test; Kiro and Windsurf JSON were not changed in this pass.
- **Windsurf:** Script already writes `.windsurf/rules/*.md` with LUPOPEDIA HEADERS and `.windsurf/README.md`; no change.
- **JetBrains:** `.idea/lupopedia_rules.xml` already includes `source_path`, `category`, `status`; no change.

---

## Cursor propagation design

- **Canonical source:** `lupo-rules/root/*.md` (read-only). No edits to root rules.
- **Command:** `php lupo-scripts/propagate_agent_rules.php --target=cursor`. Writes only to `.cursor/`.
- **Outputs:**  
  - `.cursor/lupopedia_rules.json`: `rules[]` with `id`, `text`, `enforcement`, `scope`, `source_path`, `slug`.  
  - `.cursor/rules/<slug>.mdc`: Cursor frontmatter + body from root (root headers stripped).  
- **Validation:** `php lupo-tests/unit/cursor_rules_enforcement.php` checks JSON loadable, non-empty rules, required fields, no duplicate IDs, and existence of `.cursor/rules/<slug>.mdc` for each rule.
- **Documentation:** `.cursor/README.md` explains source, command, relationship to other agents, and validation.

---

## Exact files changed

| File | Change |
|------|--------|
| `lupo-scripts/propagate_agent_rules.php` | In `write_cursor_outputs()`, added `source_path` and `slug` to each rule entry in `lupopedia_rules.json`. |
| `.cursor/README.md` | Created. LUPOPEDIA HEADERS + guide (files, propagation command, relationship to Kiro/Windsurf/JetBrains, validation). |
| `lupo-tests/unit/cursor_rules_enforcement.php` | Created. Standalone PHP 5.6 test: load JSON, validate structure and .mdc existence, exit 0/1. |
| `CHANGELOG.md` | Added subsection "Cursor Rules Import and Propagation (4.0.75)" under 4.0.75. |
| `plan.md` | Added "4.0.75 Cursor rules propagation (verified)"; updated next_actions version to 4.0.75; updated body version to 4.0.75. |
| `TODO.md` | Marked Cursor rules propagation task complete; fixed `actor_id` 1003 → 102. |
| `.cursor/lupopedia_rules.json` | Regenerated by propagation (now 15 rules with `source_path` and `slug`). |
| `.cursor/rules/*.mdc` | Regenerated by propagation (15 files). |

---

## Validation steps run

1. `php lupo-scripts/propagate_agent_rules.php --target=cursor` — Output: `Processed 15 root files; parsed 15 rules; warnings: 0; target: cursor`.  
2. `php lupo-tests/unit/cursor_rules_enforcement.php` — Output: `CURSOR RULES ENFORCEMENT: PASS` with 15 rules and .mdc presence confirmed.

---

## Changelog / plan / TODO updates

- **CHANGELOG.md:** New subsection documents research, propagation hardening, artifact regeneration, `.cursor/README.md`, enforcement test, and cross-agent alignment.  
- **plan.md:** New verified section for 4.0.75 Cursor rules propagation; version and next_actions updated to 4.0.75.  
- **TODO.md:** Cursor rules propagation item marked done; actor_id corrected to 102.

---

## Open questions / doctrine risks

- **None critical.** Optional follow-ups: (1) Run `cursor_rules_enforcement.php` in CI or pre-commit if desired. (2) If Kiro implements `.kiro/rules/*.md` generation per its design, ensure slug/source_path in Kiro JSON if their enforcement test needs it. (3) Keep Cursor and Kiro enforcement tests in sync (same checks where applicable) as the rule set evolves.

---

*Cursor IDE (lead orchestration) — 2026-03-14*
