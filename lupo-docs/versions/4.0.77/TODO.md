---
lupopedia.init:
  required_reading:
    - path: "CHANGELOG.md"
      reason: "See 4.0.77 section for high-level description of work"
    - path: "lupo-docs/versions/4.0.77/PLAN.md"
      reason: "Dependency-ordered plan for remaining 4.0.77 tasks"

lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "documentation"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/versions/4.0.77/TODO.md"
  web_path: "[web_path](http://www.lupopedia.com/versions/4.0.77/TODO)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "version_todo"
  purpose: "Concrete task list for completing 4.0.77 work (headers, tooling, Bayesian foundation integration, upgrade validation)"
  tags: ["todo", "4.0.77", "headers", "bayesian_decisions", "tooling"]

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Use this TODO list to drive concrete 4.0.77 implementation work"
---
# file: Version 4.0.77 TODO — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/versions/4.0.77/TODO

# Version 4.0.77 — TODO List

## Status

- **State:** Mostly complete; multi-agent integration done  
- **Theme:** LUPOPEDIA HEADERS enforcement, header tooling, Bayesian Decision foundation, upgrade validation, Zencoder integration  
- **Lead Agent:** Cursor (102), with Windsurf (101), Antigravity (103), and Zencoder (106) as collaborating faucets

---

## A. LUPOPEDIA HEADERS — doctrine and enforcement

1. **Review and unify header doctrine**
   - [ ] Re-read LUPOPEDIA HEADERS docs (`README.md`, `LUPOPEDIA_HEADERS_PLAN.md`, `LUPOPEDIA_HEADERS_FORMAT.md`, `OPTIONAL_BLOCKS.md`, `LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE.md`) and confirm all 4.0.77 changes (routing block, grouped edges, engagement snapshots) are reflected consistently.

2. **`lupopedia.init` correctness sweep**
   - [ ] Scan Markdown files modified in 4.0.74–4.0.77 for misuse of `lupopedia.init` (metadata instead of required_reading/required_context).
   - [ ] For each offender, schedule a small patch to move metadata into `lupopedia.headers` or `lupopedia.metadata` and leave `lupopedia.init` as required_reading/required_context only.

3. **Mandatory snapshot comments for edges/engagement**
   - [ ] Run a header audit (tool or scripted grep) to ensure all `lupopedia.edges` and `lupopedia.engagement` blocks include a `comment` that marks them as snapshots, and preferably a `meta` field.
   - [ ] Add or fix missing `comment` / `meta` fields in affected files.

---

## B. Header generation and sync tooling

4. **Export headers (file → YAML)**
   - [x] Implemented: `php lupo-bin/lupo.php headers export <path> [--output=path] [--json]` and `lupo-scripts/export_lupopedia_headers.php`. Extracts front-matter YAML from a Markdown file. Export from **DB** (`lupo_metadata`) remains **deferred**.

5. **Import headers (YAML → file)**
   - [x] Implemented: `php lupo-bin/lupo.php headers import <target.md> [source.yaml]` and `lupo-scripts/import_lupopedia_headers.php`. Replaces header block in target file; body preserved; replacement validated. Import into **DB** (`lupo_metadata`) remains **deferred**.

6. **Validator and round-trip**
   - [x] Validator: `php lupo-bin/lupo.php headers validate <path>` and `lupo-scripts/validate_lupopedia_headers.php`. Fixtures: `lupo-tests/fixtures/headers/`.
   - [x] Round-trip steps documented in `lupo-tests/fixtures/headers/README.md`. Export → import → validate verifiable; content-equivalence intended.

---

## C. Bayesian Decision Tracking foundation

7. **Final schema/TOON consistency check**
   - [x] Done. Decision tables include required `channel_id` and `project_id` (NOT NULL); install SQL and TOONs aligned; scoped indexes added. See SCHEMA_CANONICAL_SOURCES.md.

8. **Doctrine and planning docs**
   - [x] Done. BAYESIAN_DECISION_DOCTRINE has Scope Boundaries (§2); TASKS/PLAN status set to foundation shipped; migration language reframed for 4.0.x install-only path.

9. **Future engine scaffolding stub (optional for 4.0.77)**
   - [x] Land a minimal `BayesianDecisionService` scaffold in 4.0.77 that can record and read decisions/edges/influences, while deferring full traversal and integration logic to a later patch.

---

## D. Crafty Syntax 3.7.5 → Lupopedia 4.0.77 validation

10. **Run upgrade flow**
    - [x] Executed: drop → load Crafty 3.7.5 baseline → run install.php → regenerate TOONs; 161 tables. Status artifact `CRAFTY_3_7_5_TO_4_0_77_UPGRADE_VALIDATION.md` updated with evidenced completion; coordination `upgrade-validation.status` set to validated.

11. **Document regression coverage**
    - [ ] Ensure any tests/scripts that assume 4.0.74–4.0.76 semantics (paths, table counts, future_features content) are updated for 4.0.77 where needed.

---

## E. Cross-agent coordination

12. **Plan and report alignment**
    - [x] Zencoder work committed and pushed by Cursor; cross-agent report and PLAN/TODO aligned with integrated state.
    - [x] Coordination artifacts: `lupo-database/coordination/4.0.77/` with README (protocol), header-validator.status, upgrade-validation.status, bayesian-foundation-alignment.status, truth-alignment.status.

---

## F. Table documentation initiative (4.0.77 stop)

13. **Priority table docs and stop line**
    - [x] Cursor continued Zencoder/Windsurf table-doc workstream: improved **lupo_sessions.md** and **lupo_contents.md** (4.0.77 headers, "Where This Table Is Used," schema-aligned content).
    - [x] Created **TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md** — what 4.0.77 accomplished vs what moves to 4.0.78.
    - [x] Updated CHANGELOG, PLAN, TODO, and relevant status docs to reflect completion and handoff.
    - **Deferred to 4.0.78:** Remaining priority tables (lupo_channels, lupo_actors refresh; Priority 2/3), mass header version cleanup across 80+ table docs, optional markdown-from-TOON automation. See stop-line doc for pattern and next-step guidance.

---

*This TODO list should be updated as 4.0.77 work progresses. When significant items are completed, update CHANGELOG.md and lupo-docs/version.md summaries accordingly.* 

