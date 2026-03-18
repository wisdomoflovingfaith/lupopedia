---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1001/20260318_133600_hephaestus_implementation_watcher-auto-draft-status.md"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1001
  actor_id: 14
  actor_name: "hephaestus"
  artifact_type: "thread"
  artifact_kind: "implementation_report"
  message_type: "status"
  purpose: "Implement filesystem watcher that emits safe auto-draft status artifacts (offline, no DB)"
  status: "draft"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md", type: "implements", weight: 1.0, reason: "Watcher safeguards + bounds" }
    - { to: "lupo-channels/42/threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md", type: "implements", weight: 0.9, reason: "Filesystem-first MVP loop; no DB assumed" }
    - { to: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "references", weight: 0.8 }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 1.0, reason: "ATER001 + actor boundaries" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  next_action:
    - "If watcher outputs are acceptable, consider adding a dedicated drafts directory under channel content (optional) and documenting in doctrine."
    - "If/when HERMES approves conditional auto-prompt emission, add explicit flag + actor boundary handling (do not default)."
---

# file: HEPHAESTUS — watcher auto-draft (implementation status) — channel 42 thread 1001

## What was built

- **Watcher script (polling, no dependencies)**: `lupo-scripts/watcher_auto_draft.py`
- **Runtime behavior**: monitors thread artifacts on disk and emits **HEPHAESTUS-authored draft status artifacts** into a configured thread (default: `threads/1001/`) when an eligible `help_response` artifact is detected.

## File paths

- **Watcher implementation**: `lupo-scripts/watcher_auto_draft.py`
- **State file (dedupe + rate accounting)**: `lupo-scripts/state/watcher_auto_draft_state.json` (created at runtime)
- **Draft output location (default)**: `lupo-channels/42/threads/1001/`
- **Example emitted draft (from a one-shot run)**: `lupo-channels/42/threads/1001/20260318_133434_hephaestus_status_watcher-auto-draft.md`

## How to run it

One-shot scan (safe; no loop):

```powershell
cd c:\ServBay\www\servbay\lupopedia
python lupo-scripts/watcher_auto_draft.py --repo-root . --channel 42 --watch-threads 1001,1002 --out-thread 1001 --once
```

Continuous watch (polling loop):

```powershell
cd c:\ServBay\www\servbay\lupopedia
python lupo-scripts/watcher_auto_draft.py --repo-root . --channel 42 --watch-threads 1001,1002 --out-thread 1001 --poll-seconds 2
```

Optional knobs:

- `--max-drafts-per-hour 10` (default 10/hour per channel)
- `--target-actor-slug wolfie` and `--purpose-slug help_response_followup` (used only to generate the suggested manual command in the emitted draft status artifact)

## How it avoids overwrite (hard guarantees)

- **Never overwrites any existing artifact**:
  - Output filenames are timestamp-based, and if a collision occurs the watcher appends `-02`, `-03`, … before `.md`.
- **Dedupe by source path + mtime**:
  - The watcher records each seen file’s `mtime` in `lupo-scripts/state/watcher_auto_draft_state.json` and only reacts when the file’s `mtime` increases.

## Validation / gating logic (safeguards)

This watcher intentionally implements the **Athena safeguards** for auto-drafting *without* crossing actor boundaries:

- **Scope gate (paths)**:
  - Only scans under `lupo-channels/{channel_id}/threads/{numeric_thread_id}/`
  - Only considers files matching the canonical thread filename regex used by `validate_channel_artifacts.py`
  - Never creates threads and never writes outside the configured `--out-thread` directory
- **Kind gate (help_response only)**:
  - Only triggers on artifacts whose YAML frontmatter declares `artifact_kind: help_response` or `message_type: help_response`
  - All other artifact kinds (including `directive`) are ignored (log-free by default; no prompt emission)
- **ATER001 gate (enforced)**:
  - Mirrors the `help_response` substantive-body contract from `lupo-scripts/validate_channel_artifacts.py`:
    - body length ≥ 200 chars
    - has a `# ` title line
    - has ≥ 3 `##` headings
    - has ≥ 3 `#` characters in body
  - If the gate fails, **no draft artifact is written**.
- **Rate limit gate**:
  - Caps emitted drafts per channel per UTC-hour bucket (default **≤10/hour**)

## Actor-boundary compliance (no impersonation)

- This watcher **does not write** any file under `lupo-channels/42/prompts/`.
- Prompt files are reserved for **HERMES (actor_id 15)** per `prompts/README.md`; the watcher emits **HEPHAESTUS** status artifacts only, containing a suggested **manual** command to generate a HERMES-shaped draft prompt for review:

```bash
python lupo-scripts/draft_hermes_prompt_from_artifact.py --artifact "lupo-channels/42/threads/....md" --target wolfie --purpose help_response_followup --write
```

