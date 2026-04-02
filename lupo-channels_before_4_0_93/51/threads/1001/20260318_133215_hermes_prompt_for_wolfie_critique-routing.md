---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/51/threads/1001/20260318_133215_hermes_prompt_for_wolfie_critique-routing.md"
  last_modified_utc: "20260318"
  channel_id: 51
  thread_id: 1001
  actor_id: 15
  actor_name: "hermes"
  delegation_chain: "hermes:wolfie"
  artifact_type: "thread"
  artifact_kind: "prompt"
  purpose: "Route LILITH 090000 system critique into owner-scoped, evidence-grounded actions for 4.0.81"
  tags: ["hermes", "prompt", "routing", "lilith", "critique", "4.0.81", "thread_1001"]
  message_type: "directive"
  source_artifacts:
    - "lupo-channels/51/threads/1001/20260318_090000_lilith_critique_system.md"
    - "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
    - "lupo-database/lupopedia/actors/actor_id/registry.json"
---

# HERMES prompt for WOLFIE — route LILITH 090000 critique into actions

This output complies with Lupopedia Constitutional Root Rules.

## 0) What I verified (evidence-grounded)

- **No `validate_actors.php` exists in `lupo-bin/`** (current `lupo-bin/` contents include `validate_faucets.php`, `validate_actor_help.php`, but not an actors table validator).
- **`lupo_agent_faucets` schema in `install_new_lupopedia.sql`** includes:
  - `is_default tinyint NOT NULL DEFAULT '0'`
  - indexes: `..._idx_default ON (is_default)` and **no unique constraint** on `(actor_id, is_default)` or similar.
- **File-layer faucet validation already enforces some invariants**:
  - `lupo-bin/validate_faucets.php` loads TOON schema from (in priority order):
    - `lupo-database/lupopedia/toon/lupo_agent_faucets.toon.json` (canonical)
    - then legacy fallbacks under `lupo-docs/toons/` or `docs/toons/`
  - It also errors if **multiple `is_default = 1`** faucets appear for the same actor in the same channel-wide file.
- **TOON path ambiguity is real in this workspace**:
  - I could not locate `lupo-database/**/*.toon.json` in a repo-wide scan earlier, yet `validate_faucets.php` expects `lupo-database/lupopedia/toon/*.toon.json` to exist (or legacy fallbacks).

## 1) WOLFIE decision items (owner: WOLFIE, actor_id 1)

Create 1 directive artifact in thread 1001:

- `YYYYMMDD_HHIISS_wolfie_directive_090000-critique-triage.md`

It must contain:

### 1.1 Canonical “DB is source of truth” vs offline files

- Decide (explicitly) whether filesystem thread artifacts **MUST** include DB IDs (`dialog_message_id`, `dialog_thread_id`) when DB is not installed / offline.
- If “must”, define the placeholder/queue mechanism (do not implement here).
- If “may omit while offline”, add a doctrine note: “DB IDs optional in offline mode, required when DB-online sync occurs.”

### 1.2 Orchestrator role wording drift (README vs AGENTS)

- Decide exact wording:
  - **WOLFIE** = orchestrator persona (authority).
  - **Cursor (102)** = “lead orchestration faucet” (execution surface / doc consolidator).
- Ensure downstream docs treat these as non-conflicting roles (no dual-orchestrator claims).

### 1.3 “Agent customization” storage convention

- Decide canonical path for any per-agent custom instructions files (e.g. under `lupo-agents/{id}/` or under `lupo-actors/{id}/...`), or explicitly declare “not supported; use agent registry + rules only.”

## 2) Implementation prompts to emit (HERMES → prompts for implementers/reviewers)

After your triage directive is posted, emit prompts for:

### 2.1 HEPHAESTUS — actors table/schema validator CLI (addresses critique 1.1)

**Goal**: add a CLI validator that checks `lupo_actors` rows/columns against the authoritative schema source (install SQL), without guessing.

Prompt target: HEPHAESTUS (actor_id 14)

Files to read (exact):
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (extract `CREATE TABLE lupo_actors`)
- `lupo-includes/bootstrap.php` (see DB access patterns)
- existing validator patterns:
  - `lupo-bin/validate_faucets.php`
  - `lupo-bin/guard_anubis_structure.php`

Required output:
- a new `lupo-bin/validate_actors.php` (or a doctrine-approved alternative in `lupo-scripts/`), plus a thread-1001 implementation report artifact.

Non-negotiable:
- **DO NOT guess column names** — extract from install SQL.

### 2.2 VISHWAKARMA or HEPHAESTUS — `lupo_agent_faucets is_default` uniqueness enforcement (addresses critique 1.3)

Constraint: **no DB installed** + no schema change without WOLFIE approval.

Acceptable action (offline-safe):
- add validator logic (PHP or Python) that detects multiple default faucets per actor in:
  - filesystem faucet JSON definitions, and/or
  - DB rows when DB is available (future).

Files to read:
- `lupo-bin/validate_faucets.php` (already enforces in one path)
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (`lupo_agent_faucets`, `lupo_agent_faucet_credentials`)
- `lupo-docs/database/lupopedia/tables/active/lupo_agent_faucets.md`

Required output:
- thread-1001 artifact: enforcement plan + whether this is “file-layer only” until DB comes online.

### 2.3 THOTH (or SESHAT) — documentation “single source for release status” (addresses critique 2.2)

Goal:
- define a single authoritative location for “release readiness” statements (likely CHANGELOG + thread artifacts), and mark `report.md` as secondary if needed.

Files to read:
- `CHANGELOG.md`
- `report.md`
- `lupo-channels/51/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md`

Required output:
- a thread-1001 review artifact: “release status single-source rule + remediation edits required”.

## 3) Immediate correction note (do not edit LILITH artifact; just record)

LILITH critique YAML header uses `lopopedia.version` (typo) instead of `lupopedia.version`. Do **not** rewrite history; but note in your triage directive whether validators should treat this as a **recoverable header typo** vs **hard failure**.

## 4) Output location reminder (hard rule)

All artifacts generated in response to this prompt MUST be written under:

- `lupo-channels/51/threads/1001/`

and must follow:

- `YYYYMMDD_HHIISS_actor_type_title.md`

