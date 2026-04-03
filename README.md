---
lupopedia.headers:
  header_format_version: 2
  when_updated: '20260403202903'
  lupopedia.schema: documentation
  file_path_from_root: README.md
  web_path: http://www.lupopedia.com/lupopedia/README.md
  last_modified_utc: '20260403202903'
  channel_id: 42
  thread_id: readme-4-0-94
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: project_documentation
  artifact_kind: readme
  purpose: Root overview for Lupopedia system architecture, version-driven execution, and iterative release planning.
  tags:
    - readme
    - 4.0.94
    - architecture
    - doctrine
    - workflow
lupopedia.init:
  required_reading:
    - path: lupo-docs/prd/00_root_constitutional_system_requirements.md
      reason: "MANDATORY FIRST READ — constitutional law for all agents and contributors. Overrides everything else."
    - path: lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md
      reason: "4.1.0 / auto-installer gate; clarifies no Lupopedia→Lupopedia upgrades during 4.0.x"
    - path: lupo-docs/prd/27_installer_requirements.md
      reason: "Installer and 4.0.x fresh-install model (install SQL + mysql/seed/ + optional install/ merged seed + Crafty import)"
    - path: AGENTS.md
      reason: "Canonical actor, identity-layer, and coordination rules"
    - path: ONBOARDING.md
      reason: "Operational quick-start"
    - path: lupo-rules/root/WOLFIE_DOCTRINE.md
      reason: "Engineering philosophy — read before touching any existing code"
    - path: lupo-rules/root/README.md
      reason: "Complete root rules and development constraints"
    - path: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
      reason: "Header/footer validation doctrine"
    - path: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      reason: "Canonical five-layer identity model"
    - path: lupo-docs/versions/4.0.94/README.md
      reason: "Current working version overview (4.0.93 is frozen snapshot only)"
    - path: lupo-docs/versions/4.0.94/PLAN.md
      reason: "Current detailed iteration plan"
    - path: lupo-docs/versions/4.0.94/decisions/
      reason: "Architecture decisions and implementation reasoning for current version (folder with threaded decision files)"
    - path: lupo-channels/channel_index.md
      reason: Canonical channel map and path policy
    - path: ORGANIZATION.md
      reason: Canonical root folder map and repository write guidance
    - path: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
      reason: Mandatory real UTC for headers — run tick.py; never guess timestamps
lupopedia.edges:
  comment: Snapshot of root documentation references for version-driven execution and release continuity.
  outbound_edges:
    - to: lupo-docs/prd/00_root_constitutional_system_requirements.md
      type: references
      weight: 1.0
      reason: Constitutional anchor — mandatory first read for all agents
    - to: AGENTS.md
      type: aligns_with
      weight: 1.0
    - to: lupo-rules/root/WOLFIE_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Engineering philosophy binding on all agents
    - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      type: aligns_with
      weight: 1.0
    - to: lupo-rules/root/README.md
      type: references
      weight: 1.0
      reason: Complete root rules and development constraints
    - to: ONBOARDING.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.0.94/README.md
      type: references
      weight: 1.0
      reason: Current working version overview (4.0.93 frozen snapshot only)
    - to: lupo-docs/versions/4.0.94/PLAN.md
      type: references
      weight: 1.0
      reason: Current detailed iteration plan
    - to: lupo-docs/versions/4.0.94/decisions/
      type: references
      weight: 1.0
      reason: Architecture decisions and implementation reasoning for current version
    - to: lupo-docs/versions/4.0.94/TODO.md
      type: references
      weight: 1.0
      reason: Current task tracking and execution plan
    - to: lupo-docs/versions/4.1.0/plan.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.1.0/prd/README.md
      type: references
      weight: 1.0
    - to: ORGANIZATION.md
      type: references
      weight: 0.95
    - to: lupo-docs/prd/02_channels_discussions.md
      type: references
      weight: 0.95
      reason: Channel threads, THREAD_MANIFEST.md, decisions/questions/answers/comments layout
    - to: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Temporal anchor and tick.py workflow for all header timestamps
    - to: lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md
      type: references
      weight: 1.0
      reason: 4.1.0 release gate; no Lupopedia→Lupopedia migrations during 4.0.x
    - to: lupo-docs/prd/27_installer_requirements.md
      type: references
      weight: 1.0
      reason: Installer requirements; install SQL + seed pipeline (mysql/seed/, install/seed_lupopedia_4_1_0.sql) + Crafty import for 4.0.x
    - to: lupo-docs/doctrine/VERSIONING_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Canonical versioning and upgrade-path doctrine
lupopedia.footer:
  last_verified: '20260403202903'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: cursor:root
  next_action:
    - Keep constitutional PRD reference prominent and mandatory
 
---
# file: Lupopedia README - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/README.md](http://www.lupopedia.com/lupopedia/README.md)

# Lupopedia Semantic OS

---

## MANDATORY READING — Start Here

**Before writing a single line of code, before asking a question, before suggesting a change:**

Read `lupo-docs/prd/00_root_constitutional_system_requirements.md`.

This is not optional. It is constitutional law for this project. It overrides every other document, every "best practice," every framework recommendation, and every suggestion from any agent or IDE tool. If you have not read it, you are not ready to work on this codebase.

The PRDs in `lupo-docs/prd/` are the highest form of truth on what needs to be built and how things work. They are not suggestions. They are not guidelines. They are requirements.

After the constitutional PRD, read `lupo-rules/root/WOLFIE_DOCTRINE.md`. It explains the engineering philosophy behind this project. If you encounter code written in 1999 that still works, that is not an accident and it is not a problem to fix.

---

## Decisions, Q&A, and Implementation Reasoning

Every significant architectural decision, design question, and implementation choice is recorded in `decisions/` folders. These are not summaries — they are full threaded dialogs: questions asked, options considered, reasoning, and final answer.

Decisions folders can exist anywhere, scoped by context:

- `lupo-docs/versions/<version>/decisions/` — version-scoped decisions
- `lupo-docs/implementations/{id}_{slug}/decisions/` — PRD-scoped decisions
- `lupo-agents/{agent_key}/decisions/` — agent-scoped decisions
- `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/decisions/` — discussion-scoped decisions (active layout; see `lupo-channels/channel_index.md`, `lupo-docs/prd/29_project_structure.md`)
- `lupo-channels/{channel_id}/threads/{thread_id}/` — legacy/API-mirrored thread dirs (numeric `channel_id` / `dialog_thread_id`; see `lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`)

### Thread manifest

Every thread directory under the active channel layout (`lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`) **must** contain `THREAD_MANIFEST.md` with YAML frontmatter:

```yaml
thread_key: "mood_rgb_system"
channel_key: "semantic"
federation_node_id: 0
purpose: "Explain Mood RGB system"
start_date: "2026-04-04"
last_activity: "2026-04-04"
status: "active"  # active, concluded, formalized, archived
resolution: ""  # path to resolution document if concluded/formalized
archived_date: ""  # populated on archival
archived_by: ""   # script name or actor
```

Typed subfolders (`decisions/`, `questions/`, `answers/`, `comments/`), filename patterns, and thread lifecycle (formalization, archival) are specified in [lupo-docs/prd/02_channels_discussions.md](lupo-docs/prd/02_channels_discussions.md) and [lupo-docs/prd/21_thread_graduation_doctrine.md](lupo-docs/prd/21_thread_graduation_doctrine.md).

Before implementing anything non-trivial, check the relevant decisions/ folder. The answer to "why does it work this way" is almost always there. If you are about to make a decision that contradicts an existing decision record, that is a flag — read the reasoning first.

 

---

All Lupopedia documentation must include a canonical `lupopedia.footer` block. Footer validation rules require:
- `last_verified` (UTC, 14 digits)
- `verified_by` (object with at minimum: `identity_type`, `actor_id`)
- `verified_via` (object with at minimum: `type`, `faucet_slug`)

Artifacts with `last_verified` earlier than `20260301000000` UTC are considered stale and must be semantically revalidated before updating the footer. See [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) for canonical rules and validator details.

## Temporal Anchor & UTC Timestamp Policy (4.0.93+)

**IDE agents and humans must not invent, round, or “pick” header timestamps.** Guessed values (e.g. aligning to a nice clock, reusing an old date, or typing `20260404…` without running the anchor) are **invalid** for canonical artifacts.

### Mandatory workflow

1. **Before** you write or update `last_modified_utc`, `when_updated`, `last_verified`, or thread filename UTC prefixes, run:
   ```bash
   python lupo-bin/tick.py
   ```
   That updates `lupo-bin/temporal_anchor.json` and root `CURRENT_UTC` from **real system UTC** (same source as [TIMESTAMP doctrine](lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md)).

2. Copy the printed `current_utc` value (14-digit `YYYYMMDDHHMMSS`) into YAML header/footer fields for **every file you touch in that batch**.

3. For additional files **in the same session** without advancing the clock again, reuse the same value:
   ```bash
   python lupo-bin/echo_anchor_utc.py
   ```
   (Reads the anchor only; does not update it. If the file is missing, run `tick.py` first.)

4. **Thread / decision filenames** use `YYYYMMDD_HHIISS` — take the first **8** digits and last **6** digits of `current_utc` with an underscore between date and time (e.g. `20260402_224629`).

**Canonical files:** [lupo-docs/doctrine/TICK_PY_DOCTRINE.md](lupo-docs/doctrine/TICK_PY_DOCTRINE.md), `lupo-bin/temporal_anchor.json`, `lupo-bin/tick.py`, `lupo-bin/echo_anchor_utc.py`.

**Policy:**
- All BIGINT header/footer timestamps are **14-digit UTC** from the anchor after `tick.py`, not local time and not synthetic.
- If you cannot run `tick.py` safely, **do not** write new timestamped artifacts; record work in a non-canonical scratch path or hand off to an environment where Python can run.

## Agent Metadata

All agent-specific UI, avatar, and configuration attributes are stored in `metadata_json` inside `lupo_agents`. Actor-ethics fields (pono, pilau, kapakai, kapu) belong only to `lupo_actors`.

## Subdirectory-Only Installation & Monitoring Widget (Critical)

**Lupopedia must always be installed in a subdirectory of your site (never at the web root).**

**Why:**
- Auto-installers (Softaculous, Installatron, etc.) do not allow replacing the web root.
- Lupopedia is designed to coexist with an existing site and monitor the parent site above its own directory.
- The Semantic Monitoring Widget ("The Eye") is responsible for tracking and analytics of the host site, not Lupopedia itself.

**How Monitoring Works:**
- Lupopedia provides a dynamic JavaScript endpoint (`lupopedia_js.php`) that must be embedded in your host site’s pages (outside the Lupopedia directory).
- Example usage:
  ```html
  <script src="/your-subdirectory/lupopedia_js.php"></script>
  ```
- The system must NOT assume the folder is named `lupopedia`—the installer will detect and store the correct subdirectory.
- All monitoring, visitor tracking, and content interaction features depend on this script being present on the host site.

**Never install Lupopedia at the web root.** All paths, cookies, and monitoring logic assume a subdirectory context.

See also: [Semantic Monitoring Widget PRD](lupo-docs/prd/28_semantic_monitoring_widget.md)

Lupopedia is a semantic AI operating system built on Crafty Syntax 3.7.5 foundations. It combines a hybrid MySQL plus filesystem architecture, multi-agent coordination, actor-based identity, and doctrine-driven documentation to evolve the original live-help system into a broader semantic runtime.

## What Lupopedia Is

At a high level, Lupopedia is:

- a semantic AI operating system
- a hybrid MySQL plus filesystem system
- a multi-agent coordination environment
- a Crafty Syntax 3.7.5 continuation and upgrade path

### Why the product is **4.x** (not 1.x)

Lupopedia is numbered **4.x** because it is the **next major generation** of the same product line as **Crafty Syntax 3.7.5** (live help / semantic OS continuity), not a unrelated “version 1” product.

### Database and upgrades during **4.0.x** (non‑negotiable)

Until **4.1.0** is released under the gates in **[PRD 33](lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md)** and **[PRD 27](lupo-docs/prd/27_installer_requirements.md)**:

- There is **no** supported **Lupopedia → Lupopedia** upgrade path and **no** migration chain that assumes preserving data across 4.0.x schema iterations.
- **Database changes (DDL, required seed rows, or Crafty import mapping)** must stay in sync across **three** canonical SQL surfaces—do **not** update install DDL and forget seed or import:
  1. **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** — full schema (`CREATE TABLE`, indexes).
  2. **Seed (single pipeline for all of 4.0.x — no per-patch seed files until 4.1.0):** Author new/changed rows under **`lupo-database/lupopedia/mysql/seed/`** (including **`seed_4.1.0.sql`** and any other fragments the repo uses). The **4.1.0** in the filename is the **product gate** name, not “only for 4.1.0 installs.” At **wizard runtime**, `install.php` prefers **`install/seed_lupopedia_4_1_0.sql`** when that file exists (full stack built by **`lupo-scripts/build_consolidated_seed_4_1_0.py`**); otherwise it runs **`lupo-database/lupopedia/mysql/seed/seed_4.1.0.sql`**. If you ship the merged **`install/`** file, **regenerate it after seed edits** so installs do not drift. Do **not** change DDL in **`install_new_lupopedia.sql`** without updating the seed side when defaults, registry rows, or required inserts change.
  3. **`lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`** — when **Crafty Syntax 3.7.5 → Lupopedia** behavior must change.
- Operators and developers **drop all Lupopedia tables** and run a **fresh install** (empty database), or **fresh install plus** legacy Crafty tables and the **Crafty → Lupopedia** import—**that** is the only supported data-bearing transition during 4.0.x.
- **4.1.0** is not merely a higher patch number: it is blocked until **auto‑installer / Softaculous‑class** acceptance and a **documented, successful Crafty → Lupopedia** hosting story exist. Until then, all work stays on **4.0.x** with **fresh install** as the way to pick up schema changes.

Canonical detail: **[Root constitutional PRD](lupo-docs/prd/00_root_constitutional_system_requirements.md)** (product lineage and §9.18 missing-table protocol), **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`**, and **`.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`**.

Core system characteristics:

- MySQL is the runtime authority for structured data, identities, edges, and operational state.
- The filesystem is used for documentation, coordination artifacts, channels, threads, prompts, and reports.
- Actors orchestrate work; faucets are execution surfaces.
- Channels and threads are the primary coordination model.

## How the Project Is Organized

The repository is organized around domain folders such as `lupo-docs/`, `lupo-database/`, `lupo-includes/`, `lupo-tests/`, `lupo-channels/`, and other `lupo-*` surfaces.

For a full evidence-based structure map of all root `lupo-*` directories and authority boundaries, use:

- `ORGANIZATION.md`
- `lupo-docs/ORGANIZATION.md`
- `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md`
- `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md`

For planning and progress, the most important rule is:

- `lupo-docs/versions/` is the source of truth for versioned execution, planning, and release state.

Root files are intentionally high level:

- `README.md` explains the project and version model.
- `plan.md` explains the high-level strategy.
- `TODO.md` lists high-level work categories.

Detailed execution belongs in version folders such as:

- `lupo-docs/versions/4.0.88/`
- `lupo-docs/versions/4.1.0/`

Organization references:

- `ORGANIZATION.md`
- `lupo-docs/ORGANIZATION.md`


Database/file-based authority references:

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical DDL
- `lupo-database/lupopedia/mysql/seed/` — seed **sources** (includes **`seed_4.1.0.sql`**; **one** seed pipeline for **all of 4.0.x**, no separate seed per patch until **4.1.0**)
- `install/seed_lupopedia_4_1_0.sql` — **optional merged** seed the wizard **prefers** when present; rebuild with **`lupo-scripts/build_consolidated_seed_4_1_0.py`** after changing sources under **`mysql/seed/`**
- `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` — Crafty **3.7.5 → Lupopedia** import only
- `lupo-database/lupopedia/json/` — generated per-table column mirrors (e.g. `lupo_contents.json`) used by import tooling; do not hand-edit
- `lupo-database/lupopedia/toon/` — optional TOON export in some workflows; same “generated mirror” role as `json/` when present
- `lupo-docs/database/lupopedia/tables/active/` — human-readable table documentation
- `lupo-channels/`
- `lupo-database/sessions/`

## ⚠️ CRITICAL: JSON Schema Files Are READ ONLY

**Location:** lupo-database/lupopedia/json/*.json

**These files are schema references ONLY. They contain NO data.**

- Generated by lupo-scripts/generate_toon_files.py from the live database
- **NEVER manually edit these files**
- **NEVER treat them as a file-based database**
- **NEVER query them as if they contain records**

### Correct Usage

1. **Read** to confirm column names before writing SQL
2. **Verify** table structure matches your expectations
3. **Reference** the fields array to know what columns exist

### Incorrect Usage (FORBIDDEN)

- ❌ Writing to these files
- ❌ Treating "data": [] as actual data
- ❌ Guessing column names without reading these files first
- ❌ Assuming schema without verification

### Schema Authority Order

1. **Live database** (MySQL/PostgreSQL) — SOURCE OF TRUTH after you have applied current install + seed (and import if used)
2. **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** — Canonical DDL
3. **`lupo-database/lupopedia/mysql/seed/`** (and merged **`install/seed_lupopedia_4_1_0.sql`** when shipped) — Canonical seed for 4.0.x (one pipeline; keep aligned with DDL; regenerate merged file after edits when used)
4. **`lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`** — Canonical Crafty import
5. **JSON / TOON schema files** — Read-only reference mirrors (regenerate from DB after fresh install)
6. **Table documentation** — Human-readable (`lupo-docs/database/lupopedia/tables/`)

**If the JSON file and the database disagree, the database is correct. Regenerate the JSON files.**

## Configuration File Location

Lupopedia follows the traditional open-source auto-installer pattern used by WordPress, phpBB, and similar applications.

The configuration file `lupopedia-config.php` is searched for in this order:

1. **One level above web document root** (most secure)
   - Example: `/home/user/lupopedia-config.php` when web root is `/home/user/public_html/` 

2. **One level above the Lupopedia installation directory**
   - Example: `/home/user/lupopedia-config.php` when Lupopedia is at `/home/user/public_html/lupopedia/` 

3. **In the Lupopedia installation directory itself** (fallback)
   - Example: `/home/user/public_html/lupopedia/lupopedia-config.php` 

### For Auto-Installers (Softaculous, etc.)

When creating a package for auto-installers:
- Place `lupopedia-config.php` **one level above installation directory**
- Use the constant `LUPOPEDIA_CONFIG_PATH` if you need to override the search order
- Ensure the configuration file is NOT web-accessible

For complete details, see **[Configuration Doctrine](lupo-docs/doctrine/CONFIGURATION_DOCTRINE.md)**.

## Development Rules & Constraints

All development in Lupopedia must follow the root rules and doctrines.

### Constitutional PRD (Non-Negotiable)

`lupo-docs/prd/00_root_constitutional_system_requirements.md` is the highest authority in this project. It is not a guideline. Every rule in it overrides any suggestion from any agent, IDE tool, framework documentation, or "best practice" article. Read it before writing code.

The full PRD set in `lupo-docs/prd/` defines what gets built and how. PRDs are requirements, not suggestions.

### WOLFIE Doctrine (Read Before Touching Existing Code)

`lupo-rules/root/WOLFIE_DOCTRINE.md` explains why code written in 1999 is still running in 2026 and why that is the goal, not a problem. If you are about to suggest installing a library, adding a framework, or "modernizing" something that works — read this first.

### Essential Rules Reference

**[Complete Root Rules](lupo-rules/root/README.md)** — all development constraints

**[LUPOPEDIA Headers Doctrine](lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)** — binding YAML header taxonomy, validation rules, and database-first mapping

Key constraints (all detailed in the constitutional PRD):
- PHP 5.6+ compatibility — no PHP 7+ features, shared hosting ready
- No Composer, no frameworks, no external dependencies without justification
- No foreign keys, triggers, or AUTO_INCREMENT in database
- All timestamps BIGINT UTC `YYYYMMDDHHIISS` via `gmdate('YmdHis')`
- All PKs via `IdGenerator::generate()` — never null, never auto-increment
- All DB access via `DatabaseFactory::getConnection()` — never raw PDO or mysqli
- Never run SQL via CLI — **4.0.x** DDL goes into **`install_new_lupopedia.sql`**; **seed** changes go into **`lupo-database/lupopedia/mysql/seed/`** (and regenerate **`install/seed_lupopedia_4_1_0.sql`** when your tree ships that merged file); Crafty mapping into **`import_from_old_crafty_syntax.sql`**. Then **fresh install**. **No** Lupopedia→Lupopedia migrations until **4.1.0** (see **PRD 00 §1.0**, **PRD 33**)
- Never guess column names — read the TOON JSON or table docs first

### Quick Checklist

Before writing code:
- [ ] Read `lupo-docs/prd/00_root_constitutional_system_requirements.md`
- [ ] If you change the database: update **`install_new_lupopedia.sql`**, **`lupo-database/lupopedia/mysql/seed/`** (including **`seed_4.1.0.sql`**) when seed data or defaults change, run **`lupo-scripts/build_consolidated_seed_4_1_0.py`** if you ship **`install/seed_lupopedia_4_1_0.sql`**, and **`import_from_old_crafty_syntax.sql`** when Crafty import must change (do not touch only one of these)
- [ ] PHP 5.6+ compatible
- [ ] No Composer dependencies, no npm for server-side logic
- [ ] No framework code
- [ ] DB access via `DatabaseFactory::getConnection()`
- [ ] PKs via `IdGenerator::generate()`
- [ ] UTC timestamps via `gmdate('YmdHis')`
- [ ] Table names via `LUPO_TABLE_PREFIX . 'tablename'`
- [ ] Column names verified against TOON JSON or table docs


## Version model, 4.1.0 gate, and Softaculous / auto‑installer policy

Lupopedia ships as a sequence of **4.0.x** patch releases until hosting distribution requirements are met. **4.1.0** is **not** tagged or treated as an active product milestone until **PRD 33** completion criteria are satisfied—including **manual** review and acceptance by an auto‑installer vendor (e.g. **Softaculous**): the maintainer submits the package; the vendor decides when it appears in their catalog. That acceptance is part of what unlocks calling a line **4.1.0**, together with the **Crafty Syntax 3.7.5 → Lupopedia** installer story those PRDs describe.

**This is separate from day‑to‑day 4.0.x development:** during **4.0.x**, there is still **no Lupopedia → Lupopedia** upgrade (see the box under **What Lupopedia Is** above). **4.1.0** is when **Lupopedia → Lupopedia** upgrades and a real migration story **may** be introduced—**not before**.

**Summary:**
- **4.0.x:** Fresh install from **`install_new_lupopedia.sql`** + seed (**`install/seed_lupopedia_4_1_0.sql`** if present, else **`mysql/seed/seed_4.1.0.sql`** — **one** seed pipeline for the whole 4.0.x line); optional **Crafty 3.7.5 → Lupopedia** via **`import_from_old_crafty_syntax.sql`**. No Lupopedia→Lupopedia migrations.
- **4.1.0 gate:** Auto‑installer readiness, certification-style evidence, and the product/installer requirements in **PRD 33** / **PRD 27**—not a routine version bump.
- All planning and PRDs must keep these distinctions explicit so implementers do not design migration chains for 4.0.x.

## Current focus

The project is currently focused on:

- **4.0.x** patch iteration (see **`lupo-docs/versions/`** for the active version folder)
- building toward **PRD 33** / hosting readiness for **4.1.0** (not a routine version bump)

Current detailed execution surfaces (replace `<version>` with the folder you are tracking, e.g. **4.0.94**):

- `lupo-docs/versions/<version>/README.md`
- `lupo-docs/versions/<version>/PLAN.md`
- `lupo-docs/versions/<version>/TODO.md`

Post-approval milestone surfaces:

- `lupo-docs/versions/4.1.0/plan.md`
- `lupo-docs/versions/4.1.0/todo.md`
- `lupo-docs/versions/4.1.0/prd/README.md`

## Key Systems Being Built

The main systems currently being defined, stabilized, or prepared are:

- channel system
- questions and prompts workflow
- approval footer and index system
- federation model
- `lupopedia_js.php` navigation and tracking system (**must be embedded in host site pages above the Lupopedia directory; see above**)
- channel refactor and migration model

## Channel-First Workflow

The current workflow model is:

1. Channel.
2. Questions.
3. Discussion.
4. Prompts.
5. Execution.

This model is documented in more detail in the version-scoped files and doctrine surfaces. Root docs only summarize it.

## Architecture Summary

Lupopedia uses a hybrid architecture:

- database-first runtime authority for content, identities, edges, tracking, and operational tables
- filesystem-first coordination and documentation surfaces for channels, threads, prompts, and reports
- edge integrity rules to keep document references and graph references coherent during structural changes
- federation-aware design that tolerates partial node 0 completeness during current deployment stages

## Where to Read Next

For developers and IDE agents, read in this order:

1. `lupo-docs/prd/00_root_constitutional_system_requirements.md` — **mandatory, read first, non-negotiable**
2. `lupo-rules/root/WOLFIE_DOCTRINE.md` — engineering philosophy, read before touching existing code
3. `AGENTS.md` — actor identity, coordination, and faucet model
4. `ONBOARDING.md` — operational quick-start
5. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md` — header/footer validation doctrine
6. `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md` — five-layer identity model
7. `ORGANIZATION.md` — repository structure and write authority
8. `lupo-docs/versions/4.0.94/README.md` — current working version overview (4.0.93 is frozen)
9. `lupo-docs/versions/4.0.94/PLAN.md` — current iteration plan
10. `lupo-docs/versions/4.0.94/decisions/` — architectural decisions and Q&A for this version
11. `lupo-docs/versions/4.0.94/TODO.md` — current task tracking

## Root File Policy

Root files must stay aligned with version folders but must not duplicate version detail.

- Root files explain direction.
- Version files define execution.
- If a contradiction appears, the version-scoped files under `lupo-docs/versions/` are the authoritative planning surfaces.

## Documentation System Architecture

Lupopedia uses a **dual-track documentation system** that separates requirements from implementation while maintaining complete traceability.

### The Five-Layer Documentation Architecture

Lupopedia uses a **five-layer documentation system** that preserves complete knowledge provenance:

| Layer | Question | Location | Purpose |
|-------|----------|----------|---------|
| **WHAT** | What to build? | `lupo-docs/prd/` | Requirements |
| **HOW** | How to build? | `lupo-docs/implementations/` | Technical execution |
| **WHY** | Why these decisions? | `discussions/` threads | Rationale & trade-offs |
| **WHO** | Who built it? | `authors.md` | Human provenance |
| **WHERE** | Where does it connect? | `edges.md` | System-wide mapping |

#### **Layer 1: PRDs (WHAT)**
- **Location**: `lupo-docs/prd/`
- **Purpose**: Defines requirements, specifications, and system design
- **Content**: Business requirements, technical specifications, user stories
- **Naming**: `{number}_{name}.md` (e.g., `25_departments_system.md`)

#### **Layer 2: Implementations (HOW)**
- **Location**: `lupo-docs/implementations/`
- **Structure**: Parallel folders matching PRD numbers
- **Naming**: `{number}_{name}/` (e.g., `25_departments_systems/`)
- **Standard Files**:
  - `README.md` - Implementation overview and status with emoji badges (🟢 Complete, 🟡 In Progress, 🔴 Not Started)
  - `changelog.md` - Evolution of the implementation over time
  - `{feature}.md` - Specific implementation details
  - `versions/` - Historical snapshots (e.g., `v1.0.0/`)
  - `tests/` - Test files and coverage documentation

#### **Layer 3: Decisions (WHY)**
- **Every implementation folder contains `decisions/` folder**
- **Purpose**: Documents the reasoning behind design decisions as structured decision records
- **Key Concept**: The WHY layer is not a single file. It is a structured conversation composed of timestamped, actor-attributed messages grouped into decision threads. This mirrors the Lupopedia channel architecture and ensures that decision-making is preserved as a living dialog rather than a static summary.
- **Structure**: 
  - `DECISION_INDEX.md` - Index of all decision threads
  - `{decision_name}/` folders for each topic
  - `YYYYMMDD_HHIISS_DECISION_purpose_TITLE.md` files for each decision record
- **Format**: Matches channel/thread architecture for consistency
- **Benefits**: 
  - Threads can be linked to actual channel threads
  - Each decision has its own traceable conversation
  - Database-friendly structure for future dialog system
  - Machine-navigable: Agents can reconstruct decision timelines, identify responsible actors, and trace the evolution of architectural reasoning

### Decision Folder Anatomy

```
decisions/
    THREAD_INDEX.md
    decision_name/
        YYYYMMDD_HHIISS_DECISION_purpose_TITLE.md
        YYYYMMDD_HHIISS_DECISION_purpose_TITLE.md
```

#### **Layer 4: Authors (WHO)**
- **Every implementation folder contains `authors.md`**
- **Purpose**: Tracks human and agent provenance
- **Content**:
  - Primary authors and contributors
  - Agent attribution (which AI wrote/reviewed what)
  - Accountability and decision makers
  - Review chain and contacts
- **Importance**: Systems outlive their creators, but decisions don't

#### **Layer 5: Edges (WHERE)**
- **Every implementation folder contains `edges.md`**
- **Purpose**: Maps system-wide relationships and dependencies
- **Content**:
  - Database edges (tables, columns, relationships)
  - Code edges (classes, controllers, services)
  - Documentation edges (PRD links, related implementations)
  - UI edges (templates, JavaScript, CSS)
  - External edges (APIs, third-party libraries)
- **Benefit**: Creates a machine-navigable graph of the entire system

### Why Five Layers?

Each layer answers a different epistemic question:

| Layer | Question | Meaning |
|-------|----------|---------|
| **WHAT** | Requirements | What must exist? |
| **HOW** | Implementation | How does it work? |
| **WHY** | Rationale | Why was it built this way? |
| **WHO** | Provenance | Who made these decisions? |
| **WHERE** | Graph | Where does it connect? |

### Traceability and Cross-Linking

- **PRD headers** link to implementation folders via `lupopedia.edges`
- **Implementation headers** link back to parent PRD via `parent_prd` field
- **Bidirectional navigation** between requirements and implementation

### Dialog System Parity

The WHY layer uses the same channel/thread/message structure as the Lupopedia dialog system. This ensures conceptual parity across the entire platform and allows future agents to ingest, analyze, and extend decision threads using the same mechanisms used for conversational reasoning.

### Version Control

- **PRDs**: Working copies under `lupo-docs/versions/<version>/prd/` (e.g. **4.0.94**); canonical grouped PRDs under `lupo-docs/prd/`; frozen snapshots (e.g. **4.0.93**) remain for reference
- **Implementations**: Versioned in `versions/` subdirectories (e.g., `v1.0.0/`)
- **Snapshots**: Preserve implementation state at major milestones

### Template and Consistency

- Use `_template/` folder for new implementations
- Follow `lowercase_with_underscores` naming for files
- Include all standard files and proper headers
- Add status badges for progress tracking
- **Decision Template**: Use `_template/decisions/YYYYMMDD_HHIISS_DECISION_purpose_TITLE.md` for all decision records
- **Reference**: PRD 26 defines the complete five-layer architecture

## Validation & Enforcement

### Required Schemas

| File | Required Fields | Validation |
|------|----------------|------------|
| PRD front-matter | `id`, `slug`, `title`, `status` | CI check |
| Implementation README | `id`, `parent_prd`, `status`, `version`, `last_reviewed_utc` | CI check |
| `authors.md` | Table with 7 required columns | CI check |
| `edges.md` | 5 required sections with bullet format | CI check |
| `DECISION_INDEX.md` | Table with 5 required columns | CI check |

### Validation Contract

All implementation folders MUST pass `lupo-scripts/validate_implementation.py` before merge.

**Required Files**: `authors.md`, `edges.md`, `decisions/DECISION_INDEX.md` must exist  
**Schema Compliance**: Required front-matter fields present and correctly formatted  
**Link Validation**: `parent_prd` points to existing PRD file  
**Status Accuracy**: Implementation status matches actual completion

**CI Enforcement**: Any commit that adds/modifies an implementation folder MUST pass validation or be rejected.

### Transition Policy for Legacy Implementations

For implementations predating this architecture (before 2026-04-02):

1. Mark with `doc_compliance: partial` in README front-matter
2. Create minimal `authors.md` with at least `actor_id: 0`, `role: "unknown"` 
3. Create stub `edges.md` with "PENDING" sections
4. Create placeholder `discussions/THREAD_INDEX.md` with status "legacy"
5. Gradual migration to full compliance during normal maintenance

### Documentation Architecture Versioning

`doc_arch_version: 1` — Increment when schemas change. All implementations must declare their compliance version.

### Edge Conflict Resolution

- **Primary source**: Implementation-level `edges.md` is authoritative for that feature
- **Derived graphs**: Cross-feature graphs are generated by tooling, not manually edited
- **Conflicts**: When two implementations claim the same edge, escalate to `discussions/` for resolution

### Example Flow

```
PRD (25_departments_system.md) - WHAT to build
    ↓ defines requirements
Implementation (25_departments_systems/) - HOW to build it
    ├── README.md (status & overview)
    ├── discussions/ (WHY we made these decisions)
    │   ├── THREAD_INDEX.md
    │   ├── database_schema/
    │   │   └── 20260402_120000_cursor_design_database_schema.md
    │   └── foreign_key_policy/
    │       └── 20260402_121500_lilith_constitutional_violation.md
    ├── authors.md (WHO built it & reviewed it)
    ├── edges.md (WHERE it connects in the system)
    ├── access_control.md (technical details)
    └── versions/v1.0.0/ (snapshot)
```

This five-layer system ensures that as requirements evolve, the implementation documentation stays synchronized, decision-making process is preserved as threaded conversations, provenance is tracked, and system relationships are mapped - creating a **complete knowledge operating system** that is both human-readable and machine-navigable for all agents.

## PRD Policy: Canonical Location and Authority

The PRDs in `lupo-docs/prd/` are the highest form of truth on what needs to be built and how things work. They are requirements, not suggestions.

- `lupo-docs/prd/00_root_constitutional_system_requirements.md` is the constitutional anchor — all other PRDs are subordinate to it
- All canonical PRDs live in `lupo-docs/prd/` only — do not create PRDs in versioned or legacy folders
- Every PRD must declare an outbound edge to `00_root_constitutional_system_requirements.md` as its constitutional anchor
- Before creating a new PRD, check `lupo-docs/prd/` for existing documents to avoid duplication
- Archive any PRDs found in versioned folders with `status: legacy` and a `superseded_by` reference
