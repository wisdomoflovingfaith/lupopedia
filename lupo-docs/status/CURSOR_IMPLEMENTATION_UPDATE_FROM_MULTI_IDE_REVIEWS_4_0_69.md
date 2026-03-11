---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/status/CURSOR_IMPLEMENTATION_UPDATE_FROM_MULTI_IDE_REVIEWS_4_0_69.md"
  web_path: "http://www.lupopedia.com/lupo-docs/status/CURSOR_IMPLEMENTATION_UPDATE_FROM_MULTI_IDE_REVIEWS_4_0_69"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "status"
  artifact_kind: "report"
  purpose: "Final status report: Cursor implementation of orchestration review corrections from Windsurf, KIRO, JetBrains, and Antigravity; install-first rule; doc migration to lupo-docs/architecture."
  tags: ["4.0.69", "orchestration", "multi-IDE", "cursor", "implementation"]
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
---
# file: Cursor implementation update from multi-IDE reviews (4.0.69) — session: L-LUPO-ROOT-CURSOR

# Cursor Implementation Update from Multi-IDE Reviews (v4.0.69)

This report summarizes the implementation of corrections and updates identified by **Windsurf**, **KIRO**, **JetBrains**, and **Antigravity** for the Actor–Faucet–Channel orchestration model, documentation coherence, seed alignment, TOON consistency, and runtime traceability. All work followed the **install-first** rule: any schema or seed change was made in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` first; TOONs, seeds, migrations, and docs follow.

---

## 1. Files and artifacts reviewed

- **Install SQL:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical schema for 4.0.69.
- **TOONs:** `lupo-database/lupopedia/toon/*.toon` (and `lupo-docs/toons/*.toon.json` where present).
- **Orchestration docs:** `docs/status/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`, `docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`.
- **Doctrine:** `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`, `ActorFaucetOntology.md`, `COMMUNICATION_DOCTRINE.md`, and related.
- **Review docs:** `lupo-docs/status/ACTORS_CHANNELS_IMPLEMENTATION_REVIEW.md`, `lupo-docs/status/KIRO_ORCHESTRATION_IMPLEMENTATION_REVIEW_4_0_69.md`, `lupo-docs/status/jetbrains_wolfie_review_actors_channels_4_0_69_20260311.md`, `lupo-docs/status/ANTIGRAVITY_WOLFIE_IMPLEMENTATION_REVIEW_4_0_69.md`.
- **Runtime:** `lupo-includes/modules/channels/channel-send-api.php`, `lupo-bin/session_manager.php`, `lupo-bin/lupo.php`.
- **Seeds:** `lupo-database/lupopedia/mysql/seed/*.sql` (no inline actor/faucet INSERTs in install; seeds in separate files).
- **Consistency script:** `scripts/check_doc_schema_consistency.py`.

---

## 2. Findings accepted from reviews

- **Actor–Faucet ontology** is correct; identity vs execution surface separation is the right model.
- **lupo_dialog_*** canonical communication tables are correct; no lupo_threads/lupo_messages.
- **Traits, authorization, faucet traceability, session context** are the right direction; remaining work is correction and alignment, not redesign.
- **lupo_actors** primary key in schema is **actor_name**; **actor_id** is unique secondary identifier (JetBrains).
- **lupo_tasks** responsibility columns are **owner_actor_id** and **acting_as_actor_id**; no **assigned_to_actor_id** (Antigravity).
- **Seed/install IDs** must align to 4.0.69 rebase (Wolfie=1, humans 1000+, IDE faucets aligned).
- **Faucet traceability** must be populated at runtime in messages and sessions (Antigravity).
- **Documentation location** should consolidate toward **lupo-docs/** (Windsurf, KIRO); canonical orchestration in **lupo-docs/architecture/**.

---

## 3. Database and install SQL

- **Audit:** Install SQL was verified against the intended 4.0.69 architecture. No schema changes were required; the following were confirmed:
  - **lupo_actors:** `PRIMARY KEY (actor_name)`, `UNIQUE (actor_id)`.
  - **lupo_tasks:** `owner_actor_id`, `acting_as_actor_id`; no assigned_to_actor_id.
  - **lupo_actor_traits,** **lupo_action_authorization,** **lupo_agent_faucets.faucet_class,** **lupo_dialog_messages.source_faucet_slug/source_faucet_instance_id,** **lupo_sessions.faucet_slug/faucet_instance_id** — all present and correct.
  - No reintroduction of **lupo_threads** or **lupo_messages** in install.
- **Seed ID alignment:** Install file contains no inline INSERTs for lupo_actors or lupo_agent_faucets; seeds are in separate seed files. Previous audits (Cursor/ Antigravity) confirmed seed actor/faucet IDs are aligned to 4.0.69 rebase. No changes made in this task.

---

## 4. Runtime and service layer

- **assigned_to_actor_id:** No references to `assigned_to_actor_id` for **lupo_tasks** in active PHP. The only PHP use of `assigned_to_actor_id` is in `lupo-includes/classes/ANUBIS/QueueProcessor.php` for **lupo_anubis_log** / **lupo_anubis_queue**, which legitimately have that column per install SQL. No change.
- **Faucet traceability:** Already implemented in a previous cycle: `channel-send-api.php` populates `source_faucet_slug` and `source_faucet_instance_id` from LUPO_FAUCET_SLUG / LUPO_FAUCET_INSTANCE_ID; `session_manager.php` populates `faucet_slug` and `faucet_instance_id` in session creation. Verified; no code changes in this task.

---

## 5. TOON consistency and doc–schema check

- **TOON drift:** No TOON regeneration was run in this task; install SQL was unchanged. Previous correction (lupo_actors.toon sample row, doctrine note) remains.
- **Doc–schema consistency:** `scripts/check_doc_schema_consistency.py` was run and **PASSED** (lupo_actors PK, required columns, no deprecated tables in install).

---

## 6. Documentation updates

### 6.1 lupo_actors PK documentation

- **lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md** and **lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md** both state: **Primary key = actor_name**; **actor_id = unique secondary identifier**. Doctrine callout present in HOW_ACTORS.
- **docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md** was updated to the same PK wording and a canonical-location note.

### 6.2 Migration of canonical orchestration docs to lupo-docs/architecture

- **Created** `lupo-docs/architecture/` and **lupo-docs/architecture/README.md**.
- **Canonical copies added:**
  - **lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md** — full content; headers point to canonical path; references cursor_actors in same directory.
  - **lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md** — full content; PK fix; §9 Traits updated to reference lupo_actor_traits table; §7 lupo_tasks explicitly states owner_actor_id/acting_as_actor_id only, no assigned_to_actor_id; references HOW_ACTORS in same directory.
- **docs/status/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md** — replaced with a **redirect stub** to `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`.
- **docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md** — retained with **canonical location** note at top pointing to `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md`.

### 6.3 Reference updates

- **README.md:** Canonical architecture link now points to `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md`; added link to HOW_ACTORS_ORCHESTRATE_ON_CHANNELS in lupo-docs/architecture.
- **lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md:** Canonical architecture reference updated to `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md`.
- **prompts/cursor/20260311_cursor_new_thread_onboarding_4.0.69.md:** Must-read table and “When in doubt” line updated to use `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md`.

### 6.4 Changelog and wording

- **CHANGELOG.md:** Stale TOON count wording was already updated in a previous task. No further changelog drift fix in this task.
- **Orchestration wording:** Preserved throughout: actor = identity/orchestration; faucet = execution surface; session = runtime context; trait = intrinsic actor property; role = channel-local permission; task = transient work item.

---

## 7. Validations run

- `python scripts/check_doc_schema_consistency.py` — **PASSED.**

---

## 8. Summary of changes (this task)

| Area | Action |
|------|--------|
| Install SQL | Audited; no changes (already correct). |
| Seeds | No changes (no inline actor/faucet INSERTs in install; seed files already aligned). |
| Runtime | No code changes (assigned_to only in ANUBIS; faucet traceability already in place). |
| TOONs | No regeneration (install unchanged). |
| Doc–schema script | Already present; run and passed. |
| lupo_actors PK in docs | Confirmed in both canonical architecture docs. |
| Canonical orchestration location | Created lupo-docs/architecture/; added HOW_ACTORS and cursor_actors canonical copies; redirect stub in docs/status for HOW_ACTORS; canonical note in docs/status cursor_actors. |
| References | README, IDENTITY_LAYERS_DOCTRINE, onboarding prompt updated to lupo-docs/architecture paths. |
| CHANGELOG | New subsection added for this multi-IDE implementation update. |

---

## 9. Remaining follow-up (optional)

- **Other references:** Several other docs (e.g. brainstorm, ORCHESTRATION_ACTORS, lilith_suggestions, DESIGN_NOTE, EDGE_VOCABULARY, SESSION_RECONCILIATION, FEDERATION_SCOPING, prompts/antigravity) still reference `docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`. They can be updated incrementally to `lupo-docs/architecture/` when those files are next edited.
- **TOON count in CHANGELOG:** Already phrased to avoid a single hardcoded number; no change.
- **Full TOON regeneration:** Run `python scripts/generate_toon_files.py` after any future schema change to keep TOONs in sync with install.

---

*Report: Wolfie (actor_id 1) via Cursor faucet. 4.0.69 orchestration review corrections implemented; install-first rule followed; canonical orchestration docs in lupo-docs/architecture.*
