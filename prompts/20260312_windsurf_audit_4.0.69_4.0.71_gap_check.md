---
lupopedia.init:
  document_type: "prompt"
  system_version: "4.0.72"

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "prompts/20260312_windsurf_audit_4.0.69_4.0.71_gap_check.md"
  web_path: "http://www.lupopedia.com/prompts/windsurf/audit_4.0.69_4.0.71_gap_check"
  last_modified_utc: "20260312"
  system_version: "4.0.72"
  channel_id: 42
  actor_id: 1004
  artifact_type: "implementation_prompt"
  artifact_kind: "audit_gap_check"
  purpose: "Ask Windsurf to audit versions 4.0.69–4.0.71 and surface any remaining work that must be added to the 4.0.72 task list."
  tags: ["prompts", "4.0.72", "windsurf", "audit", "gap_check"]

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/status/WINDSURF_FULL_AUDIT_4.0.70_4.0.71_CORRECTIONS.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/status/WINDSURF_AUDIT_CURSOR_4.0.57.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/INIT_README.md", type: "references", weight: 0.7 }
    - { to: "prompts/20260312_ide_agent_4.0.72_required_reading.md", type: "references", weight: 0.7 }

lupopedia.footer:
  version: "4.0.72"
  last_verified: "20260312"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Have Windsurf run this audit and append any missing tasks to the 4.0.72 section of CHANGELOG.md"
    - "Re-run this prompt after major 4.0.72 fixes to confirm no new gaps were introduced"
---
# file: Windsurf Audit Prompt — Gap Check 4.0.69–4.0.71 → Tasks for 4.0.72 — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/prompts/windsurf/audit_4.0.69_4.0.71_gap_check

# Windsurf Audit Prompt — 4.0.69–4.0.71 Gap Check for 4.0.72 Tasks

**Audience:** Windsurf IDE agent reviewing Lupopedia versions **4.0.69–4.0.71** while the codebase is at **4.0.72**.  
**Goal:** Confirm that all promised, implied, or partially completed work from 4.0.69–4.0.71 has either been fully implemented or is explicitly tracked under the **4.0.72 “Still needing to be done”** task list in `CHANGELOG.md`.

---

## 1. Required context before auditing

Read these files first (if you have not already) so you have the same doctrine and version context as other agents:

1. `CHANGELOG.md`
   - Read the sections for **[4.0.69]**, **[4.0.70]**, **[4.0.71]**, and **[4.0.72]**, including all “Pending tasks” and “Still needing to be done” subsections.
2. `lupo-docs/status/WINDSURF_FULL_AUDIT_4.0.70_4.0.71_CORRECTIONS.md`
   - Understand what you (Windsurf) previously audited, what was remediated, and what was left as medium/low follow-up.
3. `lupo-docs/status/WINDSURF_AUDIT_CURSOR_4.0.57.md`
   - For historical style and expectations only; do **not** try to re-open old doctrine.
4. `prompts/20260312_ide_agent_4.0.72_required_reading.md`
   - Ensure you have the same “required reading” baseline as Cursor and other IDE agents.

---

## 2. What to audit (4.0.69–4.0.71)

For each of the releases below, compare what the changelog and audit docs **promise** vs what is clearly **implemented** or tracked as pending.

Focus on:

- **Schema & TOONs:** Tables mentioned in changelog / docs vs actual TOON files and `install_new_lupopedia.sql`.
- **Session Model A:** All remaining references to `$_SESSION['actor_id']` or legacy session behavior outside the explicitly-marked legacy/historical docs.
- **Semantic navbar backend:** Tables, TOONs, migrations, API endpoints, JS integration, and any remaining TODO markers.
- **LUPOPEDIA HEADERS migration:** Files that still use FLARE/FLIP/FLP as active (not historical) or have malformed/missing headers.
- **PHP 5.6+ compatibility:** New or edited code that may still assume PHP 5.3 behavior.

### 2.1 Version 4.0.69

- Identify promises or “coming soon” work in 4.0.69.
- Cross-check that any multi-version plans from 4.0.69 that should land by 4.0.71 either:
  - Landed in code/docs, or
  - Are clearly captured as pending tasks under 4.0.71 or 4.0.72.

### 2.2 Version 4.0.70

- Look for **pending tasks** that were meant to be resolved by 4.0.71.
- Confirm they are either completed or explicitly listed in 4.0.71/4.0.72 pending task sections.

### 2.3 Version 4.0.71

- Re-verify:
  - Synthesized Documentation Framework (schema, scripts, rules, agent registrations).
  - Semantic navbar backend (tables, migrations, TOONs, API endpoints, integration file).
  - Session Model A (DB-backed sessions).
  - FLARE → LUPOPEDIA HEADERS + PHP 5.6 migration.
- Use `WINDSURF_FULL_AUDIT_4.0.70_4.0.71_CORRECTIONS.md` as the ground truth for what **should** be done.
- Note any “remaining” or “future work” items that are not yet reflected in the 4.0.72 “Still needing to be done” list.

---

## 3. How to record newly-discovered gaps

If you find **missing work** (promised but not implemented) or **untracked follow-ups**:

1. **Stay within doctrine:**
   - Do **not** change schema, TOONs, or runtime behavior in this step.
   - Only update **documentation and tasks**.
2. **Append tasks under 4.0.72 in `CHANGELOG.md`:**
   - Go to `### [4.0.72] — Version bump (2026-03-12)`.
   - Under `#### Still needing to be done (Channel 42 / Channel 0)`, add new bullets to the appropriate subsection:
     - “From CHANGELOG pending tasks (4.0.71)”
     - “From pending-tasks fallback (Channel 42 …)”
     - “Windsurf audit — remaining (medium/low)”
     - Or add a **new clearly-labelled subsection** if needed (e.g. “Additional Windsurf audit gaps (4.0.69–4.0.71)”).
   - Each bullet should:
     - Be **specific and actionable** (what file/area, what needs doing).
     - Avoid promising a specific future version beyond 4.0.72.
     - Reference any relevant doctrine or status doc.
3. **Do not duplicate existing tasks:**
   - If a task is already present, refine it rather than adding a second copy.

---

## 4. Output / confirmation (what Windsurf should say)

When you finish the audit, respond with something like:

- A short summary of:
  - Files you read.
  - Number of new tasks you added under 4.0.72.
  - Any **high-risk** gaps that still require human review.
- An explicit confirmation line, for example:

```text
Windsurf: 4.0.69–4.0.71 audit complete. All remaining gaps have been captured under the 4.0.72 “Still needing to be done” section in CHANGELOG.md.
```

If you find **no additional gaps**, say so explicitly and **do not** add duplicate tasks.

