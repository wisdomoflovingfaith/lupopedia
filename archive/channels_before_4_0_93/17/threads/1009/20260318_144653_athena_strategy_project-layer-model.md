---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/17/threads/1009/20260318_144653_athena_strategy_project-layer-model.md"
  questions_toon: null
  channel_id: 17
  thread_id: 1009
  task_id: "task_arch_001"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Canonical architecture: PROJECT as first-class layer above channels/threads/tasks; filesystem + DB alignment; external AI boundary"
  tags: ["athena", "project_layer", "task_arch_001", "architecture", "multi_project", "4.0.81"]
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "athena"
  orchestrator: "wolfie"
---
# file: ATHENA strategy — PROJECT layer model — channel 42 thread 1009

This output complies with Lupopedia Constitutional Root Rules.

## 0. Binding scope

This artifact defines the **canonical coordination architecture** for the PROJECT layer. It does **not** implement schema, modify `TODO.md`/`plan.md`, or rewrite historical artifacts.

**Evidence check (install SQL, not live DB):** `lupo_projects` exists with `project_id`, `project_key`, `project_slug`, `project_name`, `github_repository`, `default_channel_id`, `federation_node_id`. `lupo_channels` includes `project_id`. There is **no** `lupo_projects` entry in `database/lupopedia/toon/` at this time (TOON set may be incomplete vs install); regenerating TOONs is a separate tooling step. Conceptual field `repository_url` maps to persisted column **`github_repository`**. Conceptual **`project_root_path`** is not a column today; persistence options are future schema or `metadata_json` / doctrine-only until decided.

---

## 1. Hierarchy model (canonical)

**Strict containment (top → bottom):**

```
federation_node_id (optional outer scope for multi-node replication)
  +-- project_id
        +-- channel_id  (channels belong to exactly one project in this model)
              +-- thread_id  (dialog_thread_id; scoped to one channel)
                    +-- task_id  (stable work identity; execution artifacts live in the task’s thread)
```

**Deterministic rules:**

| Edge | Relationship |
|------|----------------|
| project → channel | Every `channel_id` used for coordination **MUST** resolve to a single `project_id` (DB: `lupo_channels.project_id`; implicit default `0` only until registry row exists). |
| channel → thread | Every `thread_id` under `channels/{channel_id}/threads/{thread_id}/` **MUST** belong to that `channel_id` only. |
| project → task | **Logical:** every `task_id` in root `TODO.md` / `plan.md` **MUST** be interpreted as scoped to exactly one `project_id` (default today: implicit `0` / `lupopedia-code`). |
| task → thread | **Execution:** each active task **MUST** map to at most one primary `thread_id` at a time (per THREAD001 / Option A). |

**Ordering of inference:** Given a filesystem path under a repo root, **project** is inferred first (repo root + registry), then **channel** from `channels/{channel_id}/`, then **thread** from `threads/{thread_id}/`, then **task** from headers / registry.

---

## 2. Project identity

**What is a project?**  
A **project** is **both**:

1. **Namespace / registry object** — a row in `lupo_projects` (or equivalent) with stable `project_id`, unique slug/key per federation node.  
2. **Filesystem boundary** — the **repository root** on disk where `channels/`, `TODO.md`, `plan.md`, and doctrine live for that codebase.

GitHub (or other host) is the **external read mirror** of that filesystem boundary for agents that do not mount the DB.

**Required fields (canonical model):**

| Field | Role |
|-------|------|
| `project_id` | Stable BIGINT; application-assigned; no AUTO_INCREMENT (reserved-ID doctrine). |
| `project_slug` | URL-safe unique identifier per node (e.g. `lupopedia-code`). |
| `project_name` | Human display name. |
| `project_root_path` | **Conceptual:** absolute or workspace-relative root of the repo clone (doctrine / tooling; not necessarily a DB column today). |
| `repository_url` | **Conceptual:** canonical Git remote URL; **DB column today:** `github_repository`. |

**Additional persisted fields already in install SQL (normative for DB-backed apps):** `project_key`, `federation_node_id`, `default_channel_id`, `orchestrator_id`, status flags, timestamps.

---

## 3. File-system reality

- **All coordination artifacts** under `channels/{channel_id}/...` exist **inside one project root** (one clone / one repo tree).  
- **Channels and threads are not global** — they are **project-scoped**: the path `channels/17/threads/1009/` is read as **project P’s channel 42, thread 1009**.  
- **GitHub** is the **external read interface** for the same tree: external agents see the same paths relative to repo root.  
- **DB** may store `project_id` on `lupo_channels` and related rows; **filesystem** does not repeat `project_id` in every path — inference is by repo root + registry.

---

## 4. External AI model

- External agents (ChatGPT, Grok, DeepSeek, etc.) **read the repository** via GitHub (or zip/export); they **do not** query MySQL.  
- **Project = their boundary:** all paths and headers they see are under one repo; they **MUST** treat that repo as one `project_id` (default implicit until multi-repo onboarding docs say otherwise).  
- **Channels/threads** are **inferred from filesystem layout**: `channels/{channel_id}/threads/{thread_id}/` + LUPOPEDIA HEADERS (`channel_id`, `thread_id`, `task_id`).  
- **TODO.md / plan.md** at repo root are the **task registry and roadmap for that project only** (TSK001 applies per clone).

---

## 5. Multi-project future

- **Multiple projects** → multiple `project_id` values, each with its own `project_slug`, optional `github_repository`, and **disjoint or federated** filesystem trees.  
- **Multiple repos** → one project per repo by default; monorepo could map one project to one root with many channels.  
- **Federation** → `federation_node_id` scopes `project_slug` / `project_key` uniqueness; cross-project references are **explicit** (artifact paths + `project_id` in headers when crossing boundaries).  
- **Scaling rule:** no implicit cross-project channel or thread identity; collision of numeric `thread_id` across projects is allowed **only** because threads are namespaced by `(project_id, channel_id, thread_id)`.

---

## 6. Integration with current system

| Surface | Integration |
|---------|--------------|
| **TODO.md** | Global Task Registry **for the current project** (implicit `project_id` today). Future: optional column or section `project_id` if one clone tracks multiple projects (advanced). |
| **plan.md** | Strategic roadmap **for the current project**; phase `registry_links` reference `task_id` within the same project. |
| **Threads** | `channels/{channel_id}/threads/{thread_id}/` under project root; headers SHOULD remain consistent with `lupo_channels.project_id` when DB is authoritative. |
| **Channels** | `channel_id` is unique within a project context; channel 42 remains default **dev workspace for this project**. |

---

## 7. What changes (IMPORTANT)

**Required (coordination / documentation doctrine — not done in this artifact):**

- Explicit statement in onboarding and multi-agent doctrine: **every agent assumes a project context** (default `project_id: 0` / `lupopedia-code` until registry seed defines otherwise).  
- Optional: seed or document canonical row for `lupopedia-code` linking `default_channel_id: 42`.

**Unchanged:**

- Channel-based coordination, Option A threads, task/thread separation, `TODO.md` registry spec, `plan.md` roadmap spec.  
- Install SQL table names and core channel/thread mechanics.

**`project_id` in headers:**

- **SHOULD** appear when artifacts are **federation-relevant** or **multi-project**; **MAY** be omitted for single-project default workspace to reduce noise.  
- **MUST** appear when an artifact references another project’s path or when tooling cannot infer project from repo root.

**Schema / TOON:**  
No install SQL changes in this step. Aligning column names (`repository_url` vs `github_repository`) or adding `project_root_path` to `lupo_projects` is a **separate** VISHWAKARMA + HEPHAESTUS migration with PHP/TOON updates — out of scope here.

---

## 8. What NOT to do

- Do **not** implement or alter database schema from this artifact alone.  
- Do **not** rewrite or edit legacy thread artifacts.  
- Do **not** treat `thread_id` or `channel_id` as globally unique without `project_id` (or repo) context.  
- Do **not** use non-deterministic identifiers (random UUIDs) for `project_id`.  
- Do **not** collapse **project** into **channel** or **task** — all three layers remain distinct.

---

_ATHENA (actor_id 12) — canonical PROJECT layer architecture for task_arch_001._
