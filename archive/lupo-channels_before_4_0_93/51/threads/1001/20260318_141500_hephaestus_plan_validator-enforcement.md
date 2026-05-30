---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "lupo-channels/51/threads/1001/20260318_141500_hephaestus_plan_validator-enforcement.md"
  questions_toon: null
  channel_id: 51
  thread_id: 1001
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "plan"
  message_type: "status"
  purpose: "THREAD001 validator enforcement plan (targets, checks, legacy handling, CI integration)"
  status: "draft"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/51/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md", type: "implements", weight: 1.0, reason: "Binding task_id vs thread_id separation + P0 enforcement mechanism" }
    - { to: "lupo-channels/51/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md", type: "implements", weight: 1.0, reason: "Baseline §8 validator-relevant rules" }
    - { to: "lupo-channels/51/threads/1001/20260318_095000_lilith_review_thread-task-canonicalization.md", type: "addresses", weight: 0.9, reason: "P0 lifecycle/split/merge/validator hook gaps" }
    - { to: "lupo-channels/51/threads/1001/20260318_134258_hermes_prompt_for_wolfie_thread001-triage-and-routing.md", type: "addresses", weight: 0.9, reason: "Validator mapping + legacy constraints" }
    - { to: "lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md", type: "references", weight: 0.8, reason: "Numeric thread dirs + canonical filename rule" }
    - { to: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "references", weight: 0.7 }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 1.0, reason: "ATER001 + coordination constraints" }
    - { to: "lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md", type: "references", weight: 1.0, reason: "No hidden transitions, no rewriting history, deterministic enforcement" }
    - { to: "lupo-scripts/validate_channel_artifacts.py", type: "references", weight: 0.9, reason: "Filesystem/CI validator entrypoint" }
    - { to: "lupo-includes/classes/ChannelArtifactValidator.php", type: "references", weight: 0.9, reason: "Path-based PHP validator entrypoint" }
    - { to: "lupo-includes/classes/Lupo_Channel_Artifact_Validator.php", type: "references", weight: 0.9, reason: "API/router body gates + canonical filename/thread id validators" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  next_action:
    - "Implement checks incrementally in Python (CI) first, then mirror in PHP path-validator where safe."
    - "Do not enforce new task_id markers as blocking until WOLFIE publishes the canonical marker format + waiver format."
---

# file: HEPHAESTUS plan — THREAD001 validator enforcement — channel 42 thread 1001

This output complies with Lupopedia Constitutional Root Rules.

## 0. Scope and non-goals (binding)

- **Goal**: define validator enforcement for THREAD001 without DB assumptions (filesystem + PHP path checks + API/router body gates).
- **No code implementation in this artifact**: this is a plan only.
- **No DB/schema proposals**: THREAD001 schema content is constitutionally blocked and out of scope here.
- **Respect separation**: `task_id` (work identity) is distinct from `thread_id` (numeric `dialog_thread_id` directory key).

## 1. Validation targets (errors vs warnings vs doctrine-only)

Baseline: ATHENA §8 (V-1..V-6, W-1..W-2, D-1..D-3). Enforcement levels below reflect *mechanical enforceability offline* and the current repo’s validator surfaces:
- **CI/filesystem**: `lupo-scripts/validate_channel_artifacts.py`
- **PHP path validation**: `lupo-includes/classes/ChannelArtifactValidator.php` (calls `Lupo_Channel_Artifact_Validator::validateThreadPostBody`)
- **API/router body gate**: `lupo-includes/classes/Lupo_Channel_Artifact_Validator.php` used by `channels-api.php` and `Lupo_Channel_Message_Router.php`

### 1.1 Blocking errors (mechanically enforceable now)

- **E-ATER001-THREAD-BODY**: review/help_response substantive body contract
  - Already enforced for API/router and test-covered; CI enforces for filesystem thread artifacts when `--mode enforce` is used.
- **E-THREAD-DIR-NUMERIC**: thread directory under `threads/` must be numeric `dialog_thread_id` only
  - Already enforced by CI (`NUMERIC_THREAD`) and by doctrine.
- **E-FILENAME-CANONICAL**: thread artifact filename matches canonical regex
  - Already enforced by CI (`CANONICAL_MD`) and by `Lupo_Channel_Artifact_Validator::isValidCanonicalFilename()`.

### 1.2 Warnings-only (enforceable as non-blocking now)

These are mechanically checkable offline, but should not block until a single canonical marker format is published (WOLFIE doctrine patch step) and legacy posture is locked.

- **W-V6-TRANSITION-MARKER**: if a transition marker exists, validate it strictly; if missing, warn (not error) until marker is mandatory.
- **W-V4-SPLIT-MERGE-FIELDS**: if “split”/“merge” is declared, require fields; else warn (not error) until the deterministic block format is canonical.
- **W-V5-ONE-ACTIVE-SCOPE**: if a thread artifact declares multiple `task_id` values, warn now (not error) until canonical `task_id` declaration format is implemented repo-wide.
- **W-V3-NON-CONFLATION**: if an artifact text claims “thread_id is the task identity” (or contains conflicting markers), warn; do not attempt NLP enforcement beyond deterministic markers.
- **W-V2-LEGACY-ISOLATION**: warn when new work is posted in a thread marked legacy/archived (see §5), but do not block until WOLFIE publishes an explicit “archived/legacy thread” marker and waiver path.

### 1.3 Doctrine-only / not yet enforceable offline

- **D-OWNERSHIP-TRUTH (ATHENA D-1)**: validator can require presence of an ownership declaration, but cannot verify authoritative owner offline without integrating root `TODO.md` or DB.
- **D-REVIEW-GATING-FOR-RESOLVED (ATHENA D-2)**: whether review is required depends on task context; must remain directive/doctrine-driven.
- **D-THREAD-REASSIGNMENT-LEGITIMACY (ATHENA D-3)**: offline validator can require a reference to a WOLFIE directive artifact path, but cannot verify DB thread row existence.

## 2. Concrete validator checks (rule → check mapping)

This section defines **deterministic** checks. No hidden transitions; no content “guessing”.

### 2.1 Canonical structured markers (proposed minimal set)

These markers are designed so validators can check rules **without DB**:

- **Task identity marker (when the artifact claims to be task-execution scoped)**:
  - `task_id: <token>` (token format defined by WOLFIE; validator treats “missing” as warn until canon is published)
- **Lifecycle marker**:
  - `lifecycle_state: open|active|blocked|resolved|archived`
- **Transition marker (ATHENA V-6)**:
  - `transition: <from_state> -> <to_state>`
- **Split marker block (ATHENA V-4)**: must include:
  - `parent_task_id: ...`
  - `child_task_id: ...` (repeatable or list form)
  - `child_thread_id: ...` (if provided; numeric-only)
- **Merge marker block (ATHENA V-4)**: must include:
  - `merged_into_task_id: ...`
  - `merged_from_child_task_id: ...` (repeatable or list)
  - `merge_artifact: <path>` (repeatable; must look like `lupo-channels/.../*.md`)
- **WOLFIE waiver marker** (for legacy / exceptional postings):
  - `wolfie_waiver_artifact: lupo-channels/51/threads/1001/YYYYMMDD_HHIISS_wolfie_directive_....md`

**Validator approach**: markers are detected either in YAML frontmatter (preferred) or in an explicit body block under a single heading (e.g. “## Thread markers”). Implementation should pick one and enforce it consistently (plan assumes YAML-first because existing validators already parse YAML).

### 2.2 Checks required by this assignment (minimum list)

#### A) `task_id` presence where required

- **Trigger condition (deterministic)**: artifact declares any of:
  - `lifecycle_state:` OR `transition:` OR `parent_task_id:` OR `merged_into_task_id:`
- **Rule**: if any trigger is present, require `task_id:` to also be present.
- **Enforcement**:
  - Warnings now (W-TASKID-MISSING) until WOLFIE publishes token format + location rules.
  - Becomes blocking error (E-TASKID-MISSING) only after doctrine patch + rollout pass clears existing artifacts in active threads.

#### B) Prohibition on task/thread conflation

Deterministic checks only:

- **Rule**: a file must not treat `thread_id` as `task_id`.
- **Concrete checks**:
  - If YAML contains `task_id:` and `thread_id:` (frontmatter has `thread_id` already), ensure values are not equal *as strings* when `task_id` looks numeric-only (avoid false positives once task_id tokens are not numeric).
  - If marker `task_id:` is absent and `thread_id:` is used in a “task_id:” field (e.g. `task_id: 1001`), warn.
  - If body contains a deterministic statement line `task_id: {thread_id}`, warn.

#### C) Deterministic transition marker validation

- **Rule**: if `transition:` exists, it must match:
  - regex: `^transition:\s*(open|active|blocked|resolved|archived)\s*->\s*(open|active|blocked|resolved|archived)\s*$`
  - allowed transitions table = ATHENA §2.1
  - disallow `archived -> *`
- **Enforcement**:
  - Warning now (W-TRANSITION-INVALID / W-TRANSITION-MISSING)
  - Becomes blocking (E-TRANSITION-INVALID) when WOLFIE mandates marker presence in doctrine patch.

#### D) Split/merge required field validation

- **Split trigger**: presence of any of:
  - `split_reason:` OR `parent_task_id:` OR `child_task_id:`
- **Split required fields**:
  - `parent_task_id` + at least one `child_task_id`
  - if `child_thread_id` is present, it must be numeric-only
- **Merge trigger**: presence of any of:
  - `merged_into_task_id:` OR `merged_from_child_task_id:`
- **Merge required fields**:
  - `merged_into_task_id` + at least one `merged_from_child_task_id`
  - `merge_artifact` paths (if present) must look like `lupo-channels/<digits>/threads/<digits>/...md`
- **Enforcement**: warnings first; error after marker format is canon + at least one rollout cycle.

#### E) Legacy thread restrictions

Mechanically enforceable pieces:

- **Rule**: legacy thread directories may contain noncompliant historical artifacts; validators must not require retrofits (constitutional “no rewriting history”).
- **Concrete check**:
  - When validating thread directory `1001` and `1002`, limit enforcement to:
    - canonical filename pattern (already present for most)
    - ATER001 (already enforced for certain artifacts by kind)
    - marker-based checks only if the artifact itself declares markers (no retroactive requirements)

#### F) One active scope per thread artifact rule (ATHENA V-5)

Deterministic “single-file” enforcement:

- **Rule**: within one artifact file, there must not be multiple distinct `task_id:` marker values.
- **Check**:
  - collect all `task_id:` occurrences in YAML/body marker block; if >1 unique value => warn now, error later.

## 3. File-by-file implementation targets (real files only)

These are the **exact** files that would need edits to implement THREAD001 enforcement (no new files assumed in this plan).

### 3.1 Filesystem / CI validator

- **`lupo-scripts/validate_channel_artifacts.py`**
  - **Purpose**: extend from “structure + ATER001” into THREAD001 marker checks (task_id, transitions, split/merge, scope).
  - **Shape**:
    - add optional flags: `--enforce-thread001-markers` (warnings) and `--mode thread001_enforce` (blocking) OR equivalent additive mode (do not change existing `--mode enforce` semantics without WOLFIE directive).
    - add a parser for YAML frontmatter and body marker lines (simple regex; deterministic).

### 3.2 In-app path-based validator (PHP)

- **`lupo-includes/classes/ChannelArtifactValidator.php`**
  - **Purpose**: mirror a subset of THREAD001 checks for on-demand validation of filesystem artifacts.
  - **Current behavior**: only parses YAML to derive `message_type` and `artifact_kind`, then delegates to `Lupo_Channel_Artifact_Validator::validateThreadPostBody()` (ATER001).
  - **Planned change**: add a new method (or extend `validateThreadArtifact`) to validate marker blocks (warnings initially), without affecting API posting rules until WOLFIE approves.

### 3.3 API/router validators (PHP)

- **`lupo-includes/classes/Lupo_Channel_Artifact_Validator.php`**
  - **Purpose**: currently owns canonical filename regex, thread id strictness, and ATER001 body gates.
  - **Planned change**: add THREAD001 marker validators as *separate, opt-in* methods so API/router can adopt them behind explicit flags (no hidden behavior).

- **`lupo-includes/modules/api/channels-api.php`**
  - **Purpose**: POST gate currently enforces ATER001 for thread posts.
  - **Planned change**: only after policy is finalized, optionally enforce marker checks when `routing_type=thread` and `meta` declares marker enforcement (explicit), otherwise no change.

- **`lupo-includes/classes/Lupo_Channel_Message_Router.php`**
  - **Purpose**: runtime thread posting uses `validateThreadPostBody()` and DB thread existence (Option A).
  - **Planned change**: same as API: only opt-in marker enforcement; do not infer.

### 3.4 Tests + CI entrypoints

- **`lupo-tests/unit/channel_thread_review_body_test.php`**
  - **Purpose**: add unit tests for new marker parser/checks (pure string tests; no DB).
- **`lupo-scripts/run_unit_tests.sh`**
  - **Purpose**: already runs `validate_channel_artifacts.py --mode enforce`; plan adds a second, explicit invocation when THREAD001 marker enforcement is activated (not by default unless WOLFIE directs).

## 4. Error vs warning matrix (rule name → level → why → message shape)

Messages must be stable, grep-able, and path-addressed.

| Rule / check | Level now | Why now | Expected failure message shape |
|---|---:|---|---|
| ATER001 review body contract | **ERROR (block)** | already enforced API/router + CI | `THREAD_REVIEW_SHORT: <path> (body N chars, need 500+)` / `THREAD_REVIEW_SECTIONS: <path> (need 3+ ## headings, got N)` |
| ATER001 help_response body contract | **ERROR (block)** | already enforced API/router + CI | `THREAD_HELP_RESPONSE_SHORT: <path> (body N chars, need 200+)` / `THREAD_HELP_RESPONSE_H1: <path> ...` / `THREAD_HELP_RESPONSE_SECTIONS: <path> ...` |
| Numeric thread dir under `threads/` | **ERROR (block)** | structural integrity | `NON_NUMERIC_THREAD_DIR: lupo-channels/42/threads/<name>` |
| Canonical filename regex | **ERROR (block)** | structural integrity | `BAD_FILENAME: <relative_path>` |
| THREAD001 `task_id` required when transition/split/merge markers present | **WARN** | marker format not canon yet; avoid breaking existing work | `THREAD001_TASK_ID_MISSING: <path> (markers present: transition/lifecycle/split/merge require task_id)` |
| Task/thread conflation (deterministic) | **WARN** | avoid false positives until `task_id` token format is fixed | `THREAD001_TASK_THREAD_CONFLATION: <path> (task_id appears numeric/equal to thread_id)` |
| Transition marker regex + allowed transitions | **WARN** | marker not mandatory yet | `THREAD001_TRANSITION_INVALID: <path> (got "<raw>", allowed: open->active, ...)` |
| Hidden transitions (state changed without transition marker) | **DOCTRINE-ONLY** | needs canonical “state change declaration” format | (no validator message) |
| Split required fields | **WARN** | block only after marker format is finalized | `THREAD001_SPLIT_FIELDS_MISSING: <path> (need parent_task_id + child_task_id+)` |
| Merge required fields | **WARN** | same | `THREAD001_MERGE_FIELDS_MISSING: <path> (need merged_into_task_id + merged_from_child_task_id+)` |
| One active scope per artifact (multiple task_id markers) | **WARN** | depends on canonical marker format and rollout | `THREAD001_MULTIPLE_TASK_ID: <path> (task_ids: a,b,...)` |
| Legacy isolation / posting into archived legacy thread | **WARN** | requires explicit archive marker + waiver | `THREAD001_LEGACY_THREAD_POST: <path> (thread_id=1002 marked legacy; waiver missing)` |

## 5. Legacy handling (1001, 1002, future archives, waivers)

### 5.1 Thread `1001` (this thread)

Binding inputs:
- WOLFIE: thread 1001 is temporary triage container and becomes archived later.
- ATHENA: do not rewrite history; legacy exceptions exist.

Validator posture:
- **Do not treat 1001 as “archived legacy” yet** (it is actively used for doctrine correction work).
- Enforce only the already-existing structural checks + ATER001.
- THREAD001 marker checks apply only when an artifact opts into markers (presence-triggered warnings).

### 5.2 Thread `1002`

Validator posture:
- Treat as **legacy/mixed-scope historical** for now:
  - structural checks + ATER001 apply
  - THREAD001 markers: presence-triggered warnings only
- After WOLFIE issues an explicit archival directive, validators may add a “posting discouraged” warning gate for new artifacts in 1002 unless waived.

### 5.3 Future archived legacy threads

Validator requirement (deterministic):
- A thread becomes “archived legacy” for enforcement only when a **WOLFIE directive** exists that declares archival (ATHENA §1.5 and §2.1).
- Offline validators cannot consult DB; therefore archived/legacy enforcement must use:
  - a deterministic marker in new artifacts: `wolfie_waiver_artifact: <path>` when posting into a legacy thread, OR
  - a deterministic per-thread legacy list passed into validators (flag/config) maintained by WOLFIE (not created in this plan).

### 5.4 Explicit WOLFIE waivers

Validator rule:
- Waivers must be explicit and path-addressable.
- **Check** (offline-safe):
  - waiver path must match `lupo-channels/<digits>/threads/<digits>/[0-9]{8}_[0-9]{6}_wolfie_*.md`
  - waiver path must exist on disk (filesystem check only; no DB).
- Without waiver marker: warn (not error) until WOLFIE declares waiver mandatory.

## 6. CI / script integration (local, repo, gating)

### 6.1 Local validation

- Structural + ATER001 (current):
  - `python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --mode enforce`
- THREAD001 marker checks (planned, warnings initially):
  - `python lupo-scripts/validate_channel_artifacts.py ... --enforce-thread001-markers`

### 6.2 Repo validation (developer workflow)

- `sh lupo-scripts/run_unit_tests.sh .` already runs `validate_channel_artifacts.py --mode enforce` before PHP unit tests.
- Plan: add an **explicit additional step** for THREAD001 marker checks only when WOLFIE activates it (avoid hidden CI behavior changes).

### 6.3 Gating mode (blocking)

Gating transition must be explicit (constitutional “no hidden transitions”):

- Stage A (current): block on structural + ATER001 only (already in CI).
- Stage B (planned): treat THREAD001 marker checks as warnings (non-zero exit not triggered).
- Stage C (future, explicit WOLFIE directive): elevate specific THREAD001 checks to blocking errors (e.g. transition marker validity when present; split/merge required fields when declared).

## 7. Implementation sequence (dependency-ordered, no time language)

1. **Define canonical marker format** (WOLFIE doctrine patch step; this plan assumes YAML-first markers).
2. **Add marker parsing + warning-only checks in `validate_channel_artifacts.py`** (CI-visible, no runtime impact).
3. **Add unit tests** for marker parsing and each warning (pure filesystem/string tests).
4. **Mirror marker checks in `ChannelArtifactValidator.php`** as warning-returning codes (path-level PHP validation).
5. **Only after WOLFIE activation**: wire marker checks into API/router posting path as explicit opt-in (no silent behavior changes).

