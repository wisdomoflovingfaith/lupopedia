---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/CURSOR_README_CROSS_AGENT_UPDATE_4_0_75.md"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  artifact_type: "report"
  artifact_kind: "implementation"
  purpose: "Report of cross-agent review and root README.md update for v4.0.75"
  tags: ["cursor", "readme", "root_rules", "actor_registration", "4.0.75"]
---

# Cursor README Cross-Agent Update — Implementation Report (4.0.75)

**Date:** 2026-03-14  
**Actor:** Cursor IDE (actor_id 102)  
**Directive:** Cross-agent review + root README.md update for v4.0.75 (canonical root rules, new agent registration, doctrine alignment).

---

## 1. Executive summary

Cursor reviewed recent IDE agent work (CHANGELOG 4.0.75, Antigravity TOON/htaccess report, Kiro rules-import specs, Cursor/Kiro/Windsurf/JetBrains propagation state, AGENTS.md, current README, plan, TODO) and updated the root **README.md** for **version 4.0.75**. The README now clearly states that **`lupo-rules/root/`** is the canonical source of doctrine, that **all agents must follow root rules**, that **agent-specific rule files are derived** from the root, and that **new IDE or web terminal agents must create and register an actor** before participating. A short **doctrine reminder** (install SQL authoritative, TOON derived at `lupo-database/lupopedia/toon/`, no FKs/procedures, BIGINT UTC, etc.) was added under Architecture. No generic rewrite was performed; existing structure and useful content were preserved and aligned with current repo state.

---

## 2. Files researched

| Item | Purpose |
|------|--------|
| **README.md** | Current root README: version 4.0.74 in headers/body, no dedicated root-rules or new-agent sections, Architecture had install SQL/TOON but no TOON path unification or doctrine reminder. |
| **CHANGELOG.md** (4.0.75) | Canonical rules system, Cursor/Kiro/JetBrains/Antigravity propagation, Antigravity TOON path + .htaccess hardening, 15 root rules, propagate_agent_rules.php targets. |
| **lupo-docs/status/ANTIGRAVITY_TOON_PATH_AND_HTACCESS_REPORT_4_0_75.md** | TOON output unified to `lupo-database/lupopedia/toon`; `lupo-docs/toons/` removed; install DDL authoritative; `lupo-database/.htaccess` hardening (Require all denied). |
| **lupo-rules/root/README.md** | Index of root rule files, propagation command, “all IDE agents and code-writing agents follow these.” |
| **AGENTS.md** | Actor registry path (`lupo-database/lupopedia/actors/actor_id/registry.json`), lead orchestration (Cursor 102), seven IDE faucets, identity/registry docs. |
| **.cursor/README.md** | Canonical source = `lupo-rules/root/`; derived outputs; propagation command. |
| **lupo-database/lupopedia/actors/actor_id/registry.json** | Actor list (ids, slugs, types); Cursor 102 lead_orchestration; no formal “registration form”—registration implied by registry + seed/DB. |
| **plan.md, TODO.md** | Current backlog; referenced for consistency. |

---

## 3. Cross-agent findings incorporated into README

- **Antigravity (4.0.75):** Install SQL authoritative; TOON output path is `lupo-database/lupopedia/toon/` (not `lupo-docs/toons/`); `lupo-database/` protected from web access. Reflected in Architecture (TOON path, .htaccess note) and doctrine reminder.
- **Canonical rules system (Antigravity, Cursor, Kiro, JetBrains):** Root rules in `lupo-rules/root/`; propagation script writes to `.cursor/`, `.kiro/`, `.windsurf/`, `.idea/`; derived outputs only. README now states root is canonical and points to root rules; propagation is referenced in the new “Canonical root rules” section.
- **Kiro/Cursor/Windsurf:** Shared pattern (root = source of truth; agent-specific outputs derived). README describes this pattern without duplicating each agent’s docs.
- **AGENTS.md / registry:** Actor identity and registry path are the documented basis for “register appropriately”; README points to AGENTS.md and AGENT_IDENTITY_REGISTRY for details and does not invent a process not supported by the repo.

---

## 4. README sections updated

| Section | Change |
|--------|--------|
| **Headers / footer / next_actions** | Version 4.0.74 → 4.0.75; purpose and next_actions updated to mention root rules and registration. |
| **Title and badge** | v4.0.74 → v4.0.75; badge link to version.md. |
| **Lead paragraph** | Replaced with 4.0.75 release summary: root rules, IDE propagation, TOON path, security hardening; added “Canonical root rules” and “Architecture (onboarding)” bullets stating root rules and new-agent registration requirement. |
| **Table of contents** | Added “Canonical root rules” and “New agent / web terminal agent onboarding.” |
| **Canonical root rules** | **New section.** What root rules are, governance (all agents must follow), derived outputs (propagate_agent_rules.php), where to read (lupo-rules/root/README.md and *.md). |
| **New agent / web terminal agent onboarding** | **New section.** Must establish actor identity; register (registry + AGENTS.md / AGENT_IDENTITY_REGISTRY); adopt root rules first; no anonymous/unregistered participation; “Do not just start coding.” |
| **Architecture Overview — Database domains** | Install SQL authoritative; TOON derived at `lupo-database/lupopedia/toon/` (canonical path, no lupo-docs/toons); table count 4.0.75; note on lupo-database/ web protection. |
| **Doctrine reminder** | **New subsection** under Architecture: non-standard architecture; no FKs/triggers/procedures; explicit columns; BIGINT UTC timestamps in PHP; install SQL authoritative, TOON derived; shared-hosting/fallback-first; pointer to lupo-rules/root/. |
| **lupopedia.edges** | Added outbound edge to `lupo-rules/root/README.md`; semantic_tags included “root_rules.” |
| **Final footer** | Version 4.0.75; tagline mentions canonical doctrine: lupo-rules/root/. |

---

## 5. Exact files changed

| File | Change |
|------|--------|
| **README.md** | All updates above (version, new sections, Architecture/doctrine, edges, footer). |
| **lupo-docs/status/CURSOR_README_CROSS_AGENT_UPDATE_4_0_75.md** | Created (this report). |

---

## 6. Changelog / plan / TODO updates

None. The README update is documentation alignment and does not close a specific CHANGELOG/plan/TODO item; the directive allowed optional updates only when justified. If desired, a brief CHANGELOG line under 4.0.75 could be added later (e.g. “README: canonical root rules, new-agent onboarding, doctrine reminder for 4.0.75”).

---

## 7. Open questions or doctrine risks

- **Registration process:** The README states that new agents must “register appropriately” and points to the registry and AGENTS.md. The repo does not yet have a single “registration checklist” document (e.g. “add entry to registry.json, run seed X, update Y”). The README documents the **expectation** (actor identity + registration + root rules) from existing evidence; a formal step-by-step checklist could be added in AGENTS.md or a dedicated doc later.
- **No contradictions:** README does not contradict Antigravity’s TOON path or .htaccess report, CHANGELOG 4.0.75, or root rules doctrine.

---

*Cursor IDE (lead orchestration) — 2026-03-14*
