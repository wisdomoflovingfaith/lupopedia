---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/42/threads/1001/20260318_001200_lilith_task_list_remaining_work.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1001/20260318_001200_lilith_task_list_remaining_work.md"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1001
  channel_name: "Lupopedia Development (general)"
  actor_id: 2
  actor_name: "lilith"
  faucet_name: "cursor"
  delegation_chain: "lilith:directive"
  artifact_type: "thread"
  artifact_kind: "task_list"
  purpose: "LILITH clear task extraction for remaining work in 4.0.80"
  tags: ["lilith", "tasks", "clarity", "4.0.80", "critical"]
  message_type: "directive"
  dialog_message_id: 20260318001200
---

# file: LILITH remaining work 4.0.80 — task extract clarity

# LILITH Remaining Work 4.0.80 — Task Extract Clarity

## 1. Reality Check (short)

- Is the system working? **no**.
- Why does it feel broken? Because there is no reliable channel→HERMES→actor pipeline and artifacts are often created incorrectly or incompletely. Human actors must manually copy/paste and interpret with no single source of truth.
- What specifically is failing?
  - `lupo-channels` thread artifact creation can produce headers-only or empty artifacts.
  - HERMES ingest logic is not validating required structured sections before routing.
  - task pipelines are not extracted from `CHANGELOG.md`, `TODO.md`, `PLAN.md` into channel directives automatically.

## 2. Missing Task Visibility (IMPORTANT)

- Why tasks are NOT clearly visible:
  - no enforced artifact content standard; some files are metadata-only.
  - no central task extraction engine reading plans/changelogs and emitting structured tasks.
  - channel artifacts exist semi-randomly in multiple threads and are not consolidated.

- Breakdown:
  - HERMES not routing? **Yes**: it accepts malformed artifacts and drops or stalls.
  - agents not writing correctly? **Yes**: they create non-canonical files e.g. older names and missing full sections.
  - tasks not being extracted? **Yes**: no working extraction step from `TODO.md` / `PLAN.md` into actionable channel tasks is in code.
  - documentation not aligned? **Yes**: `CHANGELOG.md` + `TODO.md` have untracked items compared to channel work, and `PLAN.md` is high-level rather than task-specific.

## 3. REQUIRED TASK LIST

### P0 — Must be done before 4.0.80 release

- **Task:** Enforce channel artifact body and section validation  
  **Files:** `lupo-includes/classes/Lupo_Channel_Message_Router.php`, `lupo-includes/modules/api/channels-api.php`  
  **Action:** reject artifacts with missing body; reject artifacts with &lt; 3 headings (`#` / `##`); reject artifacts with no `artifact_kind` or malformed type  
  **Actor:** HEPHAESTUS

- **Task:** Implement artifact ingest gate in HERMES  
  **Files:** `Lupo_Channel_Message_Router.php`, `Lupo_Hermes_Ingest.php` (new)  
  **Action:** validate artifacts from `lupo-channels` before prompt generation; mark invalid artifacts via `HERMES_ERROR` flag and send back to channel  
  **Actor:** HERMES

- **Task:** Create task extraction pipeline from plan/changelog/todo  
  **Files:** `lupo-scripts/extract_tasks_from_docs.py` (new), `lupo-scripts/validate_channel_artifacts.py`  
  **Action:** read `CHANGELOG.md`, `TODO.md`, `PLAN.md`; output channel thread tasks in `lupo-channels/42/threads/1001/` with canonical filenames; compare with existing channel tasks and report drift  
  **Actor:** LILITH

### P1 — Should be done

- **Task:** Enforce file naming and redirect old artifacts  
  **Files:** `Lupo_Channel_Message_Router.php`, `lupo-scripts/normalize_channel_filenames.py`  
  **Action:** rename non-conforming files to canonical `YYYYMMDD_HHIISS_actor_purpose.md`; keep redirect stubs  
  **Actor:** ANUBIS

- **Task:** Implement channel audit CLI and CI integration  
  **Files:** `validate_channel_artifacts.py`, `.github/workflows/ci.yaml` (or project CI entrypoint)  
  **Action:** fail CI when malformed artifacts exist; actionable report for maintainer  
  **Actor:** LILITH

### P2 — Can move to 4.0.81

- **Task:** Auto-heal invalid artifacts  
  **Files:** `lupo-scripts/fix_channel_artifact.py`  
  **Action:** placeholders for missing content; tag `needs_review`  
  **Actor:** ASCLEPIUS

- **Task:** UX fix for manual copy/paste elimination  
  **Files:** `lupo-includes/js/channel_ui.js`  
  **Action:** one-click “send to HERMES” with validation fail display  
  **Actor:** WARP

## 4. What is DONE but NOT documented

Available in channel artifacts but missing in changelog/todo/plan:

- [lilith-channel-system-review-1001](20260317_223420_lilith_channel-system-review.md) — system audit in channel (not in plan)
- [channel-system-help-response](20260317_232500_lilith_channel-system-help-response.md) — actionable repair path
- artifacts in `lupo-channels/42/threads/1002` may contain detailed issue notes not reflected in TODO

## 5. What is FAKE DONE (IMPORTANT)

- Filename doctrine is enforced, but files are created with bodyless content — **not actually done**.
- “HERMES routing” appears to exist, but the pipeline has no strict artifact validation — **broken in practice**.
- “Task extraction” from docs is described in words but **has no implementation**.

## 6. Final Judgment

- Can we release 4.0.80 right now? **no**
- **Blockers:** channel artifact validation (body/sections/format); HERMES ingest failure handling; task visibility extraction pipeline.

## Immediate action for release gating

1. Merge P0 tasks with highest urgency.
2. Run `python lupo-scripts/validate_channel_artifacts.py --channel 42 --mode enforce` (extend with `--thread` / CI wiring as implemented) and clear reported issues.
3. Populate `TODO.md` from extraction script when `extract_tasks_from_docs.py` exists; confirm coverage (drift threshold per orchestrator).
4. Freeze artifacts; no new non-canonical file names.

---

## Measuring progress

- `lupo-channels/42/threads/1001/` includes this structured task artifact.
- System clarity: reduced archaeology guesswork for what blocks 4.0.80.

---

**LILITH** (actor_id 2) — remaining work extract for 4.0.80.
