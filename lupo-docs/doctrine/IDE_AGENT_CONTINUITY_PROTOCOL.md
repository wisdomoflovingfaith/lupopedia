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
  next_action: ["Keep in sync with lupo-logs/ layout and channel checkpoint artifact patterns"]
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

- `lupo-logs/`
- `lupo-channels/{channel_id}/` channel checkpoint artifacts (threads + tasks)
- task handoff notes (owned thread + channel tasks)

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
Channel Checkpoint Artifacts (lupo-channels/{channel_id}/threads + tasks)
     │
     ▼
Task Continuation / Handoff Notes (owned thread + channel tasks)
     │
     ▼
Next Agent Takeover
```

Each serves a different purpose.

| Layer  | Purpose                                 |
| ------ | --------------------------------------- |
| Logs   | chronological machine-readable activity |
| Checkpoint | channel thread/task checkpoint artifacts |
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

## Rule 2 — Channel Checkpoints (Thread/Task Artifacts)

Every agent must periodically publish a **checkpoint artifact** inside the owning channel context so another agent can resume work without relying on chat-time state.

A checkpoint should be created when:

- workstream begins
- major milestone reached
- agent token usage >80%
- agent preparing to hand off

Checkpoint artifact filename convention: **`YYYYMMDD_HHIISS_{actor}_{type}_{purpose}.md`** (see `lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`).
Checkpoint artifacts MUST include `lupopedia.headers.channel_id` and the relevant thread/task identity.

Checkpoint artifacts must include:

- Executive Summary
- Files Reviewed
- Files Modified
- Decisions Made
- Open Questions
- Remaining Work
- Next Agent

---

## Rule 3 — Task Handoff Notes

When an agent is nearing termination or finishing a task, it must leave resumable handoff notes in the channel system (owned thread + channel tasks).

Handoff notes should contain:

- Context
- Completed Work
- Partially Completed Work
- Remaining Tasks
- Key Files
- Guardrails
- First Actions

These notes allow the next agent to **resume work immediately**.

---

## Rule 4 — Automatic Token Threshold Checkpoints

Agents must checkpoint before token exhaustion.

| Token Usage | Required Action           |
| ----------- | ------------------------- |
| 70%         | write log checkpoint      |
| 85%         | publish channel checkpoint artifact |
| 90%         | ensure task handoff notes updated |
| 95%         | write final takeover logs |

This prevents the situation where an agent dies without persisting state.

---

## Rule 5 — Chain of Custody

Every task must record its **agent chain of custody** (e.g. Antigravity → JetBrains → Windsurf).

Logs and channel checkpoint artifacts must include:

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
- channel checkpoint artifacts
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
2. read channel checkpoint artifacts (owning thread/task)
3. read task handoff notes (owned thread + channel tasks)
4. inspect logs
5. inspect affected files
6. resume work

Agents must **never resume work blindly from prompts alone**. To leave work resumable for the next agent, publish a channel checkpoint / handoff artifact in the owning thread/task and append to logs with timestamp and actor_id; the next agent should read checkpoint artifacts and logs before changing the same areas.

---

## Rule 9 — Log Reconstruction

If an agent fails before writing logs, the takeover agent must reconstruct missing activity entries from:

- channel checkpoint artifacts
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
- channel thread/task checkpoint docs → activity summaries
- logs → machine-readable history

No document should contradict the layers above it.

---

## Recommended Directory Layout

```
lupo-docs/
    doctrine/
    database/
    architecture/

lupo-channels/
    {channel_id}/
    threads/
    tasks/

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
| IDE crash            | logs + channel checkpoints allow reconstruction |
| token exhaustion     | automatic checkpoint rules         |
| agent switching      | channel handoff notes in threads/tasks |
| unclear work history | chain-of-custody logs              |
| lost context         | repository persistence             |

---

## Final Principle

**Agents must assume they can disappear at any moment.**

Therefore: **All meaningful work must be written to the repository continuously.**

No agent should ever hold critical project state **only inside its own prompt context**.

---

**Doctrine active.** Applies to all IDE agents (Cursor, Windsurf, JetBrains, Antigravity, Kiro, Warp, etc.). For actor identity and pairing, see ACT001 and [lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md](../../lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md).
