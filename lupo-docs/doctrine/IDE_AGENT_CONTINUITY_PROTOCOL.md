---
lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md"
  system_version: "4.0.75"
  last_modified_utc: "20260315"
  channel_id: 42
  artifact_type: "doctrine"
  artifact_kind: "documentation"
  purpose: "Ensures IDE agent work is never lost; checkpoint, log, and hand off so any agent can resume."

lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260315"
  next_action: ["Keep in sync with lupo-logs/ layout and status artifact patterns"]
---

# IDE Agent Continuity Protocol (IACP)

## Purpose

The **IDE Agent Continuity Protocol (IACP)** ensures that work performed by IDE agents inside Lupopedia is never lost due to:

- token exhaustion
- quota errors
- process termination
- IDE crashes
- network interruptions
- agent switching

Every agent must continuously persist enough context so another agent can **resume work without relying on the original conversation thread**.

This protocol turns agent work into **durable artifacts inside the repository**.

---

## Core Principle

**An IDE agent session must never be the only location where work exists.**

All meaningful work must be continuously persisted into:

- `lupo-docs/status/`
- `lupo-logs/`
- TODO files

This guarantees that **Human + Agents + Repository** share a **common source of truth**.

---

## Continuity Architecture

The continuity system has four components:

```
Agent Work
     │
     ▼
Activity Logs   (lupo-logs/)
     │
     ▼
Status Artifacts (lupo-docs/status/)
     │
     ▼
Task Continuation (TODO files)
     │
     ▼
Next Agent Takeover
```

Each serves a different purpose.

| Layer  | Purpose                                 |
| ------ | --------------------------------------- |
| Logs   | chronological machine-readable activity |
| Status | human-readable summary of work          |
| TODO   | task continuation plan                  |
| Docs   | final durable output                    |

---

## Rule 1 — Continuous Activity Logging

Agents must append structured entries into **`lupo-logs/`** during all non-trivial work.

Logs must include:

- **timestamp** — Prefer BIGINT UTC `YYYYMMDDHHIISS` (e.g. `20260315163010`) for doctrine alignment; ISO8601 is acceptable if tooling requires it, but YmdHis is canonical for repository consistency.
- actor_id
- actor_name
- lupo_agent
- channel_id
- event_type
- file_path
- search_expression
- task_context
- notes
- handoff_from
- handoff_to

Example:

```json
{
  "timestamp": "20260315163010",
  "actor_id": 102,
  "actor_name": "jetbrains",
  "lupo_agent": "jetbrains",
  "channel_id": 42,
  "event_type": "file_reviewed",
  "file_path": "lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md",
  "task_context": "schema_reference_continuity",
  "notes": "Reviewing canonical reference before integrating improvements",
  "handoff_from": "antigravity",
  "handoff_to": null
}
```

Logs must be:

- append-only
- single-line JSONL
- machine readable

Logs should capture: files opened, files modified, grep/search operations, documentation research, schema exploration, decisions made.

---

## Rule 2 — Status Checkpoints

Every agent must periodically write a **checkpoint status artifact** into **`lupo-docs/status/`**. These are **human-readable summaries of work state**.

Status files should be created when:

- workstream begins
- major milestone reached
- agent token usage >80%
- agent preparing to hand off

Filename pattern: **`<AGENT>_<TASK>_STATUS_<VERSION>.md`**

Example: `JETBRAINS_TO_WINDSURF_SCHEMA_REFERENCE_CONTINUITY_HANDOFF_4_0_75.md`

Status artifacts must include:

- Executive Summary
- Files Reviewed
- Files Modified
- Decisions Made
- Open Questions
- Remaining Work
- Next Agent

---

## Rule 3 — TODO Handoff Files

When an agent is nearing termination or finishing a task, it must produce a task continuation file (e.g. **`TODO_windsurf.md`**).

TODO files contain:

- Context
- Completed Work
- Partially Completed Work
- Remaining Tasks
- Key Files
- Guardrails
- First Actions

These files allow the next agent to **resume work immediately**.

---

## Rule 4 — Automatic Token Threshold Checkpoints

Agents must checkpoint before token exhaustion.

| Token Usage | Required Action           |
| ----------- | ------------------------- |
| 70%         | write log checkpoint      |
| 85%         | write status artifact     |
| 90%         | create TODO handoff       |
| 95%         | write final takeover logs |

This prevents the situation where an agent dies without persisting state.

---

## Rule 5 — Chain of Custody

Every task must record its **agent chain of custody** (e.g. Antigravity → JetBrains → Windsurf).

Logs and status artifacts must include:

- prior_owner
- handoff_from
- handoff_to

Example:

```json
{
  "handoff_from": "jetbrains",
  "handoff_to": "windsurf",
  "prior_owner": "antigravity"
}
```

This allows any future agent to reconstruct the work history.

---

## Rule 6 — Repository Is the Source of Truth

Agent chat threads are **not considered durable storage**.

The only authoritative sources are:

- repository files
- logs
- status artifacts
- documentation

If work exists only in a conversation thread, it is considered **unsafe and incomplete**.

---

## Rule 7 — Doctrine Before Modification

Before modifying schema, documentation, or architecture, agents must review doctrine.

Required doctrine categories include:

- database doctrine
- collections doctrine
- federation doctrine
- session doctrine
- security doctrine

This prevents agents from introducing architecture drift.

---

## Rule 8 — Cross-Agent Resume Procedure

When an agent takes over a task, it must follow this order:

1. read doctrine
2. read status artifacts
3. read TODO files
4. inspect logs
5. inspect affected files
6. resume work

Agents must **never resume work blindly from prompts alone**.

---

## Rule 9 — Log Reconstruction

If an agent fails before writing logs, the takeover agent must reconstruct missing activity entries from:

- status artifacts
- file timestamps
- git history
- documentation updates

This ensures the activity trail remains complete.

---

## Rule 10 — Canonical Documentation Hierarchy

Agents must respect the documentation structure:

- install SQL → schema authority
- TOON files → generated structure
- tables/active → table documentation
- cross-domain docs → architectural explanation
- status docs → activity summaries
- logs → machine-readable history

No document should contradict the layers above it.

---

## Recommended Directory Layout

```
lupo-docs/
    doctrine/
    database/
    status/
    architecture/

lupo-logs/
    admin/
    activity/
    agents/
```

**Log location during transition:** Writing to `lupo-logs/admin/` is acceptable (e.g. takeover or handoff logs). For ongoing agent activity, prefer `lupo-logs/activity/` or `lupo-logs/agents/` when available so the trail stays organized. All locations under `lupo-logs/` are valid; consistency matters more than the exact subfolder.

---

## Benefits

| Problem              | Solution                           |
| -------------------- | ---------------------------------- |
| IDE crash            | logs + status allow reconstruction |
| token exhaustion     | automatic checkpoint rules         |
| agent switching      | TODO handoff files                 |
| unclear work history | chain-of-custody logs              |
| lost context         | repository persistence             |

---

## Final Principle

**Agents must assume they can disappear at any moment.**

Therefore: **All meaningful work must be written to the repository continuously.**

No agent should ever hold critical project state **only inside its own prompt context**.

---

**Doctrine active.** Applies to all IDE agents (Cursor, Windsurf, JetBrains, Antigravity, Kiro, Warp, etc.). For actor identity and pairing, see ACT001 and [lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md](../../lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md).
