---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/cursor/prompts/20260314_cursor_execute_plan_4_0_74.md
  web_path: https://www.lupopedia.com/lupopedia/actors/cursor/prompts/20260314_cursor_execute_plan_4_0_74.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: implementation-directive
  artifact_kind: execution
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: directive
  prd_cluster: null
  title: null
  summary: null
---
# Cursor Implementation Directive — Execute plan.md and next actions (v4.0.74)

Cursor, this is an **implementation pass**, not a planning pass.

Your job is to **execute the consolidated root plan** in `plan.md`, apply the required corrections in the repository, and then update the canonical artifacts to reflect what was actually done.

You are the **lead orchestrator** for this pass.

## Mission

Implement the current approved P0 items and the directly related P1 follow-through items from `plan.md`, with emphasis on:

1. **Path correctness**
2. **Actor ID correctness**
3. **Doctrine alignment**
4. **Canonical header usage**
5. **Schema/documentation consistency**
6. **Clean changelog/update reporting**

Do not produce another speculative plan.  
Do the implementation work in the repository and then document exactly what changed.

---

## Non-negotiable rules

### 1) Verify, do not guess
Before changing any file:

- verify actor IDs from `database/lupopedia/actors/actor_id/registry.json`
- verify schema truth from `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- verify current repo paths from the actual repository structure
- verify whether a doctrine file already exists before claiming it is missing

If something is uncertain, state that uncertainty in the implementation report instead of inventing certainty.

### 2) Canonical write format
Where files are updated to modern format:

- legacy `flare.*` may be read for compatibility
- canonical write format is `lupopedia.*`

Do not do reckless mass rewrites.  
Only convert where this implementation pass touches or standardizes a file.

### 3) `lupopedia.init` discipline
`lupopedia.init` is for:

- `required_reading`
- `required_context`

It is **not** for:

- artifact_type
- file_identity
- namespace
- domain
- system_version

That metadata belongs in `lupopedia.headers` or `lupopedia.metadata`.

### 4) `lupopedia.next_actions`
Use `lupopedia.next_actions` as the canonical optional follow-up block in updated files where appropriate.

Backward compatibility with `lupopedia.close` may remain documented/validator-supported, but new or revised canonical files should prefer `lupopedia.next_actions`.

### 5) Do not duplicate changelog content
`CHANGELOG.md` must have:

- one clean `4.0.74` section
- no duplicated bullets
- no misplaced out-of-order notes above the actual version section
- no contradictory claims

### 6) Do not overclaim TOON authority
Install SQL is the schema authority.  
If TOON paths, filenames, or output locations vary in current tooling, document the truth you verified. Do not invent a single canonical TOON location unless you also align the tooling/docs to make that statement true.

---

# Required implementation scope

## Phase A — Execute P0 immediately

### A1. Canonicalize actor IDs and identity references
Review the files touched by this work and correct any actor drift based on registry truth.

Minimum required checks:

- `plan.md`
- `report.md`
- `KIRO_CHANGES_and_report.md`
- `CHANGELOG.md`
- any KIRO-authored files from the late submission set that still use `actor_id 10000`

Required outcome:

- **KIRO actor_id must be 100**
- **Cursor actor_id must be 102**
- no touched canonical file should retain known incorrect actor IDs

If you find KIRO-authored files still using `10000`, correct them where safe and include them in the implementation report.

---

### A2. Fix path drift: `docs/` → `docs/`
Search for content-level path drift in the files relevant to root documentation and doctrine.

Required focus:

- `README.md`
- `AGENTS.md`
- `plan.md`
- `report.md`
- `CHANGELOG.md`
- linked doctrine/status files touched by this pass

Required outcome:

- content references should use **`docs/`** where that is the real canonical path
- do not claim a top-level `docs/` root exists unless it actually exists
- if some historical references remain outside current implementation scope, document them in the report as deferred cleanup

---

### A3. Keep canonical root docs aligned
Review and update, as needed, the root artifacts so they are consistent with doctrine and actual repository structure:

- `README.md`
- `CHANGELOG.md`
- `plan.md`
- `report.md`
- `AGENTS.md`

Required outcome:

- no contradiction between these files on actor/faucet/orchestrator identity
- no contradiction on canonical doc root
- no contradiction on schema authority
- no contradiction on the meaning of `lupopedia.init` and `lupopedia.next_actions`

---

### A4. Confirm and reflect `lupo_projects`
Verify the `lupo_projects` table addition in install SQL and keep supporting documentation aligned.

Required files to verify/update as needed:

- `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `database/lupopedia/mysql/seed/seed_projects.sql`
- `docs/database/lupopedia/SCHEMA_REGISTRY.md`
- `docs/database/lupopedia/tables/active/lupo_projects.md`
- `CHANGELOG.md`

Required outcome:

- table definition documented accurately
- seed file documented accurately
- if installer seed execution path does not yet include `seed_projects.sql`, say so honestly in the report/changelog rather than implying full automation exists

---

### A5. Enforce table ceiling wording
Ensure touched canonical docs reflect the Captain directive correctly:

- table ceiling / table count limit is **advisory only**
- justified schema expansion is allowed

Minimum verification target:

- `docs/channels/doctrine/SYMBOL_OPERATOR_DOCTRINE.md`
- any touched root docs that summarize this rule

---

# Phase B — Execute direct P1 follow-through items

Only do the P1 items that directly support the implementation already underway.

## B1. `lupopedia.init` alignment in touched files
For files you touch in this pass, ensure `lupopedia.init` is used correctly.

If a touched file is using `lupopedia.init` for file metadata, migrate that metadata into:

- `lupopedia.headers`
- `lupopedia.metadata`

Do not do an uncontrolled whole-repo rewrite.  
Fix touched canonical files and document broader repo cleanup as follow-up if needed.

---

## B2. `lupopedia.next_actions` adoption
For touched canonical files that need an explicit follow-up block, prefer:

```yaml
lupopedia.next_actions:
  next_actions:
    - "..."
```

Ensure doctrine docs that you touch remain consistent with: canonical block = `lupopedia.next_actions`; legacy support = `lupopedia.close`; deprecation target = 4.1.0.

---

## B3. Edge maintenance doctrine

If not already clearly documented, add or refine doctrine language stating that lupopedia.edges should be updated when semantic relationships change significantly.

If you touch the relevant doctrine files, ensure they say this plainly and without ambiguity.

You may mention potential tooling such as bin/update-edges.php only if that reference is already grounded or clearly identified as proposed tooling.

---

## B4. Merge/ownership clarity

Do not rewrite the entire ownership model, but do leave the canonical docs in a state where the following are clear:

- Cursor is lead orchestrator for root consolidated implementation
- faucet-specific plans remain authoritative for their domains
- Kiro/Windsurf/Codex/Antigravity submissions are inputs, not silent replacements of root canon
- merge process language should not contradict plan.md

---

# Required deliverables

1. **Repository implementation** — Actually update the needed files.
2. **CHANGELOG update** — Update CHANGELOG.md so that the 4.0.74 section is clean, consolidated, and non-duplicative. It must include what was truly implemented in this pass. Do not leave stray out-of-order bullets above the version section.
3. **Implementation report** — Create or update: `docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md`. The report must include: what you changed; why you changed it; exact files changed; validation performed; any deferred items; any uncertainty or incompleteness.
4. **Validation honesty** — Include a short validation section stating what you verified directly (e.g. actor IDs from registry; canonical doc root; install SQL authority; lupo_projects presence; seed file presence; whether seed execution is or is not wired into installer flow; any doctrine files confirmed to exist).

---

# Suggested execution order

1. Read plan.md, report.md, AGENTS.md, doctrine prerequisites, and registry/install authority files
2. Fix actor ID drift in touched root/canonical files
3. Fix docs/ → docs/ path drift in touched root/canonical files
4. Verify and align lupo_projects documentation and changelog wording
5. Align touched files to correct lupopedia.init / lupopedia.next_actions usage
6. Update CHANGELOG.md
7. Update/create CURSOR_IMPLEMENTATION_REPORT_4_0_74.md
8. Re-review touched files for contradictions before finalizing

---

# Acceptance criteria

Implementation is complete when all of the following are true:

- touched canonical files use verified actor IDs
- touched canonical files do not incorrectly refer to docs/ as the canonical doc root
- CHANGELOG.md has one coherent 4.0.74 entry without duplicate bullets or misplaced top records
- lupopedia.init usage is corrected in touched canonical files
- lupopedia.next_actions usage is preferred in touched canonical files where applicable
- lupo_projects is documented accurately and without overclaiming installer automation
- the implementation report lists exact changed files and honest validation results

---

# Output requirements for Cursor

When done, provide:

- **Execution summary**
- **Exact files changed**
- **Validation performed**
- **Deferred follow-ups**
- **Recommended next actions for Wolfie/Captain review**

Do not respond with only intentions.  
Respond with implementation results grounded in the actual repository state.
