---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "docs/status/CURSOR_IMPLEMENTATION_CORRECTIONS_FROM_JETBRAINS_AND_ANTIGRAVITY_4.0.69.md"
  web_path: "http://www.lupopedia.com/status/CURSOR_IMPLEMENTATION_CORRECTIONS_FROM_JETBRAINS_AND_ANTIGRAVITY_4.0.69"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "status"
  artifact_kind: "report"
  purpose: "Implementation of agreed corrections from JetBrains and Antigravity reviews: actor PK docs, task columns, TOON sample, changelog drift, doc-schema checker, faucet traceability."
  tags: ["4.0.69", "jetbrains", "antigravity", "corrections", "review"]
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
---
# file: Cursor implementation corrections from JetBrains and Antigravity (4.0.69)

# Cursor Implementation Corrections from JetBrains and Antigravity (4.0.69)

This report documents the implementation of agreed corrections identified by **JetBrains** (Wolfie review) and **Antigravity** (implementation review) for actor/channel orchestration, Actor–Faucet ontology, ID rebase propagation, install SQL, TOON alignment, and documentation coherence.

---

## 1. What was reviewed

| Source | Document | Scope |
|--------|----------|--------|
| JetBrains | `lupo-docs/status/jetbrains_wolfie_review_actors_channels_4_0_69_20260311.md` | HOW_ACTORS_ORCHESTRATE PK error, CHANGELOG TOON count drift, lupo_actors.toon sample row, doc–schema consistency |
| Antigravity | `lupo-docs/status/ANTIGRAVITY_WOLFIE_IMPLEMENTATION_REVIEW_4_0_69.md` | assigned_to_actor_id vs owner_actor_id/acting_as_actor_id for lupo_tasks, seed ID alignment, faucet traceability population |

Additional artifacts: `docs/status/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`, `CHANGELOG.md`, `lupo-database/lupopedia/toon/lupo_actors.toon`, `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, `lupo-bin/lupo.php`, `lupo-includes/modules/channels/channel-send-api.php`, `lupo-bin/session_manager.php`, seed files.

---

## 2. Which findings were accepted

- **JetBrains:** (1) lupo_actors primary key is `actor_name`, not `actor_id` — **accepted**. (2) CHANGELOG TOON count 161 is stale — **accepted**. (3) lupo_actors.toon sample row has `actor_name: ''` and conflicting identity (2031, Windsurf) — **accepted**. (4) Add doc–schema consistency checker — **accepted**.
- **Antigravity:** (1) lupo_tasks uses `owner_actor_id` and `acting_as_actor_id`; no `assigned_to_actor_id` — **accepted**. (2) Seed data should match 4.0.69 rebase — **accepted** (verified seeds already aligned). (3) Faucet traceability must be populated at runtime — **accepted** (verified in channel-send-api and session_manager).

---

## 3. What was corrected

### A. Documentation

- **HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md**
  - **Primary key:** Section 2 and summary table now state **primary key = actor_name**, **actor_id = unique secondary identifier**. Added an **ACTOR PRIMARY KEY DOCTRINE** callout referencing install SQL: PK = actor_name (canonical row identity), actor_id = operational numeric handle (used in FKs/APIs). Doc must not collapse the two.
  - **Model paragraph:** Opening sentence updated to "canonical primary key: actor_name; unique secondary identifier: actor_id".
  - **Orchestration wording:** Preserved (actors = orchestration identities, faucets = execution surfaces, sessions = runtime context).

### B. Runtime / task columns

- **lupo-bin/lupo.php (tasks command):** Query and display used `status_id`; `lupo_tasks` has `task_status` (varchar). Replaced with `task_status` and safe display of `task_status` in output. **No** `assigned_to_actor_id` reference was found in lupo_tasks code paths; Antigravity’s #1054 referred to legacy or one-off usage. AdminTasksHandler and TaskService already use `owner_actor_id` / `acting_as_actor_id`.

### C. CHANGELOG drift

- **CHANGELOG.md:** Removed the hardcoded "observed table count during this thread: **161**". Replaced with wording that the TOON table count is taken from the repo at release/review time (e.g. `Get-ChildItem lupo-database/lupopedia/toon -File | Measure-Object`) and that a single number should not be hardcoded to prevent drift.

### D. TOON sample consistency

- **lupo-database/lupopedia/toon/lupo_actors.toon:** The sample row had `actor_name: ''` (invalid for PK) and identity hints (actor_id 2031, slug cursor-ide, name Windsurf IDE). Replaced with an **illustrative** sample: `actor_name: wolfie`, `actor_id: 1`, slug `captain-wolfie`, name "Captain WOLFIE", consistent with seed. Added a comment that sample rows are **illustrative only** and not canonical identity truth; canonical data comes from seed and registry.

### E. Doc–schema consistency checker

- **scripts/check_doc_schema_consistency.py:** New script. Checks: (1) lupo_actors TOON primary_key = actor_name; (2) install SQL has PRIMARY KEY (actor_name) for lupo_actors; (3) lupo_actor_traits has actor_id, trait_key, federation_node_id in TOON and install; (4) lupo_action_authorization has action_key, required_trait_keys, required_role_keys; (5) lupo_dialog_messages has source_faucet_slug, source_faucet_instance_id; (6) lupo_sessions has faucet_slug, faucet_instance_id; (7) install does **not** create lupo_threads or lupo_messages. Run from project root: `python scripts/check_doc_schema_consistency.py`. Exit 0 = pass, 1 = fail.

---

## 4. What was verified (no change or already correct)

- **Seed actor/faucet IDs:** `seed_actors_agents_4.0.45.sql` is already aligned to 4.0.69 rebase (Wolfie=1, root=1000, cursor-ide=102, IDE agents 100–111, etc.). No seed file changes made.
- **Faucet traceability:** `channel-send-api.php` already sets `source_faucet_slug` and `source_faucet_instance_id` from `LUPO_FAUCET_SLUG` and `LUPO_FAUCET_INSTANCE_ID`. `session_manager.php` already sets `faucet_slug` and `faucet_instance_id` from session data when writing to `lupo_sessions`. No code change. **Recommendation:** Ensure IDE/CLI entry points define `LUPO_FAUCET_SLUG` (and optionally `LUPO_FAUCET_INSTANCE_ID`) when running as an IDE so that all messages get faucet traceability.
- **assigned_to_actor_id:** No references to `assigned_to_actor_id` for **lupo_tasks** were found in active PHP (AdminTasksHandler, TaskService use owner_actor_id/acting_as_actor_id). The column exists and is used only on **lupo_anubis_log** / **lupo_anubis_queue** (different tables); those were not changed.

---

## 5. Remaining follow-up items

- **TOON regeneration:** JetBrains recommended regenerating TOONs from the live DB. The lupo_actors.toon sample was corrected manually; for full alignment, run `python scripts/generate_toon_files.py` after DB is in desired state and re-check sample data.
- **Release checklist:** Add a step to run `python scripts/check_doc_schema_consistency.py` before release and to record TOON count (or "see script/output") instead of a fixed number in CHANGELOG.
- **LUPO_FAUCET_SLUG:** Document or enforce in bootstrap/entry points (e.g. CLI, web) that when running as an IDE agent, `LUPO_FAUCET_SLUG` (and optionally `LUPO_FAUCET_INSTANCE_ID`) should be set so that message creation always has faucet traceability.

---

## 6. Summary

| Finding | Action |
|---------|--------|
| lupo_actors PK documented as actor_id | Corrected to actor_name (PK), actor_id (unique secondary); doctrine callout added. |
| CHANGELOG TOON count 161 | Replaced with non-stale wording (count at release/review time). |
| lupo_actors.toon bad sample row | Replaced with illustrative wolfie/1 sample; comment added. |
| Doc–schema drift | Added check_doc_schema_consistency.py. |
| lupo_tasks status_id / assigned_to | lupo.php tasks command fixed to use task_status; no assigned_to_actor_id in tasks code. |
| Seed IDs | Verified aligned; no change. |
| Faucet population | Verified in channel-send-api and session_manager; no change. |

All accepted corrections have been implemented or verified. The doc–schema consistency script passes against the current install SQL and TOONs.
