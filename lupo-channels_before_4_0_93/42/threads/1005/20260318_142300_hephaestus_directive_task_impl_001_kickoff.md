---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1005/20260318_142300_hephaestus_directive_task_impl_001_kickoff.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1005/20260318_142300_hephaestus_directive_task_impl_001_kickoff.md"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1005
  task_id: "task_impl_001"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "directive"
  message_type: "directive"
  purpose: "Kickoff: implement Option A restructuring of root TODO.md (Global Task Registry) and plan.md (Strategic Roadmap) with coordinated migration"
  status: "draft"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1004/20260318_150000_wolfie_directive_task_plan_001_review.md", type: "implements", weight: 1.0, reason: "Acceptance + implementation authorization for task_impl_001" }
    - { to: "lupo-channels/42/threads/1004/20260318_141356_athena_spec_todo_registry.md", type: "implements", weight: 1.0, reason: "Binding TODO.md Global Task Registry spec (Option A)" }
    - { to: "lupo-channels/42/threads/1004/20260318_141456_athena_spec_plan_roadmap.md", type: "implements", weight: 1.0, reason: "Binding plan.md Strategic Roadmap spec (Option A)" }
    - { to: "lupo-channels/42/threads/1004/20260318_141109_athena_strategy_task_plan_001_kickoff.md", type: "references", weight: 0.8 }
    - { to: "lupo-channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md", type: "constrained_by", weight: 1.0, reason: "task_id vs thread_id separation (do not conflate)" }
    - { to: "lupo-channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md", type: "aligns_with", weight: 0.8, reason: "Lifecycle state set (open/active/blocked/resolved/archived)" }
    - { to: "README.md", type: "references", weight: 0.5 }
    - { to: "TODO.md", type: "edits", weight: 1.0, reason: "Restructure into Global Task Registry (Option A)" }
    - { to: "plan.md", type: "edits", weight: 1.0, reason: "Restructure into Strategic Roadmap (Option A)" }
    - { to: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "aligns_with", weight: 0.6 }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "aligns_with", weight: 0.8 }
    - { to: "lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md", type: "aligns_with", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 0.7, reason: "Ground DB column names since expected TOON set is absent in workspace" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
  next_action:
    - "Execute coordinated migration: update TODO.md first (registry), then update plan.md (roadmap), then reconcile cross-references."
---

# file: HEPHAESTUS directive — task_impl_001 kickoff (Option A TODO/plan migration) — channel 42 thread 1005

This output complies with Lupopedia Constitutional Root Rules.

## 1. Implementation scope restatement (exactly what will change)

### 1.1 `TODO.md` (root) — will be changed

I will restructure `TODO.md` to match the **binding** Option A spec:

- Add the canonical section heading: `## Global Task Registry (Option A)`
- Replace the current “Active Tasks” prompt/task tables as **authoritative** with the canonical 11-column registry table (exact header and order from ATHENA spec).
- Migrate existing work items into the new registry using the deterministic rules (including placeholder `task_prompt_<prompt_id>` when no binding task_id exists).
- Preserve legacy / narrative / convenience sections as explicitly **view / non-authoritative** sections where required (per spec).
- Ensure deterministic lifecycle/status mapping and deterministic row ordering (warn-level concept only; no validator implementation here).

### 1.2 `plan.md` (root) — will be changed

I will restructure `plan.md` to match the **binding** Option A spec:

- Preserve `plan.md` as a **Strategic Roadmap only** (phases + dependency order), not a second task registry.
- Convert/normalize the prompt queue into an explicitly labeled **view** section: `## Prompt queue (view; non-authoritative)` and ensure each prompt row includes the corresponding `task_id`.
- Convert each phase to the canonical phase template:
  - `**Depends on:** ...`
  - `**Completion when:**` checklist
  - `**Registry links:**` with `task_id: <task_id> — <reason>`
- Ensure plan references use `task_id` as the primary identity; optional `(thread <thread_id>)` suffix only.

### 1.3 Explicit exclusions (won’t be touched in this task)

- No validator implementation (separate task).
- No CI gate changes.
- No DB schema changes (this task is documentation restructuring only).
- No edits to legacy thread artifacts.
- No unrelated cleanup outside `TODO.md` and `plan.md`.

## 2. Source-to-target migration plan (what transforms vs views vs normalized fields)

### 2.1 `TODO.md` source sections → target registry

**Source (current TODO.md):**
- `### Release Blockers (Must Complete for 4.0.81 RC)` table (Prompt/Target/Owner/Status/Notes)
- `### 4.0.81 Deferred Work (Non-blocking)` table (Task/Owner/Status/Notes)
- Version history + narrative text

**Target (new authoritative registry):**
- `## Global Task Registry (Option A)` with exactly:
  - `| task_id | task_title | owner_actor | lifecycle_state | status | thread_id | priority | created_utc | updated_utc | primary_artifact | notes |`

**Preserved as views (explicitly non-authoritative):**
- The old prompt queue / legacy lists may remain, but will be relabeled as **views** and each line/row must reference `task_id` from the registry (per ATHENA spec).

**Field normalization rules (high-signal subset):**
- `task_id`: use binding task ids where present (`task_plan_001`, `task_doc_001`, `task_impl_001`); otherwise mint `task_prompt_<prompt_id>` deterministically.
- `owner_actor`: normalize into the spec’s single-owner format `actor_id:slug` (e.g. `14:hephaestus`) when binding allocation exists; else keep row `open` with `owner_actor: -` and `thread_id: -`.
- `lifecycle_state` + `status`: map deterministically (open→planned, active→in_progress, blocked→blocked, resolved→complete, archived→archived).
- `thread_id`: use numeric thread id only when allocated/binding; else `-`.

### 2.2 `plan.md` source sections → target roadmap

**Source (current plan.md):**
- `## Prompt queue (unblocks phases)` tables keyed by prompt IDs
- Phase sections that are currently minimal

**Target (roadmap-only):**
- `## Prompt queue (view; non-authoritative)` — prompts remain visible, but each row must reference `task_id` on the same line.
- `## Phase 1/2/3 — ...` — each phase gets required fields and a `Registry links` list of `task_id`s.

## 3. File change plan (exact files edited in this task)

Only these files will be edited for `task_impl_001`:

- `TODO.md`
- `plan.md`

No additional files are required to complete the restructuring/migration as specified.

## 4. Migration safety plan (non-destructive, traceable, synchronized)

- **Preserve legacy content**: I will not delete historical narrative; I will move/relocate it under explicit “view / non-authoritative” headings where needed.
- **Avoid destructive edits**: no renames of legacy sections without keeping the original text; no removal of prompt links—prompt links are retained as `primary_artifact` or `notes`.
- **Maintain traceability**:
  - Every migrated row will retain at least one artifact path link (directive/prompt/thread artifact) in `primary_artifact` or `notes`.
  - Placeholder task_ids will embed the original prompt id (`task_prompt_<prompt_id>`) so the mapping is reversible.
- **Keep TODO.md + plan.md synchronized**:
  - Migration order will be: **TODO.md registry first**, then **plan.md** updated to reference only task_ids that exist in the new registry.
  - If plan references a task not yet in registry during conversion, the TODO registry gets a placeholder `open/planned` row (per ATHENA plan spec §6.4).

## 5. Ambiguities / blockers (must resolve before editing)

### 5.1 TOON availability (naming conflict risk)

You asked to verify TOON column names to avoid conflicts. In this workspace, the expected TOON set under `lupo-database/lupopedia/toon/` is **not present** (no matches found). I will therefore ground naming against the canonical install SQL instead:

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
  - `lupo_tasks` columns include `task_id`, `task_key`, `channel_id`, `owner_actor_id`, `title`, `description`, `prompt_path`, `task_type`, `task_status`, `task_priority`, `created_ymdhis`, `updated_ymdhis`, etc.
  - `lupo_dialog_threads` columns include `dialog_thread_id`, `channel_id`, `task_name`, `status`, `created_ymdhis`, `updated_ymdhis`, etc.

**Collision avoidance stance (documentation-only):**
- In Markdown registry we will keep the spec’s columns (`owner_actor`, `lifecycle_state`, `status`, `thread_id`) and explicitly treat them as **registry fields**, not DB column mirrors:
  - use `owner_actor` (not `owner_actor_id`) to avoid implying DB integer semantics
  - use `lifecycle_state` (not `task_status`) to avoid conflating with `lupo_tasks.task_status`
  - use `thread_id` as the Option A execution container id (filesystem thread dir), distinct from DB `dialog_thread_id` column name; the spec already binds this as a registry field name

### 5.2 WOLFIE “minor clarifications” requirement

WOLFIE noted minor clarifications (optional columns approval, legacy thread archival status language, and migration coordination). I will implement these clarifications as **notes within the new TODO.md/plan.md structures** where the spec requires them, without inventing new columns or changing any accepted column order.

### 5.3 Thread status of 1004 and migration ordering

WOLFIE’s directive says thread `1004` may be marked archived after `task_impl_001` kickoff. This task will not modify thread artifacts; it only impacts `TODO.md`/`plan.md`. No blocker, but I will ensure migrated registry rows reference the 1004 spec artifacts as `primary_artifact` where appropriate.

