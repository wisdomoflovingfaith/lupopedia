---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/7/threads/1011/20260318_180000_hephaestus_impl_project-awareness.md"
  questions_toon: null
  channel_id: 7
  thread_id: 1011
  task_id: "task_impl_002"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "implementation_report"
  purpose: "Project-aware validation: infer project context, V-PROJECT-001–004, path safety, project-N:path foundation; backward compatible"
  tags: ["task_impl_002", "project_awareness", "validators", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1009/20260318_144653_athena_strategy_project-layer-model.md", type: "implements", weight: 1.0 }
    - { to: "channels/42/threads/1010/20260318_175500_thoth_doc_project-layer-integration.md", type: "implements", weight: 1.0 }
    - { to: "scripts/validate_channel_artifacts.py", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
---
# file: HEPHAESTUS implementation — project awareness — channel 42 thread 1011

**Task:** task_impl_002  
**Role:** HEPHAESTUS (actor_id 14) — tooling implementation.

---

## 1. Project inference model

Deterministic context (no DB):

| Field | Source |
|-------|--------|
| `project_root` | `Path(--repo-root).resolve()` |
| `project_id` | CLI `--project-id` (default `0`), overridden by env `LUPO_PROJECT_ID` if numeric |
| `project_slug` | CLI `--project-slug` (default `lupopedia-core`), overridden by env `LUPO_PROJECT_SLUG` if set |

Emitted on **stderr** at startup:

```text
project_context: project_id=0 project_slug=lupopedia-core project_root=<absolute>
```

**API (same module):** `infer_project_context(repo_root, project_id=None, project_slug=None)` returns a dict with those three keys. **`parse_header_project_fields(frontmatter)`** returns `(optional_project_id, optional_project_slug)` from the first YAML block; omission is valid (defaults remain tooling-level `0` / slug).

---

## 2. Validator rule additions

All implemented in **`scripts/validate_channel_artifacts.py`**.

| Rule | Severity | Behavior |
|------|----------|----------|
| **V-PROJECT-001** | ERROR | Every scanned artifact path must resolve under `project_root` (symlink / layout escape fails). |
| **V-PROJECT-002** | ERROR | Markdown links `text` with a non-HTTP target that resolves **outside** `project_root` must use explicit **`project-<id>:<relative-path>`**; otherwise reported. |
| **V-PROJECT-003** | ERROR | `project-N:inner` must be repo-relative with no `..` segments in the inner path. |
| **V-PROJECT-004** | WARN | If frontmatter contains **`federation_node_id:`** or an edge **`to: project-N:...`**, and **`project_id:`** is absent in that frontmatter → warning. |

**Activation:** V-PROJECT-001 runs on **every** validated file always. V-PROJECT-002/003/004 and **TODO.md / plan.md** link scanning run only with **`--enforce-project`**.

---

## 3. Header parsing updates

- Optional **`project_id`** (integer) and **`project_slug`** (quoted or bare) are recognized in the first frontmatter block via regex.
- **Not required** anywhere; default behavior remains implicit project `0` at tooling level.
- V-PROJECT-004 uses the same block to detect missing `project_id` when structured multi-project signals appear (not filename noise such as `project-layer-model.md`).

---

## 4. Path enforcement logic

- Targets normalized: strip fragment, ignore `http(s):`, `mailto:`, `#`.
- **`project-(\d+):(.+)`** — inner path checked for `..`; resolved only for safety messaging, not cross-repo FS access.
- Relative links resolved from the **current .md file’s directory**; must stay under `project_root`.

**TODO.md / plan.md:** When `--enforce-project`, both files (if present at repo root) get V-PROJECT-001 + markdown link rules (project-scoped registry/roadmap).

---

## 5. Backward compatibility proof

| Guarantee | Mechanism |
|-----------|-----------|
| Default CLI unchanged | Same flags; **`--mode enforce`** does **not** imply `--enforce-project`. |
| Stdout shape | Issue lines and final `validate_channel_artifacts: N issue(s)...` unchanged; **`project_context`** and WARN hint go to **stderr**. |
| Strict exit code | Exit `1` only when **`--strict`** and at least one line is **not** `PROJECT_WARN[...]` (WARNs do not fail the build). |
| No schema / no rewrites | Python-only; no DB; no artifact edits. |
| Single-project repo | Without `--enforce-project`, behavior matches prior channel validation plus silent V-PROJECT-001 (always satisfied for normal tree layout). |

---

## 6. Example validations (before / after)

**Before (4.0.80-style):** No project line; no link-scope checks.

**After (default):**

```bash
python scripts/validate_channel_artifacts.py --repo-root . --channel 42 --strict
# stderr: project_context: project_id=0 ...
# stdout: same BAD_FILENAME / thread issues as before
```

**After (`--enforce-project`):**

- Link `x` → `PROJECT_ERROR[V-PROJECT-002]: ... escapes project root; use project-<id>:<path>`.
- Link `x` → allowed (cross-project **by convention**; path not opened on disk for project 1).
- Frontmatter edge `to: "project-1:..."` without `project_id:` → `PROJECT_WARN[V-PROJECT-004]`.

---

## 7. Success criteria checklist

- [x] Default `project_id` **0** and slug **`lupopedia-core`** when not overridden.
- [x] Validators enforce project boundaries when **`--enforce-project`** is set.
- [x] External AI model unchanged: one repo = one root; paths relative to GitHub tree.
- [x] No breaking changes to default runs or enforce mode.
- [x] Foundation for **`project-<id>:<path>`** without requiring it in headers yet.

---

_HEPHAESTUS (14) — task_impl_002 — ATHENA defined the layer; THOTH explained it; this makes it enforceable in tooling._
